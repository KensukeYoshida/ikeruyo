<form method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
		<input type="text" class="field" name="s" id="s" placeholder="<?php esc_html_e( 'Search', 'fundingpress' ); ?>" />
		<input type="hidden" name="post_type" value="post" />
</form>