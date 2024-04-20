<?php
if(get_option('wt_enable_exoptions') =='yes'){
	if ( file_exists( dirname( __FILE__ ) . '/cmb2/init.php' ) ) {
		require_once dirname( __FILE__ ) . '/cmb2/init.php';
	} elseif ( file_exists( dirname( __FILE__ ) . '/CMB2/init.php' ) ) {
		require_once dirname( __FILE__ ) . '/CMB2/init.php';
	}
	require_once dirname( __FILE__ ) . '/Post-Search-field/cmb2_post_search_field.php';
}
function wt_custom_admin_css() {
	$exwt_layout_purpose = get_option('wt_slayout_purpose');
	$wt_main_purpose = get_option('wt_main_purpose');
	if($wt_main_purpose=='meta'){
		echo '
		<style>#wootours .wt_shop_view, #wootours .wt_sidebar,#wootours .wt_slayout{ display: none; }</style>
		';
	}
	if(($exwt_layout_purpose != 'tour' && $wt_main_purpose !='tour' ) || $wt_main_purpose =='custom' || (get_option('wt_old_layout')!='yes' && $exwt_layout_purpose == 'woo')){
		echo '<style>
		.post-type-product .postbox-container #time-settings.postbox,
		.post-type-product .postbox-container #tour-info.postbox,
		.post-type-product .postbox-container #additional-information.postbox,
		.post-type-product .postbox-container #layout-settings.postbox,
		.post-type-product .postbox-container #custom-field.postbox{display: none;}
		</style>';
	}
	echo '<style>.edit_form_line input.cat.textfield[name=cat]{display: inline-block !important;}</style>';
}
add_action( 'admin_head', 'wt_custom_admin_css' );
function wt_add_admin_ct_class( $classes ) {
	$purpose = get_option('wt_main_purpose');
	$exwt_layout_purpose = get_option('wt_slayout_purpose');
	if( $purpose != 'meta' && $purpose != 'custom'){
		$classes .= $wt_dbclss = 'wt-hidden-st';
	}else if( $purpose == 'meta' && $exwt_layout_purpose=='tour'){
		$classes .= $wt_dbclss = 'wt-hidden-st';
	}
	return $classes;
}
add_filter( 'admin_body_class', 'wt_add_admin_ct_class');

if(!function_exists('wt_convent_weekday_to_export')){
	add_action('admin_init','wt_convent_weekday_to_export',999);
	function wt_convent_weekday_to_export() {
		if(current_user_can( 'manage_options' ) && isset($_GET['action']) && $_GET['action'] =='convert-wd'){
			$args = array(
				'post_type' => 'product',
				'post_status' => 'publish',
				'posts_per_page' => -1,
				'meta_query' => array(
					array(
						'key'     => 'wt_weekday',
						'compare' => 'EXISTS',
					),
				),
			);
			$ex_posts = get_posts( $args );
			foreach($ex_posts as $item){
				$wt_weekday = get_post_meta($item->ID,'wt_weekday',true);
				if(is_array($wt_weekday)){
					$wt_weekday = implode(",",$wt_weekday);
					update_post_meta( $item->ID, 'wt_wdays',  $wt_weekday);
				}
			}
			wp_redirect( admin_url() );
			exit;
			
		}
		return;
	}
}


