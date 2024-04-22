<?php
// Add custom Theme Functions here

if (!function_exists('reviews')) {


    function reviews()
    {

        $labels = array(
            'name'                  => __('Trang Ý kiến', 'Post Type General Name', 'tnthemes'),
            'singular_name'         => __('Trang Ý kiến', 'Post Type Singular Name', 'tnthemes'),
            'menu_name'             => __('Trang Ý kiến', 'tnthemes'),
            'name_admin_bar'        => __('Trang Ý kiến', 'tnthemes'),
            'archives'              => __('Danh mục', 'tnthemes'),
            'attributes'            => __('Danh mục', 'tnthemes'),
            'parent_item_colon'     => __('Bài viết cha', 'tnthemes'),
            'all_items'             => __('Tất cả bài viết', 'tnthemes'),
            'add_new_item'          => __('Thêm bài viết mới', 'tnthemes'),
            'add_new'               => __('Thêm mới', 'tnthemes'),
            'new_item'              => __('Bài mới', 'tnthemes'),
            'edit_item'             => __('Chỉnh sửa bài viết', 'tnthemes'),
            'update_item'           => __('Cập nhật bài viết', 'tnthemes'),
            'view_item'             => __('Xem bài viết', 'tnthemes'),
            'view_items'            => __('Xem bài viết', 'tnthemes'),
            'search_items'          => __('Tìm kiếm bài viết', 'tnthemes'),
            'not_found'             => __('Not found', 'tnthemes'),
            'not_found_in_trash'    => __('Not found in Trash', 'tnthemes'),
            'featured_image'        => __('Ảnh đại diện', 'tnthemes'),
            'set_featured_image'    => __('Chọn ảnh đại diện', 'tnthemes'),
            'remove_featured_image' => __('Xóa ảnh đại diện', 'tnthemes'),
            'use_featured_image'    => __('Sử dụng ảnh đại diện', 'tnthemes'),
            'insert_into_item'      => __('Insert into item', 'tnthemes'),
            'uploaded_to_this_item' => __('Uploaded to this item', 'tnthemes'),
            'items_list'            => __('Danh sách', 'tnthemes'),
            'items_list_navigation' => __('Danh sách', 'tnthemes'),
            'filter_items_list'     => __('Lọc', 'tnthemes'),
        );
        $rewrite = array(
            'slug'                  => 'reviews',
            'with_front'            => true,
            'pages'                 => true,
            'feeds'                 => true,
        );
        $args = array(
            'label'                 => __('Trang Ý kiến', 'tnthemes'),
            'description'           => __('Bài viết trong trang về trang giới thiệu', 'tnthemes'),
            'labels'                => $labels,
            'supports'              => array('title', 'archives', 'thumbnail', 'custom-fields'),
            'taxonomies'            => array(''),
            'hierarchical'               => true,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 10,
            'menu_icon'             => 'dashicons-admin-tools',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => 'y-kien',
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'rewrite'               => $rewrite,
            'capability_type'       => 'page'

        );
        register_post_type('reviews', $args);
    }
    add_action('init', 'reviews', 0);
    add_action('init', function () {
        add_ux_builder_post_type('reviews');
    });
}

if (!function_exists('reviews_taxonomy')) {

    // Register Custom Taxonomy
    function reviews_taxonomy()
    {

        $labels = array(
            'name'                       => __('Danh mục Ý kiến', 'Taxonomy General Name', 'tnthemes'),
            'singular_name'              => __('Danh mục Ý kiến', 'Taxonomy Singular Name', 'tnthemes'),
            'menu_name'                  => __('Danh mục Ý kiến', 'tnthemes'),
            'all_items'                  => __('Tất cả', 'tnthemes'),
            'parent_item'                => __('Mục cha', 'tnthemes'),
            'parent_item_colon'          => __('Danh mục:', 'tnthemes'),
            'new_item_name'              => __('Tên danh mục mới', 'tnthemes'),
            'add_new_item'               => __('Thêm danh mục mới', 'tnthemes'),
            'edit_item'                  => __('Sửa danh mục', 'tnthemes'),
            'update_item'                => __('Cập nhật danh mục', 'tnthemes'),
            'view_item'                  => __('Xem', 'tnthemes'),
            'separate_items_with_commas' => __('Separate items with commas', 'tnthemes'),
            'add_or_remove_items'        => __('Thêm hoặc xóa danh mục', 'tnthemes'),
            'choose_from_most_used'      => __('Chọn danh mục hay sử dụng', 'tnthemes'),
            'popular_items'              => __('Danh mục phổ biến', 'tnthemes'),
            'search_items'               => __('Tìm danh mục', 'tnthemes'),
            'not_found'                  => __('Không tìm thấy', 'tnthemes'),
            'no_terms'                   => __('Không có danh mục', 'tnthemes'),
            'items_list'                 => __('Danh sách danh mục', 'tnthemes'),
            'items_list_navigation'      => __('Items list navigation', 'tnthemes'),
        );
        $rewrite = array(
            'slug'                       => 'y-kien',
            'with_front'                 => true,
            'hierarchical'               => true,
        );
        $args = array(
            'labels'                     => $labels,
            'hierarchical'               => true,
            'public'                     => true,
            'show_ui'                    => false,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => true,
            'show_tagcloud'              => true,
            'rewrite'                    => $rewrite,
        );
        register_taxonomy('reviews-cate', array('reviews'), $args);
    }
    add_action('init', 'reviews_taxonomy', 0);
}
// Địa điểm
if (!function_exists('location')) {
    function location()
    {

        $labels = array(
            'name'                  => __('Trang Địa điểm', 'Post Type General Name', 'tnthemes'),
            'singular_name'         => __('Trang Địa điểm', 'Post Type Singular Name', 'tnthemes'),
            'menu_name'             => __('Trang Địa điểm', 'tnthemes'),
            'name_admin_bar'        => __('Trang Địa điểm', 'tnthemes'),
            'archives'              => __('Danh mục', 'tnthemes'),
            'attributes'            => __('Danh mục', 'tnthemes'),
            'parent_item_colon'     => __('Bài viết cha', 'tnthemes'),
            'all_items'             => __('Tất cả bài viết', 'tnthemes'),
            'add_new_item'          => __('Thêm bài viết mới', 'tnthemes'),
            'add_new'               => __('Thêm mới', 'tnthemes'),
            'new_item'              => __('Bài mới', 'tnthemes'),
            'edit_item'             => __('Chỉnh sửa bài viết', 'tnthemes'),
            'update_item'           => __('Cập nhật bài viết', 'tnthemes'),
            'view_item'             => __('Xem bài viết', 'tnthemes'),
            'view_items'            => __('Xem bài viết', 'tnthemes'),
            'search_items'          => __('Tìm kiếm bài viết', 'tnthemes'),
            'not_found'             => __('Not found', 'tnthemes'),
            'not_found_in_trash'    => __('Not found in Trash', 'tnthemes'),
            'featured_image'        => __('Ảnh đại diện', 'tnthemes'),
            'set_featured_image'    => __('Chọn ảnh đại diện', 'tnthemes'),
            'remove_featured_image' => __('Xóa ảnh đại diện', 'tnthemes'),
            'use_featured_image'    => __('Sử dụng ảnh đại diện', 'tnthemes'),
            'insert_into_item'      => __('Insert into item', 'tnthemes'),
            'uploaded_to_this_item' => __('Uploaded to this item', 'tnthemes'),
            'items_list'            => __('Danh sách', 'tnthemes'),
            'items_list_navigation' => __('Danh sách', 'tnthemes'),
            'filter_items_list'     => __('Lọc', 'tnthemes'),
        );
        $rewrite = array(
            'slug'                  => 'location',
            'with_front'            => true,
            'pages'                 => true,
            'feeds'                 => true,
        );
        $args = array(
            'label'                 => __('Trang Địa điểm', 'tnthemes'),
            'description'           => __('Bài viết trong trang về trang Địa điểm', 'tnthemes'),
            'labels'                => $labels,
            'supports'              => array('title', 'archives', 'thumbnail', 'custom-fields'),
            'taxonomies'            => array(''),
            'hierarchical'               => true,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 10,
            'menu_icon'             => 'dashicons-admin-tools',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => 'dia-diem',
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'rewrite'               => $rewrite,
            'capability_type'       => 'page'

        );
        register_post_type('location', $args);
    }
    add_action('init', 'location', 0);
    add_action('init', function () {
        add_ux_builder_post_type('location');
    });
}

