<?php $login_options = array(
		'google' => 'Google', 
		'emailflastname' => 'Email/Password (flastname)',
		'emailfirstlastname' => 'Email/Password (firstlastname)',
		'emailfirst.lastname' => 'Email/Password (first.lastname)',
	);
?>
<form action="/onboard/process/<?= $invitecode ?>" method="POST">
Domain: 
<input type="text" name="domain" value="" />

Login method:
<select name="loginmethod">
	<?php foreach($login_options as $k=>$v): ?>
	<option value="<?= $k ?>"><?= $v ?></option>
	<?php endforeach ?>
</select>

<input type="submit" name="submit" value="Create School" />
</form>