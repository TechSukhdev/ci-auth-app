<!DOCTYPE html>
<html lang="en">
<head>
	<title>Mentor</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="<?php  echo base_url('assets/css/bootstrap.min.css'); ?>">
	<link rel="stylesheet" href="<?php  echo base_url('assets/css/font-awesome.min.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php  echo base_url('assets/css/style.css'); ?>">
</head>
<style type="text/css">
	@media(max-width: 800px){
		body{background-color:#f9f8ff!important ;}
	}
</style>
<body>
	<section class="loginBg1">
	<div class="container">
		<div class="row">
			<div class="col-md-12 text-center">
				<p class="text-center mt-3">
					<img src="<?php  echo base_url('assets/img/logo.png'); ?>" height="80px">
				</p>
				<h4 class="text-pink">LOGIN</h4>
				<?php echo (($this->session->flashdata('error_msg')))?"<p class='text-center error'>".$this->session->flashdata('error_msg')."</p>":""?>
				
			</div>
		</div>
	</div>

	<div class="container loginBg">
		<div class="row justify-content-center">
			
			<div class="col-sm-12 col-md-6">
				<form class="form p-4" method="post" action="<?php echo site_url('Auth/checklogin')?>"> 
					<div class="form-group">
						<input type="email" class="form-control" placeholder="Email" name="email" value="<?php echo set_value('email'); ?>" >
						<?php echo "<p class='error'>".form_error('email')."</p>"; ?>

					</div>
					<div class="form-group">
						<input type="password" class="form-control" placeholder="Password" name="password" value="" >
						<?php echo "<p class='error'>".form_error('password')."</p>"; ?>
					</div>
					<div class="form-group">
						<p class="text-center">
							<button  class="btn btn-pink" type="submit">Login </button	> </p>
					</div>
				</form>
			</div>
		</div>
	</div>
</select>
</body>
</html>