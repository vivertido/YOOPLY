<?php

function pietemplate($id, $title = 'Pie Chart', $source = '')
{
	return <<<EOE
<div class="pie" data-type="Pie Chart" id="pie{$id}" data-config=".data,.object,.scope,.period,.groupby">
	<div id="pie{$id}chart">Click to select a data source</div>
	<div class="nodata" style="display:none" style="margin: 5px 10px 0px 10px">No data.</div>
	<input type="hidden" name="key[pie{$id}]" value="pie" />
	<input type="hidden" id="pie{$id}title" name="pie{$id}title" value="{$title}" />
	<input type="hidden" id="pie{$id}source" name="pie{$id}source" value="{$source}" />
</div>
EOE;
}

function linetemplate($id, $title = 'Line Chart', $source = '')
{
	return <<<EOE
<div class="line" data-type="Line Chart" id="line{$id}" data-config=".data,.object,.scope,.period,.interval">
	<div id="line{$id}chart" style="margin: 10px">Click to select a data source</div>
	<div class="nodata" style="display:none" style="margin: 5px 10px 0px 10px">No data.</div>	
	<input type="hidden" name="key[line{$id}]" value="line" />
	<input type="hidden" id="line{$id}title" name="line{$id}title" value="{$title}" />
	<input type="hidden" id="line{$id}source" name="line{$id}source" value="{$source}" />
</div>
EOE;
}

function gaugetemplate($id, $title, $source, $scale = '50/80/100')
{
	return <<<EOE
<div class="gauge" data-type="Gauge Chart" style="min-height: 260px" id="gauge{$id}" data-config=".data,.object,.scope,.period,.scale">
	<div id="gauge{$id}chart">Click to select a data source</div>
	<input type="hidden" name="key[gauge{$id}]" value="gauge" />
	<input type="hidden" id="gauge{$id}title" name="gauge{$id}title" value="{$title}" />
	<input type="hidden" id="gauge{$id}source" name="gauge{$id}source" value="{$source}" />
	<input type="hidden" id="gauge{$id}scale" name="gauge{$id}scale" value="{$scale}" />		
</div>		
EOE;
}

function sectiontemplate($id, $title = '')
{
	return <<<EOE
<div class="section" data-type="Section Title" id="section{$id}" data-config=".title" style="overflow:auto">
	<h3>{$title}</h3>
	<input type="hidden" name="key[section{$id}]" value="section" />
	<input type="hidden" id="section{$id}title" name="section{$id}title" value="{$title}" />
</div>
EOE;
}

function leaderboardtemplate($id, $title, $source)
{
	return <<<EOE
<div class="leaderboard" data-type="Leaderboard" id="leaderboard{$id}" data-config=".title,.data,.object,.scope,.period">
	<div class="header">{$title}</div>
	<ul data-role="listview" data-inset="true" id="leaderboard{$id}chart" style="margin: 5px 10px 0px 10px"></ul>
	<div class="nodata" style="display:none" style="margin: 5px 10px 0px 10px">No data.</div>
	<input type="hidden" name="key[leaderboard{$id}]" value="leaderboard" />
	<input type="hidden" id="leaderboard{$id}title" name="leaderboard{$id}title" value="{$title}" />
	<input type="hidden" id="leaderboard{$id}source" name="leaderboard{$id}source" value="{$source}" />
</div>	
EOE;
}
?>
<style>
.pie, .gauge, .section, .leaderboard, .line
{
	position:relative;
	border: 1px dashed orange;
	border-radius:5px;
}

.pie, .leaderboard, .line
{
	width: 425px;
	height: 260px;
}