if (!function_exists('location_taxonomy')) {

    // Register Custom Taxonomy
    function location_taxonomy()
    {

        $labels = array(
            'name'                       => __('Danh mục', 'Taxonomy General Name', 'tnthemes'),
            'singular_name'              => __('Danh mục', 'Taxonomy Singular Name', 'tnthemes'),
            'menu_name'                  => __('Danh mục', 'tnthemes'),
            'all_items'                  => __('Tất cả', 'tnthemes'),
            'parent_item'                => __('Mục cha', 'tnthemes'),
            'parent_item_colon'          => __('Danh mục:', 'tnthemes'),
            'new_item_name'              => __('Tên danh mục mới', 'tnthemes'),
            'add_new_item'               => __('Thêm danh mục mới', 'tnthemes'),
            'edit_item'                  => __('Sửa danh mục', 'tnthemes'),
            'update_item'                => __('Cập nhật danh mục', 'tnthemes'),
            'view_item'                  => __('Xem', 'tnthemes'),
            'separate_items_with_commas' => __('Separate items with commas', 'tnthemes'),
            'add_or_remove_items'        => __('Thêm hoặc xóa danh mục', 'tnthemes'),
            'choose_from_most_used'      => __('Chọn danh mục hay sử dụng', 'tnthemes'),
            'popular_items'              => __('Danh mục phổ biến', 'tnthemes'),
            'search_items'               => __('Tìm danh mục', 'tnthemes'),
            'not_found'                  => __('Không tìm thấy', 'tnthemes'),
            'no_terms'                   => __('Không có danh mục', 'tnthemes'),
            'items_list'                 => __('Danh sách danh mục', 'tnthemes'),
            'items_list_navigation'      => __('Items list navigation', 'tnthemes'),
        );
        $rewrite = array(
            'slug'                       => 'dia-diem',
            'with_front'                 => true,
            'hierarchical'               => true,
        );
        $args = array(
            'labels'                     => $labels,
            'hierarchical'               => true,
            'public'                     => true,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => true,
            'show_tagcloud'              => true,
            'rewrite'                    => $rewrite,
        );
        register_taxonomy('location-cate', array('location'), $args);
    }
    add_action('init', 'location_taxonomy', 0);
}


add_filter( 'gettext', 'translate_woocommerce_strings', 999 );

function translate_woocommerce_strings( $translated ) {
     global $sitepress;
    $current_language = $sitepress->get_current_language();

    if ($current_language == 'vi') {
        $translated = str_ireplace( 'Total', 'Tổng', $translated );
         $translated = str_ireplace( 'Departure', 'Chọn ngày', $translated );
          $translated = str_ireplace( 'in stock', 'chỗ', $translated );
    }
    if ($current_language == 'en') {
         $translated = str_ireplace( 'in stock', 'available', $translated );
    }


  return $translated;
}

add_filter('woocommerce_currency_symbol', 'change_existing_currency_symbol', 10, 2);
function change_existing_currency_symbol($currency_symbol, $currency)
{

    global $sitepress;
    $current_language = $sitepress->get_current_language();

    if ($current_language == 'vi') {
        switch ($currency) {
            case 'đ':
                $currency_symbol = 'đ';
                break;
        }
    }
    if ($current_language == 'en') {
        switch ($currency) {
            case 'đ':
                $currency_symbol = '$';
                break;
        }
    }


    return $currency_symbol;
}

//Địa điểm
function getdiadiemcate()
{
    ob_start();
?>


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
    ?>
    <?php
    while ($loop->have_posts()) : $loop->the_post();
        $iteration++; {
    ?>

            <option value="<?php echo get_the_ID(); ?>"><?php echo get_the_title(); ?></option>


    <?php
        }
    endwhile;
    wp_reset_query();
    ?>

<?php
    return ob_get_clean();
}

add_shortcode("diadiemcate", 'getdiadiemcate');

function create_gallery_product_box_congdongblog()
{
    global $product;
    $product_cat =  get_the_terms($product->get_ID(), 'product_cat');
    // var_dump($product_cat);
    if ($product_cat && !is_wp_error($product_cat)) {
        //echo '<span class="isures-cate--label"><a href=' . esc_url( get_category_link( $product_cat[0]->term_id ) ) . ' title="Danh mục '.$product_cat[0]->name.'">' . $product_cat[0]->name . '</a></span>';
    }
    $attachment_ids = $product->get_gallery_image_ids();
    $count = count($attachment_ids);

    $thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($product->get_ID()));
    $size_full = wp_get_attachment_image_src(get_post_thumbnail_id($product->get_ID()), "full");
    // echo '<div class="isures-thumb--wrap">';
    echo '<a href="' . $size_full[0] . '" class="figure" data-fancybox="gallery' . get_post_thumbnail_id() . '"><img width="1200" height="660" src="' . $size_full[0] . '" data-full="' . $size_full[0] . '"></a>';
    $i = 0;
    foreach ($attachment_ids as $attachment_id) {
        // if ($i < 3) {
        echo '<a href="' . wp_get_attachment_image_src($attachment_id, 'size_full')[0] . '" class="figure" data-fancybox="gallery' . get_post_thumbnail_id() . '">';
        echo '<img width="1200" height="660" src="' . wp_get_attachment_image_src($attachment_id, 'thumbnail')[0] . '" data-full="' . wp_get_attachment_image_src($attachment_id, 'full')[0] . '">';
        echo '</a>';
        // }
        // if ($i == 3) {
        // echo '<a class="isures-more--btn" href="' . get_permalink() . '"><span>Xem thêm + ' . ($count - $i) . '</span></a>';
        // }


        $i++;
    }

    // echo '</div>';

}
add_action('woocommerce_before_shop_loop_item_title', 'create_gallery_product_box_congdongblog');

add_action('wp_footer', 'add_script_footer_gallery');

function add_script_footer_gallery()
{
?>
    <script>
        jQuery('body').on('mouseenter', '.isures-thumb--items', function() {

            let change_box = jQuery(this).closest('.product-small');
            let img_this = jQuery(this).find('img').attr('data-full');
            jQuery(change_box).find('.box-image img').attr('src', img_this);
            jQuery(change_box).find('.box-image img').attr('srcset', img_this);
            jQuery(change_box).find('.isures-thumb--items').removeClass('active');
            jQuery(this).addClass('active');
        });
    </script>
<?php
}


function woocommerce_category_image()
{
    if (is_product_category()) {
        global $wp_query;
        $cat = $wp_query->get_queried_object();
        $thumbnail_id = get_woocommerce_term_meta($cat->term_id, 'thumbnail_id', true);
        $image = wp_get_attachment_url($thumbnail_id);
        if ($image) {
            echo '<img class="category-image" src="' . $image . '" alt="" />';
        }
    }
}


//Chuyển đơn vị cỡ chữ
if (!function_exists('mce_text_sizes')) {
    function mce_text_sizes($initArray)
    {
        $initArray['fontsize_formats'] = "9px 10px 12px 13px 14px 16px 17px 18px 19px 20px 21px 24px 28px 32px 36px";
        return $initArray;
    }
    add_filter('tiny_mce_before_init', 'mce_text_sizes', 99);
}
//Weiget
add_filter('gutenberg_use_widgets_block_editor', '__return_false');
add_filter('use_widgets_block_editor', '__return_false');
//Menu Nội dung
add_theme_support('menus');
register_nav_menus(
    array(

        'footer-nav' => 'Menu nội dung'
    )
);




