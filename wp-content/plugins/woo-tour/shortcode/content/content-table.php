<?php
global $style;
global $ajax_load;
$wt_enddate = get_post_meta( get_the_ID(), 'wt_enddate', true )  ;
global $product;	
$wt_sku = $product->get_sku();
$type = $product->get_type();
$price ='';
if($type=='variable'){
	$price = wt_variable_price_html();
}else{
	if ( $price_html = $product->get_price_html() ) :
		$price = $price_html; 
	endif; 	
}

/*
$wt_discount = get_post_meta(get_the_ID(),'wt_discount',false);
$wt_disc_bo = get_post_meta(get_the_ID(),'wt_disc_bo',true);
if(!empty($wt_discount) && ( !isset($value['deposit_value']) || $value['deposit_value']=='' )){
	if($type=='variable'){
		$price = $product->get_variation_price('min');
	}else{
		$price = $product->get_price();
	}

	$cure_time =  strtotime("now");
	$gmt_offset = get_option('gmt_offset');
	if($gmt_offset!=''){
		$cure_time = $cure_time + ($gmt_offset*3600);
	}
	usort($wt_discount, function($a, $b) { // anonymous function
		return $a['wt_disc_number'] - $b['wt_disc_number'];
	});
	$wt_discount = array_reverse($wt_discount);
	if(!empty($wt_discount)){
		usort($wt_discount, function($a, $b) { // anonymous function
			return $b['wt_disc_number'] - $a['wt_disc_number'];
		});
		$wt_discount = array_reverse($wt_discount);
		$i = 0;
		foreach ($wt_discount as $item){
			$i ++;
			$enddc = $item['wt_disc_end']!='' ? $item['wt_disc_end'] + 86399 : '';
			if($item['wt_disc_type']=='percent'){
				$price_dc  = $price - ($price*$item['wt_disc_am']/100); 
			}else{
				$price_dc = $price -  $item['wt_disc_am']*1; 
			}
			if($wt_disc_bo == 'season'){
				if(($enddc=='') || ( $enddc!='' && $cure_time < $enddc) ){
					$price = wc_price($price_dc);
				}
			}else{
				if(($item['wt_disc_start']=='' && $enddc=='') || 
				($item['wt_disc_start']!='' && $enddc=='' && $cure_time > $item['wt_disc_start']) || 
				($item['wt_disc_start']=='' && $enddc!='' && $cure_time < $enddc) || 
				($item['wt_disc_start']!='' && $enddc!='' && $cure_time < $enddc)){
					$price = wc_price($price_dc);
				}
			}
			
		}
	}
}*/


$wt_adress = wt_taxonomy_info('wt_location','off');
$wt_status = get_post_meta( get_the_ID(), 'wt_duration', true );

$wt_eventcolor = get_post_meta( get_the_ID(), 'wt_eventcolor', true );
$bgev_color = '';
if($wt_eventcolor!=""){
	$bgev_color = 'style="background-color:'.$wt_eventcolor.'"';
}
$wt_transport = get_post_meta( get_the_ID(), 'wt_transport', true ) ;

if($style!='2'){ ?>
	<tr <?php if(isset($ajax_load) && $ajax_load ==1){?>class="tb-load-item de-active" <?php }?>>
		<td class="wt-first-row"><?php the_post_thumbnail($img_size='wethumb_204x153');?></td>
		<td><h3><a href="<?php the_permalink();?>"><?php the_title();?></a></h3>
			<?php wt_custom_date_html();?>
            <span class="event-meta wt-hidden-screen">
			  <?php 
			  if($wt_adress!=''){?>
				  <span class="tb-meta"><i class="fa fa-map-marker"></i> <?php echo $wt_adress;?></span>
			  <?php }if($price!=''){?>
				  <span class="tb-meta"><i class="fa fa-shopping-basket"></i><?php echo $price;?></span>
			  <?php }if($wt_status!=''){?>
				  <span class="tb-meta"><i class="fa fa-ticket"></i> <?php echo $wt_status;?></span>
			  <?php }?>
			</span>
		</td>
		<td class="wt-mb-hide"><?php echo $wt_adress;?></td>
		<td class="tb-price wt-mb-hide"><span><?php echo $price;?></span></td>
		<td class="wt-mb-hide"><?php echo $wt_status;?></td>
	</tr>
<?php }else{?>
	<tr <?php if(isset($ajax_load) && $ajax_load ==1){?>class="tb-load-item de-active" <?php }?>>
		<td class="wt-first-row">
			<?php the_post_thumbnail($img_size='wethumb_204x153');?>
		</td>
		<td>
			<h3><a href="<?php the_permalink();?>"><?php the_title();?></a></h3>
			<span class="event-meta">
			  <?php wt_custom_date_html();?>
			  <?php if($wt_sku!=''){?>
				  <span class="tb-meta"><i class="fa fa-info" aria-hidden="true"></i><?php echo esc_html__("Sku", "woo-tour").': '.$wt_sku;?></span>
			  <?php }if($wt_adress!=''){?>
				  <span class="tb-meta"><i class="fa fa-map-marker"></i> <?php echo $wt_adress;?></span>
			  <?php }if($wt_status!=''){?>
				  <span class="tb-meta"><i class="fa fa-clock-o"></i> <?php echo $wt_status;?></span>
			  <?php }if($wt_transport!=''){?>
				  <span class="tb-meta"><i class="fa fa-paper-plane"></i> <?php echo $wt_transport;?></span>
			  <?php }?>
			</span>
		</td>
		<td class="tb-viewdetails">
        	<span class="tb-price"><i class="fa fa-shopping-basket"></i><?php echo $price;?></span>
            <span>
            	<a class="exwt-btn exwt-btn-default wt-button" <?php echo $bgev_color;?> href="<?php the_permalink();?>"><?php echo esc_html__('View Details','woo-tour');?> <i class="fa fa-angle-right" aria-hidden="true"></i></a>
            </span>
		</td>
	</tr>
<?php }
