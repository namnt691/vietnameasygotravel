<link rel="stylesheet" href="/wp-content/themes/flatsome-child/css/jquery.fancybox.css">
<link rel='stylesheet' href='/wp-content/themes/flatsome-child/css/thuexe.css' type='text/css' media='all' />
 <div class="section-content-loadlink relative">
			<div class="row">
				<div class="col small-12 large-12">
				
					    <?php echo custom_breadcrumbproduct();?>
									
				</div>
			</div>
		</div>
<div class="zek_ticket_product vexe">
    <div class="container">
        <div class="card trip-card">
            <div class="block-title"></div>
            <script>
                function extractDateFromURL() {
                    const urlParams = new URLSearchParams(window.location.search);
                    const departureDateParam = urlParams.get("departure_date");


                    if (departureDateParam) {
                        const dateParts = departureDateParam.split("-");
                        const day = dateParts[0];
                        const month = dateParts[1];
                        const year = dateParts[2];


                        const blockTitleElement = document.querySelector(".block-title");
                        blockTitleElement.textContent = `${day}-${month}-${year}`;
                    } else {

                        const currentDate = new Date();
                        const day = currentDate.getDate().toString().padStart(2, "0");
                        const month = (currentDate.getMonth() + 1).toString().padStart(2, "0");
                        const year = currentDate.getFullYear();

                        const blockTitleElement = document.querySelector(".block-title");
                        blockTitleElement.textContent = `${day}-${month}-${year}`;
                    }
                }


                extractDateFromURL();
            </script>
			 <?php
 $time_xp = get_field('time_xp');
 $dd_xp = get_field('dd_xp');
 $time_dx = get_field('time_dx');
 $time_den = get_field('time_den');
 $dd_den = get_field('dd_den');
    ?>
            <div class="trip-info-card">
                <div class="trip-info-card-body">
                    <div class="trip-points">
					<div class="dep trip-points-line">
                                    <div class="trip-points-line-bar">
                                        <div class="badge"></div>
                                    </div>
                                    <div class="trip-points-line-left time-label"><?php echo $time_xp ?></div>
                                    <div class="one-line trip-points-line-desc">
                                        <span><?php echo $dd_xp ?></span>
                                    </div>
                                </div>
                        <div class="text trip-points-line">
                            <div class="trip-points-line-bar"></div>
                            <div class="trip-points-line-left logo-icons">
                                <img src="/wp-content/uploads/2024/02/screenshot_1688341545.png" alt="">
                            </div>
                            <div class="trip-points-line-desc"><span><?php echo $time_dx ?></span>
                                    </div>
                        </div>

                        <div class="arr trip-points-line">
                            <div class="trip-points-line-bar">
                                <div class="badge"></div>
                            </div>
							<div class="trip-points-line-left time-label"><?php echo $time_den ?></div>
                                    <div class="one-line trip-points-line-desc"><span><?php echo $dd_den ?></span>
                                    </div>
                        </div>
                       
                    </div>
                </div>
                <div class="trip-info-card-cta">
                    <div class="trip-labels labels">
                        <div class="icon"><img src="/wp-content/uploads/2024/02/screenshot_1688341601.png" alt="">
                        </div>
                        <div class="icon"><img src="/wp-content/uploads/2024/02/screenshot_1688341611.png" alt="">
                        </div>
                        <div class="icon"><img src="/wp-content/uploads/2024/02/kisspng-public-domain-mark-creative-commons-license-comput-cashback-5b19ec82851b48.4804559115284256025452-e1691580280649.jpg" alt="">
                        </div>
                        <div class="icon"><img src="/wp-content/uploads/2024/02/person-icon-vector-filled-flat-sign-solid-pictogram-isolated-white-user-account-member-symbol-logo-illustration-88293809-e1691581584308.webp" alt="">
                        </div>
                    </div>


                    
                    <?php do_action('woocommerce_after_shop_loop_item_title'); ?>
                    <div class="wt-discount-sif">
                        <div class="dc-title"><span>Discount:</span>
                        </div>
                    </div>
                    <style type="text/css">
                        .wt-discount-sif,
                        .wt-disc-info {
                            display: none !important
                        }
                    </style>
                     <?php
                        exwt_add_to_cart();
                        do_action('exwt_after_atc_form')
                        ?>
                    <div id="ppcp-messages" data-partner-attribution-id="Woo_PPCP"></div>
                    <div class="ppc-button-wrapper">
                        <div id="ppc-button-ppcp-gateway"></div>
                    </div>

                </div>
            </div>
        </div>
        <div class="card">
            <div class="trip-info-gallery gallery1">
                <div class="trip-info-operator trip-info-gallery-item trip-info-gallery-operator">
                    <div class="capt"><a href="#reviews"><b>Rating</b></a>
                    </div>
                </div>
                <div class="trip-gallery">
				<?php echo create_gallery_product_box_congdongblog();?>
                </div>
            </div>
            <div class="trip-info-features">
                <div class="trip-info-features-labels">
                    <?php echo do_shortcode('[block id="ux-thue-xe"]'); ?>
                </div>
            </div>
          
        </div>
        <div class="card segment-info content-post">
            <h1 class="block-title"><?php echo get_the_title();?></h1>
            <?php echo the_content();?>
        </div>
       
    </div>