function GetBlogHot($code, $arg)
{
    ob_start();
    $args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'cat' => '',
        'posts_per_page' => 8,
        'orderby' => 'post_date',
        'order' => 'desc',
        'meta_query' => array(
            array(
                'key' => 'Post_Hot', //newshot là mã custom field tin tức
                'value' => '1'
            )
        )
    );
    $loop = new WP_Query($args);
    $total = $loop->found_posts;
    $iteration = 0;
    $i = 0;

?>
    <div class='container'>
        <div class=' bloghot-home  owl-carousel'>
            <?php
            while ($loop->have_posts()) : $loop->the_post();
                $iteration++; {
            ?>

                    <div class="item">
                        <div class="homeblog-item">
                            <div class="homeblog-thumb">
                                <a href="<?php echo get_the_permalink(); ?>" title="<?php echo get_the_title() ?>">
                                    <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'large') ?>" alt="<?php echo get_the_title() ?>" />
                                </a>
                            </div>
                            <div class="homeblog-content">
                                <div class="homeblog-date">
                                    <?php echo get_the_modified_date(); ?>
                                </div>
                                <div class="homeblog-title">
                                    <a href="<?php echo get_the_permalink(); ?>" title="<?php echo get_the_title() ?>">
                                        <?php echo get_the_title() ?>
                                    </a>
                                </div>
                                <div class="homeblog-description">
                                    <?php echo get_post_meta(get_the_ID(), 'Post_Description', true); ?>
                                </div>
                                <?php

                                global $sitepress;
                                $current_language = $sitepress->get_current_language();
                                $readmore = "Xem thêm";
                                if ($current_language == 'vi') {
                                    $readmore = "Xem thêm";
                                }
                                if ($current_language == 'en') {
                                    $readmore = "Read more";
                                }

                                ?>
                                <div class="homeblog-readmore">
                                    <a href="<?php echo get_the_permalink(); ?>" title="<?php echo  $readmore; ?>">
                                        <?php echo  $readmore; ?>
                                    </a>
                                </div>

                            </div>
                        </div>
                    </div>

            <?php
                }
            endwhile;
            ?>
        </div>
    </div>
<?php
    wp_reset_query();
    return ob_get_clean();
}
add_shortcode('GetBlogHot_Fn', 'GetBlogHot');


function GetReviews($code, $arg)
{
    ob_start();
    $args = array(
        'post_type' => 'reviews',
        'post_status' => 'publish',
        'cat' => '',
        'posts_per_page' => 8,
        'orderby' => 'post_date',
        'order' => 'desc',
    );
    $loop = new WP_Query($args);
    $total = $loop->found_posts;
    $iteration = 0;
    $i = 0;

?>
    <div class='container'>
        <div class=' reviews-owl  owl-carousel'>
            <?php
            while ($loop->have_posts()) : $loop->the_post();
                $iteration++; {
            ?>
                    <div class="item">
                        <div class="review-item">
                            <div class="review-thumb">
                                <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'large') ?>" alt="<?php echo get_the_title() ?>" />
                            </div>
                            <div class="review-content">
                                <div class="review-title">
                                    <?php echo get_the_title() ?>
                                </div>
                                <div class="review-desc">
                                    <?php echo get_post_meta(get_the_ID(), 'Reviews_Description', true); ?>
                                </div>
                            </div>
                        </div>
                    </div>

            <?php
                }
            endwhile;
            ?>
        </div>
    </div>

<?php


    wp_reset_query();
    return ob_get_clean();
}
add_shortcode('GetReviews_Fn', 'GetReviews');


function getProductCategory()
{
    ob_start();
    $args = array(
        'taxonomy'   => "product_cat",
        'orderby'    => 'name',
        'order'      => 'asc',
        'hide_empty'   => false,
        'meta_key' => 'CategoryPro_Home',
        'meta_value' => '1'
    );
?>
    <div class="row">
        <?php
        $product_categories = get_terms($args);
        $i = 0;
        foreach ($product_categories as $key => $category) {

        ?>
            <div class="col medium-4 small-12 large-4">
                <div class="homeTourCategory">
                    <div class="homeTourCategory-img">
                        <a href="<?php echo get_category_link($category->term_id); ?>" title="<?php echo $category->name ?>">
                            <?php
                            $thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);
                            // get the medium-sized image url
                            $image = wp_get_attachment_image_src($thumbnail_id, 'medium');
                            ?>
                            <img src="<?php echo $image[0]; ?>" title="<?php echo $category->name ?>" />
                        </a>
                    </div>
                    <div class="homeTourCategory-name">
                        <a href="<?php echo get_category_link($category->term_id); ?>" title="<?php echo $category->name ?>">
                            <?php echo $category->name ?>

                        </a>
                    </div>
                </div>
            </div>
        <?php
            $i++;
        }
        ?>
    </div>
<?php

    wp_reset_query();
    return ob_get_clean();
}
add_shortcode('getProductCategory_Fn', 'getProductCategory');




function GetProHot($code, $arg)
{
    ob_start();
    $args = array(
        'post_type' => 'product',
        'post_status' => 'publish',
        'cat' => '',
        'posts_per_page' => 8,
        'orderby' => 'post_date',
        'order' => 'desc',
        'meta_query' => array(
            array(
                'key' => 'Pro_L1',
                'value' => '1'
            )
        )
    );
    $loop = new WP_Query($args);
    $total = $loop->found_posts;
    $iteration = 0;
    $i = 0;

?>

    <div class="row">


        <?php
        while ($loop->have_posts()) : $loop->the_post();
            $iteration++; {
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
                                <div class="homeProTitle" style = "height: 102px;vertical-align: middle;display: table-cell;">
                                    <a style="display: block; word-wrap: normal;" href="<?php echo get_the_permalink(); ?>" title="<?php echo get_the_title(); ?>">
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
                                                    echo "Departure:";
                                                }
                                                ?>
                                            </strong><?php echo get_post_meta(get_the_ID(), 'Pro_F2', true); ?></div>
                                        <!-- <div class="prox--custoe prox-hotel"><strong>
                                                <?php
                                                // if ($current_language == 'vi') {
                                                //     echo "Thời gian khởi hành:";
                                                // }
                                                // if ($current_language == 'en') {
                                                //     echo "Departure time:";
                                                // }
                                                ?> </strong><?php //echo get_post_meta(get_the_ID(), 'Pro_F3', true); ?></div> -->
                                        <div class="prox--custoe prox-tourin"><strong>
                                                <?php
                                                if ($current_language == 'vi') {
                                                    echo "Hình thức:";
                                                }
                                                if ($current_language == 'en') {
                                                    echo "Form:";
                                                }
                                                ?> </strong><?php echo get_post_meta(get_the_ID(), 'Pro_F4', true); ?></div>
                                        
                                        <div class="prox--custoe prox-timego"><strong>
													<?php
													if ($current_language == 'vi') {
														echo "Điểm đến:";
													}
													if ($current_language == 'en') {
														echo "Destination:";
													}
													?> </strong><?php echo get_post_meta(get_the_ID(), 'Cruises_Destination', true); ?></div>
                                        <!-- <div class="prox--custoe prox-confi"><strong><?php
                                                // if ($current_language == 'vi') {
                                                //     echo "Khách sạn:";
                                                // }
                                                // if ($current_language == 'en') {
                                                //     echo "Hotel:";
                                                // }
                                                ?> </strong><?php //echo get_post_meta(get_the_ID(), 'Pro_F5', true); ?></div> -->
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
            }
        endwhile;
        ?>
    </div>



