<script type="text/javascript" src="https://www.google.com/jsapi"></script>

<script type="text/javascript">

<?php if(strpos($features->detentions, $this->session->userdata('role')) !== false): ?>
function drawDetentionsByReasonChart()
{
	var data = google.visualization.arrayToDataTable([
		['Reason', 'Total'],
		<?php foreach($detention_categories_this_week as $incident): ?>
		["<?= htmlentities($incident->reason) ?>", <?= $incident->total ?>],
		<?php endforeach; ?>
	]);

	var options = {'title':'Detentions by Reason',
		backgroundColor: '#E0D6CC',
		colors:['#38e0FF','#858585', '#3e240c', '#2db7e5', '#c69c6d', '#cccccc', '#7cc4e7', '#6e4015', '#1c708c']
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
<?php endif; ?>

<?php if(strpos($features->referrals, $this->session->userdata('role')) !== false): ?>
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
<?php endif; ?>

<?php if(!empty($reinforcement_categories_this_week)): ?>
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

<?php endif; ?>

function drawCharts()
{
	<?php if(strpos($features->detentions, $this->session->userdata('role')) !== false): ?>
	drawDetentionsByReasonChart();
	drawDetentionsByAmountChart();
	<?php endif; ?>

	<?php if(strpos($features->referrals, $this->session->userdata('role')) !== false): ?>
	drawReferralsByReasonChart();
	drawReferralsByAmountChart();
	<?php endif; ?>
	
	<?php if(!empty($reinforcement_categories_this_week)): ?>
	drawReinforcementsByReasonChart();
	drawReinforcementsByAmountChart();
	<?php endif; ?>
}

google.load('visualization', '1.0', {'packages':['corechart']});
google.setOnLoadCallback(drawCharts);

$(document).ready(function () {
	$(window).resize(function(){
		drawCharts();
	});
});

</script>


<?php if(strpos($features->detentions, $this->session->userdata('role')) !== false): ?>
	<div data-role="collapsible-set" >
	<div data-role="collapsible" data-collapsed="true">

	<h3>Detentions</h3>

	<div data-role="controlgroup" data-type="horizontal" data-mini="true">
		<a href="#" data-role="button" data-theme="c">Past 7 Days</a>
	</div>

	<div id="detentionsbyreasonchart" style="width: 100%;"> </div>
	<hr>
	<div id="detentionsbyamountchart"></div>
</div>
<?php endif; ?>

<?php if(strpos($features->referrals, $this->session->userdata('role')) !== false): ?>
<div data-role="collapsible" data-collapsed="true" >
	<h3>Referrals</h3>
	<div data-role="controlgroup" data-type="horizontal" data-mini="true">
		<a href="#" data-role="button" data-theme="c">Past 7 Days</a>
	</div>

	<div id="referralsbyreasonchart" style="width: 100%"></div>
	<hr>
	<div id="referralsbyamountchart"></div>
</div>
<?php endif; ?>

<div data-role="collapsible" data-collapsed="true" >
	<h3>Reinforcements</h3>
	<?php if(!empty($reinforcement_categories_this_week)): ?>
		<div data-role="controlgroup" data-type="horizontal" data-mini="true">
			<a href="#" data-role="button" data-theme="c">Past 7 Days</a>
		</div>

		<div id="reinforcementsbyreasonchart" style="width: 100%"></div>
		<hr>
		<div id="reinforcementsbyamountchart"></div>
	<?php else: ?>
		No reinforcements found.
	<?php endif; ?>
</div>

<h3>Leader Board</h3>
<div data-role="collapsible-set" >
	<?php if(strpos($features->detentions, $this->session->userdata('role')) !== false): ?>
		<div data-role="collapsible" data-collapsed="true">
			<h3>Most Detentions</h3>
			<ul data-role="listview" data-filter="true" class="aPerson">
				<?php foreach($top_detentions as $student): ?>
				<li><a href="/student/view/<?= $student->userid ?>"><img src="/images/<?= $student->profileimage ?>" class=ui-li-icon><?= $student->studentname ?> - <?= $student->total ?> min. </a></li>
				<?php endforeach; ?>
			</ul>
		</div>
	<?php endif; ?>

	<?php if(strpos($features->referrals, $this->session->userdata('role')) !== false): ?>
		<div data-role="collapsible" data-collapsed="true">
			<h3>Most Referrals</h3>
			<ul data-role="listview" data-filter="true" class="aPerson">
				<?php foreach($top_referrals as $student): ?>
				<li><a href="/student/view/<?= $student->userid ?>"><img src="/images/<?= $student->profileimage ?>" class=ui-li-icon><?= $student->studentname ?> - <?= $student->total ?></a></li>
				<?php endforeach; ?>
			</ul>
		</div>
	<?php endif; ?>

	<div data-role="collapsible" data-collapsed="true">
		<h3>Most Scholar Dollars Earned</h3>
		<?php if(empty($top_reinforcements)): ?>
		There are no students with Scholar Dollars
		<?php else: ?>
			<ul data-role="listview" data-filter="true" class="aPerson">
				<?php foreach($top_reinforcements as $student): ?>
				<li><a href="/student/view/<?= $student->userid ?>"><img src="/images/<?= $student->profileimage ?>" class=ui-li-icon><?= $student->studentname ?> - $<?= $student->total ?></a></li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</div>
</div>
