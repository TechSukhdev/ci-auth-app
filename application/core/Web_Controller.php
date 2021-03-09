
<?php
/**
*   
*/
class Web_Controller  extends CI_Controller
{
  public $user;
  public $login_user;
  public $user_type;
  function __construct()
  {
    parent::__construct();
    date_default_timezone_set("Asia/kolkata");
    // get incommeng perameter from API
    $this->Authorization(); // check Authontication 

  }
  function Authorization()
  {
    // check user is  login or not 
    $session = $this->session->userdata('user');
    // print_r($session);die();
     if($session)
     {
       $this->user = $session;
       $this->login_user = $session['id'];
       $this->user_type = $session['user_type'];
       return true;
     }
     else
     {
     	redirect('Auth/login');
     }
  }

   public function response($response)
  {
      $status =  200;

      if(!is_array($response)){
        $response  = array('message'=>'Responce is not proper','status'=>'501');
      }
      if(array_key_exists('status', $response))
      {
        $status =  intval($response['status']);
      }
      // http_response_code($status);
      print(json_encode($response));
      die();
  }

  
}

?>

