;(function($){
	/*jQuery(document).on('change', '.wt-departure input[name=wt_date]', function() {
		jQuery(this).removeClass('warning-select');
		jQuery('.product-content .single_add_to_cart_button').removeClass('disable-book');
	});*/
	
	$(window).on('load', function(){
		var $container = $('.wt-grid-shortcode:not(.wt-grid-column-1):not(.wt-dismasonry) .grid-container');
		$container.imagesLoaded( function() {
			$container.masonry({
				itemSelector: '.item-post-n',
				horizontalOrder: true
			});
		});
	});
		function ex_carousel(){
			$(".is-carousel").each(function(){
				var carousel_id = $(this).attr('id');
				var auto_play = $(this).data('autoplay');
				var items = $(this).data('items');
				var navigation = $(this).data('navigation');
				var pagination = $(this).data('pagination');
				var rtl_mode = $(this).data('rtl');
				var paginationNumbers = $(this).data('paginationNumbers');
				if($(this).hasClass('single-carousel')){ //single style
					$(this).wt_exlCarousel({
						singleItem:true,
						rtl: rtl_mode =='yes' ? true : false,
						autoHeight: true,
						autoPlay: auto_play?true:false,
						navigation: navigation?true:false,
						autoHeight : true,
						navigationText:["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
						addClassActive : true,
						pagination:pagination?true:false,
						paginationNumbers:paginationNumbers?true:false,
						stopOnHover: true,
						slideSpeed : 600,
						transitionStyle : "fade"
					});
				}else{
					$(this).wt_exlCarousel({
						autoPlay: auto_play?true:false,
						items: items?items:4,
						rtl: rtl_mode =='yes' ? true : false,
						itemsDesktop: items?false:4,
						itemsDesktopSmall: items?(items>3?3:false):3,
						singleItem: items==1?true:false,
						//autoHeight : true,
						navigation: navigation?true:false,
						paginationNumbers:paginationNumbers?true:false,
						navigationText:["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
						pagination:pagination?true:false,
						slideSpeed: 500,
						addClassActive : true
					});
				}
			});
		}
		function wt_disable_weekday_season(){
			var $n_season = $("input[name=number_season]").val();
			if($n_season>0){
				$_dateunix = $('input[name=_dateunix]').val();
				var ndu = $_dateunix*1000;
				$('.wt-departure .picker__table tbody .picker__day').each(function(){
					var $this_cl = $(this);
					$this_cl.removeClass('picker_weed-season--disabled');
					$data_pick = $this_cl.data('pick')/1000;
					var ndf = new Date($data_pick*1000);
					var userTimezoneOffset = ndf.getTimezoneOffset() * 60000;
					ndf = ndf.getTime() - userTimezoneOffset;
					if(ndf >= ndu){
						ndf = ndf/1000;
						for($i=1; $i<=$n_season; $i++){
							var $startdate_sason = $('input[name=startdate_sason_'+$i+']').val();
							var $enddate_season = $('input[name=enddate_season_'+$i+']').val();
							var $weekday_sason = $('input[name=weekday_sason_'+$i+']').val();
							$day_disable = $.parseJSON($weekday_sason);
							if(($day_disable.length != 0) && ($startdate_sason <= ndf) && ($enddate_season >= ndf)){
								$data_day = $this_cl.data('day');
								$.each($day_disable, function(i, item) {
									if(item == $data_day){
										$this_cl.addClass('picker_weed-season--disabled');
										//console.log($day_disable);
										//return false;
									}
								});
								
							}
						}
					}
				});
			}
		}
		function wt_disable_bf_hour(){
			if($('input[name=_hour_to_disable]').length > 0 && $('input[name=_dateunix]').length > 0){
				$('.picker_ct_day--disabled').removeClass('picker_ct_day--disabled');
				$_hour_to_disable = $('input[name=_hour_to_disable]').val();
				$_hour_based_on = $('input[name=_hour_based_on]').val();
				$_datetimeunix = $('input[name=_datetimeunix]').val();
				$_dateunix = $('input[name=_dateunix]').val();
				$date_dis = ($_dateunix*1 + $_hour_based_on*1) - $_hour_to_disable*1;
				var $i = $j=  1;
				var $arr = new Array();
				if($_hour_to_disable!=''){
					$('.wt-departure .picker__table tbody .picker__day').each(function(){
					var $this_cl = $(this);
					$arr[$j] = $this_cl;
					$data_pick = $this_cl.data('pick')/1000;
					var ndf = new Date($data_pick*1000);
					var userTimezoneOffset = ndf.getTimezoneOffset() * 60000;
					ndf = ndf.getTime() - userTimezoneOffset;
					var ndu = $_dateunix*1000;
					if( ndf < ndu ){
						$this_cl.addClass('picker_ct_day--disabled');
						if($this_cl.hasClass('picker__day--selected')){
							$('.wt-departure input[name=wt_date], .wt-departure input[name=wt_sldate]').val('').addClass('warning-select');
							$('.product-content .single_add_to_cart_button').addClass('disable-book');
						}
					}else{
						if(ndf == ndu){
							if($_datetimeunix > ($_dateunix*1 + $_hour_based_on*1 - $_hour_to_disable*1)){
								$this_cl.addClass('picker_ct_day--disabled');
								if($this_cl.hasClass('picker__day--selected')){
									$('.wt-departure input[name=wt_date], .wt-departure input[name=wt_sldate]').val('').addClass('warning-select');
									$('.product-content .single_add_to_cart_button').addClass('disable-book');
									$date_dis = $date_dis + 86400;
								}
							}
						}else{
							
							if((($_dateunix*1 + 86400*$i) + $_hour_based_on*1) < ($_datetimeunix*1 + $_hour_to_disable*1)){
								$this_cl.addClass('picker_ct_day--disabled');
								if($this_cl.hasClass('picker__day--selected')){
									$('.wt-departure input[name=wt_date], .wt-departure input[name=wt_sldate]').val('').addClass('warning-select');
									$('.product-content .single_add_to_cart_button').addClass('disable-book');
									$date_dis = $date_dis + 86400;
								}
							}
							$i ++;
						}
					}
				});
				}
				//$('.wt-departure input[name=wt_date], .wt-departure input[name=wt_sldate]').val('');
			}
		}
		function wt_check_ticket_status(wt_sldate,$form){
			if(wt_sldate==''){ return;}
			var ajaxurl = jQuery('input[name=wt_ajax_url]').val();
			var wt_tourid = $form.find('input[name=wt_tourid]').val();
			var wt_variable_id = '';
			if($form.hasClass('variations_form') && !$form.find('input[name=wt_variable_id]').length > 0){
				return;
			}
			if($form.find('input[name=wt_variable_id]').length > 0){
				wt_variable_id = $form.find('input[name=wt_variable_id]').val();
			}
			$form.find('.single_add_to_cart_button').addClass('loading');
			jQuery.ajax({
				url: ajaxurl,
				type: "POST",
				dataType: 'json',
				data: {
					action:'wt_check_ticket_status',
					wt_sldate : wt_sldate,
					wt_tourid : wt_tourid,
					wt_variable_id : wt_variable_id,
				},
				success: function(data){
					if(data!=0){
						// console.log(data);
						if(data.status=='0'){
							$form.find('.single_add_to_cart_button').addClass('wtdisabled');
						}else{
							$form.find('.single_add_to_cart_button').removeClass('wtdisabled');
						}
						// season price
						if(data.p_season!='' && data.p_season!=null){
							if($form.hasClass('variations_form')){
								$form.find('.tour-tble .woocommerce---price .p-price').html(data.p_season.wt_p_adult).fadeIn();
								$form.find('.tour-tble .woocommerce-variation-wt-child-price .p-price').html(data.p_season.wt_p_child).fadeIn();
								$form.find('.tour-tble .woocommerce-variation-wt-infant-price .p-price').html(data.p_season.wt_p_infant).fadeIn();
								$form.find('.tour-tble .woocommerce-variation-wt-ct1-price .p-price').html(data.p_season.wt_p_ctps1).fadeIn();
								$form.find('.tour-tble .woocommerce-variation-wt-ct2-price .p-price').html(data.p_season.wt_p_ctps2).fadeIn();
							}else{
								$form.find('._adult_select .p-price').html(data.p_season.wt_p_adult).fadeIn();
								$form.find('._child_select .p-price').html(data.p_season.wt_p_child).fadeIn();
								$form.find('._infant_select .p-price').html(data.p_season.wt_p_infant).fadeIn();
								$form.find('._ct1_select .p-price').html(data.p_season.wt_p_ctps1).fadeIn();
								$form.find('._ct2_select .p-price').html(data.p_season.wt_p_ctps2).fadeIn();
							}
						}

						$form.find('.wt-tickets-status').html(data.massage);
						if($form.find('select[name=wt_number_adult]').length){
							$form.find('[name=wt_number_adult]').trigger('change');
						}else if($form.find('.wt-quantity [name=wt_number_adult]').length){
							$form.find('.wt-quantity [name=wt_number_adult]').trigger('keyup');
						}
						
						$form.find('.single_add_to_cart_button').removeClass('loading');
					}
				}
			});
		}
		function exwt_variation_days($form,$picker){
			var data_variation = $form.find( 'input[name=exwt_dvardays]').val();
			var data_key = $form.find( 'input[name=exwt_keydays]').val();
			if(data_variation=='' || data_variation==undefined){ return;}
			if($picker=='off'){
				var $day = $form.attr('data-day');
				if($day=='' || $day==undefined){ return;}
			}else{
				var $datapick = $picker.get('select');
				var $day = $datapick['day']==0 ? 8 : $datapick['day'];
				$form.attr('data-day',$day);
				$form.find( '.variations .reset_variations').trigger('click');
			}
			data_variation = JSON.parse(data_variation);
			data_key = JSON.parse(data_key);
			$.each(data_variation,function(index, value){
			    if(value!='' && value!=null){
			    	$.each(value,function(index1, value1){
			    		if(value1==$day){//alert('12');
			    			index = index.split('|');//console.log(index);
			    			//console.log(index[1]);
			    			if(index[1] !== undefined){
			    				//console.log($form.find( '.variations select[name="'+data_key[index[0]]+'"]').val());
			    				if($form.find( '.variations select[name="'+data_key[index[0]]+'"]').val() == index[0] ){
			    					$form.find( '.variations select[name="'+data_key[index[1]]+'"] option[value="'+(index[1])+'"] ').remove();
			    				}
				    			//$form.find( '.variations select option[value="'+(typeof index[1] !== 'undefined' ? index[1] :index[1] )+'"]').remove();
				    		}else{
				    			$form.find( '.variations select option[value="'+(index[0])+'"]').remove();
				    		}
			    		}
			    	});
			    }
			});
		}
		$(document).ready(function() {
			jQuery('body').on('change','.variations select',function(){
				if(jQuery(".tour-info-select input[name=wt_sldate]").length>0 && jQuery(".tour-info-select input[name=wt_sldate]").val()!=''){
					var $form = $(this).closest('form.cart');
					exwt_variation_days($form,'off');
					wt_check_ticket_status(jQuery(".tour-info-select input[name=wt_sldate]").val(),$form);
				}
			});
			/*
			jQuery('.woocommerce-variation').on('click', '.wt-quantity #wtminus_ticket', function(e) {
				var value = parseInt(jQuery(this).next().val()) - 1;
				if(jQuery(this).next().attr('name')=='wt_number_adult'){
					if(value > 0){
						jQuery(this).next().val(value);
					}
				}else{
					if(value >= 0){
						jQuery(this).next().val(value);
					}
				}
			});
			jQuery('.woocommerce-variation').on('click', '.wt-quantity #wtadd_ticket', function(e) {
				var value = parseInt(jQuery(this).prev().val()) + 1;
				jQuery(this).prev().val(value);
			});
			*/
			
			
			jQuery('body').on('click', '.wt-quantity #wtminus_ticket', function(e) {
				var value = parseInt(jQuery(this).next().val()) - 1;
				var $_min = jQuery(this).next().data('min');
				if(jQuery.isNumeric( $_min ) && value < $_min){ value = $_min;}
				if(jQuery(this).next().attr('name')=='wt_number_adult'){
					if(value > 0){
						jQuery(this).next().val(value);
					}
				}else{
					if(value >= 0){
						jQuery(this).next().val(value);
					}
				}
				if(!jQuery(this).next().hasClass('wt-qf')){
					var value = parseInt(jQuery(this).next().children('[type="text"]').val()) - 1;
					if(jQuery(this).next().children('[type="text"]').attr('name')=='wt_number_adult'){
						if(value > 0){
							jQuery(this).next().children('[type="text"]').val(value);
						}
					}else{
						if(value >= 0){
							jQuery(this).next().children('[type="text"]').val(value);
						}
					}
				}
				jQuery(this).trigger('keyup');
			});
			jQuery('body').on('click', '.wt-quantity #wtadd_ticket', function(e) {
				var value = parseInt(jQuery(this).prev().val()) + 1;

				var $_max = jQuery(this).prev().data('max');
				if(jQuery.isNumeric( $_max ) && value > $_max){ value = $_max;}

				jQuery(this).prev().val(value);
				if(!jQuery(this).prev().hasClass('wt-qf')){
					var value = parseInt(jQuery(this).prev().children('[type="text"]').val()) + 1;
					jQuery(this).prev().children('[type="text"]').val(value);
				}
				
			});
			jQuery('body').on("propertychange change keyup paste input", '.wt-quantity [type="text"]', function(){
			    var $_max = jQuery(this).data('max');
			    var $_min = jQuery(this).data('min');
			    if(jQuery.isNumeric( $_max ) && jQuery(this).val() > $_max){ jQuery(this).val($_max);}
			    if(jQuery.isNumeric( $_min ) && jQuery(this).val() < $_min){ jQuery(this).val($_min);}
			});
			
			/* Ajax add session*/
			jQuery('html').on('click','.single-product form.cart .single_add_to_cart_button, .single-product form.cart .kad_add_to_cart, .single-product form.cart button.add_to_cart_button',function(event) {
				//event.preventDefault();
				//event.stopPropagation();
				//$(this).addClass('loading');
				return;
				var $this = $(this);
				var $form = $this.closest('form.cart');
				if($this.hasClass('wt-redr') || $form.find("input[name=wt_date]").length <= 0){ return;}
				$this.addClass('loading');
				$this.addClass('wt-redr');
				//$this.closest('.cart').addClass('loading');
				/*code to add validation, if any*/
				/* If all values are proper, then send AJAX request*/
				var wt_date = $form.find('input[name=wt_date]').val();
				var wt_sldate = $form.find('input[name=wt_sldate]').val();
				var wt_number_adult = $form.find('[name=wt_number_adult]').val();
				wt_number_adult = wt_number_adult > 0 ? wt_number_adult : '';
				var wt_number_child = $form.find('[name=wt_number_child]').val();
				wt_number_child = wt_number_child > 0 ? wt_number_child : '';
				var wt_number_infant = $form.find('[name=wt_number_infant]').val();
				wt_number_infant = wt_number_infant > 0 ? wt_number_infant : '';
				
				var wt_number_ct1 = $form.find('[name=wt_number_ct1]').val();
				wt_number_ct1 = wt_number_ct1 > 0 ? wt_number_ct1 : '';
				var wt_number_ct2 = $form.find('[name=wt_number_ct2]').val();
				wt_number_ct2 = wt_number_ct2 > 0 ? wt_number_ct2 : '';
				
				var ajaxurl = $form.find('input[name=wt_ajax_url]').val();
				//alert(picker.get('select').pick/1000);
				jQuery.ajax({
					url: ajaxurl,
					type: "POST",
					data: {
						action:'wdm_add_user_custom_data_options',
						wt_date : wt_date,
						wt_number_adult : wt_number_adult,
						wt_number_child : wt_number_child,
						wt_number_infant : wt_number_infant,
						wt_number_ct1 : wt_number_ct1,
						wt_number_ct2 : wt_number_ct2,
						wt_sldate : wt_sldate,
					},
					success: function(data){
						$this.trigger( "click" );
						//$this.removeClass('loading');
					}
				});
				return false;
			})
			/* Carousel */
			ex_carousel();
			
			/* Front end time picker */
			function exwt_date_picker(){
				$("form.cart:not(.exdpk-initialized)").each(function(){
					var $this = $(this);
					$this.addClass('exdpk-initialized');
					if($this.find(".tour-info-select input[name=wt_date]:not(.wt-custom-date)").length>0){
						$this.find(".tour-info-select input[name=wt_date] + i").click(function() {
							event.preventDefault();
		    				$this.find('.tour-info-select input[name=wt_date]').focus();
						});
						var $day_disable = $this.find('.tour-info-select input[name=wt_weekday_disable]').val();
						var $date_disable = $this.find('.tour-info-select input[name=wt_date_disable]').val();
						var $wt_cust_date = $this.find('.tour-info-select input[name=wt_cust_date]').val();
						var $wt_cust_datefm = $this.find('.tour-info-select input[name=wt_cust_datefm]').val();
						var $wt_expired = $this.find('.tour-info-select input[name=wt_expired]').val();
						var $wt_book_before = $this.find('.tour-info-select input[name=wt_book_before]').val();
						var $wt_firstday = $this.find('.tour-info-select input[name=wt_firstday]').val();
						var datepicker_language = $this.find('.tour-info-select input[name=wt_langu]').val();
						var datepicker_format = $this.find('.tour-info-select input[name=wt_datefm]').val();
						if(datepicker_format==''){ datepicker_format = 'd mmmm, yyyy';}
						$day_disable = $.parseJSON($day_disable);
						$date_disable = $.parseJSON($date_disable);
						if($wt_cust_date==''){ $wt_cust_date='[]';}
						$cust_date = $.parseJSON($wt_cust_date);
						$cust_datefm = $.parseJSON($wt_cust_datefm);
						if(datepicker_language ==''){
							$wt_daytrsl = $this.find('.tour-info-select input[name=wt_daytrsl]').val();
							$wt_montrsl = $this.find('.tour-info-select input[name=wt_montrsl]').val();
							$wt_montrsl = $.parseJSON($wt_montrsl);
							$wt_daytrsl = $.parseJSON($wt_daytrsl);
							var $trsl_m =[];
							$.each($wt_montrsl, function(i, item) {
								$trsl_m.push(item);
							});
							var $trsl_d =[];
							$.each($wt_daytrsl, function(i, item) {
								$trsl_d.push(item);
							});
							var  $input = $this.find(".tour-info-select input[name=wt_date]").pickadate({
								monthsFull:$trsl_m,
								weekdaysShort:$trsl_d,
								firstDay: $wt_firstday!='' ? true : false,
								//container: '.wt-departure',
								selectYears: true,
								selectMonths: true,
								min: true,
								//max: + $wt_expired,
								format: datepicker_format,
								onSet: function(thingSet) {
									wt_disable_bf_hour();
									if (typeof thingSet.select !== 'undefined') {
										var wt_sldate = picker.get('select','yyyy_mm_dd');
										$this.find(".tour-info-select input[name=wt_sldate]").val(wt_sldate);
										wt_check_ticket_status(wt_sldate,$this);
										exwt_variation_days($this,picker);
									}
								},
								onRender: function() {
									wt_disable_weekday_season();
									wt_disable_bf_hour();
									if($wt_cust_date!=''){
										$this.find('.wt-departure .picker__table tbody .picker__day').each(function(){
											var $this_cl = $(this);
											/*$data_pick = $this_cl.data('pick')/1000;
											$.each($cust_date, function(i, item) {
												if((item < $data_pick) && ($data_pick < (item*1 + 86400))){
													$($this_cl).removeClass('picker__day--disabled');
												}
											});*/
											$data_date = $this_cl.data('date');
											$.each($cust_datefm, function(i, item) {
												if((item == $data_date)){
													$($this_cl).removeClass('picker__day--disabled');
												}
											});
										});
									}
								},
							});
						}else{
							var  $input = $this.find(".tour-info-select input[name=wt_date]").pickadate({
								//container: '.wt-departure',
								firstDay: $wt_firstday!='' ? true : false,
								selectYears: true,
								selectMonths: true,
								min: true,
								//max: + $wt_expired,
								format: datepicker_format,
								onSet: function(thingSet) {
									wt_disable_bf_hour();
									if (typeof thingSet.select !== 'undefined') {
										var wt_sldate = picker.get('select','yyyy_mm_dd');
										$this.find(".tour-info-select input[name=wt_sldate]").val(wt_sldate);
										wt_check_ticket_status(wt_sldate,$this);
										exwt_variation_days($this,picker);
									}
								},
								onRender: function() {
									wt_disable_bf_hour();
									if($wt_cust_date!=''){
										$this.find('.wt-departure .picker__table tbody .picker__day').each(function(){
											var $this_cl = $(this);
											/*$data_pick = $this_cl.data('pick')/1000;
											$.each($cust_date, function(i, item) {
												if((item < $data_pick) && ($data_pick < (item*1 + 86400))){
													$($this_cl).removeClass('picker__day--disabled');
												}
											});*/
											$data_date = $this_cl.data('date');
											$.each($cust_datefm, function(i, item) {
												if((item == $data_date)){
													$($this_cl).removeClass('picker__day--disabled');
												}
											});
										});
									}
								},
							});
						}
						var $disable =[];
						/* disable date*/
						$.each($date_disable, function(i, item) {
							var date_ds = new Date(item * 1000)
							var userTimezoneOffset = date_ds.getTimezoneOffset() * 60000;
							var rmtzdate = new Date(date_ds.getTime() + userTimezoneOffset);
							$disable.push(rmtzdate);
						});
						/* Disable day*/
						$.each($day_disable, function(i, item) {
							$disable.push(item);
						});
						
						var picker = $input.pickadate('picker');
						var isSafari = /^((?!chrome|android).)*safari/i.test(navigator.userAgent);
						if($wt_expired!=''){
							if (isSafari){
							  $wt_expired = $wt_expired.replace(/-/g, '/');
							}
							picker.set( 'max', new Date($wt_expired) );
						}
						if($wt_book_before!=''){
							//if (isSafari){
							$wt_book_before = $wt_book_before.replace(/-/g, '/');
							//}
							picker.set('min', new Date($wt_book_before));
						}
						if($wt_cust_date!='' && $wt_cust_date!='[]' && (typeof $wt_cust_date != 'undefined')){
							if($day_disable!='' && $day_disable!='[]' && (typeof $day_disable != 'undefined')){
								picker.set('disable', $disable);
							}else{
								picker.set('disable', true);
							}
						}else{
							picker.set('disable', $disable);
						}
						/*
						jQuery(".tour-info-select input[name=wt_date]").datepicker({
							"language": datepicker_language,
							"todayHighlight" : true,
							datesDisabled: ['01/06/2017', '01/21/2017'],
							"daysOfWeekDisabled": $day_disable,
							"allowInputToggle": true,
							"startDate": new Date(),
							"endDate": $wt_expired,
							"autoclose": true,
						});
						*/
						$this.find(".wt-departure >span").on('click', function (e) {
							e.stopPropagation();
							// prevent the default click action
							e.preventDefault();
							// open the date picker
							picker.open();
						});
						
					}else if($this.find(".tour-info-select input[name=wt_date].wt-custom-date").length>0){
						$this.find(".tour-info-select .wt-list-date + i").click(function() {
							if($this.find(".tour-info-select .wt-list-date").hasClass('active')){
								$this.find(".tour-info-select .wt-list-date").removeClass('active');
							}else{
								$this.find(".tour-info-select .wt-list-date").addClass('active');
							}
						});
						$this.find(".tour-info-select .wt-list-date li").click(function() {
							$this.find(".tour-info-select input[name=wt_date].wt-custom-date").val($(this).data('value'));
							$this.find(".tour-info-select input[name=wt_sldate]").val($(this).data('date'));
							wt_check_ticket_status($(this).data('date'),$this);
							$this.find(".tour-info-select .wt-list-date").removeClass('active');
						});
						jQuery(document).on('click', function (e) {
							if ($this.find(e.target).closest(".wt-bticon").length === 0 && $this.find(e.target).closest(".wt-custom-date").length === 0) {
								$this.find(".wt-list-date").removeClass('active');
							}
						});
					}

				});

			}
			exwt_date_picker();
			if($('body').hasClass('theme-porto')){
				setTimeout(function(){
					exwt_date_picker();
				}, 500);	
			}
			// end date picker function
			
			jQuery(".wt-departure .wt-custom-date").on('click', function (e) {
					jQuery(".wt-list-date").addClass('active');
			});
			//search
			jQuery(".wt-search-dropdown:not(.wt-sfilter)").on('click', 'li a', function(){
				jQuery(".wt-search-dropdown:not(.wt-sfilter) .wt-search-dropdown-button .button-label").html(jQuery(this).text());
				jQuery(".wt-product-cat").val(jQuery(this).data('value'));
				jQuery(".wt-search-dropdown").removeClass('open');
				return false;
			});
			jQuery(".button-scroll, .exwt-button-scroll").click(function() {
				var $scrtop = jQuery(".summary,.exwt-booking-form").offset().top;
				jQuery('html, body').animate({
					scrollTop: ($scrtop-100)
				}, 500);
			});
			jQuery(".wt-discount-sif .dc-it").click(function() {
				var idscr = $(this).data('scroll');
				var $scrtop = jQuery("#"+idscr).offset().top;
				jQuery('html, body').animate({
					scrollTop: ($scrtop-100)
				}, 500);
			});
			var wt_hash = window.location.hash;
			if(wt_hash=='#tab-pricetb_tab'){
				setTimeout(function(){ 
					var idscr = $('.pricetb_tab_tab a');
					idscr.trigger('click');
					var $scrtop = idscr.offset().top;
					jQuery('html, body').animate({
						scrollTop: ($scrtop-100)
					}, 500);
				}, 500);
				
			}
			jQuery(".tbss-viewprice").click(function() {
				var idscr = $('.pricetb_tab_tab a');
				idscr.trigger('click');
				var $scrtop = idscr.offset().top;
				jQuery('html, body').animate({
					scrollTop: ($scrtop-100)
				}, 500);
			});
			jQuery(".tb-ssbt").click(function() {
				var idscr = $('form.cart');
				var $scrtop = idscr.offset().top;
				jQuery('html, body').animate({
					scrollTop: ($scrtop-250)
				}, 500);
			});
			
			$('.exwt-input-group-btn:not(.wt-sfilter)').on('click', function(e) {
				$menu = $(this);
				//e.preventDefault();
				$('.wt-search-dropdown').removeClass('open');
				if (!$menu.hasClass('open')) {
					$menu.addClass('open');
			
					$(document).one('click', function closeTooltip(e) {
						if ($menu.has(e.target).length === 0 && $('.exwt-input-group-btn').has(e.target).length === 0) {
							$menu.removeClass('open');
						} else if ($menu.hasClass('open')) {
							$(document).one('click', closeTooltip);
						}
					});
				} else {
					//$menu.removeClass('open');
				}
			}); 
			
			$('.exwt-input-group-btn.wt-sfilter').on('click', function(e) {
				$this = $(this);
				if(!$this.hasClass('wt-sfilter-close')){
					$this.addClass('wt-sfilter-close');
					$('.wt-filter-expand').addClass('active');
				}else{
					$this = $(this);
					$this.removeClass('wt-sfilter-close');
					$('.wt-filter-expand').removeClass('active');
				}
				//e.preventDefault();
			});
			// ajax load morer
			$('.loadmore-grid').on('click',function() {
				var $this_click = $(this);
				if($this_click.hasClass('table-loadmore') || $this_click.hasClass('lm-list')){ return;}
				$this_click.addClass('disable-click');
				var id_crsc  		= $this_click.data('id');
				var n_page = $('#'+id_crsc+' input[name=num_page_uu]').val();
				//alert(n_page);
				$('#'+id_crsc+' .loadmore-grid').addClass("loading");
				var param_query  		= $('#'+id_crsc+' input[name=param_query]').val();
				var page  		= $('#'+id_crsc+' input[name=current_page]').val();
				var num_page  		= $('#'+id_crsc+' input[name=num_page]').val();
				var ajax_url  		= $('#'+id_crsc+' input[name=ajax_url]').val();
				//alert(num_page);
				var param_shortcode  		= $('#'+id_crsc+' input[name=param_shortcode]').val();
				//alert(param_shortcode);
					var param = {
						action: 'ex_loadmore_grid',
						param_query: param_query,
						page: page*1+1,
						param_shortcode: param_shortcode,
					};
		
					$.ajax({
						type: "post",
						url: ajax_url,
						dataType: 'html',
						data: (param),
						success: function(data){
							if(data != '0')
							{
								n_page = n_page*1+1;
								$('#'+id_crsc+' input[name=num_page_uu]').val(n_page)
								if(data == ''){ 
									$('#'+id_crsc+' .loadmore-grid').remove();
								}
								else{
									$('#'+id_crsc+' input[name=current_page]').val(page*1+1);
									var $g_container = $('#'+id_crsc+' .grid-container');
									if($('#'+id_crsc).hasClass('wt-grid-column-1')){
										$g_container.append(data);
									}else{
										$g_container.append(data).imagesLoaded( function() {
											$g_container.masonry('reloadItems');
											$g_container.masonry({
												isInitLayout : false,
												itemSelector: '.item-post-n'
											});
										});
									}
									$('#'+id_crsc+' .loadmore-grid').removeClass("loading");
									$this_click.removeClass('disable-click');
								}
								if(n_page == num_page){
									$('#'+id_crsc+' .loadmore-grid').remove();
								}
								
							}else{$('.exwt-row.loadmore').html('error');}
						}
					});
				return false;	
			});
			// ajax load morer
			$('.loadmore-grid.lm-list').on('click',function() {
				var $this_click = $(this);
				$this_click.addClass('disable-click');
				var id_crsc  		= $this_click.data('id');
				var n_page = $('#'+id_crsc+' input[name=num_page_uu]').val();
				//alert(n_page);
				$('#'+id_crsc+' .loadmore-grid').addClass("loading");
				var param_query  		= $('#'+id_crsc+' input[name=param_query]').val();
				var page  		= $('#'+id_crsc+' input[name=current_page]').val();
				var num_page  		= $('#'+id_crsc+' input[name=num_page]').val();
				var ajax_url  		= $('#'+id_crsc+' input[name=ajax_url]').val();
				//alert(num_page);
				var param_shortcode  		= $('#'+id_crsc+' input[name=param_shortcode]').val();
				//alert(param_shortcode);
					var param = {
						action: 'ex_loadmore_list',
						param_query: param_query,
						page: page*1+1,
						param_shortcode: param_shortcode,
					};
		
					$.ajax({
						type: "post",
						url: ajax_url,
						dataType: 'html',
						data: (param),
						success: function(data){
							if(data != '0')
							{
								n_page = n_page*1+1;
								$('#'+id_crsc+' input[name=num_page_uu]').val(n_page)
								if(data == ''){ 
									$('#'+id_crsc+' .loadmore-grid').remove();
								}
								else{
									$('#'+id_crsc+' input[name=current_page]').val(page*1+1);
									var $g_container = $('#'+id_crsc+' .grid-container');
									if($('#'+id_crsc).hasClass('wt-grid-column-1')){
										$g_container.append(data);
									}else{
										$g_container.append(data).imagesLoaded( function() {
											$g_container.masonry('reloadItems');
											$g_container.masonry({
												isInitLayout : false,
												itemSelector: '.item-post-n'
											});
										});
									}
									$('#'+id_crsc+' .loadmore-grid').removeClass("loading");
									$this_click.removeClass('disable-click');
								}
								if(n_page == num_page){
									$('#'+id_crsc+' .loadmore-grid').remove();
								}
								
							}else{$('.exwt-row.loadmore').html('error');}
						}
					});
				return false;	
			});
			//table load
			$('.loadmore-grid.table-loadmore').on('click',function() {
				var $this_click = $(this);
				$this_click.addClass('disable-click');
				var id_crsc  		= $this_click.data('id');
				var n_page = $('#'+id_crsc+' input[name=num_page_uu]').val();
				//alert(n_page);
				$('#'+id_crsc+' .loadmore-grid').addClass("loading");
				var param_query  		= $('#'+id_crsc+' input[name=param_query]').val();
				var page  		= $('#'+id_crsc+' input[name=current_page]').val();
				var num_page  		= $('#'+id_crsc+' input[name=num_page]').val();
				var ajax_url  		= $('#'+id_crsc+' input[name=ajax_url]').val();
				//alert(num_page);
				var param_shortcode  		= $('#'+id_crsc+' input[name=param_shortcode]').val();
				//alert(param_shortcode);
					var param = {
						action: 'ex_loadmore_table',
						param_query: param_query,
						page: page*1+1,
						param_shortcode: param_shortcode,
					};
		
					$.ajax({
						type: "post",
						url: ajax_url,
						dataType: 'html',
						data: (param),
						success: function(data){
							if(data != '0')
							{
								n_page = n_page*1+1;
								$('#'+id_crsc+' input[name=num_page_uu]').val(n_page)
								if(data == ''){ 
									$('#'+id_crsc+' .loadmore-grid').remove();
								}
								else{
									$('#'+id_crsc+' input[name=current_page]').val(page*1+1);
									var $g_container = $('#'+id_crsc+' tbody');
									$g_container.append(data);
									setTimeout(function(){ 
										$('#'+id_crsc+' tbody .tb-load-item').addClass("active");
									}, 200);
									$('#'+id_crsc+' .loadmore-grid').removeClass("loading");
									$this_click.removeClass('disable-click');
								}
								if(n_page == num_page){
									$('#'+id_crsc+' .loadmore-grid').remove();
								}
								
							}else{$('.exwt-row.loadmore').html('error');}
						}
					});
				return false;	
			});
			
			$('#wt-ajax-search button.wt-search-submit').on('click',function() {
				var $this_click = $(this);
				var id_crsc  		= $this_click.data('id');
				$this_click.addClass('disable-click');
				wt_ajax_search(id_crsc,$this_click);
				return false;	
			});
			if(jQuery(".woocommerce-cart:not(.wt-unremove-qtn) table.shop_table tbody tr:last-child td.actions").length>0){
				var col_nb = $(".woocommerce-cart:not(.wt-unremove-qtn) table.shop_table tbody tr:last-child td.actions").attr("colspan");
				if(col_nb==6){
					$(".woocommerce-cart:not(.wt-unremove-qtn) table.shop_table tbody tr:last-child td.actions").attr('colspan',5)
				}
			};

			/*-Search ajax shortcode-*/
			function wt_ajax_search_sc(id_crsc,$page){
				$('.'+id_crsc).addClass('loading');
				var result_showin  		= $('#'+id_crsc+' input[name=result_showin]').val();
				var key_word  		= $('#'+id_crsc+' .wt-product-search-form input[name=s]').val();
				var cat  		= $('#'+id_crsc+' select[name=product_cat]').val();
				var tag  		= $('#'+id_crsc+' select[name=product_tag]').val();
				var location  		= $('#'+id_crsc+' select[name=wt_location]').val();
				var ajax_url  		= $('#'+id_crsc+' input[name=ajax_url]').val();
				var search_layout  		= $('#'+id_crsc+' input[name=search_layout]').val();
				var param = {
					action: 'wt_ajax_search',
					key_word: key_word,
					cat: cat,
					tag: tag,
					location: location,
					page: $page,
					layout: search_layout,
					idsc: id_crsc,
				};
				$.ajax({
					type: "post",
					url: ajax_url,
					dataType: 'html',
					data: (param),
					success: function(data){
						if(data != '0')
						{
							if(data == ''){ 
							}
							else{
								//$this.removeClass('disable-click');
								if($('.'+id_crsc+' '+result_showin).length){
									$showin = $('.'+id_crsc+' '+result_showin);
								}else{ $showin = $(result_showin); }
								$($showin).fadeOut({
									duration:0,
									complete:function(){
										$( this ).empty();
									}
								});
								$('body').addClass('wt-ajax-mode');
								$('.'+id_crsc).removeClass("loading");
								
								$showin.append(data).fadeIn();
							}
						}else{ alert('error');}
					}
				});
				return false;
			}
			$.urlParam = function(name,url){
				var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(url);
				if (results==null){
				   return null;
				}
				else{
				   return decodeURI(results[1]) || 0;
				}
			}
			$('body').on('click', '.wt-ajax-pagination li a.page-numbers', function(event) {
				$id  = $(this).closest('.wt-ajax-pagination').attr('id');
				$(this).closest('.wt-ajax-dfrs').addClass('loading');
				$('#'+$id+' li .page-numbers').removeClass('current');
				$(this).addClass('current');
				event.preventDefault();
				if(!$(this).hasClass('disable-click')){
					$(this).addClass('disable-click');
					$id = $(this).closest('.wt-ajax-pagination').data('id');
					wt_ajax_search_sc($id,$.urlParam('paged',$(this).attr('href')));
				}
			});
			$('.wt-search-form.we-ajax-search button.wt-product-search-submit').on('click',function() {
				event.preventDefault();
				var $this_click = $(this);
				var id_crsc  		= $this_click.data('id');
				$('.'+id_crsc).addClass('remove-view-tb');
				$('.'+id_crsc+' .wt-search-dropdown').removeClass('open');
				wt_ajax_search_sc(id_crsc,'');
				return false;	
			});
			// search by date
			if(jQuery(".wt-search-modern input[name=date]").length>0){
				var datepicker_language = jQuery('.wt-search-modern input[name=wt_langu]').val();
				if(datepicker_language ==''){
					$wt_daytrsl = jQuery('.wt-search-modern input[name=wt_daytrsl]').val();
					$wt_montrsl = jQuery('.wt-search-modern input[name=wt_montrsl]').val();
					$wt_montrsl = $.parseJSON($wt_montrsl);
					$wt_daytrsl = $.parseJSON($wt_daytrsl);
					var $trsl_m =[];
					$.each($wt_montrsl, function(i, item) {
						$trsl_m.push(item);
					});
					var $trsl_d =[];
					$.each($wt_daytrsl, function(i, item) {
						$trsl_d.push(item);
					});
					var  $input_s = jQuery(".wt-search-modern input[name=date]").pickadate({
							monthsFull:$trsl_m,
							weekdaysShort:$trsl_d,
							container: '.wt-search-modern .wt-sdt',
							selectYears: true,
							selectMonths: true,
							min: true,
							//max: + $wt_expired,
							format: 'dd/mm/yyyy',
					});	
				}else{
					var  $input_s = jQuery(".wt-search-modern input[name=date]").pickadate({
							container: '.wt-search-modern .wt-sdt',
							selectYears: true,
							selectMonths: true,
							min: true,
							//max: + $wt_expired,
							format: 'dd/mm/yyyy',
					});
				}
				var picker = $input_s.pickadate('picker');
			}
			$(".wt-search-form #searchform").submit(function(){
				$(this).find("input[name=date_submit]").attr("disabled", "disabled");
      			return true; // ensure form still submits
			});
	});
	$(document).ajaxSuccess(function() {
		if(jQuery(".woocommerce-cart:not(.wt-unremove-qtn) table.shop_table tbody tr:last-child td.actions").length>0){
			var col_nb = $(".woocommerce-cart:not(.wt-unremove-qtn) table.shop_table tbody tr:last-child td.actions").attr("colspan");
			if(col_nb==6){
				$(".woocommerce-cart:not(.wt-unremove-qtn) table.shop_table tbody tr:last-child td.actions").attr('colspan',5)
			}
		};
	});
	jQuery( document ).on( "found_variation.first", function ( e, variation ) {
		if(variation._max_adult!='' || !isNaN(variation._max_adult)){
			var $max = variation._max_adult!='' && !isNaN(variation._max_adult) ? variation._max_adult : 0;
			var $min = variation._min_adult!='' && !isNaN(variation._min_adult) ? variation._min_adult : 0;
			if($('select[name=wt_number_adult]').length){
				$('select[name=wt_number_adult] option').each(function(){
					if( ($min!== 0 && parseInt($(this).val())) < $min || ($max!== 0 && parseInt($(this).val()) > $max) ){
						$(this).remove();
					}
				});
				$('[name=wt_number_adult]').trigger('change');
			}else if($('.wt-quantity [name=wt_number_adult]').length){
				if($min!=0){ 
					$('.wt-quantity [name=wt_number_adult]').val(variation._min_adult);
					$('.wt-quantity [name=wt_number_adult]').attr({
				       "min" : $min,
				       "data-min" : $min,
				    });
				}
				if($max!=0){ 
					$('.wt-quantity [name=wt_number_adult]').attr({
				       "max" : $max,
				       "data-max" : $max,
				    });
				}
				$('.wt-quantity [name=wt_number_adult]').trigger('keyup');
			}
		}
	} );
}(jQuery));

/*jQuery(document).ready(function($) {
	var dheight = $('.flex-viewport .woocommerce-product-gallery__wrapper > div:first-child img').height();
	$('.flex-viewport').css('height',dheight);
	setTimeout(function(){
		var dheight = $('.flex-viewport .woocommerce-product-gallery__wrapper > div:first-child').height(); 
		$('.flex-viewport').css('height',dheight);
	}, 200);
	setTimeout(function(){ 
		var dheight = $('.flex-viewport .woocommerce-product-gallery__wrapper > div:first-child').height();
		$('.flex-viewport').css('height',dheight);
	}, 500);
});
jQuery(window).load(function($) {
	var dheight = $('.flex-viewport .woocommerce-product-gallery__wrapper > div:first-child img').height();
	$('.flex-viewport').css('height',dheight);
	setTimeout(function(){
		var dheight = $('.flex-viewport .woocommerce-product-gallery__wrapper > div:first-child').height(); 
		$('.flex-viewport').css('height',dheight);
	}, 200);
	setTimeout(function(){ 
		var dheight = $('.flex-viewport .woocommerce-product-gallery__wrapper > div:first-child').height();
		$('.flex-viewport').css('height',dheight);
	}, 500);
});
jQuery(document).ready(function($) {
	$(".gr-product input,.gr-product select").each(function(){
		var name = $(this).attr('name');
		var val = $(this).val();
		$('form[name="checkout"]').append('<input type="hidden" name= "'+name+'" value="'+val+'" />');
	});
	$('body').on('change', '.gr-product input,.gr-product select', function() {
		var name = $(this).attr('name');
		var type = $(this).attr('type');
		var val = $(this).val();
		$('form[name="checkout"] > input[name="'+name+'"]').val(val);
	});
});
*/