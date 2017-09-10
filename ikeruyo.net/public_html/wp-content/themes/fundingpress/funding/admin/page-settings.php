<?php $current_user= wp_get_current_user();
$level = $current_user->user_level;
if($level == 10){
    ?><div class="wrap">
    <div id="icon-options-general" class="icon32"></div>
    <h2><?php esc_html_e('Funding Settings', 'fundingpress'); ?></h2>

    <form action="" method="POST">
        <h3><?php esc_html_e("Fundit Settings", 'fundingpress'); ?></h3>
        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th scope="row"><label for="paypal-email"><?php esc_html_e('PayPal Email Address', 'fundingpress');?></label></th>
                    <td>
                        <input type="text" name="email" id="paypal-email" class="regular-text" value="<?php print @$f_paypal['email']; ?>" />
                        <div class="description">
                            <?php print esc_html__('The PayPal email address you want to be paid into.', 'fundingpress') ?>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

        <h3><?php esc_html_e('PayPal API Credentials', 'fundingpress'); ?></h3>

        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th scope="row"><label for="paypal-mode"><?php esc_html_e('Mode', 'fundingpress') ?></label></th>
                    <td>
                        <select name="mode">
                            <option value="sandbox" <?php selected('sandbox', @$f_paypal['mode']) ?>><?php esc_html_e("Sandbox", 'fundingpress'); ?></option>
                            <option value="production" <?php selected('production', @$f_paypal['mode']) ?>><?php esc_html_e("Production", 'fundingpress'); ?></option>
                        </select>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row"><label for="paypal-app-id"><?php esc_html_e('PayPal Application ID', 'fundingpress') ?></label></th>
                    <td>
                        <input type="text" name="app_id" id="paypal-app-id" class="regular-text" value="<?php print @$f_paypal['app_id']; ?>" />
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row"><label for="paypal-api-username"><?php esc_html_e('PayPal API Username', 'fundingpress') ?></label></th>
                    <td>
                        <input type="text" name="api_username" id="paypal-api-username" class="regular-text" value="<?php print @$f_paypal['api_username']; ?>" />
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row"><label for="paypal-api-password"><?php esc_html_e('PayPal API Password', 'fundingpress') ?></label></th>
                    <td>
                        <input type="text" name="api_password" id="paypal-api-password" class="regular-text" value="<?php print @$f_paypal['api_password']; ?>" />
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row"><label for="paypal-api-signature"><?php esc_html_e('PayPal API Signature', 'fundingpress') ?></label></th>
                    <td>
                        <input type="text" name="api_signature" id="paypal-api-signature" class="regular-text" value="<?php print @$f_paypal['api_signature']; ?>" />
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row"><label for="paypal_limit"><?php esc_html_e('PayPal maximum funding amount', 'fundingpress') ?></label></th>
                    <td>
                        <input type="text" name="paypal_limit" id="paypal_limit" class="regular-text" value="<?php print @$f_paypal['paypal_limit']; ?>" />
                    </td>
                </tr>

            </tbody>
        </table>

		 <h3><?php esc_html_e('WePay Credentials', 'fundingpress'); ?></h3>

        <table class="form-table">
            <tbody>

                <tr valign="top">
                    <th scope="row"><label for="wepay-client_id"><?php esc_html_e('WePay client ID', 'fundingpress') ?></label></th>
                    <td>
                        <input type="text" name="wepay-client_id" id="wepay-client_id" class="regular-text" value="<?php print @$f_paypal['wepay-client_id']; ?>" />
                    </td>
                </tr>
                 <tr valign="top">
                    <th scope="row"><label for="wepay-client_secret"><?php esc_html_e('WePay client secret', 'fundingpress') ?></label></th>
                    <td>
                        <input type="text" name="wepay-client_secret" id="wepay-client_secret" class="regular-text" value="<?php print @$f_paypal['wepay-client_secret'] ?>" />
                    </td>
                </tr>
                 <tr valign="top">
                    <th scope="row"><label for="wepay-access_token"><?php esc_html_e('WePay access token', 'fundingpress') ?></label></th>
                    <td>
                        <input type="text" name="wepay-access_token" id="wepay-access_token" class="regular-text" value="<?php print @$f_paypal['wepay-access_token']; ?>" />
                    </td>
                </tr>
                  <tr valign="top">
                    <th scope="row"><label for="wepay-account_id"><?php esc_html_e('WePay account ID', 'fundingpress') ?></label></th>
                    <td>
                        <input type="text" name="wepay-account_id" id="wepay-account_id" class="regular-text" value="<?php print @$f_paypal['wepay-account_id']; ?>" />
                    </td>
                </tr>

                  <tr valign="top">
                    <th scope="row"><label for="wepay-staging"><?php esc_html_e('WePay staging enabled?', 'fundingpress') ?></label></th>
                    <td>
                        <input type="checkbox" name="wepay-staging" id="wepay-staging" <?php if(@$f_paypal['wepay-staging'] == 'Yes'){ ?> checked <?php } ?> value="Yes" />
                    </td>
                </tr>

            </tbody>
        </table>
 <h3><?php esc_html_e('Stripe Credentials', 'fundingpress'); ?></h3>

        <table class="form-table">
            <tbody>

                <tr valign="top">
                    <th scope="row"><label for="stripe-client_id"><?php esc_html_e('Stripe client ID', 'fundingpress') ?></label></th>
                    <td>
                        <input type="text" name="stripe-client_id" id="stripe-client_id" class="regular-text" value="<?php print @$f_paypal['stripe-client_id']; ?>" />
                    </td>
                </tr>
                 <tr valign="top">
                    <th scope="row"><label for="stripe-client_secret"><?php esc_html_e('Stripe secret key', 'fundingpress') ?></label></th>
                    <td>
                        <input type="text" name="stripe-client_secret" id="stripe-client_secret" class="regular-text" value="<?php print @$f_paypal['stripe-client_secret'] ?>" />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="stripe-publishable"><?php esc_html_e('Stripe publishable key', 'fundingpress') ?></label></th>
                    <td>
                        <input type="text" name="stripe-publishable" id="stripe-publishable" class="regular-text" value="<?php print @$f_paypal['stripe-publishable'] ?>" />
                    </td>
                </tr>



            </tbody>
        </table>
         <h3><?php esc_html_e('Admin commission', 'fundingpress'); ?></h3>

        <table class="form-table">
            <tbody>

                <tr valign="top">
                    <th scope="row"><label for="admin-commission"><?php esc_html_e('Enter admin commission (Amount is in percents. Enter amount without % sign)', 'fundingpress') ?></label></th>
                    <td>
                        <input type="text" name="admin-commission" id="admin-commission" class="regular-text" value="<?php print @$f_paypal['admin-commission']; ?>" />
                    </td>
                </tr>
           </tbody>
        </table>

        <p>
            <?php wp_nonce_field('funding_settings') ?>
            <input class="button-primary" type="submit" value="<?php esc_html_e('Save Changes', 'fundingpress'); ?>" name="submit" />
        </p>
    </form>
</div>

<?php }else{ ?>
    <div class="wrap">
    <div id="icon-options-general" class="icon32"></div>
    <h2><?php esc_html_e('Funding Settings', 'fundingpress') ?></h2>

    <form action="" method="POST">
        <h3><?php esc_html_e("Fundit Settings", 'fundingpress'); ?></h3>
        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th scope="row"><label for="paypal_email"><?php esc_html_e('PayPal Email Address', 'fundingpress'); ?></label></th>
                    <td> <?php
                    if(isset($_POST['paypal_email'])){
                    wp_update_user( array( 'ID' => get_current_user_id(), 'paypal_email' => $_POST['paypal_email'] ) );
					}?>
                    <input type="text" name="paypal_email" id="paypal_email" class="regular-text" value="<?php $usr = get_userdata(get_current_user_id()); echo esc_attr($usr->paypal_email); ?>" />
                        <div class="description">
                            <?php print esc_html__('The PayPal email address you want to be paid into.', 'fundingpress') ?>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <p>


            <input class="button-primary" type="submit" value="<?php esc_html_e('Save Changes', 'fundingpress'); ?>" name="submit" />
        </p>
    </form>


</div>

<?php } ?>