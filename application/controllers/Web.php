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
    public function dbtest()
    {
        echo $this->db->query("SELECT VERSION()")->row('version');
    }
}
