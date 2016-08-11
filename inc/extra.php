<?php
/**
 * Extra functions that can be commonly used by all other files.
 */


 /**
  * Display a relative time/date.
  * "4 hours ago", "3 weeks ago", etc.
  * @params $date datestring in any supported format
  * http://www.php.net/manual/en/datetime.formats.php
  */
function relative_date( $date ) {
	$now = new DateTime;
	$ago = new DateTime( $date );
	$diff = $now->diff( $ago );

	$diff->w = floor( $diff->d / 7 );
	$diff->d -= $diff->w * 7;

	$string = array(
		'y' => 'year',
		'm' => 'month',
		'w' => 'week',
		'd' => 'day',
		'h' => 'hour',
		'i' => 'minute',
		's' => 'second',
	);

	foreach ( $string as $k => &$v ) :
		if ( $diff->$k ) {
			$v = $diff->$k . ' ' . $v . ( $diff->$k > 1 ? 's' : '' );
		} else {
			unset( $string[$k] );
		}
	endforeach;

	// Remove minutes and seconds from our date.
	$string = array_slice( $string, 0, 1 );

	return $string ? implode( ', ', $string ) . ' ago' : 'just now';
}
