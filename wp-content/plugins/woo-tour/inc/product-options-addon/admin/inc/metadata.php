<?php
/**
 * Register metadata box
 */
function exwt_license_infomation(){
	$scd_ck = get_option( 'exwt_cupdate');
	$crt = strtotime('now');
	$res = '';
	if($scd_ck=='' || $crt > $scd_ck ){
		$_name = get_option('exwt_evt_name');
		$_pcode = get_option('exwt_evt_purcode');
		if($_name=='' || $_pcode==''){
			return array('error');
		}
		$site = get_site_url();
		$url = 'https://exthemes.net/verify-purchase-code/';
		$myvars = 'buyer=' . $_name . '&code=' . $_pcode. '&site='.$site.'&item_id=19404740';
		$res = '';
		if(function_exists('stream_context_create')){
			$data = array('buyer' => $_name, 'code' => $_pcode, 'item_id' =>'19404740', 'site' => $site);
			$options = array(
			        'http' => array(
			        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
			        'method'  => 'POST',
			        'content' => http_build_query($data),
			    )
			);

			$context  = stream_context_create($options);
			$res = @file_get_contents($url, false, $context);
			if($res=== false){
				$res!='';
			}
		}
		if($res!=''){
			$res = json_decode($res);
		}else{
			$ch = curl_init( $url );
			curl_setopt( $ch, CURLOPT_POST, 1);
			curl_setopt( $ch, CURLOPT_POSTFIELDS, $myvars);
			curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt( $ch, CURLOPT_HEADER, 0);
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0); 
			curl_setopt($ch, CURLOPT_TIMEOUT, 2);
			$res=json_decode(curl_exec($ch),true);
			curl_close($ch);
		}
		//print_r( $res) ;exit;
		if(isset($res[0]) && $res[0] == 'error'){
			update_option( 'exwt_cupdate', '' );
		}else if(isset($res[0]) && $res[0] == 'success'){
			update_option( 'exwt_cupdate', strtotime('+25 day') );
		}else{
			update_option( 'exwt_cupdate', strtotime('+17 day') );
		}
	}
	return $res;
}
function exwf_hide_if_no_product( $field ) {
	// Don't show this field if not in the cats category.
	if ( get_post_type($field->object_id) == 'exwo_glboptions') {
		return false;
	}
	return true;
}

function exwo_vrf_purc_ofwf(){
	$_license = function_exists('exwt_license_infomation') ? exwt_license_infomation() : '';
	if(isset($_license[0]) && $_license[0] == 'error'){
		return false;
	}
	return true;
}

function exwo_vrf_purc_invl_ofwf(){
	$_license = function_exists('exwt_license_infomation') ? exwt_license_infomation() : '';
	if(isset($_license[0]) && $_license[0] == 'error'){
		return true;
	}
	return false;
}

function exwo_vrf_purc_ofwf_info( $cmb_options ) {
	$_license = function_exists('exwt_license_infomation') ? exwt_license_infomation() : '';
	if(isset($_license[0]) && $_license[0] == 'error'){
		echo '<div class="notice-error" style="background: #fff; border: 1px solid #c3c4c7; border-left-width: 4px; box-shadow: 0 1px 1px rgb(0 0 0 / 4%); margin: 5px 15px 2px; padding: 1px 12px; border-left-color: #d63638;"><p>Please add a valid purchase code to continue, <a href="'.esc_url(admin_url('admin.php?page=wootours#plugin-license')).'">activate your license here</a></p></div>';
	}
}

add_action( 'cmb2_admin_init', 'exwo_register_metabox' );

