<?php
/**
 * Get options
 */
function exwoo_get_options($id){
	if($id ==''){ $id = get_the_ID();}
	$global_op = array();
	//Check global option
	$exclude_options = get_post_meta( $id, 'exwo_exclude_options', true );
	if($exclude_options!='on'){
		$cate = wp_get_post_terms($id,'product_cat',array( 'fields' => 'slugs' ));
		$args = array(
			'post_type'     => 'exwo_glboptions',
			'post_status'   => array( 'publish' ),
			'numberposts'   => -1,
			'suppress_filters' => true
		);
		$args['meta_query'] = array(
			array(
	            'key' => 'exwo_product_ids_arr',
	            'value' => $id,
	            'compare' => '='
	        )
		);
		$args = apply_filters('exwo_option_by_cr_ids',$args);
		$glb_oids = array();
		if(isset($args['meta_query'])){
			$glb_oids = get_posts( $args );
			$glb_oids = wp_list_pluck( $glb_oids, 'ID' );
			unset($args['meta_query']);
		}
		if(!empty($cate) && count($cate) > 0){
			$args['tax_query'] = array(
					array(
					'taxonomy'         => 'product_cat',
					'field'            => 'slug',
					'terms'            => $cate,
					'operator' => 'IN',
					'include_children'=>false,
				)
			);
		}
		//print_r($args);exit;
		$glb_otqr = get_posts( $args );
		$glb_otqr = wp_list_pluck( $glb_otqr, 'ID' );
		$glb_otqr = array_merge($glb_otqr,$glb_oids);
		if(!empty($glb_otqr) && count($glb_otqr) > 0){
			foreach ($glb_otqr as $op_item) {
				$goptions = get_post_meta( $op_item, 'exwo_options', true );
				if(!empty($goptions)){
					$global_op = array_merge($global_op,$goptions);
				}
			}
			wp_reset_postdata();
		}
	}
	// include option
	$include_options = get_post_meta( $id, 'exwo_include_options', true );
	if($include_options!=''){
		$include_options = explode(",",$include_options);
		foreach ($include_options as $in_item) {
			$goptions = get_post_meta( $in_item, 'exwo_options', true );
			if(is_array($goptions) && !empty($goptions)){
				$global_op = array_merge($global_op,$goptions);
			}
		}
	}
	if(is_array($global_op)){$global_op = array_unique($global_op,SORT_REGULAR);}
	//$glb_options = 
	$data_options = get_post_meta( $id, 'exwo_options', true );
	if(!empty($global_op)){
		if($data_options==''){$data_options=array();}
		$data_op_pos = get_post_meta( $id, 'exwo_options_pos', true );
		$pos_glbop = apply_filters('expoa_pos_global',$data_op_pos);
		if($pos_glbop=='before'){
			$data_options = array_merge($global_op,$data_options);
		}else{
			$data_options = array_merge($data_options,$global_op);
		}
	}
	return $data_options;
}
/**
 * Add the field to add to cart form
 */
