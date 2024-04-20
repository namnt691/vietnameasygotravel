<?php
global $the_query,$posts_per_page,$count,$layout,$idsc;
if($the_query->have_posts()){
	echo '<div class="wt-ajax-dfrs">';
	if($layout=='grid'){?>
        <div class="wt-grid-shortcode wt-grid-column-3 gr-classic">
            <div class="ct-grid">
            <div class="grid-container">
                <?php 
                global $columns,$number_excerpt,$img_size;
                $columns = 3; $number_excerpt=15;$img_size='wethumb_460x307';
                $i=0;
                $it = $the_query->found_posts;
                if($it < $count || $count=='-1'){ $count = $it;}
                if($count  > $posts_per_page){
                    $num_pg = ceil($count/$posts_per_page);
                    $it_ep  = $count%$posts_per_page;
                }else{
                    $num_pg = 1;
                }
                while($the_query->have_posts()){ $the_query->the_post();
                    if(function_exists('wp_pagenavi')) {
                        $the_query->max_num_pages = $num_pg;
                    }
                    $i++;
                    if(($num_pg == $paged) && $num_pg!='1'){
                        if($i > $it_ep){ break;}
                    }
                    wootour_template_plugin('grid-classic', true);
                    
                }?>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
        <div class="clearfix"></div>
        <?php
	}else{
		global $style;
		$style = 2;?>
		<div class="wt-table-lisst table-style-2">
            <table class="wt-table">
                <tbody>
				<?php
                $i=0;
                $it = $the_query->found_posts;
                if($it < $count || $count=='-1'){ $count = $it;}
                if($count  > $posts_per_page){
                    $num_pg = ceil($count/$posts_per_page);
                    $it_ep  = $count%$posts_per_page;
                }else{
                    $num_pg = 1;
                } 
                while($the_query->have_posts()){ $the_query->the_post();
                    $i++;
                    if(($num_pg == $paged) && $num_pg!='1'){
                        if($i > $it_ep){ break;}
                    }
                    wootour_template_plugin('table', true);
                }?>
                </tbody>
			</table>
		</div>
	<?php }
	if($posts_per_page<$count && $num_pg > 1){
		wpext_pagenavi($the_query, $idsc);
	}
	echo '</div>';
}else{
	$textrsl = esc_html__('Nothing matched your search terms. Please try again with some different keywords.','woo-tour');
	echo '<ul class="products we-search-ajax no-result-info"><p class="woocommerce-info calendar-info">'.$textrsl.'</p></ul>';
}
