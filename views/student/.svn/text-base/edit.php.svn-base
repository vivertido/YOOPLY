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
#parents div, #login div
{
	margin-left: 40px;
}

._addparent
{
	margin-left: 40px;
}
</style>
<script>
$(function()
{
	$('._addparent').on('click', function()
	{
		$('#parents').append($('._template').html().replace(/__ID__/g, $('#parents').children().size()+<?= count($parents)+1 ?>));
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
endswitch; ?>
</div><br />
<?php endif; ?>
<form action="/student/edit/<?= $user->userid ?>" method="POST" data-ajax="false">
	First name:
	<input type="text" name="firstname" value="<?= htmlentities($user->firstname) ?>" />

	Last name:
	<input type="text" name="lastname" value="<?= htmlentities($user->lastname) ?>" />

	Email:
	<input type="text" name="email" value="<?= htmlentities($user->email) ?>" />

	Grade:
	<select name="grade">
	<?php foreach(array('TK','K','1','2','3','4','5','6','7','8','9','10','11','12') as $g): ?>
		<option value="<?= $g ?>"<?php if($user->grade == $g): ?> selected="selected"<?php endif ?>><?= $g ?></option>
	<?php endforeach; ?>
	</select>

	Student ID#:
	<input type="text" name="studentid" value="<?= htmlentities($user->studentid) ?>" />

	Gender:
	<select name="gender">
	<?php foreach(array('M' => 'Male', 'F' => 'Female') as $k=>$g): ?>
		<option value="<?= $k ?>"<?php if($user->gender == $k): ?> selected="selected"<?php endif ?>><?= $g ?></option>
	<?php endforeach; ?>
	</select>

	Birthday:
	<input type="date" name="dob" value="<?= htmlentities($user->dob) ?>" />

	Ethnicity:
	<input type="text" name="ethnicity" value="<?= htmlentities($user->ethnicity) ?>" />

	<h3>Classes</h3>
	<ul>
	<?php $i = 0; foreach($allgroups as $group): $in_group = false; foreach($usergroups as $g): if($g->groupid == $group->groupid): $in_group = true; break; endif; endforeach; ?>
		<li><input type="checkbox" name="group[<?= $group->groupid ?>]" value="1" id="class<?= $i ?>"<?= $in_group ? ' checked="checked"' : '' ?> /> <label for="class<?= $i ?>"><?= $group->title ?></label></li>
	<?php $i++; endforeach; ?>
	</ul>

	<h3>Parents</h3>
	<?php if(!empty($parents)): ?>
		<ul>
			<?php $i = 1; foreach($parents as $parent): 
				if(isset($existingparents))
				{
					$is_checked = false;
					foreach($existingparents as $k=>$v)
					{
						if($v == 1 && $k == $parent->userid)
						{
							$is_checked = true;
						}
					}
				}
				else
				{
					$is_checked = true;
				}
			?>
				<li><input id="parent<?= $i ?>" type="checkbox" name="parents[<?= $parent->userid ?>]" value="1"<?= $is_checked ? ' checked="checked"' : '' ?> /> <label for="parent<?= $i ?>"><?= $parent->firstname ?> <?= $parent->lastname ?></label></li>
			<?php $i++; endforeach; ?>
		</ul>
	<?php endif; ?>

	<div id="parents">
		<?php if(isset($formparents)): foreach($formparents as $p): ?>
			<div style="border: 1px solid #C0C0C0; margin-top: 10px; padding: 10px; background-color: #E0E0E0" class="rounded">
				<h3>Parent #<?= $i ?></h3>
				First name: <input type="text" name="parent[<?= $i ?>][firstname]" value="<?= htmlentities($p['firstname']) ?>" /> 
				Last name: <input type="text" name="parent[<?= $i ?>][lastname]" value="<?= htmlentities($p['lastname']) ?>" /> 
				Email: <input type="text" name="parent[<?= $i ?>][email]" value="<?= htmlentities($p['email']) ?>" />
				Phone: <input type="text" name="parent[<?= $i ?>][phone]" value="<?= htmlentities($p['phone']) ?>" />
			</div>
		<?php $i++; endforeach; endif; ?>
	</div>

	<a href="#" class="_addparent">Add another parent</a>	

	<?php if($emailsigninenabled): ?>
	<h3>Username/Password</h3>
	<div id="login">
		Username: <input type="text" name="username" value="<?= htmlentities($user->username) ?>" />
		Password (leave blank to keep previous password): <input type="password" name="password" value="" placeholder="leave blank to keep previous password"/>
	</div>
	<?php endif; ?>

	<input type="submit" name="submit" value="Save Changes" />
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