;(function($){
	'use strict';
	$(document).ready(function() {
		function exwo_add_title($box){
			if(!$box.length){ return;}
			$box.find( '.cmb-group-title' ).each( function() {
				var $this = $( this );
				var txt = $this.next().find( '[id$="_name"]' ).val();
				var rowindex;
				if ( ! txt ) {
					txt = $box.find( '[data-grouptitle]' ).data( 'grouptitle' );
					if ( txt ) {
						rowindex = $this.parents( '[data-iterator]' ).data( 'iterator' );
						txt = txt.replace( '{#}', ( rowindex + 1 ) );
					}
				}
				if ( txt ) {
					$this.text( txt );
				}
			});
		}
		function exwo_replace_title(evt){
			var $this = $( evt.target );
			var id = 'name';
			if ( evt.target.id.indexOf(id, evt.target.id.length - id.length) !== -1 ) {
				$this.parents( '.cmb-row.cmb-repeatable-grouping' ).find( '.cmb-group-title' ).text( $this.val() );
			}
		}
		jQuery('#exwo_addition_options').on( 'cmb2_add_row cmb2_shift_rows_complete', exwo_add_title )
				.on( 'keyup', exwo_replace_title );
		exwo_add_title(jQuery('#exwo_addition_options'));
		jQuery('body').on('click', function() {
			exwo_add_title(jQuery('#exwo_addition_options'));
		});

		// show hide option by type of option
		if(jQuery('#cmb2-metabox-exwo_addition_options.cmb2-metabox .extype-option select').length>0){
			jQuery('#cmb2-metabox-exwo_addition_options.cmb2-metabox .extype-option select').each(function(){
				var $this = $(this);
				var $val = $this.val();
				var $cls = '';
				if($val!=''){
					$cls = 'ex-otype-'+$val;
				}
				var $enb_img = $this.closest('.exwo-general').find('.exwo-op-enbimg select').val();
				if($enb_img==''){$cls = $cls+' ex-hide-enbimg';}
				if($cls!=''){
					$this.closest('.postbox.cmb-repeatable-grouping').addClass($cls);
				}
			});
			jQuery('body').on('change', '#cmb2-metabox-exwo_addition_options.cmb2-metabox .extype-option select', function() {
				var $this = $(this);
				var $val = $this.val();
				$this.closest(".postbox").find('.exwo-options input[type="checkbox"]').prop('checked', false);
				$this.closest('.postbox.cmb-repeatable-grouping').removeClass (function (index, className) {
					return (className.match (/(^|\s)ex-otype\S+/g) || []).join(' ');
				});
				if($val!=''){
					$this.closest('.postbox.cmb-repeatable-grouping').addClass('ex-otype-'+$val);
				}
			});
			jQuery('body').on('change', '#cmb2-metabox-exwo_addition_options.cmb2-metabox .exwo-op-enbimg select', function() {
				var $this = $(this);
				var $val = $this.val();
				if($val!=''){
					$this.closest('.postbox.cmb-repeatable-grouping').removeClass('ex-hide-enbimg');
				}
				else{
					$this.closest('.postbox.cmb-repeatable-grouping').addClass('ex-hide-enbimg');
				}
			});
		}
		// set default value
		$('body').on('change', '.cmb-type-price-options .exwo-options input[type="checkbox"]', function() {
	    	var $this_sl = $(this);
	    	var $nbsl = $this_sl.closest(".cmb-type-price-options").find('.exwo-options.exwo-def-option input[type="checkbox"]:checked').length;
	    	if( ($this_sl.closest(".postbox.ex-otype-radio").length || $this_sl.closest(".postbox.ex-otype-select").length) &&  $nbsl > 1 ){
	    		$this_sl.closest(".cmb-type-price-options").find('.exwo-options.exwo-def-option input[type="checkbox"]').prop('checked', false);
		    	this.checked = true;
		    	event.preventDefault();
		    }
	    });
	    // change settings tab
	    $('body').on('click', '.exwo-gr-option a:not(.exwo-copypre):not(.exwo-copy):not(.exwo-paste)', function() {
	    	var $this_sl = $(this);
	    	$this_sl.closest('.cmb-field-list').find('.exwo-gr-option a').removeClass('current');
	    	$this_sl.addClass('current');
	    	var _remove = $this_sl.attr('data-remove');
	    	var _add = $this_sl.attr('data-add');
	    	$this_sl.closest('.cmb-field-list').find(_remove).fadeOut();
	    	$this_sl.closest('.cmb-field-list').find(_add).fadeIn();
	    });
	    jQuery( "body" ).on( "change", ".exwo-options.exwo-val-option select", function () {
			jQuery(this).next().val( (jQuery(this).val()) );
		});
		//copy from previous option
		jQuery('#exwo_addition_options').on('click', '.exwo-copypre',function() {
	    	var $crr_info = $(this).closest('.cmb-repeatable-grouping');
	    	var $pre_info = $crr_info.prev();
    		$crr_info.find('.exwo-op-name .cmb-td input').val($pre_info.find('.exwo-op-name .cmb-td input').val());
    		$crr_info.find('.exwo-op-type .cmb-td select').val($pre_info.find('.exwo-op-type .cmb-td select').val()).trigger('change');
    		$crr_info.find('.exwo-op-enbimg .cmb-td select').val($pre_info.find('.exwo-op-enbimg .cmb-td select').val()).trigger('change');
    		$crr_info.find('.exwo-op-rq .cmb-td select').val($pre_info.find('.exwo-op-rq .cmb-td select').val());
    		$crr_info.find('.exwo-op-min .cmb-td input').val($pre_info.find('.exwo-op-min .cmb-td input').val());
    		$crr_info.find('.exwo-op-max .cmb-td input').val($pre_info.find('.exwo-op-max .cmb-td input').val());
    		var _name = $crr_info.find('.exwo-op-name .cmb-td input').attr('name');
			var _res =  _name.split("][");
			var _iterator = _res[0].match(/\d+/);
			//console.log(_iterator);
    		var _mt_op = $pre_info.find('.exwo-op-ops .cmb-td .cmb-field-list').html();
    		//console.log(_mt_op);
    		$crr_info.find('.exwo-op-ops .cmb-td .cmb-field-list').html(_mt_op);
    		$crr_info.find( '.exwo-op-ops .cmb-td .cmb-field-list input:not([type=button])' ).each( function(){
				var $_name = $(this).attr('name');
				var res =  $_name.split("][");
				$_name = $_name.replace( res[0], 'exwo_options['+(_iterator) );
				$(this).attr('name',$_name);
			});

    		$crr_info.find('.exwo-op-tpr .cmb-td select').val($pre_info.find('.exwo-op-tpr .cmb-td select').val());
    		$crr_info.find('.exwo-op-pri .cmb-td input').val($pre_info.find('.exwo-op-pri .cmb-td input').val());
	    });
	    // Copy option
	    jQuery('#exwo_addition_options').on('click', '.exwo-copy',function() {
	    	var $temp = $("<input class='exwo-ctcopy'>");
	    	var $crr_info = $(this).closest('.cmb-repeatable-grouping');
			$("body").append($temp);
			$temp.val($crr_info.html()).select();
			document.execCommand("copy");
			$temp.remove();
	    });
	    jQuery('#exwo_addition_options').on('click', '.exwo-paste',function(e) {
	    	$(this).find('.exwo-ctpaste').fadeIn();
	    	$(this).find('.exwo-paste-tt').css('display','block');
	    	$(this).find('.exwo-paste-mes').css('display','none');
	    	/*
	    	navigator.clipboard.readText().then(text => {
		        // use text as a variable, here text = 'clipboard text'
		        $("body").append('<div class="copy-hidden"></div>');
		        $('.copy-hidden').html(text);
		    });
		    */
	    });
	    if(jQuery('#exwo_addition_options').length){
		    jQuery(document).on('click', function (e) {
			    if ($(e.target).closest(".exwo-paste").length === 0) {
			        $('.exwo-ctpaste').fadeOut();
			    }
			    if ($(e.target).closest(".exwo-val-option").length === 0) {
			        $('.exwo-list-value').fadeOut();
			    }
			});
		}
	    $("body").on('paste', '#exwo_addition_options .exwo-ctpaste', function (){
	    	var $this = $(this);
	    	var $crr_info = $this.closest('.cmb-repeatable-grouping');
	    	setTimeout(function () {
		    	var $pre_info = $this.val();
		    	$pre_info = $('<div>'+$pre_info+'<div>');
		    	$crr_info.find('.exwo-op-name .cmb-td input').val($pre_info.find('.exwo-op-name .cmb-td input').val());
	    		$crr_info.find('.exwo-op-type .cmb-td select').val($pre_info.find('.exwo-op-type .cmb-td select').val()).trigger('change');
	    		$crr_info.find('.exwo-op-rq .cmb-td select').val($pre_info.find('.exwo-op-rq .cmb-td select').val());
	    		$crr_info.find('.exwo-op-min .cmb-td input').val($pre_info.find('.exwo-op-min .cmb-td input').val());
	    		$crr_info.find('.exwo-op-max .cmb-td input').val($pre_info.find('.exwo-op-max .cmb-td input').val());
	    		var _name = $crr_info.find('.exwo-op-name .cmb-td input').attr('name');
				var _res =  _name.split("][");
				var _iterator = _res[0].match(/\d+/);
				//console.log(_iterator);
	    		var _mt_op = $pre_info.find('.exwo-op-ops .cmb-td .cmb-field-list').html();
	    		//console.log(_mt_op);
	    		$crr_info.find('.exwo-op-ops .cmb-td .cmb-field-list').html(_mt_op);
	    		$crr_info.find( '.exwo-op-ops .cmb-td .cmb-field-list input:not([type=button])' ).each( function(){
					var $_name = $(this).attr('name');
					var res =  $_name.split("][");
					$_name = $_name.replace( res[0], 'exwo_options['+(_iterator) );
					$(this).attr('name',$_name);
				});

	    		$crr_info.find('.exwo-op-tpr .cmb-td select').val($pre_info.find('.exwo-op-tpr .cmb-td select').val());
	    		$crr_info.find('.exwo-op-pri .cmb-td input').val($pre_info.find('.exwo-op-pri .cmb-td input').val());

	    		$this.closest('.exwo-paste').find('.exwo-paste-tt').css('display','none');
	    		$this.closest('.exwo-paste').find('.exwo-paste-mes').css('display','block');
	    		$this.val('').fadeOut();

	    	}, 100);
    		
		} );
		jQuery('#exwo_addition_options').on('click', '.cmb-add-row button',function(e) {
	    	$(this).closest('.cmb-row').prev().find('.exwo-paste-mes').css('display','none');
	    	$(this).closest('.cmb-row').prev().find('.exwo-paste-tt').css('display','block');
	    	$(this).closest('.cmb-row').prev().find('.exwo-copy').addClass('disabled').css('opacity','0.5');
	    	$(this).closest('.cmb-row').prev().find('.exwo-con-logic .exwo-stcon-logic .exwo-options.exwo-type_op-option select option').prop("disabled", false);
	    });
	    jQuery('#exwo_addition_options').on('click', '.exwo-copy.disabled',function(e) {
	    	alert($(this).attr('data-textdis'));
	    });
	    jQuery('#exwo_addition_options').on('click', '.exwo-con-logic .exwo-val-option',function(e) {
	    	var $this = $(this);
	    	var $type_op = $this.closest('.cmb-td').find('.exwo-type_op-option select').val();
	    	if($type_op =='varia'){
	    		$this.find('.exwo-list-value li:not(.exwo-variation)').css('display','none');
	    		$this.find('.exwo-list-value li.exwo-variation').css('display','block');
	    	}else{
	    		$this.find('.exwo-list-value li').css('display','none');
	    		$this.find('.exwo-list-value li.'+$type_op).css('display','block');
	    	}
	    	if(!$this.find('.exwo-list-value li').hasClass($type_op)){
	    		$this.find('input').attr("readonly", false);
	    	}else{
	    		$this.find('input').attr("readonly", true);
	    	}
	    	$this.find('.exwo-list-value').fadeIn();
	    });
	    jQuery('#exwo_addition_options').on('click', '.exwo-con-logic .exwo-list-value li',function(e) {
	    	var $this = $(this);
	    	e.stopPropagation();
	    	$this.closest('.exwo-val-option').find('li').removeClass('exwo-current');
	    	$this.addClass('exwo-current');
	    	$this.closest('.exwo-val-option').find('.exwo-conval').val($this.attr('data-val'));
	    	$('.exwo-list-value').fadeOut();
	    });

	    $("body").on("click",".exwf-collapse .cmb-type-title" ,function(e){
			var $this = $(this);
			$($this).next(".exwf-collapse-con").slideToggle(200);
			if($this.hasClass('exwf-active')){ 
				$this.removeClass('exwf-active');
			}else{
				$this.addClass('exwf-active');
			}
		});
    });
}(jQuery));