 <form action="/a/users/adduser" method="POST" data-ajax="false">
	<select name="schoolid">
		<?php foreach($schools as $school): ?>
			<option value="<?= $school->schoolid ?>"><?= $school->title ?></option>
		<?php endforeach; ?>
	</select>

	First Name: <input type="text" name="firstname" />
	Last Name: <input type="text" name="lastname" />
	Email: <input type="text" name="email" />
	Account type: <select name="accounttype">
		<option value="a">Admin</option>
		<option value="t">Teacher</option>
		<option value="s">Student</option>
	</select>

	Grade: <input type="text" name="grade"/>


	<input type="submit" name="submit" value="Add User" />
</form>
