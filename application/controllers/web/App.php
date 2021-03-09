<?php

defined('BASEPATH') OR exit('No direct script access allowed');
 // require APPPATH . 'core/API_Controller.php';
 
/**
    Created By Sukhdev Pawar (26-05-2020)
 * Controller for all app related operations..
 */ 

class App extends CI_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->load->model('api/AuthModal', 'User');   
  }

  function index()
  {
    echo "Not good";
  }
  function privacy()
  {
    $this->load->view("app/privacy_policy");
  }
  function SetPassword()
 {
 	$token   = "";
 	$message = "Invalid link";
 	$user_id = "";
 	$data['status']   = "400";

 	if(isset($_GET['link']))
 	{
      $message = "Success";
      $token   = $_GET['link'];
      $detail  = $this->User->RowData('users','*',array('forgot_pass_link'=>$token));
      // print_r($detail);
      // lq();
      if($detail)
      {
       
 	    $message = "Hello ".$detail->full_name.'. Please reset your password and continue with vanmate';
 	    $user_id = $detail->id;
 	    $data['status']   = "200";
      }
      else
      {
        $token   = "";
 	    $message = "Invalid link";
      }
      
 	}
 	$data['token']   = $token;
 	$data['message'] = $message;
 	$data['user_id'] = $user_id;
    $this->load->view("reset_password",$data);
 }

 function Set()
 {
 	$password         = $this->input->post('password');
 	$confirmPassoword = $this->input->post('confirmPassword');
 	$token            = $this->input->post('token');
 	$data['status']   = "400";
 	if($password && $confirmPassoword && $token)
 	{
 	 $where = array('forgot_pass_link'=>$token);
     $detail  = $this->User->RowData('users','*',$where);
     if($detail)
     {
     	if($password == $confirmPassoword)
     	{
     	   $update['password'] =md5($confirmPassoword);
     	   $update['update_on'] =now;
     	   $update['forgot_pass_link'] ='';
           $query  = $this->User->updateData('users',$update,$where);
           if($query)
           {
            $data['token']   = "";
 	        $data['message'] = "Your password hasbeen changed successfully.Please login now with new password.";
 	        $data['user_id'] = '';
 	        $data['status']   = "200";

           }
           else
           {
            $data['token']   = $token;
 	        $data['message'] = "Something went wrong please try again";
 	        $data['user_id'] = $detail->id;
           }
           
     	}
     	else
     	{
     	   $data['token']   = $token;
 	       $data['message'] = "Password and confirm password should be same";
 	       $data['user_id'] = $detail->id;
     	}
      
     }
     else
     {
       $data['token']   = $token;
 	   $data['message'] = "Unauthorized users";
 	   $data['user_id'] = '';
     }

 	}
 	else
 	{
     $data['token']   = $token;
 	 $data['message'] = "Invalid request submited";
 	 $data['user_id'] = '';
 	}
 	
    $this->load->view("reset_password",$data);
 }

  
}
?>