<?php 
class WT_Checkouthook {
	public function __construct()
    {
		if(get_option('wt_disable_attendees') != 'yes'){
			add_action('woocommerce_after_order_notes', array( &$this,'add_user_data_booking'));
			add_action( 'woocommerce_checkout_update_order_meta', array( &$this,'saveto_order_meta'));
			add_action( 'woocommerce_after_order_itemmeta', array( &$this,'show_adminorder_ineach_metadata'), 10, 3 );
			add_action( 'woocommerce_order_item_meta_end', array( &$this,'show_order_ineach_metadata'), 10, 3 );
		}
		add_action( 'woocommerce_before_checkout_process', array( &$this,'verify_checkout'));
		add_action( 'woocommerce_reduce_order_stock', array( &$this,'update_quantity_ofdate'),99 );
		add_action( 'woocommerce_order_status_cancelled', array( &$this,'update_quantity_ifcancel'),99 );
		add_action( 'wt_after_attendees_field', array( &$this,'add_same_above_info'));
    }
	function verify_checkout(){
		$check_stock = array();
		$disable_book = get_option('wt_disable_book');
		$cure_time =  strtotime("now");
		$gmt_offset = get_option('gmt_offset');
		if($gmt_offset!=''){
			$cure_time = $cure_time + ($gmt_offset*3600);
		}
		$msg_it ='';
		foreach ( WC()->cart->get_cart() as $cart_item_key => $values ) {
			if(isset($values['_date']) && isset($values['_adult']) && isset($values['_metadate'])){
				$id_cr = $values['product_id'];
				$wt_disable_book = get_post_meta( $id_cr, 'wt_disable_book', true ) ;
				$wt_start = get_post_meta( $id_cr, 'wt_start', true ) ;
				if($wt_disable_book==''){
					$wt_disable_book = $disable_book!='' ? $disable_book : 0;
				}
				$dis_uni = apply_filters( 'wt_disable_book_day', strtotime("+$wt_disable_book day") );
				$wt_disable_book = date_i18n('Y-m-d',$dis_uni);
				if($wt_start!='' && is_numeric($wt_start)){
					if($wt_start > $dis_uni && $wt_start > time()){
						$dis_uni = $wt_start;
					}
				}
				$dtb = explode("_",$values['_metadate']);
				$dtb = $dtb[0].'-'.$dtb[1].'-'.$dtb[2];
				$dtb_unix = strtotime($dtb);
				$wt_expired = get_post_meta( $id_cr, 'wt_expired', true ) ;
				if((($dtb_unix+86399) < $cure_time) || (($dtb_unix+86399) < $dis_uni) || ($wt_expired > 0 && ($dtb_unix+86399) > $wt_expired ) ){
				//if((($dtb_unix+86399) < $cure_time) || (($dtb_unix+86399) < $dis_uni)  ){	
				//	$msg_it .= sprintf( esc_html__('Sorry the date "%s" of tour:"%s" has passed or not available','woocommerce-food' ),$values['_date'],get_the_title($id_cr)).'</br>';
				}

				$crr_qty = $values['_adult']*1;
				if(isset($values['_infant']) && $values['_infant'] > 0 ){
					$crr_qty = $crr_qty + $values['_infant']*1;
				}
				if(isset($values['_child']) && $values['_child'] > 0 ){
					$crr_qty = $crr_qty + $values['_child']*1;
				}
				if(isset($values['_wtct1']) && $values['_wtct1'] > 0){
					$crr_qty = $crr_qty + $values['_wtct1']*1;
				}
				if(isset($values['_wtct2']) && $values['_wtct2'] > 0){
					$crr_qty = $crr_qty + $values['_wtct2']*1;
				}
				if(isset($values['variation_id']) && $values['variation_id'] >0){
					if(isset($check_stock[$values['product_id']][1]) && $check_stock[$values['product_id']][2] == $values['_metadate'] && $check_stock[$values['product_id']][4] == $values['variation_id'] ){
						$check_stock[$values['product_id']][1] = $check_stock[$values['product_id']][1] + $crr_qty;
					}else{
						$check_stock[$values['product_id']][1] = $crr_qty;
						$check_stock[$values['product_id']][2] = $values['_metadate'];
						$check_stock[$values['product_id']][3] = $values['product_id'];
						$check_stock[$values['product_id']][4] = $values['variation_id'];
					}
				}else{
					if(isset($check_stock[$values['product_id']][1]) && $check_stock[$values['product_id']][2] == $values['_metadate'] ){
						$check_stock[$values['product_id']][1] = $check_stock[$values['product_id']][1] + $crr_qty;
					}else{
						$check_stock[$values['product_id']][1] = $crr_qty;
						$check_stock[$values['product_id']][2] = $values['_metadate'];
						$check_stock[$values['product_id']][3] = $values['product_id'];
					}
				}
				
			}
		}
		if($msg_it!=''){
			wc_add_notice(  $msg_it,'error');
			return;
		}
		if(!empty($check_stock)){
			foreach($check_stock as $item){
				if(isset($item[2]) && $item[2]!=''){
					$avari = get_post_meta($item[3], $item[2], true);
					$mt_varst= get_option('wt_dismulti_varstock');
					if($mt_varst!='yes' && isset($item[4]) && $item[4] > 0){
						$avari = get_post_meta($item[3], $item[2].'_vaID_'.$item[4], true);
					}
					if($avari==''){
						$def_stock_va = '';
						if($mt_varst!='yes' && isset($item[4]) && $item[4] > 0){
							$def_stock_va = get_post_meta($item[3], $item[4].'_def_stock', true);
						}
						$def_stock = $def_stock_va!='' ? $def_stock_va : get_post_meta($item[3], 'def_stock', true);
						if($def_stock > 0){
							$avari = $def_stock;
						}
					}
					if($avari!='' && ($avari < $item[1])){
						$title = get_the_title( $item[3] );
						$t_stopb = sprintf(esc_html__('Sorry, "%s" is not enought stock. Please edit your quantity or date of tour. We apologise for any inconvenience caused.', 'woo-tour'),$title);
						wc_add_notice( $t_stopb, 'error' );
						global $woocommerce;
						$woocommerce->cart->empty_cart();
						return;
					}
				}
			}
		}
		//echo '<pre>';print_r($_POST);echo '</pre>';exit;
		if( isset($_POST['wt_ids']) && isset($_POST['wt_quatiny']) && (get_option('wt_disable_attendees') != 'yes') ){
			$wt_attendee_name = get_option('wt_attendee_name');
			$wt_attendee_email = get_option('wt_attendee_email');
			$wt_attendee_birth = get_option('wt_attendee_birth');
			$wt_attendee_gender = get_option('wt_attendee_gender');
			if($wt_attendee_name=='' || $wt_attendee_email=='' || $wt_attendee_birth=='' || $wt_attendee_gender==''){
				$it=0;
				foreach($_POST['wt_ids'] as $item){
					//if ( ! empty( $_POST['wt_if_name'][$item] ) ) {
						for( $i = 0 ; $i < $_POST['wt_quatiny'][$it]; $i++){
							$name = isset($_POST['wt_if_name'][$item][$i]) ? $_POST['wt_if_name'][$item][$i] : '';
							$name = preg_replace('/\s+/', '', $name);
							if(($name =='') && $wt_attendee_name!='no' && $wt_attendee_name!='dis' ){
								wc_add_notice( esc_html__( 'Please fill first name of Passenger' ,'woo-tour'), 'error' );
							}
							$lname = isset($_POST['wt_if_lname'][$item][$i]) ? $_POST['wt_if_lname'][$item][$i] : '';
							$lname = preg_replace('/\s+/', '', $lname);
							if(($lname =='') && $wt_attendee_name=='' ){
								wc_add_notice( esc_html__( 'Please fill last name of Passenger' ,'woo-tour'), 'error' );
							}
							$email = isset($_POST['wt_if_email'][$item][$i]) ? $_POST['wt_if_email'][$item][$i] : '';
							if((!filter_var($email, FILTER_VALIDATE_EMAIL)) && $wt_attendee_email=='' ){
								wc_add_notice( esc_html__( 'Please fill email info of Passenger' ,'woo-tour'), 'error' );
							}
							if((!isset($_POST['wt_if_dd'][$item][$i]) || $_POST['wt_if_dd'][$item][$i] =='') && $wt_attendee_birth=='' ){
								wc_add_notice( esc_html__( 'Please fill day of birth' ,'woo-tour'), 'error' );
							}
							if((!isset($_POST['wt_if_mm'][$item][$i]) || $_POST['wt_if_mm'][$item][$i] =='') && $wt_attendee_birth=='' ){
								wc_add_notice( esc_html__( 'Please fill month of birth' ,'woo-tour'), 'error' );
							}
							if((!isset($_POST['wt_if_yyyy'][$item][$i]) || $_POST['wt_if_yyyy'][$item][$i] =='') && $wt_attendee_birth=='' ){
								wc_add_notice( esc_html__( 'Please fill year of birth' ,'woo-tour'), 'error' );
							}
							if((!isset($_POST['wt_if_male'][$item][$i]) || $_POST['wt_if_male'][$item][$i] =='')  && $wt_attendee_gender==''  ){
								wc_add_notice( esc_html__( 'Please fill gender of Passenger' ,'woo-tour'), 'error' );
							}
						}
						$it++;
//					}else{
//						wc_add_notice( esc_html__( 'Please fill info Passenger' ,'woo-tour'), 'error' );
//					}
				}
			}
		}
		
	}
	function add_user_data_booking( $checkout ) {
		$c_it = 0;
		$n = 0;
		echo '<div class="wt-att-ck user_checkout_field">';
		$nb_check = 0;
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$n ++ ;
			$id = $cart_item['product_id'];
			$value_id = $id.'_'.$n;
			$value_id = apply_filters( 'wt_attendee_key', $value_id, $cart_item );
			$_product = wc_get_product ($id);
			$wt_main_purpose = wt_global_main_purpose();
			$wt_slayout_purpose = get_option('wt_slayout_purpose');
			$wt_layout_purpose = get_post_meta($id,'wt_layout_purpose',true);
			if( ($wt_main_purpose=='') || ($wt_main_purpose=='tour') || ($wt_main_purpose=='custom' && $wt_layout_purpose=='tour') || ($wt_main_purpose=='meta' && $wt_layout_purpose=='tour') || ($wt_main_purpose=='meta' && $wt_layout_purpose!='woo' && $wt_slayout_purpose=='tour') ){
				$c_it ++;
				if($c_it==1){
					echo '<h3>' . esc_html__('Attendees info','woo-tour') . '</h3>';
				}
				$t_fname = esc_html__('First Name: ','woo-tour');
				$t_lname = esc_html__('Last Name: ','woo-tour');
				$t_email = esc_html__('Email: ','woo-tour');
				echo '<div class="gr-product">';
					if(!isset($cart_item['_adult']) || $cart_item['_adult']==''){
						$cart_item['_adult'] = 0;
					}
					if(!isset($cart_item['_child']) || $cart_item['_child']==''){
						$cart_item['_child'] = 0;
					}
					if(!isset($cart_item['_infant']) || $cart_item['_infant']==''){
						$cart_item['_infant'] = 0;
					}
					if(!isset($cart_item['_wtct1']) || $cart_item['_wtct1']==''){
						$cart_item['_wtct1'] = 0;
					}
					if(!isset($cart_item['_wtct2']) || $cart_item['_wtct2']==''){
						$cart_item['_wtct2'] = 0;
					}
					$nb_p = $cart_item['_adult'] + $cart_item['_child'] + $cart_item['_infant'] + $cart_item['_wtct1'] + $cart_item['_wtct2'];
					$cls_c = '';
					if($nb_check == 0){
						$nb_check = $nb_p;
					}elseif($nb_check !=$nb_p){
						$nb_check = $nb_p;
						$cls_c ='wt-nlk-previous';
					}
					$nb_p = apply_filters('exwf_number_attendee',$nb_p,$cart_item);
					if ( $_product && $_product->exists() && $nb_p > 0) {
						$html_sab = '';
						if($c_it>1){
							$html_sab = ' <a class="wt-same-info" href="javascript:;">'.esc_html__('Same as above','woo-tour').'</a>';
						}
						echo '<h4 class="'.esc_attr($cls_c).'">('.$c_it.') '. $_product->get_title() .$html_sab. '</h4>';
						echo '<input type="hidden" name="wt_ids[]" value="'.$value_id.'">';
						echo '<input type="hidden" name="wt_quatiny[]" value="'.$nb_p.'">';
						echo '<div class="w-product">';
						$year = $month = $day = array();
						$day[''] = esc_html__('DD','woo-tour');
						$month[''] = esc_html__('MM','woo-tour');
						$year[''] = esc_html__('YYYY','woo-tour');
						for($m = 1 ; $m <13; $m++){
							$month[str_pad($m, 2, '0', STR_PAD_LEFT)] = str_pad($m, 2, '0', STR_PAD_LEFT);
						}
						for($d= 1 ; $d <32; $d++){
							$day[str_pad($d, 2, '0', STR_PAD_LEFT)] = str_pad($d, 2, '0', STR_PAD_LEFT);
						}
						$cr_y = date('Y');
						for($y = $cr_y ; $y > 1930 ; $y--){
							$year[$y] = $y;
						}
						
						$wt_attendee_name = get_option('wt_attendee_name');
						$wt_attendee_email = get_option('wt_attendee_email');
						$wt_attendee_birth = get_option('wt_attendee_birth');
						$wt_attendee_gender = get_option('wt_attendee_gender');
						for($i=0; $i < $nb_p; $i++){
							echo '<div class="wt-passenger-info">
							<p class="pa-lab">'.esc_html__('Passenger','woo-tour').' ('.($i+1).')</p>';
								do_action( 'wt_before_custom_field', $value_id, $i );
								if($wt_attendee_name!='dis'){
									woocommerce_form_field( 
										'wt_if_name['.$value_id.']['.$i.']', 
										array(
											'type'          => 'text',
											'class'         => array('we-ct-class '.($wt_attendee_email=='dis' ? 'we-ct-w50' :'').' form-row-wide first-el'),
											'label'         => '',
											'required'  => $wt_attendee_name!='no' ? true : false,
											'placeholder'   => esc_html__('First Name','woo-tour'),
										), 
										''
									);
									woocommerce_form_field( 
										'wt_if_lname['.$value_id.']['.$i.']', 
										array(
											'type'          => 'text',
											'class'         => array('we-ct-class '.($wt_attendee_email=='dis' ? 'we-ct-w50' :'').' form-row-wide'),
											'label'         => '',
											'required'  => $wt_attendee_name!='no' ? true : false,
											'placeholder'   => esc_html__('Last Name','woo-tour'),
										), 
										''
									);
								}
								if($wt_attendee_email!='dis'){
									woocommerce_form_field( 'wt_if_email['.$value_id.']['.$i.']', 
										array(
											'type'          => 'text',
											'class'         => array('we-ct-class '.($wt_attendee_name=='dis' ? 'we-ct-w100' :'').' form-row-wide'),
											'label'         => '',
											'required'  => $wt_attendee_email!='no' ? true : false,
											'placeholder'   => esc_html__('Email','woo-tour'),
										), 
										''
									);
								}
								if($wt_attendee_birth!='dis'){
									$dd = woocommerce_form_field( 'wt_if_dd['.$value_id.']['.$i.']', 
										array(
											'type'          => 'select',
											'class'         => array('we-ct-class form-row-wide first-el'),
											'label'         => esc_html__('Date of birth','woo-tour'),
											'required'  => $wt_attendee_birth!='no' ? true : false,
											'placeholder'   => '',
											'options' => $day,
											'return' => true,
										), 
										''
									);
									$mm = woocommerce_form_field( 'wt_if_mm['.$value_id.']['.$i.']', 
										array(
											'type'          => 'select',
											'class'         => array('we-ct-class form-row-wide'),
											'label'         => '',
											'required'  => $wt_attendee_birth!='no' ? true : false,
											'placeholder'   => '',
											'options' => $month,
											'return' => true,
										), 
										''
									);
									$yy = woocommerce_form_field( 'wt_if_yyyy['.$value_id.']['.$i.']', 
										array(
											'type'          => 'select',
											'class'         => array('we-ct-class form-row-wide'),
											'label'         => '',
											'required'  => $wt_attendee_birth!='no' ? true : false,
											'placeholder'   => '',
											'options' => $year,
											'return' => true,
										), 
										''
									);
									$html_birthday = apply_filters('exwt_user_birhday_html',$dd.$mm.$yy,$day,$month,$year,$wt_attendee_birth,$value_id,$i);
									echo $html_birthday;
								}
								if($wt_attendee_gender!='dis'){
									woocommerce_form_field( 'wt_if_male['.$value_id.']['.$i.']', 
										array(
											'type'          => 'select',
											'class'         => array('we-ct-class form-row-wide first-el wt-ged'),
											'label'         => esc_html__('Gender','woo-tour'),
											'required'  => $wt_attendee_gender!='no' ? true : false,
											'placeholder'   => '',
											'options' => array(
												'' => esc_html__('Select','woo-tour'), 
												'male'=>esc_html__('Male','woo-tour'), 
												'female'=>esc_html__('Female','woo-tour'), 
												'other' => esc_html__('Other','woo-tour')
											),
										), 
										''
									);
								}
								do_action( 'wt_after_custom_field', $value_id, $i );
							echo '</div>';
						}
						echo '</div>';
					}
				echo '</div>';
			}
		}
		do_action( 'wt_after_attendees_field', $value_id, $i );
		echo '</div>';
	}
	function add_same_above_info(){?>
		<script type="text/javascript">
		jQuery(document).ready(function($) {
		    jQuery('.user_checkout_field .gr-product h4').on('click', '.wt-same-info',function() {
		    	var $crr_info = $(this).closest('.gr-product');
		    	var $pre_info = $crr_info.prev();
		    	var $i = 0;
		    	$pre_info.find('.wt-passenger-info').each(function(){
		    		var $this_pr =$(this);
		    		$i++;
		    		$crr_info.find('.w-product .wt-passenger-info:nth-child('+$i+') [name^=wt_if_name]').val($this_pr.find('[name^=wt_if_name]').val());
		    		$crr_info.find('.w-product .wt-passenger-info:nth-child('+$i+') [name^=wt_if_lname]').val($this_pr.find('[name^=wt_if_lname]').val());
		    		$crr_info.find('.w-product .wt-passenger-info:nth-child('+$i+') [name^=wt_if_email]').val($this_pr.find('[name^=wt_if_email]').val());
		    		$crr_info.find('.w-product .wt-passenger-info:nth-child('+$i+') [name^=wt_if_dd]').val($this_pr.find('[name^=wt_if_dd]').val());
		    		$crr_info.find('.w-product .wt-passenger-info:nth-child('+$i+') [name^=wt_if_mm]').val($this_pr.find('[name^=wt_if_mm]').val());
		    		$crr_info.find('.w-product .wt-passenger-info:nth-child('+$i+') [name^=wt_if_yyyy]').val($this_pr.find('[name^=wt_if_yyyy]').val());
		    		$crr_info.find('.w-product .wt-passenger-info:nth-child('+$i+') [name^=wt_if_male]').val($this_pr.find('[name^=wt_if_male]').val());
		    	});
		    });
		});
		</script>
		<?php
	}

	function saveto_order_meta( $order_id ) {
		if ( ! empty( $_POST['wt_ids'] ) ) {
			foreach($_POST['wt_ids'] as $item){
				if ( ! empty( $_POST['wt_if_name'][$item] ) ) {
					$nl_meta = '';
					$ar_attend = array();
					$other_meta = '';
					$nbid= count($_POST['wt_if_name'][$item]);
					for( $i = 0 ; $i < $nbid; $i++){
						$name = sanitize_text_field( $_POST['wt_if_name'][$item][$i] );
						$lname = sanitize_text_field( $_POST['wt_if_lname'][$item][$i] );
						$email = sanitize_text_field( $_POST['wt_if_email'][$item][$i] );
						
						$dd = sanitize_text_field( $_POST['wt_if_dd'][$item][$i] );
						$mm = sanitize_text_field( $_POST['wt_if_mm'][$item][$i] );
						$yy = sanitize_text_field( $_POST['wt_if_yyyy'][$item][$i] );
						$bir_day = apply_filters('exwt_user_birhday',$dd.' '.$mm.' '.$yy, $dd,$mm,$yy);
						$male = sanitize_text_field( $_POST['wt_if_male'][$item][$i] );
						if($nl_meta!=''){
							$nl_meta = $nl_meta.']['.$email.'||'.$name.'||'.$lname.'||'.$bir_day.'||'.$male;
						}else{
							$nl_meta = $email.'||'.$name.'||'.$lname.'||'.$bir_day.'||'.$male;
						}
						$nl_meta = apply_filters( 'wt_custom_field_extract', $nl_meta, $_POST, $item, $i );
						$all_att = array($email,$name,$lname,$bir_day,$male);
						$all_att = apply_filters( 'wt_att_arr_info_extract', $all_att, $_POST, $item, $i );
						$ar_attend[] = $all_att;
					}
					update_post_meta( $order_id, 'att_info-'.$item, $nl_meta );
					update_post_meta( $order_id, 'att_arrinfo-'.$item, $ar_attend );
				}
			}
		}
	}
	function show_adminorder_ineach_metadata($item_id, $item, $_product){
		$id = $item['product_id'];
		
		$order = new WC_Order( $_GET['post'] );
		$order_items = $order->get_items();
		$n = 0; $find = 0;
		foreach ($order_items as $items_key => $items_value) {
			$n ++;
			if($items_value->get_id() == $item_id){
				$find = 1;
				break;
			}
		}
		if($find == 0){ return;}
		
		$value_id = $id.'_'.$n;
		$value_id = apply_filters( 'wt_attendee_key', $value_id, $item );
		
		$metadata = get_post_meta($_GET['post'],'att_info-'.$value_id, true);
		if($metadata == ''){
			$metadata = get_post_meta($_GET['post'],'att_info-'.$id, true);
		}
		// if WPML
		if(class_exists('SitePress') && $metadata==''){
			global $sitepress;
			$trid = $sitepress->get_element_trid( $id, 'post_product' );
			$translations = $sitepress->get_element_translations( $trid, 'post_product' );
			$ld = $sitepress->get_element_language_details( $id, 'post_product' );
			foreach( $translations as $translation ){
				if ( $ld->language_code != $translation->language_code ) {
					$value_id = $translation->element_id.'_'.$n;
					$metadata = get_post_meta($_GET['post'],'att_info-'.$value_id, true);
					if($metadata != ''){ break; }
				}
			}
		}
		if($metadata !=''){
			$metadata = explode("][",$metadata);
			if(!empty($metadata)){
				$i=0;
				foreach($metadata as $item){
					$i++;
					$item = explode("||",$item);
					$f_name = isset($item[1]) && $item[1]!='' ? $item[1] : '';
					$l_name = isset($item[2]) && $item[2]!='' ? $item[2] : '';
					$bir_day = isset($item[3]) && $item[3]!='' ? $item[3] : '';
					$male = isset($item[4]) && $item[4]!='' ? $item[4] : '';
					echo '<div class="we-user-info">'.esc_html__('Attendees info','woo-tour').' ('.$i.') <br>';
					do_action( 'wt_before_order_info', $item);
					echo  $f_name!='' && $l_name!='' ? '<span><b>'.esc_html__(' Name: ','woo-tour').'&nbsp;</b>'.$f_name.' '.$l_name.'</span><br>' : '';
					echo  isset($item[0]) && $item[0]!='' ? '<span><b>'.esc_html__(' Email: ','woo-tour').'&nbsp;</b>'.$item[0].'</span><br>' : '';
					if(str_replace(' ', '', $bir_day)!=''){
						$bir_day = preg_replace('/\s+/', '/', $bir_day);
						$bir_day = wt_safe_strtotime($bir_day,'');
					}
					echo  str_replace(' ', '', $bir_day)!='' ? '<span><b>'.esc_html__('Date of birth: ','woo-tour').'&nbsp;</b>'.$bir_day.'</span><br>' : '';
					if($male=='male'){ $male = esc_html__('Male','woo-tour');}
					else if($male=='female'){ $male = esc_html__('Female','woo-tour');}
					else if($male=='other'){ $male = esc_html__('Other','woo-tour');}
					
					echo  $male!='' ? '<span><b>'.esc_html__('Gender: ','woo-tour').'&nbsp;</b>'.$male.'</span><br>' : '';
					do_action( 'wt_after_order_info', $item);
					echo '</div>';
				}
			}
		}
	}
	
	function show_order_ineach_metadata($item_id, $item, $order){
		$id = $item['product_id'];
		
		$order_items = $order->get_items();
		$n = 0; $find = 0;
		foreach ($order_items as $items_key => $items_value) {
			$n ++;
			if($items_value->get_id() == $item_id){
				$find = 1;
				break;
			}
		}
		if($find == 0){ return;}
		$value_id = $id.'_'.$n;
		$value_id = apply_filters( 'wt_attendee_key', $value_id, $item );
		
		$metadata = get_post_meta($order->get_id(),'att_info-'.$value_id, true);
		if($metadata == ''){
			$metadata = get_post_meta($order->get_id(),'att_info-'.$id, true);
		}
		// if WPML
		if(class_exists('SitePress') && $metadata==''){
			global $sitepress;
			$trid = $sitepress->get_element_trid( $id, 'post_product' );
			$translations = $sitepress->get_element_translations( $trid, 'post_product' );
			$ld = $sitepress->get_element_language_details( $id, 'post_product' );
			foreach( $translations as $translation ){
				if ( $ld->language_code != $translation->language_code ) {
					$value_id = $translation->element_id.'_'.$n;
					$metadata = get_post_meta($order->get_id(),'att_info-'.$value_id, true);
					if($metadata != ''){ break; }
				}
			}
		}
		if($metadata !=''){
						
			$metadata = explode("][",$metadata);
			if(!empty($metadata)){
				$i=0;
				foreach($metadata as $item){
					$i++;
					$item = explode("||",$item);
					$f_name = isset($item[1]) && $item[1]!='' ? $item[1] : '';
					$l_name = isset($item[2]) && $item[2]!='' ? $item[2] : '';
					$bir_day = isset($item[3]) && $item[3]!='' ? $item[3] : '';
					$male = isset($item[4]) && $item[4]!='' ? $item[4] : '';
					echo '<div class="we-user-info">'.esc_html__('Attendees info','woo-tour').' ('.$i.') <br>';
					do_action( 'wt_before_order_info', $item);
					echo  $f_name!='' && $l_name!='' ? '<span><b>'.esc_html__('Name: ','woo-tour').'&nbsp;</b>'.$f_name.' '.$l_name.'</span><br>' : '';
					echo  isset($item[0]) && $item[0]!='' ? '<span><b>'.esc_html__('Email: ','woo-tour').'&nbsp;</b>'.$item[0].'</span><br>' : '';
					if(str_replace(' ', '', $bir_day)!=''){
						$bir_day = preg_replace('/\s+/', '/', $bir_day);
						$bir_day = wt_safe_strtotime($bir_day,'');
					}
					echo  str_replace(' ', '', $bir_day)!='' ? '<span><b>'.esc_html__('Date of birth: ','woo-tour').'&nbsp;</b>'.$bir_day.'</span><br>' : '';
					
					if($male=='male'){ $male = esc_html__('Male','woo-tour');}
					else if($male=='female'){ $male = esc_html__('Female','woo-tour');}
					else if($male=='other'){ $male = esc_html__('Other','woo-tour');}
					
					echo  $male!='' ? '<span><b>'.esc_html__('Gender: ','woo-tour').'&nbsp;</b>'.$male.'</span><br>' : '';
					do_action( 'wt_after_order_info', $item);
					echo '</div>';
				}
			}
		}
	}
	// update quantity
	function update_quantity_ofdate( $order ){
		//$order = new WC_Order( $order_id );
		
		$items = $order->get_items();
		foreach ( $items as $item ) {
			$product_id = $item['product_id'];
			$variation_id = isset($item['variation_id']) && $item['variation_id']!='' ? $item['variation_id'] : '';
			$metadate = isset( $item['_metadate']) ? $item['_metadate'] : '';
			$mt_varst= get_option('wt_dismulti_varstock');
			if($mt_varst!='yes' && is_numeric($variation_id) && $variation_id > 0){
				if($mt_varst=='sp_only'){
					$_idva_reduce = get_post_meta($variation_id, '_idva_reduce', true);
					if($_idva_reduce!='' && is_numeric($_idva_reduce)){
						$metadate = $metadate.'_vaID_'.$_idva_reduce;
					}else{
						$metadate = $metadate.'_vaID_'.$variation_id;
					}
				}else{
					$metadate = $metadate.'_vaID_'.$variation_id;
				}
			}
			if($product_id!='' && $metadate !=''){
				$avari = get_post_meta($product_id, $metadate, true);
				if($avari==''){
					$def_stock_va = '';
					if($mt_varst!='yes' && is_numeric($variation_id) && $variation_id > 0){
						$def_stock_va = get_post_meta($product_id, $variation_id.'_def_stock', true);
					}
					$def_stock = $def_stock_va!='' && $def_stock_va > 0 ? $def_stock_va : get_post_meta($product_id, 'def_stock', true);
					//$def_stock = get_post_meta($product_id, 'def_stock', true);
					if($def_stock > 0){
						$avari = $def_stock;
					}
				}
				if($avari!='' && ($avari > 0)){
					$ud_qty = '';
					if(isset($item['adult'])){
						$ud_qty = $item['adult']*1;
					}
					if(isset($item['infant'])){
						$ud_qty = $ud_qty + $item['infant']*1;
					}
					if(isset($item['child'])){
						$ud_qty = $ud_qty + $item['child']*1;
					}
					if(isset($item['_wtct1'])){
						$ud_qty = $ud_qty + $item['_wtct1']*1;
					}
					if(isset($item['_wtct2'])){
						$ud_qty = $ud_qty + $item['_wtct2']*1;
					}
					$ud_qty = apply_filters('exwt_number_items_reduce',$ud_qty,$item);
					if($avari > $ud_qty){
						$avari = $avari - $ud_qty;
					}else{
						$avari = 0;
					}
					update_post_meta( $product_id, $metadate, $avari);
					update_post_meta( $order->get_id(), 'exwt_rdticket', $ud_qty);

				}
			}
			
		}
	}
	function update_quantity_ifcancel( $order_id ){
		$reduce_ticket =  get_post_meta($order_id, 'exwt_rdticket', true);
		if($reduce_ticket!='' && is_numeric($reduce_ticket) && $reduce_ticket >0 ){
			update_post_meta( $order_id, 'exwt_rdticket','0');
		}else{
			return;
		}
		$order = new WC_Order( $order_id );
		$items = $order->get_items();
		foreach ( $items as $item ) {
			$product_id = $item['product_id'];
			$variation_id = isset($item['variation_id']) && $item['variation_id']!='' ? $item['variation_id'] : '';
			$metadate = isset( $item['_metadate']) ? $item['_metadate'] : '';
			$mt_varst= get_option('wt_dismulti_varstock');
			if($mt_varst!='yes' && is_numeric($variation_id) && $variation_id > 0){
				if($mt_varst=='sp_only'){
					$_idva_reduce = get_post_meta($variation_id, '_idva_reduce', true);
					if($_idva_reduce!='' && is_numeric($_idva_reduce)){
						$metadate = $metadate.'_vaID_'.$_idva_reduce;
					}else{
						$metadate = $metadate.'_vaID_'.$variation_id;
					}
				}else{
					$metadate = $metadate.'_vaID_'.$variation_id;
				}
			}
			if($product_id!='' && $metadate !=''){
				$avari = get_post_meta($product_id, $metadate, true);
				if($avari==''){
					$def_stock_va = '';
					if($mt_varst!='yes' && is_numeric($variation_id) && $variation_id > 0){
						$def_stock_va = get_post_meta($product_id, $variation_id.'_def_stock', true);
					}
					$def_stock = $def_stock_va!='' && $def_stock_va > 0 ? $def_stock_va : get_post_meta($product_id, 'def_stock', true);
					//$def_stock = get_post_meta($product_id, 'def_stock', true);
					if($def_stock > 0){
						$avari = $def_stock;
					}
				}
				if($avari!='' && ($avari >= 0)){
					if(isset($item['adult'])){
						$ud_qty = $item['adult']*1;
					}
					if(isset($item['infant'])){
						$ud_qty = $ud_qty + $item['infant']*1;
					}
					if(isset($item['child'])){
						$ud_qty = $ud_qty + $item['child']*1;
					}
					if(isset($item['_wtct1'])){
						$ud_qty = $ud_qty + $item['_wtct1']*1;
					}
					if(isset($item['_wtct2'])){
						$ud_qty = $ud_qty + $item['_wtct2']*1;
					}
					$ud_qty = apply_filters('exwt_number_items_reduce',$ud_qty,$item);
					$avari = $avari + $ud_qty;
					update_post_meta( $product_id, $metadate, $avari);
				}
			}
			
		}
	}
	
	
}
$WT_Checkouthook = new WT_Checkouthook();