function exwoo_display_custom_field() {
	global $post, $cart_itemkey;
	$data_edit = '';
	if($cart_itemkey!=''){
		$cart = WC()->cart->get_cart();
		if(isset($cart[$cart_itemkey]['data_edit']) && $cart[$cart_itemkey]['data_edit']!='' ){
			$data_edit = $cart[$cart_itemkey]['data_edit'];
			echo '<input type="hidden" name="exwf-up-cartitem" value="'.esc_attr($cart_itemkey).'"/>';
		}
	}
	$text_domain = exwo_text_domain();
	// Check for the custom field value
	$data_options = exwoo_get_options($post->ID);//echo '<pre>'; print_r($data_options);exit;
	if(is_array($data_options) && !empty($data_options)){
		$i = 0;
		$show_more = apply_filters( 'exwo_show_more_option_button', 0 );
		$cls = $show_more=='1' ? 'exwo-hide-options' : '';
		$accordion_style = apply_filters( 'exwo_accordion_style', 0 );
		$cls = $accordion_style=='1' ? 'exwo-accordion-style' : '';
		$exid = 'exot_'.rand(10000,10000000000);
		echo '<div class="exwo-product-gr-options" id="'.$exid.'">';
		$j=0;
		$logic_js =  $extralg_js = '';
		foreach ($data_options as $item) {
			$cls = $accordion_style=='1' ? 'exwo-accordion-style' : '';
			$display_type = isset($item['_display_type'])  ? $item['_display_type'] : '';
			if($display_type =='accor'){
				$cls = 'exwo-accordion-style';
			}else if($display_type=='nor'){
				$cls = '';
			}
			echo '<div class="exwo-product-options '.esc_attr($cls).'">';
				//$j++;
				$el_id = isset($item['_id']) && $item['_id']!='' ? $item['_id'] : 'exwo-id'.rand(10000,10000000000);
				$el_id = $el_id.'-'.$j;
				$type = isset($item['_type']) && $item['_type']!='' ? $item['_type'] : 'checkbox';
				$required = isset($item['_required']) && $item['_required']!='' ? 'ex-required' : '';
				$min_req = $max_req = $required_m = '';
				if($type=='checkbox'){
					$min_req = isset($item['_min_op']) && $item['_min_op']!='' ? $item['_min_op'] : '';
					if(is_numeric($min_req) && $min_req > 0){
						$required_m =' ex-required-min';
					}
					$max_req = isset($item['_max_op']) && $item['_max_op']!='' ? $item['_max_op'] : '';
					if(is_numeric($max_req) && $max_req > 0){
						$required_m .=' ex-required-max';
					}
				}
				$enb_logic = isset($item['_enb_logic']) ? $item['_enb_logic'] : '';
				$plus_sign = apply_filters('exwo_plus_sign_char','+',$item);
				if($enb_logic=='on'){
					$con_logic = isset($item['_con_logic']) ? $item['_con_logic'] : '';
					$logic_rule = isset($item['_con_tlogic']) && $item['_con_tlogic']=='hide' ? 'fadeOut()' : 'fadeIn()';
					if(is_array($con_logic) && !empty($con_logic)){
						$log_option = $extralog_option =  '';
						$lg = 0;
						foreach ($con_logic as $key => $item_logic) {
							$lg ++;
							$cttype_rel = isset($item_logic['type_rel']) && $item_logic['type_rel']=='and' ? '&&' : '||';
							$ctype_con = isset($item_logic['type_con']) && $item_logic['type_con']=='is_not' ? '!=' : '==';
							$ctype_op = isset($item_logic['type_op']) && $item_logic['type_op']!='' && $item_logic['type_op']!='varia' ? $item_logic['type_op'].'-'.$j/*$el_id*/ : '$ex_variation';
							$con_val = isset($item_logic['val']) ? $item_logic['val'] : '';
							$con_val = explode("-",$con_val);
							$con_val = $con_val[0];
							if($cttype_rel!='' && $ctype_con!='' && $ctype_op!=''){
								if($ctype_op=='$ex_variation'){
									if(count($con_logic) > 1){
										if($lg==1){
											$log_option .= '$ex_variation '.$ctype_con.' "'.$con_val.'" ';
											//$log_option .= ' if($ex_variation '.$ctype_con.' "'.$con_val.'"){ jQuery("#'.$el_id.'").'.$ctype_rule.';}';
										}else{
											$log_option .= $cttype_rel.' $ex_variation '.$ctype_con.' "'.$con_val.'" ';
											//$log_option .= ' else if($ex_variation '.$ctype_con.' "'.$con_val.'"){ jQuery("#'.$el_id.'").'.$ctype_rule.';}';
										}

										if($lg== count($con_logic)){
											$log_option = 'if('.$log_option.'){ jQuery("#'.$el_id.'")'.($logic_rule == 'fadeOut()' ? '.addClass("exwf-offrq").css("display","none")' : '.removeClass("exwf-offrq").css("display","block")').';}
											else{ jQuery("#'.$el_id.'")'.($logic_rule == 'fadeOut()' ? '.removeClass("exwf-offrq").css("display","block")' : '.addClass("exwf-offrq").css("display","none")').';}';
										}
									}else{
										$log_option = ' if($ex_variation '.$ctype_con.' "'.$con_val.'"){ jQuery("#'.$el_id.'")'.($logic_rule == 'fadeOut()' ? '.addClass("exwf-offrq").css("display","none")' : '.removeClass("exwf-offrq").css("display","block")').';}
											else{ jQuery("#'.$el_id.'")'.($logic_rule == 'fadeOut()' ? '.removeClass("exwf-offrq").css("display","block")' : '.addClass("exwf-offrq").css("display","none")').';}';
									}
								}else{
									$extralog_option = ' 
										if(jQuery("#'.$exid.' #'.$ctype_op.'").hasClass("ex-checkbox")){
											var $value = [];
											jQuery.each(jQuery("#'.$exid.' #'.$ctype_op.' input:checked"), function(){
								                $value.push(jQuery(this).val());
								            });
										}else if(jQuery("#'.$exid.' #'.$ctype_op.'").hasClass("ex-textarea")){
											var $value = jQuery("#'.$exid.' #'.$exid.' #'.$ctype_op.' textarea").val();
										}else if(jQuery("#'.$exid.' #'.$ctype_op.'").hasClass("ex-radio")){
											var $value = jQuery("#'.$exid.' #'.$ctype_op.' input:checked").val();
										}else if(jQuery("#'.$exid.' #'.$ctype_op.'").hasClass("ex-select")){
											var $value = jQuery("#'.$exid.' #'.$ctype_op.' select").val();
										}else{
											var $value = jQuery("#'.$exid.' #'.$ctype_op.' input").val();
										}';
									if(count($con_logic) > 1){
										if($lg==1){
											$extralog_op = '( (jQuery.isArray($value) && jQuery.inArray("'.$con_val.'", $value) !== -1 ) ||  $value '.$ctype_con.' "'.$con_val.'") ';
										}else{
											$extralog_op .= $cttype_rel.' ( (jQuery.isArray($value) && jQuery.inArray("'.$con_val.'", $value) !== -1 ) ||  $value '.$ctype_con.' "'.$con_val.'") ';
										}

										if($lg== count($con_logic)){
											$extralog_option .= '
												if('.$extralog_op.'){ 
														jQuery("#'.$exid.' #'.$el_id.'")'.($logic_rule == 'fadeOut()' ? '.addClass("exwf-offrq").css("display","none")' : '.removeClass("exwf-offrq").css("display","block")').';}
													else{ jQuery("#'.$exid.' #'.$el_id.'")'.($logic_rule == 'fadeOut()' ? '.removeClass("exwf-offrq").css("display","block")' : '.addClass("exwf-offrq").css("display","none")').';
												}
												jQuery("#'.$exid.' #'.$el_id.' .ex-options").trigger("change");';
											$extralog_option .= '
											jQuery("body").on("change", "#'.$exid.' #'.$ctype_op.' .ex-options", function() {
												'.$extralog_option.'
												jQuery("#'.$exid.' .exwf-offrq .ex-options:not([type=radio]):not([type=checkbox])").val("");
												jQuery("#'.$exid.' .exwf-offrq .ex-options[type=radio], #'.$exid.' .exwf-offrq .ex-options[type=checkbox]").prop("checked", false);
											});
											';	
										}
									}else{
										$extralog_option .= ' 
											if( (jQuery.isArray($value) && jQuery.inArray("'.$con_val.'", $value) !== -1 ) ||  $value '.$ctype_con.' "'.$con_val.'"){ jQuery("#'.$exid.' #'.$el_id.'")'.($logic_rule == 'fadeOut()' ? '.addClass("exwf-offrq").css("display","none")' : '.removeClass("exwf-offrq").css("display","block")').';}
												else{ jQuery("#'.$exid.' #'.$el_id.'")'.($logic_rule == 'fadeOut()' ? '.removeClass("exwf-offrq").css("display","block")' : '.addClass("exwf-offrq").css("display","none")').';
											}
											';
										$extralog_option .= '
										jQuery("body").on("change", "#'.$exid.' #'.$ctype_op.' .ex-options", function() {
											'.$extralog_option.'
											jQuery("#'.$exid.' #'.$el_id.'.exwf-offrq .ex-options").trigger("change");
											jQuery("#'.$exid.' .exwf-offrq .ex-options:not([type=radio]):not([type=checkbox])").val("");
											jQuery("#'.$exid.' .exwf-offrq.ex-select select").removeClass("exwo-defed");
											jQuery("#'.$exid.' .exwf-offrq .ex-options[type=radio], #'.$exid.' .exwf-offrq .ex-options[type=checkbox]").prop("checked", false).removeClass("exwo-defed");
											if(jQuery("#'.$exid.' .ex-logic-on:not(.exwf-offrq) .ex-options[data-def=yes]").length > 0) {
												jQuery("#'.$exid.' .ex-logic-on:not(.exwf-offrq) .ex-options[data-def=yes]:not(.exwo-defed)").each(function(){
													jQuery(this).prop("checked", true).addClass("exwo-defed").trigger("change");
												});
											}
											if(jQuery("#'.$exid.' .ex-logic-on:not(.exwf-offrq) .ex-options option[data-def=yes]").length > 0) {
												jQuery("#'.$exid.' .ex-logic-on.ex-select:not(.exwf-offrq) .ex-options:not(.exwo-defed) option[data-def=yes]").each(function(){
													jQuery(this).closest("select").val(jQuery(this).attr("value")).addClass("exwo-defed").trigger("change");
												});
											}
										});
										';	
									} 
								}
							}
						}
						$logic_js .= $log_option;
						$extralg_js .= $extralog_option;
					}
				}
				echo '<div class="exrow-group ex-'.esc_attr($type).' '.esc_attr($required).' '.esc_attr($required_m).' ex-logic-'.esc_attr($enb_logic).'" data-minsl="'.$min_req.'"  data-maxsl="'.$max_req.'" id="'.$el_id.'">';
					if(isset($item['_name']) && $item['_name']){
						$price_tt = '';
						if($type =='text' || $type =='textarea' || $type =='quantity'){
							$price_tt = isset($item['_price']) && $item['_price']!='' ? wc_price(exwo_convert_number_decimal_comma($item['_price'],true)) :'';
							$price_tt = $price_tt !='' ? '<span> '.$plus_sign.' '.wp_strip_all_tags($price_tt).'</span>' : '';
						}
						echo  '<span class="exfood-label"><span class="exwo-otitle">'.$item['_name'].'</span> '.$price_tt.'</span>' ;
					}
					$enb_img = (($type =='checkbox'||$type =='radio') && isset($item['_enb_img'])) ? $item['_enb_img']!='' : '';
					echo '<div class="exwo-container'.($enb_img=='yes' ? ' exwo-img-option' : '').'" '.($display_type =='accor' ? 'style="display:none"' : '').'>';
						$options = isset($item['_options']) ? $item['_options'] : '';
						if($type =='radio' && !empty($options)){
							foreach ($options as $key => $value) {
								$op_name = isset($value['name'])? $value['name'] : '';
								$dis_ck = isset($value['dis'])? $value['dis'] : '';
								if(is_array($data_edit)){
									$def_ck = isset($data_edit[$item['_id']][$key]) ? 'yes' : '';
								}else{
									$def_ck = isset($value['def']) && $dis_ck!='yes'? $value['def'] : '';
								}
								$op_val = isset($value['price'])? exwo_convert_number_decimal_comma($value['price'],true) : '';
								$op_typ = isset($value['type'])? $value['type'] : '';
								$op_name = $op_val !='' ? $op_name .' '.$plus_sign.' '.wc_price($op_val) : $op_name;
								$id_op = 'raid-'.rand(1,10000).'-'.$el_id.'-'.rand(1,10000);

								$img_op = isset($value['image'])? $value['image'] : '';
								$img_op_html = $img_op!='' && $enb_img =='yes' ? '<span class="exwo-op-img"><img src="'.esc_url($img_op).'"/></span>':'';
								echo '<span><input class="ex-options" type="radio" name="ex_options_'.esc_attr($i).'[]" id="'.esc_attr($id_op).'" value="'.esc_attr($key).'" data-price="'.esc_attr($op_val).'" data-def="'.esc_attr($def_ck).'" data-type="'.esc_attr($op_typ).'" '.checked($def_ck,'yes',false).' '.disabled($dis_ck,'yes',false).'><label for="'.esc_attr($id_op).'">'.$img_op_html.'<span class="exwo-op-name">'.wp_kses_post($op_name).'</span></label></span>';
							}
						}else if($type =='select' && !empty($options)){
							echo '<select class="ex-options" name="ex_options_'.esc_attr($i).'[]">';
							echo '<option value="" data-price="">'.esc_html__( 'Select', $text_domain ).'</option>';
							foreach ($options as $key => $value) {
								$op_name = isset($value['name'])? $value['name'] : '';
								$dis_ck = isset($value['dis'])? $value['dis'] : '';
								if(is_array($data_edit)){
									$def_ck = isset($data_edit[$item['_id']][$key]) ? 'yes' : '';
								}else{
									$def_ck = isset($value['def']) && $dis_ck!='yes'? $value['def'] : '';
								}
								$op_val = isset($value['price'])? exwo_convert_number_decimal_comma($value['price'],true) : '';
								$op_typ = isset($value['type'])? $value['type'] : '';
								$op_name = $op_val !='' ? $op_name .' '.$plus_sign.' '.wc_price($op_val) : $op_name;
								echo '<option value="'.esc_attr($key).'" data-price="'.esc_attr($op_val).'" data-type="'.esc_attr($op_typ).'" data-def="'.esc_attr($def_ck).'" '. selected( $def_ck, 'yes',false ) .' '.disabled($dis_ck,'yes',false).'>'.wp_kses_post($op_name).'</option>';
							}
							echo '<select>';
						}else if($type =='text'){
							$price_ta = isset($item['_price']) && $item['_price']!='' ? exwo_convert_number_decimal_comma($item['_price']) :'';
							$price_typ = isset($item['_price_type']) && $item['_price_type']!='' ? $item['_price_type'] :'';
							$def ='';
							if(is_array($data_edit) && isset($data_edit[$item['_id']])){
								$def = $data_edit[$item['_id']];
							}
							echo '<input class="ex-options" value="'.esc_attr($def).'" type="text" name="ex_options_'.esc_attr($i).'" data-price="'.esc_attr($price_ta).'" data-type="'.esc_attr($price_typ).'"/>';
						}else if($type =='quantity'){
							$price_ta = isset($item['_price']) && $item['_price']!='' ? exwo_convert_number_decimal_comma($item['_price']) :'';
							$price_typ = isset($item['_price_type']) && $item['_price_type']!='' ? $item['_price_type'] :'';
							$def ='';
							if(is_array($data_edit) && isset($data_edit[$item['_id']])){
								$def = $data_edit[$item['_id']];
							}
							echo '<input class="ex-options" value="'.esc_attr($def).'" type="number" min="0" name="ex_options_'.esc_attr($i).'" data-price="'.esc_attr($price_ta).'" data-type="'.esc_attr($price_typ).'" placeholder="0" />';
						}else if($type =='textarea'){
							$price_ta = isset($item['_price']) && $item['_price']!='' ? exwo_convert_number_decimal_comma($item['_price']) :'';
							$price_typ = isset($item['_price_type']) && $item['_price_type']!='' ? $item['_price_type'] :'';
							$def ='';
							if(is_array($data_edit) && isset($data_edit[$item['_id']])){
								$def = $data_edit[$item['_id']];
							}
							echo '<textarea class="ex-options" name="ex_options_'.esc_attr($i).'" data-price="'.esc_attr($price_ta).'" data-type="'.esc_attr($price_typ).'"/>'.($def).'</textarea>';
						}else if(!empty($options)){
							foreach ($options as $key => $value) {
								$op_name = isset($value['name'])? $value['name'] : '';
								$dis_ck = isset($value['dis'])? $value['dis'] : '';
								if(is_array($data_edit)){
									$def_ck = isset($data_edit[$item['_id']][$key]) ? 'yes' : '';
								}else{
									$def_ck = isset($value['def']) && $dis_ck!='yes'? $value['def'] : '';
								}
								$op_val = isset($value['price'])? exwo_convert_number_decimal_comma($value['price'],true) : '';
								$op_typ = isset($value['type'])? $value['type'] : '';
								$op_name = $op_val !='' ? $op_name .' '.$plus_sign.' '.wc_price($op_val) : $op_name;
								$id_op = 'ckid-'.rand(1,1000).'-'.$el_id.'-'.rand(1,10000);

								$img_op = isset($value['image'])? $value['image'] : '';
								$img_op_html = $img_op!='' && $enb_img =='yes' ? '<span class="exwo-op-img"><img src="'.esc_url($img_op).'"/></span>':'';
								echo '<span><input class="ex-options" type="checkbox" name="ex_options_'.esc_attr($i).'[]" id="'.esc_attr($id_op).'" value="'.esc_attr($key).'" data-price="'.esc_attr($op_val).'" data-def="'.esc_attr($def_ck).'" data-type="'.esc_attr($op_typ).'" '.checked($def_ck,'yes',false).' '.disabled($dis_ck,'yes',false).'><label for="'.esc_attr($id_op).'">'.$img_op_html.'<span class="exwo-op-name">'.wp_kses_post($op_name).'</span></label></span>';
							}
						}
						if($required!=''){
							echo '<p class="ex-required-message">'.esc_html__('This option is required', $text_domain ).'</p>';
						}
						if($type=='checkbox' && is_numeric($min_req) && $min_req > 0){
							echo '<p class="ex-required-min-message">'.sprintf( esc_html__('Please choose at least %s options.','woocommerce-food' ) , $min_req).'</p>';
						}
						if($type=='checkbox' && is_numeric($max_req) && $max_req > 0){
							echo '<p class="ex-required-max-message">'.sprintf( esc_html__('You only can select max %s options.','woocommerce-food' ) , $max_req).'</p>';
						}
					echo '</div>
				</div>';
			$i ++;
			echo '</div>';
		}
		if($logic_js !='' || $extralg_js!=''){
			echo '<script type="text/javascript">
				jQuery(document).ready(function() {
					var $ex_variation = jQuery("input.variation_id").val();
					if($ex_variation!="" && $ex_variation!=0){
						'.$logic_js.'
					}
					'.$extralg_js.'
					if(jQuery("#'.$exid.' .exwf-offrq").length ){ 
						jQuery("#'.$exid.' .exwf-offrq .ex-options:not([type=radio]):not([type=checkbox])").val("").trigger("change");
						jQuery("#'.$exid.' .exwf-offrq .ex-options[type=radio], #'.$exid.' .exwf-offrq .ex-options[type=checkbox]").prop("checked", false).trigger("change");
					}
				});
				jQuery( document ).on( "found_variation.first", function ( e, variation ) {
				});
				jQuery( ".variations_form" ).on( "woocommerce_variation_select_change", function () {
					setTimeout(function(){ 
						var $ex_variation = jQuery("input.variation_id").val();
						if($ex_variation=="" ){
							jQuery("#'.$exid.' .ex-logic-on").fadeOut();
							jQuery("#'.$exid.' .exwf-offrq .ex-options:not([type=radio]):not([type=checkbox])").val("").trigger("change");
							jQuery("#'.$exid.' .exwf-offrq .ex-options[type=radio], #'.$exid.' .exwf-offrq .ex-options[type=checkbox]").prop("checked", false).trigger("change");
						}
					}, 100);
				});	
				jQuery( ".single_variation_wrap" ).on( "show_variation", function ( event, variation ) {
					var $ex_variation = variation.variation_id;
					'.$logic_js.'
					//jQuery("#'.$exid.' .exwf-offrq .ex-options:not([type=radio]):not([type=checkbox])").val("").trigger("change");
					//jQuery("#'.$exid.' .exwf-offrq .ex-options[type=radio], #'.$exid.' .exwf-offrq .ex-options[type=checkbox]").prop("checked", false).trigger("change");
				});';
				do_action('exwo_after_logicjs_op');	
			echo '	
			</script>';
		}
		do_action( 'exwo_after_product_options');
		echo '</div>';
		if($show_more =='1'){
			echo '<div class="exwo-showmore"><span>'.esc_html__( 'Show extra options', $text_domain ).'<span></div>';
		}

	}
}
add_action( 'woocommerce_before_add_to_cart_button', 'exwoo_display_custom_field' );
/**
 * Validate the text field
 */
