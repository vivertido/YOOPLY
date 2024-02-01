<form action="/forgot" method="POST">
<h3>Forgot your password?</h3>
To reset your password, enter the username for your account.  We'll send you instructions on how to reset your password.<br /><br />

<?php if(isset($error)): switch($error): 
case 'usernameempty': ?>
<b>Please enter your username.</b><br /><br />
<?php break;
case 'unabletoprocess': ?>
<b>Unable to reset your password. Please contact your school administrator for assistance.</b><br /><br />
<?php break;
endswitch; endif; ?>

Username:<br />
<input type="text" name="username" />

<input type="submit" name="submit" value="Send Verification Email" />
</form>