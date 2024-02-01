<?php if(count($detentions) == 0): ?>
<div style="text-align:center">
	<img src="/images/smileyface.png" /><br /><br />
	You have been a very good student. You haven't had a single <?= htmlentities($labels->detentionunit) ?>!
</div>

<?php else: ?>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
function drawChart() {
	var data = google.visualization.arrayToDataTable([
		['Month', '<?= htmlentities($labels->detentionunits) ?>', ],
		<?php foreach($assignedtotals as $month): ?>
		['<?= $month->month ?>.',  <?= $month->total ?> ,],

		<?php endforeach; ?>
	]);

	var options = {
		'colors':['#DB5705'],
		'title': '<?= htmlentities($labels->detentions) 	?> by month',
		'legend':{position: 'bottom', textStyle: {color: 'blue', fontSize: 16}}
	};

	var chart = new google.visualization.BarChart(document.getElementById('chart_div2'));
	chart.draw(data, options);
}
/* draws the Monthly Total */
function drawByReason() {
	console.log('drawByClass');
	var data = google.visualization.arrayToDataTable([
		['Month', '<?= htmlentities($labels->detentionunits) ?>', ],
		<?php foreach($assignedtotals as $week): ?>
		['<?= $month->month ?>.',  <?= $week->total ?> ,],

		<?php endforeach; ?>
	]);

	var options = {
		colors:['#33ccff','#B0E0E6', '#D2B48C', '#5b5b5b', '#696969', '#DB5705', '#DEB887', '#FFA500'],
		'title': '<?= htmlentities($labels->detentions) 	?> by reason',
		pieHole:0.4,
		 
		'legend':{position: 'right', textStyle: {color: '#B0E0E6', fontSize: 16}}
	};

	var chart = new google.visualization.PieChart(document.getElementById('by_reason'));
	chart.draw(data, options);

}

function drawByClass() {

	 var data = google.visualization.arrayToDataTable([
        ["Class", "number", { role: "style" } ],
        ["English", 1 , 'orange'],
        ["Economics1", 3 , 'orange'],
        ["Geometry", 4 , 'orange'] 
         
      ]);

      var view = new google.visualization.DataView(data);
      view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" },
                       2]);

      var options = {
        title: "Detentions by Class",
        
        bar: {groupWidth: "40%"},
        legend: { position: "none" },
        annotations: {
    	textStyle: {
	      fontName: 'Dosis',
	      fontSize: 18,
	      bold: true,
	      italic: false,
	      // The color of the text.
	      color: '#fff',
	      // The color of the text outline.
	      auraColor: '#fff',
	      // The transparency of the text.
       
    	}
  }
      };
      var chart = new google.visualization.ColumnChart(document.getElementById("columnchart_values"));
      chart.draw(view, options);
}


$(document).ready(function () {
	$(window).resize(function(){
		drawByReason();
		drawChart();
		
		drawByClass();

		
	});
});

google.load("visualization", "1", {packages:["corechart"]});
google.setOnLoadCallback(drawChart);
google.setOnLoadCallback(drawByReason);
google.setOnLoadCallback(drawByClass);
</script>

<div class="ui-grid-a">
	<div><h3 style="font-weight:normal; text-align:right">Unserved: <strong><?= $balance ?></strong> <?= htmlentities($balance == 1 ? $labels->detentionunit : $labels->detentionunits) ?></h3></div>
</div>

 

<ul data-role="listview" class="ui-shadow" data-inset="true">
<?php $header = ''; foreach($detentions as $detention):
$h = time_elapsed_term_string($detention->timecreated);

if($header != $h): $header = $h; ?>
	<li data-role="list-divider"><?= $header ?></li>
<?php endif; ?>
	<li>
		<h2 style="font-size:110%"><?= $detention->reason ?></h2>
		<p style="font-size:110%"><strong>Assigned by:</strong> <?= $detention->teacherfirstname ?> <?= $detention->teacherlastname ?></p>
		<p class="ui-li-aside" style="font-size:80%"><strong><?= $detention->minutes ?></strong> <?= htmlentities($detention->minutes == 1 ? $labels->detentionunit : $labels->detentionunits) ?></p>
	</li>
<?php endforeach; ?>
</ul>

<div  data-content-theme="c">
	<h2>Yearly Total</h2>
	<div class="ui-shadow" id="chart_div2"></div>
	<p>So far this year you have served <?= $totalserved ?> <?= htmlentities($totalserved == 1 ? $labels->detentionunit : $labels->detentionunits) ?></p>
</div>

 <!-- __________________________________MOCKUPS______________________________ -->

<div    data-content-theme="c">
	<h2>By Reason</h2>
	<div  style="height:400px; width:90%" class="ui-shadow" id="by_reason"></div>
	<p>So far this year you have served <?= $totalserved ?> <?= htmlentities($totalserved == 1 ? $labels->detentionunit : $labels->detentionunits) ?></p>
</div>
<div  data-content-theme="c">
	<h2>By Class</h2>
	<div  class="ui-shadow" id="columnchart_values"></div>
	 <p>So far this year you have served <?= $totalserved ?> <?= htmlentities($totalserved == 1 ? $labels->detentionunit : $labels->detentionunits) ?></p>
</div>
<?php endif; ?>