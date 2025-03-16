<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Web extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->load->view('/frontend/vue_tester');
    }

    public function login()
    {
        $this->load->view('/frontend/login');
    }
    public function signup()
    {
        $this->load->view('/frontend/signup');
    }
    public function admin()
    {
        $this->load->view('/frontend/adminPanel/main');
    }
    public function roleAssignment() {
        $this->load->view('/frontend/adminPanel/roleAssigment');
    }
    public function advert() {
        $this->load->view('/frontend/adminPanel/advert');
    }

    public function dbtest()
    {
        echo $this->db->query("SELECT VERSION()")->row('version');
    }
}
