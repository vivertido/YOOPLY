<h2><?= $school->name ?></h2>

<?= $studentcount ?> students
<?= $teachercount ?> teachers
<?= $sectioncount ?> sections

<h2>Teachers</h2>
<?php foreach($teachers as $teacher): ?>
<?= $teacher->data->last_modified ?> <?= $teacher->data->name->last ?> <?= $teacher->data->name->first ?> <?= $teacher->data->email ?><br />
<?php endforeach; ?>

<h2>Students</h2>
<?php foreach($students as $teacher): ?>
<?= $teacher->data->last_modified ?> <?= $teacher->data->name->last ?> <?= $teacher->data->name->first ?> <?= $teacher->data->email ?><br />
<?php endforeach; ?>

<h2>Sections</h2>
<?php foreach($sections as $teacher): ?>
<?= $teacher->data->last_modified ?> <?= $teacher->data->name ?><br />
<?php endforeach; ?>

<form action="/partner/clever/import/<?= $school->id ?>" method="POST" data-ajax="false">
	<!--<input type="submit" name="submit" value="Import School" />-->
</form>