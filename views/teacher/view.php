
<?php if(empty($groups)): ?>
This teacher has no assigned classes.
<?php else: ?>
<ul data-role="listview" data-inset="true">
<?php foreach($groups as $group): ?>
	<li><a href="/group/view/<?= $group->groupid ?>"><?= $group->title ?></a></li>
<?php endforeach; ?>
</ul>
<?php endif; ?>

<?php if($role == 'a'): ?>
<ul data-role="listview" data-inset="true">
	<li><a href="/teacher/edit/<?= $teacher->userid ?>">Edit</a></li>
</ul>	
<?php endif; ?>

<?php if(!empty($formsview)): ?>
<h3>Assign</h3>
<ul data-role="listview" data-inset="true">
	<?php foreach($formsassign as $form): ?>
		<li><a href="/form/respond/<?= $form->formid ?>/<?= $teacher->userid ?>"><?= $form->title ?></a></li>
	<?php endforeach; ?>
</ul>
<?php endif; ?>

<?php if(!empty($formsview)): ?>
<h3>Responses</h3>
<ul data-role="listview" data-inset="true">
	<?php foreach($formsview as $form): ?>
		<li><a href="/report/form/<?= $form->formid ?>/today/<?= $teacher->userid ?>"><?= $form->title ?></a></li>
	<?php endforeach; ?>
</ul>
<?php endif; ?>