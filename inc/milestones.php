<?php
/**
 * Functionality relating to project milestones.
 * Allows for listing milestones, displaying due dates, etc.
 */


 /**
  * Display a list of all milestones.
  * @params $user string $repo string
  */
function list_all_milestones( $user, $repo ) {
	$client = new \Github\Client();
	$milestones = $client->api( 'repo' )->milestones( $user, $repo );

	foreach ( $milestones as $milestone ) :
		echo '<dt><a href="' . $milestone['html_url'] . '">' . $milestone['title'] . '</a></dt>';
		echo '<dd>' . $milestone['description'] . '<dd>';
		echo $milestone['state'];
		echo $milestone['due_on'];
	endforeach;
}

/**
 * Display the current milestone.
 * We're assuming the current milestone is the one with the nearest due date:
 * the first result returned when fetching milestones.
 *
 * @todo: Make this smarter.
 * @params $user string $repo string
 */
function show_current_milestone( $user, $repo ) {
	$client = new \Github\Client();
	$milestones = $client->api( 'repo' )->milestones( $user, $repo );

	// Set current milestone.
	$current_milestone = $milestones[0]; ?>

	<div class="widget current-milestone">
		<h2 class="widget-title">Current milestone</h2>
		<h3 class="current-milestone-title"><a title="<?php echo $current_milestone['description']; ?>" href="<?php echo $current_milestone['html_url']; ?>">
			<?php echo $current_milestone['title']; ?></a>
		</h3>

		<?php // Calculate percent complete.
		$total_issues = $current_milestone['open_issues'] + $current_milestone['closed_issues'];
		$percent_done = round( 100*( $current_milestone['closed_issues'] / $total_issues ), 0 );

		// Output percent complete. ?>
		<?php echo $percent_done; ?>% complete

		<?php
		if ( $current_milestone['due_on'] ) :
			// Get the current milestone's due date.
			$current_due_day = date( 'd', strtotime( $current_milestone['due_on'] ) );
			$current_due_month = date( 'F Y', strtotime( $current_milestone['due_on'] ) );

			// Calculate days remaining.
			$current_due_date = date_create( $current_milestone['due_on'] );
			$today = date_create();
			$current_interval = date_diff( $current_due_date, $today );
			$current_days_remaining = $current_interval->format( '%a' );

			// Output due date. ?>
			<div class="due-date">
				<h3>Due</h3>
				<div class="break"></div>

				<div class="date">
					<span class="big-number"><?php echo $current_due_day; ?></span>
					<span class="label"><?php echo $current_due_month; ?></span>
				</div>

				<div class="days-remaining">
					<span class="big-number"><?php echo $current_days_remaining; ?></span>
					<span class="label">Days remaining</span>
				</div>
			</div>
		<?php endif; // Check for existence of due date ?>

	</div><!-- .widget .current-milestone -->
<?php }
