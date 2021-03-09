<?php 
/**
* 
*/
class Verify extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
    $this->load->model("admin/CommanModal","Verify");
	}


	function index()
	{
    $this->load->view("layout/layout");
		
	}

    
    function join($token='',$err = '')
    {
      $status   = 404;
      $message  = 'Invalid Invitation. Please check';
      $responce = array();

      $join  = array(array('table'=>"users",'condition'=>"mentors_mentee.mentor_id = users.id"),
                     array('table'=>"users as mentee",'condition'=>"mentors_mentee.mentee_id = mentee.id"),
                    array('table'=>"courses",'condition'=>"mentors_mentee.course_id = courses.id"));

      $where   = "mentors_mentee.token='".$token."' AND mentors_mentee.status='1' ";
       
      $colonm  = "mentors_mentee.*,users.full_name 'mentor_name',users.email 'mentor_email',users.country_code 'mentor_country_code',users.phone 'mentor_phone',
        users.profile 'mentor_profile',courses.name 'course_name',mentee.full_name 'mentee_name',mentee.email 'mentee_email',mentee.country_code 'mentee_country_code',mentee.phone 'mentee_phone'";
      $check = $this->Verify->RowData('mentors_mentee',$colonm,$where,$join);

      if($check)
      {
        $status   = 200;
        $message  = 'Please update your info and join this course';
        $responce = (array)$check;
      }
      else
      {
        $status  = 404;
        $message = 'Invalid Invitation. Please check';
        $responce    = array();
      }

      $data['status']  = $status;
      $data['message'] = $message;
      $data['data']    = $responce;
      $data['token']   = $token;
      $data['err']     = $err;

      // echo '<pre>';print_r($data);die();
      $this->load->view("join_course",$data);
    }

    function confirmjoin()
    {
      $err ='';
      $token = ($this->input->post('token'))?$this->input->post('token'):"";
      $name  = ($this->input->post('name'))?$this->input->post('name'):"";
      $phone = ($this->input->post('phone'))?$this->input->post('phone'):"";
      $country_code = ($this->input->post('country_code'))?$this->input->post('country_code'):"";
      $password     = ($this->input->post('password'))?$this->input->post('password'):"";
      $confirm_password = ($this->input->post('confirm_password'))?$this->input->post('confirm_password'):"";

      // print_r($this->input->post());die();
      $check = $this->Verify->RowData('mentors_mentee','*',array('token'=>$token)); 
      if($check)
      {
        if($password == $confirm_password)
        {
          $update['full_name'] = $name;
          $update['phone'] =$phone ;
          $update['country_code'] =$country_code;
          $update['password'] =md5($password);
          $update['update_on'] =now;
          $query  = $this->Verify->UpdateData('users',$update,array('id'=>$check->mentee_id)); 
          
          $update =array();
          $update['status'] ='2';
          $update['update_on'] =now;
          $query = $this->Verify->UpdateData('mentors_mentee',$update,array('id'=>$check->id)); 
          $status  = 202;
          $message = 'Thanks for join this  course ';
          $responce    = array();
        }
        else
        {
         $err     =  'Password and conform password shoud be same';
         $this->join($token,$err);
         return;
        }

        // confirm join request 
       
      }
      else
      {
        $status  = 404;
        $message = 'Invalid Invitation. Please check';
        $responce    = array();
      }

        $data['status']  = $status;
        $data['message'] = $message;
        $data['err']     = $err;
        
        $data['data']    = array();
        $data['token']   = $token;
        $this->load->view("join_course",$data);

    }
	
}

?>

