<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
google.load('visualization', '1.0', {'packages':['corechart', 'gauge']});
google.setOnLoadCallback(drawCharts);

function drawCharts()
{
	<?php if(strpos($settings->referrals, $this->session->userdata('role')) !== false): ?>
	drawReferralThisWeekChart();
	drawReferralThisMonthChart();
	drawReferralThisYearChart();
	drawReferralTeacherChart();
	drawReferralGradeChart();
	<?php endif; ?>

	<?php if(strpos($settings->demerits, $this->session->userdata('role')) !== false): ?>
	drawDemeritThisWeekChart();
	drawDemeritThisMonthChart();
	drawDemeritThisYearChart();
	drawDemeritTeacherChart();
	drawDemeritGradeChart();
	<?php endif; ?>

	drawGuage();
}

<?php if(strpos($settings->referrals, $this->session->userdata('role')) !== false): ?>
function drawReferralThisWeekChart() {
	var data = new google.visualization.DataTable();

	data.addColumn('string', 'Incident');
	data.addColumn('number', '# Referrals');
	data.addRows([
		<?php foreach($referralsthisweek as $incident): ?>
		["<?= htmlentities($incident->incident) ?>", <?= $incident->total ?>],
		<?php endforeach; ?>
	]);

	var options = {'title':'Referrals this week',
		'width':320,
		'height':260,
		pieHole: 0.3,
		fontName:'Dosis',
		fontSize:12,
		backgroundColor: '#ffffff',
		colors: ['#DB5705', //orange
				'#696969', // darkGray
				 '#B0E0E6', //lightBlue
				 '#8B4513', //darkBrown
				  '#D2B48C', //lightBrown
				 '#A36E32', //brown
				 '#33ccff', //blue
				 '#DEB887',  //tan
				 '#5b5b5b', //darkGray
				 '#DCDCDC', //lightgray
				 '#B0E0E6',//blue
				  '#DEB887',//tan
				  '#F0FFF0', //very light gray
				  '#FFA500' //light Orange

				 ]
	};

	 

	var chart = new google.visualization.PieChart(document.getElementById('referralsthisweek'));
	chart.draw(data, options);

	 
}

function drawReferralThisMonthChart() {
	var data = new google.visualization.DataTable();

	data.addColumn('string', 'Incident');
	data.addColumn('number', '# Referrals');
	data.addRows([
		<?php foreach($referralsthismonth as $incident): ?>
		["<?= htmlentities($incident->incident) ?>", <?= $incident->total ?>],
		<?php endforeach; ?>
	]);

	var options = {'title':'Referrals this month',
		'width':320,
		'height':260,
		pieHole: 0.3,
		fontName:'Dosis',
		fontSize:12,
		backgroundColor: '#ffffff',
		colors: ['#DB5705', //orange
				'#696969', // darkGray
				 '#B0E0E6', //lightBlue
				 '#8B4513', //darkBrown
				  '#D2B48C', //lightBrown
				 '#A36E32', //brown
				 '#33ccff', //blue
				 '#DEB887',  //tan
				 '#5b5b5b', //darkGray
				 '#DCDCDC', //lightgray
				 '#B0E0E6',//blue
				  '#DEB887',//tan
				  '#F0FFF0', //very light gray
				  '#FFA500' //light Orange

				 ]
	};

	var chart = new google.visualization.PieChart(document.getElementById('referralsthismonth'));
	chart.draw(data, options);
}

function drawReferralThisYearChart() {
	var data = new google.visualization.DataTable();

	data.addColumn('string', 'Incident');
	data.addColumn('number', '# Referrals');
	data.addRows([
		<?php foreach($referralsthisyear as $incident): ?>
		["<?= htmlentities($incident->incident) ?>", <?= $incident->total ?>],
		<?php endforeach; ?>
	]);

	var options = {'title':'Referrals this year',
	    'width':320,
		'height':260,
		pieHole: 0.3,
		fontName:'Dosis',
		fontSize:12,
		backgroundColor: '#ffffff',
		colors: ['#DB5705', //orange
				'#696969', // darkGray
				 '#B0E0E6', //lightBlue
				 '#8B4513', //darkBrown
				  '#D2B48C', //lightBrown
				 '#A36E32', //brown
				 '#33ccff', //blue
				 '#DEB887',  //tan
				 '#5b5b5b', //darkGray
				 '#DCDCDC', //lightgray
				 '#B0E0E6',//blue
				  '#DEB887',//tan
				  '#F0FFF0', //very light gray
				  '#FFA500' //light Orange

				 ]
	};

	var chart = new google.visualization.PieChart(document.getElementById('referralsthisyear'));
	chart.draw(data, options);
}

