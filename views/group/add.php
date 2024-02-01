<?php if(isset($error)): switch($error):
case 'emptytitle':
?>
<b>Please enter a group title.</b>
<?php
break;
case 'noteachers':
?>
<b>Please select a teacher.</b>
<?php
break;
endswitch; endif; ?>
<form action="/group/add" method="POST" data-ajax="false">
Group name:<br />
<input type="text" name="title" value="<?= isset($title) ? htmlentities($title) : '' ?>" />
<?php if(!empty($teachers)): ?>
Teachers:<br />
<?php foreach($teachers as $teacher): ?>
<label><input type="checkbox" name="teachers[<?= $teacher->userid ?>]" value="1" /><?= $teacher->firstname ?> <?= $teacher->lastname ?></label>
<?php endforeach; ?>
<?php endif; ?>
<input type="submit" name="submit" value="Add Group" />
</form>