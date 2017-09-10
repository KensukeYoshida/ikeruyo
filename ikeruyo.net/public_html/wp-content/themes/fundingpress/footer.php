</div> <!-- End of container -->
  <!-- FOOTER -->
    <footer>
      <div class="container">

           <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Footer widgets') ) : ?>
         		<?php dynamic_sidebar('one'); ?>
           <?php endif; ?>

      </div>
    </footer>

    <div class="copyright">
    	<div class="container  cpr">
          	<p>© <?php echo date("Y"); ?>&nbsp;<?php if(of_get_option('copyright')!=""){ echo of_get_option('copyright');} ?>
        		&nbsp;
        	<a href="<?php if(of_get_option('privacy')!=""){echo of_get_option('privacy');}?>"><?php esc_html_e("Privacy", 'fundingpress'); ?></a> ·
        	<a href="<?php if(of_get_option('terms')!=""){echo of_get_option('terms');}?>"><?php esc_html_e("Terms", 'fundingpress'); ?></a></p>
        </div>
    </div>

<?php  echo of_get_option('googlean'); ?>


<script>
	function social_startlogin(provider, proceed) {
		var CurrentLocation = "<?php
		if (is_single()) {
			echo wp_get_shortlink();
		} else {
			$protocol = is_ssl() ? 'https' : 'http';
			echo "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		}

		?>";
		window.location.replace(settings.authlocation+"handler/index.php?initiatelogin=" + provider + "&returnto=" + encodeURIComponent(CurrentLocation));
	}
	<?php
	if (isset($_SESSION['needtorefresh'])) {
		if ($_SESSION['needtorefresh'] == true) {
			unset ($_SESSION['needtorefresh']);
			echo 'setTimeout(function(){
				   window.location.reload(1);
				}, 1000);';
		}
	}

	?>




  function get_currency_sign(cur) {


       jQuery.ajax({
        type: 'POST',
        url: ajaxurl,
        data: {"action": "return_currency", curr: cur },
        success: function(response) {
              jQuery( ".pb-target .cu" ).text(response);
            return false;
        }
    });
}

function cat_ajax_get_all(catID) {
       jQuery('.category-menu li').click(function(li) {
        jQuery('li').removeClass('current');
        jQuery(this).addClass('current');
        });

     jQuery(".category-post-content").hide();
    jQuery(".loading-animation").show();



       jQuery.ajax({
        type: 'POST',
        url: ajaxurl,
        data: {"action": "load-filter-all", cat: catID },
        success: function(response) {
            jQuery(".category-post-content").html(response);
            jQuery(".loading-animation").hide();
            jQuery(".category-post-content").show();
            return false;
        }
    });
}
</script>

<script>
jQuery(document).ready(function() {
	jQuery('#WePayUnlink').click(function (e) {

       	jQuery.ajax({
        type: 'POST',
        url: ajaxurl,
        data: {"action": "funding_unlink_wepay"},
        success: function(response) {
           if (response.substr(0,2) == "ok") {
           		location.reload();
           }
        }
    });
	});


	jQuery('#StripeUnlink').click(function (e) {

       	jQuery.ajax({
        type: 'POST',
        url: ajaxurl,
        data: {"action": "funding_unlink_stripe"},
        success: function(response) {
           if (response.trim().substr(0,2) == "ok") {
           		location.reload();
           }
        }
    });
	});

    jQuery( "#currency" )
  .change(function () {
    var str = "";
    jQuery( "#currency option:selected" ).each(function() {
      str += jQuery( this ).val();
    });
    jQuery( ".pb-target .cu" ).text("<?php if(isset($f_currency_signs['EUR'])){ echo esc_attr($f_currency_signs['EUR']);}?>");
  })
  .change(); });
</script>

<?php if(of_get_option("rewards") == 1){ ?>
<script>
/*rewards options*/
jQuery(document).ready(function() {
var chosen = jQuery('.chosen_reward');
var amount = jQuery('#field-amount');
var zerorew = jQuery('.chosen_reward_value');
var rewlist = jQuery('#project-rewards-list');

if(amount.val() == 0 ){
	jQuery('#project-rewards-list .chosen_reward').first().prop('checked', true);
}
 amount.keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if (jQuery.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
             // Allow: Ctrl+A, Command+A
            (e.keyCode == 65 && ( e.ctrlKey === true || e.metaKey === true ) ) ||
             // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });


chosen.click(function() {
    amount.val(jQuery(this).next('input').val());
});
});
</script>
<?php }else{ ?>
  <script>jQuery( document ).ready(function() {
  	var chosen = jQuery('.chosen_reward');
    chosen.prop('checked', true);
           });
   </script>
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/rewards.css">
<?php } ?>
<script>
function delpost(id){

    var Keep = id;
        jQuery.ajax({
            type: "POST",
            url: ajaxurl,
            data: {  action: 'delcomments',id: id},
            success: function(data, textStatus, XMLHttpRequest){

               jQuery('#li-comment-'+id).hide();
               jQuery('#li-comment-update-'+id).hide();
            }
        });
}
  jQuery(document).ready(function() {
    jQuery('.comment_deletor').click(function(e) {
      var cid =  jQuery(this).data('cid');
      var type =  jQuery(this).data('type');
      jQuery.ajax({
          type: "POST",
          url: ajaxurl,
          data: {  action: 'delcomments',id: cid},
          success: function(data, textStatus, XMLHttpRequest){
            if (type == "comment") {
              jQuery('#comments_counter').fadeOut('fast', function() {
                var temp = jQuery('#comments_counter').html();
                temp = temp - 1;
                jQuery('#comments_counter').html(temp);
                jQuery('#comments_counter').fadeIn();
              });
            } else if (type == "update") {
              jQuery('#updates_counter').fadeOut('fast', function() {
                var temp = jQuery('#updates_counter').html();
                temp = temp - 1;
                jQuery('#updates_counter').html(temp);
                jQuery('#updates_counter').fadeIn();
              });
            }
             jQuery('#li-comment-'+cid).hide();
             jQuery('#li-comment-update-'+cid).hide();
          }
      });
    });
  });

function delete_project(prID) {

       	jQuery.ajax({
        type: 'POST',
        url: ajaxurl,
        data: {"action": "funding_delete_projects", "idp" : prID},
        success: function(response) {

           if (response.trim().substr(0,2) == "ok") {
           		location.reload();
           }
        }
    });
}

</script>
<?php wp_footer(); ?>
</div>
</body></html>