<?php


    wp_reset_query();
    return ob_get_clean();
}
add_shortcode('GetProHot_Fn', 'GetProHot');




function GetPrice($newprice, $oldprice)
{
    $str = "";


    if ($oldprice != $newprice && $oldprice != 0) {

        $str .= "<div class='new'><strong>Giá: </strong>" . wc_price($oldprice) . "</div>";
    } else if ($newprice != 0 && $newprice != null) {
        $str .= "<div class='new'><strong>Giá: </strong>" . wc_price($newprice) . "</div>";
    } else {
        $str .= "<div class='contact'>" . do_shortcode('[block id="hotline-lien-he"]') . "</div>";
    }

    return $str;
}

function GetSale($newprice, $oldprice)
{
    $str = "";


    if ($oldprice != $newprice && $oldprice != 0) {

        $str .= "<div class='posSale'>-" . round((($oldprice - $newprice) / $oldprice) * 100) . "%</div>";
    }

    return $str;
}

add_action('wp_head', 'my_head_css');
function my_head_css()
{
    echo "<link rel='stylesheet'  href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.css' type='text/css' media='all' />";
    echo "<link rel='stylesheet'  href='https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.css' type='text/css' media='all' />";

    echo "<script  src='https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js' ></script>";
    wp_register_style('owl2', get_stylesheet_directory_uri() . '/css/dangky.css', 'all');
    wp_enqueue_style('owl2');
    wp_register_script('scriptadd1', get_stylesheet_directory_uri() . '/js/dangky.js', '', '1.1', true);
    wp_enqueue_script('scriptadd1');
    echo "<link rel='stylesheet'  href='https://cdn.jsdelivr.net/jquery.slick/1.6.0/slick.css' type='text/css' media='all' />";
    echo "<script  src='https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js' ></script>";
    echo "<script  src='" . get_stylesheet_directory_uri() . "/js/bootstrap.min.js' ></script>";
    // echo "<script  src='https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js' ></script>";
}


//Search
function cf_search_join($join)
{
    global $wpdb;

    if (is_search()) {
        $join .= ' LEFT JOIN ' . $wpdb->postmeta . ' ON ' . $wpdb->posts . '.ID = ' . $wpdb->postmeta . '.post_id ';
    }

    return $join;
}
add_filter('posts_join', 'cf_search_join');

function cf_search_where($where)
{
    global $pagenow, $wpdb;

    if (is_search()) {
        $where = preg_replace(
            "/\(\s*" . $wpdb->posts . ".post_title\s+LIKE\s*(\'[^\']+\')\s*\)/",
            "(" . $wpdb->posts . ".post_title LIKE $1) OR (" . $wpdb->postmeta . ".meta_value LIKE $1)",
            $where
        );
    }

    return $where;
}
add_filter('posts_where', 'cf_search_where');

function cf_search_distinct($where)
{
    global $wpdb;

    if (is_search()) {
        return "DISTINCT";
    }

    return $where;
}
add_filter('posts_distinct', 'cf_search_distinct');







function custom_breadcrumbnew()
{
    // Set variables for later use
    global $sitepress;
    $here_text        = __('');
    $home_link        = home_url('/');
    $home_text        = __('<i class="fa fa-home" aria-hidden="true"></i> Trang chủ');


    $link_before      = '';
    $link_after       = '';
    $link_attr        = '';
    $link             = $link_before . '<a' . $link_attr . ' href="%1$s">%2$s</a>' . $link_after;
    $delimiter        = ' / ';              // Delimiter between crumbs
    $before           = '<span class="current">'; // Tag before the current crumb
    $after            = '</span>';                // Tag after the current crumb
    $page_addon       = '';                       // Adds the page number if the query is paged
    $breadcrumb_trail = '';
    $category_links   = '';

    /** 
     * Set our own $wp_the_query variable. Do not use the global variable version due to 
     * reliability
     */
    $wp_the_query   = $GLOBALS['wp_the_query'];
    $queried_object = $wp_the_query->get_queried_object();

    // Handle single post requests which includes single pages, posts and attatchments
    if (is_singular()) {
        /** 
         * Set our own $post variable. Do not use the global variable version due to 
         * reliability. We will set $post_object variable to $GLOBALS['wp_the_query']
         */
        $post_object = sanitize_post($queried_object);

        // Set variables 
        $title          = apply_filters('the_title', $post_object->post_title);
        $parent         = $post_object->post_parent;
        $post_type      = $post_object->post_type;
        $post_id        = $post_object->ID;
        $post_link      = $before . $title . $after;
        $parent_string  = '';
        $post_type_link = '';

        if ('post' === $post_type) {
            // Get the post categories
            $categories = get_the_category($post_id);
            if ($categories) {
                // Lets grab the first category
                $category  = $categories[0];

                $category_links = get_category_parents($category, true, $delimiter);
                $category_links = str_replace('<a',   $link_before . '<a' . $link_attr, $category_links);
                $category_links = str_replace('</a>', '</a>' . $link_after,             $category_links);
            }
        }

        if (!in_array($post_type, ['post', 'page', 'attachment'])) {
            $post_type_object = get_post_type_object($post_type);
            $archive_link     = esc_url(get_post_type_archive_link($post_type));

            $terms = get_the_terms($post->ID, 'category');
            if (!empty($terms)) {
                // get the first term
                $term = array_shift($terms);

                $term_object        = get_term($term->term_id);

                $taxonomy           = $term_object->taxonomy;
                $term_id            = $term_object->term_id;
                $term_name          = $term_object->name;

                $term_parent        = $term_object->parent;
                $taxonomy_object    = get_taxonomy($taxonomy);
                $current_term_link  = $before  . '<a href=' . get_category_link($term_id) . '>' . $term_name . '</a>' . $after;
                $parent_term_string = '';


                if (0 !== $term_parent) {
                    // Get all the current term ancestors
                    $parent_term_links = [];
                    while ($term_parent) {
                        $term = get_term($term_parent, $taxonomy);

                        $parent_term_links[] = sprintf($link, esc_url(get_term_link($term)), $term->name);

                        $term_parent = $term->parent;
                    }

                    $parent_term_links  = array_reverse($parent_term_links);
                    $parent_term_string = implode($delimiter, $parent_term_links);
                }
                if ($parent_term_string) {
                    $post_type_link = $parent_term_string . $delimiter . $current_term_link;
                } else {
                    $post_type_link = $current_term_link;
                }


                // $post_type_link = '<a href='.get_category_link($term->term_id).'>'.$term->name.'</a>';
            } else {

                $post_type_link   = sprintf($link, $archive_link, $post_type_object->labels->singular_name);
            }
        }

        // Get post parents if $parent !== 0
        if (0 !== $parent) {
            $parent_links = [];
            while ($parent) {
                $post_parent = get_post($parent);

                $parent_links[] = sprintf($link, esc_url(get_permalink($post_parent->ID)), get_the_title($post_parent->ID));

                $parent = $post_parent->post_parent;
            }

            $parent_links = array_reverse($parent_links);

            $parent_string = implode($delimiter, $parent_links);
        }

        // Lets build the breadcrumb trail
        if ($parent_string) {
            $breadcrumb_trail = $parent_string . $delimiter . $post_link;
        } else {
            $breadcrumb_trail = $post_link;
        }

        if ($post_type_link)
            $breadcrumb_trail = $post_type_link . $delimiter . $breadcrumb_trail;

        if ($category_links)
            $breadcrumb_trail = $category_links . $breadcrumb_trail;
    }

    // Handle archives which includes category-, tag-, taxonomy-, date-, custom post type archives and author archives
    if (is_archive()) {
        if (
            is_category()
            || is_tag()
            || is_tax()
        ) {
            // Set the variables for this section
            $term_object        = get_term($queried_object);
            $taxonomy           = $term_object->taxonomy;
            $term_id            = $term_object->term_id;
            $term_name          = $term_object->name;
            $term_parent        = $term_object->parent;
            $taxonomy_object    = get_taxonomy($taxonomy);
            $current_term_link  = $before  . $term_name . $after;
            $parent_term_string = '';

            if (0 !== $term_parent) {
                // Get all the current term ancestors
                $parent_term_links = [];
                while ($term_parent) {
                    $term = get_term($term_parent, $taxonomy);

                    $parent_term_links[] = sprintf($link, esc_url(get_term_link($term)), $term->name);

                    $term_parent = $term->parent;
                }

                $parent_term_links  = array_reverse($parent_term_links);
                $parent_term_string = implode($delimiter, $parent_term_links);
            }

            if ($parent_term_string) {
                $breadcrumb_trail = $parent_term_string . $delimiter . $current_term_link;
            } else {
                $breadcrumb_trail = $current_term_link;
            }
        } elseif (is_author()) {
        } elseif (is_date()) {
            // Set default variables


        } elseif (is_post_type_archive()) {

            $post_type        = $wp_the_query->query_vars['post_type'];
            $post_type_object = get_post_type_object($post_type);

            $breadcrumb_trail = $before . $post_type_object->labels->singular_name . $after;
        }
    }

    // Handle the search page


    // Handle paged pages
    if (is_paged()) {
        $current_page = get_query_var('paged') ? get_query_var('paged') : get_query_var('page');
        $page_addon   = $before . sprintf(__(' ( Page %s )'), number_format_i18n($current_page)) . $after;
    }

    $breadcrumb_output_link  = '';
    $breadcrumb_output_link .= '<div class="breadcrumb">';
    if (
        is_home()
        || is_front_page()
    ) {
        // Do not show breadcrumbs on page one of home and frontpage
        if (is_paged()) {
            $breadcrumb_output_link .= $here_text;
            $breadcrumb_output_link .= '<a href="' . $home_link . '">' . $home_text . '</a>';
            $breadcrumb_output_link .= $page_addon;
        }
    } else {
        $breadcrumb_output_link .= $here_text;
        $breadcrumb_output_link .= '<a href="' . $home_link . '" rel="v:url" property="v:title">' . $home_text . '</a>';
        $breadcrumb_output_link .= $delimiter;
        $breadcrumb_output_link .= $breadcrumb_trail;
        $breadcrumb_output_link .= $page_addon;
    }
    $breadcrumb_output_link .= '</div><!-- .breadcrumbs -->';

    return $breadcrumb_output_link;
}


