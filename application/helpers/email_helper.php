<?php
require APPPATH.'third_party/phpMailer/vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

   function forgotpasswordMail($send_to,$to_name,$link)
   {
      $mail = new PHPMailer(true);
     try {
            $mail->isSMTP();     
            $mail->Host       = 'smtp.gmail.com';  
            $mail->SMTPAuth   = true;      
            $mail->Username   = email;
            $mail->Password   = gpassword;    
            $mail->SMTPSecure = 'tls';   
            $mail->Port       = 587;
            $mail->setFrom(mailfrom, mailfromname);
             $mail->addAddress($send_to, $to_name); 
            // Content
            $mail->isHTML(true);  
            $body="<p>Hello ".$to_name."</p> Please verify below OTP for reset password";
            $body .= "<p> ".$link."</p>";
            
            $mail->Subject = "Reset your password";
            $mail->Body    = $body;
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
            return   1;
        } catch (Exception $e) {
            return 0;
            return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
   } 

   function accountverifymail($link,$send_to, $to_name)
   {
       $mail = new PHPMailer(true);
     try {
            $mail->isSMTP();     
            $mail->Host       = 'smtp.gmail.com';  
            $mail->SMTPAuth   = true;      
            $mail->Debug   = 2;      
            $mail->Username   = email;
            $mail->Password   = gpassword;    
            $mail->SMTPSecure = 'tls';   
            $mail->Port       = 587;
            $mail->setFrom(mailfrom, mailfromname);
             $mail->addAddress($send_to, $to_name); 
            // Content
            $mail->isHTML(true);  
            $body="<p>Hello ".$to_name."</p> Please click  to below link for verify your account";
            $body .= "<p> ".$link."</p>";
            
            $mail->Subject = "Account verification";
            $mail->Body    = $body;
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
            return   1;
        } catch (Exception $e) {
            // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            return 0;
        }
   }

   function SendAddMenteeLink($send_to, $to_name='',$link='')
   {
     $mail = new PHPMailer(true);
     try {
            $mail->isSMTP();     
            $mail->Host       = 'smtp.gmail.com';  
            $mail->SMTPAuth   = true;      
            $mail->Username   = email;
            $mail->Password   = gpassword;    
            $mail->SMTPSecure = 'tls';   
            $mail->Port       = 587;
            $mail->setFrom(mailfrom, mailfromname);
             $mail->addAddress($send_to, $to_name); 
            // Content
            $mail->isHTML(true);  
            $body="<p>Hello ".$to_name."</p> Please join course by clicking below link";
            $body .= "<p> ".$link."</p>";
            
            $mail->Subject = "Join The Course";
            $mail->Body    = $body;
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
            
            return   1;
        } catch (Exception $e) {
            return 0;
            return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
   }
?>