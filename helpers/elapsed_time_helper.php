<?php
function time_elapsed_string($ptime, $now = 0, $ago = 'ago')
{
		$now = $now == 0 ? time() : $now;

    $etime = $now - $ptime;

    if ($etime < 1)
    {
        return '0 seconds';
    }

    $a = array( 12 * 30 * 24 * 60 * 60  =>  'year',
                30 * 24 * 60 * 60       =>  'month',
                24 * 60 * 60            =>  'day',
                60 * 60                 =>  'hour',
                60                      =>  'minute',
                1                       =>  'second'
                );

    foreach ($a as $secs => $str)
    {
        $d = $etime / $secs;
        if ($d >= 1)
        {
            $r = round($d);
            return $r . ' ' . $str . ($r > 1 ? 's' : '') . ' '.$ago;
        }
    }
}

function time_elapsed_term_string($time)
{
	switch(true)
	{
		case date('m/d/Y') == date('m/d/Y', $time):
			return 'Today';
		break;
		case (date('z')-1) == date('z', $time):
			return 'Yesterday';
		break;
		case date('W') == date('W', $time):
			return 'This Week';
		break;
		case (date('W')-1) == date('W', $time):
			return 'Last Week';
		break;
		case date('n') == date('n', $time):
			return 'This Month';
		break;
		case (date('n')-1) == date('n', $time):
			return 'Last Month';
		break;
		default:
			return date('F Y', $time);
		break;
	}
}

function addOrdinalNumberSuffix($num)
{
	if (!in_array(($num % 100),array(11,12,13))){
	switch ($num % 10) {
	// Handle 1st, 2nd, 3rd
		case 1:  return $num.'st';
		case 2:  return $num.'nd';
		case 3:  return $num.'rd';
		}
	}

	return $num.'th';
}

?>