<?php
global $event_items;
$check_ev = false;
foreach ( $event_items as $item ) {
	$product_id = $item['product_id'];
	$wt_startdate = get_post_meta( $product_id, 'wt_startdate', true );
	$wt_enddate = get_post_meta( $product_id, 'wt_enddate', true );
	if($wt_startdate!=''){
		$check_ev = true;
		break;
	}
}
if($check_ev == true){
	?>
	<table class="td" cellspacing="0" cellpadding="6" style="width: 100%; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;" border="1">
		<thead>
			<tr>
				<th class="td" scope="col" style="text-align:left;"><?php echo esc_html__( 'Tour Name', 'woo-tour' ); ?></th>
				<th class="td" scope="col" style="text-align:left;"><?php echo esc_html__( 'Departure', 'woo-tour' ); ?></th>
				<th class="td" scope="col" style="text-align:left;"><?php echo esc_html__( 'Location', 'woo-tour' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ( $event_items as $item ) {
					$product_name = $item['name'];
					$product_id = $item['product_id'];
					$product_variation_id = $item['variation_id'];
					$wt_startdate = get_post_meta( $product_id, 'wt_startdate', true );
					$wt_enddate = get_post_meta( $product_id, 'wt_enddate', true );
					if($wt_startdate!=''){
					?>
					<tr>
						<td class="td" scope="col" style="text-align:left; border: 1px solid #e4e4e4;"><?php echo $item['name'];?></td>
						<td class="td" scope="col" style="text-align:left; border: 1px solid #e4e4e4;">
							<span class=""><b><?php echo esc_html__('Departure','woo-tour');?>: </b><?php echo date_i18n( get_option('date_format'), $wt_startdate).' '.date_i18n(get_option('time_format'), $wt_startdate);?></span><br>
						</td>
						<td class="td" scope="col" style="text-align:left; border: 1px solid #e4e4e4;"><?php echo get_post_meta( $product_id, 'wt_adress', true );?></td>
					</tr>
					<?php
					}else{
						$product_id = wp_get_post_parent_id( $product_id );
						$wt_startdate = get_post_meta( $product_id, 'wt_startdate', true );
						$wt_enddate = get_post_meta( $product_id, 'wt_enddate', true );
						if($wt_startdate!=''){
							?>
							<tr>
								<td class="td" scope="col" style="text-align:left; border: 1px solid #e4e4e4;"><?php echo get_the_title($product_id);?></td>
								<td class="td" scope="col" style="text-align:left; border: 1px solid #e4e4e4;">
									<span class=""><b><?php echo esc_html__('Start Date','woo-tour');?>: </b><?php echo date_i18n( get_option('date_format'), $wt_startdate).' '.date_i18n(get_option('time_format'), $wt_startdate);?></span><br>
									<span class=""><b><?php echo esc_html__('End Date','woo-tour');?>: </b><?php echo date_i18n( get_option('date_format'), $wt_enddate).' '.date_i18n(get_option('time_format'), $wt_enddate);?></span><br>
								</td>
								<td class="td" scope="col" style="text-align:left; border: 1px solid #e4e4e4;"><?php echo get_post_meta( $product_id, 'wt_adress', true );?></td>
							</tr>
							<?php
						}
					}
					
				} ?>
		</tbody>
		<tfoot>
		</tfoot>
	</table>
	<?php
}