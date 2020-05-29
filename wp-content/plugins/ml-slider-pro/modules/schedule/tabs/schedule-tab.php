<?php 
	global $wp_locale;
	$current_time = current_time('timestamp');
?>

<div class="row schedule">
	<input 
		class="schedule-slide"
		type="checkbox" 
		id="schedule-select-<?php echo $post->ID ?>"
		name="attachment[<?php echo $post->ID ?>][schedule]" 
		<?php echo ('yes' === $is_scheduled) ? 'checked="checked"' : ''; ?>
	> 
	<label class="schedule-slide" for="schedule-select-<?php echo $post->ID ?>">
		<?php _e('Schedule this slide', 'ml-slider-pro'); ?>
	</label>
	<div class="hide-if-notchecked scheduling-area">
		<table>
			<thead>
				<tr>
					<th></th>
					<th><?php _e('Date'); ?></th>
					<th></th>
					<th><?php _e('Hour'); ?></th>
					<th></th>
					<th><?php _e('Minute'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php 
				// Essentially if $schedule_start isn't set, we can set defaults here
				$texts = array(
					'from' => _x('From', 'As in "From January 7th to April 2nd..."', 'ml-slider-pro'),
					'to' => _x('To', 'As in "From January 7th to April 2nd..."', 'ml-slider-pro')
				);
				foreach(array('from' => $schedule_start, 'to' => $schedule_end) as $id => $date) :

					// By default use the current time
					$start_real = $date ? mysql2date('Y-m-d', $date, false) : gmdate('Y-m-d', $current_time);
					$hh = $date ? mysql2date('H', $date, false) : gmdate('H', $current_time);
					$mn = $date ? mysql2date('i', $date, false) : gmdate('i', $current_time);
					$ss = $date ? mysql2date('s', $date, false) : gmdate('s', $current_time);

					// By default select every day ($days_scheduled could be false if coming from an older version)
					$days_scheduled = ($date && $days_scheduled) ? $days_scheduled : array(0, 1, 2, 3, 4, 5, 6);
				?>
				<tr>
					<td style="text-align:right"><?php echo $texts[$id]; ?></td>
					<td>
						<label>
							<span class="screen-reader-text"><?php _e('Date'); ?></span>
							<input
								type="text"
								class="datepicker"
								name="attachment[<?php echo $post->ID; ?>][<?php echo $id; ?>][date]"
								value="<?php echo $start_real; ?>"
							>
						</label>
					</td>
					<td><?php _ex('at', 'As in "your slide will display Tuesday at 5pm"', 'ml-slider-pro'); ?></td>
					<td>
						<label>
							<span class="screen-reader-text"><?php _e('Hour'); ?></span>
							<input 
								type="text"
								name="attachment[<?php echo $post->ID; ?>][<?php echo $id; ?>][hh]"
								value="<?php echo $hh; ?>"
								size="2"
								maxlength="2"
								autocomplete="off"
							>
						</label>
					</td>
					<td>:</td>
					<td>
						<label>
							<span class="screen-reader-text"><?php _e('Minute'); ?></span>
							<input 
								type="text"
								name="attachment[<?php echo $post->ID; ?>][<?php echo $id; ?>][mn]"
								value="<?php echo $mn; ?>"
								size="2"
								maxlength="2"
								autocomplete="off"
							>
						</label>
						<input
							type="hidden"
							name="attachment[<?php echo $post->ID; ?>][<?php echo $id; ?>][ss]"
							value="<?php echo $ss; ?>"
						>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<table class="days-schedules">
			<thead>
				<tr>
					<th><?php _e('Days', 'ml-slider-pro'); ?></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<?php
					// Sunday = 0, etc
					foreach(array_values($wp_locale->weekday_abbrev) as $day_id => $day_abbr) :
						$days_scheduled = is_array($days_scheduled) ? $days_scheduled : array(); ?>
						<td><input 
								type="checkbox"
								name="attachment[<?php echo $post->ID; ?>][days][<?php echo $day_id; ?>]"
								<?php echo in_array($day_id, $days_scheduled) ? 'checked="checked"' : ''; ?>
							><label><?php echo $day_abbr ?></label>
						</td>
					<?php endforeach; ?>
				</tr>
			</tbody>
		</table>
		<span class="tipsy-tooltip-top time-helper" data-time="<?php echo gmdate('Y-m-d h:i:s', $current_time); ?>" data-now-text="<?php _e('Current server time', 'ml-slider-pro') ?>"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-clock"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></span>
	</div>
</div>
