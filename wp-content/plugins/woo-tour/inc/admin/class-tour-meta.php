<?php
class wootour_Meta {
	public function __construct()
    {
		add_action( 'init', array($this,'init'), 0);
    }
	function init(){
		// Variables
		add_filter( 'exc_mb_meta_boxes', array($this,'wootour_metadata') );
		add_action( 'init', array( &$this, 'register_category_taxonomies' ) );
		//create child Variation price
		add_action( 'woocommerce_product_after_variable_attributes', array( &$this, 'variation_settings_fields'), 10, 3 );
		// Save Variation Settings
		add_action( 'woocommerce_save_product_variation', array( &$this, 'save_variation_settings_fields'), 10, 2 );
		// Add Variation
		add_filter( 'woocommerce_available_variation', array( &$this, 'load_variation_settings_fields'),15, 3 );
		add_filter( 'save_post', array($this,'save_metadata') );
	}
	/**
	 * Convert date format
	 *
	*/
	function save_metadata($post_id){
		if(get_option('wt_date_picker')=='dmy'){
			$_POST['wt_start']['exc_mb-field-0'] = isset($_POST['wt_start']['exc_mb-field-0']) ? str_replace("/","-",$_POST['wt_start']['exc_mb-field-0']) : '';
			$_POST['wt_expired']['exc_mb-field-0'] = isset($_POST['wt_expired']['exc_mb-field-0']) ?  str_replace("/","-",$_POST['wt_expired']['exc_mb-field-0']) : '';
			$_POST['wt_disabledate'] = isset($_POST['wt_disabledate']) ? str_replace("/","-",$_POST['wt_disabledate'] ) : '';
			$_POST['wt_customdate'] = isset($_POST['wt_customdate']) ? str_replace("/","-",$_POST['wt_customdate'] ) : '';
			if( isset($_POST['wt_discount']) && is_array($_POST['wt_discount']) && count((array)$_POST['wt_discount']) > 0){
				foreach($_POST['wt_discount'] as $key=>$value){
					$_POST['wt_discount'][$key]['wt_disc_start']['exc_mb-field-0']=str_replace("/","-",$_POST['wt_discount'][$key]['wt_disc_start']['exc_mb-field-0']);
					$_POST['wt_discount'][$key]['wt_disc_end']['exc_mb-field-0']=str_replace("/","-",$_POST['wt_discount'][$key]['wt_disc_end']['exc_mb-field-0']);
				}
			}
			if( isset($_POST['wt_p_season']) && is_array($_POST['wt_p_season']) && count($_POST['wt_p_season']) > 0){
				foreach($_POST['wt_p_season'] as $key=>$value){
					$_POST['wt_p_season'][$key]['wt_p_start']['exc_mb-field-0']=str_replace("/","-",$_POST['wt_p_season'][$key]['wt_p_start']['exc_mb-field-0']);
					$_POST['wt_p_season'][$key]['wt_p_end']['exc_mb-field-0']=str_replace("/","-",$_POST['wt_p_season'][$key]['wt_p_end']['exc_mb-field-0']);
				}
			}
		}
	}
	/**
	 * Create new fields for variations
	 *
	*/
	function variation_settings_fields( $loop, $variation_data, $variation ) {
		woocommerce_wp_text_input( 
			array( 
				'id'          => '_min_adult[' . $variation->ID . ']', 
				'label'       => esc_html__( 'Minimum adult','woo-tour' ), 
				'desc_tip'    => 'true',
				'wrapper_class' 	  => 'form-row form-row-first',
				'placeholder' => esc_html__('Enter number', 'woo-tour' ),
				'description' => esc_html__( 'Minimum adult required', 'woo-tour' ),
				'value'       => get_post_meta( $variation->ID, '_min_adult', true ),
			)
		);
		woocommerce_wp_text_input( 
			array( 
				'id'          => '_max_adult[' . $variation->ID . ']', 
				'label'       => esc_html__( 'Maximum adult','woo-tour' ), 
				'desc_tip'    => 'true',
				'wrapper_class' 	  => 'form-row form-row-last',
				'placeholder' => esc_html__('Enter number', 'woo-tour' ),
				'description' => esc_html__( 'Maximum adult required', 'woo-tour' ),
				'value'       => get_post_meta( $variation->ID, '_max_adult', true ),
			)
		);
		// _child_price Field
		woocommerce_wp_text_input( 
			array( 
				'id'          => '_child_price[' . $variation->ID . ']', 
				'label'       => esc_html__( 'Tour price for Children','woo-tour' ), 
				'desc_tip'    => 'true',
				'wrapper_class' 	  => 'form-row form-row-first',
				'placeholder' => esc_html__('Enter number', 'woo-tour' ),
				'description' => esc_html__( 'Enter OFF to hide this field', 'woo-tour' ),
				'value'       => get_post_meta( $variation->ID, '_child_price', true ),
			)
		);
		woocommerce_wp_text_input( 
			array( 
				'id'          => '_child_price_sale[' . $variation->ID . ']', 
				'label'       => esc_html__( 'Sale price for Children','woo-tour' ), 
				'desc_tip'    => 'true',
				'wrapper_class' 	  => 'form-row form-row-last',
				'placeholder' => esc_html__('Enter number', 'woo-tour' ),
				'description' => esc_html__( 'Enter OFF to hide this field', 'woo-tour' ),
				'value'       => get_post_meta( $variation->ID, '_child_price_sale', true ),
			)
		);
		// _infant_price Field
		woocommerce_wp_text_input( 
			array( 
				'id'          => '_infant_price[' . $variation->ID . ']', 
				'label'       => esc_html__( 'Tour price for Infant','woo-tour' ), 
				'desc_tip'    => 'true',
				'wrapper_class' 	  => 'form-row form-row-first',
				'placeholder' => esc_html__('Enter number', 'woo-tour' ),
				'description' => esc_html__( 'Enter OFF to hide this field', 'woo-tour' ),
				'value'       => get_post_meta( $variation->ID, '_infant_price', true ),
			)
		);
		woocommerce_wp_text_input( 
			array( 
				'id'          => '_infant_price_sale[' . $variation->ID . ']', 
				'label'       => esc_html__( 'Sale price for Infant','woo-tour' ), 
				'desc_tip'    => 'true',
				'wrapper_class' 	  => 'form-row form-row-last',
				'placeholder' => esc_html__('Enter number', 'woo-tour' ),
				'description' => esc_html__( 'Enter Sale price', 'woo-tour' ),
				'value'       => get_post_meta( $variation->ID, '_infant_price_sale', true ),
			)
		);
		if(get_option('wt_ctfieldprice') == 1){
			$label1 = explode("|",get_option('wt_ctfield1_info'));
			$label2 = explode("|",get_option('wt_ctfield2_info'));
			// custom price Field 1
			woocommerce_wp_text_input( 
				array( 
					'id'          => '_ctfield1_price[' . $variation->ID . ']', 
					'label'       => esc_html__( 'Tour price for ','woo-tour' ).$label1[0], 
					'desc_tip'    => 'true',
					'wrapper_class' 	  => 'form-row form-row-first',
					'placeholder' => esc_html__('Enter number', 'woo-tour' ),
					'description' => esc_html__( 'Enter OFF to hide this field', 'woo-tour' ),
					'value'       => get_post_meta( $variation->ID, '_ctfield1_price', true ),
				)
			);
			woocommerce_wp_text_input( 
				array( 
					'id'          => '_ctfield1_price_sale[' . $variation->ID . ']', 
					'label'       => esc_html__( 'Sale price','woo-tour' ), 
					'desc_tip'    => 'true',
					'wrapper_class' 	  => 'form-row form-row-last',
					'placeholder' => esc_html__('Enter number', 'woo-tour' ),
					'description' => esc_html__( 'Enter Sale price', 'woo-tour' ),
					'value'       => get_post_meta( $variation->ID, '_ctfield1_price_sale', true ),
				)
			);
			// custom price Field 2
			woocommerce_wp_text_input( 
				array( 
					'id'          => '_ctfield2_price[' . $variation->ID . ']', 
					'label'       => esc_html__( 'Tour price for ','woo-tour' ).$label2[0], 
					'desc_tip'    => 'true',
					'wrapper_class' 	  => 'form-row form-row-first',
					'placeholder' => esc_html__('Enter number', 'woo-tour' ),
					'description' => esc_html__( 'Enter OFF to hide this field', 'woo-tour' ),
					'value'       => get_post_meta( $variation->ID, '_ctfield2_price', true ),
				)
			);
			woocommerce_wp_text_input( 
				array( 
					'id'          => '_ctfield2_price_sale[' . $variation->ID . ']', 
					'label'       => esc_html__( 'Sale price ','woo-tour' ), 
					'desc_tip'    => 'true',
					'wrapper_class' 	  => 'form-row form-row-last',
					'placeholder' => esc_html__('Enter number', 'woo-tour' ),
					'description' => esc_html__( 'Enter Sale price', 'woo-tour' ),
					'value'       => get_post_meta( $variation->ID, '_ctfield2_price_sale', true ),
				)
			);
		}
		$dis_weekdays = get_post_meta( $variation->ID, '_dis_weekdays', true );
		echo '<p class="form-row exef-disday-tit form-row-full">'.esc_html__('Disable this variation on Week day').'</p>';
		woocommerce_wp_checkbox( 
			array( 
				'id'            => '_mond['.$variation->ID.']', 
				'label'         => esc_html__('Monday', 'woo-tour' ),
				'wrapper_class' 	  => 'form-row exwt-disday',
				'description'   => '',
				'value'         => (isset($dis_weekdays['_mond']) && $dis_weekdays['_mond']=='1' ? 'yes' : ''), 
			)
		);
		woocommerce_wp_checkbox( 
			array( 
				'id'            => '_tued['.$variation->ID.']', 
				'label'         => esc_html__('Tuesday', 'woo-tour' ),
				'wrapper_class' 	  => 'form-row exwt-disday',
				'description'   => '',
				'value'         => (isset($dis_weekdays['_tued']) && $dis_weekdays['_tued']=='2' ? 'yes' : ''), 
			)
		);
		woocommerce_wp_checkbox( 
			array( 
				'id'            => '_wedd['.$variation->ID.']', 
				'label'         => esc_html__('Wednesday', 'woo-tour' ),
				'wrapper_class' 	  => 'form-row exwt-disday',
				'description'   => '',
				'value'         => (isset($dis_weekdays['_wedd']) && $dis_weekdays['_wedd']=='3' ? 'yes' : ''), 
			)
		);
		woocommerce_wp_checkbox( 
			array( 
				'id'            => '_thurd['.$variation->ID.']', 
				'label'         => esc_html__('Thursday', 'woo-tour' ),
				'wrapper_class' 	  => 'form-row exwt-disday',
				'description'   => '',
				'value'         => (isset($dis_weekdays['_thurd']) && $dis_weekdays['_thurd']=='4' ? 'yes' : ''), 
			)
		);
		woocommerce_wp_checkbox( 
			array( 
				'id'            => '_frid['.$variation->ID.']', 
				'label'         => esc_html__('Friday', 'woo-tour' ),
				'wrapper_class' 	  => 'form-row exwt-disday',
				'description'   => '',
				'value'         => (isset($dis_weekdays['_frid']) && $dis_weekdays['_frid']=='5' ? 'yes' : ''), 
			)
		);
		woocommerce_wp_checkbox( 
			array( 
				'id'            => '_satd['.$variation->ID.']', 
				'label'         => esc_html__('Saturday', 'woo-tour' ),
				'wrapper_class' 	  => 'form-row exwt-disday',
				'description'   => '',
				'value'         => (isset($dis_weekdays['_satd']) && $dis_weekdays['_satd']=='6' ? 'yes' : ''), 
			)
		);
		woocommerce_wp_checkbox( 
			array( 
				'id'            => '_sund['.$variation->ID.']', 
				'label'         => esc_html__('Sunday', 'woo-tour' ),
				'wrapper_class' 	  => 'form-row exwt-disday',
				'description'   => '',
				'value'         => (isset($dis_weekdays['_sund']) && $dis_weekdays['_sund']=='8' ? 'yes' : ''), 
			)
		);
		$mt_varst= get_option('wt_dismulti_varstock');
		if($mt_varst=='sp_only'){
			woocommerce_wp_text_input( 
				array( 
					'id'          => '_idva_reduce[' . $variation->ID . ']', 
					'label'       => esc_html__( 'Reduce stock for special variation','woo-tour' ), 
					'desc_tip'    => 'true',
					'wrapper_class' 	  => 'form-row form-row-full',
					'placeholder' => esc_html__('Enter id of variation you want to reduce stock', 'woo-tour' ),
					'description' => esc_html__( 'Enter id of variation', 'woo-tour' ),
					'value'       => get_post_meta( $variation->ID, '_idva_reduce', true ),
				)
			);
		}
	}
	/**
	 * Save new fields for variations
	 *
	*/
	function save_variation_settings_fields( $post_id ) {
		$_min_adult = $_POST['_min_adult'][ $post_id ];
		if( isset( $_min_adult ) ) {
			update_post_meta( $post_id, '_min_adult', esc_attr( $_min_adult ) );
		}
		$_max_adult = $_POST['_max_adult'][ $post_id ];
		if( isset( $_max_adult ) ) {
			update_post_meta( $post_id, '_max_adult', esc_attr( $_max_adult ) );
		}

		$_child_price = $_POST['_child_price'][ $post_id ];
		if( isset( $_child_price ) ) {
			update_post_meta( $post_id, '_child_price', esc_attr( $_child_price ) );
		}
		$_infant_price = $_POST['_infant_price'][ $post_id ];
		if( isset( $_infant_price ) ) {
			update_post_meta( $post_id, '_infant_price', esc_attr( $_infant_price ) );
		}
		
		$_child_price_sale = $_POST['_child_price_sale'][ $post_id ];
		if( isset( $_child_price_sale ) ) {
			update_post_meta( $post_id, '_child_price_sale', esc_attr( $_child_price_sale ) );
		}
		$_infant_price_sale = $_POST['_infant_price_sale'][ $post_id ];
		if( isset( $_infant_price_sale ) ) {
			update_post_meta( $post_id, '_infant_price_sale', esc_attr( $_infant_price_sale ) );
		}
		
		$_ctfield1_price = $_POST['_ctfield1_price'][ $post_id ];
		if( isset( $_ctfield1_price ) ) {
			update_post_meta( $post_id, '_ctfield1_price', esc_attr( $_ctfield1_price ) );
		}
		$_ctfield1_price_sale = $_POST['_ctfield1_price_sale'][ $post_id ];
		if( isset( $_ctfield1_price_sale ) ) {
			update_post_meta( $post_id, '_ctfield1_price_sale', esc_attr( $_ctfield1_price_sale ) );
		}
		
		$_ctfield2_price = $_POST['_ctfield2_price'][ $post_id ];
		if( isset( $_ctfield2_price ) ) {
			update_post_meta( $post_id, '_ctfield2_price', esc_attr( $_ctfield2_price ) );
		}
		$_ctfield2_price_sale = $_POST['_ctfield2_price_sale'][ $post_id ];
		if( isset( $_ctfield2_price_sale ) ) {
			update_post_meta( $post_id, '_ctfield2_price_sale', esc_attr( $_ctfield2_price_sale ) );
		}

		$_idva_reduce = $_POST['_idva_reduce'][ $post_id ];
		if( isset( $_idva_reduce ) ) {
			update_post_meta( $post_id, '_idva_reduce', esc_attr( $_idva_reduce ) );
		}
		// Save disable days
		$arr_disday['_mond'] = isset( $_POST['_mond'][ $post_id ]) && $_POST['_mond'][ $post_id ]=='yes' ? '1' : '';
		$arr_disday['_tued'] = isset( $_POST['_tued'][ $post_id ]) && $_POST['_tued'][ $post_id ]=='yes' ? '2' : '';
		$arr_disday['_wedd'] = isset( $_POST['_wedd'][ $post_id ]) && $_POST['_wedd'][ $post_id ]=='yes' ? '3' : '';
		$arr_disday['_thurd'] = isset( $_POST['_thurd'][ $post_id ]) && $_POST['_thurd'][ $post_id ]=='yes' ? '4' : '';
		$arr_disday['_frid'] = isset( $_POST['_frid'][ $post_id ]) && $_POST['_frid'][ $post_id ]=='yes' ? '5' : '';
		$arr_disday['_satd'] = isset( $_POST['_satd'][ $post_id ]) && $_POST['_satd'][ $post_id ]=='yes' ? '6' : '';
		$arr_disday['_sund'] = isset( $_POST['_sund'][ $post_id ]) && $_POST['_sund'][ $post_id ]=='yes' ? '8' : '';
		if( !empty($arr_disday) ) {
			update_post_meta( $post_id, '_dis_weekdays', $arr_disday );
		}

	}
	/**
	 * Add custom fields for variations
	 *
	*/
	function load_variation_settings_fields( $data,$product,$_product_vari ) {
		//global $product;
		global $sitepress;
		$current_language = $sitepress->get_current_language();	
		$adu ="Adult: ";	
		$chil ="Children: ";	
		$Infant ="Infant: ";	
		if ($current_language == 'vi') {
			$adu ="Người lớn :";	
			$chil ="Trẻ em: ";	
			$Infant ="Sơ sinh: ";	
		}
		if ($current_language == 'en') {
			$adu ="Adult: ";	
			$chil ="Children: ";	
			$Infant ="Infant: ";	
		}
		$data['_adult_price'] = $_product_vari->get_price_html();
		// adult
		$id_pro = $_product_vari->get_parent_id();
		$wt_adult_max = get_post_meta( $id_pro, 'wt_adult_max', true );
		$sl_value = '';
		$al = get_option('wt_default_adl')!='' ? get_option('wt_default_adl') : 5;
		if(is_numeric ($wt_adult_max)){
			$al = $wt_adult_max;
		}
		$wt_adult_min = get_post_meta( $id_pro, 'wt_adult_min', true );
		$wt_adult_min = $wt_adult_min!='' && $wt_adult_min >= 0 ? $wt_adult_min : 1;
		for($i=$wt_adult_min; $i <= $al ; $i++){
			$sl_value .= '<option value="'.$i.'">'.$i.'</option>';
		}
		
		$wt_adult_label = get_post_meta( $id_pro, 'wt_adult_label', true ) ;
		$wt_adult_label = $wt_adult_label!='' ? $wt_adult_label.': ' : esc_html__($adu,'woo-tour');
		$html_adult = exwt_quantity_html('wt_number_adult',$sl_value,'1',$wt_adult_min,$al);
		$data['_adult_select'] = $html_adult;
		$data['_adult_label'] = $wt_adult_label;
		// duplicate the line for each field
		$wt_child = $child_price = get_post_meta( $data[ 'variation_id' ], '_child_price', true );
		$wt_child_max = get_post_meta( $id_pro, 'wt_child_max', true ) ;
		$wt_def_childf = get_option( 'wt_def_childf' ) ;
		if( ($wt_child!='OFF' && $wt_child!='') || ($wt_child=='' && $wt_def_childf!='off') ){
			$sl_cvalue = '';//'<option value="">0</option>';
			$l = get_option('wt_default_child')!='' ? get_option('wt_default_child') : 5;
			if(is_numeric ($wt_child_max)){$l = $wt_child_max;}
			if(!is_numeric($wt_child_max)){ $wt_child_max = 5;}
			$wt_child_max = $wt_child_max * 1;

			$_min = get_post_meta( $id_pro, 'wt_child_min', true );
			$_min = $_min > 0 ? $_min : 0;
			if($_min > 0 ){$sl_cvalue ='';}
			//if($_min == 0){ $_min = 1;}	

			for($i=$_min; $i <= $l ; $i++){$sl_cvalue .= '<option value="'.$i.'">'.$i.'</option>';}
			$html_child  = exwt_quantity_html('wt_number_child',$sl_cvalue,'0',$_min,$l);
			$if_sale = get_post_meta( $data[ 'variation_id' ], '_child_price_sale', true );
			$child_label = get_post_meta( $id_pro, 'wt_child_label', true ) ;
			$child_label = $child_label!='' ? $child_label.': ' : esc_html__($chil,'woo-tour');
			$child_label = '<span class="lb-pric">'.$child_label.'</span>';
			$html_child_price = $child_price=='' ? $child_label : $child_label.wt_addition_price_html($child_price,$span='1',$if_sale,true);
			$data['_child_price'] = exwt_table_variation_html($html_child_price, $html_child, 'wt-child-price');
		}
		
		$wt_infant = $infant_price = get_post_meta( $data[ 'variation_id' ], '_infant_price', true );
		$wt_infant_max = get_post_meta( $id_pro, 'wt_infant_max', true ) ;
		$wt_def_intff = get_option( 'wt_def_intff' ) ;
		if( ($wt_infant!='OFF' && $wt_infant!='') || ($wt_infant=='' && $wt_def_intff!='off') ){
			$sl_ivalue = '';//'<option value="">0</option>';
			$l = get_option('wt_default_inf') !='' ? get_option('wt_default_inf') : 5 ;
			if(is_numeric ($wt_infant_max)){$l = $wt_infant_max;}
			if(!is_numeric($wt_infant_max)){ $wt_infant_max = 5;}
			$wt_infant_max = $wt_infant_max * 1;

			$_min = get_post_meta( $id_pro, 'wt_infant_min', true );
			$_min = $_min > 0 ? $_min : 0;
			if($_min > 0 ){$sl_ivalue ='';}
			//if($_min == 0){ $_min = 1;}	

			for($i=$_min; $i <= $l ; $i++){$sl_ivalue .= '<option value="'.$i.'">'.$i.'</option>';}
			$html_infant = exwt_quantity_html('wt_number_infant',$sl_ivalue,'0',$_min,$l);
			$if_sale = get_post_meta( $data[ 'variation_id' ], '_infant_price_sale', true );
			$infant_label = get_post_meta( $id_pro, 'wt_infant_label', true ) ;
			$infant_label = $infant_label!='' ? $infant_label.': ' : esc_html__($Infant,'woo-tour');
			$infant_label = '<span class="lb-pric">'.$infant_label.'</span>';
			$html_infant_price = $infant_price=='' ? $infant_label : $infant_label.wt_addition_price_html($infant_price,$span='1',$if_sale,true);
			$data['_infant_price'] =  exwt_table_variation_html($html_infant_price, $html_infant, 'wt-infant-price');
		}
		// ct1
		$_ctfield1_price = $_ctfield1_price = get_post_meta( $data[ 'variation_id' ], '_ctfield1_price', true );
		$wt_ctps1_max = get_post_meta( $id_pro, 'wt_ctps1_max', true ) ;
		$label1 = explode("|",get_option('wt_ctfield1_info'));
		$dfl_ct1 = isset($label1[2]) ? $label1[2] : '';
		$dfm_ct1 = isset($label1[1]) ? $label1[1] : '';
		if(isset($label1[0]) && $label1[0]!=''){
			$dfl_ct1 = preg_replace('/\s+/', '', $dfl_ct1);
			if( ($_ctfield1_price!='OFF' && $_ctfield1_price!='') || ($_ctfield1_price=='' && $dfl_ct1!='hide') ){
				$sl_1value = '';//'<option value="">0</option>';
				$l = $dfm_ct1 !='' ? $dfm_ct1 : 5 ;
				if(is_numeric ($wt_ctps1_max)){$l = $wt_ctps1_max;}

				$_min = get_post_meta( $id_pro, 'wt_ctps1_min', true );
				$_min = $_min > 0 ? $_min : 0;
				if($_min > 0 ){$sl_1value ='';}
				//if($_min == 0){ $_min = 1;}	

				for($i=$_min; $i <= $l ; $i++){$sl_1value .= '<option value="'.$i.'">'.$i.'</option>';}
				$html_ct1 = exwt_quantity_html('wt_number_ct1',$sl_1value,'0',$_min,$l);
				$ct1_sale = get_post_meta( $data[ 'variation_id' ], '_ctfield1_price_sale', true );
				$ct1_label = get_post_meta( $id_pro, 'wt_ctps1_label', true ) ;
				$ct1_label = $ct1_label!='' ? $ct1_label.': ' : $label1[0].': ';
				$ct1_label = '<span class="lb-pric">'.$ct1_label.'</span>';
				$html_ct1_price = $_ctfield1_price=='' ? $ct1_label : $ct1_label.wt_addition_price_html($_ctfield1_price,$span='1',$ct1_sale,true);
				$data['_ct1_price'] =  exwt_table_variation_html($html_ct1_price, $html_ct1, 'wt-ct1-price');
			}
		}
		// ct2
		$_ctfield2_price = $_ctfield2_price = get_post_meta( $data[ 'variation_id' ], '_ctfield2_price', true );
		$wt_ctps2_max = get_post_meta( $id_pro, 'wt_ctps2_max', true ) ;
		$label2 = explode("|",get_option('wt_ctfield2_info'));
		$dfl_ct2 = isset($label2[2]) ? $label2[2] : '';
		$dfm_ct2 = isset($label2[1]) ? $label2[1] : '';
		if(isset($label2[0]) && $label2[0]!=''){
			$dfl_ct2 = preg_replace('/\s+/', '', $dfl_ct2);
			if( ($_ctfield2_price!='OFF' && $_ctfield2_price!='') || ($_ctfield2_price=='' && $dfl_ct2!='hide') ){
				$sl_2value = '';//'<option value="">0</option>';
				$l = $dfm_ct2 !='' ? $dfm_ct2 : 5 ;
				if(is_numeric ($wt_ctps2_max)){$l = $wt_ctps2_max;}

				$_min = get_post_meta( $id_pro, 'wt_ctps2_min', true );
				$_min = $_min > 0 ? $_min : 0;
				if($_min > 0 ){$sl_2value ='';}
				//if($_min == 0){ $_min = 1;}	

				for($i=$_min; $i <= $l ; $i++){$sl_2value .= '<option value="'.$i.'">'.$i.'</option>';}
				$html_ct2 = exwt_quantity_html('wt_number_ct2',$sl_2value,'0',$_min,$l);
				$ct2_sale = get_post_meta( $data[ 'variation_id' ], '_ctfield2_price_sale', true );
				$ct2_label = get_post_meta( $id_pro, 'wt_ctps2_label', true ) ;
				$ct2_label = $ct2_label!='' ? $ct2_label.': ' : $label2[0].': ';
				$ct2_label = '<span class="lb-pric">'.$ct2_label.'</span>';
				$html_ct2_price = $_ctfield2_price=='' ? $ct2_label : $ct2_label.wt_addition_price_html($_ctfield2_price,$span='1',$ct2_sale,true);
				$data['_ct2_price'] =  exwt_table_variation_html($html_ct2_price, $html_ct2, 'wt-ct2-price');
			}
		}
		// min max
		$data['_min_adult'] = get_post_meta( $data[ 'variation_id' ], '_min_adult', true );
		$data['_max_adult'] = get_post_meta( $data[ 'variation_id' ], '_max_adult', true );
		// disable days
		//$data['_dis_weekdays'] = json_encode(get_post_meta( $data[ 'variation_id' ], '_dis_weekdays', true ));

		return $data;
	}
	
