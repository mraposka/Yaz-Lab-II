<?php

defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('UserModel');
    }

    public function role_check(){
        try {
            $user=$this->sessionKontrol();
            if($user->role == "Kullanici" || $user->role == "Admin")
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
        $this->role_check();
        $user=$this->sessionKontrol();
        return $this->user(['user'=>$user]);
	}

    public function user($user=null){
        $this->role_check();
        $this->load->view('/frontend/userPanel/userPanel');
    }

    public function ads(){
        $this->role_check();
        $user=$this->sessionKontrol();
        $data=[
            'ads'=>$this->db->get('ads')->result(),
            'apps'=>$this->db->where('user_id',$user->id)->get('application')->result(),
            'positions'=>$this->db->get('positions')->result(),
            'departments'=>$this->db->get('deps')->result(),
        ];
        $this->load->view('/frontend/userPanel/ads',$data);
    }

    public function applications(){
        $this->role_check();
        $user=$this->sessionKontrol();
        $data=[
            'ads'=>$this->db->get('ads')->result(),
            'application'=>$this->UserModel->getUsersApplications($user->id,'3'),
            '_application'=>$this->UserModel->getApplications($user->id),
            'positions'=>$this->db->get('positions')->result(),
            'departments'=>$this->db->get('deps')->result(),
            'status'=>$this->db->get('status')->result(),
        ];
        $this->load->view('/frontend/userPanel/applications',$data);
    }

    public function apply(){
        $this->role_check();
        $user=$this->sessionKontrol();
        print_r($this->input->post());
        $user = $this->session->userdata('user');
        print_r($user);
        $data=[
            'user_id'=>$user->id,
            'ads_id'=>$this->input->post('id'),
            'application_date'=>date("Y-m-d h:i:sa"),
            'status'=>$this->UserModel->get('id=3','status')->id
        ];
        if($this->UserModel->add('application',$data)){
            redirect('user/applications');
        }
    }

    public function documents(){
        $this->role_check();
        $data=[
            'rules'=>$this->UserModel->getRules($this->input->post('position_id'),$this->input->post('department_id')),
            'cats' => $this->db->get('cats')->result(),
            'app_id' => $this->input->post('app_id'),
            'user_id' => $this->sessionKontrol()->id,
        ];
        $this->load->view('/frontend/userPanel/documents',$data);
    }

    public function getFileTypeID($extension) 
    {
        return $this->UserModel->getExtension(strtoupper($extension));
    }

    public function handleUpload() 
    {
        $this->role_check();
        $app_id=$this->input->post('app_id');
        $user_id=$this->input->post('user_id');
        $uploadDir = FCPATH . 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        if (empty($_FILES)) 
        {
            echo "Dosya gelmedi, \$_FILES boş!";
        }
        foreach ($_FILES['files']['name'] as $docName => $fileGroup) {
            foreach ($fileGroup as $index => $originalName) {
                if (empty($_FILES['files']['name'][$docName][$index])) {
                    continue;
                }

                $tmpName = $_FILES['files']['tmp_name'][$docName][$index];
                $fileType = $_FILES['files']['type'][$docName][$index];
                $error = $_FILES['files']['error'][$docName][$index];

                if ($error !== UPLOAD_ERR_OK) {
                    //echo "Dosya yüklenirken hata oluştu: $originalName<br>";
                    continue;
                }

                $filename = uniqid() . '_' . basename($originalName);
                $destination = $uploadDir . $filename;

                if (move_uploaded_file($tmpName, $destination))
                {
                    $meta = $this->findMetaByDocNameAndIndex($docName, $index);
                    $fileExtension=explode('.', $filename);
                    /*echo "Dosya yüklendi: $filename<br>";
                    echo "UserID: " . $user_id . "<br>";
                    echo "AppID: " . $app_id . "<br>";
                    echo "Belge Adı: $docName<br>";
                    echo "Belge Uzantısı: " . end($fileExtension) . " = " . $this->getFileTypeID(end($fileExtension)) . "<br>";
                    echo "Kategori: " . htmlspecialchars($meta['category']) . "<br>";
                    echo "Yazar: -" . $meta['author'] . "<br>";
                    echo "Çalışan Sayısı: " . htmlspecialchars($meta['employees']) . "<br>";
                    echo "Açıklama: " . htmlspecialchars($meta['description']) . "<br><hr>";*/
                    $docCat=$meta['category'];
                    $lead='BY=F';
                    if(strpos(strtolower($meta['author']),'evet')==0)
                    $lead='BY=T';
                    
                    $score='P='.($this->UserModel->getCatPoint($docCat)*$this->UserModel->getColabMultiplier($meta['employees']));
                    $doc=[
                        'application_id' => $app_id,
                        'doc_type' => $this->getFileTypeID(end($fileExtension)),
                        'file_path' => $filename,
                        'description' => htmlspecialchars($meta['description']),
                        'rules' => $docCat.','.$lead.','.$score,
                    ];
                    $this->UserModel->add('documents',$doc);
                } else {
                    //echo "Dosya taşınamadı: $originalName<br>";
                }
            }
        }
        $update=[
            'status' => 4
        ];
        $this->UserModel->update('application',$update,'id='.$app_id);
        
        echo "1";
    }

    function findMetaByDocNameAndIndex($docName, $fileIndex) {
        foreach ($_POST['meta'] as $outerIndex => $innerArray) {
            foreach ($innerArray as $innerIndex => $meta) {
                if ($innerIndex == $fileIndex) {
                    return [
                        'author' => $meta['author'] ?? '',
                        'employees' => $meta['employees'] ?? '',
                        'description' => $meta['description'] ?? '',
                        'category' => $meta['category'] ?? '',
                    ];
                }
            }
        }

        return [
            'author' => '',
            'employees' => '',
            'description' => '',
            'category' => '',
        ];
    }

}