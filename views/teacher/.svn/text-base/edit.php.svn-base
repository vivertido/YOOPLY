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
<form action="/teacher/edit/<?= $user->userid ?>" method="POST" data-ajax="false">
	Firstname:
	<input type="text" name="firstname" value="<?= htmlentities($user->firstname) ?>" />

	Lastname:
	<input type="text" name="lastname" value="<?= htmlentities($user->lastname) ?>" />

	Email:
	<input type="text" name="email" value="<?= htmlentities($user->email) ?>" />

	Phone:
	<input type="text" name="phone" value="<?= htmlentities($user->phone) ?>" />

	Permissions:
	<select name="admin">
		<option value="0">Teacher</option>
		<option value="1" <?= $isadmin ? ' selected="selected"' : '' ?>>Teacher/Admin</option>
	</select>

	<h3>Classes</h3>
	<ul>
	<?php foreach($allgroups as $group): $in_group = false; foreach($usergroups as $g): if($g->groupid == $group->groupid): $in_group = true; endif; endforeach; ?>
		<li><input type="checkbox" name="group[<?= $group->groupid ?>]" value="1" data-role="none"<?php if($in_group): echo ' checked="checked"'; endif; ?> /> <?= $group->title ?></li>
	<?php endforeach; ?>
	</ul>

	<input type="submit" name="submit" value="Save Changes" />
</form>