// Loadlink sản phẩm
function custom_breadcrumbproduct()
{
    // Set variables for later use
    global $sitepress;
    $here_text        = __('');
    $home_link        = home_url('/');
    $home_text        = __('Trang chủ');

    $current_language = $sitepress->get_current_language();
    if ($current_language == 'vi') {
        $home_text        = __('Trang chủ');
    }

    if ($current_language == 'en') {
        $home_text        = __('Home');
    }

    $link_before      = '';
    $link_after       = '';
    $link_attr        = '';
    $link             = $link_before . '<a' . $link_attr . ' href="%1$s">%2$s</a>' . $link_after;
    $delimiter        = ' » ';              // Delimiter between crumbs
    $before           = '<span class="current">'; // Tag before the current crumb
    $after            = '</span>';                // Tag after the current crumb
    $page_addon       = '';                       // Adds the page number if the query is paged
    $breadcrumb_trail = '';
    $category_links   = '';

    /** 
     * Set our own $wp_the_query variable. Do not use the global variable version due to 
     * reliability
     */
    $wp_the_query   = $GLOBALS['wp_the_query'];
    $queried_object = $wp_the_query->get_queried_object();

    // Handle single post requests which includes single pages, posts and attatchments
    if (is_singular()) {
        /** 
         * Set our own $post variable. Do not use the global variable version due to 
         * reliability. We will set $post_object variable to $GLOBALS['wp_the_query']
         */
        $post_object = sanitize_post($queried_object);

        // Set variables 
        $title          = apply_filters('the_title', $post_object->post_title);
        $parent         = $post_object->post_parent;
        $post_type      = $post_object->post_type;
        $post_id        = $post_object->ID;
        $post_link      = $before . $title . $after;
        $parent_string  = '';
        $post_type_link = '';

        if ('post' === $post_type) {
            // Get the post categories
            $categories = get_the_category($post_id);
            if ($categories) {
                // Lets grab the first category
                $category  = $categories[0];

                $category_links = get_category_parents($category, true, $delimiter);
                $category_links = str_replace('<a',   $link_before . '<a' . $link_attr, $category_links);
                $category_links = str_replace('</a>', '</a>' . $link_after,             $category_links);
            }
        }

        if (!in_array($post_type, ['post', 'page', 'attachment'])) {
            $post_type_object = get_post_type_object($post_type);
            $archive_link     = esc_url(get_post_type_archive_link($post_type));

            $terms = get_the_terms($post->ID, 'product_cat');
            if (!empty($terms)) {
                // get the first term
                $term = array_shift($terms);

                $term_object        = get_term($term->term_id);

                $taxonomy           = $term_object->taxonomy;
                $term_id            = $term_object->term_id;
                $term_name          = $term_object->name;

                $term_parent        = $term_object->parent;
                $taxonomy_object    = get_taxonomy($taxonomy);
                $current_term_link  = $before  . '<a href=' . get_category_link($term_id) . '>' . $term_name . '</a>' . $after;
                $parent_term_string = '';


                if (0 !== $term_parent) {
                    // Get all the current term ancestors
                    $parent_term_links = [];
                    while ($term_parent) {
                        $term = get_term($term_parent, $taxonomy);

                        $parent_term_links[] = sprintf($link, esc_url(get_term_link($term)), $term->name);

                        $term_parent = $term->parent;
                    }

                    $parent_term_links  = array_reverse($parent_term_links);
                    $parent_term_string = implode($delimiter, $parent_term_links);
                }
                if ($parent_term_string) {
                    $post_type_link = $parent_term_string . $delimiter . $current_term_link;
                } else {
                    $post_type_link = $current_term_link;
                }


                // $post_type_link = '<a href='.get_category_link($term->term_id).'>'.$term->name.'</a>';
            } else {

                $post_type_link   = sprintf($link, $archive_link, $post_type_object->labels->singular_name);
            }
        }

        // Get post parents if $parent !== 0
        if (0 !== $parent) {
            $parent_links = [];
            while ($parent) {
                $post_parent = get_post($parent);

                $parent_links[] = sprintf($link, esc_url(get_permalink($post_parent->ID)), get_the_title($post_parent->ID));

                $parent = $post_parent->post_parent;
            }

            $parent_links = array_reverse($parent_links);

            $parent_string = implode($delimiter, $parent_links);
        }

        // Lets build the breadcrumb trail
        if ($parent_string) {
            $breadcrumb_trail = $parent_string . $delimiter . $post_link;
        } else {
            $breadcrumb_trail = $post_link;
        }

        if ($post_type_link)
            $breadcrumb_trail = $post_type_link . $delimiter . $breadcrumb_trail;

        if ($category_links)
            $breadcrumb_trail = $category_links . $breadcrumb_trail;
    }

    // Handle archives which includes category-, tag-, taxonomy-, date-, custom post type archives and author archives
    if (is_archive()) {
        if (
            is_category()
            || is_tag()
            || is_tax()
        ) {
            // Set the variables for this section
            $term_object        = get_term($queried_object);
            $taxonomy           = $term_object->taxonomy;
            $term_id            = $term_object->term_id;
            $term_name          = $term_object->name;
            $term_parent        = $term_object->parent;
            $taxonomy_object    = get_taxonomy($taxonomy);
            $current_term_link  = $before  . $term_name . $after;
            $parent_term_string = '';

            if (0 !== $term_parent) {
                // Get all the current term ancestors
                $parent_term_links = [];
                while ($term_parent) {
                    $term = get_term($term_parent, $taxonomy);

                    $parent_term_links[] = sprintf($link, esc_url(get_term_link($term)), $term->name);

                    $term_parent = $term->parent;
                }

                $parent_term_links  = array_reverse($parent_term_links);
                $parent_term_string = implode($delimiter, $parent_term_links);
            }

            if ($parent_term_string) {
                $breadcrumb_trail = $parent_term_string . $delimiter . $current_term_link;
            } else {
                $breadcrumb_trail = $current_term_link;
            }
        } elseif (is_author()) {
        } elseif (is_date()) {
            // Set default variables


        } elseif (is_post_type_archive()) {

            $post_type        = $wp_the_query->query_vars['post_type'];
            $post_type_object = get_post_type_object($post_type);

            $breadcrumb_trail = $before . $post_type_object->labels->singular_name . $after;
        }
    }

    // Handle the search page


    // Handle paged pages
    if (is_paged()) {
        $current_page = get_query_var('paged') ? get_query_var('paged') : get_query_var('page');
        $page_addon   = $before . sprintf(__(' ( Page %s )'), number_format_i18n($current_page)) . $after;
    }

    $breadcrumb_output_link  = '';
    $breadcrumb_output_link .= '<div class="breadcrumb">';
    if (
        is_home()
        || is_front_page()
    ) {
        // Do not show breadcrumbs on page one of home and frontpage
        if (is_paged()) {
            $breadcrumb_output_link .= $here_text;
            $breadcrumb_output_link .= '<a href="' . $home_link . '">' . $home_text . '</a>';
            $breadcrumb_output_link .= $page_addon;
        }
    } else {
        $breadcrumb_output_link .= $here_text;
        $breadcrumb_output_link .= '<a href="' . $home_link . '" rel="v:url" property="v:title">' . $home_text . '</a>';
        $breadcrumb_output_link .= $delimiter;
        $breadcrumb_output_link .= $breadcrumb_trail;
        $breadcrumb_output_link .= $page_addon;
    }
    $breadcrumb_output_link .= '</div><!-- .breadcrumbs -->';

    return $breadcrumb_output_link;
}