.gauge
{
	 width: 160px; 
	 height: 160px;
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

.tools
{
	z-index:10;
	position:absolute;
	text-align:center;
	top:0px;
	right:0px;
	display:none;
	width:100%;
	height:100%;
	background-color:rgba(255,255,255,.5);
}

.tools a
{
	z-index: 11;
}

.leaderboard ul
{
	
}

.leaderboard .header
{
	padding-top:10px;
	font-size: 9pt;
	font-family: Arial;
	font-weight: bold;
}

</style>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
   
<script>  
google.load("visualization", "1.1", {packages:["corechart","gauge","line","bar"]});
google.setOnLoadCallback(drawChart);

function drawPie(title, chartdata, container) {
	if(chartdata.length == 0)
	{
		$('#'+container+'chart').hide().siblings('.nodata').show();
	}
	else
	{
		chartdata.unshift(['Label', 'Value']);

	    var data = google.visualization.arrayToDataTable(chartdata);

	    var options = {
	      title: title,
	      width: 425, height: 260,
	      is3D: false,
	      pieHole:0.2,
	      chartArea: {left:10, width: 480, top:30, height: 210},
	      colors:['#33ccff','#B0E0E6', '#D2B48C', '#5b5b5b', '#696969', '#DB5705', '#DEB887', '#FFA500'],
	    };

	    var chart = new google.visualization.PieChart(document.getElementById(container+'chart'));
	    chart.draw(data, options);

		$('#'+container+'chart').show().siblings('.nodata').hide();			    
	}
}

function drawGauge(title, count, container, min, mid, max) {
	var data = google.visualization.arrayToDataTable([
		['Label', 'Value'],
		[title, count]]);

	var options = {
		width: 160, height: 160,
		redColor: '#DB5705',
		yellowColor: '#33ccff',
		redFrom: mid, redTo: max,
		yellowFrom:min, yellowTo: mid,
		max: max,
		minorTicks: 5
	};

	var chart = new google.visualization.Gauge(document.getElementById(container+'chart'));
	chart.draw(data, options);
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

		chartdata.unshift(['','']);

		var data = google.visualization.arrayToDataTable(chartdata);

		var options = {
			width: 480, height: 240,
			chart: {
				title: title
			},
			chartArea: {left:10, width: 480, top:30, height: 210},
			colors: ['darkGray'],
			fontName: 'Dosis',
			legend: {
				position: 'none'
			}
		};

		var chart = new google.charts.Line(document.getElementById(container+'chart'));

		chart.draw(data, options);
		$('#'+container+'chart').show().siblings('.nodata').hide();
	}	
}

function drawLeaderboard(title, results, container)
{
	$('#'+container+'chart').empty();
	$('#'+container+'chart').listview();

	console.log(results);


	if(results.length == 0)
	{
		$('#'+container+'chart').hide().siblings('.nodata').show();
	}
	else
	{
		for(var i=0; i<results.length; i++)
		{
			$('#'+container+'chart').append('<li><img src="/images/'+results[i].profileimg+'" class="ui-li-icon">'+results[i].name+'<span class="ui-li-count">'+results[i].count+'</span></li>');
		}

		$('#'+container+'chart').show().siblings('.nodata').hide();
	}

	$('#'+container+'chart').listview('refresh');
}

function drawChart()
{
<?php $i = 1; $html = ''; foreach($results as $result): switch($result['type']): 
case 'pie':
?>
drawPie("<?= htmlentities($result['title']) ?>",[<?php 
	foreach($result['results'] as $r):
		echo '["'.preg_replace(array('/&amp;/', "/&quot;/"), array('&', "\\\""), htmlentities($r->label)).'", '.intval($r->total).'],';
	endforeach; ?>], 'pie<?= $i ?>');
<?php
	$html .= pietemplate($i, $result['title'], $result['source']);
break;
case 'gauge':
list($min, $mid, $max) = preg_split('/\//', isset($result['scale']) ? $result['scale'] : '50/80/100');
?>
drawGauge("<?= htmlentities($result['title']) ?>", <?= intval($result['total']) ?>, 'gauge<?= $i ?>', <?= intval($min) ?>, <?= intval($mid) ?>, <?= intval($max) ?>);
<?php
	$html .= gaugetemplate($i, $result['title'], $result['source'], $result['scale']);
break;
case 'leaderboard':
?>
drawLeaderboard("<?= htmlentities($result['title']) ?>", [<?php $o = ''; foreach($result['results'] as $student): $o .= <<<EOE
{name:"{$student->studentname}", profileimg: "{$student->profileimage}", count: {$student->total}, id: {$student->userid}},
EOE;
endforeach; echo trim($o, ',');?>], "leaderboard<?= $i ?>");
<?php
	$html .= leaderboardtemplate($i, $result['title'], $result['source']);
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
	$html .= sectiontemplate($i);
break;
case 'spacer':
	$html .= '<div id="spacer'.$i.'" style="clear:both"></div><input type="hidden" name="key[spacer'.$i.']" value="spacer" />';
break;
endswitch;
$i++; endforeach; ?>
}

