<form action="/teacher/redeemaward/<?= $student->userid ?>" method="POST" data-ajax="false">
	<?= $student->firstname ?> <?= $student->lastname ?>

	<h3>How many <?= $labels->reinforcements ?> does this reward cost?</h3>
	<input type="range" name="amount" class="slider" min="0" data-mini="true" max="<?= $dollars ?>" value="1" step="1" data-theme="c" />

	<h3>What is the student receiving?</h3>
	<input type="text" name="reason" />

	<input type="submit" name="submit" value="Deduct <?= $labels->reinforcements ?>" />
</form>