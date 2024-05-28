
<?php
/**
 * The blog template file.
 *
 * @package flatsome
 */

get_header();
$taxonomy = get_queried_object();
$term = get_queried_object();

?>
<link rel="stylesheet" href="/wp-content/themes/flatsome-child/css/jquery.fancybox.css">
<link rel='stylesheet' href='/wp-content/themes/flatsome-child/css/thuexe.css' type='text/css' media='all' />


	<link href="/wp-content/themes/flatsome-child/css/jquery-ui-git.css" rel="stylesheet" type="text/css" />
	
	<div id="page_taxonomy">
    <div class="zek_ticket_cat_banner">
      
        <?php
        echo woocommerce_category_image();
        ?>
                       
        <div class="container">
            <h1 class="title text-center">
                <?php 
                    echo  $taxonomy->name;?></h1>
                <?php
                $term = get_queried_object();
                $class = (is_tax('product_cat', $category->slug)) ? 'active' : '';
                // instead of permalink:
                if (is_tax('custom-taxonomy-slug')) {
                    $permalink = get_term_link(get_query_var('term'), 'custom-taxonomy-slug');
                } elseif (is_post_type_archive('custom-post-type')) {
                    $permalink = get_post_type_archive_link('custom-post-type');
                } else {
                    $permalink = get_permalink($post->ID);
                }
                ?>
            <form method="get" class="searchform search_bus" id="search_post_type" action="<?php echo get_term_link($taxonomy->term_id) ?>">
                <div class="search-form__content">
                    <div class="search-form__content__form">
                        <div class="frm-flight-from search-form__content__form__des box_click">
                            <i class="icon-clock"></i>
                            <div class="text">
                            
                                <?php 
                                    global $sitepress;
                                    $current_language = $sitepress->get_current_language();
                                ?>
                                <?php if($current_language =='vi'){?>
                                    <div class="labels">Điểm khởi hành</div>
                                <?php } ?>
                                <?php if($current_language =='en') { ?>
                                    <div class="labels">Departure</div>
                                <?php } ?>
                                <select id="cars" name="joblistdiemdi" form="search_post_type" style="-webkit-appearance: none;">
                                        
                                    <?php 
                                        global $sitepress;
                                        $current_language = $sitepress->get_current_language();
                                    ?>
                                    <?php if($current_language =='vi') { ?>
                                    <option value="">Chọn điểm đi</option>
                                    <?php } ?>
                                    <?php if($current_language =='en') { ?>
                                        <option value="">Select departure</option>
                                    <?php } ?>
                                    <?php
                                        $args = array(
                                        'post_type' => 'location',
                                        'post_status' => 'publish',
                                        
                                        'posts_per_page' => 100,
                                        'orderby' => 'post_date',
                                        'order' => 'asc'
                                        
                                        );
                                        $loop = new WP_Query($args);
                                        $total = $loop->found_posts;
                                        $iteration = 0;
                                        $i = 0;
                                        $diadiemdi = isset($_GET['joblistdiemdi']) && $_GET['joblistdiemdi'] ? $_GET['joblistdiemdi'] : "";
                                    ?>
                                    <?php
                                        while ($loop->have_posts()) : $loop->the_post();
                                            $iteration++; {
                                        ?>
        
                                    <option value="<?php echo get_the_ID();?>" ><?php echo get_the_title(); ?></option>
                                    <?php
                                    } endwhile;
                                        wp_reset_query();
                                    ?>
                                </select>
                                    
                            </div>
                            
                        </div>
                        <div class="search-form__content__form__price box_click">
                            <i class="icon-tag"></i>
                            <div class="text">
                                
                                <?php 
                                    global $sitepress;
                                    $current_language = $sitepress->get_current_language();
                                ?>
                                <?php if($current_language =='vi') { ?>
                                <div class="labels">Điểm đến</div>
                                <?php } ?>
                                <?php if($current_language =='en'){ ?>
                                    <div class="labels">Destination</div>
                                <?php } ?>
                                <select id="cars1" name="joblistdiemden" form="search_post_type" style="-webkit-appearance: none;">
                                <?php 
                                    global $sitepress;
                                    $current_language = $sitepress->get_current_language();
                                ?>
                                <?php if($current_language =='vi'){ ?>
                                    <option value="">Chọn điểm Đến</option>
                                <?php } ?>
                                <?php if($current_language =='en') { ?>
                                    <option value="">Select Destination</option>
                                <?php } ?>

                                <?php
                                    $args = array(
                                    'post_type' => 'location',
                                    'post_status' => 'publish',
                                    
                                    'posts_per_page' => 100,
                                    'orderby' => 'post_date',
                                    'order' => 'asc'
                                    
                                    );
                                    $loop = new WP_Query($args);
                                    $total = $loop->found_posts;
                                    $iteration = 0;
                                    $i = 0;
                                    $diadiemden = isset($_GET['joblistdiemden']) && $_GET['joblistdiemden'] ? $_GET['joblistdiemden'] : "";
                                ?>
  
                                <?php
                                    while ($loop->have_posts()) : $loop->the_post();
                                        $iteration++;
                                        $css="";
                                        if($diadiemden==$diadiemdi)
                                            $css='hidden';
                                        {
                                    ?>
                                    
                                <option type="<?php echo $css ?>" value="<?php echo get_the_ID();?>" ><?php echo get_the_title(); ?></option>
                                    <?php
                                        }
                                    endwhile;
                                    wp_reset_query();
                                    ?> 
                                    </select>
                            </div>
                            
                        </div>
                        <div class="search-form__content__form__date">
						    <i class="fa fa-calendar" aria-hidden="true"></i>
                            <div class="text">
                            <?php 
                                global $sitepress;
                                $current_language = $sitepress->get_current_language();
                            ?>
                            <?php if($current_language =='vi') { ?>
                            <div class="labels">Thời gian</div>
                                <?php } ?>
                                <?php if($current_language =='en') 
                                { ?>
                                <div class="labels">Departure Date</div>
                                <?php } ?>
                                <input type="text" id="datepicker1" name="departure_date" placeholder="">
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-orange btn-block btn-orange--mod" id="search_button"><i class="icon-search"></i>Search now</button>
                </div>
            </form>
        </div>
    </div>
    <div class="section-content-loadlink relative">
			<div class="row">
				<div class="col small-12 large-12">
				
					    <?php echo custom_breadcrumbproduct();?>
									
				</div>
			</div>
		</div>
    <div class="zek_ticket_product">
        <div class="container">
            <div class="woocommerce-notices-wrapper"></div>
            <?php

