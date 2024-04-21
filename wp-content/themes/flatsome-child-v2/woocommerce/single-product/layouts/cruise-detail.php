<?php
global $wt_main_purpose;
$exwf_sgpp = exwt_get_layout_purpose(get_the_ID());
$wt_main_purpose = $exwf_sgpp;


global $product, $woocommerce;
$idPro = $product->id;
global $sitepress;
$current_language = $sitepress->get_current_language();
?>
<div class="cruise-details">
    <?php

    $image = get_field('Cruises_Image');

    ?>
    <div class="cruise-details-bg" <?php
                                    if ($image) {
                                        echo 'style="background-image: url(' . $image["url"] . ')"';
                                    }
                                    ?>>
        <div class="cruise-details-main">
            <div class="row">
                <div class="col  medium-12 small-12 large-12">
                    <div class="cruise-details-info">
                        <div class="cruise-details-heading">
                            <?php echo get_the_title($idPro); ?>
                        </div>
                        <div class="cruise-details-divider">

                        </div>
                        <div class="cruise-details-desc">
                            <?php echo get_post_meta(get_the_ID(), 'Cruises_Description', true); ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col medium-8  small-12 large-8">

            <div class="slidebox">
                <div class="slider slider-for1">
                    <?php
                    $attachment_ids = $product->get_gallery_attachment_ids();
                    foreach ($attachment_ids as $attachment_id) {
                    ?>
                        <div class='item'>
                            <img alt='' src='<?php echo wp_get_attachment_url($attachment_id, 'large'); ?>'>
                        </div>
                    <?php
                    }
                    ?>

                </div>

                <div class="slider slider-nav1">
                    <?php
                    $attachment_idse = $product->get_gallery_attachment_ids();
                    foreach ($attachment_idse as $attachment_ide) {
                    ?>
                        <div class='item'>
                            <img alt='' src='<?php echo wp_get_attachment_url($attachment_ide, 'large'); ?>'>
                        </div>

                    <?php
                    }
                    ?>

                </div>
            </div>

            <div class="productBoxCusbox noTopRadius" id="navHightlight">
                <div class="heading titlehightlight">

                    <?php
                    if ($current_language == 'vi') {
                        echo "Điểm nổi bật";
                    } elseif ($current_language == 'en') {
                        echo "Hightlight";
                    }

                    ?>
                </div>
                <div class="descriptionBox">
                    <?php echo get_post_meta(get_the_ID(), 'Cruises_Highlights', true); ?>
                </div>
            </div>

            <div class="productBoxCusbox noTopRadius" id="navItinerary">
                <div class="heading titleItinerary">
                    
                    
                    <?php
                    if ($current_language == 'vi') {
                        echo "Chương trình tour";
                    } elseif ($current_language == 'en') {
                        echo "Itinerary";
                    }

                    ?>
                </div>
                <div class="descriptionBox">
                    <div class="boxitinerary">
                        <ul id="boxacc" data-id="0" class="boxaccordion uk-accordion contentindex" uk-accordion="">
                            <?php

                            if (have_rows('Cruises_Itinerary')) :
                            ?>

                                <?php
                                $i = 0;
                                // Loop through rows.
                                while (have_rows('Cruises_Itinerary')) : the_row();
                                    // Load sub field value.
                                    $name = get_sub_field('Cruises_Itinerary_Title');
                                    $value = get_sub_field('Cruises_Itinerary_Content');
                                ?>

                                    <li class="<?php echo $i; ?> ">
                                        <a class="uk-accordion-title daytitlekmdate" href="#<?php echo $i; ?>">
                                            <span class="title14 days"><?php
                                                                        echo    $name;
                                                                        ?>,</span>
                                            <span class="title15 visitdays"></span><span class="uk-float-right daytxt"></span>
                                        </a>
                                        <div class="uk-accordion-content class-<?php echo $i; ?>" hidden="" aria-hidden="true">
                                            <?php
                                            echo    $value;
                                            ?>
                                        </div>
                                    </li>
                                    <script>
                                        jQuery("#boxacc li.<?php echo $i; ?> a.uk-accordion-title").click(function() {
                                            jQuery(".class-<?php echo $i; ?>").toggle();
                                        });
                                    </script>

                                    <?php
                                    ?>

                                <?php

                                    // Do something...

                                    // End loop.
                                    $i++;
                                endwhile;


                                ?>

                            <?php
                            endif;
                            ?>

                        </ul>
                    </div>

                </div>
            </div>

        </div>
        <div class="col medium-4 small-12 large-4">
            <div class="bookingnow-boxx">
                <div class="exwt-booking-form">
                    <?php
                    woocommerce_template_single_add_to_cart();
                    // do_action('exwt_after_atc_form')
                    ?>
                </div>
            </div>

        </div>
    </div>
    <div class="row">
        <div class="col medium-6 small-12 large-6">
            <div class="productBoxCusbox noTopRadius" id="navIncludes">
                <div class="heading titleInclusions">
                    
                    
                    <?php
                    if ($current_language == 'vi') {
                        echo "Giá tour bao gồm";
                    } elseif ($current_language == 'en') {
                        echo "Includes";
                    }

                    ?>
                </div>
                <div class="descriptionBox descInclusions">
                    <?php echo get_post_meta(get_the_ID(), 'Cruises_Includes', true); ?>
                </div>
            </div>
        </div>
        <div class="col medium-6 small-12 large-6">
            <div class="productBoxCusbox noTopRadius" id="navIncludes">
                <div class="heading titleExclusions">
                    
                    <?php
                    if ($current_language == 'vi') {
                        echo "Giá tour không bao gồm";
                    } elseif ($current_language == 'en') {
                        echo "Exclusions";
                    }

                    ?>
                </div>
                <div class="descriptionBox ">
                    <?php echo get_post_meta(get_the_ID(), 'Cruises_Excludes', true); ?>
                </div>
            </div>
        </div>

        <div class="col medium-12 small-12 large-12">

            <div class="productBoxCusbox noTopRadius" id="navPolicies">
                <div class="heading titleInclusions">
                    
                    <?php
                    if ($current_language == 'vi') {
                        echo "Chính sách";
                    } elseif ($current_language == 'en') {
                        echo "Policies";
                    }

                    ?>
                </div>
                <div class="descriptionBox descExclusions">
                    <?php echo get_post_meta(get_the_ID(), 'Cruises_Policies', true); ?>
                </div>
            </div>
        </div>
        <div class="col medium-12 small-12 large-12">

            <div class="productBoxCusbox noTopRadius" id="navPolicies">
                <div class="heading titleInclusions">
                    
                    <?php
                    if ($current_language == 'vi') {
                        echo "Tour liên quan";
                    } elseif ($current_language == 'en') {
                        echo "Similar Cruises";
                    }

                    ?>
                </div>
                <div class="row RealteTour-row">
                    <?php
                    if (!is_a($product, 'WC_Product')) {
                        $product = wc_get_product(get_the_id());
                    }
                    $custom_taxterms = wp_get_object_terms($product->ID, 'product_cat', array('fields' => 'ids'));
                    $taxonomy   = 'product_cat';

                    $term_slugs = wp_get_post_terms(get_the_id(), $taxonomy, ['fields' => 'slugs']);
                    $args = apply_filters('woocommerce_related_products_args', array(
                        'post_type'            => 'product',
                        'ignore_sticky_posts'  => 1,
                        'posts_per_page'       => 6,

                        'post__not_in'         => array(get_the_id()),
                        'tax_query'            => array(array(
                            'taxonomy' => $taxonomy,
                            'field'    => 'slug',
                            'terms'    => $term_slugs,
                        )),
                        'meta_key' => 'detail_pr',
                        'meta_value' => 'Cruise',

                        'fields'  => 'ids',
                        'orderby' => 'rand',
                    ));
                    $related_items = new WP_Query($args);
                    // loop over query
                    if ($related_items->have_posts()) :
                        while ($related_items->have_posts()) : $related_items->the_post();
                    ?>
                            <div class="col medium-4 small-12 large-4">
                                <?php
                                global $sitepress;
                                $current_language = $sitepress->get_current_language();

                                $price = get_post_meta(get_the_ID(), '_regular_price', true);
                                $saleprice = get_post_meta(get_the_ID(), '_sale_price', true);
                                ?>
                                <?php
                                $field = get_field_object('Pro_F1');
                                $value = $field['value'];
                                $label = $field['choices'][$value];
                                ?>


                                <div class="item">
                                    <div class="homeProItem">

                                        <div class="homeProThumb">
                                            <a href="<?php echo get_the_permalink(); ?>" title="<?php echo get_the_title(); ?>">
                                                <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'large') ?>" alt="<?php echo get_the_title(); ?>" />
                                            </a>
                                        </div>
                                        <div class="homeProContent">
                                            <div class="homeProTitle">
                                                <a href="<?php echo get_the_permalink(); ?>" title="<?php echo get_the_title(); ?>">
                                                    <?php echo get_the_title(); ?>
                                                </a>
                                            </div>
                                            <div class="homeProField">
                                                <div class="prox-custom">

                                                    <div class="prox--custoe prox-confi"><strong>
                                                            <?php
                                                            if ($current_language == 'vi') {
                                                                echo "Loại:";
                                                            }
                                                            if ($current_language == 'en') {
                                                                echo "Category:";
                                                            }
                                                            ?>
                                                        </strong><?php echo get_post_meta(get_the_ID(), 'Cruises_Category', true); ?></div>
                                                    <div class="prox--custoe prox-timego"><strong>
                                                            <?php
                                                            if ($current_language == 'vi') {
                                                                echo "Điểm đến:";
                                                            }
                                                            if ($current_language == 'en') {
                                                                echo "Destination:";
                                                            }
                                                            ?> </strong><?php echo get_post_meta(get_the_ID(), 'Cruises_Destination', true); ?></div>
                                                    <div class="prox--custoe prox-hotel"><strong>
                                                            <?php
                                                            if ($current_language == 'vi') {
                                                                echo "Hình thức:";
                                                            }
                                                            if ($current_language == 'en') {
                                                                echo "CAPACITY:";
                                                            }
                                                            ?> </strong><?php echo get_post_meta(get_the_ID(), 'Cruises_Capacity', true); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="homeProPrice">
                                                <div class="ProPriceGr">
                                                    <?php echo GetPrice($saleprice, $price); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
<?php


?>