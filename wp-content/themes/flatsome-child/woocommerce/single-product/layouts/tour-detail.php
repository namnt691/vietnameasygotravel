<?php
global $wt_main_purpose;
$exwf_sgpp = exwt_get_layout_purpose(get_the_ID());
$wt_main_purpose = $exwf_sgpp;


global $product, $woocommerce;
$idPro = $product->id;
global $sitepress;
$current_language = $sitepress->get_current_language();
?>

<div class="pageTourDetails">
    <div class="row">
        <div class="col medium-12 small-12 large-12">
            <h1 class="tieude-detail ">
                <?php echo get_the_title($idPro); ?>
            </h1>
            <div class="tr-gallery-box">
                <div class="gallery_list">
                    <div class="gallery_item item_first">
                        <img class="gallery_img" id="zoom_01" src="<?php echo get_the_post_thumbnail_url($idPro, 'large') ?>">
                    </div>
                    <?php

                    $attachment_ids = $product->get_gallery_attachment_ids();
                    $i = 0;
                    foreach ($attachment_ids as $attachment_id) {

                        if ($i < 2) {
                    ?>
                            <div class="gallery_item  ">
                                <img class="gallery_img" src="<?php echo wp_get_attachment_url($attachment_id, 'large'); ?>" alt="">
                            </div>
                    <?php
                        }

                        $i++;
                    }
                    ?>
                </div>
            </div>
        </div>

    </div>
    <div class="row">
        <div class="col medium-12 small-12 large-12">

        </div>
    </div>
    <div class="row">
        <div class="col medium-8 small-12 large-8">

            <div class="row">
                <div class="col medium-12 small-12 large-12">
                    <div class="tabBox">
                        <div class="navProTab">
                            <ul>
                                <li class="navbtnMenuli nav-item">
                                    <a class="navbtnMenu navOverview active" href="#navOverview">

                                        <?php
                                        if ($current_language == 'vi') {
                                            echo "Tổng quan";
                                        } elseif ($current_language == 'en') {
                                            echo "Overview";
                                        }

                                        ?>
                                    </a>
                                </li>
                                <li class="navbtnMenuli nav-item">
                                    <a class="navbtnMenu navHightlight " href="#navHightlight">
                                        
                                        <?php
                                        if ($current_language == 'vi') {
                                            echo "Điểm nổi bật";
                                        } elseif ($current_language == 'en') {
                                            echo "Hightlight";
                                        }

                                        ?>
                                    </a>
                                </li>
                                <li class="navbtnMenuli nav-item">
                                    <a class="navbtnMenu navItinerary " href="#navItinerary">
                                        
                                        <?php
                                        if ($current_language == 'vi') {
                                            echo "Chương trình tour";
                                        } elseif ($current_language == 'en') {
                                            echo "Itinerary";
                                        }

                                        ?>
                                    </a>
                                </li>
                                <li class="navbtnMenuli nav-item">
                                    <a class="navbtnMenu navInclusions " href="#navInclusions">
                                        
                                        <?php
                                        if ($current_language == 'vi') {
                                            echo "Giá tour bao gồm";
                                        } elseif ($current_language == 'en') {
                                            echo "Inclusions";
                                        }

                                        ?>
                                    </a>
                                </li>
                                <li class="navbtnMenuli nav-item">
                                    <a class="navbtnMenu navPolicy " href="#navPolicy">
                                        
                                        <?php
                                        if ($current_language == 'vi') {
                                            echo "Chính sách";
                                        } elseif ($current_language == 'en') {
                                            echo "Policy";
                                        }

                                        ?>
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </div>
                    <div class="productBoxCusbox noTopRadius" id="navOverview">
                        <div class="heading titleoverview">
                            
                            <?php
                                        if ($current_language == 'vi') {
                                            echo "Tổng quan";
                                        } elseif ($current_language == 'en') {
                                            echo "Overview";
                                        }

                                        ?>
                        </div>
                        <div class="descriptionBox">
                            <div class="row">
                                <div class="col  medium-6 small-12 large-6">
                                    <?php

                                    if (have_rows('Tour_Overview')) :
                                    ?>

                                        <?php

                                        while (have_rows('Tour_Overview')) : the_row();
                                            // Load sub field value.
                                            $name = get_sub_field('Tour_Overview_Image');
                                            $value = get_sub_field('Tour_Overview_Description');
                                        ?>
                                            <?php
                                            $image = get_sub_field('Tour_Overview_Image');
                                            ?>
                                            <a class="hover-effect">
                                                <img loading="lazy" width="100%" height="100%" src="<?php echo esc_url($image['url']); ?>" alt="">
                                            </a>
                                            <?php
                                            ?>
                                        <?php

                                        endwhile;
                                        ?>

                                    <?php
                                    endif;
                                    ?>


                                </div>
                                <div class="col  medium-6 small-12 large-6">
                                    <div class="prox-custom">
                                        <div class="prox--custoe prox-time">
                                            <strong>
                                                <?php
                                                $field = get_field_object('Pro_F1');
                                                $value = $field['value'];
                                                $label = $field['choices'][$value];
                                                if ($current_language == 'vi') {
                                                    echo "Thời gian:";
                                                }
                                                if ($current_language == 'en') {
                                                    echo "Time:";
                                                }
                                                ?>
                                            </strong>
                                            <?php echo get_post_meta(get_the_ID(), 'Post_Description', true); ?>
                                            <?php echo $label; ?>
                                        </div>
                                        <div class="prox--custoe prox-timego"><strong>
                                                <?php
                                                if ($current_language == 'vi') {
                                                    echo "Khởi hành - Điểm đến:";
                                                }
                                                if ($current_language == 'en') {
                                                    echo "Departure - Destination:";
                                                }
                                                ?>
                                            </strong><?php echo get_post_meta(get_the_ID(), 'Pro_F2', true); ?>
                                        </div>
                                        <div class="prox--custoe prox-hotel"><strong>
                                                <?php
                                                if ($current_language == 'vi') {
                                                    echo "Thời gian khởi hành:";
                                                }
                                                if ($current_language == 'en') {
                                                    echo "Departure time:";
                                                }
                                                ?> </strong><?php echo get_post_meta(get_the_ID(), 'Pro_F3', true); ?>
                                        </div>
                                        <div class="prox--custoe prox-tourin"><strong>
                                                <?php
                                                if ($current_language == 'vi') {
                                                    echo "Hình thức:";
                                                }
                                                if ($current_language == 'en') {
                                                    echo "Form:";
                                                }
                                                ?> </strong><?php echo get_post_meta(get_the_ID(), 'Pro_F4', true); ?>
                                        </div>
                                        <div class="prox--custoe prox-confi"><strong>
                                                <?php
                                                if ($current_language == 'vi') {
                                                    echo "Khách sạn:";
                                                }
                                                if ($current_language == 'en') {
                                                    echo "Hotel:";
                                                }
                                                ?> </strong><?php echo get_post_meta(get_the_ID(), 'Pro_F5', true); ?>
                                        </div>
                                    </div>
                                    <?php

                                    if (have_rows('Tour_Overview')) :
                                    ?>

                                        <?php

                                        while (have_rows('Tour_Overview')) : the_row();
                                            // Load sub field value.
                                            $name = get_sub_field('Tour_Overview_Image');
                                            $value = get_sub_field('Tour_Overview_Description');
                                        ?>
                                            <div class="overdesc">
                                                <?php echo $value; ?>
                                            </div>
                                        <?php

                                        endwhile;
                                        ?>

                                    <?php
                                    endif;
                                    ?>
                                </div>
                            </div>
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
                            <?php echo get_post_meta(get_the_ID(), 'Tour_Hightlight', true); ?>
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

                                    if (have_rows('Tour_Itinerary')) :
                                    ?>

                                        <?php
                                        $i = 0;
                                        // Loop through rows.
                                        while (have_rows('Tour_Itinerary')) : the_row();
                                            // Load sub field value.
                                            $name = get_sub_field('Tour_Itinerary_Title');
                                            $value = get_sub_field('Tour_Itinerary_Content');
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
                    <div class="productBoxCusbox noTopRadius" id="navInclusions">
                        <div class="row">
                            <div class="col  medium-6 small-12 large-6">
                                <div class="heading titleInclusions">
                                    
                                    <?php
                                        if ($current_language == 'vi') {
                                            echo "Giá tour bao gồm";
                                        } elseif ($current_language == 'en') {
                                            echo "Inclusions";
                                        }

                                        ?>
                                </div>
                                <div class="descriptionBox descInclusions">
                                    <?php echo get_post_meta(get_the_ID(), 'Tour_Inclusions', true); ?>
                                </div>
                            </div>
                            <div class="col  medium-6 small-12 large-6">
                                <div class="heading titleExclusions">
                                    
                                    <?php
                                        if ($current_language == 'vi') {
                                            echo "Giá tour không bao gồm";
                                        } elseif ($current_language == 'en') {
                                            echo "Exclusions";
                                        }

                                        ?>
                                </div>
                                <div class="descriptionBox descExclusions">
                                    <?php echo get_post_meta(get_the_ID(), 'Tour_Exclusions', true); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="productBoxCusbox noTopRadius" id="navPolicy">
                        <div class="heading titlePolicy">

                        </div>
                        <div class="descriptionBox">
                            <?php echo get_post_meta(get_the_ID(), 'Tour_Policy', true); ?>
                        </div>
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
    <div class="row RealteTour-row">
        <div class="col medium-12 small-12 large-12">
            <div class="RealteTour">
                <h3><?php

                    if ($current_language == 'vi') {
                        echo "Tour liên quan";
                    }
                    if ($current_language == 'en') {
                        echo "Other Tours";
                    }

                    ?></h3>
            </div>
        </div>
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
            'meta_value' => 'Tour',
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
                                        <div class="prox--custoe prox-time">
                                            <strong>
                                                <?php
                                                if ($current_language == 'vi') {
                                                    echo "Thời gian:";
                                                }
                                                if ($current_language == 'en') {
                                                    echo "Time:";
                                                }
                                                ?>
                                            </strong>
                                            <?php echo $label; ?>
                                        </div>
                                        <div class="prox--custoe prox-timego"><strong>
                                                <?php
                                                if ($current_language == 'vi') {
                                                    echo "Khởi hành - Điểm đến:";
                                                }
                                                if ($current_language == 'en') {
                                                    echo "Departure - Destination:";
                                                }
                                                ?>
                                            </strong><?php echo get_post_meta(get_the_ID(), 'Pro_F2', true); ?></div>
                                        <div class="prox--custoe prox-hotel"><strong>
                                                <?php
                                                if ($current_language == 'vi') {
                                                    echo "Thời gian khởi hành:";
                                                }
                                                if ($current_language == 'en') {
                                                    echo "Departure time:";
                                                }
                                                ?> </strong><?php echo get_post_meta(get_the_ID(), 'Pro_F3', true); ?></div>
                                        <div class="prox--custoe prox-tourin"><strong>
                                                <?php
                                                if ($current_language == 'vi') {
                                                    echo "Hình thức:";
                                                }
                                                if ($current_language == 'en') {
                                                    echo "Form:";
                                                }
                                                ?> </strong><?php echo get_post_meta(get_the_ID(), 'Pro_F4', true); ?></div>
                                        <div class="prox--custoe prox-confi"><strong><?php
                                                                                        if ($current_language == 'vi') {
                                                                                            echo "Khách sạn:";
                                                                                        }
                                                                                        if ($current_language == 'en') {
                                                                                            echo "Hotel:";
                                                                                        }
                                                                                        ?> </strong><?php echo get_post_meta(get_the_ID(), 'Pro_F5', true); ?></div>
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

<?php


?>