//Function Update Luan


function GetAsideBlogHot($code, $arg)
{
    ob_start();
    $args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'cat' => '',
        'posts_per_page' => 8,
        'orderby' => 'post_date',
        'order' => 'desc',
        'meta_query' => array(
            array(
                'key' => 'Post_Hot', //newshot là mã custom field tin tức
                'value' => '1'
            )
        )
    );
    $loop = new WP_Query($args);
    $total = $loop->found_posts;
    $iteration = 0;
    $i = 0;

?>
    <ul>
        <?php
        while ($loop->have_posts()) : $loop->the_post();
            $iteration++; {
        ?>

                <li>
                    <a href="<?php echo get_the_permalink(); ?>" title="<?php echo get_the_title() ?>">
                        <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'large') ?>" alt="<?php echo get_the_title() ?>" />
                    </a>
                    <p class="las-wbloglist__title">
                        <a href="<?php echo get_the_permalink(); ?>" title="<?php echo get_the_title() ?>">
                            <?php echo get_the_title() ?>
                        </a>
                    </p>
                </li>

        <?php
            }
        endwhile;
        ?>
    </ul>

<?php
    wp_reset_query();
    return ob_get_clean();
}
add_shortcode('GetAsideBlogHot_Fn', 'GetAsideBlogHot');



function GetAsideBlogNew($code, $arg)
{
    ob_start();
    $args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'cat' => '',
        'posts_per_page' => 8,
        'orderby' => 'post_date',
        'order' => 'desc',

    );
    $loop = new WP_Query($args);
    $total = $loop->found_posts;
    $iteration = 0;
    $i = 0;

?>
    <ul>
        <?php
        while ($loop->have_posts()) : $loop->the_post();
            $iteration++; {
        ?>
                <li>
                    <a href="<?php echo get_the_permalink(); ?>" title="<?php echo get_the_title() ?>">
                        <?php echo get_the_title() ?>
                    </a>
                </li>


        <?php
            }
        endwhile;
        ?>
    </ul>

<?php
    wp_reset_query();
    return ob_get_clean();
}
add_shortcode('GetAsideBlogNew_Fn', 'GetAsideBlogNew');


//