function exwo_validate_custom_field( $passed, $product_id, $quantity, $variation_id=false ) {
	if(isset($_POST['exwf-up-cartitem']) && $_POST['exwf-up-cartitem']!=''){
		WC()->cart->set_quantity( $_POST['exwf-up-cartitem'], 0 );
	}
	$vari_pro = false;
	if(is_numeric($variation_id) && $variation_id > 0){
		$variation = wc_get_product($variation_id);
		$product_id = $variation->get_parent_id();
		$vari_pro = true;
	} else if(get_post_type($product_id) == 'product_variation') {
		$variation = wc_get_product($product_id);
		$variation_id = $product_id = $variation->get_parent_id();
		$vari_pro = true;
	}
	$data_options = exwoo_get_options($product_id);
	$text_domain = exwo_text_domain();
	$msg = '';
	if(is_array($data_options) && !empty($data_options)){
		foreach ( $data_options as $key=> $options ) {
			$rq = isset($options['_required']) ? $options['_required'] : ''; 
			$data_exts = isset($_POST['ex_options_'.$key]) ? $_POST['ex_options_'.$key] :'';
			$type = isset($options['_type']) && $options['_type']!='' ? $options['_type'] : 'checkbox';
			if( ($type=='checkbox' || $type=='select' || $type=='radio' ) && !empty($data_exts) && is_array($data_exts)){
				foreach ($data_exts as $k => $opc) {
					if( isset($options['_options'][$opc]['dis']) && ($options['_options'][$opc]['dis']=='yes')){
						unset($data_exts[$k]);
					}
				}
				$data_exts = array_values($data_exts);
			}
			$min_req = $type=='checkbox' && isset($options['_min_op']) && $options['_min_op']!='' ? $options['_min_op'] : 0;
			$max_req = $type=='checkbox' && isset($options['_max_op']) && $options['_max_op']!='' ? $options['_max_op'] : 0;

			$enb_logic = isset($options['_enb_logic']) ? $options['_enb_logic'] : '';
			if($enb_logic == 'on'){
				$tlogic = isset($options['_con_tlogic']) ? $options['_con_tlogic'] : '';
				$c_logic = isset($options['_con_logic']) ? $options['_con_logic'] : '';
				if(is_array($c_logic) && !empty($c_logic)){
					$c_or = $c_and = array();
					$vali_con = true;
					foreach ($c_logic as $key_lg => $c_lg_val) {
						$c_val = isset($c_lg_val['val']) ? $c_lg_val['val'] : '';
						$c_val = explode("-",$c_val);
						$c_val = $c_val[0];
						$c_type_con = isset($c_lg_val['type_con']) ? $c_lg_val['type_con'] : '';
						$c_type_op = isset($c_lg_val['type_op']) ? $c_lg_val['type_op'] : '';
						if(($c_type_op=='' || $c_type_op=='varia') && $vari_pro == true){
							if($c_type_con=='is_not'){
								if($tlogic=='hide' && $c_val != $variation_id){
									$rq ='no'; $min_req = $max_req = 0;
									unset($_POST['ex_options_'.$key]);
								}else if($tlogic=='' && $c_val == $variation_id){
									$rq ='no'; $min_req = $max_req = 0;
									unset($_POST['ex_options_'.$key]);
								}
							}else{
								if($tlogic=='hide' && $c_val == $variation_id){
									$rq ='no'; $min_req = $max_req = 0;
									unset($_POST['ex_options_'.$key]);
								}else if($tlogic==''){
									//$rq ='no'; $min_req = $max_req = 0;
									//unset($_POST['ex_options_'.$key]);
									if( $c_val != $variation_id){
										$vali_con = false;
									}else{
										$vali_con = true;break;
									}
								}
							}
							/*
							if(isset($c_lg_val['type_rel']) && $c_lg_val['type_rel'] == 'or'){
								$c_or[] = isset($c_lg_val['val']) ? $c_lg_val['val'] : '';
							}else{
								$c_and[] = isset($c_lg_val['val']) ? $c_lg_val['val'] : '';
							}*/
						}else if($c_type_op!=''){
							$findk = '';
							if(function_exists('array_column')){
								$findk = array_search($c_type_op, array_column($data_options, '_id'));
							}else{
								foreach($data_options as $keyfn => $optionfn){
								    if ( $optionfn['_id'] === $c_type_op ){
								    	$findk = $keyfn;
								    	break;
								    } 
								}
							}
							//echo $findk;print_r($_POST['ex_options_'.$findk]);
							if($c_type_con=='is_not'){
								if($tlogic=='hide' &&  ( !isset($_POST['ex_options_'.$findk]) || (isset($_POST['ex_options_'.$findk]) && is_array($_POST['ex_options_'.$findk]) && !in_array($c_val, $_POST['ex_options_'.$findk]))) ){
									$rq ='no'; $min_req = $max_req = 0;
									unset($_POST['ex_options_'.$key]);
								}else if($tlogic=='' && isset($_POST['ex_options_'.$findk]) && is_array($_POST['ex_options_'.$findk]) && in_array($c_val, $_POST['ex_options_'.$findk])){
									$rq ='no'; $min_req = $max_req = 0;
									unset($_POST['ex_options_'.$key]);
								}
							}else{
								if($tlogic=='hide' && isset($_POST['ex_options_'.$findk]) && is_array($_POST['ex_options_'.$findk]) && in_array($c_val, $_POST['ex_options_'.$findk])){
									$rq ='no'; $min_req = $max_req = 0;
									unset($_POST['ex_options_'.$key]);
								}else if($tlogic=='' ){
									if( !isset($_POST['ex_options_'.$findk]) || (isset($_POST['ex_options_'.$findk]) && is_array($_POST['ex_options_'.$findk]) && !in_array($c_val, $_POST['ex_options_'.$findk]))){
										$vali_con = false;
									}else{
										$vali_con = true;break;
									}
								}
							}
						}
					}
					if($vali_con == false){
						$rq ='no'; $min_req = $max_req = 0;
						unset($_POST['ex_options_'.$key]);
					}
				}
			}
			$rq = apply_filters('exwo_required_option',$rq,$options,$key);
			if(is_array($data_exts) && count($data_exts) ==1 && $data_exts[0]==''){
				$data_exts = '';
			}
			$c_item = !empty($data_exts) && is_array($data_exts) ? count($data_exts) : 0;
			if( ($rq =='yes' && ($data_exts=='' || empty($data_exts))) || ( $min_req > 0 &&  $min_req > $c_item) || ( $max_req > 0 &&  $max_req < $c_item) ){
				$passed = false;
				wc_add_notice( __( 'Please re-check all required fields and try again', $text_domain ), 'error' );
				break;
			}
			//print_r($options);
		}//return false;
	}
	return $passed;
}
add_filter( 'woocommerce_add_to_cart_validation', 'exwo_validate_custom_field', 10, 4 );


