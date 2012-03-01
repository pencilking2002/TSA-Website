



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

translations["Uploading"]="<?php echo __('Uploading',_DEV_);?>";
translations["file"]="<?php echo __('file',_DEV_);?>";
translations["This may take a while"]="<?php echo __('This may take a while',_DEV_);?>";
translations["Click to set as featured"]="<?php echo __('Click to set as featured',_DEV_);?>";
translations["Remove"]="<?php echo __('Remove',_DEV_);?>";
translations["Are you sure?"]="<?php echo __('Are you sure?',_DEV_);?>";
