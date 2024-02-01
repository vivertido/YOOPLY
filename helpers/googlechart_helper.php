<?php
/**
 * Google Chart API Helper
 *
 * @version 0.0.1
 * Last modified: 8/23/2011
 */

// Maximum width of image
define('CHART_MAX_WIDTH', '1000');

if(!defined('CHART_MAX_HEIGHT'))
{
	// Maximum height of image
	define('CHART_MAX_HEIGHT', '200');
}

// Length of dash
define('CHART_OPT_DASH_LENGTH', '4');

// Length of space
define('CHART_OPT_SPACE_LENGTH', '3');

/**
 * Creates an image tag referencing a Google Chart with data provided.
 *
 * @param array $values values to be charted
 * @param array $labels labels for x-axis
 * @param string $title title for chart
 * @param mixed $tick_step step interval for the y-axis.
 * @param mixed $lines_per_tick number of horizontal lines per tick (>1 multiple per tick, <0 spans over multiple ticks)
 * @param boolean $return if true, returns a string, false, outputs string
 * @return mixed if $return == true, returns string with image tag, if $return == false, returns true
 */
function line_chart($values, $labels, $title, $tick_step = .5, $lines_per_tick = 1, $return = false)
{
	$number_of_series = !is_array($values[0]) ? 1 : count($values);
	

	
	if($number_of_series > 1)
	{
		$min = min($values[0]);
		$max = max($values[0]);
		
		$chd = 't:';
		foreach($values as $series)
		{
			$min = min($series[0], $min);
			$max = max($series[0], $max);
			
			$chd .= '-1|'.implode(',', $series).'|';
		}
		
		$chd = rtrim($chd, '|');
	}
	else
	{
		$min = min($values);
		$max = max($values);

		$chd = 't:'.implode(',', $values);
	}

	$width = min(CHART_MAX_WIDTH, count($labels) * 45);
	$height = CHART_MAX_HEIGHT;

	$query = array(
		// Chart type: http://code.google.com/apis/chart/image/docs/gallery/line_charts.html#chart_types
		'cht' => 'lc',

		// Legend position: http://code.google.com/apis/chart/image/docs/gallery/line_charts.html#gcharts_legend
		'chdlp' => 'b',

		// Chart size
		'chs' => $width.'x'.$height,

		// Labels
		'chl' => implode('|', $labels),
		
		// Data
		'chd' => $chd,

		// Custom scaling
		'chds' => floor($min).','.ceil($max),

		// Visible axes: http://code.google.com/apis/chart/image/docs/gallery/line_charts.html#axis_type
		'chxt' => 'y',

		// Axis range: http://code.google.com/apis/chart/image/docs/gallery/line_charts.html#axis_range
		'chxr' => implode(',', array('0', floor($min), ceil($max), $tick_step)),

		// Grid lines: http://code.google.com/apis/chart/image/docs/gallery/line_charts.html#gcharts_grid_lines


		'chg' => implode(',', array(0, round(100/(((ceil($max)-floor($min))/$tick_step)*$lines_per_tick)), CHART_OPT_DASH_LENGTH, CHART_OPT_SPACE_LENGTH, 0, 0)),

		// Chart title: http://code.google.com/apis/chart/image/docs/gallery/line_charts.html#gcharts_chart_title
		'chtt' => $title
	);
	
	if($number_of_series > 1)
	{
	  $colors = array('FF0000','FF0000','0000FF','0000FF','00FF00');
	  $slice = min($number_of_series, count($colors));
		$query['chco'] = implode(',', array_slice($colors, 0, $slice));
	}

	$output = '<img src="http://chart.apis.google.com/chart?'.http_build_query($query).'" width="'.$width.'" height="'.$height.'" />';

	if($return)
	{
		return $output;
	}
	else
	{
		echo $output;
		return true;
	}
}
?>