/**
 * Add the text field as item data to the cart object
 */
function exwo_add_custom_field_item_data( $cart_item_data, $product_id ) {

	$data_options = exwoo_get_options($product_id);
	$c_options = array();
	//$price = '';
	if(is_array($data_options) && !empty($data_options)){
		$data_edit = array();
		foreach ( $data_options as $key=> $options ) {
			$data_exts = isset($_POST['ex_options_'.$key]) ? $_POST['ex_options_'.$key] :'';
			if(isset($options['_type']) &&($options['_type']=='text' || $options['_type']=='textarea' || $options['_type']=='quantity')){
				$price_op = isset($options['_price']) ? exwo_convert_number_decimal_comma($options['_price']) : '';
				if($data_exts!=''){
					$type_price = isset($options['_price_type']) ? $options['_price_type'] : '';
					if($options['_type']=='quantity'){
						$price_op = floatval($price_op)*$data_exts;
					}
					$c_options[] = array(
						'name'       => sanitize_text_field( $options['_name'] ),
						'value'      => $data_exts,
						'type_of_price'      => $type_price,
						'price'      => floatval($price_op),
						'_type'      => $options['_type'],
					);
					//$price += (float) floatval($price_op);
					$data_edit[$options['_id']] = $data_exts;
				}
			}else{
				if(is_array($data_exts) && !empty($data_exts)){
					foreach ($data_exts as $value) {
						if($value!=''){
							$price_op = isset($options['_options'][$value]['price']) ? exwo_convert_number_decimal_comma($options['_options'][$value]['price']) : '';
							$type_price = isset($options['_options'][$value]['type']) ? $options['_options'][$value]['type'] : '';
							$c_options[] = array(
								'name'       => sanitize_text_field( isset($options['_name']) ? $options['_name'] : '' ),
								'value'      => isset($options['_options'][$value]['name']) ? $options['_options'][$value]['name'] :'' ,
								'type_of_price'      => $type_price,
								'price'      => floatval($price_op),
								'_type'      => isset($options['_type']) ? $options['_type'] : '',
							);
							$data_edit[$options['_id']][$value] = $value;
							//$price += (float) floatval($price_op);
						}
					}
				}
			}
			$cart_item_data['data_edit'] = $data_edit;
		}
		$cart_item_data['exoptions'] = $c_options;
	}
	return $cart_item_data;
}
add_filter( 'woocommerce_add_cart_item_data', 'exwo_add_custom_field_item_data', 10, 2 );
function exwo_add_custom_field_item_data_again($cart_item_data, $product, $order){
	remove_filter( 'woocommerce_add_to_cart_validation', 'exwo_validate_custom_field', 10);
	if(isset($product['item_meta']['_exoptions']) && is_array($product['item_meta']['_exoptions'])){
		$cart_item_data['exoptions'] =  $product['item_meta']['_exoptions'];
		//echo '<pre>';print_r($cart_item_data);exit;
	}
	return $cart_item_data;
}
add_filter( 'woocommerce_order_again_cart_item_data', 'exwo_add_custom_field_item_data_again', 11, 3 );
/**
 * Update price
 */
