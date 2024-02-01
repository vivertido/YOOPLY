<?php if(isset($error)): ?><div class="<?php
switch($error):
case 'nocontributors': ?>error">Please select at least one user type who can fill out this form.<?php break;
case 'noviewers': ?>error">lease select at least one user type who can view this form.<?php break;
case 'title':?>error">Please enter a form title.<?php break;
endswitch;
?>
</div>
<?php
endif;

$questions = $form->formdata; 
?>
<p>Create your new form and add elements you want your user to interact with, such as dropdowns, text fields, and add instructions, if needed. </p>

<form action="/form/edit/<?= $form->formid ?>" method="POST" data-ajax="false">
	Form title: 
	<input type="text" name="title" value="<?= isset($form->title) ? htmlentities($form->title) : '' ?>" />
	
<div id="elements">
	<?php inflate_edit_form($questions->questions, 'f', true, false) ?>

	<a href="#addItemMenu" id="addElement" data-rel="popup" data-position-to="window" data-role="button" data-inline="true"  data-transition="fade">Add Element</a>
</div>	
<p>Enter your form title and read/write options. Don't forget to enter Save Changes, or your form won't be created.</p>
<div data-role="collapsible"<?= true || isset($error) ? ' data-collapsed="false"' : '' ?>>
	<h3>Form details</h3>

	Who can view the form responses?
	<ul>
		<?php foreach(array('s' => 'Student', 't' => 'Teacher', 'a' => 'Admin', 'p' => 'Parent') as $k=>$v): ?>
			<li><input type="checkbox" name="viewers[<?= $k ?>]" id="viewers<?= $k ?>"<?= strpos($form->viewers, $k) !== false ? ' checked="checked"' : '' ?> /> <label for="viewers<?= $k ?>"><?= $v ?></label></li>
		<?php endforeach; ?>
	</ul>

	Who can fill out this form?
	<ul>
		<?php foreach(array('s' => 'Student', 't' => 'Teacher', 'a' => 'Admin', 'p' => 'Parent') as $k=>$v): ?>
			<li><input type="checkbox" name="contributors[<?= $k ?>]" id="contributors<?= $k ?>"<?= strpos($form->contributors, $k) !== false ? ' checked="checked"' : '' ?> /> <label for="contributors<?= $k ?>"><?= $v ?></label></li>
		<?php endforeach; ?>
	</ul>

	<fieldset data-role="controlgroup">
		<legend>This form is about a:</legend>
		<input type="radio" name="subject" value="t" id="teacher"<?= strpos($form->subject, 't') !== false ? ' checked="checked"' : '' ?> /> <label for="teacher">Teacher</label>
		<input type="radio" name="subject" value="s" id="student"<?= strpos($form->subject, 's') !== false ? ' checked="checked"' : '' ?> /> <label for="student">Student</label>
		<input type="radio" name="subject" value="" id="none"<?= $form->subject == '' ? ' checked="checked"' : '' ?>  /> <label for="none">None</label>
	</fieldset>


	What field do you want to display in the listview?
	<select id="displayanswer" name="indextitle">

	</select>

	What time do you want to display in the listview?
	<select id="displaytime" name="timetitle" data-selected="<?= htmlentities($form->timetitle) ?>">

	</select>

	<h3>Options</h3>

	<label>
		<input type="checkbox" name="notify" value="1"<?php if($customnotificationsenabled): ?> checked="checked"<?php endif; ?> /> Include notification search bar
	</label>	
</div>




<input type="submit" name="submit" value="Save Changes" />
</form>
<?php form_wizard('f') ?>