	function wootour_metadata(array $meta_boxes){
		$time_settings = array(	
			array( 'id' => 'wt_start', 'name' => esc_html__('Start Date:', 'woo-tour'), 'cols' => 3, 'type' => 'date_unix' ,'desc' => esc_html__('Select start date for this tour', 'woo-tour'), 'repeatable' => false, 'multiple' => false ),
			array( 'id' => 'wt_expired', 'name' => esc_html__('Expired Date:', 'woo-tour'), 'cols' => 3, 'type' => 'date_unix' ,'desc' => esc_html__('Select expired date for this tour', 'woo-tour'), 'repeatable' => false, 'multiple' => false ),
			array( 
				'id' => 'wt_weekday', 
				'name' => esc_html__('Weekdays:', 'woo-tour'), 
				'type' => 'select', 'options' => array( 
					'2' => esc_html__('Monday', 'woo-tour'), 
					'3' => esc_html__('Tuesday', 'woo-tour'), 
					'4' => esc_html__('Wednesday', 'woo-tour'), 
					'5' => esc_html__('Thursday', 'woo-tour'), 
					'6' => esc_html__('Friday', 'woo-tour'), 
					'7' => esc_html__('Saturday', 'woo-tour'), 
					'1' => esc_html__('Sunday', 'woo-tour') 
				),
				'cols' => 6,
				'desc' => esc_html__('Select special days of week available for this tour', 'woo-tour') ,
				'multiple' => true 
			),
			array( 'id' => 'wt_disabledate', 'name' => esc_html__('Disable dates:', 'woo-tour'), 'cols' => 4, 'type' => 'date_unix','desc' => esc_html__('Select dates you want to disable, Ex: 01/01/2024', 'woo-tour') , 'repeatable' => true, 'multiple' => true ),	
			
			array( 'id' => 'wt_customdate', 'name' => esc_html__('Special Dates:', 'woo-tour'), 'cols' => 4, 'type' => 'date_unix','desc' => esc_html__('If your tour only contain one or some dates, you can select it from this option', 'woo-tour') , 'repeatable' => true, 'multiple' => true ),
			array( 'id' => 'def_stock', 'name' => esc_html__('Number of ticket', 'woo-tour'), 'cols' => 4, 'type' => 'text' ,'desc' => esc_html__('Number of ticket for each date of tour ( enter number )', 'woo-tour'), 'repeatable' => false, 'multiple' => false ),
		);
		$info_settings = array(	
			array( 'id' => 'wt_duration', 'name' => esc_html__('Duration', 'woo-tour'), 'cols' => 6, 'type' => 'text' ,'desc' => esc_html__('Enter duration information, Ex: 2 days', 'woo-tour'), 'repeatable' => false, 'multiple' => false ),
			array( 'id' => 'wt_type', 'name' => esc_html__('Tour type', 'woo-tour'), 'cols' => 6, 'type' => 'text' ,'desc' => esc_html__('Enter tour type information, Ex: Weekly Tour', 'woo-tour'), 'repeatable' => false, 'multiple' => false ),
			array( 'id' => 'wt_transport', 'name' => esc_html__('Transport', 'woo-tour'), 'cols' => 6, 'type' => 'text' ,'desc' => esc_html__('Enter transport information, Ex: Plane, Boat, Bus', 'woo-tour'), 'repeatable' => false, 'multiple' => false ),
			array( 'id' => 'wt_group_size', 'name' => esc_html__('Group size', 'woo-tour'), 'cols' => 6, 'type' => 'text' ,'desc' => esc_html__('Enter Group size information, Ex: Min 2 Max 10', 'woo-tour'), 'repeatable' => false, 'multiple' => false ),
			array( 'id' => 'wt_accom_service', 'name' => esc_html__('Accompanied service', 'woo-tour'), 'type' => 'text' ,'desc' => esc_html__('Add Accompanied service Info for this tour', 'woo-tour'), 'repeatable' => true, 'multiple' => true ),
			//array( 'id' => 'wt_eventcolor', 'name' => esc_html__('Color', 'woo-tour'), 'type' => 'colorpicker', 'repeatable' => false, 'multiple' => true ),
			
		);
		if(get_option('wt_schedu_map') == 1){
			$info_settings_map = array(
				array( 'id' => 'wt_schedu', 'name' => esc_html__('Schedule', 'woo-tour'), 'type' => 'text' ,'desc' => esc_html__('Add Schedule for this tour', 'woo-tour'), 'repeatable' => true, 'multiple' => true ),
				array( 'id' => 'wt_adress', 'name' => esc_html__('Address', 'woo-tour'), 'type' => 'text' ,'desc' => esc_html__('Enter Location Address', 'woo-tour'), 'repeatable' => false, 'multiple' => false ),
				array( 'id' => 'wt_latitude_longitude', 'name' => esc_html__('Latitude and Longitude', 'woo-tour'), 'type' => 'text' ,'desc' => esc_html__('Physical address of your tour location, you can see how to find physical address here: https://support.google.com/maps/answer/18539. Enter Latitude and Longitude, separated by a comma. Ex for London: 42.9869502,-81.243177', 'woo-tour'), 'repeatable' => false, 'multiple' => false ),
			);
			$info_settings = array_merge($info_settings, $info_settings_map);
		}
		$addition_settings = array(	
			array( 'id' => 'wt_adult_min', 'name' => esc_html__('Minimum adult quantity', 'woo-tour'), 'cols' => 6, 'type' => 'text' ,'desc' => esc_html__('Set Minimum adult quantity required, Default:1', 'woo-tour'), 'repeatable' => false, 'multiple' => false ),
			array( 'id' => 'wt_adult_max', 'name' => esc_html__('Maximum adult quantity', 'woo-tour'), 'cols' => 6, 'type' => 'text' ,'desc' => esc_html__('Set Maximum adult quantity required, Default:5', 'woo-tour'), 'repeatable' => false, 'multiple' => false ),
			array( 'id' => 'wt_child', 'name' => esc_html__('Children price', 'woo-tour'), 'cols' => 3, 'type' => 'text' ,'desc' => esc_html__('Enter price or OFF to hide this field', 'woo-tour'), 'repeatable' => false, 'multiple' => false ),
			array( 'id' => 'wt_child_sale', 'name' => esc_html__('Sale price', 'woo-tour'), 'cols' => 3, 'type' => 'text' ,'desc' => esc_html__('Enter Sale price for Children', 'woo-tour'), 'repeatable' => false, 'multiple' => false ),
			array( 'id' => 'wt_child_min', 'name' => esc_html__('Min quantity', 'woo-tour'), 'cols' => 3, 'type' => 'text' ,'desc' => esc_html__('Set Minimum quantity required', 'woo-tour'), 'repeatable' => false, 'multiple' => false ),
			array( 'id' => 'wt_child_max', 'name' => esc_html__('Max quantity', 'woo-tour'), 'cols' => 3, 'type' => 'text' ,'desc' => esc_html__('Set Maximum quantity required', 'woo-tour'), 'repeatable' => false, 'multiple' => false ),

			array( 'id' => 'wt_infant', 'name' => esc_html__('Infant price', 'woo-tour'), 'cols' => 3, 'type' => 'text' ,'desc' => esc_html__('Enter price or OFF to hide this field', 'woo-tour'), 'repeatable' => false, 'multiple' => false ),
			array( 'id' => 'wt_infant_sale', 'name' => esc_html__('Sale price', 'woo-tour'), 'cols' => 3, 'type' => 'text' ,'desc' => esc_html__('Enter Sale price for Infant', 'woo-tour'), 'repeatable' => false, 'multiple' => false ),
			array( 'id' => 'wt_infant_min', 'name' => esc_html__('Min quantity', 'woo-tour'), 'cols' => 3, 'type' => 'text' ,'desc' => esc_html__('Set Minimum quantity required', 'woo-tour'), 'repeatable' => false, 'multiple' => false ),
			array( 'id' => 'wt_infant_max', 'name' => esc_html__('Max quantity', 'woo-tour'), 'cols' => 3, 'type' => 'text' ,'desc' => esc_html__('Set Maximum quantity required', 'woo-tour'), 'repeatable' => false, 'multiple' => false ),
		);
		$label1 = explode("|",get_option('wt_ctfield1_info'));
		$label2 = explode("|",get_option('wt_ctfield2_info'));
		$addition_csf = array(
			array( 'id' => 'wt_ctps1', 'name' => $label1[0].esc_html__(' price', 'woo-tour'), 'cols' => 3, 'type' => 'text' ,'desc' => esc_html__('Enter price or OFF to hide this field', 'woo-tour'), 'repeatable' => false, 'multiple' => false ),
			array( 'id' => 'wt_ctps1_sale', 'name' => esc_html__('Sale price', 'woo-tour'), 'cols' => 3, 'type' => 'text' ,'desc' => esc_html__('Enter Sale price', 'woo-tour'), 'repeatable' => false, 'multiple' => false ),
			array( 'id' => 'wt_ctps1_min', 'name' => esc_html__('Min quantity', 'woo-tour'), 'cols' => 3, 'type' => 'text' ,'desc' => esc_html__('Set Minimum quantity required', 'woo-tour'), 'repeatable' => false, 'multiple' => false ),
			array( 'id' => 'wt_ctps1_max', 'name' => esc_html__('Max quantity', 'woo-tour'), 'cols' => 3, 'type' => 'text' ,'desc' => esc_html__('Set Maximum quantity required', 'woo-tour'), 'repeatable' => false, 'multiple' => false ),
			
			array( 'id' => 'wt_ctps2', 'name' => $label2[0].esc_html__(' price', 'woo-tour'), 'cols' => 3, 'type' => 'text' ,'desc' => esc_html__('Enter price or OFF to hide this field', 'woo-tour'), 'repeatable' => false, 'multiple' => false ),
			array( 'id' => 'wt_ctps2_sale', 'name' => esc_html__('Sale price', 'woo-tour'), 'cols' => 3, 'type' => 'text' ,'desc' => esc_html__('Enter Sale price', 'woo-tour'), 'repeatable' => false, 'multiple' => false ),
			array( 'id' => 'wt_ctps2_min', 'name' => esc_html__('Min quantity', 'woo-tour'), 'cols' => 3, 'type' => 'text' ,'desc' => esc_html__('Set Minimum quantity required', 'woo-tour'), 'repeatable' => false, 'multiple' => false ),
			array( 'id' => 'wt_ctps2_max', 'name' => esc_html__('Max quantity', 'woo-tour'), 'cols' => 3, 'type' => 'text' ,'desc' => esc_html__('Set Maximum quantity required', 'woo-tour'), 'repeatable' => false, 'multiple' => false ),
		);
		if(get_option('wt_ctfieldprice') == 1){
			$addition_settings = array_merge($addition_settings, $addition_csf);
		}
		$addition_settings = apply_filters( 'wt_addition_settings_meta', $addition_settings );
		$event_layout = array(	
			array( 'id' => 'wt_layout', 'name' => esc_html__('Layout', 'woo-tour'), 'cols' => 6, 'type' => 'select', 'options' => array( '' => esc_html__('Default', 'woo-tour'), 'layout-2' => esc_html__('Full Width', 'woo-tour'),'layout-3' => esc_html__('Full Width Flat', 'woo-tour')),'desc' => esc_html__('Select "Default" to use settings in Options page', 'woo-tour') , 'repeatable' => false, 'multiple' => false),
			array( 'id' => 'wt_sidebar', 'name' => esc_html__('Sidebar', 'woo-tour'), 'cols' => 6, 'type' => 'select', 'options' => array( '' => esc_html__('Default', 'woo-tour'), 'right' => esc_html__('Right', 'woo-tour'), 'left' => esc_html__('Left', 'woo-tour'),'hide' => esc_html__('Hidden', 'woo-tour')),'desc' => esc_html__('Select "Default" to use settings in Options page', 'woo-tour') , 'repeatable' => false, 'multiple' => false),
		);
		
		$wt_main_purpose = get_option('wt_main_purpose');
		$meta_boxes[] = array(
			'title' => __('Time Settings','woo-tour'),
			'pages' => 'product',
			'fields' => $time_settings,
			'priority' => 'high'
		);
		$meta_boxes[] = array(
			'title' => __('Tour Info','woo-tour'),
			'pages' => 'product',
			'fields' => $info_settings,
			'priority' => 'high'
		);
		$meta_boxes[] = array(
			'title' => __('Additional Information','woo-tour'),
			'pages' => 'product',
			'fields' => $addition_settings,
			'priority' => 'high'
		);
		
		$event_purpose = array(	
			array( 
				'id' => 'wt_layout_purpose', 
				'name' => '', 
				'type' => 'select', 
				'options' => array( 
					'def' => esc_html__('Default', 'woo-tour'),
					'tour' => esc_html__('Tour', 'woo-tour'), 
					'woo' => esc_html__('WooCommere', 'woo-tour')
				), 'desc' => esc_html__('Select "Default" to use the setting from plugin settings page, if you want to sell normal product, please choose "WooCommerce" option', 'woo-tour') , 'repeatable' => false, 'multiple' => false
			)
		);
		$meta_boxes[] = array(
			'title' => __('Layout Purpose','woo-tour'),
			'context' => 'side',
			'pages' => 'product',
			'fields' => $event_purpose,
			'priority' => 'high'
		);
		$group_fields = array(
			array( 'id' => 'wt_custom_title',  'name' => esc_html__('Title', 'woo-tour'), 'type' => 'text', 'cols' => 6 ),
			array( 'id' => 'wt_custom_content', 'name' => esc_html__('Content', 'woo-tour'), 'type' => 'text', 'desc' => '', 'repeatable' => false, 'cols' => 6),
		);
		foreach ( $group_fields as &$field ) {
			$field['id'] = str_replace( 'field', 'gfield', $field['id'] );
		}
	
		$meta_boxes[] = array(
			'title' => esc_html__('Custom Field', 'woo-tour'),
			'pages' => 'product',
			'fields' => array(
				array(
					'id' => 'wt_custom_metadata',
					'name' => esc_html__('Custom Metadata', 'woo-tour'),
					'type' => 'group',
					'repeatable' => true,
					'sortable' => true,
					'fields' => $group_fields,
					'desc' => esc_html__('Custom metadata for this post', 'woo-tour')
				)
			),
			'priority' => 'high'
		);
		
		if($wt_main_purpose!='meta'){
			$meta_boxes[] = array(
				'title' => __('Layout Settings','woo-tour'),
				'pages' => 'product',
				'fields' => $event_layout,
				'priority' => 'high'
			);
		}
		$fixedprice_fields = array(
			array( 
				'id' => 'wt_fixed_price', 
				'name' => '', 
				'cols' => 12, 
				'type' => 'select', 
				'options' => array( 
					'' => esc_html__('No', 'woo-tour'), 
					'yes' => esc_html__('Yes', 'woo-tour')
				),
				'desc' => esc_html__('Set fixed price for this tour, select yes to disable increase price for each person', 'exthemes')
			),
		);
		$meta_boxes[] = array(
			'title' => __('Fixed Price','woo-tour'),
			'pages' => 'product',
			'fields' => $fixedprice_fields,
			'context' => 'side',
			'priority' => ''
		);
		$discount_fields = array(
			array( 'id' => 'wt_disc_start', 'name' => esc_html__('Start', 'exthemes'), 'cols' => 6, 'type' => 'date_unix','desc' => ''),
			array( 'id' => 'wt_disc_end', 'name' => esc_html__('End', 'exthemes'), 'cols' => 6, 'type' => 'date_unix' ,'desc' => ''),
			array( 'id' => 'wt_disc_type', 'name' => esc_html__('Type', 'woo-tour'), 'type' => 'select', 'options' => array( 'price' => esc_html__('Fixed price', 'woo-tour'), 'percent' => esc_html__('Percentage', 'woo-tour')),'desc' => esc_html__('', 'woo-tour'),'cols' => 12),
			array( 'id' => 'wt_disc_number',  'name' => esc_html__('Number adult', 'woo-tour'), 'type' => 'text', 'cols' => 6 ),
			array( 'id' => 'wt_disc_am',  'name' => esc_html__('Amount', 'woo-tour'), 'type' => 'number', 'cols' => 6 ),
			array( 'id' => 'wt_disc_note',  'name' => esc_html__('Description', 'woo-tour'), 'type' => 'textarea', 'cols' => 12 ),
		);	
		$meta_boxes[] = array(
			'title' => esc_html__('Discount', 'woo-tour'),
			'pages' => 'product',
			'context' => 'side',
			'fields' => array(
				array( 'id' => 'wt_disc_bo', 'name' => esc_html__('Discount based on', 'woo-tour'), 'type' => 'select', 'options' => array( '' => esc_html__('Number adult', 'woo-tour'), 'season' => esc_html__('Season', 'woo-tour')),'desc' => esc_html__('', 'woo-tour'),'cols' => 12),
				array(
					'id' => 'wt_discount',
					'name' => esc_html__('Discount Rule', 'woo-tour'),
					'type' => 'group',
					'repeatable' => true,
					'sortable' => true,
					'fields' => $discount_fields,
					'desc' => esc_html__('', 'woo-tour')
				),
			),
			'priority' => ''
		);
		$bookbf_fields = array(
			array( 'id' => 'wt_disable_book', 'name' => esc_html__('User need book before', 'exthemes'), 'cols' => 12, 'type' => 'text','desc' => esc_html__('This feature allow user only can booking tour before X day. Enter number', 'exthemes')),
		);
		$meta_boxes[] = array(
			'title' => __('Date able to book','woo-tour'),
			'pages' => 'product',
			'fields' => $bookbf_fields,
			'context' => 'side',
			'priority' => ''
		);
		$label_fields = array(
			array( 'id' => 'wt_date_label', 'name' => esc_html__('Label name for Departure', 'woo-tour'), 'cols' => 12, 'type' => 'text' ,'desc' => esc_html__('Default is Departure:', 'woo-tour'), 'repeatable' => false, 'multiple' => false ),
			array( 'id' => 'wt_adult_label', 'name' => esc_html__('Label name for Adult', 'woo-tour'), 'cols' => 12, 'type' => 'text' ,'desc' => esc_html__('Default is Adult:', 'woo-tour'), 'repeatable' => false, 'multiple' => false ),
			array( 'id' => 'wt_child_label', 'name' => esc_html__('Label name for children', 'woo-tour'), 'cols' => 12, 'type' => 'text' ,'desc' => esc_html__('Default is Children:', 'woo-tour'), 'repeatable' => false, 'multiple' => false ),
			array( 'id' => 'wt_infant_label', 'name' => esc_html__('Label name for Infant', 'woo-tour'), 'cols' => 12, 'type' => 'text' ,'desc' => esc_html__('Default is Infant:', 'woo-tour'), 'repeatable' => false, 'multiple' => false ),
		);
		if(get_option('wt_ctfieldprice') == 1){
			$label_fields[] = array( 'id' => 'wt_ctps1_label', 'name' => esc_html__('Label name for '.$label1[0], 'woo-tour'), 'cols' => 12, 'type' => 'text' ,'desc' => esc_html__('Default in settings page', 'woo-tour'), 'repeatable' => false, 'multiple' => false );
			$label_fields[] = array( 'id' => 'wt_ctps2_label', 'name' => esc_html__('Label name for '.$label2[0], 'woo-tour'), 'cols' => 12, 'type' => 'text' ,'desc' => esc_html__('Default in settings page', 'woo-tour'), 'repeatable' => false, 'multiple' => false );
		}
		$meta_boxes[] = array(
			'title' => __('Label name','woo-tour'),
			'pages' => 'product',
			'fields' => $label_fields,
			'context' => 'side',
			'priority' => ''
		);
		// price by seasion
		$ar_variations = array();
		if(isset($_GET['post']) && $_GET['post'] > 0){
			$product = wc_get_product($_GET['post']);
			if( is_object($product) &&  method_exists($product, 'is_type') && $product->is_type( 'variable' ) ) {
				$variations = $product->get_children();
				if(is_array($variations)){
					foreach ($variations as $variation) {
						if(count($variations) > 1){
							$ar_variations[$variation] = $variation.' - '.get_the_title($variation);
						}
					}
				}
			}
		}
		$pr_ss_fields [] = array( 'id' => 'wt_p_start', 'name' => esc_html__('Start', 'exthemes'), 'cols' => 6, 'type' => 'date_unix','desc' => '');
		$pr_ss_fields [] = array( 'id' => 'wt_p_end', 'name' => esc_html__('End', 'exthemes'), 'cols' => 6, 'type' => 'date_unix' ,'desc' => '');
		$pr_ss_fields [] = array( 'id' => 'wt_p_variation', 'name' => esc_html__('Variations', 'woo-tour'), 'type' => 'select', 'options' => $ar_variations,'desc' => esc_html__('', 'woo-tour'),'cols' => 12);
		$pr_ss_fields [] = array( 'id' => 'wt_p_adult',  'name' => esc_html__('Adult', 'woo-tour'), 'type' => 'number', 'cols' => 4 );
		$pr_ss_fields [] = array( 'id' => 'wt_p_child',  'name' => esc_html__('Children', 'woo-tour'), 'type' => 'number', 'cols' => 4 );
		$pr_ss_fields [] = array( 'id' => 'wt_p_infant',  'name' => esc_html__('Infant', 'woo-tour'), 'type' => 'number', 'cols' => 4 );
		if(get_option('wt_ctfieldprice') == 1){
			$pr_ss_fields[] = array( 'id' => 'wt_p_ctps1',  'name' => $label1[0], 'type' => 'number', 'cols' => 6 );
			$pr_ss_fields[] = array( 'id' => 'wt_p_ctps2',  'name' => $label2[0], 'type' => 'number', 'cols' => 6 );
		}
		$meta_boxes[] = array(
			'title' => esc_html__('Price by Season', 'woo-tour'),
			'pages' => 'product',
			'context' => 'side',
			'fields' => array(
				array(
					'id' => 'wt_p_season',
					'name' => esc_html__('Price Rule', 'woo-tour'),
					'type' => 'group',
					'repeatable' => true,
					'sortable' => true,
					'fields' => $pr_ss_fields,
					'desc' => esc_html__('', 'woo-tour')
				),
			),
			'priority' => ''
		);

		return $meta_boxes;
	}
	function register_category_taxonomies(){
		$labels = array(
			'name'              => esc_html__( 'Location', 'woo-tour' ),
			'singular_name'     => esc_html__( 'Location', 'woo-tour' ),
			'search_items'      => esc_html__( 'Search','woo-tour' ),
			'all_items'         => esc_html__( 'All Locations','woo-tour' ),
			'parent_item'       => esc_html__( 'Parent Location' ,'woo-tour'),
			'parent_item_colon' => esc_html__( 'Parent Location:','woo-tour' ),
			'edit_item'         => esc_html__( 'Edit Location' ,'woo-tour'),
			'update_item'       => esc_html__( 'Update Location','woo-tour' ),
			'add_new_item'      => esc_html__( 'Add New Location' ,'woo-tour'),
			'menu_name'         => esc_html__( 'Locations','woo-tour' ),
		);
		$wt_loc_slug = get_option('wt_loc_slug');
		if($wt_loc_slug==''){
			$wt_loc_slug = 'location';
		}
		$rewrite =  array( 'slug' => untrailingslashit( $wt_loc_slug ), 'with_front' => false, 'feeds' => true );
				
		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => $rewrite,
		);
		
