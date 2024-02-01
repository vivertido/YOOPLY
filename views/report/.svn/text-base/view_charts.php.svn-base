<?php

function pietemplate($id)
{
	return <<<EOE
<div class="pie" id="pie{$id}">
	<div id="pie{$id}chart"></div>
	<div class="nodata" style="display:none" style="margin: 5px 10px 0px 10px">No data.</div>
</div>
EOE;
}

function gaugetemplate($id)
{
	return <<<EOE
<div class="gauge" id="gauge{$id}" style="min-height: 260px">
	<div id="gauge{$id}chart"></div>
</div>		
EOE;
}

function linetemplate($id, $title = 'Line Chart', $source = '')
{
	return <<<EOE
<div class="line" id="line{$id}">
	<div id="line{$id}chart" style="margin: 10px">Click to select a data source</div>
	<div class="nodata" style="display:none" style="margin: 5px 10px 0px 10px">No data.</div>
</div>
EOE;
}

function sectiontemplate($id, $title)
{
	return <<<EOE
<div class="section" id="section{$id}">
	<h3>{$title}</h3>
</div>
EOE;
}

function leaderboardtemplate($id, $title)
{
	return <<<EOE
<div class="leaderboard" id="leaderboard{$id}">
	<div class="header">{$title}</div>
	<ul data-role="listview" data-inset="true" id="leaderboard{$id}chart" style="margin: 5px 10px 0px 10px"></ul>
	<div class="nodata" style="display:none" style="margin: 5px 10px 0px 10px">No data.</div>
</div>	
EOE;
}
?>

<style>
.pie, .gauge, .section, .leaderboard, .line
{
	position:relative;
}

.pie, .leaderboard, .line
{
	
	height:auto;
	 
	 
}

.gauge
{
	 width: 160px; 
	 height: 160px;
	 margin-right:35px;
	 margin-bottom:-40px;
}

.section
{
	clear:both; 
	padding-top: 10px;
}

.pie, .gauge, .leaderboard, .line
{
	float:left;
}
.pie{


	height:280px;
}

.leaderboard
{
	padding-right: 10px;
}

.leaderboard .header
{
	padding-top:10px;
	font-size: 9pt;
	font-family: Arial;
	font-weight: bold;
}
.line{

	 width: 90%; 
	 height: 260px;
	  
}

@media screen and (max-width: 360px) {

	.ui-controlgroup, fieldset.ui-controlgroup, .change-chart-LBL  {


			visibility: hidden;
			margin-bottom:-30px;
		}
 


}
</style>

<script type="text/javascript" src="https://www.google.com/jsapi"></script>
   
<script> 



var pieWidth=480;
var pieHeight=280;

var gagueWidth=160;
var gagueHeight=160;
 
var lineChartWidth=800;
var lineChartHeight=240; 

if ($(window).width() < 960) {
   

   pieWidth=280;
 pieHeight=120;

 gagueWidth=160;
 gagueHeight=160;
 
 lineChartWidth=800;
 lineChartHeight=240; 

 $('input').toggle();

 $('.leaderboard').css({'width':'90%'});
}
else {
    
}



$(document).ready(function(){

	console.log('clicked');

  $("[name=radio-choice-h-6]").change(function(event) {  
        
     var value= $("input[name=radio-choice-h-6]:checked").val();
    

     if (value<2){

     	console.log('ONE');


     	 $('.pie').css({'width':'90%'});
     	 pieWidth=800;
     	 pieHeight=460;

     	  $('.guage').css(
     	  	{'width':'40%'});

     	  gagueWidth=200;
		  gagueHeight=200;

		  $('.leaderboard').css({'width':'80%'});

		  lineChartWidth=780;

		  $('.line').css({'width':'80%'});


     	 drawChart();


     }else if (value == 2){

     	 $('.pie').css({'width':'40%'  });
     	 pieWidth=480;
     	 pieHeight=240;

     	  $('.guage').css({'width':'20%'});

     	  gagueWidth=160;
		  gagueHeight=160;

		  $('.leaderboard').css({'width':'40%'});

		  lineChartWidth=480;
		$('.line').css({'width':'40%'});

     	  
     	 drawChart();
     	console.log('we have a TWO');

     }else if (value >2){

     	 $('.pie').css({'width':'30%' });
     	 pieWidth=480;
     	 pieHeight=210;

     	  $('.guage').css({'width':'15%'});

     	  gagueWidth=140;
		  gagueHeight=140;

		   $('.leaderboard').css({'width':'30%'});

		   lineChartWidth=200;
		   $('.line').css({'width':'30%'});

     	 drawChart();
     	  ;
     	console.log('we have a 3');

     }


});  


   

}); 


google.load("visualization", "1", {packages:["corechart","gauge","line","bar"]});
google.setOnLoadCallback(drawChart);



function drawPie(title, chartdata, container) {
	if(chartdata.length == 0)
	{
		$('#'+container+'chart').hide().siblings('.nodata').show();
	}
	else
	{	
		chartdata.unshift(['Label', 'Value']);
		var data = new google.visualization.DataTable();
		
	    var data = google.visualization.arrayToDataTable(chartdata);

	    var options = {
	      title: title,
	      is3D: false,
	      pieHole:0.3,
	      chartArea: {left:10, width: pieWidth, top:30, height: pieHeight},
	      colors:['#33ccff','#B0E0E6', '#D2B48C', '#5b5b5b', '#696969', '#DB5705', '#DEB887', '#FFA500'],
	    };

	    var chart = new google.visualization.PieChart(document.getElementById(container));
	    chart.draw(data, options);

	    $('#'+container+'chart').show().siblings('.nodata').hide();
	}
}

