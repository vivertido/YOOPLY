<h2><?= $school->name ?></h2>

<?= $admincount ?> admins  <?= $teachercount ?> teachers <?= $studentcount ?> students

<?php foreach($sections as $section): ?>
<?= $section->data->name ?> (<?= count($section->data->teachers) ?> teachers, <?= count($section->data->students) ?> students)<br />
<?php endforeach; ?>

<form action="/partner/clever/import/<?= $school->id ?>" method="POST">
	<?php if(empty($existingschool)): ?>
		This school is already in Yoop.ly.
		<input type="submit" name="update" value="Update Roster" />
	<?php else: ?>
		<input type="submit" name="submit" value="Add school to Yooply" />
	<?php endif; ?>
</form>