<?php
/**
 * Functionality relating to repositories.
 * Allows for listing of public repos.
 */

 /**
  * Display a list of all public repos.
  * @params $user string
  */
function show_available_repos( $user ) {
	$client = new \Github\Client();
	$repositories = $client->api( 'user' )->repositories( $user );
	echo '<dl>';
	foreach ( $repositories as $repository ) :
		echo '<dt><a href="?repo=' . $repository['full_name'] . '">' . $repository['name'] . '</a></dt>';
		echo '<dd>' . $repository['description'] . '<dd>';
	endforeach;
	echo '</dl>';
}
