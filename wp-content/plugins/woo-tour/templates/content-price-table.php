<?php
$p_season = exwt_get_price_season(get_the_ID(),false);
global $product;
$ck_vari = false;
if( $product->is_type( 'variable' ) ) {
	$variations = $product->get_children();
	if(is_array($variations) && count($variations) > 1){
		$ck_vari = true;
	}
}
if(is_array($p_season) && !empty($p_season)){
	$adult_label = get_post_meta( get_the_ID(), 'wt_adult_label', true ) ;
	$adult_label = $adult_label!='' ? $adult_label.': ' : esc_html__('Adult: ','woo-tour');
	$child_label = get_post_meta( get_the_ID(), 'wt_child_label', true ) ;
	$child_label = $child_label!='' ? $child_label.': ' : esc_html__('Children: ','woo-tour');
	$infant_label = get_post_meta( get_the_ID(), 'wt_infant_label', true ) ;
	$infant_label = $infant_label!='' ? $infant_label.': ' : esc_html__('Infant: ','woo-tour');

	$ctps1_label = get_post_meta( get_the_ID(), 'wt_ctps1_label', true ) ;
	$label1 = explode("|",get_option('wt_ctfield1_info'));
	$ctps1_label = $ctps1_label =='' && isset($label1[0]) && $label1[0]!='' ? $label1[0].': ' : $ctps1_label.': ';

	$ctps2_label = get_post_meta( get_the_ID(), 'wt_ctps2_label', true ) ;
	$label2 = explode("|",get_option('wt_ctfield2_info'));
	$ctps2_label = $ctps2_label =='' && isset($label2[0]) && $label2[0]!='' ? $label2[0].': ' : $ctps2_label.': ';
	?>
	<div class="wt-table-lisst table-style-1 exwt-table-ssprice">
		<table style="width:100%" class="wt-table">
			<tr>
				<th <?php echo $ck_vari == true ? 'colspan="2"' : '';?>><?php esc_html_e('Departure','woo-tour');?></th>
				<th class="hd-pr"><?php esc_html_e('Price','woo-tour');?></th>
				<th></th>
			</tr>
			<?php foreach($p_season as $item){
				$st = $item['wt_p_start']!='' ? date_i18n( get_option('date_format'), $item['wt_p_start']) : esc_html__('No limit ','woo-tour');
				$end = $item['wt_p_end']!='' ? date_i18n( get_option('date_format'), $item['wt_p_end']) : esc_html__('No limit ','woo-tour');
				$adult = wt_get_price('', '_adult',$item);
				$child = wt_get_price('', 'wt_child',$item);
				$infant = wt_get_price('', 'wt_infant',$item);
				$cf1 = wt_get_price('', 'wt_ctps1',$item);
				$cf2 = wt_get_price('', 'wt_ctps2',$item);
				?>
				<tr>
					<td>
						<div class="tb-ssdate">
							<span><?php echo $st; ?></span>
							<?php if($end!=$st){?>
								<span class="dclb-to"><?php echo esc_html__('To','woo-tour'); ?></span>
								<span><?php echo  $end; ?></span>
							<?php }?>
						</div>
					</td>
					<?php 
					if($ck_vari == true){?>
						<td>
							<div class="tb-ssvariation">
								<span>
									<?php 
									$varia = $item['wt_p_variation']!='' ? $item['wt_p_variation']  : '';
									echo $varia!='' ? get_the_title($varia) : '';
									?>
								</span>
							</div>
						</td>
						<?php
					}
					?>
					<td>
						<div class="tb-ssprice">
							<?php 
							echo $adult!='' ? '<span><span class="tb-sslb">'.$adult_label.'</span>'.wc_price($adult).'</span>' : '';
							echo $child!='' ? '<span><span class="tb-sslb">'.$child_label.'</span>'.wc_price($child).'</span>' : '';
							echo $infant!='' ? '<span><span class="tb-sslb">'.$infant_label.'</span>'.wc_price($infant).'</span>' : '';
							echo $cf1!='' ? '<span><span class="tb-sslb">'.$ctps1_label.'</span>'.wc_price($cf1).'</span>' : '';
							echo $cf2!='' ? '<span><span class="tb-sslb">'.$ctps2_label.'</span>'.wc_price($cf2).'</span>' : '';
							?>
						</div>
					</td>
					<td class="tb-ssbt">
						<div>
							<a class="exwt-btn exwt-btn-default we-button" href="javascript:;"><?php echo esc_html__('Book Now','woo-tour'); ?></a>
						</div>
					</td>
				</tr>
			<?php }?>
		</table>
	</div>
	<?php
}

