<?php
    header( 'Content-type:text/javascript' );
    include '../../../../../../wp-load.php';
    
    $initScript = '';
     
?>
<?php if( false ) { ?><script><?php } ?>

<?php
    
    include get_template_directory() . '/../../../wp-admin/js/farbtastic.js';
    include 'jquery.cookie.js';
    include 'jquery.autocomplete-min.js';
    
    
    
    include 'map.js';
    include 'box.js';
    include 'tools.js';
    
    include 'fields.js';
    include 'resources.js';
    include 'meta.js';
    
    include 'sidebar.js';
    include 'attachment.js';
    include 'attachdocs.js';
    include 'program.js';
    include 'likes.js';
    include 'additional.js';
    include 'widget.js';
    include 'slideshow.js';

	include 'translations.js.php';
    
?>
    
/* init scripts */
jQuery(function(){
    field.vr.init();
    res.vr.init();
    res.tax.vr.init();
    sidebar.vr.init();
});
        
<?php if( false ) { ?></script><?php } ?>