		register_taxonomy('wt_location', 'product', $args);
	}
}
$wootour_Meta = new wootour_Meta();
include_once(ABSPATH.'wp-admin/includes/plugin.php');
/* location feature image */
add_action( 'wt_location_add_form_fields', 'wt_image_fields', 10 );
add_action ( 'wt_location_edit_form_fields', 'wt_image_fields');

function wt_image_fields( $tag ) {    //check for existing featured ID
	$t_id 					= isset($tag->term_id) ? $tag->term_id : '';
	if(!is_plugin_active('categories-images/categories-images.php')){
		$id_image 			= get_option( "id_image_$t_id")?get_option( "id_image_$t_id"):'';
		?>
			<tr class="form-field" style="">
				<th scope="row" valign="top">
					<label for="id-image"><?php esc_html_e('Image Attachment ID','woo-tour'); ?></label>
	            </th>
				<td>
					<input type="text" name="id-image" id="id-image" value="<?php echo esc_attr($id_image) ?>" />
					<p style="margin-bottom:15px;"><?php esc_html_e( 'Set featured image for this location', 'woo-tour' ) ?></p>
	            </td>
			</tr>
		<?php 
	}
	/*$id_tourist 			= get_option( "id_tourist_$t_id")?get_option( "id_tourist_$t_id"):'';
	?>
	<tr class="form-field" style="">
		<th scope="row" valign="top">
			<label for="id-tourist"><?php esc_html_e('Tourists','woo-tour'); ?></label>
        </th>
		<td>
			<input type="text" name="id-tourist" id="id-tourist" value="<?php echo esc_attr($id_tourist) ?>" />
			<p style="margin-bottom:15px;"><?php esc_html_e( 'Number of tourists', 'woo-tour' ) ?></p>
        </td>
	</tr>
	<?php*/
}
//save image fields
add_action ( 'edited_wt_location', 'wt_save_extra_image_fileds', 10, 2);
add_action( 'created_wt_location', 'wt_save_extra_image_fileds', 10, 2 );
function wt_save_extra_image_fileds( $term_id ) {
	if ( isset( $_POST[sanitize_key('id-image')] ) ) {
		$id_image = $_POST['id-image'];
		update_option( "id_image_$term_id", $id_image );
	}
	/*if ( isset( $_POST[sanitize_key('id-tourist')] ) ) {
		$id_tourist = $_POST['id-tourist'];
		update_option( "id_tourist_$term_id", $id_tourist );
	}*/
}