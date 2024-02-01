<form action="/reset/<?= $reset->userid ?>/<?= $reset->hashkey ?>" method="POST" data-ajax="false">

To reset your account password, please enter your new password.<br /><br />

<?php if(isset($error)): switch($error): 
case 'empty': ?>
<b>Please fill in all fields.</b><br /><br />
<?php break;
case 'passwordmismatch': ?>
<b>Passwords are not the same.</b><br /><br />
<?php break;
endswitch; endif; ?>

Password:<br />
<input type="password" name="password" />

Confirm password:<br />
<input type="password" name="confirm" />

<input type="submit" name="submit" value="Reset Password" />
</form>