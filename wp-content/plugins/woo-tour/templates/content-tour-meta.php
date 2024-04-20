<?php
if(is_admin()){return;}
global $woocommerce, $post,$wt_main_purpose,$product;
if($wt_main_purpose=='woo'){
	return;
}
	
$expireddate = wt_global_expireddate();
$wt_duration = get_post_meta( $post->ID, 'wt_duration', true ) ;
$wt_type = get_post_meta( $post->ID, 'wt_type', true ) ;
$wt_transport = get_post_meta( $post->ID, 'wt_transport', true ) ;
$wt_group_size = get_post_meta( $post->ID, 'wt_group_size', true ) ;
$wt_custom_info = get_post_meta( $post->ID, 'wt_custom_info', false );
$wt_sku = $product->get_sku();
$location = wt_taxonomy_info('wt_location');
$class = 'exwt-col4';
$wt_layout = wootour_global_layout();
if($wt_layout=='layout-3' || $wt_layout=='layout-2'){
	$class = 'exwt-col6';
}
?>
<div class="woo-tour-info exwt-col12 <?php if(get_option('wt_old_layout')!='yes' || get_option('wt_metaposition') == 'above'){ echo 'meta-full-style';}?>">
	<?php 
	if(get_option('wt_metaposition') == 'above'){?>
    <h3><?php echo esc_html__('Tour info','woo-tour')?></h3>
    <?php
	}
	if($wt_duration!='' || $wt_transport!='' || $wt_sku!='' || $wt_group_size!='' || $wt_sku!='' || $location!=''){?>
        <div class="exwt-row location-info">
			<?php 
            if($wt_duration!=''){?>
            <div class="<?php echo $class;?>">
                <div class="exwt-meta">
                    <div class="exwt-meta-body">
                        <span class="sub-lb">
                        <i class="fa fa-clock-o" aria-hidden="true"></i>
                        <?php echo  esc_html__('Duration:','woo-tour');?> </span>          	
                        <div class="exwt-meta-heading">
                            <?php echo $wt_duration;?>
                        </div>
                    </div>
                </div>
            </div>
            <?php }?>
            <?php 
            if($wt_transport!=''){?>
            <div class="<?php echo $class;?>">
                <div class="exwt-meta">
                    <div class="exwt-meta-body">
                        <span class="sub-lb"><i class="fa fa-paper-plane" aria-hidden="true"></i>
                        <?php echo esc_html__('Transport:','woo-tour');?></span>   	
                        <div class="exwt-meta-heading">
                            <?php echo $wt_transport;?>
                        </div>
                    </div>
                </div>
            </div>
            <?php }?>
            <?php 
            if($wt_type!=''){?>
            <div class="<?php echo $class;?>">
                <div class="exwt-meta">
                    <div class="exwt-meta-body">
                        <span class="sub-lb">
                        <i class="fa fa-info" aria-hidden="true"></i>
						<?php echo esc_html__('Tour Type:','woo-tour');?></span>      	
                        <div class="exwt-meta-heading">
                            <?php echo $wt_type;?>
                        </div>
                    </div>
                </div>
            </div>
            <?php }?>
            
            <?php 
            if($wt_sku!=''){?>
            <div class="<?php echo $class;?>">
                <div class="exwt-meta">
                    <div class="exwt-meta-body">
                        <span class="sub-lb">
                        <i class="fa fa-info" aria-hidden="true"></i>
                        <?php echo esc_html__('SKU:','woo-tour');?></span>      	
                        <div class="exwt-meta-heading">
                            <?php echo $wt_sku;?>
                        </div>
                    </div>
                </div>
            </div>
            <?php }?>
            <?php 
            if($wt_group_size!=''){?>
            <div class="<?php echo $class;?>">
                <div class="exwt-meta">
                    <div class="exwt-meta-body">
                        <span class="sub-lb"><i class="fa fa-users" aria-hidden="true"></i>
						<?php echo esc_html__('Group size:','woo-tour');?></span>   	
                        <div class="exwt-meta-heading">
                            <?php echo $wt_group_size;?>
                        </div>
                    </div>
                </div>
            </div>
            <?php }?>
            <?php 
            if($location!=''){?>
            <div class="<?php echo $class;?>">
                <div class="exwt-meta">
                    <div class="exwt-meta-body">
                        <span class="sub-lb">
                        <i class="fa fa-map-marker"></i>
						<?php echo esc_html__('Location:','woo-tour');?></span>   	
                        <div class="exwt-meta-heading">
                            <?php echo $location;?>
                        </div>
                    </div>
                </div>
            </div>
            <?php }?>
            
        </div>
        <?php 
	}?>
    <?php 
	$wt_custom_metadata = get_post_meta( $post->ID, 'wt_custom_metadata', false );
	if(is_array($wt_custom_metadata) && !empty($wt_custom_metadata)){
		$number = count($wt_custom_metadata);?>
		<div class="exwt-row wt-custom-info">
			<?php 
			$i = 0;
			foreach($wt_custom_metadata as $item){
				$i++;?>
				<div class="exwt-col6 exwt-col-sm6">
					<div class="exwt-meta">
						<div class="exwt-meta-body">
							<span class="sub-lb"><?php echo $item['wt_custom_title'];?></span>
							<div class="exwt-meta-heading">
                                <span class="wt-sub-ct exwt-meta-heading"><?php echo $item['wt_custom_content'];?></span>
							</div>
						</div>
					</div>
				</div>
				<?php 
				if($i < $number && $i % 2==0){?>
				</div>
				<div class="exwt-row">	
				<?php }
			}?>
		</div>
	<?php }?>
</div>