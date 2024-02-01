<?php $notes = json_decode($referral->reflection); ?>

<h3>Incident</h3>
<?= $referral->incident ?>

<h3>Teacher</h3>
<?= $teacher->firstname ?> <?= $teacher->lastname ?>

<h3>Date</h3>
<?= date('m/d H:i', $referral->timereflection) ?>

<?php foreach($notes as $question):
	if(empty($question->value)): continue; endif;?>
<h3><?= $question->label ?></h3>
<?php if(is_array($question->value)): ?>
<ul><li><?= implode('</li><li>', $question->value) ?></li></ul>
<?php else: ?>
<p><?= $question->value ?></p>
<?php endif; endforeach; ?>