function exwo_register_metabox() {
	$prefix = 'exwo_';
	$text_domain = exwo_text_domain();
	/**
	 * Food general info
	 */
	$exwo_vrf = new_cmb2_box( array(
		'id'            => $prefix . 'vrf_options',
		'title'         => esc_html__( 'Additional option', $text_domain ),
		'object_types'  => array( 'product','exwo_glboptions' ),
		'show_on_cb' => 'exwo_vrf_purc_invl_ofwf',
	) );
	$exwo_vrf->add_field( array(
		'name' => '',
		'description' => '',
		'id'   => 'exwo_exclude_options',
		'type' => 'title',
		'default' => '',
		'before_row'     => 'exwo_vrf_purc_ofwf_info',
	) );

	$exwo_options = new_cmb2_box( array(
		'id'            => $prefix . 'addition_options',
		'title'         => esc_html__( 'Additional option', $text_domain ),
		'object_types'  => array( 'product','exwo_glboptions' ), // Post type
		'show_on_cb' => 'exwo_vrf_purc_ofwf',
	) );
	$exwo_options->add_field( array(
		'name' => esc_html__( 'Exclude Global Option', $text_domain ),
		'description' => esc_html__( 'Exclude all Global Options apply this product', $text_domain ),
		'id'   => 'exwo_exclude_options',
		'type' => 'checkbox',
		'default' => '',
		'show_on_cb' => 'exwf_hide_if_no_product',
	) );
	$exwo_options->add_field( array(
		'name'        => esc_html__( 'Include global options',$text_domain  ),
		'id'          => 'exwo_include_options',
		'type'        => 'post_search_text', 
		'desc'       => esc_html__( 'Select Option(s) to apply for this product', $text_domain ),
		'post_type'   => 'exwo_glboptions',
		'select_type' => 'checkbox',
		'select_behavior' => 'add',
		'after_field'  => '',
		'show_on_cb' => 'exwf_hide_if_no_product',
	) );
	$exwo_options->add_field( array(
		'name' => esc_html__( 'Global Option position', $text_domain ),
		'description' => esc_html__( 'Select postion of global option', $text_domain ),
		'id'   => 'exwo_options_pos',
		'type' => 'select',
		'default' => '',
		'show_on_cb' => 'exwf_hide_if_no_product',
		'options'          => array(
			'' => esc_html__( 'After option of product', 'woocommerce-food' ),
			'before'   => esc_html__( 'Before option of product', 'woocommerce-food' ),
		),
	) );
	$group_option = $exwo_options->add_field( array(
		'id'          => $prefix . 'options',
		'type'        => 'group',
		'description' => esc_html__( 'Add additional product option to allow user can order with this product', $text_domain ),
		// 'repeatable'  => false, // use false if you want non-repeatable group
		'options'     => array(
			'group_title'   => esc_html__( 'Option {#}', $text_domain ), // since version 1.1.4, {#} gets replaced by row number
			'add_button'    => esc_html__( 'Add Option', $text_domain ),
			'remove_button' => esc_html__( 'Remove Option', $text_domain ),
			'sortable'      => true, // beta
			'closed'     => true, // true to have the groups closed by default
		),
		'after_group' => '',
	) );
	// Id's for group's fields only need to be unique for the group. Prefix is not needed.
	$exwo_options->add_group_field( $group_option, array(
		'name' => esc_html__( 'Name', $text_domain ),
		'id'   => '_name',
		'type' => 'text',
		'classes' => 'exwo-stgeneral exwo-op-name',
		'before_row'     => 'exwf_option_sttab_html',
		// 'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
	) );
	$exwo_options->add_group_field( $group_option, array(
		'name' => esc_html__( 'Option type', $text_domain ),
		'description' => esc_html__( 'Select type of this option', $text_domain ),
		'id'   => '_type',
		'classes' => 'exwo-stgeneral extype-option exwo-op-type',
		'type' => 'select',
		'show_option_none' => false,
		'default' => '',
		'options'          => array(
			'' => esc_html__( 'Checkboxes', $text_domain ),
			'radio'   => esc_html__( 'Radio buttons', $text_domain ),
			'select'   => esc_html__( 'Select box', $text_domain ),
			'text'   => esc_html__( 'Textbox', $text_domain ),
			'textarea'   => esc_html__( 'Textarea', $text_domain ),
			'quantity'   => esc_html__( 'Quantity', $text_domain ),
		),
	) );
	$exwo_options->add_group_field( $group_option, array(
		'name' => esc_html__( 'Required?', $text_domain ),
		'description' => esc_html__( 'Select this option is required or not', $text_domain ),
		'id'   => '_required',
		'type' => 'select',
		'classes' => 'exwo-stgeneral exwo-op-rq',
		'show_option_none' => false,
		'default' => '',
		'options'          => array(
			'' => esc_html__( 'No', $text_domain ),
			'yes'   => esc_html__( 'Yes', $text_domain ),
		),
	) );
	$exwo_options->add_group_field( $group_option, array(
		'name' => esc_html__( 'Minimun selection', $text_domain ),
		'classes' => 'exwo-stgeneral exhide-radio exhide-select exhide-quantity exhide-textbox exhide-textarea exwo-op-min',
		'description' => esc_html__( 'Enter number minimum at least option required', $text_domain ),
		'id'   => '_min_op',
		'type' => 'text',
		'default' => '',
	) );
	$exwo_options->add_group_field( $group_option, array(
		'name' => esc_html__( 'Maximum selection', $text_domain ),
		'classes' => 'exwo-stgeneral exhide-radio exhide-select exhide-quantity exhide-textbox exhide-textarea exwo-op-max',
		'description' => esc_html__( 'Enter number Maximum option can select', $text_domain ),
		'id'   => '_max_op',
		'type' => 'text',
		'default' => '',
	) );
	$exwo_options->add_group_field( $group_option, array(
		'name' => esc_html__( 'Option image', $text_domain ),
		'description' => esc_html__( 'Select yes to replace default choice element with image upload', $text_domain ),
		'id'   => '_enb_img',
		'type' => 'select',
		'classes' => 'exwo-stgeneral exhide-select exhide-quantity exhide-textbox exhide-textarea exwo-op-enbimg',
		'show_option_none' => false,
		'default' => '',
		'options'          => array(
			'' => esc_html__( 'No', $text_domain ),
			'yes'   => esc_html__( 'Yes', $text_domain ),
		),
	) );
	$exwo_options->add_group_field( $group_option, array(
		'name' => esc_html__( 'Options', $text_domain ),
		'classes' => 'exwo-stgeneral exhide-textbox exhide-quantity exhide-textarea exwo-op-ops',
		'description' => esc_html__( 'Set name and price for each option', $text_domain ),
		'id'   => '_options',
		'type' => 'price_options',
		'repeatable'     => true,
	) );
	$exwo_options->add_group_field( $group_option, array(
		'name' => esc_html__( 'Type of price', $text_domain ),
		'description' => '',
		'classes' => 'exwo-stgeneral exshow-textbox exshow-quantity exshow-textarea exwo-hidden exwo-op-tpr',
		'id'   => '_price_type',
		'type' => 'select',
		'show_option_none' => false,
		'default' => '',
		'options'          => array(
			'' => esc_html__( 'Quantity Based', $text_domain ),
			'fixed'   => esc_html__( 'Fixed Amount', $text_domain ),
		),
	) );
	$exwo_options->add_group_field( $group_option, array(
		'name' => esc_html__( 'Price', $text_domain ),
		'classes' => 'exwo-stgeneral exshow-textbox exshow-quantity exshow-textarea exwo-hidden exwo-op-pri',
		'description' => '',
		'id'   => '_price',
		'type' => 'text',
		'default' => '',
		'after_row'     => '',
	) );
	$exwo_options->add_group_field( $group_option, array(
		'name' => esc_html__( 'Display type', $text_domain ),
		'classes' => 'exwo-stgeneral',
		'description' => esc_html__( 'Select Display type of this option, you can select default display type in settings page', $text_domain ),
		'id'   => '_display_type',
		'type' => 'select',
		'default' => '',
		'options'          => array(
			'' => esc_html__( 'Default', $text_domain ),
			'nor' => esc_html__( 'Normal', $text_domain ),
			'accor'   => esc_html__( 'Accordion', $text_domain ),
		),
		'after_row'     => '</div>',
	) );
	
	$exwo_options->add_group_field( $group_option, array(
		'name' => esc_html__( 'Enable Conditional Logic', $text_domain ),
		'description' => esc_html__( 'Enable Conditional Logic for this option', $text_domain ),
		'classes' => 'exwo-stcon-logic',
		'id'   => '_enb_logic',
		'type' => 'checkbox',
		'show_option_none' => false,
		'before_row'     => '<div class="exwo-con-logic">',
		'default' => '',
	) );
	$exwo_options->add_group_field( $group_option, array(
		'name' => esc_html__( 'Conditional Logic', $text_domain ),
		'classes' => 'exwo-stcon-logic',
		'description' => '',
		'id'   => '_con_tlogic',
		'type' => 'select',
		'show_option_none' => false,
		'default' => '',
		'options'          => array(
			''   => esc_html__( 'Show this option if', $text_domain ),
			'hide' => esc_html__( 'Hide this option if', $text_domain ),
		),
	) );
	$exwo_options->add_group_field( $group_option, array(
		'name' => ' ',
		'classes' => 'exwo-stcon-logic',
		'description' => '',
		'id'   => '_con_logic',
		'type' => 'conlogic_options',
		'repeatable'     => true,
		'show_option_none' => false,
	) );
	$exwo_options->add_group_field( $group_option, array(
		'name' => esc_html__( 'Id of option', $text_domain ),
		'description' => '',
		'classes' => 'exwo-stcon-logic exwo-hidden',
		'id'   => '_id',
		'type' => 'text',
		'show_option_none' => false,
		'default' => '',
		'sanitization_cb' => 'exwo_metadata_save_id_html',
		'after_row'     => '</div>',
	) );

	$exwf_proptions = new_cmb2_box( array(
		'id'            => $prefix . 'products',
		'title'         => esc_html__( 'Products', 'woocommerce-food' ),
		'object_types'  => array( 'exwo_glboptions' ),
		'context' => 'side',
		'priority' => 'low',
	) );
	$exwf_proptions->add_field( array(
		'name'        => '',
		'id'          => $prefix . 'product_ids',
		'type'        => 'post_search_text', 
		'desc'       => esc_html__( 'Select product to apply this options', 'woocommerce-food' ),
		'post_type'   => 'product',
		'select_type' => 'checkbox',
		'select_behavior' => 'add',
		'after_field'  => '',
		'sanitization_cb' => 'exwo_save_single_id_prod',
	) );
	//echo get_post_meta( '5743', 'exwo_product_ids', true );exit;
}
function exwo_save_single_id_prod( $value, $field_args, $field ) {
	if ( $value!='' && isset($_POST['post_ID']) && $_POST['post_ID']!='') {
		delete_post_meta($_POST['post_ID'],'exwo_product_ids_arr');
		$arr_ids = explode(",",$value);
		foreach ($arr_ids as $key => $item) {
			add_post_meta($_POST['post_ID'],'exwo_product_ids_arr',str_replace(' ', '',$item));
		}
	}//print_r(get_post_meta(5743,'exwo_product_ids_arr',false));exit;

	return $value;
}
//
function exwo_metadata_save_id_html( $original_value, $args, $cmb2_field ) {
	//print_r(array_filter($_POST['exwo_options']));print_r($_POST);exit;
	if(isset($_POST['exwo_options']) && count($_POST['exwo_options']) == 1){
		if($_POST['exwo_options']['0']['_name'] == ''){
			return $original_value;
		}
	}
	if($original_value==''){
		$original_value = 'exwo-id'.rand(10000,10000000000);
	}
    return $original_value; // Unsanitized value.
}
function exwf_option_sttab_html( $field_args, $field ) {
	$text_domain = exwo_text_domain();
	echo '<p class="exwo-gr-option">
		<a href="javascript:;" class="current" data-add=".exwo-general" data-remove=".exwo-con-logic">'.esc_html__('General',$text_domain).'</a>
		<a href="javascript:;" class="exwo-copypre">'.esc_html__('Copy from previous option',$text_domain).'</a>
		<a href="javascript:;" class="exwo-copy" data-textdis="'.esc_html__('Please save option before copy',$text_domain).'">'.esc_html__('Copy this option',$text_domain).'</a>
		<a href="javascript:;" class="exwo-paste">
		<span class="exwo-paste-tt">'.esc_html__('Paste option',$text_domain).'</span>
		<span class="exwo-paste-mes" style="display:none">'.esc_html__('Completed!',$text_domain).'</span>

		<textarea style="display:none" class="exwo-ctpaste"  placeholder="'.esc_html__('Paste your option here',$text_domain).'"></textarea></a>';
		
		//$product = wc_get_product(get_the_ID());
		//if( is_object($product) && method_exists($product, 'is_type') && $product->is_type( 'variable' ) ) {
		echo '<a href="javascript:;" class="" data-add=".exwo-con-logic" data-remove=".exwo-general">'.esc_html__('Conditional logic',$text_domain).'</a>';
		//}
		
		echo '
	</p>
	<div class="exwo-general">';
}
// Metadata repeat field
function exwocmb2_get_price_type_options( $text_domain,$value = false ) {
	$_list = array(
		''   => esc_html__( 'Quantity Based', $text_domain ),
		'fixed' => esc_html__( 'Fixed Amount', $text_domain ),
	);

	$_options = '';
	foreach ( $_list as $abrev => $state ) {
		$_options .= '<option value="'. $abrev .'" '. selected( $value, $abrev, false ) .'>'. $state .'</option>';
	}

	return $_options;
}