function shapeSpace_display_search_form()
{
    ob_start();
?>
    <form role="search" method="get" class="woocommerce-product-search" action="<?php echo esc_url(home_url('/')); ?>">
        <form role="search" method="get" class="woocommerce-product-search" action="<?php echo esc_url(home_url('/')); ?>">
            <div id="st-content-wrapper" class="search-result-page search-result-page--custom">
                <div class="container">
                    <div class="row">
                        <div class="wpb_column column_container col medium-12">
                            <div class="vc_column-inner wpb_wrapper">
                                <div class="search-form-wrapper auto-height-form-search normal mix st-search-form-st_tours st-search-form-st_rental">
                                    <ul class="nav nav-tabs" role="tablist">
                                        <li role="st_tours" class="active"><a href="#st_tours" aria-controls="st_tours" role="tab" data-toggle="tab">Tours</a></li>
                                        <li role="st_rental" class="hidden"><a href="#st_rental" aria-controls="st_rental" role="tab" data-toggle="tab">Cruises</a></li>
                                    </ul>
                                    <div class="tab-content">
                                        <div role="tabpanel" class="tab-pane active" id="st_tours">
                                            <div class="row">
                                                <div class="col large-12 tour-search-form-home">
                                                    <div class="search-form">
                                                        <div class="form">
                                                            <div class="row rd-flex">
                                                                <div class="col medium-4 border-right">
                                                                    <div class="form-group form-extra-field dropdown clearfix field-detination has-icon">
                                                                        <i class="input-icon st-border-radius field-icon fa fa-date">
                                                                            <svg width="40px" height="40px" viewBox="0 0 17 24" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                                                                <!-- Generator: Sketch 49 (51002) - http://www.bohemiancoding.com/sketch -->
                                                                                <defs></defs>
                                                                                <g id="Hotel-layout" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" stroke-linecap="round" stroke-linejoin="round">
                                                                                    <g id="Search_Result_1_Grid" transform="translate(-165.000000, -328.000000)" stroke="#A0A9B2">
                                                                                        <g id="form_search_hotel_row" transform="translate(135.000000, 290.000000)">
                                                                                            <g id="input" transform="translate(30.000000, 0.000000)">
                                                                                                <g id="where" transform="translate(0.000000, 26.000000)">
                                                                                                    <g id="Group" transform="translate(0.000000, 12.000000)">
                                                                                                        <g id="ico_maps_search_box">
                                                                                                            <path d="M15.75,8.25 C15.75,12.471 12.817,14.899 10.619,17.25 C9.303,18.658 8.25,23.25 8.25,23.25 C8.25,23.25 7.2,18.661 5.887,17.257 C3.687,14.907 0.75,12.475 0.75,8.25 C0.75,4.10786438 4.10786438,0.75 8.25,0.75 C12.3921356,0.75 15.75,4.10786438 15.75,8.25 Z" id="Shape"></path>
                                                                                                            <circle id="Oval" cx="8.25" cy="8.25" r="3"></circle>
                                                                                                        </g>
                                                                                                    </g>
                                                                                                </g>
                                                                                            </g>
                                                                                        </g>
                                                                                    </g>
                                                                                </g>
                                                                            </svg>
                                                                        </i>
                                                                        <div class="dropdown" data-toggle="dropdown" id="dropdown-destination" aria-expanded="false">
                                                                            <label>
                                                                                <?php

                                                                                global $sitepress;
                                                                                $current_language = $sitepress->get_current_language();
                                                                                if ($current_language == 'vi') {
                                                                                    echo "Điểm đến";
                                                                                }

                                                                                if ($current_language == 'en') {
                                                                                    echo "Destination";
                                                                                }

                                                                                ?>


                                                                            </label>
                                                                            <div class="render">
                                                                                <input type="text" touchend="stKeyupsmartSearch(this)" autocomplete="off" onkeyup="stKeyupsmartSearch(this)" id="st_location_name_tour" name="location_name" value="" placeholder=" <?php

                                                                                                                                                                                                                                                                    global $sitepress;
                                                                                                                                                                                                                                                                    $current_language = $sitepress->get_current_language();
                                                                                                                                                                                                                                                                    if ($current_language == 'vi') {
                                                                                                                                                                                                                                                                        echo "Bạn muốn đi đâu?";
                                                                                                                                                                                                                                                                    }

                                                                                                                                                                                                                                                                    if ($current_language == 'en') {
                                                                                                                                                                                                                                                                        echo "Where are you going?";
                                                                                                                                                                                                                                                                    }

                                                                                                                                                                                                                                                                    ?>">
                                                                            </div>
                                                                            <input type="hidden" id="location_id" name="location_id" value="">
                                                                        </div>
                                                                        <ul class="dropdown-menu" aria-labelledby="dropdown-destination">
                                                                            <?php

                                                                            $args = array(
                                                                                'number'     => '999',
                                                                                'orderby'    => 'name',
                                                                                'hide_empty' => true,
                                                                                'meta_key' => 'cate_pr',
                                                                                'meta_value' => 'Tour'
                                                                            );
                                                                            $product_categories = get_terms('product_cat', $args);
                                                                            $selected_category  = isset($_REQUEST['product_cat']) ? $_REQUEST['product_cat'] : '';
                                                                            foreach ($product_categories as $value) {

                                                                            ?>
                                                                                <li class="item" data-value="<?php echo  $value->term_id ?>">
                                                                                    <i class="input-icon st-border-radius field-icon fa">
                                                                                        <svg width="25px" height="25px" viewBox="0 0 17 24" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                                                                            <!-- Generator: Sketch 49 (51002) - http://www.bohemiancoding.com/sketch -->
                                                                                            <defs></defs>
                                                                                            <g id="Hotel-layout" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" stroke-linecap="round" stroke-linejoin="round">
                                                                                                <g id="Search_Result_1_Grid" transform="translate(-165.000000, -328.000000)" stroke="#A0A9B2">
                                                                                                    <g id="form_search_hotel_row" transform="translate(135.000000, 290.000000)">
                                                                                                        <g id="input" transform="translate(30.000000, 0.000000)">
                                                                                                            <g id="where" transform="translate(0.000000, 26.000000)">
                                                                                                                <g id="Group" transform="translate(0.000000, 12.000000)">
                                                                                                                    <g id="ico_maps_search_box">
                                                                                                                        <path d="M15.75,8.25 C15.75,12.471 12.817,14.899 10.619,17.25 C9.303,18.658 8.25,23.25 8.25,23.25 C8.25,23.25 7.2,18.661 5.887,17.257 C3.687,14.907 0.75,12.475 0.75,8.25 C0.75,4.10786438 4.10786438,0.75 8.25,0.75 C12.3921356,0.75 15.75,4.10786438 15.75,8.25 Z" id="Shape"></path>
                                                                                                                        <circle id="Oval" cx="8.25" cy="8.25" r="3"></circle>
                                                                                                                    </g>
                                                                                                                </g>
                                                                                                            </g>
                                                                                                        </g>
                                                                                                    </g>
                                                                                                </g>
                                                                                            </g>
                                                                                        </svg>
                                                                                    </i>
                                                                                    <span><?php echo $value->name ?></span>
                                                                                </li>
                                                                            <?php


                                                                            }

                                                                            ?>


                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                                <div class="col medium-4 border-right">
                                                                    <div class="form-group form-date-field  field-durations  clearfix  has-icon" data-format="DD/MM/YYYY">
                                                                        <i class="input-icon st-border-radius field-icon fa">
                                                                            <svg height="40px" width="40px" viewBox="0 0 24 25" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                                                                <!-- Generator: Sketch 49 (51002) - http://www.bohemiancoding.com/sketch -->
                                                                                <defs></defs>
                                                                                <g id="Hotel-layout" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" stroke-linecap="round" stroke-linejoin="round">
                                                                                    <g id="Search_Result_1_Grid" transform="translate(-436.000000, -328.000000)" stroke="#A0A9B2">
                                                                                        <g id="form_search_hotel_row" transform="translate(135.000000, 290.000000)">
                                                                                            <g id="input" transform="translate(30.000000, 0.000000)">
                                                                                                <g id="check-in" transform="translate(270.000000, 26.000000)">
                                                                                                    <g id="ico_calendar_search_box" transform="translate(1.000000, 12.000000)">
                                                                                                        <g id="calendar-add-1">
                                                                                                            <path d="M9.5,18.5 L1.5,18.5 C0.94771525,18.5 0.5,18.0522847 0.5,17.5 L0.5,3.5 C0.5,2.94771525 0.94771525,2.5 1.5,2.5 L19.5,2.5 C20.0522847,2.5 20.5,2.94771525 20.5,3.5 L20.5,10" id="Shape"></path>
                                                                                                            <path d="M5.5,0.501 L5.5,5.501" id="Shape"></path>
                                                                                                            <path d="M15.5,0.501 L15.5,5.501" id="Shape"></path>
                                                                                                            <path d="M0.5,7.501 L20.5,7.501" id="Shape"></path>
                                                                                                            <circle id="Oval" cx="17.5" cy="17.501" r="6"></circle>
                                                                                                            <path d="M17.5,14.501 L17.5,20.501" id="Shape"></path>
                                                                                                            <path d="M20.5,17.501 L14.5,17.501" id="Shape"></path>
                                                                                                        </g>
                                                                                                    </g>
                                                                                                </g>
                                                                                            </g>
                                                                                        </g>
                                                                                    </g>
                                                                                </g>
                                                                            </svg>
                                                                        </i>
                                                                        <div class="date-wrapper clearfix" data-toggle="dropdown" id="dropdown-durations">
                                                                            <div class="check-in-wrapper">
                                                                                <label>
                                                                                    <?php

                                                                                    global $sitepress;
                                                                                    $current_language = $sitepress->get_current_language();
                                                                                    if ($current_language == 'vi') {
                                                                                        echo "Thời gian";
                                                                                    }

                                                                                    if ($current_language == 'en') {
                                                                                        echo "From - To";
                                                                                    }

                                                                                    ?>
                                                                                </label>
                                                                                <div class="render durations">dd/mm/yyyy</div>
                                                                                <input type="hidden" id="data_taxonomy" class="data_taxonomy" name="time" value="">
                                                                            </div>
                                                                        </div>
                                                                        <ul class="dropdown-menu" aria-labelledby="dropdown-durations">
                                                                            <?php
                                                                            $field_key = "Pro_F1";
                                                                            $field = acf_get_field($field_key);
                                                                            ?>

                                                                            <?php
                                                                            foreach ($field['choices']  as $value => $label) {
                                                                            ?>

                                                                                <li class="item" data-value="<?php echo $value; ?>">
                                                                                    <span><?php echo $label; ?></span>
                                                                                </li>
                                                                            <?php
                                                                            }
                                                                            ?>

                                                                            <?php

                                                                            ?>

                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                                <div class="col medium-4 no-pad-left">
                                                                    <div class="form-button">
                                                                        <input type="hidden" name="post_type" value="product" />
                                                                        <button value="<?php echo esc_attr_x('Search', 'submit button', 'woocommerce'); ?>" class="btn btn-primary button btn-search">Search</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </form>


    </form>
<?php
    return ob_get_clean();
}
add_shortcode('display_search_form', 'shapeSpace_display_search_form');






