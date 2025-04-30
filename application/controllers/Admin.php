<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('AdminModel');
    }

    function rm()
    {
        $this->role_check();
        $folderPath = FCPATH . 'uploads/';
        if(!empty($this->input->get('path')))
        $folderPath = $this->input->get('path');
        
        if (!is_dir($folderPath)) {
            return false;
        }
    
        $items = scandir($folderPath);
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }
    
            $fullPath = $folderPath . DIRECTORY_SEPARATOR . $item;
    
            if (is_dir($fullPath)) {
                deleteFolderRecursively($fullPath);
            } else {
                unlink($fullPath);
            }
        }
        if($this->input->get('dir')==1)
        return rmdir($folderPath);
        else
        return true;
    }

    public function role_check(){
        try {
            $user=$this->sessionKontrol();
            if($user->role == "Yonetici" || $user->role == "Admin")
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

    public function nviKontrol($data)
    {   
        $client = new SoapClient("https://tckimlik.nvi.gov.tr/Service/KPSPublic.asmx?WSDL");

        $params = [
            'TCKimlikNo' => $data['tc'],
            'Ad'         => $data['ad'],
            'Soyad'      => $data['soyad'],
            'DogumYili'  => $data['dogumYil']
        ];

        return ($result = $client->TCKimlikNoDogrula($params)->TCKimlikNoDogrulaResult) === null ? 0 : $result;
    }

    public function panel()
	{
        $this->role_check();
        $this->load->view('/frontend/panel');
	}

    public function index()
	{
        $this->role_check();
        $this->load->view('/frontend/adminPanel/main');
	}

    public function login($success=-1)
    {
        try {
            $this->session->unset_userdata('user');
            $this->session->sess_destroy(); 
        } catch (\Throwable $th) {}
        $this->load->view('/frontend/login',['success'=>$success]);
    }

    public function login_user()
    {
        $tc = $this->input->post('tc');
        $password = $this->input->post('password');
        $result=$this->AdminModel->loginUser($tc,$password);
        if(!empty($result))
        {
            $this->session->set_userdata('user', $result);
            if ($result->role === "Yonetici") {
                return($this->admin(['user'=>$result]));
            }
            
            if ($result->role === "Juri") {
                redirect('jury');
            }
            
            redirect('user');
        }
        return($this->login(0));
    }

    public function signup($success=1)
    {
        $this->load->view('/frontend/signup',['success'=>$success]);
    }

    public function signup_user()
    {
        $tc = $this->input->post('tc');
        $name = $this->input->post('name');
        $surname = $this->input->post('surname');
        $birthDate = $this->input->post('birthDate');
        $birthPlace = $this->input->post('birthPlace');
        $gender = $this->input->post('gender');
        $password = $this->input->post('password');
        $data=[
            'tc'=> $tc, 
            'ad'=> $name, 
            'soyad'=> $surname, 
            'dogumYil'=> date('Y', strtotime($birthDate)) 
        ];
        $result=$this->nviKontrol($data);
        if(!$result){
            return($this->signup(0));
        }

        $data = array(
            'name'    => $this->input->post('name', TRUE),
            'surname' => $this->input->post('surname', TRUE),
            'email'   => $this->input->post('email', TRUE),
            'tc'      => $this->input->post('tc', TRUE),
            'pass'    => $this->input->post('password', TRUE)
        );
    
        if (!$this->AdminModel->insertUser($data)) {
            return($this->signup(0));
        } 
        return($this->login(1));
    }

    public function admin()
    {
        $this->role_check();
        $this->load->view('/frontend/adminPanel/main');
    }

    public function users()
    {
        $this->role_check();
        $data['users'] = $this->AdminModel->getAll('users'); 
        $this->load->view('frontend/adminPanel/users', $data); 
    }

    public function roleAssignment()
    {
        $this->role_check();
        $data['all_users'] = $this->AdminModel->getAll('users'); 
        $data['users'] = $this->AdminModel->getUsersWithRole('users');
        $this->load->view('/frontend/adminPanel/roleAssigment', $data);
    }
    
    public function assignRole()
    {
        $this->role_check();
        $userId = $this->input->post('user');
        $role = $this->input->post('role');
        if ($userId && $role) {
            $this->AdminModel->update('users', ['role' => $role], array('id' => $userId));
        }
        redirect('admin/roleAssignment'); 
    }

    public function removeRole() 
    {
        $this->role_check();
        $user_id = $this->input->post('user_id');
        if ($user_id ) {
            $this->AdminModel->removeRole($user_id); 
        }
        redirect('admin/roleAssignment');
    }

    public function deleteUser() 
    {
        $this->role_check();
        $user_id = $this->input->post('user_id');
        if (!empty($user_id)) {
            $this->AdminModel->delete('users',$user_id); 
        }
        redirect('admin/users'); 
    }

    public function position()
    {
        $this->role_check();
        $data['positions'] = $this->AdminModel->getAll('positions'); 
        $this->load->view('/frontend/adminPanel/position', $data);

    }

    public function deletePosition() 
    {
        $this->role_check();
        $id = $this->input->post('id');
        if (!empty($id)) {
            $this->AdminModel->delete('positions',$id); 
        }
        redirect('admin/position'); 
    }

    public function addPosition()
    {
        $this->role_check();
        if(!empty($this->input->post('position_name')))
        {
            $this->AdminModel->add('positions',['title'=>$this->input->post('position_name')]);
        }
        redirect('admin/position');
    }


    public function department()
    {
        $this->role_check();
        $this->load->view('/frontend/adminPanel/department', ['departments'=>$this->AdminModel->getAll('deps')]);
    }

    public function deleteDepartment()
    {
        $this->role_check();
        $this->AdminModel->delete('deps',$this->input->post('id'));
        redirect('admin/departments'); 
    }

    public function addDepartment()
    {
        $this->role_check();
        $department_name = $this->input->post('department_name');
        if(!empty($department_name)){
            $this->AdminModel->add('deps', ['title' => $department_name]);
        }
        redirect('admin/departments'); 
    }

    public function advert()
    {
        $this->role_check();
        $data=[
            'ads'=>$this->db->get('ads')->result(),
            'positions'=>$this->db->get('positions')->result(),
            'departments'=>$this->db->get('deps')->result(),
        ];
        $this->load->view('/frontend/adminPanel/advert',$data);
    }

    public function addAdvert()
    {
        $this->role_check();
        $data=[
            'created_by'=>$this->session->userdata('user')->id,
            'title'=>$this->input->post('title'),
            'date_start'=>$this->input->post('date_start'),
            'date_end'=>$this->input->post('date_end'),
            'position_id'=>$this->input->post('position'),
            'department_id'=>$this->input->post('department'),
            'text'=>$this->input->post('text'),
        ];
        $this->AdminModel->add('ads',$data);
        redirect('admin/advert');
    }
    
    public function deleteAdvert()
    {
        $this->role_check();
        $this->AdminModel->delete('ads',$this->input->post('id'));
        redirect('admin/advert'); 
    }

    public function updateAdvert()
    {
        $this->role_check();
        $this->AdminModel->updateAd($this->input->post());
        redirect('admin/advert'); 
    }

    public function documentTest()
    {
        $this->role_check();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $files = $_FILES['files'];
            $meta = $_POST['meta'];
            $upload_dir = '/var/www/html/yazlab/uploads/';
            $file_info = [];
            
            $flattened_meta = [];
            foreach ($meta as $key => $value) {
                    $flattened_meta[] = reset($value);
                
            }
            $i=0;
            foreach ($files['name'] as $documentName => $documentFiles) {
                foreach ($documentFiles as $index => $fileName) {
                    $metaData = isset($flattened_meta[$i]) ? $flattened_meta[$i] : [];
                    $uniqueId = uniqid('', true);
                    $targetFile = $upload_dir . $uniqueId . '-' . basename($fileName);
                    $tempName = $files['tmp_name'][$documentName][$index];
            
                    if (move_uploaded_file($tempName, $targetFile)) {
                        $file_info[] = [
                            'document' => $documentName,
                            'file_name' => $fileName,
                            'unique_id' => $uniqueId,
                            'target_path' => $targetFile,
                            'mime_type' => $files['type'][$documentName][$index],
                            'size' => $files['size'][$documentName][$index],
                            'author' => $metaData['author'] ?? 'N/A',
                            'employees' => $metaData['employees'] ?? 'N/A',
                            'description' => $metaData['description'] ?? 'N/A',
                            'cats' => $metaData['category'] ?? 'N/A',
                        ];
                    } else {
                        echo "Dosya yükleme hatası: " . $fileName;
                    }
                    $i++;
                }
            }
            //echo "<pre>";print_r($file_info);echo "</pre>";
        }

        $data = [
            'ads' => $this->db->get('ads')->result(),
            'cats' => $this->db->get('cats')->result(),
            'rules' => $this->db->get('rules')->result(),
        ];

        $this->load->view('/frontend/documentTest', $data);
    }
    
    public function addJury(){
        $this->role_check();
        $data['adverts'] = $this->AdminModel->getAllAdverts();
        $data['juries'] = $this->AdminModel->getAllJuries();
        $data['ads_juries'] = $this->AdminModel->getAdsWithJuries();

        $this->load->view('/frontend/adminPanel/juryPanel', $data);

    }

    public function savejury()
    {
        $this->role_check();
        $adsId = $this->input->post('ad');
        $juryList = $this->input->post('jury');

        if (!$adsId || !is_array($juryList)) {
            return false;
        }

        $insertData = [];
        foreach ($juryList as $userId) {
            $insertData[] = [
                'ads_id' => (int)$adsId,
                'user_id' => (int)$userId
            ];
        }

        $this->db->insert_batch('ads_juries', $insertData);

        return $this->addJury();
    }

    public function deleteJury() 
    {
        $this->role_check();
        $id = $this->input->post('id');
        if (!empty($id))
        {
            $this->AdminModel->deleteJuries($id);
        }
        return $this->addJury();
    }
}