<? $this->load->view('account/menu', array('tab' => 'login')) ?>

<form action="/account/login" method="POST">
<?php if(isset($error)): ?><div class="error"><?php switch($error): 
case 'emptyusername': ?>Please enter a username<?php break;
case 'emptypassword': ?>Please enter a password<?php break;
case 'passwordsnotequal': ?>Passwords are not the same.<?php break;
case 'usernametaken': ?>The username you have chosen is already taken.<?php break;
endswitch; ?></div><?php endif; ?>


	Username: <input type="text" name="username" value="<?= htmlentities($user->username) ?>" />

	Password: <input type="password" name="password" value="" />

	Confirm Password: <input type="password" name="confirmpassword" value="" />

	<input type="submit" name="submit" value="Save Changes" />
</form>
