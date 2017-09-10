<ul>
	<li>
		<label for="f_target_currency"><?php esc_html_e("Currency", 'fundingpress'); ?></label>
		<select name="f_target_currency" id="f_target_currency" <?php disabled(!empty($funders)) ?>>
			<?php global $f_currencies; foreach($f_currencies as $key => $name) : ?>
				<option value=<?php print $key ?> <?php selected($settings['currency'], $key) ?>><?php echo esc_attr($name); ?></option>
			<?php endforeach; ?>
		</select>
</li>
	<li>
		<label for="f_target_amount"><?php esc_html_e("Amount", 'fundingpress'); ?></label>
		<input type="text" name="f_target_amount" id="f_target_amount" class="widefat" value="<?php echo esc_attr($settings['target']); ?>" />
		<div class="description"><?php esc_html_e('The minimum amount you need.', 'fundingpress') ?></div>
	</li>
	<li>
		<label for="f_target_date"><?php esc_html_e("Date", 'fundingpress'); ?></label>
		<input type="text" name="f_target_date" id="f_target_date" class="widefat" value="<?php echo esc_attr($settings['date']); ?>" />
		<div class="description"><?php esc_html_e('Date that funding ends.', 'fundingpress') ?></div>
	</li>
</ul>