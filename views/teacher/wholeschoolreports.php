<script type="text/javascript" src="https://www.google.com/jsapi"></script>

<script type="text/javascript">

function drawDetentionsByReasonChart()
{
	var data = new google.visualization.DataTable();
	data.addColumn('string', 'Topping');
	data.addColumn('number', 'Slices');
	data.addRows([
		<?php foreach($detention_categories_this_week as $incident): ?>
		["<?= htmlentities($incident->reason) ?>", <?= $incident->total ?>],
		<?php endforeach; ?>
	]);

	var options = {'title':'Detentions by Reason',
		backgroundColor: '#ffffff',
		pieHole:0.2;
		colors:['#33ccff','#B0E0E6', '#D2B48C', '#5b5b5b', '#696969', '#f0f4f4', '#DB5705', '#DEB887', '#FFA500']
	};

	var chart = new google.visualization.PieChart(document.getElementById('detentionsbyreasonchart'));
	chart.draw(data, options);
}

function drawDetentionsByAmountChart() {
	var data = google.visualization.arrayToDataTable([
		['Day', 'Detentions'],
		<?php foreach($detentions_this_week as $incident): ?>
		["<?= date('D', $incident->date) ?>", <?= $incident->total ?>],
		<?php endforeach; ?>
	]);

	var options = {
		title: 'Detentions by Day',
		legend: {position: 'top', textStyle: {color: '#603813', fontSize: 10}},
		backgroundColor: '#E0D6CC',
		colors: ['#6e4015', '#38e0FF'],
		fontName: 'Dosis',
		vAxis: {title: 'Day',  titleTextStyle: {color: '#603813'}}
	};

	var chart = new google.visualization.BarChart(document.getElementById('detentionsbyamountchart'));
	chart.draw(data, options);
}

function drawReferralsByReasonChart()
{
	var data = google.visualization.arrayToDataTable([
		['Type of Referral', 'Total'],
		<?php foreach($referral_categories_this_week as $incident): ?>
		["<?= htmlentities($incident->incident) ?>", <?= $incident->total ?>],
		<?php endforeach; ?>
	]);

	var options = {'title':'Referrals by Reason',
		backgroundColor: '#E0D6CC',
		colors:['#38e0FF','#858585', '#3e240c', '#2db7e5', '#c69c6d', '#cccccc', '#7cc4e7', '#6e4015', '#1c708c']
	};

	var chart = new google.visualization.PieChart(document.getElementById('referralsbyreasonchart'));
	chart.draw(data, options);
}

function drawReferralsByAmountChart() {
	var data = google.visualization.arrayToDataTable([
		['Day', 'Referrals'],
		<?php foreach($referrals_this_week as $incident): ?>
		["<?= date('D', $incident->date) ?>", <?= $incident->total ?>],
		<?php endforeach; ?>
	]);

	var options = {
		title: 'Referrals by Day',
		legend: {position: 'top', textStyle: {color: '#603813', fontSize: 10}},
		backgroundColor: '#E0D6CC',
		colors: ['#6e4015', '#38e0FF'],
		fontName: 'Dosis',
		vAxis: {title: 'Day',  titleTextStyle: {color: '#603813'}}
	};

	var chart = new google.visualization.BarChart(document.getElementById('referralsbyamountchart'));
	chart.draw(data, options);
}

function drawReinforcementsByReasonChart()
{
	var data = google.visualization.arrayToDataTable([
		['Type of Reinforcement', 'Total'],
		<?php foreach($reinforcement_categories_this_week as $incident): ?>
		["<?= htmlentities($incident->reason) ?>", <?= $incident->total ?>],
		<?php endforeach; ?>
	]);

	var options = {'title':'Reinforcements by Reason',
		backgroundColor: '#E0D6CC',
		colors:['#38e0FF','#858585', '#3e240c', '#2db7e5', '#c69c6d', '#cccccc', '#7cc4e7', '#6e4015', '#1c708c']
	};

	var chart = new google.visualization.PieChart(document.getElementById('reinforcementsbyreasonchart'));
	chart.draw(data, options);
}

