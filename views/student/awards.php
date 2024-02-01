<?php
//$reinforcements = array(); // uncomment to test no reinforcements for student
if(empty($reinforcements)): ?>
You haven't earned any <?= htmlentities($dollarlabel) ?> yet!
<?php else: ?>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">


function drawChart() {
	var data = google.visualization.arrayToDataTable([
	['Month', '$'],
	<?php foreach($totals as $month): ?>
		['<?= $month->month ?>.',  <?= $month->total ?>],
	<?php endforeach; ?>
	]);

	var options = {
	  
	 backgroundColor: '#bbbbbb',
	 colors: ['#603813','#38e0FF']
	
	};

	var chart = new google.visualization.LineChart(document.getElementById('awardschart_div'));
	chart.draw(data, options);
}


$(document).ready(function () {
	$(window).resize(function(){
		drawChart();
	});
});



google.load("visualization", "1", {packages:["corechart"]});
google.setOnLoadCallback(drawChart);

</script>
<div class="ui-grid-a">
<div style="float:left; width: 28%; text-align: center"><span style="font-size: 20pt">$<?= $dollars-$spent ?></span><br />available</div>
<div style="float:left; width: 28%; text-align: center"><span style="font-size: 20pt">$<?= $spent ?></span><br />spent</div>
<div style="float:left; width: 42%; text-align: center"><span style="font-size: 20pt">$<?= $dollars ?></span><br />earned this year</div>
</div>
<ul data-role="listview" data-inset="true">
<?php $header = ''; foreach($reinforcements as $reinforcement):
$h = time_elapsed_term_string($reinforcement->timecreated);

if($header != $h) : $header = $h; ?>
	<li data-role="list-divider"><?= $header ?></li>
<?php endif; ?>
	<li ><h3 style="white-space: normal">$1 - <?= $reinforcement->reason ?> - <?= $reinforcement->teacherfirstname ?> <?= $reinforcement->teacherlastname ?></li>
<?php endforeach; ?>
</ul>

<h3>This year</h3>
<div id="awardschart_div"></div>

<?php endif; ?>