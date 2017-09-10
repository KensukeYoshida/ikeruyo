<ul id="current-rewards">
	<?php foreach($rewards_keyed as $id => $reward) : ?>
		<li class="reward" id="reward-<?php echo esc_attr($id); ?>">
			<strong><?php print $reward['title'] ?></strong>
			<span class="availability">
				<?php printf(esc_html__('%s available @ %s%s each', 'fundingpress'), $reward['available'], $project_currency_sign, $reward['amount']) ?>
			</span>
			<p><?php print $reward['description'] ?></p>
		</li>
	<?php endforeach; ?>
</ul>

<div id="reward-inputs">
	<ul>
		<li>
			<label><?php esc_html_e("Title", 'fundingpress'); ?></label>
			<input type="text" class="widefat" name="reward_title" />
		</li>
		<li>
			<label><?php esc_html_e("Description", 'fundingpress'); ?></label>
			<textarea class="widefat"  name="reward_description"></textarea>
		</li>
		<li>
			<label><?php esc_html_e("Minimum Amount", 'fundingpress'); ?></label>
			<input type="text" name="reward_amount" />
			<span class="description"><?php esc_html_e('Amount a user has to fund to get this reward.', 'fundingpress'); ?></span>
		</li>
		<li>
			<label><?php esc_html_e("Number Available", 'fundingpress'); ?></label>
			<input type="text" name="reward_available" />
		</li>
	</ul>

	<input type="button" id="add-reward-save" value="Save" class="button-secondary" /> or <a href="#" id="add-reward-cancel"><?php esc_html_e('cancel', 'fundingpress') ?></a> | <a href="#" class="delete" id="add-reward-delete"><?php esc_html_e('delete', 'fundingpress') ?></a>
</div>
<input type="button" value="<?php esc_html_e('Add Reward', 'fundingpress'); ?>" id="add-reward" class="button-secondary" />

<input type="hidden" name="rewards" id="rewards-field" />
<input type="hidden" name="rewards_deleted" id="rewards-deleted-field" />