/***** add filter order by tour date *****/
if(!function_exists('exwt_admin_filter_order_tour_date')){
	function exwt_admin_filter_order_tour_date( $post_type, $which ) {
		if ( $post_type == 'shop_order' ) {	
			wp_enqueue_script('jquery-ui-core');
			wp_enqueue_script('jquery-ui-datepicker');
			wp_enqueue_script('jquery-ui-datetimepicker');
			// Display filter HTML
			echo '<input type="text" class="date-picker" name="_tour_date" placeholder="'.esc_html__( 'Select tour date', 'woo-tour' ).'" value="'.(isset( $_GET['_tour_date'] ) ? $_GET['_tour_date'] : '' ).'">';
		}
	
	}
	add_action( 'restrict_manage_posts', 'exwt_admin_filter_order_tour_date' , 10, 2);
}
add_action( 'pre_get_posts','exwt_admin_filter_tour_date_qr',101 );
if (!function_exists('exwt_admin_filter_tour_date_qr')) {
	function exwt_admin_filter_tour_date_qr($query) {
		if ( isset($_GET['post_type']) && $_GET['post_type']=='shop_order' && is_admin()) {
			if( isset($_GET['_tour_date']) && $_GET['_tour_date']!='' ){
				$tdate = str_replace('-', '_', $_GET['_tour_date']);
				$ids = exwt_admin_search_ordeby_date($tdate);
				if($ids=='' || empty($ids)){
					$query->set('post__in', array(0));
				}else{
					$query->set('post__in', $ids);
				}
			}
		}
	}
}
function exwt_admin_search_ordeby_date($date){
	global $wpdb;
	$result = $wpdb->get_col( $wpdb->prepare( "
	    SELECT oi.order_id
	    FROM {$wpdb->prefix}woocommerce_order_items as oi
	    LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as oim
	        ON oi.order_item_id = oim.order_item_id
	    LEFT JOIN {$wpdb->posts} AS p
	        ON oi.order_id = p.ID
	    WHERE p.post_type = 'shop_order'
	    AND oi.order_item_type = 'line_item'
	    AND oim.meta_key = '_metadate'
	    AND oim.meta_value = '%s'
	    ORDER BY oi.order_id DESC
	", $date ) );
	return $result;
}
add_action( 'woocommerce_after_order_itemmeta', 'exwt_admin_edit_attendee', 9999, 3 );
function exwt_admin_edit_attendee($item_id, $item, $order){
	if(current_user_can( 'manage_options' ) && is_admin()){
		$_adult = wc_get_order_item_meta( $item_id, '_adult', true );
		$_adult = $_adult > 0 ? $_adult : 0;
		$_child = wc_get_order_item_meta( $item_id, '_child', true );
		$_child = $_child > 0 ? $_child : 0;
		$_infant = wc_get_order_item_meta( $item_id, '_infant', true );
		$_infant = $_infant > 0 ? $_infant : 0;
		$_wtct1 = wc_get_order_item_meta( $item_id, '_wtct1', true );
		$_wtct1 = $_wtct1 > 0 ? $_wtct1 : 0;
		$_wtct2 = wc_get_order_item_meta( $item_id, '_wtct2', true );
		$_wtct2 = $_wtct2 > 0 ? $_wtct2 : 0; 
		$nb_p = $_adult + $_child + $_infant + $_wtct1 + $_wtct2;
		if($nb_p > 0 ){
			$product_id = $item['product_id'];
			$order_id = wc_get_order_id_by_order_item_id($item_id);
			echo '<p><a class="exwt-edit-attendee" href="javascript:;"> '.esc_html__('Add/edit Attendees info','woo-tour').'</a></p>';
			$n = 0; $find = 0;
			$order = new WC_Order( $order_id );
			$order_items = $order->get_items();
			foreach ($order_items as $items_key => $items_value) {
				$n ++;
				if($items_value->get_id() == $item_id){
					$find = 1;
					break;
				}
			}
			$metadata = get_post_meta($order_id,'att_info-'.$product_id.'_'.$n, true);
			$metadata = explode("][",$metadata);
			global $post_ID;
			echo '<div class="wt-add-passenger-infos">
				<input type="hidden" name="exwtorder_id" value="'.$order_id.'">
				<input type="hidden" name="exwtproduct_id" value="'.$product_id.'">
				<input type="hidden" name="ajaxurl" value="'.esc_url(admin_url( 'admin-ajax.php' )).'">
				<input type="hidden" name="key_change" value="'.esc_attr('att_info-'.$product_id.'_'.$n).'">
				<input type="hidden" name="edit_link" value="'.admin_url( 'post.php?action=edit&post=' ).'">';

			for ($i=0; $i < $nb_p; $i++) { 
				$item_meta = explode("||",$metadata[$i]);
				echo '<div class="wt-add-passenger-info">
					
					<p class="pa-lab">'.esc_html__('Passenger','woo-tour').' ('.($i+1).')</p>';
						do_action( 'wt_admin_before_custom_field', $item_id, $i,$item_meta );
						woocommerce_form_field( 
							'wt_if_name['.$item_id.']['.$i.']', 
							array(
								'type'          => 'text',
								'class'         => array('we-ct-class att-fname form-row-wide first-el'),
								'label'         => '',
								'required'  => false,
								'placeholder'   => esc_html__('First Name','woo-tour'),
							), 
							isset($item_meta[1]) ? $item_meta[1] : ''
						);
						woocommerce_form_field( 
							'wt_if_lname['.$item_id.']['.$i.']', 
							array(
								'type'          => 'text',
								'class'         => array('we-ct-class att-lname form-row-wide'),
								'label'         => '',
								'required'  => false,
								'placeholder'   => esc_html__('Last Name','woo-tour'),
							), 
							isset($item_meta[2]) ? $item_meta[2] : ''
						);
						woocommerce_form_field( 'wt_if_email['.$item_id.']['.$i.']', 
							array(
								'type'          => 'text',
								'class'         => array('we-ct-class att-email form-row-wide'),
								'label'         => '',
								'required'  => false,
								'placeholder'   => esc_html__('Email','woo-tour'),
							), 
							isset($item_meta[0]) ? $item_meta[0] : ''
						);
						$bd = isset($item_meta[3]) ? $item_meta[3] : '';
						$bd = explode(" ",$bd);
						woocommerce_form_field( 'wt_if_dd['.$item_id.']['.$i.']', 
							array(
								'type'          => 'text',
								'class'         => array('we-ct-class att-dd form-row-wide first-el'),
								'label'         => esc_html__('Date of birth','woo-tour'),
								'required'  => false,
								'placeholder'   => esc_html__('Day(dd)','woo-tour'),
							), 
							isset($bd[0]) ? $bd[0] : ''
						);
						woocommerce_form_field( 'wt_if_mm['.$item_id.']['.$i.']', 
							array(
								'type'          => 'text',
								'class'         => array('we-ct-class att-mm form-row-wide'),
								'label'         => '',
								'required'  => false,
								'placeholder'   => esc_html__('Month(mm)','woo-tour'),
							), 
							isset($bd[1]) ? $bd[1] : ''
						);
						woocommerce_form_field( 'wt_if_yyyy['.$item_id.']['.$i.']', 
							array(
								'type'          => 'text',
								'class'         => array('we-ct-class att-yy form-row-wide'),
								'label'         => '',
								'required'  => false,
								'placeholder'   => esc_html__('Year(yyyy)','woo-tour'),
							), 
							isset($bd[2]) ? $bd[2] : ''
						);
						woocommerce_form_field( 'wt_if_male['.$item_id.']['.$i.']', 
							array(
								'type'          => 'select',
								'class'         => array('we-ct-class att-gend form-row-wide first-el wt-ged'),
								'label'         => esc_html__('Gender','woo-tour'),
								'required'  => false,
								'placeholder'   => '',
								'options' => array(
									'' => esc_html__('Select','woo-tour'), 
									'male'=>esc_html__('Male','woo-tour'), 
									'female'=>esc_html__('Female','woo-tour'), 
									'other' => esc_html__('Other','woo-tour')
								),
							), 
							isset($item_meta[4]) ? $item_meta[4] : ''
						);
						do_action( 'wt_admin_after_custom_field', $item_id, $i,$item_meta );
					echo '
					</div>';
			}
			echo '
			<p><a class="exwt-save-att button" href="javascript:;">'.esc_html__('Save','woo-tour').'</a></p>
			</div>';
		}
	}
}
add_action('wp_ajax_wt_admin_change_attendees', 'wt_admin_change_attendees' );
function wt_admin_change_attendees(){
	$product_id = $_POST['product_id'];
	$order_id = $_POST['order_id'];
	$data_atts = $_POST['data_atts'];
	$key_change = $_POST['key_change'];
	$nl_meta = '';
	if(is_array($data_atts) && !empty($data_atts)){
		foreach ($data_atts as $key => $atte) {
			$name = isset($atte[0]) ? $atte[0] : '';
			$lname = isset($atte[1]) ? $atte[1] : '';
			$email = isset($atte[2]) ? $atte[2] : '';
			
			$dd = isset($atte[3]) ? $atte[3] : '';
			$mm = isset($atte[4]) ? $atte[4] : '';
			$yy = isset($atte[5]) ? $atte[5] : '';
			$bir_day = $dd.' '.$mm.' '.$yy;
			$male = isset($atte[6]) ? $atte[6] : '';
			if($nl_meta!=''){
				$nl_meta = $nl_meta.']['.$email.'||'.$name.'||'.$lname.'||'.$bir_day.'||'.$male;
			}else{
				$nl_meta = $email.'||'.$name.'||'.$lname.'||'.$bir_day.'||'.$male;
			}
			$nl_meta = apply_filters( 'wt_adm_custom_field_extract', $nl_meta, $atte,$_POST );
		}
	}
	update_post_meta($order_id,$key_change,$nl_meta);
	$output =  array('html_content'=>$nl_meta);
	echo str_replace('\/', '/', json_encode($output));
	die;
}
add_filter('acf/settings/remove_wp_meta_box', '__return_false', 20);

// Show tour date column
add_filter( 'manage_shop_order_posts_columns', 'exwt_edit_order_columns',99 );
function exwt_edit_order_columns( $columns ) {
	$columns['tour-date'] = esc_html__( 'Departure' , 'woocommerce-food' );
	return $columns;
}
add_action( 'manage_shop_order_posts_custom_column', 'exwt_admin_order_departure_columns',12);
function exwt_admin_order_departure_columns( $column ) {
	global $the_order;
	switch ( $column ) {
		case 'tour-date':
			$order_items = $the_order->get_items();
			foreach ($order_items as $items_key => $items_value) {
				$departure = wc_get_order_item_meta( $items_key, '_date', true );
	            echo $departure.'</br>';
	        }
			break;
				
	}
}