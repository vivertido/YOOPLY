<? $this->load->view('account/menu', array('tab' => 'personal')) ?>

<?php if(isset($error)): ?><div class="error"><?php switch($error):
case 'firstname': ?>Please enter a first name.<? break;
case 'lastname': ?>Please enter a last name.<? break;
endswitch; ?></div><br /><?php endif; ?>

<form action="/account/personal" method="POST" data-ajax="false">
	First name:
	<input type="text" name="firstname" value="<?= htmlentities($user->firstname) ?>"<?php if($namedisabled): ?> disabled="disabled"<?php endif; ?> />

	Last name:
	<input type="text" name="lastname" value="<?= htmlentities($user->lastname) ?>"<?php if($namedisabled): ?> disabled="disabled"<?php endif; ?> />

	Phone number:
	<input type="text" name="phone" value="<?= htmlentities($user->phone) ?>" />

	Date of birth:
	<input type="date" name="dob" value="<?= $user->dob ?>" />

	Gender:
	<select name="gender">
		<?php foreach(array('' => 'Not Specified', 'M' => 'Male', 'F' => 'Female') as $k=>$gender): ?>
			<option value="<?= $k ?>"<?= $user->gender == $k ? ' selected="selected"' : '' ?>><?= $gender ?></option>
		<?php endforeach; ?>
	</select>

	<input type="submit" name="submit" value="Save Changes" />
</form>