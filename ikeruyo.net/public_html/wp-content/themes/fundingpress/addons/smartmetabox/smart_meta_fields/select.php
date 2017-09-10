<select name="<?php echo esc_attr($id); ?>" id="<?php echo esc_attr($id); ?>">
	<?php foreach ($options as $opt_value=>$opt_name): ?>
		<option <?php selected($value, $opt_value)?> value="<?php echo esc_attr($opt_value); ?>"><?php echo esc_attr($opt_name); ?></option>
	<?php endforeach ?>
</select>