$(function()
{
	var elementCount = <?= $i ?>;
	var activeChart = '';

	
	function addclicks(els)	
	{
		console.log(els);

		els.append('<div class="tools"><a href="#" data-role="button" data-icon="arrow-l" data-inline="true" data-iconpos="notext" title="Move element up" class="_moveup">&lt;</a><a href="#" data-role="button" data-icon="gear" data-inline="true" title="Edit data" data-iconpos="notext" class="_edit">E</a><a href="#" data-role="button" title="Delete element" data-icon="delete" data-inline="true" data-iconpos="notext" class="_delete">D</a><a href="#" data-role="button" data-icon="arrow-r" data-iconpos="notext" data-inline="true" title="Move element down" class="_movedown">&gt;</a></div>').find('a').button();

		els.find('._moveup').parent().on('click', function(event)
		{
			event.stopPropagation();
			$(this).parents('.pie, .gauge, .section, .leaderboard, .line').insertBefore($(this).parents('.pie, .gauge, .section, .leaderboard, .line').prev());
		});

		els.find('._movedown').parent().on('click', function(event)
		{
			event.stopPropagation();
			$(this).parents('.pie, .gauge, .section, .leaderboard, .line').insertAfter($(this).parents('.pie, .gauge, .section, .leaderboard, .line').next());
		});

		els.find('._delete').parent().on('click', function(event)
		{
			event.stopPropagation();
			$(this).parents('.pie, .gauge, .section, .leaderboard, .line').remove();
		});

		els.find('._edit').parent().on('click', function()
		{
			activeChart = $(this).parents('.pie, .gauge, .section, .leaderboard, .line').attr('id');

			$('#title').val($('#'+activeChart+'title').val());
			$('.data,.object,.scope,.period,.groupby,.scale,.interval').hide();
			$($(this).parents('.pie, .gauge, .section, .leaderboard, .line').attr('data-config')).show();		

			if($('#'+activeChart).hasClass('pie') || $('#'+activeChart).hasClass('leaderboard') || $('#'+activeChart).hasClass('gauge'))
			{
				var source = $('#'+activeChart+'source').val().split(/\//);

				if(source[0] == 'form')
				{
					source[0] += '/'+source.splice(1,1);
				}

				$('#object').val(source[0]).attr('selected', true).siblings('option').removeAttr('selected');
				$('#scope').val(source[1]).attr('selected', true).siblings('option').removeAttr('selected');
				$('#groupby').val(source[2]).attr('selected', true).siblings('option').removeAttr('selected');
				$('#period').val(source[3]).attr('selected', true).siblings('option').removeAttr('selected');
				$('#object, #scope, #groupby, #period').selectmenu("refresh", true);
			}				

			if($('#'+activeChart).hasClass('line'))
			{
				var source = $('#'+activeChart+'source').val().split(/\//);

				if(source[0] == 'form')
				{
					source[0] += '/'+source.splice(1,1);
				}

				$('#object').val(source[0]).attr('selected', true).siblings('option').removeAttr('selected');
				$('#scope').val(source[1]).attr('selected', true).siblings('option').removeAttr('selected');
				$('#interval').val(source[4]).attr('selected', true).siblings('option').removeAttr('selected');
				$('#period').val(source[3]).attr('selected', true).siblings('option').removeAttr('selected');
				$('#object, #scope, #interval, #period').selectmenu("refresh", true);

				console.log(source);
			}

			if($('#'+activeChart+'scale').size() != 0)
			{
				var source = $('#'+activeChart+'scale').val().split(/\//);
				$('#min').val(source[0]);
				$('#mid').val(source[1]);
				$('#max').val(source[2]);
			}

			$('#dialogtitle').text('Change '+$(this).parents('.pie, .gauge, .section, .leaderboard, .line').attr('data-type')+' Settings')


			$("#popupEdit").popup("open");
		});	

		els.on('click', function()
		{	
			$(this).find('.tools').toggle();
		});		
	}

	addclicks($('#elements .pie, #elements .gauge, #elements .section, #elements .leaderboard, #elements .line'));

	$('._savesettings').on('click', function()
	{
		$('#'+activeChart+'title').val($('#title').val());		
		$('#title').val('');

		if($('#'+activeChart).hasClass('section'))
		{
			$('#'+activeChart).find('h3').text($('#'+activeChart+'title').val());
		}

		if($('#'+activeChart).hasClass('pie'))
		{
			$('#'+activeChart+'source').val($('#object').val()+'/'+$('#scope').val()+'/'+$('#groupby').val()+'/'+$('#period').val());

			console.log('/api/chartdata/'+$('#object').val()+'/'+$('#scope').val()+'/'+$('#groupby').val()+'/'+$('#period').val());
			$.getJSON('/api/chartdata/'+$('#object').val()+'/'+$('#scope').val()+'/'+$('#groupby').val()+'/'+$('#period').val(), function(data)
			{
				$('#'+activeChart+'chart').text('loading data...');
				drawPie($('#'+activeChart+'title').val(), data.results, activeChart);
			});
		}

		if($('#'+activeChart).hasClass('line'))
		{
			$('#'+activeChart+'source').val($('#object').val()+'/'+$('#scope').val()+'/interval/'+$('#period').val()+'/'+$('#interval').val());

			console.log('/api/chartdata/'+$('#object').val()+'/'+$('#scope').val()+'/interval/'+$('#period').val()+'/'+$('#interval').val());
			$.getJSON('/api/chartdata/'+$('#object').val()+'/'+$('#scope').val()+'/interval/'+$('#period').val()+'/'+$('#interval').val(), function(data)
			{
				$('#'+activeChart+'chart').text('loading data...');
				drawLine($('#'+activeChart+'title').val(), data.results, activeChart);
			});
		}		

		if($('#'+activeChart).hasClass('leaderboard'))
		{
			$('#'+activeChart+'source').val($('#object').val()+'/'+$('#scope').val()+'/leaderboard/'+$('#period').val());
			$('#'+activeChart).find('.header').text($('#'+activeChart+'title').val());

			console.log('/api/chartdata/'+$('#object').val()+'/'+$('#scope').val()+'/leaderboard/'+$('#period').val());
			$.getJSON('/api/chartdata/'+$('#object').val()+'/'+$('#scope').val()+'/leaderboard/'+$('#period').val(), function(data)
			{
				//$('#'+activeChart+'chart').text('loading data...');
				drawLeaderboard($('#'+activeChart+'title').val(), data.results, activeChart);
			});
		}

		if($('#'+activeChart).hasClass('gauge'))
		{
			$('#'+activeChart+'source').val($('#object').val()+'/'+$('#scope').val()+'/count/'+$('#period').val());
			$('#'+activeChart+'scale').val($('#min').val()+'/'+$('#mid').val()+'/'+$('#max').val());

			console.log('/api/chartdata/'+$('#object').val()+'/'+$('#scope').val()+'/count/'+$('#period').val());
			$.getJSON('/api/chartdata/'+$('#object').val()+'/'+$('#scope').val()+'/count/'+$('#period').val(), function(data)
			{

				drawGauge($('#'+activeChart+'title').val(), data.total, activeChart, parseInt($('#min').val()), parseInt($('#mid').val()), parseInt($('#max').val()));
				console.log(data);
			});
		}		

		$("#popupEdit").popup("close");
	});

	$('._closesettings').on('click', function()
	{
		$("#popupEdit").popup("close");
	});

	$('._additem').on('click', function()
	{
		$('#elements').show();
		$('#elements').append($('#template'+$(this).attr('data-type')).html().replace(/__ID__/g, elementCount));
		
		addclicks($('#'+$(this).attr('data-type')+elementCount));
		elementCount++;

		$('#addItemMenu').popup('close');		
	});
});
</script>
<form action="/report/<?= $reportid == 'add' ? 'add/charts' : 'edit/'.$reportid ?>" method="POST" data-ajax="false">
<div id="elements" style="overflow:auto; background-color: white; padding:10px" class="ui-shadow">
	<?= $html ?>
</div>
<a href="#addItemMenu" class="_addElement" data-rel="popup" data-position-to="window" data-role="button" data-inline="true"  data-transition="fade">Add Element</a>
<div style="clear:both">
	Report Title:<br />
	<input type="text" name="reporttitle" value="<?= htmlentities($reporttitle) ?>" />

	Who can see this report?<br />
	<?php foreach(array('1000' => 'Admin', '100' => 'Teacher') as $permission=>$role): // , '10' => 'Student'?>
	<input type="checkbox" id="p<?= $permission ?>" name="viewers[]" value="<?= $permission ?>"<?= (intval($viewers) & bindec($permission)) ? ' checked="checked"' : '' ?> /><label for="p<?= $permission ?>"><?= $role ?></label>
	<?php endforeach; ?>
</div>
<input style="clear:both" type="submit" name="submit" value="Save Changes" />
</form>
<?php
$objects = array();

if(strpos($settings->referrals, $this->session->userdata('role')) !== false)
{
	$objects['referral'] = $labels->referrals;
} 

if(strpos($settings->interventions, $this->session->userdata('role')) !== false)
{
	$objects['intervention'] = 'Interventions';
}

if(strpos($settings->reinforcements, $this->session->userdata('role')) !== false)
{
	$objects['reinforcement'] = $labels->reinforcements;
}

if(strpos($settings->demerits, $this->session->userdata('role')) !== false)
{
	$objects['demerit'] = $labels->demerits;
}

if(strpos($settings->detentions, $this->session->userdata('role')) !== false)
{
	$objects['detention'] = $labels->detentions;
}

foreach($forms as $form): 
	$objects['form/'.$form->formid] = $form->title;
endforeach;


$scope = array(
	'school' => 'Schoolwide *',
	'teacher' => 'Assigned by report viewer *'
);

$period = array(
	'today' => 'Today',
	'week' => 'This Week',
	'month' => 'This Month',
	'year' => 'This Year'
);

$group_by = array(
	'reason' => 'Reason',
	'teacher.name' => 'Teacher',
	array(
		'heading' => 'Student', 
		'options' => array(
			'student.name' => 'Name',
			'student.grade' => 'Grade',
			'student.ethnicity' => 'Ethnicity',
			'student.gender' => 'Gender'
		)
	)
);


$intervals = array(
	'month' => 'Month',
	'day' => 'Day',
	'hour' => 'Hour',
);
?>


<div style="min-width: 300px" data-role="popup" id="popupEdit" data-theme="a" class="ui-corner-all" data-dismissible="false" data-history="false" style="overflow:auto">
	<a href="#" class="ui-btn ui-corner-all ui-shadow ui-btn-a ui-icon-delete ui-btn-icon-notext ui-btn-right _closesettings">Close</a>
	<form style="overflow:auto">
		<div style="padding:10px 20px;">
			<h3 id="dialogtitle">Change Pie Chart</h3>
			<label for="un" class="ui-hidden-accessible">Title:</label>
			<input type="text" id="title" value="" placeholder="" data-theme="a" />

			<div class="data">
				<label class="data" for="object">Data:</label>
				<select class="object" id="object" data-inline="true">
					<?php foreach($objects as $k=>$v): ?>
					<option value="<?= $k ?>"><?= $v ?></option>
					<?php endforeach; ?>
				</select>
				<select class="scope" id="scope" data-inline="true">
					<?php foreach($scope as $k=>$v): ?>
					<option value="<?= $k ?>"><?= $v ?></option>
					<?php endforeach; ?>
				</select>					          
				<select class="period" id="period" data-inline="true">
					<?php foreach($period as $k=>$v): ?>
					<option value="<?= $k ?>"><?= $v ?></option>
					<?php endforeach; ?>
				</select>		
			</div>

			<div class="scale">
				<label class="scale" for="min">Color:</label>
				<div class="ui-grid-b">
					<div class="ui-block-a"><input type="number" id="min" data-inline="true" value="50" maxlength="3" /></div>
					<div class="ui-block-b"><input type="number" id="mid" data-inline="true" value="80" maxlength="3" /></div>
					<div class="ui-block-c"><input type="number" id="max" data-inline="true" value="100" maxlength="3" /></div>
				</div>
			</div>

			<div class="groupby">
				<label for="groupby">Group by:</label>
				<select id="groupby" data-inline="true">
					<?php foreach($group_by as $k=>$v): if(is_array($v)): ?>
					<optgroup label="<?= $v['heading'] ?>">
						<?php foreach($v['options'] as $sk=>$sv): ?>
							<option value="<?= $sk ?>"><?= $sv ?></option>
						<?php endforeach; ?>
					</optgroup>
					<?php else: ?>
					<option value="<?= $k ?>"><?= $v ?></option>
					<?php endif; endforeach; ?>
				</select>					          
			</div>

			<div class="interval">
				<label for="interval">Interval:</label>
				<select id="interval" data-inline="true">
					<?php foreach($intervals as $k=>$v): ?>
					<option value="<?= $k ?>"><?= $v ?></option>
					<?php endforeach; ?>
				</select>					          
			</div>

			<div style="width: 100px; float:right"> 
				<input type="button" class="_savesettings" data-icon="check" value="OK" />
			</div>

			<div class="scope" style="clear:right">
				<small>* Notes about scope of the data source:<br />
				Schoolwide - includes data that viewers of the report may not normally be able to view. Each viewer will see the same graph.<br />
				Assigned by report viewer - uses data that is assigned by the viewer (a subset of the schoolwide data). Each viewer will see different graphs.</small>
			</div>
		</div>
	</form>
</div>

<div data-role="popup" id="addItemMenu" data-overlay-theme="b">
	<ul data-role="listview" data-inset="true" style="width:180px;" data-theme="b">
		<li><a class="_additem" data-before="addElement" data-type="pie">Pie Chart</a></li>
		<li><a class="_additem" data-before="addElement" data-type="gauge">Gauge</a></li>
		<li><a class="_additem" data-before="addElement" data-type="section">Section Title</a></li>
		<li><a class="_additem" data-before="addElement" data-type="leaderboard">Leaderboard</a></li>
		<li><a class="_additem" data-before="addElement" data-type="line">Line Chart</a></li>
	</ul>
</div>

<div id="templatepie" style="display:none">
	<?= pietemplate('__ID__', 'Pie Chart', '') ?>
</div>

<div id="templategauge" style="display:none">
	<?= gaugetemplate('__ID__', '', ''); ?>
</div>

<div id="templatesection" style="display:none">
	<?= sectiontemplate('__ID__', ''); ?>
</div>

<div id="templateleaderboard" style="display:none">
	<?= leaderboardtemplate('__ID__', '', ''); ?>
</div>

<div id="templateline" style="display:none">
	<?= linetemplate('__ID__', '', ''); ?>
</div>