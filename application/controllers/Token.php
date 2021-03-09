<?php

defined('BASEPATH') OR exit('No direct script access allowed');
 require APPPATH . 'core/API_Controller.php';
 
/**
    Created By Sukhdev Pawar (24-09-2019)
 * Controller for all Toke Refresh, etc..
 */ 

	class Token extends API_Controller
	{

		function __construct()
		{
			parent::__construct();
		}

		function refresh()
		{
			$pera =$this->PerameterValidation(array('oldToken'));
		    $data = $this->emptyValidation(array('oldToken'));
		    $where = array('token'=>$pera['oldToken']);
		    $check = $this->RestModal->checkUserToken($where);
		    if($check)
		    {
		    	$token = $this->refreshToken($check->user_id,$check->user_type);
	            $res = array('status'=>'200','message'=>'Check header','token'=>$token);
		    }
		    else
		    {
	              $res = array('status'=>'401','message'=>'Unauthorized');
		    }
	              
		    $this->response($res);
		}

		
	}
?>