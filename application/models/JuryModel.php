<?php

class JuryModel extends CI_Model{

    public function __construct(){
    }

    public function get($where, $table){
        return $this->db->where($where)->get($table)->row();
    }

    public function getAll($table) {
        return $this->db->get($table)->result();
    }

    public function getAllPositions() {
        return $this->db->get('positions')->result();
    }

    public function getAllBeReviewed($id = null, $userId = null) 
    {
        $this->db->select('
            a.id AS application_id,
            a.user_id,
            a.ads_id,
            a.application_date,
            a.created_at,
            a.status,
            ads.title AS ad_title,
            users.name AS user_name,
            users.surname AS user_surname,
            status.title AS status_title
        ');
        $this->db->from('application a');
        $this->db->join('ads', 'ads.id = a.ads_id', 'left');
        $this->db->join('users', 'users.id = a.user_id', 'left');
        $this->db->join('status', 'status.id = a.status', 'left');
        $this->db->where_in('a.status', [4, 5]);

        if (!empty($id)) {
            $this->db->where('a.id', $id);
        }

        $applications = $this->db->get()->result_array();

        // Eğer $userId parametresi varsa: reviews'a bak, eşleşenleri çıkar
        if (!empty($userId)) {
            $this->db->select('application_id');
            $this->db->from('reviews');
            $this->db->where('user_id', $userId);
            $reviewedApps = array_column($this->db->get()->result_array(), 'application_id');
        } else {
            $reviewedApps = [];
        }

        // Filtreleme
        $filteredApps = [];
        foreach ($applications as $app) {
            if (!in_array($app['application_id'], $reviewedApps)) {
                // Dokümanları al
                $this->db->where('application_id', $app['application_id']);
                $documents = $this->db->get('documents')->result_array();
                $app['documents'] = $documents;

                $filteredApps[] = $app;
            }
        }

        return $filteredApps;
    }


    public function getAllDepartments() {
        return $this->db->get('deps')->result();
    }

    public function selectFromRules($select) {
        return $this->db->select($select)->get('rules')->result();
    }

    public function add($table, $data)
    {
        return $this->db->insert($table, $data);
    }

    public function delete($table,$id) {
        $this->db->where('id', $id);
        return $this->db->delete($table);
    }

    public function checkReviewStatus($id){
        $this->db->select('user_id');
        $this->db->from('reviews');
        $this->db->where('application_id', $id);
        $query = $this->db->get();
        $reviewUserIds = array_column($query->result_array(), 'user_id');
        $this->db->select('ads_id');
        $this->db->from('application');
        $this->db->where('id', $id);
        $adsRow = $this->db->get()->row();

        if (!$adsRow) {
            return false;
        }

        $adsId = $adsRow->ads_id;

        $this->db->select('user_id');
        $this->db->from('ads_juries');
        $this->db->where('ads_id', $adsId);
        $juryUserIds = array_column($this->db->get()->result_array(), 'user_id');

        $reviewUserIds = array_unique($reviewUserIds);
        sort($reviewUserIds);
        sort($juryUserIds);
        if ($reviewUserIds === $juryUserIds) {
            $this->db->where('id', $id);
            $this->db->update('application', ['status' => 5]);
            $this->calculateScore($id);
        }

    }

    public function calculateScore()
    {
        $applications=$this->getAll('application');
        foreach ($applications as $app) {
            $this->processReviewScores();
        }
    }

    private function extractTotalLimitsFromRule($scorerulesText)
    {
        $min = null;
        $max = null;

        // Dış parantez dışı TOPLAM= min kabul edilir
        if (preg_match('/\bTOPLAM=(\d+)\b(?![^\(]*\))/', $scorerulesText, $match)) {
            $min = (int)$match[1];
        }

        // Parantez içindeki TOPLAM= max kabul edilir
        if (preg_match('/\(\s*[^)]*TOPLAM=(\d+)[^)]*\)/', $scorerulesText, $match)) {
            $max = (int)$match[1];
        }

        return ['min' => $min, 'max' => $max];
    }


    public function checkPaperRules($applicationId, $paperRulesText)
    {
        if (empty($paperRulesText)) return true;
    
        // Dökümanları al
        $documents = $this->db->where('application_id', $applicationId)->get('documents')->result_array();
        if (empty($documents)) return false;
    
        // Verileri dönüştür
        $categories = [];
        $hasBY = false;
        $totalDocs = 0;
    
        foreach ($documents as $doc) {
            $totalDocs++;
    
            // rules = A1,BY=T,P=30
            $parts = explode(',', $doc['rules']);
            $category = null;
            $by = null;
    
            foreach ($parts as $part) {
                $kv = explode('=', $part);
                $key = trim($kv[0]);
                $value = $kv[1] ?? '';
    
                if ($key === 'BY' && strtoupper($value) === 'T') {
                    $hasBY = true;
                } elseif (preg_match('/^A\d+$/', $key)) {
                    $category = $key;
                }
            }
    
            if ($category) {
                $categories[] = $category;
            }
        }
    
        // Kuralları yorumla: A1-A2=1, BY=1, TM=1
        $rules = array_map('trim', explode(',', $paperRulesText));
        foreach ($rules as $rule) {
            if (preg_match('/^A(\d+)-A(\d+)=\d+$/', $rule, $match)) {
                $range = range((int)$match[1], (int)$match[2]);
                $found = false;
                foreach ($categories as $cat) {
                    $catNum = (int)substr($cat, 1);
                    if (in_array($catNum, $range)) {
                        $found = true;
                        break;
                    }
                }
                if (!$found) return false;
    
            } elseif (preg_match('/BY=1/', $rule)) {
                if (!$hasBY) return false;
    
            } elseif (preg_match('/TM=(\d+)/', $rule, $match)) {
                if ($totalDocs < (int)$match[1]) return false;
            }
        }
    
        return true;
    }
    

    public function processReviewScores()
    {
        $this->load->database();
        $reviews = $this->db->get('reviews')->result_array();
        $scoreMap = [];

        // 1. Approved review'ların skorlarını topla
        foreach ($reviews as $review) {
            $appId = $review['application_id'];
            if (!isset($scoreMap[$appId])) {
                $scoreMap[$appId] = 0;
            }
            $scoreMap[$appId] += (int)$review['score'];
        }

        foreach ($scoreMap as $applicationId => $totalScore) {
            // 2. Application ve Ads bilgileri
            $application = $this->db->where('id', $applicationId)->get('application')->row_array();
            if (!$application) continue;

            $ad = $this->db->where('id', $application['ads_id'])->get('ads')->row_array();
            if (!$ad) continue;

            // 3. Kuralları al
            $rule = $this->db->where('position_id', $ad['position_id'])
                            ->where('department_id', $ad['department_id'])
                            ->get('rules')->row_array();
            if (!$rule) continue;

            // 4. scorerules kontrolü
            $scoreIsValid = true;
            if (!empty($rule['scorerules'])) {
                $limits = $this->extractTotalLimitsFromRule($rule['scorerules']);

                if ($limits['min'] !== null && $totalScore < $limits['min']) {
                    $scoreIsValid = false;
                }

                if ($limits['max'] !== null && $totalScore > $limits['max']) {
                    $scoreIsValid = false;
                }
            }

            // 5. paperrules kontrolü
            $paperIsValid = $this->checkPaperRules($applicationId, $rule['paperrules'] ?? '');

            // 6. Her iki rule sağlanıyorsa status = true
            $finalStatus = ($scoreIsValid && $paperIsValid);
            $scoreStatus = $finalStatus ? 1 : 0;
            $applicationStatus = $finalStatus ? 6 : 7;

            // 7. scores tablosuna insert veya update
            $existing = $this->db->where('application_id', $applicationId)->get('scores')->row_array();
            if ($existing) {
                $this->db->where('application_id', $applicationId)
                        ->update('scores', ['score' => $totalScore, 'status' => $scoreStatus]);
            } else {
                $this->db->insert('scores', [
                    'application_id' => $applicationId,
                    'score' => $totalScore,
                    'status' => $scoreStatus
                ]);
            }

            // 8. application tablosunu güncelle
            $this->db->where('id', $applicationId)
                    ->update('application', ['status' => $applicationStatus]);
        }
    }

    
    public function update($table, $data, $where)
    {
        $this->db->where($where);
        return $this->db->update($table, $data);
    }

    public function getRules($position_id, $department_id){
        return $this->db->where('department_id', $department_id)->get('rules')->row();
    }

    public function getExtension($extension){
        return $this->db->where('title', $extension)->get('types')->row()->id ?? -1;
    }
}
?>