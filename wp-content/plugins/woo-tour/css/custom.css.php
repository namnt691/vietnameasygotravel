<?php
$wt_main_color = get_option('wt_main_color');
$hex  = $wt_main_color = str_replace("#", "", $wt_main_color);
if(strlen($hex) == 3) {
  $r = hexdec(substr($hex,0,1).substr($hex,0,1));
  $g = hexdec(substr($hex,1,1).substr($hex,1,1));
  $b = hexdec(substr($hex,2,1).substr($hex,2,1));
} else {
  $r = hexdec(substr($hex,0,2));
  $g = hexdec(substr($hex,2,2));
  $b = hexdec(substr($hex,4,2));
}
$rgb = $r.','. $g.','.$b;

$wt_fontfamily = get_option('wt_fontfamily');
$main_font_family = explode(":", $wt_fontfamily);
$main_font_family = $main_font_family[0];

$wt_fontsize = get_option('wt_fontsize');

$wt_hfont = get_option('wt_hfont');

$h_font_family = explode(":", $wt_hfont);
$h_font_family = $h_font_family[0];

$wt_hfontsize = get_option('wt_hfontsize');
$wt_hfontsize = explode(";", $wt_hfontsize);
$wt_hfontsize_pls = isset($wt_hfontsize[1]) ? $wt_hfontsize[1] : '';
$wt_hfontsize = $wt_hfontsize[0];

