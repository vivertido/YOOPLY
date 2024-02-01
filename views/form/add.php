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

$questions = isset($form->formdata) ? $form->formdata->questions : array();

?>
<form action="/form/add" method="POST" data-ajax="false">
<h3>Create a Custom Form</h3>

Form title: 
<input type="text" name="title" style="border-color:#1CCFE7; border-width:1px; border-style:solid" placeholder="Title" value="<?= isset($title) ? htmlentities($title) : '' ?>" />

<p>Step 1: Add one or more elements, give them titles, and set options users will see (for dropdowns and checkboxes).</p>

<p></p>

<div id="elements">
	<?php inflate_edit_form($questions, 'f', true, false) ?>
	<a href="#addItemMenu" id="addElement" data-rel="popup" data-position-to="window" data-role="button" data-inline="true"  data-theme="c" data-transition="fade" style="border-radius:50px; border-style:solid;"> + </a>
</div>
<p>Step 2: Enter form Title, who has access, and other details:</p>
<div data-role="collapsible" <?= isset($error) ? ' data-collapsed="false"' : '' ?>>
	<legend>Form details</legend>
	Who can view the form responses?
	<ul>
		<?php foreach(array('s' => 'Student', 't' => 'Teacher', 'a' => 'Admin', 'p' => 'Parent') as $k=>$v): ?>
			<li><input type="checkbox" name="viewers[<?= $k ?>]" id="viewer<?= $k ?>" /> <label for="viewer<?= $k ?>"><?= $v ?></label></li>
		<?php endforeach; ?>
	</ul>

	Who can fill out this form?
	<ul>
		<?php foreach(array('s' => 'Student', 't' => 'Teacher', 'a' => 'Admin', 'p' => 'Parent') as $k=>$v): ?>
			<li><input type="checkbox" name="contributors[<?= $k ?>]" id="contributor<?= $k ?>" /> <label for="contributor<?= $k ?>"><?= $v ?></label></li>
		<?php endforeach; ?>
	</ul>

	<fieldset data-role="controlgroup">
		<legend>This form is about a:</legend>
		<input type="radio" name="subject" value="t" id="teacher"<?= isset($form->subject) && strpos($form->subject, 't') !== false ? ' checked="checked"' : '' ?> /> <label for="teacher">Teacher</label>
		<input type="radio" name="subject" value="s" id="student"<?= isset($form->subject) && strpos($form->subject, 's') !== false ? ' checked="checked"' : '' ?> /> <label for="student">Student</label>
		<input type="radio" name="subject" value="" id="none"<?= isset($form->subject) && $form->subject == '' ? ' checked="checked"' : '' ?>  /> <label for="none">None</label>
	</fieldset>

	What field do you want to display in the listview?
	<select id="displayanswer" name="indextitle">

	</select>

	What time do you want to display in the listview?
	<select id="displaytime" name="timetitle">

	</select>

	<h3>Options</h3>

	<label>
		<input type="checkbox" name="notify" value="1"<?php if(isset($customnotificationsenabled)): ?> checked="checked"<?php endif; ?> /> Include notification search bar
	</label>
</div>
<input type="submit" name="submit" value="Save Changes" data-theme="c" />
</form>

<?php form_wizard('f') ?>