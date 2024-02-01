
<style>
	.progress{

		width:100%;
		height: 20px;
	}
	.progress-wrap{

		background: #f80;
		margin: 20px 0;
		overflow: hidden;
		position: relative;
	}
	.progress-bar {

		background :#ddd;
		left: 0;
		position: absolute;
		top:0;

	}
	.date-set-label{


		float:left;

	}
	.date-due-label{

		float:right;
	}
	.bar-label {
		 
		height: 30px;
		font-size: 80%;
	 

	}
</style>
<script type="text/javascript">
	
$(document).ready( function () {

	 moveProgressBar();

	   $(window).resize(function() {
        moveProgressBar();
    });

	 
	 function moveProgressBar(){

	 	 

	 	var getPercent =  $('.progress-wrap').data('progress-percent')  ;
	 	var getProgressWrapWidth = $('.progress-wrap').width();
	 	var progressTotal = getPercent * getProgressWrapWidth;
	 	var animationLength = 1500;

	 	$('.progress-bar').stop().animate( { left : progressTotal }, animationLength );

   
	 	}
});


</script>
<?php
$who = $student->userid == $this->session->userdata('userid') ? 'You' : $student->firstname.' '.$student->lastname;
$ownership = $student->userid == $this->session->userdata('userid') ? 'your' : 'their';
$hashave = $student->userid == $this->session->userdata('userid') ? 'have' : 'has';
//$goal->status = GOAL_STATUS_COMPLETED;
//$details->type = 'atmost';
//$details->quantity = 5;
//$details->progress = 4; 
//$objectname = 'detentions'; 
?>
<div style="background-color:#ffffff; padding:15px;" class="ui-corner-all ui-shadow">

	<h3><?= $student->firstname ?> <?= $student->lastname ?></h3>
	<p><?= $who ?> will receive <?php switch($goal->details->type):
	case 'atleast':
		echo 'at least';
	break;
	case 'atmost':
		echo 'at most';
	break;
	endswitch; ?> <?= $goal->details->quantity ?> <?= $goal->details->metric ?><?= $goal->details->quantity == 1 ? '' : 's' ?> by <?= date('m/d/Y', $goal->timedue) ?></p>

	<?php if(!empty($goal->details->notes)): ?>
		<p>Notes:<br />
			<?= preg_replace("/\n/", "<br />\n", htmlentities($goal->details->notes)) ?></p>
	<?php endif; ?>

	<p>Set by: <?= $teacher->firstname ?> <?= $teacher->lastname ?> on <?= date('m/d/Y', $goal->timecreated) ?></p>

	<hr/>
	<?php switch($goal->status): 
	case GOAL_STATUS_ACTIVE: ?>
		<h3>Progress towards goal</h3>
		<p><?= $who ?> <?= $hashave ?> <?= $goal->details->progress == 0 ? 'no' : $goal->details->progress ?> <?= $goal->details->metric ?><?= $goal->details->progress == 1 ? '' : 's' ?></p>
		<p>Remaining :  <span style="font-size:140%; color:#1CCFE7"> <?= $goal->details->quantity-$goal->details->progress ?> <?= $goal->details->metric ?><?= $goal->details->quantity == 1 ? '' : 's' ?> </span></p>
		<p><div class="progress-wrap progress" data-progress-percent="<?php echo $goal->details->progress/$goal->details->quantity ?>"><div class="progress-bar progress"></div></div>
		<p class="bar-label">  <span class="date-set-label ">  <?=  date('m/d/Y', $goal->timecreated)  ?></span><span class="date-due-label  "><?=  date('m/d/Y', $goal->timedue)  ?> </strong></span>
		 
		   
	<?php break;
	case GOAL_STATUS_COMPLETED: ?>
		<h3>Result</h3>

		<p><?= $who ?> received <?= $goal->details->progress ?> <?= $goal->details->metric ?><?= $goal->details->progress == 1 ? '' : 's' ?></p>
		<p><?php switch(true): 
			case $goal->details->type == 'atleast' && $goal->details->progress > $goal->details->quantity: ?>
			 
				Congrats! <?= $who ?> surpassed <?= $ownership ?> goal of <?= $goal->details->quantity ?> <?= $goal->details->metric ?><?= $goal->details->quantity == 1 ? '' : 's' ?><?php if(($goal->details->progress-$goal->details->quantity) > 1): ?> by <?= $goal->details->progress-$goal->details->quantity ?> <?= $goal->details->metric ?>s

			<?php endif; ?>.
			<?php 
			break;
			case $goal->details->type == 'atleast' && $goal->details->progress == $goal->details->quantity: ?>
				Congrats! <?= $who ?> met <?= $ownership ?> goal of <?= $goal->details->quantity ?> <?= $goal->details->metric ?><?= $goal->details->quantity == 1 ? '' : 's' ?>.
			<?php 
			break;
			case $goal->details->type == 'atleast' && $goal->details->progress < $goal->details->quantity: ?>
				<?= $who ?> were <?= $goal->details->quantity-$goal->details->progress ?> <?= $goal->details->metric ?><?= $goal->details->quantity == 1 ? '' : 's' ?> short of <?= $ownership ?> goal.
			<?php 
			break;
			case $goal->details->type == 'atmost' && $goal->details->progress < $goal->details->quantity: ?>
				Congrats! <?= $who ?> met <?= $ownership ?> goal of <?= $goal->details->quantity ?> <?= $goal->details->metric ?><?= $goal->details->quantity == 1 ? '' : 's' ?><?php if(($goal->details->progress-$goal->details->quantity) > 1): ?> by <?= $goal->details->progress-$goal->details->quantity ?> <?= $goal->details->metric ?>s<?php endif; ?>.
			<?php 
			break;
			case $goal->details->type == 'atmost' && $goal->details->progress == $goal->details->quantity: ?>
				Congrats! <?= $who ?> met <?= $ownership ?> goal of <?= $details->quantity ?> <?= $goal->details->metric ?><?= $goal->details->quantity == 1 ? '' : 's' ?>.
			<?php 
			break;
			case $goal->details->type == 'atmost' && $goal->details->progress > $goal->details->quantity: ?>
				<?= $who ?> exceeded <?= $ownership ?> goal of <?= $goal->details->quantity ?> <?= $goal->details->metric ?><?= $goal->details->quantity == 1 ? '' : 's' ?><?php if(($goal->details->progress-$goal->details->quantity) > 1): ?> by <?= $goal->details->progress-$goal->details->quantity ?> <?= $goal->details->metric ?>s<?php endif; ?> on <?= date('m/d/Y', $goal->timecompleted) ?>.
			
				<p><div class="progress-wrap progress" data-progress-percent="<?php echo 100 ?>" style="background:#1CCEF7"><div class="progress-bar progress"></div></div>
			<?php 
			break;
			endswitch;
			?></p>
	<?php break;
	endswitch;
	?>
</div>

<?php if(isset($candelete) && $candelete): ?>
<ul data-role="listview" data-inset="true">
	<li><a href="/goal/remove/<?= $goal->goalid ?>">Remove</a></li>
</ul>
<?php endif; ?>