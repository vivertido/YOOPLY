<?php if(isset($error)): ?>
<div class="error">
<?php switch($error):
case 'firstname': ?>Please enter a first name<?php break;
case 'lastname': ?>Please enter a last name<?php break;
case 'email': ?>Please enter an email address<?php break;
case 'nogroupsslected': ?>Please select at least one group.<?php break;
case 'emailinuse': ?>The email you entered is associated with another teacher.<?php break;
endswitch; ?>
</div><br />
<?php endif; ?>
<form action="/teacher/add" method="POST" data-ajax="false">
	Firstname:
	<input type="text" name="firstname" value="<?= isset($firstname) ? htmlentities($firstname) : '' ?>" />

	Lastname:
	<input type="text" name="lastname" value="<?= isset($lastname) ? htmlentities($lastname) : '' ?>" />

	Email:
	<input type="text" name="email" value="<?= isset($email) ? htmlentities($email) : '' ?>" />

	Permissions:
	<select name="admin">
		<option value="0">Teacher</option>
		<option value="1" <?= isset($isadmin) && $isadmin ? ' selected="selected"' : '' ?>>Teacher/Admin</option>
	</select>

	<h3>Classes</h2>
	<ul>
	<?php foreach($allgroups as $group): ?>
		<li><input type="checkbox" name="group[<?= $group->groupid ?>]" value="1" data-role="none"><?= $group->title ?></li>
	<?php endforeach; ?>
	</ul>
	<input type="submit" name="submit" value="Add Teacher" />
</form>