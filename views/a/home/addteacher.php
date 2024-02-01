<form action="/a/home/addteacher/<?= $group->groupid ?>" method="POST" data-ajax="false">
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

	Role:<br />
	<input type="radio" name="role" value="teacher" checked="checked" data-role="none" /> Teacher <input type="radio" name="role" value="hybrid" data-role="none" /> Teacher/Admin <input type="radio" name="role" value="admin" data-role="none" /> Admin

	<input type="submit" name="submit" value="Add Student" />
</form>