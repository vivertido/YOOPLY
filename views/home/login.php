<style>

	#login-box{

		padding:10px;
		width:480px;

	}


	 @media only screen and (max-device-width:680px){
		#login-box{

			padding:10px;
			width:250px;

		}
	}	
}
</style>
<center>

<h1>Let's figure out who you are...</h1>
<div id="login-box">
	
		<?php if(isset($school->metadata->logo)): ?>
	<img src="<?= $school->metadata->logo ?>"/><br />
	<?php endif; ?>

	<?php if(isset($school->metadata->emailsignin) && $school->metadata->emailsignin): ?>
	<form action="/login<?php if(isset($redirect) && !empty($redirect)): echo '/'.htmlentities($redirect); endif; ?>" method="POST" data-ajax="false">
		<?php if(isset($error)): ?>
		<b>Opps! The username/password combination entered was incorrect.</b><br /><br />
		<?php endif; ?>
		Username:<br />
		<input type="text" name="username" value="<?= isset($username) ? htmlentities($username) : '' ?>" />

		Password:<br />
		<input type="password" name="password" />

		<input type="submit" name="submit" value="Login">
		<input type="submit" name="forgot" value="Forgot Password?">
	</form>
	<?php endif; ?>

	<?php if(isset($school->metadata->googlesignin) && $school->metadata->googlesignin): ?>
		<a href="/partner/google" data-ajax="false"><img src="/images/googlesignin.png" /></a>
	<?php endif; ?>
	
</div>
</center>