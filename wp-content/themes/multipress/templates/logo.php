<div class="logo b w_210">
    <?php
        if( _core::method( '_settings' , 'get' , 'settings' , 'style' , 'general' , 'logo_type' ) == 'text' ){
    ?>
            <?php echo _core::method( '_text' , 'content' , 'settings' , 'style' , 'general' , 'logo_text' , 'link_text' , array( home_url() , get_option( 'blogname' ) ), 'h1' ) ?>
    <?php
        }else{
			$logo=_core::method( '_settings' , 'get' , 'settings' , 'style' , 'general' , 'logo_upload' );
			if(strlen($logo)){?>
				<h1><a href="<?php echo home_url(); ?>" class="hover"><img src="<?php echo $logo; ?>" /></a></h1>
		<?php }else{ ?>
				<h1><a href="<?php echo home_url(); ?>" class="hover"><img src="<?php echo get_template_directory_uri();?>/images/logo.png"/></a></h1>
		<?php }
        }
    ?>
</div>
<?php _core::hook( 'logo' ); ?>