function drawReferralTeacherChart() {
	var data = new google.visualization.DataTable();

	data.addColumn('string', 'Teacher');
	data.addColumn('number', '# Referrals');
	data.addRows([
		<?php foreach($referralsbyteacher as $incident): ?>
		["<?= htmlentities($incident->teachername) ?>", <?= $incident->total ?>],
		<?php endforeach; ?>
	]);

	var options = {'title':'Referrals by teacher',
		'width':320,
		'height':260,
		pieHole: 0.3,
		fontName:'Dosis',
		fontSize:12,
		backgroundColor: '#ffffff',
		colors: ['#DB5705', //orange
				'#696969', // darkGray
				 '#B0E0E6', //lightBlue
				 '#8B4513', //darkBrown
				  '#D2B48C', //lightBrown
				 '#A36E32', //brown
				 '#33ccff', //blue
				 '#DEB887',  //tan
				 '#5b5b5b', //darkGray
				 '#DCDCDC', //lightgray
				 '#B0E0E6',//blue
				  '#DEB887',//tan
				  '#F0FFF0', //very light gray
				  '#FFA500' //light Orange

				 ]
	};

	var chart = new google.visualization.PieChart(document.getElementById('referralsteacher'));
	chart.draw(data, options);
}

function drawReferralGradeChart() {
	var data = new google.visualization.DataTable();

	data.addColumn('string', 'Grade');
	data.addColumn('number', '# Referrals');
	data.addRows([
		<?php foreach($referralsbygrade as $incident): ?>
		["<?= htmlentities($incident->grade) ?>", <?= $incident->total ?>],
		<?php endforeach; ?>
	]);

	var options = {'title':'Referrals by grade',
		'width':320,
		'height':260,
		pieHole: 0.3,
		fontName:'Dosis',
		fontSize:12,
		backgroundColor: '#ffffff',
		colors: ['#DB5705', //orange
				'#696969', // darkGray
				 '#B0E0E6', //lightBlue
				 '#8B4513', //darkBrown
				  '#D2B48C', //lightBrown
				 '#A36E32', //brown
				 '#33ccff', //blue
				 '#DEB887',  //tan
				 '#5b5b5b', //darkGray
				 '#DCDCDC', //lightgray
				 '#B0E0E6',//blue
				  '#DEB887',//tan
				  '#F0FFF0', //very light gray
				  '#FFA500' //light Orange

				 ]
	};

	var chart = new google.visualization.PieChart(document.getElementById('referralsgrade'));
	chart.draw(data, options);
}
<?php endif; ?>

<?php if(strpos($settings->demerits, $this->session->userdata('role')) !== false): ?>
function drawDemeritThisWeekChart() {
	var data = new google.visualization.DataTable();

	data.addColumn('string', 'Incident');
	data.addColumn('number', '# <?= $demeritlabel ?>');
	data.addRows([
		<?php foreach($demeritsthisweek as $incident): ?>
		["<?= htmlentities($incident->reason) ?>", <?= $incident->total ?>],
		<?php endforeach; ?>
	]);

	var options = {'title':'<?= $demeritlabel ?> this week',
		'width':320,
		'height':260,
		pieHole: 0.3,
		fontName:'Dosis',
		fontSize:12,
		backgroundColor: '#ffffff',
		colors: ['#DB5705', //orange
				'#696969', // darkGray
				 '#B0E0E6', //lightBlue
				 '#8B4513', //darkBrown
				  '#D2B48C', //lightBrown
				 '#A36E32', //brown
				 '#33ccff', //blue
				 '#DEB887',  //tan
				 '#5b5b5b', //darkGray
				 '#DCDCDC', //lightgray
				 '#B0E0E6',//blue
				  '#DEB887',//tan
				  '#F0FFF0', //very light gray
				  '#FFA500' //light Orange

				 ]
	};

	var chart = new google.visualization.PieChart(document.getElementById('demeritsthisweek'));
	chart.draw(data, options);
}

