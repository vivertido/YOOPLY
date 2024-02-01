<style>
._template
{
	display:none;
}

.rounded
{
	-moz-border-radius: 15px;
	-webkit-border-radius: 15px;
	border-radius: 15px;
	border: 2px solid #c4cfef;
}
</style>
<script>
$(function()
{
	$('._addparent').on('click', function()
	{
		$('#parents').append($('._template').html().replace(/__ID__/g, $('#parents').children().size()+1));
	});
});
</script>

<?php if(isset($error)): ?>
<div class="error">
<?php switch($error):
case 'firstname': ?>Please enter a first name<?php break;
case 'lastname': ?>Please enter a last name<?php break;
case 'email': ?>Please enter an email address<?php break;
case 'nogroupsslected': ?>Please select at least one group.<?php break;
case 'emailinuse': ?>The email you entered is associated with another student.<?php break;
case 'parentfirstname': ?>Please fill in the parent's first name.<?php break;
case 'parentlastname': ?>Please fill in the parent's last name.<?php break;
case 'usernameempty': ?>Please enter a username.<?php break;
case 'usernameinuse': ?>Username in use. Please choose another username<?php break;
case 'passwordempty': ?>Please enter a password.<?php break;
endswitch; ?>
</div><br />
<?php endif; ?>
<form action="/student/add" method="POST" data-ajax="false">
	Firstname:
	<input type="text" name="firstname" value="<?= isset($firstname) ? htmlentities($firstname) : '' ?>" />

	Lastname:
	<input type="text" name="lastname" value="<?= isset($lastname) ? htmlentities($lastname) : '' ?>" />

	Email:
	<input type="text" name="email" value="<?= isset($email) ? htmlentities($email) : '' ?>" />

	Grade:
	<select name="grade">
	<?php foreach(array('TK','K','1','2','3','4','5','6','7','8','9','10','11','12') as $g): ?>
		<option value="<?= $g ?>"<?php if(isset($grade) && $grade == $g): ?> selected="selected"<?php endif ?>><?= $g ?></option>
	<?php endforeach; ?>
	</select>

	Student ID#:
	<input type="text" name="studentid" value="<?= isset($studentid) ? htmlentities($studentid) : '' ?>" />

	Gender:
	<select name="gender">
	<?php foreach(array('M' => 'Male', 'F' => 'Female') as $k=>$g): ?>
		<option value="<?= $k ?>"<?php if(isset($gender) && $gender == $k): ?> selected="selected"<?php endif ?>><?= $g ?></option>
	<?php endforeach; ?>
	</select>

	Birthday:
	<input type="date" name="dob" value="<?= isset($dob) ? htmlentities($dob) : '' ?>" />

	Ethnicity:
	<input type="text" name="ethnicity" value="<?= isset($ethnicity) ? htmlentities($ethnicity) : '' ?>" />

	<h3>Classes</h3>
	<ul>
	<?php $i = 0; foreach($allgroups as $group): ?>
		<li><input type="checkbox" name="group[<?= $group->groupid ?>]" value="1" id="class<?= $i ?>"<?php if(isset($groups) && isset($groups[$group->groupid])): ?> checked="checked"<?php endif; ?> /> <label for="class<?= $i ?>"><?= $group->title ?></label></li>
	<?php $i++; endforeach; ?>
	</ul>

	<h3>Parents</h3>
	<?php $i = 1; ?>

	<div id="parents">
		<?php if(isset($formparents) && !empty($formparents)): 
			foreach($formparents as $p): ?>
		<div style="border: 1px solid #C0C0C0; margin-top: 10px; padding: 10px; background-color: #E0E0E0" class="rounded">
			<h3>Parent #<?= $i ?></h3>
			First name: <input type="text" name="parent[<?= $i ?>][firstname]" value="<?= htmlentities($p['firstname']) ?>" /> 
			Last name: <input type="text" name="parent[<?= $i ?>][lastname]" value="<?= htmlentities($p['lastname']) ?>" /> 
			Email: <input type="text" name="parent[<?= $i ?>][email]" value="<?= htmlentities($p['email']) ?>" />
			Phone: <input type="text" name="parent[<?= $i ?>][phone]" value="<?= htmlentities($p['phone']) ?>" />
		</div>
		<?php $i++; 
			endforeach; 
		else: ?>
		<div style="border: 1px solid #C0C0C0; margin-top: 10px; padding: 10px; background-color: #E0E0E0" class="rounded">
			<h3>Parent #<?= $i ?></h3>
			First name: <input type="text" name="parent[<?= $i ?>][firstname]" value="" /> 
			Last name: <input type="text" name="parent[<?= $i ?>][lastname]" value="" /> 
			Email: <input type="text" name="parent[<?= $i ?>][email]" value="" />
			Phone: <input type="text" name="parent[<?= $i ?>][phone]" value="" />
		</div>
		<?php endif; ?>
	</div>

	<a href="#" class="_addparent">Add another parent</a>

	<?php if($emailsigninenabled): ?>
	<h3>Username/Password</h3>
	<div id="login">
		Username: <input type="text" name="username" value="<?= isset($username) ? htmlentities($username) : '' ?>" />
		Password: <input type="password" name="password" value="" />
	</div>
	<?php endif; ?>

	<input type="submit" name="submit" value="Add Student" />
</form>

<div class="_template">
	<div style="border: 1px solid #C0C0C0; margin-top: 10px; padding: 10px; background-color: #E0E0E0" class="rounded">
		<h3>Parent #__ID__</h3>
		First name: <input type="text" name="parent[__ID__][firstname]" value="" /> 
		Last name: <input type="text" name="parent[__ID__][lastname]" value="" /> 
		Email: <input type="text" name="parent[__ID__][email]" value="" />
		Phone: <input type="text" name="parent[__ID__][phone]" value="" />
	</div>
</div>