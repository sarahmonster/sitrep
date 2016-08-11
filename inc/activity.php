<?php
/**
 * Functionality relating to repository activity.
 * Stuff like commits, comments, etc.
 */


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
			'date'   => $commit['commit']['committer']['date'],
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
