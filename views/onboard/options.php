Welcome to Yoop.ly


<form action="/onboard/options/<?= $invitecode ?>" method="POST" data-ajax="false">
	The data looks good! We found <?= $stats->students ?> students, <?= $stats->parents ?> parents, <?= $stats->teachers ?> teachers, <?= $stats->admins ?> admins, and <?= $stats->groups ?> groups.

	Here are some options you can enable from the start. As an admin, you can always go back into your school's settings and enable/disable and customize the options of each feature.

	Features:
	<?php $features = array(
		'detentions' => 'Detentions', 
		'referrals' => 'Referrals',
		'interventions' => 'Interventions',
		'shoutouts' => 'Shoutouts',
		'demerits' => 'Behavior Incidents',
		'goals' => 'Goals',
		'reinforcements' => 'Postive Reinforcements'); ?>

		<?php foreach($features as $feature=> $name): ?>
			<label><input type="checkbox" name="feature[<?= $feature ?>]" value="1" /> <?= $name ?></label>
		<?php endforeach; ?>
	<input type="submit" name="submit" value="Next" />
</form>