function drawGauge(title, count, container, min, mid, max) {
	var data = google.visualization.arrayToDataTable([
		['Label', 'Value'],
		[title, count]]);

	var options = {
		width: gagueWidth, height: gagueHeight,
		redColor: '#DB5705',
		yellowColor: '#33ccff',
		redFrom: mid, redTo: max,
		yellowFrom:min, yellowTo: mid,
		max:max,
		minorTicks: 5
	};

	var chart = new google.visualization.Gauge(document.getElementById(container));
	chart.draw(data, options);
}

function drawLeaderboard(title, results, container)
{
	$('#'+container).empty();
	$('#'+container).listview();

	console.log(results);


	if(results.length == 0)
	{
		$('#'+container).hide().siblings('.nodata').show();
	}
	else
	{
		for(var i=0; i<results.length; i++)
		{
			$('#'+container).append('<li><img src="/images/'+results[i].profileimg+'" class="ui-li-icon">'+results[i].name+'<span class="ui-li-count">'+results[i].count+'</span></li>');
		}

		$('#'+container).show().siblings('.nodata').hide();
	}

	$('#'+container).listview('refresh');
	$('#'+container+'title').find('p').text(title);
}

function drawLine(title, chartdata, container)
{
	if(chartdata.length == 0)
	{
		$('#'+container+'chart').hide().siblings('.nodata').show();
	}
	else
	{	
		if(chartdata.length == 1)
		{
			chartdata.unshift(['', 0]);
		}

		chartdata.unshift(['', '']);

		var data = google.visualization.arrayToDataTable(chartdata);

		var options = {
			width: lineChartWidth, height: lineChartHeight,
			chart:
			{
				title: title
			},
			chartArea: {left:10, width: lineChartWidth, top:30, height: lineChartHeight},
			colors: ['darkGray'],
			fontName: 'Dosis',
			fontSize: 12,
			legend: {
				position: 'none'
			},
		};

		var chart = new google.charts.Line(document.getElementById(container+'chart'));

		chart.draw(data, options);	

		$('#'+container+'chart').show().siblings('.nodata').hide();		
	}
}

function drawChart()
{
	 
<?php $i = 1; $html = ''; foreach($results as $result): switch($result['type']): 
case 'pie':
?>
drawPie("<?= htmlentities($result['title']) ?>",[<?php 
	foreach($result['results'] as $r):
		echo '["'.preg_replace(array('/&amp;/', "/&quot;/"), array('&', "\\\""), htmlentities($r->label)).'", '.intval($r->total).'],';
	endforeach; ?>], "pie<?= $i ?>");
<?php
	$html .= pietemplate($i);
break;
case 'gauge':
list($min, $mid, $max) = preg_split('/\//', isset($result['scale']) ? $result['scale'] : '50/80/100');
?>
drawGauge("<?= htmlentities($result['title']) ?>", <?= intval($result['total']) ?>, "gauge<?= $i ?>", <?= intval($min) ?>, <?= intval($mid) ?>, <?= intval($max) ?>);
<?php
	$html .= gaugetemplate($i);
break;
case 'leaderboard':
?>
drawLeaderboard("<?= htmlentities($result['title']) ?>", [<?php $o = ''; foreach($result['results'] as $student): $o .= <<<EOE
{name:"{$student->studentname}", profileimg: "{$student->profileimage}", count: {$student->total}, id: {$student->userid}},
EOE;
;
endforeach; echo trim($o, ',');?>], "leaderboard<?= $i ?>chart");
<?php
	$html .= leaderboardtemplate($i, $result['title']);
break;
case 'line':
?>
drawLine("<?= htmlentities($result['title']) ?>",[<?php 
	foreach($result['results'] as $r):
		echo '["'.preg_replace(array('/&amp;/', "/&quot;/"), array('&', "\\\""), htmlentities($r->label)).'", '.intval($r->total).'],';
	endforeach; ?>], 'line<?= $i ?>');
<?php
	$html .= linetemplate($i, $result['title'], $result['source']);
break;
case 'section':
	$html .= sectiontemplate($i, $result['title']);
break;
case 'spacer':
	$html .= '<div style="clear:both"></div>';
break;
endswitch;
$i++; endforeach; ?>
}
</script>
<script>


</script>

<h3 class="change-chart-LBL">Change Chart Size</h3>
<form>
<fieldset class="change-size-radio" data-role="controlgroup" data-type="horizontal" data-mini="true">
    
    <input type="radio" name="radio-choice-h-6" id="radio-choice-h-6a" value="1" >
    <label for="radio-choice-h-6a">1</label>
    <input type="radio" name="radio-choice-h-6" id="radio-choice-h-6b" value="2" checked="checked">
    <label for="radio-choice-h-6b">2</label>
    <input type="radio" name="radio-choice-h-6" id="radio-choice-h-6c" value="3">
    <label for="radio-choice-h-6c">3</label>
</fieldset>
</form>

 
<div style="background-color: white; overflow:auto; padding:10px" class="ui-shadow">
	<?= $html ?>
</div>
<?php if($this->session->userdata('role') == 'a'): ?>
<div style="clear:both; padding-top: 10px">
	<ul data-role="listview" data-inset="true" >
		<li><a href="/report/edit/<?= $reportid ?>" data-ajax="false">Edit</a></li>
		<li><a href="/report/remove/<?= $reportid ?>" data-ajax="false">Delete</a></li>
	</ul>
</div>
<?php endif; ?>