$paged = get_query_var('paged') ? get_query_var('paged') : 1;
                            
?>
            <div class="products list_product">
			<?php
                        if ($diadiemdi !='' && $diadiemden!='') {
                             $args = array(
                                'post_type' => 'product',
                                'post_status' => 'publish',
                                'posts_per_page' => 10,
                                'orderby' => 'post_date',
                                'order' => 'desc',
                                'paged' => $paged,
                                'meta_query' => array(
                                    'relation' => 'AND',

                                    array(

                                         'key' => 'add_di',
                                        'value'     => $diadiemdi,
                                    ),
                                     array(

                                         'key' => 'add_den',
                                        'value'     => $diadiemden,
                                    ),


                                ),
                                'tax_query' => array(
                                    array(
                                        'taxonomy' => 'product_cat',
                                        'field' => 'term_id',
                                        'terms' => $taxonomy->term_id,
                                    )
                                ),

                            );
                        } 
                        else {
                           $args = array(
                                'post_type' => 'product',
                                'post_status' => 'publish',
                                'posts_per_page' => 10,
                                'orderby' => 'post_date',
                                'order' => 'desc',
                                'paged' => $paged,
                                'tax_query' => array(
                                    array(
                                        'taxonomy' => 'product_cat',
                                        'field' => 'term_id',
                                        'terms' => $taxonomy->term_id,
                                    )
                                ),

                            );
                        }
                      
                        $i = 0;
                         $loop = new WP_Query($args);
                        while ($loop->have_posts()) : $loop->the_post(); {
                        ?>
	  <?php
 $time_xp = get_field('time_xp');
 $dd_xp = get_field('dd_xp');
 $time_dx = get_field('time_dx');
 $time_den = get_field('time_den');
 $dd_den = get_field('dd_den');
  
    ?>
                <!-- Item-trip -->
                <div class="card trip-card">
                    <div class="trip-info-card">
                        <div class="trip-info-card-body">

                            <div class="trip-points zek_position">
                                <a href="<?php echo get_the_permalink(); ?>"  title="<?php echo get_the_title(); ?>" class="zek_linkfull"></a>
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
							
                            <div class="trip-gallery">
								
      <?php echo create_gallery_product_box_congdongblog();?>

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
                          
                            <div class="booking">
                                <a href="<?php echo get_the_permalink(); ?>">Book now</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End -->
			<?php


                            }

                        endwhile;
                        wp_reset_query();
                        ?>
            </div>
             <?php wp_pagenavi(array('query' => $loop)) ?>
            
        </div>
    </div>


</div>
<script type="text/javascript" src="/wp-content/themes/flatsome-child/js/jquery.fancybox.min.js"></script>
<script type="text/javascript" src="/wp-content/themes/flatsome-child/js/jquery-ui-git.js"></script>
<script type="text/javascript" src="/wp-content/themes/flatsome-child/js/custom.js"></script>