add_filter( 'woocommerce_add_cart_item',  'exwf_update_total_price_item', 30, 1 );
function exwf_update_total_price_item($cart_item){
	if(isset($cart_item['exoptions']) && is_array($cart_item['exoptions'])){
		$price = (float) $cart_item['data']->get_price( 'edit' );
		$qty = $cart_item['quantity'];
		if(isset($_POST['action']) && isset($_POST['key']) && $_POST['action'] == 'exwf_update_quantity' && $_POST['key'] == $cart_item['key']){
			$qty = $_POST['quantity'];
		}
		foreach ( $cart_item['exoptions'] as $option ) {
			if ( $option['price'] ) {
				if($option['type_of_price'] == 'fixed'){
					if(isset($cart_item['_adult']) && ($cart_item['_adult']!='' || ($cart_item['_adult']=='0'))){
						$price += (float) $option['price']/$cart_item['_adult'];
					}else{
						$price += (float) $option['price']/$qty;
					}
				}else{
					$price += (float) $option['price'];
				}
			}
		}
		$cart_item['data']->set_price( $price );
	}
	return $cart_item;
}
add_filter( 'woocommerce_get_cart_item_from_session', 'exwf_update_total_from_session', 20, 2 );
function exwf_update_total_from_session($cart_item, $values){
	if(isset($cart_item['exoptions']) && is_array($cart_item['exoptions'])){
		$cart_item = exwf_update_total_price_item($cart_item);
	}
	return $cart_item;
}
/**
 * Display in cart
 */
