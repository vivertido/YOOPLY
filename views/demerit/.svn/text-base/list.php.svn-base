<script>
$(function()
{
	var reportids = [];

	$('._batchprint').on('click', function()
	{
		$('.demerits li a').each(function(i, e)
		{
			$('#print').attr('disabled', 'disabled');

			var href = $(e).attr('href');
			$(e).attr('data-href', href);
			$(e).attr('href', '#');

			$(e).removeClass('ui-btn-icon-right', 'ui-icon-carat-r');

			$('.printbar').show();
		});
	});

	$('._hideprintbar').on('click', function()
	{
		$('.printbar').hide();
		$('#reportid').val('');
		reportids = [];

		$('.selected').css('background-color','#FFFFFF').removeClass('selected');

		$('.demerits li a').each(function(i, e)
		{
			$('#print').attr('disabled', 'disabled');

			$(e).attr('href', $(e).attr('data-href'));
			$(e).removeAttr('data-href');


			$(e).addClass('ui-btn-icon-right', 'ui-icon-carat-r');

			$('.printbar').hide();
		});
	});

	$('li a').on('click', function()
	{
		if($(this).hasClass('selected'))
		{
			$(this).removeClass('selected');
			$(this).css('background-color','#FFFFFF');

			var v = $(this).attr('data-href').split(/\//);
			var index = reportids.indexOf(v[v.length-1]);

			if(index > -1) 
			{
			    reportids.splice(index, 1);
			}

			$('#reportid').val(reportids.join('|'));
			$('#count').text(reportids.length+' behavior incident'+(reportids.length != 1 ? 's' : ''));

			if(reportids.length > 0)
			{
				$('#print').removeAttr('disabled');
			}
			else
			{
				$('#print').attr('disabled', 'disabled');
			}
		}
		else
		{
			$(this).addClass('selected');
			$(this).css('background-color','#F0F0F0');

			var v = $(this).attr('data-href').split(/\//);
			reportids.push(v[v.length-1]);
			
			$('#reportid').val(reportids.join('|'));
			$('#count').text(reportids.length+' behavior incident'+(reportids.length != 1 ? 's' : ''));	

			if(reportids.length > 0)
			{
				$('#print').removeAttr('disabled');
			}
			else
			{
				$('#print').attr('disabled', 'disabled');
			}					
		}

	});
});
</script>

<style>
.printbar
{
	background-color: white; position:fixed; left: 0px; bottom: 0px; right:0px; z-index: 50; padding: 10px; padding-bottom: 70px;
	display:none;
}

.selected
{

}
</style>

Assigned : <?php
$periods = array('today' => 'Today', 'week' => 'This Week', 'month' => 'This Month', 'year' => 'This Year', 'all' => 'All');

foreach($periods as $k=>$v): ?>
<?php if($period != $k): ?><a href="/demerit/<?= $filter ?>/<?= $k ?>"><?php else: ?><b><?php endif; ?><?= $v ?><?php if($period != $k): ?></a><?php else: ?></b><?php endif; ?>&nbsp;|
<?php endforeach; ?>

<?php switch($this->session->userdata('role')):
	case 'a': $word = "No one has assigned"; break;
	case 's': $word = "You haven't been assigned"; break;
	case 't': $word = "You haven't assigned"; break;
	case 'p': $word = "Your child hasn't been assigned"; break;
endswitch; ?>

<?php if(empty($demerits)): ?>
<p><?= $word ?> any <?= htmlentities(trim($demeritlabel)) ?>s <?php switch($period):
	case 'today': echo " today"; break;
	case 'week': echo " this week"; break;
	case 'month': echo " this month"; break;
	case 'year': echo " this year"; break;
endswitch;
?>.</p>
<?php else: ?>
	<a href="#" class="_batchprint">Batch Print</a>

<ul data-role="listview" data-inset="true" class="demerits">
<?php foreach($demerits as $demerit): ?>
	<li><a href="/demerit/view/<?= $demerit->demeritid ?>">
	<h2><?= $demerit->reason ?></h2>
	<p><?php if(isset($demerit->studentfirstname)): ?>Assigned to: <?= $demerit->studentfirstname ?> <?= $demerit->studentlastname ?><br /><?php endif; ?>
  	<?php if(isset($demerit->teacherfirstname)): ?>Assigned by: <?= $demerit->teacherfirstname ?> <?= $demerit->teacherlastname ?><br /><?php endif; ?>
	<?= date('m/d g:i a', $demerit->timeincident > 0 ? $demerit->timeincident : $demerit->timecreated) ?></p></a></li>
<?php endforeach; ?>
</ul>
<?php endif; ?>
<div class="printbar">
	<form action="/demerit/school/<?= $period ?>" method="POST" data-ajax="false">
		<input type="button" style="float:right" value="Cancel" data-role="none" class="_hideprintbar"/>
		<input type="submit" id="print" name="submit" style="float:right" value="Print" data-role="none"/>
		<input type="hidden" id="reportid" name="reportid" value="" />
		You have added <span id="count">0 behavior incidents</span> to this report.
	</form>
</div>