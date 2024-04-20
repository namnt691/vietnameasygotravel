<?php
	global $woocommerce, $product;
    if ( ! function_exists( 'get_woocommerce_price_format' ) ) {
        $currency_pos = get_option( 'woocommerce_currency_pos' );
        switch ( $currency_pos ) {
            case 'left' :
                $format = '%1$s%2$s';
            break;
            case 'right' :
                $format = '%2$s%1$s';
            break;
            case 'left_space' :
                $format = '%1$s&nbsp;%2$s';
            break;
            case 'right_space' :
                $format = '%2$s&nbsp;%1$s';
            break;
        }
        $currency_fm = esc_attr( str_replace( array( '%1$s', '%2$s' ), array( '%s', '%v' ), $format ) );
    } else {
        $currency_fm = esc_attr( str_replace( array( '%1$s', '%2$s' ), array( '%s', '%v' ), get_woocommerce_price_format() ) );
    }
    $wt_fixed_price = get_post_meta( get_the_ID(), 'wt_fixed_price', true );
    $thousand_sep = get_option( 'woocommerce_price_thousand_sep' );
    $decimal_sep = get_option( 'woocommerce_price_decimal_sep' );
    $num_decimals = get_option( 'woocommerce_price_num_decimals' );
    if(function_exists('wmc_get_price')){
        $wmc_settings                  = get_option( 'woo_multi_currency_params', array() );
    }
	echo sprintf('<div id="product_total_price" style="display: block;">%s %s</div>',__('Total:','woo-tour'),'<span class="price">'.$product->get_price().'</span>');?>
    <script>
        if (typeof accounting === 'undefined') {
            (function(p,z){function q(a){return!!(""===a||a&&a.charCodeAt&&a.substr)}function m(a){return u?u(a):"[object Array]"===v.call(a)}function r(a){return"[object Object]"===v.call(a)}function s(a,b){var d,a=a||{},b=b||{};for(d in b)b.hasOwnProperty(d)&&null==a[d]&&(a[d]=b[d]);return a}function j(a,b,d){var c=[],e,h;if(!a)return c;if(w&&a.map===w)return a.map(b,d);for(e=0,h=a.length;e<h;e++)c[e]=b.call(d,a[e],e,a);return c}function n(a,b){a=Math.round(Math.abs(a));return isNaN(a)?b:a}function x(a){var b=c.settings.currency.format;"function"===typeof a&&(a=a());return q(a)&&a.match("%v")?{pos:a,neg:a.replace("-","").replace("%v","-%v"),zero:a}:!a||!a.pos||!a.pos.match("%v")?!q(b)?b:c.settings.currency.format={pos:b,neg:b.replace("%v","-%v"),zero:b}:a}var c={version:"0.4.1",settings:{currency:{symbol:"$",format:"%s%v",decimal:".",thousand:",",precision:2,grouping:3},number:{precision:0,grouping:3,thousand:",",decimal:"."}}},w=Array.prototype.map,u=Array.isArray,v=Object.prototype.toString,o=c.unformat=c.parse=function(a,b){if(m(a))return j(a,function(a){return o(a,b)});a=a||0;if("number"===typeof a)return a;var b=b||".",c=RegExp("[^0-9-"+b+"]",["g"]),c=parseFloat((""+a).replace(/\((.*)\)/,"-$1").replace(c,"").replace(b,"."));return!isNaN(c)?c:0},y=c.toFixed=function(a,b){var b=n(b,c.settings.number.precision),d=Math.pow(10,b);return(Math.round(c.unformat(a)*d)/d).toFixed(b)},t=c.formatNumber=c.format=function(a,b,d,i){if(m(a))return j(a,function(a){return t(a,b,d,i)});var a=o(a),e=s(r(b)?b:{precision:b,thousand:d,decimal:i},c.settings.number),h=n(e.precision),f=0>a?"-":"",g=parseInt(y(Math.abs(a||0),h),10)+"",l=3<g.length?g.length%3:0;return f+(l?g.substr(0,l)+e.thousand:"")+g.substr(l).replace(/(\d{3})(?=\d)/g,"$1"+e.thousand)+(h?e.decimal+y(Math.abs(a),h).split(".")[1]:"")},A=c.formatMoney=function(a,b,d,i,e,h){if(m(a))return j(a,function(a){return A(a,b,d,i,e,h)});var a=o(a),f=s(r(b)?b:{symbol:b,precision:d,thousand:i,decimal:e,format:h},c.settings.currency),g=x(f.format);return(0<a?g.pos:0>a?g.neg:g.zero).replace("%s",f.symbol).replace("%v",t(Math.abs(a),n(f.precision),f.thousand,f.decimal))};c.formatColumn=function(a,b,d,i,e,h){if(!a)return[];var f=s(r(b)?b:{symbol:b,precision:d,thousand:i,decimal:e,format:h},c.settings.currency),g=x(f.format),l=g.pos.indexOf("%s")<g.pos.indexOf("%v")?!0:!1,k=0,a=j(a,function(a){if(m(a))return c.formatColumn(a,f);a=o(a);a=(0<a?g.pos:0>a?g.neg:g.zero).replace("%s",f.symbol).replace("%v",t(Math.abs(a),n(f.precision),f.thousand,f.decimal));if(a.length>k)k=a.length;return a});return j(a,function(a){return q(a)&&a.length<k?l?a.replace(f.symbol,f.symbol+Array(k-a.length+1).join(" ")):Array(k-a.length+1).join(" ")+a:a})};if("undefined"!==typeof exports){if("undefined"!==typeof module&&module.exports)exports=module.exports=c;exports.accounting=c}else"function"===typeof define&&define.amd?define([],function(){return c}):(c.noConflict=function(a){return function(){p.accounting=a;c.noConflict=z;return c}}(p.accounting),p.accounting=c)})(this);
        }

        jQuery(function($){
        function addCommas(nStr){
            nStr += '';
            x = nStr.split('.');
            x1 = x[0];
            x2 = x.length > 1 ? '.' + x[1] : '';
            var rgx = /(\d+)(\d{3})/;
            while (rgx.test(x1)) {
                x1 = x1.replace(rgx, '$1' + ',' + '$2');
            }
            return x1 + x2;
        }
        var currency ='<?php echo get_woocommerce_currency_symbol(); ?>';
            function priceformat($form) {
                var product_total ='';
                /*
                if($form.hasClass('variations_form')){
                    if($('form > .single_variation_wrap .woocommerce---price ins .amount').length){
                        product_total = jQuery('form > .single_variation_wrap .woocommerce---price ins .amount').text();
                    }

                    if(product_total==''){
                        product_total = jQuery('form > .single_variation_wrap .woocommerce---price .amount').text();
                    }
                    if(!$('form > .single_variation_wrap .woocommerce---price').length){
                        product_total = jQuery('form > .single_variation_wrap ins .amount').text();
                        if(product_total==''){
                            product_total = jQuery('form > .single_variation_wrap .amount').text();
                        }
                    }
                }else{
                    if($('.product-summary .price-wrapper .price').length){
                        $class_pr = '.product-summary .price-wrapper';   
                    }else if($('.summary .summary-inner .price').length){
                        $class_pr = '.summary .summary-inner';   
                    }else{$class_pr = '.summary';}
                    if($($class_pr +' .price .amount').length){
                        product_total = jQuery($class_pr +' > .price .amount').text();
                        if($($class_pr +' > .price ins .amount').length){
                            product_total = jQuery($class_pr +' > .price ins .amount').text();
                        }
                    }else if($('.product_field.price .amount').length){
                        product_total = jQuery('.product_field.price .amount').text();
                        if($('.product_field.price ins .amount').length){
                            product_total = jQuery('.product_field.price ins .amount').text();
                        }
                    }
                    if(product_total ==''){
                        product_total = jQuery('.wt-user-info > span:first-of-type .amount').text();
                        if($('.wt-user-info > span:first-of-type ins .amount').length){
                            product_total = jQuery('.wt-user-info > span:first-of-type ins .amount').text();
                        }
                    }
                    
                }*/
                if($form.hasClass('variations_form')){
                    if($form.find(' > .single_variation_wrap .woocommerce-variation .woocommerce---price ins .amount').length){
                        product_total = $form.find(' > .single_variation_wrap .woocommerce-variation .woocommerce---price ins .amount').text();
                    }
                    if(product_total==''){
                        product_total = $form.find(' > .single_variation_wrap .woocommerce-variation .woocommerce---price .amount').text();
                    }
                    if(!$form.find(' > .single_variation_wrap .woocommerce-variation .woocommerce---price').length){
                        product_total = $form.find(' > .single_variation_wrap .woocommerce-variation ins .amount').text();
                        if(product_total==''){
                            product_total = $form.find(' > .single_variation_wrap .woocommerce-variation .amount').text();
                        }
                    }
                }else{
                    if($form.find('.wt-user-info > span:first-of-type .exwt-sspr').length){
                        product_total = '';
                    }else{
                        product_total = $form.find('.wt-user-info > span:first-of-type .amount').text();
                        if($form.find('.wt-user-info > span:first-of-type ins .amount').length){
                            product_total = $form.find('.wt-user-info > span:first-of-type ins .amount').text();
                        }
                    }
                }
                $discount = 0;
                var ttbf_rm = product_total;
                product_total = product_total.replace( currency, '' );
                if(ttbf_rm = product_total){
                    currency = $("<div/>").html('<?php echo get_woocommerce_currency_symbol(); ?>').text();
                    product_total = product_total.replace( currency, '' );
                }
                <?php if($thousand_sep!=''){?>
                product_total = product_total.replace( /\<?php echo $thousand_sep;?>/g, '' );
                <?php }?>
                <?php if($decimal_sep!=''){?>
                product_total = product_total.replace( '<?php echo $decimal_sep;?>', '.' );
                <?php }?>
                product_total = product_total.replace(/[^0-9\.]/g, '' );
                var _t_price = product_total;
                if($form.find('[name=wt_number_adult]').length){
                    var adult = $form.find('[name=wt_number_adult]').val();
                    if($form.find(".crda-dcinfo.wt-dctype-adult > span").length){
                        $form.find(".crda-dcinfo.wt-dctype-adult > span").each(function(){
                            var $this = $(this);
                            $nbad = $this.data('adult');
                            if(adult >= $nbad ){
                                $dc_type = $this.data('type');
                                $dc_nb = $this.data('number');
                                if($dc_type =='percent'){
                                    $discount = (adult * product_total * $dc_nb)/100;
                                }else{
                                    $discount = adult *  $dc_nb;
                                }
                                if(!$this.hasClass('dc-active')){
                                    $form.find(".crda-dcinfo.wt-dctype-adult > span.dc-active").removeClass('dc-active').fadeOut();
                                    setTimeout(function(){ 
                                        $this.addClass('dc-active').fadeIn();
                                    }, 500);
                                }
                                return false;
                            }else{
                                $form.find(".crda-dcinfo.wt-dctype-adult > span.dc-active").removeClass('dc-active').fadeOut();
                            }
                        });
                    }else if($form.find(".crda-dcinfo .wt-dc-season").length){
                        $dc_type = $form.find(".crda-dcinfo .wt-dc-season").data('type');
                        $dc_nb = $form.find(".crda-dcinfo .wt-dc-season").data('number');
                        if( $dc_type == 'percent'){
                            $discount = (adult * product_total * $dc_nb)/100;
                        }else{
                            $discount = adult *  $dc_nb;
                        }
                    }
                }else{var adult = 1;}
                <?php if($wt_fixed_price!='yes'){?>
                    product_total = product_total* adult;
                    product_total = product_total - $discount;
                    if($form.find('[name=wt_number_child]').length){
                        var price_child =0;
                        var child = $form.find('[name=wt_number_child]').val();
                        if($form.find('.woocommerce-variation-wt-child-price .amount').length){
                            price_child = $form.find('.woocommerce-variation-wt-child-price span > .amount').text();
                        }else if($form.find('._child_select span > span.woocommerce-Price-amount.amount').length){
                            price_child = $form.find('._child_select span > span.woocommerce-Price-amount.amount').text(); 
                        }
                        if(price_child !=''){
                            price_child = price_child.replace( currency, '' );
                            <?php if($thousand_sep!=''){?>
                            price_child = price_child.replace( /\<?php echo $thousand_sep;?>/g, '' );
                            <?php }?>
                            <?php if($decimal_sep!=''){?>
                            price_child = price_child.replace( '<?php echo $decimal_sep;?>', '.' );
                            <?php }?>
                            price_child = price_child.replace(/[^0-9\.]/g, '' );

                            product_total = product_total + (child*price_child);
                            if($form.find(".crda-dcinfo .wt-dc-season").length){
                                $dc_type = $form.find(".crda-dcinfo .wt-dc-season").data('type');
                                $dc_nb = $form.find(".crda-dcinfo .wt-dc-season").data('number');
                                if( $dc_type == 'percent'){
                                    $discount = (child * price_child * $dc_nb)/100;
                                }else{
                                    $discount = child *  $dc_nb;
                                }
                                product_total = product_total - $discount;
                            }
                        }
                    }
                    if($form.find('[name=wt_number_infant]').length){
                        var price_infant =0;
                        var infant = $form.find('[name=wt_number_infant]').val();
                        if($form.find('.woocommerce-variation-wt-infant-price .amount').length){
                            price_infant = $form.find('.woocommerce-variation-wt-infant-price span > .amount').text();
                        }else if($form.find('._infant_select span > span.woocommerce-Price-amount.amount').length){
                            price_infant = $form.find('._infant_select span > span.woocommerce-Price-amount.amount').text();
                        }
                        
                        if(price_infant !=''){
                            price_infant = price_infant.replace( currency, '' );
                            <?php if($thousand_sep!=''){?>
                            price_infant = price_infant.replace( /\<?php echo $thousand_sep;?>/g, '' );
                            <?php }?>
                            <?php if($decimal_sep!=''){?>
                            price_infant = price_infant.replace( '<?php echo $decimal_sep;?>', '.' );
                            <?php }?>
                            price_infant = price_infant.replace(/[^0-9\.]/g, '' );

                            product_total = product_total + (infant*price_infant);
                            if($form.find(".crda-dcinfo .wt-dc-season").length){
                                if( $dc_type == 'percent'){
                                    $discount = (infant * price_infant * $dc_nb)/100;
                                }else{
                                    $discount = infant *  $dc_nb;
                                }
                                product_total = product_total - $discount;
                            }
                        }
                    }
          			// ct1
    				if($form.find('[name=wt_number_ct1]').length){
                        var price_ct1 =0;
                        var ct1 = $form.find('[name=wt_number_ct1]').val();
                        if($form.find('.woocommerce-variation-wt-ct1-price .amount').length){
                            price_ct1 = $form.find('.woocommerce-variation-wt-ct1-price span > .amount').text();
                        }else if($form.find('._ct1_select span > span.woocommerce-Price-amount.amount').length){
                            price_ct1 = $form.find('._ct1_select span > span.woocommerce-Price-amount.amount').text();
                        }
                        if(price_ct1 !=''){
                            price_ct1 = price_ct1.replace( currency, '' );
                            <?php if($thousand_sep!=''){?>
                            price_ct1 = price_ct1.replace( /\<?php echo $thousand_sep;?>/g, '' );
                            <?php }?>
                            <?php if($decimal_sep!=''){?>
                            price_ct1 = price_ct1.replace( '<?php echo $decimal_sep;?>', '.' );
                            <?php }?>
                            price_ct1 = price_ct1.replace(/[^0-9\.]/g, '' );

                            product_total = product_total + (ct1*price_ct1);
                            if($form.find(".crda-dcinfo .wt-dc-season").length){
                                if( $dc_type == 'percent'){
                                    $discount = (ct1 * price_ct1 * $dc_nb)/100;
                                }else{
                                    $discount = ct1 *  $dc_nb;
                                }
                                product_total = product_total - $discount;
                            }
                        }
                    }
    				// ct2
    				if($form.find('[name=wt_number_ct2]').length){
                        var price_ct2 =0;
                        var ct2 = $form.find('[name=wt_number_ct2]').val();
                        if($form.find('.woocommerce-variation-wt-ct2-price .amount').length){
                            price_ct2 = $form.find('.woocommerce-variation-wt-ct2-price span > .amount').text();
                        }else if($form.find('._ct2_select span > span.woocommerce-Price-amount.amount').length){
                            price_ct2 = $form.find('._ct2_select span > span.woocommerce-Price-amount.amount').text();
                        }
                        if(price_ct2 !=''){
                            price_ct2 = price_ct2.replace( currency, '' );
                            <?php if($thousand_sep!=''){?>
                            price_ct2 = price_ct2.replace( /\<?php echo $thousand_sep;?>/g, '' );
                            <?php }?>
                            <?php if($decimal_sep!=''){?>
                            price_ct2 = price_ct2.replace( '<?php echo $decimal_sep;?>', '.' );
                            <?php }?>
                            price_ct2 = price_ct2.replace(/[^0-9\.]/g, '' );

                            product_total = product_total + (ct2*price_ct2);
                            if($form.find(".crda-dcinfo .wt-dc-season").length){
                                if( $dc_type == 'percent'){
                                    $discount = (ct2 * price_ct2 * $dc_nb)/100;
                                }else{
                                    $discount = ct2 *  $dc_nb;
                                }
                                product_total = product_total - $discount;
                            }
                        }
                    }
                <?php }?>
                if($form.find('.quantity .qty').length){
                    var $qty = $form.find('.quantity .qty').val();
                    if(jQuery.isNumeric( $qty )){
                        product_total = product_total*$qty;
                    }
                }
                // support product addon
                if($form.find('#product-addons-total').length){
                    var addon_pr = 0;
                    addon_pr = $form.find('#product-addons-total .price .amount').text();
                    if(addon_pr !=''){
                        addon_pr = addon_pr.replace( currency, '' );
                        <?php if($thousand_sep!=''){?>
                        addon_pr = addon_pr.replace( /\<?php echo $thousand_sep;?>/g, '' );
                        <?php }?>
                        <?php if($decimal_sep!=''){?>
                        addon_pr = addon_pr.replace( '<?php echo $decimal_sep;?>', '.' );
                        <?php }?>
                        addon_pr = addon_pr.replace(/[^0-9\.]/g, '' );
                        if(adult < 1){
                            _t_price = 0; 
                        }
                        product_total = product_total + (adult*(addon_pr - _t_price));
                        $form.find(".wc-pao-addon-container").each(function(){
                            var $field = $(this).find('.wc-pao-addon-field');
                            if($field.hasClass('wc-pao-addon-checkbox')){
                                $field.each(function(){
                                    if($(this).attr('data-price-type') =='flat_fee' && $(this).is(':checked')){
                                        product_total = product_total - ($(this).attr('data-price') * (adult -1))      
                                    }
                                });
                            }else if($field.hasClass('wc-pao-addon-select')){
                                $field.find('option').each(function(){
                                    if($(this).attr('data-price-type') =='flat_fee' && $(this).is(':selected')){
                                        product_total = product_total - ($(this).attr('data-price') * (adult -1))      
                                    }
                                });
                            }else if($field.hasClass('wc-pao-addon-input-multiplier')){
                                if($field.attr('data-price-type') =='flat_fee'){
                                    product_total = product_total - ($field.attr('data-price') * $field.val() * (adult -1))      
                                }
                            }else{
                                if($field.attr('data-price-type') =='flat_fee'){
                                    product_total = product_total - ($field.attr('data-price') * (adult -1))      
                                }
                            }
                        });
                        
                    }
                }
                // support YITH addon
                if($form.find('#wapo-total-options-price').length){
                    var addon_pr = 0;
                    addon_pr = $form.find('#wapo-total-options-price').text();
                    if(addon_pr !=''){
                        addon_pr = addon_pr.replace( currency, '' );
                        <?php if($thousand_sep!=''){?>
                        addon_pr = addon_pr.replace( /\<?php echo $thousand_sep;?>/g, '' );
                        <?php }?>
                        <?php if($decimal_sep!=''){?>
                        addon_pr = addon_pr.replace( '<?php echo $decimal_sep;?>', '.' );
                        <?php }?>
                        addon_pr = addon_pr.replace(/[^0-9\-.]/g, '' );
                        if(addon_pr*1 != 0){
                            product_total = product_total +  addon_pr*1;
                        }   
                    }
                }
                //
                $('.exwo-product-options .exrow-group:not(.exwf-offrq)').each(function(){
                    var $this_sl = $(this);
                    if($this_sl.hasClass('ex-radio') || $this_sl.hasClass('ex-checkbox')){
                        $this_sl.find('.ex-options').each(function(){
                            var $this_op = $(this);
                            if($this_op.is(":checked")){
                                var $price_op = $this_op.data('price');
                                if($.isNumeric($price_op)){
                                    if($this_op.data('type')=='fixed'){
                                        product_total = product_total + $price_op*1;
                                    }else{
                                        product_total = product_total + ($price_op*adult);
                                    }
                                }
                            }
                        });
                    }else if($this_sl.hasClass('ex-select')){
                        $this_sl.find('.ex-options option').each(function(){
                            var $this_op = $(this);
                            if($this_op.is(":selected")){
                                var $price_op = $this_op.data('price');
                                if($.isNumeric($price_op)){
                                    if($this_op.data('type')=='fixed'){
                                        product_total = product_total + $price_op*1;
                                    }else{
                                        product_total = product_total + ($price_op*adult);
                                    }
                                }
                            }
                        });
                    }else{
                        var $this_op = $this_sl.find('.ex-options');
                        var $price_op = $this_op.data('price');
                        if($this_sl.hasClass('ex-quantity')){
                            $price_op = $price_op*$this_sl.find('input.ex-options').val();
                        }
                        if($this_op.val() != '' && $.isNumeric($price_op)){
                            if($this_op.data('type')=='fixed'){
                                product_total = product_total + $price_op;
                            }else{
                                product_total = product_total + ($price_op*adult);
                            }
                        }
                    }
                });
                $total_cr = accounting.formatMoney( product_total,{
                    symbol      : currency,
                    decimal     : '<?php echo $decimal_sep;?>',
                    thousand    : '<?php echo $thousand_sep;?>',
                    precision   : '<?php echo $num_decimals;?>',
                    format      : '<?php echo $currency_fm;?>'
                });
                $form.find('#product_total_price .price').html( $total_cr);
            }
            if($('#product-addons-total').length){
                $("body").on('DOMSubtreeModified', "#product-addons-total", function() {
                    var $form = $(this).closest('form.cart');
                    priceformat($form);
                });
            }
            if($('#wapo-total-options-price').length){
                $("body").on('DOMSubtreeModified', "#wapo-total-options-price", function() {
                    var $form = $(this).closest('form.cart');
                    priceformat($form);
                });
            }

            jQuery('body').on('change','[name=wt_number_adult], [name=wt_number_child], [name=wt_number_infant], [name=wt_number_ct1], [name=wt_number_ct2]',function(){
                var $form = $(this).closest('form.cart');
                priceformat($form);
            });
            jQuery('.quantity .qty').on('keyup mouseup', function(){ var $form = $(this).closest('form.cart');
                priceformat($form);});
            jQuery('.wt-quantity input[type=text]').on('keyup', function(){ var $form = $(this).closest('form.cart');
                priceformat($form);});
            jQuery('body').on('change','.variations select',function(){ var $form = $(this).closest('form.cart');
                priceformat($form); });
            jQuery('.woocommerce-variation, .wtsl-text').on('click', '.wt-quantity #wtminus_ticket', function(e) {
                var $form = $(this).closest('form.cart');
                priceformat($form);
            });
            jQuery('.woocommerce-variation').on('click', '.wt-quantity #wtadd_ticket', function(e) {
                var $form = $(this).closest('form.cart');
                priceformat($form);
            });
            jQuery('body').on('click', '.wt-quantity #wtminus_ticket, .wt-quantity #wtadd_ticket,  .variations input[type=radio]', function(e) {
                var $this = $(this);
                setTimeout(function(){
					var $form = $this.closest('form.cart');
                    priceformat($form);
				}, 200);
            });
            jQuery("form.cart").each(function(){
                var $form = $(this);
                priceformat($form);
            });
            jQuery('body').on('change', '.exrow-group:not(.exwf-offrq).ex-select .ex-options',  function(e) {
                jQuery(this).trigger('click'); 
            });
            jQuery('body').on('click', '.exrow-group:not(.exwf-offrq) .ex-options',  function(e) {
                var $this = $(this);
                setTimeout(function(){
                    var $form = $this.closest('form.cart');
                    priceformat($form);
                }, 200);
            });
            setTimeout(function(){
                jQuery("form.cart").each(function(){
                    var $form = $(this);
                    priceformat($form);
                });
            }, 200);
        });
    </script>	