add_filter('woocommerce_get_item_data','exwf_show_option_in_cart',11,2);
function exwf_show_option_in_cart( $other_data, $cart_item ) {
	if(isset($cart_item['exoptions']) && is_array($cart_item['exoptions'])){
		$show_sgline = apply_filters( 'exwf_show_options_single_line', 'no' );
		if($show_sgline!='yes'){
			foreach ( $cart_item['exoptions'] as $option ) {
				$char_j = ' + ';
				if(isset ($option['_type']) && $option['_type']=='quantity'){ $char_j = ' x ';}
				$char_j = apply_filters('exwo_plus_sign_char',$char_j,$option);
				if(isset ($option['_type']) && $option['_type']=='quantity'){
					$price_s = isset($option['price']) && $option['price'] > 0 ? $option['value'] .$char_j.wc_price(exwo_price_display($option['price'])/$option['value']) : $option['value'];
				}else{
					$price_s = isset($option['price']) && $option['price'] > 0 ? $option['value'] .$char_j.wc_price(exwo_price_display($option['price'])) : $option['value'];
				}
				$price_s = apply_filters( 'exwo_price_show_inorder', $price_s, $option,$cart_item );
				$name_opt = apply_filters( 'exwo_oname_show_inorder', $option['name'], $option,$cart_item );
				$other_data[] = array(
					'name'  => $name_opt,
					'value' => $price_s
				);
			}
		}else{
			$grouped_types = array();
			foreach($cart_item['exoptions'] as $type){
			    $grouped_types[$type['name']][] = $type;
			}
			foreach ($grouped_types as $key => $option_tp) {
				if (is_array($option_tp)){
					$price_a = '';
					$i = 0;
					foreach ($option_tp as $option_it) {
						$i ++;
						$name = $option_it['name'];
						$char_j = ' + ';
						if(isset ($option_it['_type']) && $option_it['_type']=='quantity'){ $char_j = ' x ';}
						$char_j = apply_filters('exwo_plus_sign_char',$char_j,$option_it);
						$price_s = isset($option_it['price']) && $option_it['price'] > 0 ? $option_it['value'] .$char_j.wc_price(exwo_price_display($option_it['price'])) : $option_it['value'];
						$price_s = apply_filters( 'exwo_price_show_inorder', $price_s, $option_it,$cart_item );
						$price_a .= $price_s;
						if($i > 0 && $i < count($option_tp)){$price_a .=', '; }
					}
					$name_opt = apply_filters( 'exwo_oname_show_inorder', $option_it['name'], $option_tp,$cart_item );
					$other_data[] = array(
						'name'  => $name_opt,
						'value' => $price_a
					);
				}
			}
		}
	}
	return $other_data;
}
//add_action( 'woocommerce_after_cart_item_name', 'exwf_edit_extra_options', 12, 2 );
function exwf_edit_extra_options( $cart_item, $cart_item_key ) {
	if(isset($cart_item['exoptions']) && !empty($cart_item['exoptions'])){
		$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
		$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
		echo '<a class="exwf-edit-options" data-key="'.esc_attr($cart_item_key).'" data-id_food="'.esc_attr($product_id).'" href="javascript:;">'.esc_html__('Edit Options','woocommerce-food').'</a>';
	}
}
//add_filter( 'woocommerce_widget_cart_item_quantity', 'exwf_minicart_edit_extra_options', 99, 3 );
function exwf_minicart_edit_extra_options( $html, $cart_item, $cart_item_key ) {
	if(isset($cart_item['exoptions']) && !empty($cart_item['exoptions'])){
		$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
		$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
		return '<a class="exwf-edit-options" data-key="'.esc_attr($cart_item_key).'" data-id_food="'.esc_attr($product_id).'" href="javascript:;">'.esc_html__('Edit Options','woocommerce-food').'</a>'.$html;
	}
	return $html;
}
//add_action( 'woocommerce_before_cart', 'exwf_edit_product_option_on_popup' );
function exwf_edit_product_option_on_popup() {
	$check_ex = exwf_if_check_product_notin_shipping();
	if($check_ex == false){
		return ;
	}
	echo '<div class="ex-hidden">'.do_shortcode('[ex_wf_ordbutton enable_mtod="no" product_id="-1" cart_enable="no"]').'</div>';
}
add_filter( 'woocommerce_quantity_input_args', 'exwo_defaut_qty', 99, 2 );
function exwo_defaut_qty( $args, $product ) {
	global $cart_itemkey;
	if($cart_itemkey!=''){
		$cart = WC()->cart->get_cart();
		if(isset($cart[$cart_itemkey])){
			$args['input_value'] = $cart[$cart_itemkey]['quantity'];
		}
	}
	return $args;
}
add_filter( 'woocommerce_product_get_default_attributes', 'exwo_defaut_variation_attribute', 10, 2 );
function exwo_defaut_variation_attribute( $default_attributes, $product ){
    if( ! $product->is_type('variable') ) return $default_attributes;
    global $cart_itemkey;
	if($cart_itemkey!=''){
		$cart = WC()->cart->get_cart();
		if(isset($cart[$cart_itemkey])){
			$item_data = $cart[$cart_itemkey]['data'];
			$attributes = $item_data->get_attributes();
			if(is_array($attributes) && !empty($attributes)){
				$default_attributes = $attributes;
			}
		}
	}
    return $default_attributes;
}
/**
 * Add option to order object
 */
