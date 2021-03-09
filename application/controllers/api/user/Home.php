<?php

defined('BASEPATH') OR exit('No direct script access allowed');
 require APPPATH . 'core/API_Controller.php';
 
/**
    Created By Sukhdev Pawar (24-02-2021)
 * Controller for all Auth related  Operation like signin , signUp , update detail,Otp verify,changePassword, etc..
 */ 

class Home extends API_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->load->model('api/AuthModal', 'Home');   
  }

  function index()
  {
     $res = array('status'=>'510','message'=>"Under Development");
          $this->response($res);
  }
  
}
?>