<form action="/contact" method="POST">
<p>If you would like to contact the Yooply Team, please fill out the following information.  If your message requires a response, we will contact you at the email address you provide.</p>

<?php if(isset($error)): ?>
<div style="border: 2px solid red; background-color: #FF9999; padding: 20px">
<b><?= $error ?></b>
</div><br />
<?php endif; ?>
Type of message: (required)<br />
<select name="department">
<option value="">Select one...</option>
<?php $departments = array('support' => 'Technical Support',
'comments' => 'Comments/Suggestions',
'complaints' => 'Complaints',

//'betafocus' => 'Beta Tester/Focus Group',
'business' => 'Business Partnership',
'signup' => 'Sign up your school',
'other' => 'Other'
); foreach($departments as $k=>$v): ?>

	<option value="<?= $k ?>"<?php if(isset($department) && $department == $k): ?> selected="selected"<?php endif; ?>><?= $v ?></option>
<?php endforeach; ?>
</select><br /><br />

Name: (required)<br />
<input type="text" name="name" style="width: 300px" value="<?= isset($name) ? htmlentities($name) : '' ?>" /><br /><br />
Email: (required)<br />
<input type="text" name="email" style="width: 300px" value="<?= isset($email) ? htmlentities($email) : '' ?>" /><br /><br />
Message: (required)<br />
<textarea name="message" style="width: 400px; height: 200px;"><?= isset($message) ? htmlentities($message) : '' ?></textarea><br /><br />

<input type="submit" name="submit" value="Send" />
</form>