</div>
<script type="text/javascript" src="/wp-content/themes/flatsome-child/js/jquery.fancybox.min.js"></script>

<div class="related-articles">
    <div class="container">
        <div class="related-articles-lq">
        <div class="related-articles-head">
          <h3>
         
          <?php 
      global $sitepress;
      $current_language = $sitepress->get_current_language();
      ?>
 <?php if($current_language =='vi') 
      {
        ?>
 Dịch vụ thuê xe liên quan
        <?php 
      }
      ?>
      <?php if($current_language =='en') 
      {
        ?>
      Related car rental services
        <?php 
      }
      ?>
          </h3>
        </div>
        <div class="related-articles-body">
          <div class="row large-columns-4 medium-columns-3 small-columns-2 slider row-slider slider-nav-circle slider-nav-push" data-flickity-options='{"imagesLoaded": true, "groupCells": "100%", "dragThreshold" : 5, "cellAlign": "left","wrapAround": true,"prevNextButtons": true,"percentPosition": true,"pageDots": false, "rightToLeft": false, "autoPlay" : true}'>

          <?php 
    // get the custom post type's taxonomy terms
            $custom_taxterms = wp_get_object_terms( $post->ID, 'product_cat', array('fields' => 'ids') );
    // arguments
            $args = array(
              'post_type' => 'product',
              'post_status' => 'publish',
    'posts_per_page' => 10, // you may edit this number
    'orderby' => 'rand',
    'tax_query' => array(
      array(
        'taxonomy' => 'product_cat',
        'field' => 'id',
        'terms' => $custom_taxterms
      )
    ),
    'post__not_in' => array ($post->ID),
  );
            $related_items = new WP_Query( $args );
    // loop over query
            if ($related_items->have_posts()) :
              while ( $related_items->have_posts() ) : $related_items->the_post();
                ?>

               <div class="col">
<article class="post-item post-list clearfix">
    <div class="article-image-rencent">
        <div class="post-image">
            <a href="<?php echo get_the_permalink(); ?>" title="<?php echo get_the_title(); ?>" class="plain">
                  <img width="1000" height="750" src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'large') ?>" class="attachment-original size-original wp-post-image" alt="<?php echo get_the_title(); ?>"/> </a>
        </div>
    </div>
    <div class="article-content-rencent">
        <div class="entry-header clearfix">
            <header class="entry-header-title">
                <h3 class="entry-title">
						<a href="<?php echo get_the_permalink(); ?>" title="<?php echo get_the_title(); ?>" class="plain">
                      <?php echo get_the_title(); ?></a>
					</h3>
            </header>
        </div>
      
        
    </div>
</article>
</div>
                <?php
              endwhile;
            endif;
    // Reset Post Data
            wp_reset_postdata();
            ?>
          </div>
        </div>
       </div>
       </div>
    </div>
    <style>
        #main{
            display: flex;
    flex-direction: column;
        }
        .zek_ticket_product{
            order:1;
        }
        .related-articles{
            order:3;
                background: rgb(245, 245, 245);
        }
        .picker__holder{display:none}
        .reviews-new{
            order:2;
                background: rgb(245, 245, 245);
        }
        #tabs{ margin: 20px -20px; border: none; }
			#tabs, #ui-datepicker-div, .ui-datepicker{ font-size: 85%; }
			.ui-state-active, .ui-widget-content .ui-state-active, .ui-widget-content .ui-state-focus,  .ui-widget-header .ui-state-active,.ui-datepicker-today .ui-state-default{height:auto !important;width:auto !important;border:0px !important;}
			.ui-timepicker-select{padding:0px 5px !important;}
			.ui-datepicker-calendar .ui-state-default:hover{height:auto !important;width:auto !important;border:0px !important;}
			.ui-state-active, .ui-widget-content .ui-state-active, .ui-widget-content .ui-state-focus, .ui-widget-content .ui-state-hover, .ui-widget-header .ui-state-active{line-height: 20px !important;
    border-radius: 0px !important;}
    </style>
    <link rel="stylesheet" media="all" type="text/css" href="//code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css">
 
    <script type="text/javascript" src="//code.jquery.com/ui/1.11.0/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.3/jquery-ui-timepicker-addon.min.css">
      <link rel="stylesheet" href="https://trentrichardson.com/examples/timepicker/jquery-ui-timepicker-addon.css">
    
 <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.3/jquery-ui-timepicker-addon.min.js"></script>
 <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.3/i18n/jquery-ui-timepicker-addon-i18n.min.js"></script>
  <script type="text/javascript" src=" https://trentrichardson.com/examples/timepicker/jquery-ui-sliderAccess.js"></script>

    <script>
    (function( $ ) {

        $('input[name=wt_date]').datetimepicker({
	controlType: 'select',
	oneLine: true,
	timeFormat: 'hh:mm tt'
});


 

})( jQuery );
    </script>