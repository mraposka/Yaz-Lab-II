<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Jury extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Common_model');

    }
    
    public function role_check(){
        try {
            $user=$this->sessionKontrol();
            if($user->role !== "Juri")
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

    public function index()
	{
        $user=$this->sessionKontrol();
        if(!empty($user))
        {
            if ($user->role === "Juri") {
                return $this->jury($user);
            }
            
            if ($user->role === "Yonetici") {
                redirect('admin');
            }
            redirect('user');
        }
	}

    public function jury($user=null){
        $user=$this->sessionKontrol();//get user and check session for security purposes!!!!
        print_r($user);
    }

}