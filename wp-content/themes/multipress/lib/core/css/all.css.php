<?php
    header( 'Content-type:text/css' );
    include '../../../../../../wp-load.php';
    
    $noload = array( 'all.css.php' , 'shortcodes.css' );
    $files = scandir('.');
    
    foreach( $files as $file ){
        if( file_exists( $file ) && is_file( $file ) && !in_array( $file , $noload ) ){
            include $file;
        }
    }
?>