$wt_main_purpose = get_option('wt_main_purpose');
if($wt_main_purpose!='meta'){
    if($wt_main_color!=''){?>
    	.widget.wt-latest-tours-widget .thumb.item-thumbnail .item-evprice,
    	.wt-table-lisst .wt-table th,
        .woocommerce ul.products li.product a.button,
        .exwt-btn.wt-button, .woocommerce div.product form.cart button.button, .woocommerce div.product form.cart div.quantity.buttons_added [type="button"], .woocommerce #wtmain-content .wt-main.layout-2 .tour-details .exwt-btn,
        .ex-loadmore .loadmore-grid,
        .wt-search-form #searchform #searchsubmit,
        .wt-search-form button[type="submit"]:hover, .wt-search-form button[type="submit"],
        .wt-grid-shortcode figure.ex-modern-blog .date,
        .wt-departure .picker table thead tr th,
        .wt-grid-shortcode.wt-grid-column-1 figure.ex-modern-blog .ex-social-share ul li a,
        .wt-grid-shortcode figure.ex-modern-blog .ex-social-share,
        .woocommerce-cart .wc-proceed-to-checkout a.checkout-button, .woocommerce #payment #place_order, .woocommerce-page #payment #place_order, .woocommerce #respond input#submit, .woocommerce a.button, .woocommerce button.button, .woocommerce input.button,
        .wt-quantity > input[type=button],
        .exwt-btn.we-button,
        .wt-ajax-pagination ul li .page-numbers.current,
        .wt-tax-content .loc-details span,
        .wt-search-modern > div.wt-srel.wt-find-bt,
        .wt-grid-shortcode.wt-grid-column-1.wt-tourlist .item-post-n > h3,
        .price .tbss-viewprice,
        .exwt-single-tour #wtmain-content .quantity input[type="button"], .exwt-single-tour #wtmain-content .exwt-button-scroll, .exwt-single-tour #wtmain-content form.cart button[type="submit"],
        .wt-disc-info .dc-title  > span:not(:empty){ background:#<?php echo esc_html($wt_main_color);?>}
        .woocommerce #wtmain-content h4.wemap-title a, .wt-infotable .wemap-details h4.wemap-title a,
        .woocommerce #wtmain-content .woo-tour-info a,
        .wt-table-lisst .wt-table td h3 a,
        .woocommerce #wtmain-content .wt-table-lisst .wt-table td h3 a,
        .wt-table-lisst .wt-table td.tb-viewdetails .tb-price,
        .woocommerce div.product .product_title,
        figure.ex-modern-blog h3,
        .woocommerce #reviews #comments h2,
        .woocommerce #reviews h3,
        .woocommerce #reviews span#reply-title,
        body.woocommerce-page #wtmain-content .related ul.products li.product h3,
        .wt-search-form span.loc-details h3,
        .wt-grid-shortcode.wt-grid-column-1.wt-tourlist .ex-modern-blog .wt-more-meta .wt-pr-la,
        .wt-ext-info .woo-tour-accompanied .tour-service span,
        .exwt-single-tour #wtmain-content .tbss-viewprice,
        .wt-table-lisst .wt-table td.tb-price, .wt-table-lisst .wt-table td span.amount{ color:#<?php echo esc_html($wt_main_color);?>}
        .wt-search-form button[type="submit"]:hover, .wt-search-form button[type="submit"],
        .woocommerce-page .woocommerce .myaccount_address, .woocommerce-page .woocommerce .address address, .woocommerce-page .woocommerce .myaccount_user,
        .wt-ext-info .exwt-btn.we-button.wt-getct,
        .wt-discount-sif .dc-title,
        .woocommerce div.product .woocommerce-tabs ul.tabs li.active,
        .woocommerce form.checkout_coupon, .woocommerce form.login, .woocommerce form.register, .woocommerce table.my_account_orders, .wt-table-lisst .wt-table{ border-color:#<?php echo esc_html($wt_main_color);?>}
        .wt-location-arr{background: rgba(<?php echo esc_attr($rgb);?>, .7);}
        .wt-grid-shortcode.wt-grid-column-1.wt-tourlist .item-post-n{ border-color:rgba(<?php echo esc_attr($rgb);?>, .7);}
        .wt-disc-info .dc-title  > span:empty{
        	border: 13px solid #<?php echo esc_html($wt_main_color);?>; border-right: 13px solid #fff; border-left: 0;
        }
    <?php
    }
    if($wt_fontfamily!=''){?>
        .woocommerce-page form .input-text::-webkit-input-placeholder{
            font-family: "<?php echo esc_html($main_font_family);?>", sans-serif;
        }
        .wt-search-form input.form-control::-webkit-input-placeholder{
            font-family: "<?php echo esc_html($main_font_family);?>", sans-serif;
        }
        .woocommerce-page form .input-text::-moz-placeholder{
            font-family: "<?php echo esc_html($main_font_family);?>", sans-serif;
        }
        .woocommerce-page form .input-text:-ms-input-placeholder{
            font-family: "<?php echo esc_html($main_font_family);?>", sans-serif;
        }
        .wt-search-form input.form-control:-ms-input-placeholder{
            font-family: "<?php echo esc_html($main_font_family);?>", sans-serif;
        }
        .woocommerce-page form .input-text:-moz-placeholder{
            font-family: "<?php echo esc_html($main_font_family);?>", sans-serif;
        }
        .wt-search-form input.form-control:-moz-placeholder{
            font-family: "<?php echo esc_html($main_font_family);?>", sans-serif;
        }
    	.wt-search-form input.form-control {
            font-family: "<?php echo esc_html($main_font_family);?>", sans-serif;
        }
        .we-search-container,
        .wt-latest-event,
        .wt-dropdown-select,
        .woocommerce form .form-row select,
        .woocommerce-cart .wc-proceed-to-checkout a.checkout-button, 
        .woocommerce #payment #place_order, 
        .woocommerce-page #payment #place_order, 
        .woocommerce #respond input#submit, .woocommerce a.button, 
        .woocommerce button.button, .woocommerce input.button,
        .woocommerce-cart .woocommerce,
        .woocommerce-account .woocommerce,
        .woocommerce-checkout .woocommerce,
        .wt-grid-shortcode,
        .ex-loadmore .loadmore-grid,
        .woocommerce #wtmain-content .wt-table-lisst .wt-table td h3 a,
        .woocommerce #wtmain-content select,
        .wootour-search .exwt-btn.wt-product-search-dropdown-button,
        .wt-table-lisst .wt-table,
        .woocommerce #wtmain-content .wt-sidebar input,
        .woocommerce #wtmain-content .wt-sidebar,
        .wt-search-form .exwt-btn, .wt-search-form input[type="text"],
        .wt-search-form,
        .woocommerce #wtmain-content{ font-family: "<?php echo esc_html($main_font_family);?>", sans-serif;}
    <?php }
    if($wt_fontsize!=''){?>
    	.wt-dropdown-select,
        .woocommerce-cart table.cart td.actions .coupon .input-text,
        .woocommerce-wt-onsale, .woocommerce span.onsale,
        .wt-search-form .exwt-btn, .wt-search-form input[type="text"],
        .wt-timeline-shortcode ul li,
        .woocommerce-page .woocommerce,
        .woocommerce #wtmain-content,
        .wt-social-share ul li,
        .woocommerce #wtmain-content div.product form.cart .variations td.label,
        .wt-table-lisst .wt-table ,
        .woocommerce form .form-row input.input-text, .woocommerce form .form-row textarea,
        .woocommerce-cart table.cart td.actions .coupon .input-text,
        .woocommerce .select2-container .select2-choice,
        .woocommerce-page .woocommerce .myaccount_user,
        .woocommerce table.shop_table .quantity input,
        .woocommerce-cart table.cart td, .woocommerce-cart table.cart th, .woocommerce table.my_account_orders th, .woocommerce table.my_account_orders td, .wt-table-lisst .wt-table td, .wt-table-lisst .wt-table th,
        .wootour-search .exwt-btn.wt-product-search-dropdown-button,
        .wt-grid-shortcode figure.ex-modern-blog .grid-excerpt,
        .woocommerce #wtmain-content a, .woocommerce #wtmain-content,
        .exwt-btn.wt-button, .woocommerce div.product form.cart button.button, .woocommerce div.product form.cart div.quantity.buttons_added [type="button"], .woocommerce #wtmain-content .wt-main.layout-2 .tour-details .exwt-btn,.ex-loadmore .loadmore-grid,
        .woocommerce form.checkout_coupon, .woocommerce form.login, .woocommerce form.register, .woocommerce table.shop_table, 
        .woocommerce table.my_account_orders, .wt-table-lisst .wt-table,
        span.wt-sub-lb, .woo-tour-info span.sub-lb,
        .wt-location-arr,
        .gr-product h4,
        .woocommerce form .form-row select,
        .woocommerce-cart .wc-proceed-to-checkout a.checkout-button, 
        .woocommerce #payment #place_order, 
        .woocommerce-page #payment #place_order, 
        .woocommerce #respond input#submit, .woocommerce a.button, 
        .wt-grid-shortcode figure.ex-modern-blog .wt-more-meta span,
        .wootour-search .wt-product-search-form button,
        .woocommerce button.button, .woocommerce input.button{ font-size: <?php echo esc_html($wt_fontsize) ?>;}
        .woocommerce-page form .input-text::-webkit-input-placeholder{ font-size: <?php echo esc_html($wt_fontsize) ?>;  }
        .wt-search-form input.form-control::-webkit-input-placeholder{ font-size: <?php echo esc_html($wt_fontsize) ?>;  }
        .woocommerce-page form .input-text::-moz-placeholder{ font-size: <?php echo esc_html($wt_fontsize) ?>;  }
        .wt-search-form input.form-control{ font-size: <?php echo esc_html($wt_fontsize) ?>;  }
        .woocommerce-page form .input-text:-ms-input-placeholder{ font-size: <?php echo esc_html($wt_fontsize) ?>;  }
        .wt-search-form input.form-control:-ms-input-placeholder{ font-size: <?php echo esc_html($wt_fontsize) ?>;  }
        .woocommerce-page form .input-text:-moz-placeholder{ font-size: <?php echo esc_html($wt_fontsize) ?>;  }
        .wt-search-form input.form-control:-moz-placeholder{ font-size: <?php echo esc_html($wt_fontsize) ?>;  }
    <?php }
    if($h_font_family!=''){?>
        .wt-search-form span.loc-details h3,
        .wt-table-lisst .wt-table td h3 a,
        .woocommerce #wtmain-content .wt-table-lisst .wt-table td h3 a,
        .wt-table-lisst .wt-table td.tb-viewdetails .tb-price,
        .archive.woocommerce #wtmain-content h2,
        .archive.woocommerce #wtmain-content h3,
        .woocommerce #wtmain-content .wt-sidebar h2,
        .woocommerce #wtmain-content .wt-sidebar h3,
        .woocommerce #wtmain-content .wt-content-custom h1,
        .woocommerce #wtmain-content .product > *:not(.woocommerce-tabs) h1,
        .woocommerce-page .woocommerce .product > *:not(.woocommerce-tabs) h2,
        .woocommerce-page .woocommerce .product > *:not(.woocommerce-tabs) h3,
        .woocommerce-page.woocommerce-edit-account .woocommerce fieldset legend,
        .woocommerce #wtmain-content .product > *:not(.woocommerce-tabs) h2,
        body.woocommerce div.product .woocommerce-tabs .panel h2:first-child,
        .woocommerce div.product .product_title,
        figure.ex-modern-blog h3,
        .woocommerce #reviews #comments h2,
        .woocommerce #reviews h3,
        .woocommerce #reviews span#reply-title,
        body.woocommerce-page #wtmain-content .related ul.products li.product h3,
        .woocommerce-checkout .woocommerce h3,
        .woocommerce-account .woocommerce h3,
        .wt-grid-shortcode.wt-grid-column-1.wt-tourlist .ex-modern-blog .wt-more-meta *:not(i) ,
        .woocommerce #wtmain-content ul.tabs li a,
        .woocommerce div.product .woocommerce-tabs ul.tabs li a,
        .woocommerce #wtmain-content .product > *:not(.woocommerce-tabs) h3{
            font-family: "<?php echo esc_html($h_font_family);?>", sans-serif;
        }
    <?php }
    if($wt_hfontsize!=''){?>
        .wt-search-form span.loc-details h3,
        .wt-table-lisst .wt-table td h3 a,
        .woocommerce #wtmain-content .wt-table-lisst .wt-table td h3 a,
        .wt-table-lisst .wt-table td.tb-viewdetails .tb-price,
        .archive.woocommerce #wtmain-content h2,
        .archive.woocommerce #wtmain-content h3,
        .woocommerce #wtmain-content .wt-sidebar h2,
        .woocommerce #wtmain-content .wt-sidebar h3,
        .woocommerce #wtmain-content .wt-content-custom h1,
        .woocommerce #wtmain-content .product > *:not(.woocommerce-tabs) h1,
        .woocommerce-page .woocommerce .product > *:not(.woocommerce-tabs) h2,
        .woocommerce-page .woocommerce .product > *:not(.woocommerce-tabs) h3,
        .woocommerce-page.woocommerce-edit-account .woocommerce fieldset legend,
        .woocommerce #wtmain-content .product > *:not(.woocommerce-tabs) h2,
        body.woocommerce div.product .woocommerce-tabs .panel h2:first-child,
        .woocommerce div.product .product_title,
        figure.ex-modern-blog h3,
        .woocommerce #reviews #comments h2,
        .woocommerce #reviews h3,
        .woocommerce #reviews span#reply-title,
        body.woocommerce-page #wtmain-content .related ul.products li.product h3,
        .woocommerce #wtmain-content .product > *:not(.woocommerce-tabs) h3,
    	.wt-grid-shortcode.wt-grid-column-1.wt-tourlist .ex-modern-blog .wt-more-meta * ,
    	.woocommerce-checkout .woocommerce h3,
        .woocommerce-account .woocommerce h3,
        .woocommerce #wtmain-content .wt-content-custom h1,
        body.woocommerce div.product .woocommerce-tabs .panel h2:first-child,
        .woocommerce #reviews h3,
        .woocommerce #reviews span#reply-title,
        .woocommerce #reviews #comments h2,
        .woocommerce #wtmain-content .product > .related.products > h2,
        body.woocommerce-page .related.products > h2,
        .woo-tour-accompanied h3,
        .woocommerce-cart .woocommerce h2,
        .woo-tour-info.meta-full-style h3,
        .woocommerce #wtmain-content .product > .woo-tour-accompanied h3,
        .woocommerce #wtmain-content ul.tabs li a,
        .woocommerce div.product .woocommerce-tabs ul.tabs li a,
        .exwt-single-tour #wtmain-content .woocommerce-tabs ul.tabs li a, .exwt-single-tour .exwt-info > div.exwt-info-ct3 h3.exwt-dh-title,
        .woocommerce #wtmain-content .product > *:not(.woocommerce-tabs) h1{
            font-size: <?php echo esc_html($wt_hfontsize); ?>;
        }
    <?php }
    if($wt_hfontsize_pls!=''){?>
        .exwt-single-tour #wtmain-content .exwt-info > div.exwt-info-ct3 h3.exwt-dh-title,
        .exwt-single-tour #wtmain-content .woocommerce-tabs ul.tabs li a,
        .exwt-single-tour #wtmain-content h1.exwt-title,
        .exwt-single-tour #wtmain-content h3.h3-ev-schedu,
        .exwt-single-tour #wtmain-content .woo-tour-accompanied h3,
        .woocommerce #wtmain-content .wt-sidebar h3.widget-title{font-size: <?php echo esc_html($wt_hfontsize_pls); ?>;}
        <?php
    }

}else{
    if($wt_main_color!=''){?>
        .widget.wt-latest-tours-widget .thumb.item-thumbnail .item-evprice,
        .exwt-btn.wt-button,
        .ex-loadmore .loadmore-grid,
        .wt-grid-shortcode figure.ex-modern-blog .date,
        .wt-departure .picker table thead tr th,
        .wt-grid-shortcode.wt-grid-column-1 figure.ex-modern-blog .ex-social-share ul li a,
        .wt-table-lisst .wt-table th,
        .wt-search-form button[type="submit"]:hover, .wt-search-form button[type="submit"],
        .wt-quantity > input[type=button],
        .exwt-btn.we-button, .wt-disc-info .dc-title > span:not(:empty),
        .wt-tax-content .loc-details span,
        .wt-search-form #searchform #searchsubmit,
        .wt-ajax-pagination ul li .page-numbers.current,
        .wt-search-modern > div.wt-srel.wt-find-bt,
        .wt-grid-shortcode.wt-grid-column-1.wt-tourlist .item-post-n > h3,
        .price .tbss-viewprice,
        .wt-grid-shortcode figure.ex-modern-blog .ex-social-share{ background:#<?php echo esc_html($wt_main_color);?>}
        .wt-table-lisst .wt-table td.tb-viewdetails .tb-price,
        .wt-search-form span.loc-details h3,
        .wt-table-lisst .wt-table td h3 a,
        .wt-table-lisst .wt-table td.tb-viewdetails .tb-price,
        figure.ex-modern-blog h3,
        .wt-grid-shortcode.wt-grid-column-1.wt-tourlist .ex-modern-blog .wt-more-meta .wt-pr-la,
        .wt-ext-info .woo-tour-accompanied .tour-service span,
        .wt-table-lisst .wt-table td.tb-price, .wt-table-lisst .wt-table td span.amount{ color:#<?php echo esc_html($wt_main_color);?>}
        .wt-discount-sif .dc-title,
        .wt-ext-info .exwt-btn.we-button.wt-getct,
        .wt-search-form button[type="submit"]:hover, .wt-search-form button[type="submit"],
        .wt-table-lisst .wt-table{ border-color:#<?php echo esc_html($wt_main_color);?>}
        .wt-location-arr{background: rgba(<?php echo esc_attr($rgb);?>, .7);}
        .wt-grid-shortcode.wt-grid-column-1.wt-tourlist .item-post-n{ border-color:rgba(<?php echo esc_attr($rgb);?>, .7);}
        .wt-disc-info .dc-title  > span:empty{
            border: 13px solid #<?php echo esc_html($wt_main_color);?>; border-right: 13px solid #fff;
            border-left: 0;
        }
    <?php
    }
    if($wt_fontfamily!=''){?>
        .wt-search-form input.form-control::-webkit-input-placeholder{
            font-family: "<?php echo esc_html($main_font_family);?>", sans-serif;
        }
        .wt-search-form input.form-control:-ms-input-placeholder{
            font-family: "<?php echo esc_html($main_font_family);?>", sans-serif;
        }
        .wt-search-form input.form-control:-moz-placeholder{
            font-family: "<?php echo esc_html($main_font_family);?>", sans-serif;
        }
        .wt-search-form input.form-control {
            font-family: "<?php echo esc_html($main_font_family);?>", sans-serif;
        }
        .we-search-container,
        .wt-latest-event,
        .wt-grid-shortcode,
        .wt-grid-shortcode figure.ex-modern-blog .wt-more-meta span{
            font-family: "<?php echo esc_html($main_font_family);?>", sans-serif;
        }
    <?php }
    $wt_fontsize = get_option('wt_fontsize');
    if($wt_fontsize!=''){?>
        .wt-location-arr,
        .woocommerce-wt-onsale,
        .wt-search-form .exwt-btn, .wt-search-form input[type="text"],
        .wt-search-form span.loc-details span,
        .wt-grid-shortcode figure.ex-modern-blog .grid-excerpt,
        .ex-loadmore .loadmore-grid,
        .wt-table-lisst .wt-table,
        .exwt-btn.wt-button,
        .wootour-search .exwt-btn.wt-product-search-dropdown-button,
        .wootour-search .wt-product-search-form button,
        .wt-grid-shortcode figure.ex-modern-blog .wt-more-meta span{
            font-size: <?php echo esc_html($wt_fontsize) ?>;
        }
        .wt-search-form input.form-control::-webkit-input-placeholder{ font-size: <?php echo esc_html($wt_fontsize) ?>;  }
        .wt-search-form input.form-control{ font-size: <?php echo esc_html($wt_fontsize) ?>;  }
        .wt-search-form input.form-control:-ms-input-placeholder{ font-size: <?php echo esc_html($wt_fontsize) ?>;  }
        .wt-search-form input.form-control:-moz-placeholder{ font-size: <?php echo esc_html($wt_fontsize) ?>;  }
    <?php }
    if($h_font_family!=''){?>
        .wt-table-lisst .wt-table td h3 a,
        .wt-grid-shortcode figure.ex-modern-blog h3 a,
        .wt-grid-shortcode.wt-grid-column-1.wt-tourlist .ex-modern-blog .wt-more-meta *:not(i) ,
        .wt-infotable .wemap-details h4.wemap-title a{
            font-family: "<?php echo esc_html($h_font_family);?>", sans-serif;
        }
    <?php }
    if($wt_hfontsize!=''){?>
        .wt-table-lisst .wt-table td h3 a, .wt-table-lisst .wt-table td.tb-viewdetails .tb-price, 
        .wt-search-form span.loc-details h3,
        .wt-grid-shortcode.wt-grid-column-1.wt-tourlist .ex-modern-blog .wt-more-meta * ,
        .wt-table-lisst .wt-table td.tb-viewdetails .tb-price, figure.ex-modern-blog h3{
            font-size: <?php echo esc_html($wt_hfontsize); ?>;
        }
    <?php }
}
if($wt_main_color!=''){?>
    .wpex-spinner > div{background:#<?php echo esc_html($wt_main_color);?>}
    <?php
}
$wt_custom_css = get_option('wt_custom_css');
if($wt_custom_css!=''){
	echo $wt_custom_css;
}