function exwf_add_options_to_order( $item, $cart_item_key, $values, $order ) {
	if(isset($values['exoptions']) && is_array($values['exoptions'])){
		$show_sgline = apply_filters( 'exwf_show_options_single_line', 'no' );
		if($show_sgline!='yes'){
			foreach ( $values['exoptions'] as $option ) {
				$char_j = '+';
				if(isset ($option['_type']) &&  $option['_type']=='quantity'){ $char_j = 'x';}
				$char_j = apply_filters('exwo_plus_sign_char',$char_j,$option);
				//$value = isset($option['price']) && $option['price']!='' ? strip_tags($option['value'] .$char_j.wc_price($option['price'])) : $option['value'];
				$name = isset($option['price']) && $option['price']!='' ? strip_tags($option['name'] .' ('.$char_j.wc_price(exwo_price_display($option['price'])).')') : $option['name'];
				$name = apply_filters( 'exwo_name_show_inorder', $name, $option,$values );
				$ovalue = apply_filters( 'exwo_ovalue_show_inorder', $option['value'], $option,$values );
				$item->add_meta_data( $name,(isset($option['value']) && $option['value']!='' ? $option['value'] : ' '));
			}
		}else{
			$grouped_types = array();
			foreach($values['exoptions'] as $type){
			    $grouped_types[$type['name']][] = $type;
			}
			foreach ($grouped_types as $key => $option_tp) {
				if (is_array($option_tp)){
					$price_a = '';
					$i = 0;
					foreach ($option_tp as $option_it) {
						$i ++;
						$name = $option_it['name'];
						$char_j = ' + ';
						if(isset ($option_it['_type']) && $option_it['_type']=='quantity'){ $char_j = ' x ';}
						$char_j = apply_filters('exwo_plus_sign_char',$char_j,$option);
						$price_s = isset($option_it['price']) && $option_it['price']!='' ? $option_it['value'] .$char_j.wc_price(exwo_price_display($option_it['price'])) : $option_it['value'];
						$price_s = apply_filters( 'exwo_price_show_inorder', $price_s, $option_it,$values );
						$price_a .= $price_s;
						if($i > 0 && $i < count($option_tp)){$price_a .=', '; }
					}
					$name_opt = apply_filters( 'exwo_oname_show_inorder', $option_it['name'], $option_it,$values );
					$item->add_meta_data( $option_it['name'], $price_a );
				}
			}
		}
	}
}
add_action( 'woocommerce_checkout_create_order_line_item', 'exwf_add_options_to_order', 10, 4 );

add_action('woocommerce_new_order_item','exwf_add_options_order_item_meta',10,2);

function exwf_add_options_order_item_meta($item_id, $item){
	if ( is_object( $item ) && isset($item->legacy_values) ) {
		$values = $item->legacy_values;
		if(isset($values['exoptions']) && !empty($values['exoptions'])){
			wc_add_order_item_meta($item_id,'_exoptions',$values['exoptions']);
		}
	}
}