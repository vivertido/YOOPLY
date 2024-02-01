	<ul data-role="listview" class="listviews1" data-divider-theme="d">

<?php
$show_load_more = false;
if(count($shoutouts) == 6)
{
	array_pop($shoutouts);
	$show_load_more = true;
}

$header = '';
foreach($shoutouts as $shoutout):
$h = time_elapsed_term_string($shoutout->timecreated);

if($header != $h):
	$header = $h;
?>
		<li data-role="list-divider"><?= $header ?></li>
<?php
endif;
?>
		<li><img src="/images/<?= $shoutout->profileimage ?>" ><p class="listViewTextTop"><?= $shoutout->fromfirstname ?> <?= $shoutout->fromlastname ?> to <?= $shoutout->tofirstname ?> <?= $shoutout->tolastname ?></p>
			<p class="listViewTextBottom" ><?= $shoutout->content ?></p>
			<p class="ui-li-aside"> <?= time_elapsed_string($shoutout->timecreated) ?></p>
		</li>
<?php endforeach; ?>

</ul>
