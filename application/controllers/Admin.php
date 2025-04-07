<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('AdminModel');
    }

    public function role_check(){
        try {
            $user=$this->sessionKontrol();
            if($user->role !== "Admin")
            {
                redirect('login');
            }
            return true;
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

    public function index()
	{
        $user=$this->sessionKontrol();
        if(!empty($user))
        {
            if ($user->role === "Yonetici") {
                return $this->admin(['user'=>$user]);
            }
            
            if ($user->role === "Juri") {
                redirect('jury');
            }
            redirect('user');
        }
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
            
            if ($user->role === "Juri") {
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
        //DB Insert

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
        $this->sessionKontrol();
        $this->load->view('/frontend/adminPanel/main');
    }

    public function users()
    {
        $this->sessionKontrol();
        $data['users'] = $this->AdminModel->getAll('users'); 
        $this->load->view('frontend/adminPanel/users', $data); 
    }

    public function roleAssignment()
    {
        $this->sessionKontrol();
         
        $data['all_users'] = $this->AdminModel->getAll('users'); 
        $data['users'] = $this->AdminModel->getUsersWithRole('users');
        $this->load->view('/frontend/adminPanel/roleAssigment', $data);
    }
    
    public function assignRole()
    {
        $this->sessionKontrol();
        $userId = $this->input->post('user');
        $role = $this->input->post('role');
        if ($userId && $role) {
            $this->AdminModel->update('users', ['role' => $role], array('id' => $userId));
        }
        redirect('admin/roleAssignment'); 
    }

    public function removeRole() 
    {
        $this->sessionKontrol();
        $user_id = $this->input->post('user_id');
        if ($user_id ) {
            $this->AdminModel->removeRole($user_id); 
        }
        redirect('admin/roleAssignment');
    }

    public function deleteUser() 
    {
        $this->sessionKontrol();
        $user_id = $this->input->post('user_id');
        if (!empty($user_id)) {
            $this->AdminModel->delete('users',$user_id); 
        }
        redirect('admin/users'); 
    }

    public function position()
    {
        $this->sessionKontrol();
         
        $data['positions'] = $this->AdminModel->getAll('positions'); 
        $this->load->view('/frontend/adminPanel/position', $data);

    }

    public function deletePosition() 
    {
        $this->sessionKontrol();
        $id = $this->input->post('id');
        if (!empty($id)) {
            $this->AdminModel->delete('positions',$id); 
        }
        redirect('admin/position'); 
    }

    public function addPosition()
    {
        $this->sessionKontrol();
        if(!empty($this->input->post('position_name')))
        {
            $this->AdminModel->add('positions',['title'=>$this->input->post('position_name')]);
        }
        redirect('admin/position');
    }


    public function department()
    {
        $this->sessionKontrol();
        $this->load->view('/frontend/adminPanel/department', ['departments'=>$this->AdminModel->getAll('deps')]);
    }

    public function deleteDepartment()
    {
        $this->sessionKontrol();
        $this->AdminModel->delete('deps',$this->input->post('id'));
        redirect('admin/departments'); 
    }

    public function addDepartment()
    {
        $this->sessionKontrol();
        $department_name = $this->input->post('department_name');
        if(!empty($department_name)){
            $this->AdminModel->add('deps', ['title' => $department_name]);
        }
        redirect('admin/departments'); 
    }

    public function advert()
    {
        $this->sessionKontrol();
        $data=[
            'ads'=>$this->db->get('ads')->result(),
            'positions'=>$this->db->get('positions')->result(),
            'departments'=>$this->db->get('deps')->result(),
        ];
        $this->load->view('/frontend/adminPanel/advert',$data);
    }

    public function addAdvert()
    {
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
        $this->sessionKontrol();
        $this->AdminModel->delete('ads',$this->input->post('id'));
        redirect('admin/advert'); 
    }

    public function documentTest()
    {
        $this->sessionKontrol();
        $this->load->view('/frontend/documentTest');
    }
}