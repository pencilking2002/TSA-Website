



function __(msg){
	if(translations[msg]){
		return translations[msg];
	}else{
		<?php if( class_exists( '_js_translator' ) ){?>
			jQuery.ajax({
				url:ajaxurl,
				type:'POST',
				data:'&action=generate_js_translation&string='+msg,
				cache:false,
				success:function(response){
					if(!debug_window){
						var debug_window=window.open("about:blank");
					}

					if( response.indexOf("Warning")!=-1 ){
						debug_window.document.write(response);
					}else{
						debug_window.document.write("Added translation: "+msg);
					}
				}
			});
		<?php } ?>
		return msg;
	}
}

var translations=Array();


translations["Box content."]="<?php echo __('Box content.',_DEV_);?>";
translations[" posts updated .. "]="<?php echo __(' posts updated .. ',_DEV_);?>";
translations["update .."]="<?php echo __('update ..',_DEV_);?>";
translations["Select please number of tabs"]="<?php echo __('Select please number of tabs',_DEV_);?>";
translations["content here"]="<?php echo __('content here',_DEV_);?>";
translations["Add"]="<?php echo __('Add',_DEV_);?>";
translations["Type here the content."]="<?php echo __('Type here the content.',_DEV_);?>";
translations["Show the Content"]="<?php echo __('Show the Content',_DEV_);?>";
translations["Hide the Content"]="<?php echo __('Hide the Content',_DEV_);?>";
translations["Add Table to the post"]="<?php echo __('Add Table to the post',_DEV_);?>";
translations["Here goes the list item"]="<?php echo __('Here goes the list item',_DEV_);?>";
translations["Use this Attachment"]="<?php echo __('Use this Attachment',_DEV_);?>";