function drawDemeritThisMonthChart() {
	var data = new google.visualization.DataTable();

	data.addColumn('string', 'Incident');
	data.addColumn('number', '# <?= $demeritlabel ?>');
	data.addRows([
		<?php foreach($demeritsthismonth as $incident): ?>
		["<?= htmlentities($incident->reason) ?>", <?= $incident->total ?>],
		<?php endforeach; ?>
	]);

	var options = {'title':'<?= $demeritlabel ?> this month',
		'width':320,
		'height':260,
		fontName:'Dosis',
		fontSize:12,
		backgroundColor: '#ffffff',
		 pieHole: 0.3,
		 colors: ['#DB5705', //orange
				'#696969', // darkGray
				 '#B0E0E6', //lightBlue
				 '#8B4513', //darkBrown
				  '#D2B48C', //lightBrown
				 '#A36E32', //brown
				 '#33ccff', //blue
				 '#DEB887',  //tan
				 '#5b5b5b', //darkGray
				 '#DCDCDC', //lightgray
				 '#B0E0E6',//blue
				  '#DEB887',//tan
				  '#F0FFF0', //very light gray
				  '#FFA500' //light Orange

				 ]
		  
	};

	var chart = new google.visualization.PieChart(document.getElementById('demeritsthismonth'));
	chart.draw(data, options);
}

function drawDemeritThisYearChart() {
	var data = new google.visualization.DataTable();

	data.addColumn('string', 'Incident');
	data.addColumn('number', '# <?= $demeritlabel ?>');
	data.addRows([
		<?php foreach($demeritsthisyear as $incident): ?>
		["<?= htmlentities($incident->reason) ?>", <?= $incident->total ?>],
		<?php endforeach; ?>
	]);

	var options = {'title':'<?= $demeritlabel ?> this year',
		'width':320,
		'height':260,
		fontName:'Dosis',
		fontSize:12,
		backgroundColor: '#ffffff',
		pieHole: 0.3,
		borderWidth: 0,
		colors: ['#DB5705', //orange
				'#696969', // darkGray
				 '#B0E0E6', //lightBlue
				 '#8B4513', //darkBrown
				  '#D2B48C', //lightBrown
				 '#A36E32', //brown
				 '#33ccff', //blue
				 '#DEB887',  //tan
				 '#5b5b5b', //darkGray
				 '#DCDCDC', //lightgray
				 '#B0E0E6',//blue
				  '#DEB887',//tan
				  '#F0FFF0', //very light gray
				  '#FFA500' //light Orange

				 ]

	};

	var chart = new google.visualization.PieChart(document.getElementById('demeritsthisyear'));
	chart.draw(data, options);
}

function drawDemeritTeacherChart() {
	var data = new google.visualization.DataTable();

	data.addColumn('string', 'Teacher');
	data.addColumn('number', '# <?= $demeritlabel ?>');
	data.addRows([
		<?php foreach($demeritsbyteacher as $incident): ?>
		["<?= htmlentities($incident->teachername) ?>", <?= $incident->total ?>],
		<?php endforeach; ?>
	]);

	var options = {'title':'<?= $demeritlabel ?> by teacher',
		'width':320,
		'height':260,
		pieHole: 0.3,
		fontName:'Dosis',
		fontSize:12,
		backgroundColor: '#ffffff',
		colors: ['#DB5705', //orange
				'#696969', // darkGray
				 '#B0E0E6', //lightBlue
				 '#8B4513', //darkBrown
				  '#D2B48C', //lightBrown
				 '#A36E32', //brown
				 '#33ccff', //blue
				 '#DEB887',  //tan
				 '#5b5b5b', //darkGray
				 '#DCDCDC', //lightgray
				 '#B0E0E6',//blue
				  '#DEB887',//tan
				  '#F0FFF0', //very light gray
				  '#FFA500' //light Orange

				 ]
	};

	var chart = new google.visualization.PieChart(document.getElementById('demeritsteacher'));
	chart.draw(data, options);
}

