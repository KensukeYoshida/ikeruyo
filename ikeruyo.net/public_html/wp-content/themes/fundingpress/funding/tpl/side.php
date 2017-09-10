 		<div class="project-info-wrapper">
                <div class="project-info">
                	<div class="progress progress-striped active bar-green"><div style="height: <?php printf(esc_html__('%u%', 'fundingpress'), round($funded_amount/$target*100), $project_currency_sign, round($target)) ?>%" class="bar"></div></div>

                      <h3 class="traised"><?php echo esc_attr($project_currency_sign); echo esc_attr(number_format(round((int)$funded_amount), 0, '.', ',')); ?> <br>
                    <span><?php esc_html_e("raised of", 'fundingpress'); ?>  <?php echo esc_attr($project_currency_sign); print esc_attr(number_format(round((int)$target), 0, '.', ','));?></span></h3>

                       <?php
                    if(!$project_expired) : ?>
                     <h3>
                        <?php if(strpos(F_Controller::timesince(time(), strtotime($parseddate), 1, ''), "hour")){ ?> <strong><br> <?php esc_html_e('< 24', 'fundingpress'); ?></strong> <?php }else{ ?>
                        <strong><?php print F_Controller::timesince(time(), strtotime($parseddate), 1, ''); } ?></strong><br>
                        <?php if(strpos(F_Controller::timesince(time(), strtotime($parseddate), 1, ''), "hour")){ ?>
                         <span><?php esc_html_e('hours to go', 'fundingpress'); ?></span>
                        <?php }else{ ?>
                        	<?php if(F_Controller::timesince(time(), strtotime($parseddate), 1, '') == 1){ ?>
                        		<span> <?php esc_html_e('day to go', 'fundingpress'); ?></span>
                        	<?php }else{ ?>
                        		<span> <?php esc_html_e('days to go', 'fundingpress'); ?></span>
                        	<?php } ?>


                        <?php } ?>
                        </h3>
                    <?php endif; ?>

                   <div class="funding-info"><?php esc_html_e("This project will only be funded if at least ", 'fundingpress'); ?> <?php print $project_currency_sign; print number_format(round((int)$target), 0, '.', ',');?> <?php esc_html_e("is raised by", 'fundingpress'); ?>
                  	<?php $wpdate = get_option('date_format'); ?>
                  	<?php if(strtotime($project_settings['date']) == false){ ?>
                  	<?php
                  		$bits = explode('/',$project_settings['date']);
						$date = $bits[1].'/'.$bits[0].'/'.$bits[2];
                  	print date_i18n($wpdate, strtotime($date)); ?>
                  	<?php }else{ ?>
                  	<?php print date_i18n($wpdate, strtotime($project_settings['date'])); ?>
                  	<?php } ?>
                  	</div>
                    <?php if(!$project_expired) : ?>
                   <div class="funding-minimum">
                        <a class="edit-button button-small button-green" href="<?php print add_query_arg('step', 1) ?>"><i class="fa fa-rocket" aria-hidden="true"></i> <?php esc_html_e('Fund This Project', 'fundingpress') ?></a>
                          <!--<?php if($funding_minimum == ""){ ?>
                        <?php }else{ ?>
                      <small><?php printf(esc_html__("%s minimum", 'fundingpress'),$project_currency_sign.$funding_minimum) ?></small>
                        <?php } ?>-->
                    </div>
                <?php endif; ?>
                </div>
                <div class="clear"></div>
              </div>
              <!-- project-info-wrapper -->

              <div class="author-side">         <?php
              $autorpic = get_the_author_meta('profile_pic', get_the_author_meta( 'ID' ));
              if(!empty($autorpic)){
               $image = aq_resize( $autorpic,  250, 250, true, true, true ); //resize & crop img
                if (!isset ($image[0])) {
                    $theimage = $autorpic;
                } else {
                    $theimage = $image;
                }
               ?><img src="<?php echo esc_url($theimage); ?>" />
               <?php }else{ ?>
               <?php echo get_avatar( get_the_author_meta( 'ID' ), 250 );?>
               <?php } ?>
                <div class="author-info">
                	<div class="psponsor"><?php esc_html_e("Project sponsor", 'fundingpress'); ?> </div>

                  <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta( 'ID' ))); ?>">
                  	<?php  if ( get_the_author_meta('first_name', get_the_author_meta( 'ID' )) ) {
                  		echo esc_attr(get_the_author_meta('first_name',get_the_author_meta( 'ID' ))); }?>
                     <?php  if ( get_the_author_meta('last_name', get_the_author_meta( 'ID' )) ) {
                     	echo esc_attr(get_the_author_meta('last_name',get_the_author_meta( 'ID' ))); } ?>
                     <?php
                     $first = get_the_author_meta('first_name', get_the_author_meta( 'ID' ));
					 $last = 	get_the_author_meta('last_name', get_the_author_meta( 'ID' ));
					 $display = get_the_author_meta('display_name',get_the_author_meta( 'ID' ));
                     if(empty($first) && empty($last)){
                     	echo esc_attr($display);
					 } ?>
                  </a>
                  <p>
                <?php if(usercountry_name_display(get_the_author_meta( 'ID' )) != '' || get_the_author_meta('city', get_the_author_meta( 'ID' )) != ''){ ?>
                <i class="fa fa-map-marker" aria-hidden="true"></i> <b><?php echo esc_attr(usercountry_name_display(get_the_author_meta( 'ID' )));?></b><?php if(get_the_author_meta('city', get_the_author_meta( 'ID' )) != ''){ echo ', '; } ?>
                <?php if ( get_the_author_meta('city', get_the_author_meta( 'ID' )) ) {echo esc_attr(get_the_author_meta('city',get_the_author_meta( 'ID' ))); } ?>
                <?php } ?>
            </p>
                </div>
                <div class="clear"></div>
              </div>
              <!-- author -->
              <?php if(of_get_option('rewards') == '1'){ ?>
              <h2><?php esc_html_e('Support this project', 'fundingpress'); ?></h2>
            <ul class="perks-wrapper rew">
                <?php foreach($rewards as $reward) : ?>
                	 <li class="perk">
                    <?php
                        $reward_funding_amount = get_post_meta($reward->ID, 'funding_amount', true);
                        $reward_available = get_post_meta($reward->ID, 'available', true);
                        $funders2 = get_posts(array(
                            'numberposts'     => -1,
                            'post_type' => 'funder',
                            'post_parent' => $reward->ID,
                            'post_status' => 'publish'
                        ));
                    ?>

                        <?php if(!$project_expired && (empty($reward_available) || count($funders2) < $reward_available)) : ?>

                            <?php $url = add_query_arg(array('step' => 1, 'chosen_reward' => $reward->ID, 'amount' => $reward_funding_amount)); ?>
                           <h4><?php print $reward->post_title ?>   <span>  <?php if(!empty($reward_available)) : ?>
                              <?php if($reward->post_title == 'No reward'){}else{ ?>
                            <div class="available">(<?php printf(esc_html__('%d of %d available', 'fundingpress'), $reward_available - count($funders2), $reward_available) ?>)</div>
                        <?php }endif; ?></span></h4>
                          <p><?php print $reward->post_content ?></p>
                            <a href="<?php echo esc_url($url); ?>"><div class="min-amount"> <input type="button" value="<?php printf(esc_html__('Fund %s%s or more', 'fundingpress'), $project_currency_sign, number_format(round((int)$reward_funding_amount), 0, '.', ','));?>" class="button-green button-medium button-contribute "></div></a>

                        <?php else : ?>
                            <h4><?php echo esc_attr($reward->post_title); ?></h4>
                             <p><?php echo esc_attr($reward->post_content); ?></p>


                        <?php endif; ?>

				 </li>
                <?php endforeach ?>

            </ul>
			<?php } ?>