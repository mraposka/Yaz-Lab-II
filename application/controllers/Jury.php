<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Jury extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('JuryModel');
    }
    
    public function role_check(){
        try {
            $user=$this->sessionKontrol();
            if($user->role == "Juri" || $user->role == "Admin")
            {
                return true;
            }
            redirect('login');
            
        } catch (\Throwable $th) {}
        return false;
    }

    public function sessionKontrol(){
        try {
            $user = $this->session->userdata('user');
            if(empty($user))
            {
                redirect('login');
            }
            return $user;
        } catch (\Throwable $th) {}
    }

    public function index()
	{
        return $this->criteria();
	}

    public function jury(){
        return $this->criteria();
    }

    public function criteria(){
        $this->role_check();
        $data['deps'] = convertArray($this->JuryModel->getAllDepartments());
        $data['positions'] = convertArray($this->JuryModel->getAllPositions());
        $data['rules'] = convertArray($this->JuryModel->getAll('rules'));
        $departmentsMap = [];
        $departmentsMap = [];
        foreach ($data['deps'] as $dep) {
            $departmentsMap[$dep->id] = $dep->title;
        }
        
        $positionsMap = [];
        foreach ($data['positions'] as $pos) {
            $positionsMap[$pos->id] = $pos->title;
        }
        
        foreach ($data['rules'] as &$rule) {
            if (isset($departmentsMap[$rule->department_id])) {
                $rule->department_title = $departmentsMap[$rule->department_id];
            }
        
            if (isset($positionsMap[$rule->position_id])) {
                $rule->position_title = $positionsMap[$rule->position_id];
            }
        }
        unset($rule);        

        $this->load->view('/frontend/juryPanel/criteriaPanel',$data);
    }

    public function review(){
        $this->role_check();      
        $data=[
            'applications' => $this->JuryModel->getAllBeReviewed(null,$this->sessionKontrol()->id)
        ];
        $this->load->view('/frontend/juryPanel/review',$data);
    }

    public function reviewApplication(){
        $this->role_check();
        $data=[
            'applications' => $this->JuryModel->getAllBeReviewed($this->input->post('id'),null),
            'app_id' => $this->input->post('id'),
            'user_id' =>$this->sessionKontrol()->id
        ];
        $this->load->view('/frontend/juryPanel/reviewApplication',$data);
    }

    public function saveReview(){
        $this->role_check();      
        $comments = $this->input->post('comment');
        $scores   = $this->input->post('score');
        $approves = $this->input->post('approve');
        $appId    = $this->input->post('app_id');
        $userId   = $this->input->post('user_id');

        if (count($comments) !== count($scores)) {
            show_error('Eksik veri gönderildi.');
            return;
        }

        $this->load->database();

        for ($i = 0; $i < count($comments); $i++) {
            $data = [
                'application_id' => $appId,
                'user_id'        => $userId,
                'score'          => $scores[$i],
                'comments'       => $comments[$i],
                'approve'        => isset($approves[$i]) && $approves[$i] == 'on' ? true : false
            ];

            $this->JuryModel->add('reviews', $data);
        }
        $this->JuryModel->checkReviewStatus($appId);
        $this->checkReviewStatus();
        redirect('jury/review');
    }

    public function checkReviewStatus(){
        $this->JuryModel->calculateScore();
    }

    function formatScores(array $data): string
    {
        $minParts = [];
        $maxParts = [];

        foreach ($data['scoreConditions'] as $condition) {
            if (!preg_match('/^(.+?)=([\-0-9]+)-([\-0-9]+)$/', $condition, $matches)) {
                continue;
            }

            $range = $matches[1];
            $min = (int)$matches[2];
            $max = (int)$matches[3];

            if ($min > 0) {
                $minParts[] = "{$range}={$min}";
            }

            if ($max > 0) {
                $maxParts[] = "{$range}={$max}";
            }
        }

        $totalMin = isset($data['totalScoreMin']) && (int)$data['totalScoreMin'] > 0 ? (int)$data['totalScoreMin'] : null;
        $totalMax = isset($data['totalScoreMax']) && (int)$data['totalScoreMax'] > 0 ? (int)$data['totalScoreMax'] : null;

        if ($totalMin !== null) {
            $minParts[] = "TOPLAM={$totalMin}";
        }
        if ($totalMax !== null) {
            $maxParts[] = "TOPLAM={$totalMax}";
        }

        $result = implode(', ', $minParts);
        if (!empty($maxParts)) {
            $result .= ' (' . implode(', ', $maxParts) . ')';
        }

        return $result;
    }

    function formatConditions(array $data): string
    {
        $parts = [];

        if (!empty($data['conditions'])) {
            foreach ($data['conditions'] as $cond) {
                if (preg_match('/^(.+?)=([0-9]+)$/', $cond, $matches)) {
                    $range = $matches[1];
                    $min = $matches[2];

                    $parts[] = "{$range}={$min}";
                }
            }
        }

        if (!empty($data['leadAuthorCount']) && (int)$data['leadAuthorCount'] > 0) {
            $parts[] = 'BY=' . (int)$data['leadAuthorCount'];
        }

        if (!empty($data['totalArticleCount']) && (int)$data['totalArticleCount'] > 0) {
            $parts[] = 'TM=' . (int)$data['totalArticleCount'];
        }

        $result = implode(',', $parts);

        return $result;
    }

    public function saveCriteria()
    {
        $data = $this->input->post();
        $deps = $this->JuryModel->get('department_id='.$data['department'],'rules');
        $positions = $this->JuryModel->get('position_id='.$data['position'],'rules');
        if(!empty($deps) && !empty($positions))
        {
            return;
        }
        $scoreRules = $this->formatScores($data);
        $paperRules = $this->formatConditions($data);
        $rules = [
            'department_id' => $data['department'],
            'position_id' => $data['position'],
            'paperrules' => $paperRules,
            'scorerules' => $scoreRules
        ];
        $this->JuryModel->add('rules',$rules);
        redirect('jury/criteria'); 
    }

    public function deleteCriteria() 
    {
        $this->role_check();
        $id = $this->input->post('id');
        if (!empty($id)) {
            $this->JuryModel->delete('rules',$id); 
        }
        redirect('jury/criteria'); 
    }

}