function drawDemeritGradeChart() {
	var data = new google.visualization.DataTable();

	data.addColumn('string', 'Grade');
	data.addColumn('number', '# <?= $demeritlabel ?>');
	data.addRows([
		<?php foreach($demeritsbygrade as $incident): ?>
		["<?= htmlentities($incident->grade) ?>", <?= $incident->total ?>],
		<?php endforeach; ?>
	]);

	var options = {'title':'<?= $demeritlabel ?> by grade',
		'width':320,
		'height':260,
		pieHole: 0.3,
		fontName:'Dosis',
		fontSize:12,
		backgroundColor: '#ffffff',
		colors: ['#DB5705', //orange
				'#696969', // darkGray
				 '#B0E0E6', //lightBlue
				 '#8B4513', //darkBrown
				  '#D2B48C', //lightBrown
				 '#A36E32', //brown
				 '#33ccff', //blue
				 '#DEB887',  //tan
				 '#5b5b5b', //darkGray
				 '#DCDCDC', //lightgray
				 '#B0E0E6',//blue
				  '#DEB887',//tan
				  '#F0FFF0', //very light gray
				  '#FFA500' //light Orange

				 ]
	};

	var chart = new google.visualization.PieChart(document.getElementById('demeritsgrade'));
	chart.draw(data, options);
}
<?php endif; ?>

function drawGuage() {
	var data = google.visualization.arrayToDataTable([
		['Label', 'Value'],
		<?php if(strpos($settings->detentions, $this->session->userdata('role')) !== false): ?>['Detentions', <?= $detentionstoday ?>],<?php endif; ?>
		<?php if(strpos($settings->referrals, $this->session->userdata('role')) !== false): ?>['Referrals', <?= $referralstoday ?>],<?php endif; ?>
		<?php if(strpos($settings->demerits, $this->session->userdata('role')) !== false): ?>['<?= preg_replace("/'/", "\'", $demeritlabel) ?>', <?= $demeritstoday ?>]<?php endif; ?>
		]);

	var options = {
		width: 400, height: 120,
		redColor: '#DB5705',
		yellowColor: '#33ccff',
		redFrom: 80, redTo: 100,
		yellowFrom:50, yellowTo: 79,
		minorTicks: 5
	};

	var chart = new google.visualization.Gauge(document.getElementById('gauge'));
	chart.draw(data, options);
}
</script>
<?php if(strpos($settings->referrals, $this->session->userdata('role')) !== false || 
strpos($settings->detentions, $this->session->userdata('role')) !== false ||
strpos($settings->demerits, $this->session->userdata('role')) !== false): ?>
<div>
	<h2>Daily Snapshot</h2>
	<div id="gauge" style="padding:10px;">
	</div>
</div>
<?php endif; ?>

	<style>
		#charts div
		{
			float: left;
		}
	</style>
<div id="charts">
<?php if(strpos($settings->referrals, $this->session->userdata('role')) !== false): ?>
	<div id="referralsthisweek" class="reportsChart ui-shadow"></div>
	<div id="referralsthismonth" class="reportsChart ui-shadow"></div>
	<div id="referralsthisyear" class="reportsChart ui-shadow"></div>
	<div id="referralsteacher" class="reportsChart ui-shadow"></div>
	<div id="referralsgrade" class="reportsChart ui-shadow"></div>
<?php endif; ?>	
<?php if(strpos($settings->demerits, $this->session->userdata('role')) !== false): ?>
	<div id="demeritsthisweek" class="reportsChart ui-shadow" ></div>
	<div id="demeritsthismonth" class="reportsChart ui-shadow"></div>
	<div id="demeritsthisyear" class="reportsChart ui-shadow"></div>
	<div id="demeritsteacher" class="reportsChart ui-shadow"></div>
	<div id="demeritsgrade" class="reportsChart ui-shadow"></div>	
<?php endif; ?>
</div>