function exwocmb2_render_price_options_field_callback( $field, $value, $object_id, $object_type, $field_type ) {
	$text_domain = exwo_text_domain();
	// make sure we specify each part of the value we need.
	$value = wp_parse_args( $value, array(
		'name' => '',
		'type' => '',
		'def' => '',
		'dis' => '',
		'price' => '',
		'image' => '',
	) );
	?>
	<div class="exwo-options exwo-image-option exhide-select"><p><label for="<?php echo $field_type->_id( '_image' ); ?>'"><?php esc_html_e('Image',$text_domain)?></label></p>
		<?php echo $field_type->file( array(
			'class' => 'cmb2-upload-file regular-text',		
			'name'  => $field_type->_name( '[image]' ),
			'id'    => $field_type->_id( '_image' ),
			'value' => $value['image'],
			'type' => 'hidden',
			'size'            => 45,
			'js_dependencies' => 'media-editor',
			'query_args' => array(
				'type' => array(
					'image/gif',
					'image/jpeg',
					'image/png',
				),
			),
			'preview_size' => array( 30, 30 ),
			'desc'  => '',
		) ); ?>
	</div>
	<div class="exwo-options exwo-name-option"><p><label for="<?php echo $field_type->_id( '_name' ); ?>"><?php esc_html_e('Option name',$text_domain)?></label></p>
		<?php echo $field_type->input( array(
			'class' => '',
			'name'  => $field_type->_name( '[name]' ),
			'id'    => $field_type->_id( '_name' ),
			'value' => $value['name'],
			'type'  => 'text',
			'desc'  => '',
		) ); ?>
	</div>
	<div class="exwo-options exwo-def-option">
		<p><label for="<?php echo $field_type->_id( '_def' ); ?>"><?php esc_html_e('Default',$text_domain)?></label></p>
		<input type="checkbox" class="" name="<?php echo esc_attr($field_type->_name( '[def]' ))?>" id="<?php echo $field_type->_id( '_def' ); ?>" value="yes" data-hash="<?php echo $field->hash_id( '_def' ); ?>" <?php checked($value['def'],'yes');?>>
	</div>
	<div class="exwo-options exwo-dis-option">
		<p><label for="<?php echo $field_type->_id( '_dis' ); ?>"><?php esc_html_e('Disable ?',$text_domain)?></label></p>
		<input type="checkbox" class="" name="<?php echo esc_attr($field_type->_name( '[dis]' ))?>" id="<?php echo $field_type->_id( '_dis' ); ?>" value="yes" data-hash="<?php echo $field->hash_id( '_dis' ); ?>" <?php checked($value['dis'],'yes');?>>
	</div>
	<div class="exwo-options exwo-price-option"><p><label for="<?php echo $field_type->_id( '_price' ); ?>'"><?php esc_html_e('Price',$text_domain)?></label></p>
		<?php echo $field_type->input( array(
			'class' => '',		
			'name'  => $field_type->_name( '[price]' ),
			'id'    => $field_type->_id( '_price' ),
			'value' => $value['price'],
			'type'  => 'text',
			'desc'  => '',
		) ); ?>
	</div>
	<div class="exwo-options exwo-type-option"><p><label for="<?php echo $field_type->_id( '_type' ); ?>'"><?php esc_html_e('Type of price',$text_domain)?></label></p>
		<?php echo $field_type->select( array(
			'class' => '',		
			'name'  => $field_type->_name( '[type]' ),
			'id'    => $field_type->_id( '_type' ),
			'value' => $value['type'],
			'options' => exwocmb2_get_price_type_options($text_domain, $value['type'] ),
			'desc'  => '',
		) ); ?>
	</div>
	<br class="clear">
	<?php
	echo $field_type->_desc( true );

}
add_filter( 'cmb2_render_price_options', 'exwocmb2_render_price_options_field_callback', 10, 5 );
function exwocmb2_sanitize_price_options_callback( $override_value, $value ) {
	echo '<pre>';print_r($value);exit;
	return $value;
}
//add_filter( 'cmb2_sanitize_openclose', 'exwocmb2_sanitize_price_options_callback', 10, 2 );
// option select
function exwocmb2_get_select_type_options( $_list,$value = false, $pos_gr=false ) {
	
	$_options = '';
	$i = 0;
	foreach ( $_list as $abrev => $state ) {
		$disable ='';
		if(isset($pos_gr) && is_numeric($pos_gr)){
			if($abrev=='varia' || $abrev==''){
				$pos_gr = $pos_gr + 1;
			}
			if($pos_gr == $i){
				$disable ='1';
			}
		}
		$_options .= '<option value="'. $abrev .'" '. selected( $value, $abrev, false ) .' '.disabled( $disable, '1', false ).'>'. $state .'</option>';
		$i ++ ;
	}

	return $_options;
}
// condition logic
function exwocmb2_render_conlogic_options_field_callback( $field, $value, $object_id, $object_type, $field_type ) {
	$text_domain = exwo_text_domain();
	// make sure we specify each part of the value we need.
	$value = wp_parse_args( $value, array(
		'type_rel' => '',
		'type_con' => '',
		'type_op' => '',
		'val' => '',
	) );
	$id =  get_the_ID();
	$product = wc_get_product($id);
	?>
	<div class="exwo-options exwo-type_rel-option">
		<?php 
		$list_rule = array(
			''   => esc_html__( 'Or', $text_domain ),
			//'and' => esc_html__( 'And', $text_domain ),
		);
		echo $field_type->select( array(
			'class' => '',
			'name'  => $field_type->_name( '[type_rel]' ),
			'id'    => $field_type->_id( '_type_rel' ),
			'value' => $value['type_rel'],
			'options' => exwocmb2_get_select_type_options($list_rule, $value['type_rel'] ),
			'desc'  => '',
		) ); ?>
	</div>
	<div class="exwo-options exwo-type_op-option">
		<?php 
		$pos_gr = $field->group->index;
		$list_op = $list_val = array();$op_val_list = '';
		$list_op[''] = esc_html__( '--', $text_domain );
		if( is_object($product) && method_exists($product, 'is_type') && $product->is_type( 'variable' ) ) {
			$list_op['varia'] = esc_html__( 'Variation', $text_domain );
		}
		$extra_op = get_post_meta( $id, 'exwo_options', true );
		if(is_array($extra_op) && count($extra_op) > 0){
			foreach ($extra_op as $op) {
				$id_op = isset($op['_id']) ? $op['_id'] : '';
				$name = isset($op['_name']) ? $op['_name'] : '';
				if($id_op!=''){
					$list_op[$id_op] = $name.' - '.$id_op;
				}
				$op_val = isset($op['_options']) ? $op['_options'] : '';
				if(is_array($op_val) && !empty($op_val)){
					foreach ($op_val as $valkey => $val) {
						$val['id'] = $id_op;
						$active = '';
						if($value['type_op']== $val['id'] && $value['val'] == $valkey){
							$active = 'exwo-current';
						}
						$op_val_list .='<li class="'.esc_attr($val['id'].' '.$active).'" data-val="'.esc_attr($valkey.'-'.$val['name']).'">'.$val['name'].'</li>';
					}
				}
			}

		}
		echo $field_type->select( array(
			'class' => '',
			'name'  => $field_type->_name( '[type_op]' ),
			'id'    => $field_type->_id( '_type_op' ),
			'value' => $value['type_op'],
			'options' => exwocmb2_get_select_type_options($list_op, $value['type_op'], $pos_gr ),
			'desc'  => '',
		) ); ?>
	</div>
	<div class="exwo-options exwo-type_con-option">
		<?php 
		$list_con = array(
			''   => esc_html__( 'is', $text_domain ),
			'is_not' => esc_html__( 'is not', $text_domain ),
		);
		echo $field_type->select( array(
			'class' => '',
			'name'  => $field_type->_name( '[type_con]' ),
			'id'    => $field_type->_id( '_type_con' ),
			'value' => $value['type_con'],
			'options' => exwocmb2_get_select_type_options($list_con, $value['type_con'] ),
			'desc'  => '',
		) ); ?>
	</div>
	<div class="exwo-options exwo-val-option">
		<?php 
		$ar_variations = $variations = array();
		$ar_variations[] = '';
		if( is_object($product) && method_exists($product, 'is_type') && $product->is_type( 'variable' ) ) {
			$variations = $product->get_children();
			/*if(is_array($variations)){
				foreach ($variations as $variation) {
					if(count($variations) > 1){
						$ar_variations[$variation] = $variation.' - '.get_the_title($variation);
					}
				}
			}*/
		}
		/*echo $field_type->select( array(
			'class' => '',		
			'name'  => $field_type->_name( '[val]' ),
			'id'    => $field_type->_id( '_val' ),
			'value' => $value['val'],
			'options' => exwocmb2_get_select_type_options($ar_variations, $value['val'] ),
			'desc'  => '',
		) );*/
		echo $field_type->input( array(
			'class' => 'exwo-conval',		
			'name'  => $field_type->_name( '[val]' ),
			'id'    => $field_type->_id( '_val' ),
			'value' => $value['val'],
			'type'  => 'text',
			'desc'  => '',
			'readonly' => 'readonly',
		) );
		if((is_array($variations) && !empty($variations)) || $op_val_list!=''){
			echo '<ul class="exwo-list-value">';
				echo $op_val_list;
				if(is_array($variations) && !empty($variations)){
					foreach ($variations as $variation) {
						$active = '';
						if($value['val'] == $variation){
							$active = 'exwo-current';
						}
						echo '<li class="exwo-variation '.esc_attr($active).'" data-val="'.esc_attr($variation).'">'.$variation.'-'.get_the_title($variation).'</li>';
					}
				}
			echo '</ul>';
		}
		?>
	</div>
	<br class="clear">
	<?php
	echo $field_type->_desc( true );

}
add_filter( 'cmb2_render_conlogic_options', 'exwocmb2_render_conlogic_options_field_callback', 12, 5 );
add_filter( 'cmb2_sanitize_conlogic_options', 'exwosanitize' , 10, 5 );
add_filter( 'cmb2_types_esc_conlogic_options', 'exwoescape' , 10, 4 );

add_filter( 'cmb2_sanitize_price_options', 'exwosanitize' , 10, 5 );
add_filter( 'cmb2_types_esc_price_options', 'exwoescape' , 10, 4 );
function exwosanitize( $check, $meta_value, $object_id, $field_args, $sanitize_object ) {

	// if not repeatable, bail out.
	if ( ! is_array( $meta_value ) || ! $field_args['repeatable'] ) {
		return $check;
	}

	foreach ( $meta_value as $key => $val ) {
		$meta_value[ $key ] = array_filter( array_map( 'sanitize_text_field', $val ) );
	}

	return array_filter( $meta_value );
}

function exwoescape( $check, $meta_value, $field_args, $field_object ) {
	// if not repeatable, bail out.
	if ( ! is_array( $meta_value ) || ! $field_args['repeatable'] ) {
		return $check;
	}

	foreach ( $meta_value as $key => $val ) {
		$meta_value[ $key ] = array_filter( array_map( 'esc_attr', $val ) );
	}

	return array_filter( $meta_value );
}
