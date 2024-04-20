;(function($){
	$(document).ready(function() {
	
		var wt_layout_purpose_obj  = jQuery('.postbox-container #wt_layout_purpose select');
		var wt_layout_purpose = jQuery('.postbox-container #wt_layout_purpose select').val();
		var wt_time_settings = jQuery('#time-settings.postbox');
		var wt_tour_settings = jQuery('#tour-info.postbox');
		
		var wt_add_settings = jQuery('#additional-information.postbox');
		var wt_lay_settings = jQuery('#layout-settings.postbox');
		var wt_ct_settings = jQuery('#custom-field.postbox');
		if(typeof(wt_layout_purpose)!='undefined'){
			if(wt_layout_purpose == 'tour'){
				wt_time_settings.show();
				wt_tour_settings.show();
				wt_add_settings.show();
				wt_lay_settings.show();
				wt_ct_settings.show();
			}else if(wt_layout_purpose == 'woo'){				
				wt_time_settings.hide();
				wt_tour_settings.hide();
				wt_add_settings.hide();
				wt_lay_settings.hide();
				wt_ct_settings.hide();
			}else if(wt_layout_purpose == 'def'){				
				wt_time_settings.css("display","");
				wt_tour_settings.css("display","");
				wt_add_settings.css("display","");
				wt_lay_settings.css("display","");
				wt_ct_settings.css("display","");
			}
			wt_layout_purpose_obj.change(function(event) {
				if(jQuery(this).val() == 'tour'){
					wt_time_settings.show(200);
					wt_tour_settings.show(200);
					wt_add_settings.show(200);
					wt_lay_settings.show(200);
					wt_ct_settings.show(200);
				}else if(jQuery(this).val() == 'woo'){
					wt_time_settings.hide(200);
					wt_tour_settings.hide(200);
					wt_add_settings.hide(200);
					wt_lay_settings.hide(200);
					wt_ct_settings.hide(200);
				}else if(wt_layout_purpose == 'def'){				
					wt_time_settings.css("display","");
					wt_tour_settings.css("display","");
					wt_add_settings.css("display","");
					wt_lay_settings.css("display","");
					wt_ct_settings.css("display","");
				}
			});
		}
		jQuery(document).on('change', '#wt_disc_start .field-item .exc_mb_datepicker:first-child', function() {
			fieldItem = jQuery(this).closest('.exc_mb-row' );
			jQuery('#wt_disc_end .field-item .exc_mb_datepicker:first-child', fieldItem).val(this.value);
		});
		var wt_ctfieldprice_obj  = jQuery('#wootours #wt_ctfieldprice');
		var wt_ctfieldprice = jQuery('#wootours #wt_ctfieldprice').val();
		var wt_ctfield1_info  = jQuery('#wootours #wt_ctfield1_info');
		var wt_ctfield2_info  = jQuery('#wootours #wt_ctfield2_info');
		if(typeof(wt_ctfieldprice_obj)!='undefined'){
			ct1_pr = jQuery(wt_ctfield1_info).closest('tr' );
			ct2_pr = jQuery(wt_ctfield2_info).closest('tr' );
			if(wt_ctfieldprice == true){
				ct1_pr.show(200);
				ct2_pr.show(200);
			}else{
				ct1_pr.hide(200);
				ct2_pr.hide(200);
			}
			wt_ctfieldprice_obj.change(function(event) {
				if(jQuery(this).val() == true){
					ct1_pr.show(200);
					ct2_pr.show(200);
				}else{
					ct1_pr.hide(200);
					ct2_pr.hide(200);
				}
			});
		}
		var wt_disc_bo_obj = jQuery('#discount #wt_disc_bo select');
		var wt_disc_bo = jQuery('#discount #wt_disc_bo select').val();
		var discount  = jQuery('.postbox-container #discount');
		if(typeof(wt_disc_bo)!='undefined'){
			if(wt_disc_bo == 'season'){
				discount.addClass('hide-adf');
			}
			wt_disc_bo_obj.change(function(event) {
				if(jQuery(this).val() == 'season'){
					discount.addClass('hide-adf');
				}else{
					discount.removeClass('hide-adf');
				}
			});
		}
		var product_type  = jQuery('.post-type-product .postbox-container select#product-type').val();
		var $variation_op = jQuery('.post-type-product .postbox-container #wt_p_variation select');
		if(product_type!='variable'){
			$variation_op.val('');
			$variation_op.closest('.exc_mb-row').fadeOut();
		}else if(!$variation_op.has('option').length){
			$variation_op.closest('.exc_mb-row').fadeOut();
		}
		jQuery('body').on('click', '.exwt-edit-attendee', function() {
			var _next = jQuery(this).closest('p').next();
			if(!_next.hasClass('ex-active')){
				_next.fadeIn().addClass('ex-active');
			}else{
				_next.fadeOut().removeClass('ex-active');
			}
		});
		jQuery('body').on('click', '.exwt-save-att:not(.exwf-custom_action)', function() {
			var _attes = jQuery(this).closest('.wt-add-passenger-infos');
			_attes.addClass('ex-loading');
			var _all_attes = [];
			_attes.find('.wt-add-passenger-info').each(function(){
				var _this_attes = $(this);
				var _fname = _this_attes.find('.att-fname input').val();
				var _lname = _this_attes.find('.att-lname input').val();
				var _email = _this_attes.find('.att-email input').val();
				var _dd = _this_attes.find('.att-dd input').val();
				var _mm = _this_attes.find('.att-mm input').val();
				var _yy = _this_attes.find('.att-yy input').val();
				var _gend = _this_attes.find('.att-gend select').val();
				var _att = [];
				_att.push(_fname,_lname,_email,_dd,_mm,_yy,_gend);
				if(_this_attes.find("input[name=wt_if_ct\\[\\]]").length){
					_this_attes.find("input[name=wt_if_ct\\[\\]]").each(function() {
					  _att.push($(this).val());
					});
				}
				_all_attes.push(_att);
			});
			var product_id = jQuery('input[name=exwtproduct_id]').val();
			var order_id = jQuery('input[name=exwtorder_id]').val();
			var ajaxurl = jQuery('input[name=ajaxurl]').val();
			var key_change = _attes.find('input[name=key_change]').val();
			var _link = jQuery('input[name=_wp_original_http_referer]').val();
			var _id_ft = jQuery('#post input#post_ID').val();
			jQuery.ajax({
				url: ajaxurl,
				type: "POST",
				dataType: 'json',
				data: {
					action:'wt_admin_change_attendees',
					data_atts : _all_attes,
					product_id : product_id,
					order_id : order_id,
					key_change : key_change,
				},
				success: function(data){
					_attes.removeClass('ex-loading');
					jQuery('#the-list input[value='+key_change+']').closest('tr').find('textarea').html(data.html_content);
				}
			});
		});

		if(jQuery('.toolbar.toolbar-variations-defaults .variations-defaults > select').length > 1){
			jQuery('.toolbar.toolbar-variations-defaults .variations-defaults > select').closest('#variable_product_options').addClass('exwt-hide-disday');
			jQuery('.exwt-hide-disday .exwt-disday .checkbox').prop('checked', false);
		}
		jQuery(document).ajaxSuccess(function(event,xhr,options) {
			var urlParams = new URLSearchParams(options.data);
			var $action = urlParams.get('action');
			if($action=='woocommerce_load_variations' || $action =='woocommerce_save_attributes'){
				if(jQuery('.toolbar.toolbar-variations-defaults .variations-defaults > select').length > 1){
					jQuery('.toolbar.toolbar-variations-defaults .variations-defaults > select').closest('#variable_product_options').addClass('exwt-hide-disday');
					jQuery('.exwt-hide-disday .exwt-disday .checkbox').prop('checked', false);
				}else if(jQuery('.toolbar.toolbar-variations-defaults .variations-defaults > select').length = 1){
					jQuery('.toolbar.toolbar-variations-defaults .variations-defaults > select').closest('#variable_product_options').removeClass('exwt-hide-disday');
				}
			}
		});
	});
}(jQuery));