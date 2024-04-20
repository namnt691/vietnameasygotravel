<?php
if(is_admin()){return;}
global $woocommerce, $post,$wt_main_purpose;
$wt_enddate = wt_global_expireddate();
$wt_accom_service = get_post_meta( $post->ID, 'wt_accom_service', false );
if($wt_main_purpose!='woo' && !empty($wt_accom_service)){?>
    <div class="clear"></div>
    <div class="woo-tour-accompanied exwt-col12">
        <h3><?php echo esc_html__('Accompanied service','woo-tour')?></h3>
        <div class="wt-sche-detail tour-service">
            <?php 
            $i = 0;
            foreach($wt_accom_service as $item){
                $i++ ?>
                    <span><?php echo $item; ?></span>
                    <?php 
                if($i%5==0 && count($wt_accom_service)!=$i){
                    ?>
                    </div>
                    <div class="wt-sche-detail tour-service">
                    <?php
                }
            }?>
        </div>
    </div>
<?php }
$off_ssocial = get_option('wt_ssocial');
if($off_ssocial!='off'){
	?>
	<div class="wt-social-share exwt-col12" id="wt-sc-s">
		<div class="exwt-row">
			<?php echo  wt_social_share();?>
		</div>
	</div>
<?php }?>
<div class="clear"></div>
<?php 
if(get_option('wt_schedu_map') == 1){
	$wt_schedu= get_post_meta($post->ID,'wt_schedu', false );
	$adress= get_post_meta($post->ID,'wt_adress', true );
	$wt_latitude_longitude = get_post_meta($post->ID,'wt_latitude_longitude', true );
	if(!empty($wt_schedu) || $wt_latitude_longitude !='' || $adress!=''){ ?>
        <div class="woo-tour-accompanied woo-tour-schedu exwt-col12">
            <div class="exwt-row">
                <?php if(!empty($wt_schedu)){ ?>
                <div class="<?php if($wt_latitude_longitude =='' && $adress=='' ){?> exwt-col12 <?php }else {?> exwt-col6 <?php }?>exwt-col-sm6">
                    <h3 class="h3-ev-schedu"><?php echo esc_html__('Schedule','woo-tour')?></h3>
                    <div class="we-sche-detail ev-schedu">
                        <?php foreach($wt_schedu as $item){ ?>
                                <p><?php echo $item; ?></p>
                                <?php 
                        }?>
                    </div>
                </div>
                <?php }?>
                
                <?php if($wt_latitude_longitude !='' || $adress!='' ){?>
                    <div class="<?php if(empty($wt_schedu)){?> exwt-col12 <?php }else {?> exwt-col6 <?php }?> exwt-col-sm6">
                        <?php
                            if($wt_latitude_longitude !=''){
                                $adress = $wt_latitude_longitude;
                            }
                            if($adress!=''){
                                $langm = apply_filters('exwt_embeb_map_lang','en');
                                ?>
                                <iframe frameborder="0" scrolling="no" marginheight="0" marginwidth="0"width="100%" height="100%" src="https://maps.google.com/maps?hl=<?php esc_attr_e($langm);?>&q=<?php echo ($adress);?>&ie=UTF8&t=roadmap&z=10&iwloc=B&output=embed"></iframe>
                                <?php
                            }
                        ?>
                    </div>
                <?php }?>
            </div>
        </div>
<?php }
}