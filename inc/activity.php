<?php
/**
 * Functionality relating to repository activity.
 * Stuff like commits, comments, etc.
 */

 /**
  * Get number of commits made this week.
  * @params $user string $repo string $period string
  */
 function number_commits( $user, $repo, $period = 'week' ) {
	$client = new \Github\Client();
	$commits = $client->api( 'repo' )->commits()->all( $user, $repo, array('sha' => 'master' ) );

	$commitcount = 0;

	// Cycle through each commit. If it was made in the specified period,
	// increment our count by one. A bit hacky, but it works.
	// Note: might not work if there are a lot of commits—I'm not sure the API gets *everything*.
	foreach ( $commits as $commit ) :
		if ( is_in_date_period( $commit['commit']['committer']['date'], $period ) ) :
			$commitcount++;
		else :
			break;
		endif;
	endforeach;

	echo $commitcount;
 }

 /**
  * Display a list of most recent activity.
  * Currently only displays recent commits.
  * @todo Display issues opened, closed, branches pushed, comments left...
  * @params $user string $repo string
  */
function show_activity_feed( $user, $repo, $number = 10 ) {
	$client = new \Github\Client();

	// Get x most recent commits to master.
	$commits = $client->api( 'repo' )->commits()->all( $user, $repo, array('sha' => 'master' ) );
	$commits = array_slice( $commits, 0, $number );

	// Add commit information as a new array to our activities array.
	$activities = array();
	foreach ( $commits as $commit ) :
		$activities[] = array (
			'type'   => 'commit',
			'name'   => '<a href="' . $commit['committer']['html_url'] . '">' . $commit['commit']['committer']['name'] . '</a>',
			'action' => ' committed <a href="' . $commit['html_url'] . '">' . substr( $commit['sha'], 0, 7 ) . '</a>',
			'date'   => relative_date( $commit['commit']['committer']['date'] ),
			'detail' => $commit['commit']['message']
		);
	endforeach;

	// Show a formatted list of recent activity.
	if ( 0 < count( $activities ) ) : ?>
		<div class="widget recent-activity">
			<h2 class="widget-title">Recent activity</h2>
			<ul>
			<?php foreach ( $activities as $activity ) : ?>
				<li><?php echo $activity['name']; ?>
				<?php echo $activity['action']; ?>
				<?php echo $activity['date']; ?>
				<?php echo $activity['detail']; ?>
			</li>
			<?php endforeach; ?>
			</ul>
		</div><!-- .widget .recent-activity -->
	<?php endif; // Check for existence of next milestone.
}