function drawReinforcementsByAmountChart() {
	var data = google.visualization.arrayToDataTable([
		['Day', 'Reinforcements'],
		<?php foreach($reinforcements_this_week as $incident): ?>
		["<?= date('D', $incident->date) ?>", <?= $incident->total ?>],
		<?php endforeach; ?>
	]);

	var options = {
		title: 'Reinforcements by Day',
		legend: {position: 'top', textStyle: {color: '#603813', fontSize: 10}},
		backgroundColor: '#E0D6CC',
		colors: ['#6e4015', '#38e0FF'],
		fontName: 'Dosis',
		vAxis: {title: 'Day',  titleTextStyle: {color: '#603813'}}
	};

	var chart = new google.visualization.BarChart(document.getElementById('reinforcementsbyamountchart'));
	chart.draw(data, options);
}

function drawCharts()
{
	drawDetentionsByReasonChart();
	drawDetentionsByAmountChart();
	drawReferralsByReasonChart();
	drawReferralsByAmountChart();
	drawReinforcementsByReasonChart();
	drawReinforcementsByAmountChart();
}

google.load('visualization', '1.0', {'packages':['corechart']});
google.setOnLoadCallback(drawCharts);

$(document).ready(function () {
	$(window).resize(function(){
		drawCharts();
	});
});

</script>


<div data-role="collapsible-set" >
	<div data-role="collapsible" data-collapsed="true">

	<h3>Detentions</h3>

	<!--<div data-role="controlgroup" data-type="horizontal" data-mini="true">
		<a href="#" data-role="button" data-theme="c">Past 7 Days</a>
		<a href="#" data-role="button">Past 30 Days</a>
		<a href="#" data-role="button">Year To Date</a>
	</div>-->

	<div id="detentionsbyreasonchart" style="width: 100%;"> </div>
	<hr>
	<div id="detentionsbyamountchart"></div>
</div>

<div data-role="collapsible" data-collapsed="true" >
	<h3>Referrals</h3>
	<!--<div data-role="controlgroup" data-type="horizontal" data-mini="true">
		<a href="#" data-role="button" data-theme="c">Past 7 Days</a>
		<a href="#" data-role="button">Past 30 Days</a>
		<a href="#" data-role="button">Year To Date</a>
	</div>-->

	<div id="referralsbyreasonchart" style="width: 100%"></div>
	<hr>
	<div id="referralsbyamountchart"></div>
</div>

<div data-role="collapsible" data-collapsed="true" >
	<h3>Reinforcements</h3>
	<!--<div data-role="controlgroup" data-type="horizontal" data-mini="true">
		<a href="#" data-role="button" data-theme="c">Past 7 Days</a>
		<a href="#" data-role="button">Past 30 Days</a>
		<a href="#" data-role="button">Year To Date</a>
	</div>-->

	<div id="reinforcementsbyreasonchart" style="width: 100%"></div>
	<hr>
	<div id="reinforcementsbyamountchart"></div>
</div>

<h3>Leader Board</h3>
<div data-role="collapsible-set" >
	<div data-role="collapsible" data-collapsed="true">
		<h3>Most Detentions</h3>
		<ul data-role="listview" data-filter="true" class="aPerson">
			<?php foreach($top_detentions as $student): ?>
			<li><a href="/student/view/<?= $student->userid ?>"><img src="/images/<?= $student->profileimage ?>" class=ui-li-icon><?= $student->studentname ?> - <?= $student->total ?> min. </a></li>
			<?php endforeach; ?>
		</ul>
	</div>

	<div data-role="collapsible" data-collapsed="true">
		<h3>Most Referrals</h3>
		<ul data-role="listview" data-filter="true" class="aPerson">
			<?php foreach($top_referrals as $student): ?>
			<li><a href="/student/view/<?= $student->userid ?>"><img src="/images/<?= $student->profileimage ?>" class=ui-li-icon><?= $student->studentname ?> - <?= $student->total ?></a></li>
			<?php endforeach; ?>
		</ul>
	</div>

	<div data-role="collapsible" data-collapsed="true">
		<h3>Most Scholar Dollars Earned</h3>
		<ul data-role="listview" data-filter="true" class="aPerson">
			<?php foreach($top_reinforcements as $student): ?>
			<li><a href="/student/view/<?= $student->userid ?>"><img src="/images/<?= $student->profileimage ?>" class=ui-li-icon><?= $student->studentname ?> - $<?= $student->total ?></a></li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>
