<form action="/a/home/addstudent/<?= $group->groupid ?>" method="POST" data-ajax="false">
	First name:<br />
	<input type="text" name="firstname" />

	Last name:<br />
	<input type="text" name="lastname" />

	Username:<br />
	<input type="text" name="username" />

	Password:<br />
	<input type="text" name="password" />

	Email:<br />
	<input type="text" name="email" />

	Grade:<br />
	<input type="text" name="grade" />

	Student Id:<br />
	<input type="text" name="studentid" />

	DOB:<br />
	<input type="text" name="dob" />

	Ethnicity:<br />
	<input type="text" name="ethnicity" />

	<input type="submit" name="submit" value="Add Student" />
</form>