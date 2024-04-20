<?php
global $wt_disc_bo, $wt_discount,$dtp;
$cure_time =  strtotime("now");
$gmt_offset = get_option('gmt_offset');
if($gmt_offset!=''){
	$cure_time = $cure_time + ($gmt_offset*3600);
}
if($dtp==''){
	$hide_empty = 0;
	echo '<div class="wt-discount-sif">
		<div class="dc-title"><span>'.esc_html__('Discount:','woo-tour').'</span></div>';
		if(!empty($wt_discount)){
			usort($wt_discount, function($a, $b) { // anonymous function
				return $b['wt_disc_am'] - $a['wt_disc_am'];
			});
			$wt_discount = array_reverse($wt_discount);
			$i = 0;
			foreach ($wt_discount as $item){
				$i ++;
				$enddc = $item['wt_disc_end']!='' ? $item['wt_disc_end'] + 86399 : '';
				if($item['wt_disc_type']=='percent'){
					$dc_pr = '- '.$item['wt_disc_am'].'%'; 
				}else{
					$dc_pr = '- '.wc_price($item['wt_disc_am']); 
				}
				if($wt_disc_bo == 'season'){
					if(($enddc=='') || ( $enddc!='' && $cure_time < $enddc) ){
						$hide_empty = 1;
						echo '<span class="dc-it" data-scroll="wt-dc'.$i.'">'.$dc_pr.'</span>';
					}
				}else{
					if(($item['wt_disc_start']=='' && $enddc=='') || 
					($item['wt_disc_start']!='' && $enddc=='' && $cure_time > $item['wt_disc_start']) || 
					($item['wt_disc_start']=='' && $enddc!='' && $cure_time < $enddc) || 
					($item['wt_disc_start']!='' && $enddc!='' && $cure_time < $enddc)){
						$hide_empty = 1;
						echo '<span class="dc-it" data-scroll="wt-dc'.$i.'">'.$dc_pr.'</span>';
					}
				}
				
			}
		}
	echo '</div>';
	if($hide_empty != 1){
		echo '<style type="text/css">.wt-discount-sif, .wt-disc-info{ display:none !important}</style>';
	}
}else{
	$class = get_option('wt_old_layout')=='yes' ? 'wt-disc-info woo-tour-info meta-full-style' : 'wt-disc-info woo-tour-accompanied';
	echo '
	<div class="'.$class.'">
		<h3>'.esc_html__('Special offer','woo-tour').'</h3>';
		
		if(!empty($wt_discount)){
			usort($wt_discount, function($a, $b) { // anonymous function
				return $b['wt_disc_am'] - $a['wt_disc_am'];
			});
			$wt_discount = array_reverse($wt_discount);
			$html_it ='';
			if($wt_disc_bo == 'season'){
				$dtex = esc_html__('Departure from','woo-tour');
				$ctex = esc_html__(' per person','woo-tour');
			}else{
				$dtex = esc_html__('From','woo-tour');
				$ctex = esc_html__(' per adult','woo-tour');
			}
			$j = 0;
			foreach ($wt_discount as $item){
				$j++;
				if($item['wt_disc_type']=='percent'){
					$dc_pr = '- '.$item['wt_disc_am'].'%'.$ctex; 
				}else{
					$dc_pr = wc_price($item['wt_disc_am']).$ctex; 
				}
				$note = '';
				if($item['wt_disc_note']!=''){
					$note = '
					<div class="dc-note">
						<span>'.esc_html__('Details: ','woo-tour').'</span>
						<div>
							<span>'.$item['wt_disc_note'].'</span>
						</div>
					</div>';
				}
				$title_html ='<div class="dc-title" id="wt-dc'.$j.'"><span>'.$dc_pr.'</span><span></span></div><div class="clearfix"></div>';
				$enddc = $item['wt_disc_end']!='' ? $item['wt_disc_end'] + 86399 : '';
				
				$st = $item['wt_disc_start']!='' ? date_i18n( get_option('date_format'), $item['wt_disc_start']) : esc_html__('No limit ','woo-tour');
				$end = $item['wt_disc_end']!='' ? date_i18n( get_option('date_format'), $item['wt_disc_end']) : esc_html__('No limit ','woo-tour');
				if($wt_disc_bo == 'season'){
					if(($enddc=='') || ( $enddc!='' && $cure_time < $enddc) ){
						$html_it .= $title_html.'
						<div class="dc-content">
							<div class="dc-date">
								<span>'.$dtex.'</span>
								<div>
									<span>'.$st.'</span>
									<span class="dclb-to">'.esc_html__('To','woo-tour').'</span>
									<span>'.$end.'</span>
								</div>
							</div>
							'.$note.'
						 </div>';
					}
				}else{
					if(($item['wt_disc_start']=='' && $enddc=='') || 
					($item['wt_disc_start']!='' && $enddc=='' && $cure_time > $item['wt_disc_start']) || 
					($item['wt_disc_start']=='' && $enddc!='' && $cure_time < $enddc) || 
					($item['wt_disc_start']!='' && $enddc!='' && $cure_time < $enddc)){
						$html_it .= $title_html.'
						<div class="dc-content">
							<div class="dc-date">
								<span>'.$dtex.'</span>
								<div>
									<span>'.$st.'</span>
									<span class="dclb-to">'.esc_html__('To','woo-tour').'</span>
									<span>'.$end.'</span>
								</div>
							</div>
							<div class="dc-cond">
								<span>'.esc_html__('Condition','woo-tour').'</span>
								<div>
									<span>'.esc_html__('Number adult >=','woo-tour').$item['wt_disc_number'].'</span>
								</div>
							</div>
							'.$note.'
						 </div>
						 ';
					}
				}
			}
			echo '
			<div class="dc-details">
				'.$html_it.'
			</div>';
		}
		echo'
	</div>';
}