function custom_breadcrumbsgg($custoxomaxy)
{
    // Set variables for later use
    global $post, $wp_query;
    $here_text        = __('');
    $home_link        = home_url('/');
    global $sitepress;
    global $sitepress;
    $current_language = $sitepress->get_current_language();
    $home_text        = __('Trang chủ');
    if ($current_language == 'vi') {
        $home_text        = __('Trang chủ');
    }
    if ($current_language == 'en') {
        $home_text        = __('Home');
    }



    $link_before      = '';
    $link_after       = '';
    $link_attr        = '';
    $link             = $link_before . '<a' . $link_attr . ' href="%1$s">%2$s</a>' . $link_after;
    $delimiter        = ' &raquo; ';              // Delimiter between crumbs
    $before           = '<span class="current">'; // Tag before the current crumb
    $after            = '</span>';                // Tag after the current crumb
    $page_addon       = '';                       // Adds the page number if the query is paged
    $breadcrumb_trail = '';
    $category_links   = '';

    /** 
     * Set our own $wp_the_query variable. Do not use the global variable version due to 
     * reliability
     */
    $wp_the_query   = $GLOBALS['wp_the_query'];
    $queried_object = $wp_the_query->get_queried_object();

    // Handle single post requests which includes single pages, posts and attatchments
    if (is_singular()) {
        /** 
         * Set our own $post variable. Do not use the global variable version due to 
         * reliability. We will set $post_object variable to $GLOBALS['wp_the_query']
         */
        $post_object = sanitize_post($queried_object);

        // Set variables 
        $title          = apply_filters('the_title', $post_object->post_title);
        $parent         = $post_object->post_parent;
        $post_type      = $post_object->post_type;
        $post_id        = $post_object->ID;
        $post_link      = $before . $title . $after;
        $parent_string  = '';
        $post_type_link = '';

        if ('post' === $post_type) {
            // Get the post categories
            $categories = get_the_category($post_id);
            if ($categories) {
                // Lets grab the first category
                $category  = $categories[0];

                $category_links = get_category_parents($category, true, $delimiter);
                $category_links = str_replace('<a',   $link_before . '<a' . $link_attr, $category_links);
                $category_links = str_replace('</a>', '</a>' . $link_after,             $category_links);
            }
        }

        if (!in_array($post_type, ['post', 'page', 'attachment'])) {
            $post_type_object = get_post_type_object($post_type);
            $archive_link     = esc_url(get_post_type_archive_link($post_type));

            $terms = get_the_terms($post->ID, $custoxomaxy);
            if (!empty($terms)) {
                // get the first term
                $term = array_shift($terms);

                $term_object        = get_term($term->term_id);

                $taxonomy           = $term_object->taxonomy;
                $term_id            = $term_object->term_id;
                $term_name          = $term_object->name;

                $term_parent        = $term_object->parent;
                $taxonomy_object    = get_taxonomy($taxonomy);
                $current_term_link  = $before  . '<a href=' . get_category_link($term_id) . '>' . $term_name . '</a>' . $after;
                $parent_term_string = '';


                if (0 !== $term_parent) {
                    // Get all the current term ancestors
                    $parent_term_links = [];
                    while ($term_parent) {
                        $term = get_term($term_parent, $taxonomy);

                        $parent_term_links[] = sprintf($link, esc_url(get_term_link($term)), $term->name);

                        $term_parent = $term->parent;
                    }

                    $parent_term_links  = array_reverse($parent_term_links);
                    $parent_term_string = implode($delimiter, $parent_term_links);
                }
                if ($parent_term_string) {
                    $post_type_link = $parent_term_string . $delimiter . $current_term_link;
                } else {
                    $post_type_link = $current_term_link;
                }


                // $post_type_link = '<a href='.get_category_link($term->term_id).'>'.$term->name.'</a>';
            } else {

                $post_type_link   = sprintf($link, $archive_link, $post_type_object->labels->singular_name);
            }
        }

        // Get post parents if $parent !== 0
        if (0 !== $parent) {
            $parent_links = [];
            while ($parent) {
                $post_parent = get_post($parent);

                $parent_links[] = sprintf($link, esc_url(get_permalink($post_parent->ID)), get_the_title($post_parent->ID));

                $parent = $post_parent->post_parent;
            }

            $parent_links = array_reverse($parent_links);

            $parent_string = implode($delimiter, $parent_links);
        }

        // Lets build the breadcrumb trail
        if ($parent_string) {
            $breadcrumb_trail = $parent_string . $delimiter . $post_link;
        } else {
            $breadcrumb_trail = $post_link;
        }

        if ($post_type_link)
            $breadcrumb_trail = $post_type_link . $delimiter . $breadcrumb_trail;

        if ($category_links)
            $breadcrumb_trail = $category_links . $breadcrumb_trail;
    }

    // Handle archives which includes category-, tag-, taxonomy-, date-, custom post type archives and author archives
    if (is_archive()) {
        if (
            is_category()
            || is_tag()
            || is_tax()
        ) {
            // Set the variables for this section
            $term_object        = get_term($queried_object);
            $taxonomy           = $term_object->taxonomy;
            $term_id            = $term_object->term_id;
            $term_name          = $term_object->name;
            $term_parent        = $term_object->parent;
            $taxonomy_object    = get_taxonomy($taxonomy);
            $current_term_link  = $before  . $term_name . $after;
            $parent_term_string = '';

            if (0 !== $term_parent) {
                // Get all the current term ancestors
                $parent_term_links = [];
                while ($term_parent) {
                    $term = get_term($term_parent, $taxonomy);

                    $parent_term_links[] = sprintf($link, esc_url(get_term_link($term)), $term->name);

                    $term_parent = $term->parent;
                }

                $parent_term_links  = array_reverse($parent_term_links);
                $parent_term_string = implode($delimiter, $parent_term_links);
            }

            if ($parent_term_string) {
                $breadcrumb_trail = $parent_term_string . $delimiter . $current_term_link;
            } else {
                $breadcrumb_trail = $current_term_link;
            }
        } elseif (is_author()) {
        } elseif (is_date()) {
            // Set default variables


        } elseif (is_post_type_archive()) {

            $post_type        = $wp_the_query->query_vars['post_type'];
            $post_type_object = get_post_type_object($post_type);

            $breadcrumb_trail = $before . $post_type_object->labels->singular_name . $after;
        }
    }

    // Handle the search page


    // Handle paged pages
    if (is_paged()) {
        $current_page = get_query_var('paged') ? get_query_var('paged') : get_query_var('page');
        $page_addon   = $before . sprintf(__(' ( Page %s )'), number_format_i18n($current_page)) . $after;
    }

    $breadcrumb_output_link  = '';
    $breadcrumb_output_link .= '<div class="">';
    if (
        is_home()
        || is_front_page()
    ) {
        // Do not show breadcrumbs on page one of home and frontpage
        if (is_paged()) {
            $breadcrumb_output_link .= $here_text;
            $breadcrumb_output_link .= '<a href="' . $home_link . '">' . $home_text . '</a>';
            $breadcrumb_output_link .= $page_addon;
        }
    } else {
        $breadcrumb_output_link .= $here_text;
        $breadcrumb_output_link .= '<a href="' . $home_link . '" rel="v:url" property="v:title">' . $home_text . '</a>';
        $breadcrumb_output_link .= $delimiter;
        $breadcrumb_output_link .= $breadcrumb_trail;
        $breadcrumb_output_link .= $page_addon;
    }
    $breadcrumb_output_link .= '</div><!-- .breadcrumbs -->';

    return $breadcrumb_output_link;
}
