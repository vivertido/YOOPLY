<?php
function get_benchmark_requirement($time, $grade)
{
	$semester = 0; switch(date('j', $time)):
	case 8:
	case 9:
	case 10:
		$semester = 0;
	break;
	case 11:
	case 12:
	case 1:
	case 2:
		$semester = 1;
	break;
	case 3:
	case 4:
	case 5:
	case 6:
	case 7:
		$semester = 2;
	break;
	endswitch;

	$benchmark = array(
		'1' => array(0, 23, 53),
		'2' => array(51, 72, 89),
		'3' => array(71, 92, 107),
		'4' => array(94, 112, 123),
		'5' => array(110, 127, 139),
		'6' => array(127, 140, 150),
		'7' => array(128, 136, 150),
		'8' => array(133, 146, 151)
	);

	// If the grade is not 1 through 8, return -1 as an invalid benchmark.
	if($grade < 1 || $grade > 8)
	{
		return -1;
	}

	return $benchmark[$grade][$semester];
}

function minutes( $seconds )
{
	return sprintf( "%02.2d:%02.2d", floor( $seconds / 60 ), $seconds % 60 );
}

function wpm($words, $time_taken)
{
	$scale = 60/$time_taken;
	return ceil($words*$scale);
}


function smaller_bytes($a_bytes)
{
    if ($a_bytes < 1024) {
        return $a_bytes .' B';
    } elseif ($a_bytes < 1048576) {
        return round($a_bytes / 1024, 2) .' KiB';
    } elseif ($a_bytes < 1073741824) {
        return round($a_bytes / 1048576, 2) . ' MiB';
    } elseif ($a_bytes < 1099511627776) {
        return round($a_bytes / 1073741824, 2) . ' GiB';
    } elseif ($a_bytes < 1125899906842624) {
        return round($a_bytes / 1099511627776, 2) .' TiB';
    } elseif ($a_bytes < 1152921504606846976) {
        return round($a_bytes / 1125899906842624, 2) .' PiB';
    } elseif ($a_bytes < 1180591620717411303424) {
        return round($a_bytes / 1152921504606846976, 2) .' EiB';
    } elseif ($a_bytes < 1208925819614629174706176) {
        return round($a_bytes / 1180591620717411303424, 2) .' ZiB';
    } else {
        return round($a_bytes / 1208925819614629174706176, 2) .' YiB';
    }
}

function get_benchmark($accuracy)
{
	switch(true)
	{
		case $accuracy >= 95:
			return 'independent';
		break;
		case $accuracy >= 90:
			return 'instructional';
		break;
		default:
			return 'frustration';
		break;
	}
}

?>