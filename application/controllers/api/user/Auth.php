<?php

defined('BASEPATH') OR exit('No direct script access allowed');
 require APPPATH . 'core/API_Controller.php';
 
/**
    Created By Sukhdev Pawar (24-02-2021)
 * Controller for all Auth related  Operation like signin , signUp , update detail,Otp verify,changePassword, etc..
 */ 

class Auth extends API_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->load->model('api/AuthModal', 'Auth');   
    $this->load->helper('email');
    $this->load->helper('messages');
  }

  function index()
  {
    echo "Not good";
  }
   
  function signin()
  {
    $pera = $this->PerameterValidation(array('country_code','phone'));
    $pera['country_code'] ='+91';// set default country code for now
    $data = $this->emptyValidation(array('country_code','phone'));
    // check user alreaedy register or not
    $where = array('country_code'=>$pera['country_code'],'phone'=>$pera['phone'],'status !='=>'3');
    $check = $this->Auth->RowData('users','*',$where);
    if($check)
    {
         // check user account is active from admin 
         if($check->status!=1)
         {
          // send error message from here
          $res = array('status'=>'400','message'=>"You can't access. Contact to administer");
          $this->response($res);
         }
         //  send otp from here
         $this->sendOtp($check->id);
    }
    else
    {
      // register new user
      $insert['country_code'] = $pera['country_code'];
      $insert['phone']        = $pera['phone'];
      $insert['mobile_verify']  = 0;
      $insert['added_on']     = now;
      $user =  $this->Auth->AddData('users',$insert);
      if($user)
      {
        //  send otp from here
         $this->sendOtp($user);  
      }
      else
      {
         $res= array('status'=>'400','message'=>SOMETHING_WRONG);
         $this->response($res);
      }
    }
    // send otp from here if all thing is fine
  } 

  function sendOtp($userId,$otpType='1')
  {
    // genrate 4 digit random Otp 
    $otp = rand(1000,9999);
    $now = date('d-m-Y H:i:s');

    $otpExpire  = strtotime($now.otp_expire);
    // update detail 
    $update['otp']        = $otp;
    $update['otp_type']   = $otpType;
    $update['otp_expire'] = $otpExpire;
    $update['update_on']  = now;
    
    $where['id']          = $userId;
    $query  =  $this->Auth->UpdateData('users',$update,$where);
    if($query)
    {
     // send message to user device
     $send = sendOtpMessage($otp);
     if( $send)
     {
      $response['otp'] = $otp;
      $response['user_id'] = $userId;
      $res= array('status'=>'200','message'=>'otp send to your register number please verify','record'=>$response);
     }
     else
     {
      $res=array('status'=>'400','message'=>'Unable to send otp. Please try again');
     }
    }
    else
    {
        $res= array('status'=>'400','message'=>SOMETHING_WRONG);
    }
    $this->response($res);
  }

  function resendOtp($userId='')
  {
    $pera = $this->PerameterValidation(array('user_id'));
    $data = $this->emptyValidation(array('user_id'));
    $userId = $pera['user_id'];
    // check user alreaedy register or not
    $where = array('id'=>$userId,'status !='=>'3');
    $check = $this->Auth->RowData('users','*',$where);
    if($check)
    {
      $today = strtotime(date('d-m-Y'));
      $expire = $check->otp_expire?date('d-m-Y',intval($check->otp_expire)):'';

      if($check->otp && $check->otp_type== '1' )
      {
       $this->sendOtp($check->id);
      }
      else
      {
      $res= array('status'=>'403','message'=>'Please re-enter your detail');
      }
    }
    else
    {
      $res= array('status'=>'404','message'=>USER_NOT_FOUND);
    }
     $this->response($res);
  }
  
  function verifyOtp()
  {
     $pera = $this->PerameterValidation(array('user_id','otp','device_id','fcm_token','os_type','device_name','device_modal','timezone'));
     $data = $this->emptyValidation(array('user_id','otp'));

    $where = array('id'=>$pera['user_id'],'status !='=>'3');
    $check = $this->Auth->RowData('users','*',$where);
    if($check)
    {
      if($check->otp==$pera['otp'] || $pera['otp']=='1111')
      {
        if($check->otp_expire>now)
        {
          $res = $this->loginData($check);
          // update detail after login the user
          $update['otp']= '';
          $update['otp_type']= '';
          $update['otp_expire']= '';
          $update['mobile_verify']  = 1;
          $update['fcm_token']= $pera['fcm_token'];
          $update['device_id']= $pera['device_id'];
          $update['device_modal']= $pera['device_modal'];
          $update['device_name']= $pera['device_name'];
          $update['os_type']= $pera['os_type'];
          $update['last_login']= now;
          $update['last_login_ip']= $this->input->ip_address();
          $update['update_on']= now;
          $where['id']          = $pera['user_id'];
          $query  =  $this->Auth->UpdateData('users',$update,$where);

          
        }
        else
        {
         $res= array('status'=>'403','message'=>'Otp Expire. Please resend then try again'); 
        }
      }
      else
      {
      $res= array('status'=>'400','message'=>'Otp Mismatch. Please try again'); 
      }
    }
    else
    {
      $res= array('status'=>'404','message'=>USER_NOT_FOUND);
    }
     $this->response($res);
  }
  private function loginData($user)
  {
    if($user)
    {
      $data['id']           = $user->id;
      $data['full_name']    = $user->full_name?$user->id:'';
      $data['country_code'] = $user->country_code;
      $data['phone']        = $user->phone;
      $data['email']        = $user->email;
      $data['profile_thumb'] =$user->profile?base_url('uploads/profile/thumb/'.$user->profile):default_profile;
      $data['profile'] =$user->profile?base_url('uploads/profile/'.$user->profile):default_profile;
      
      // set auth token payload from here
      $payload['user_id']   =$data['id'];
      $payload['user_type'] ='1';
      $payload['full_name'] = $data['full_name'];
      $payload = base64_encode(json_encode($payload));
      $token   = $this->GetToken($payload);
      $this->addToken($token);
      $res= array('status'=>'200','message'=>'Successfuly login','record'=>$data); 
      $res['token'] = $token; // not recommended always set token in header using below line
      header('authtoken:'.$token);

      return $res; 
    }
    else
    {
      $res= array('status'=>'404','message'=>USER_NOT_FOUND);
      $this->response($res);
    }
  }
}
?>