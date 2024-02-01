<?php if(!empty($school_shoutouts)): ?>
<p style="font-size: 20px; margin-top:10px; margin-bottom:5px">Today's Shoutouts</p>
<div id="marqueeContainer" style="overflow:hidden">
<marquee behavior="scroll" scrollamount=2 direction="up">
<hr>
<?php foreach($school_shoutouts as $shoutout): ?>
<img class="scrollingImgs" src="/images/<?= $shoutout->fromprofileimage ?>" ><p class="scrollingText"><?= $shoutout->fromfirstname ?> <?= $shoutout->fromlastname ?> to <?= $shoutout->tofirstname ?> <?= $shoutout->tolastname ?>: <?= $shoutout->content ?></p>
<hr>
<?php endforeach; ?>
</marquee>
</div>
<?php endif; ?>


<!--My shoutouts -->
<div data-role="collapsible" data-inset="true">
	<h2>My Shout-outs</h2>
<?php if(count($shoutouts) > 0): ?>
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
		<li><img src="/images/<?= $shoutout->profileimage ?>" ><p class="listViewTextTop"><?= $shoutout->tofirstname ?> <?= $shoutout->tolastname ?></p>
			<p class="listViewTextBottom" ><?= $shoutout->content ?></p>
			<p class="ui-li-aside"> <?= time_elapsed_string($shoutout->timecreated) ?></p>
		</li>
<?php endforeach; ?>

<?php if($show_load_more): ?>
<li><a href="/student/shoutouts/mine" data-ajax="false" class="loadmore" style="font-family:Dosis, Helvetica; height:30px" data-role='button' href='#' data-theme="b" data-maxid="<?= $shoutouts[count($shoutouts)-1]->shoutoutid ?>">Load More</a></li>
<?php endif; ?>

	</ul>
<?php else: ?>
There are no shout-outs that mention you.
<?php endif; ?>
</div><!-- /collapsible -->

<!-- All shoutouts -->
<div data-role="collapsible" data-inset="true">
	<h2>All Shout-outs</h2>
<?php if(count($all_shoutouts) > 0): ?>
	<ul data-role="listview" class="listviews1" data-divider-theme="d">

<?php
$show_load_more_all = false;
if(count($all_shoutouts) == 6)
{
	array_pop($all_shoutouts);
	$show_load_more_all = true;
}

$header = '';

foreach($all_shoutouts as $shoutout):
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

<?php if($show_load_more_all): ?>
<li><a href="/student/shoutouts/friend" data-ajax="false" class="loadmore" style="font-family:Dosis, Helvetica; height:30px" data-role='button' href='#' data-theme="b" data-maxid="<?= $all_shoutouts[count($all_shoutouts)-1]->shoutoutid ?>">Load More</a></li>
<?php endif; ?>

	</ul>
<?php else: ?>
There are no shout-outs that mention your friends.
<?php endif; ?>
</div><!-- /collapsible -->

<ul data-role="listview" data-inset="true">
	<li><a href="/shoutout/send">New Shout-out</a></li>
</ul>