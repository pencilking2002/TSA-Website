<?php
    header( 'Content-type:text/css' );
    include '../../../../wp-load.php';
    
    include 'generic.css.php';
    
    $noinclude = array( 'generic.css.php' , 'all.css.php' , 'custom.css' );
    $files = scandir( get_template_directory() . '/css' );
    $custom = false;
    foreach( $files as $file ){
        if( file_exists( $file ) && !in_array( $file , $noinclude ) && is_file( $file ) ){
            include $file;
        }
        
        if( $file == 'custom.css' ){
            $custom = true;
        }
    }
    
    /* custom css // use options _options::value(  'css' , 'custom' , 'file' ) */
    if( $custom ){
        include 'custom.css';
    }
    
    echo _core::method( '_settings' , 'get' , 'extra' , 'settings' , 'css' , 'header-css' );
    echo _core::method( '_settings' , 'get' , 'extra' , 'settings' , 'css' , 'general-css' );
    
    $bgstyle = '';
    
    /* CUSTOM CSS ON SINGLE */
    if( isset( $_GET[ 'post' ] ) && (int)$_GET[ 'post' ] > 0 ){
        $posts_settings = _core::method( '_meta' , 'get' , (int)$_GET[ 'post' ] , 'posts-settings' );
        
        /* GENERAL STYLE */
        $background = _core::method( '_settings' , 'get' , 'settings' , 'style' , 'general' , 'background' );
        $color = _core::method( '_settings' , 'get' , 'settings' , 'style' , 'general' , 'background_color' );
        
        if( ( isset( $posts_settings[ 'background-color' ] ) && !empty( $posts_settings[ 'background-color' ] ) ) || ( isset( $posts_settings[ 'background-image' ] ) && !empty( $posts_settings[ 'background-image' ] ) ) ){
            $single = "\nbody {\n";
            
            if( isset( $posts_settings[ 'background-color' ] ) && !empty( $posts_settings[ 'background-color' ] ) ){
                if( _settings::$default[ 'settings' ][ 'style' ][ 'general' ][ 'background_color' ] != $posts_settings[ 'background-color' ] ){
                    $single .= "\tbackground-color: " . $posts_settings[ 'background-color' ] . " !important;\n";
                }else{
                    $single .= "\tbackground-color: " . $color . " !important;\n";
                }
            }
            
            if( isset( $posts_settings[ 'background-image' ] ) && !empty( $posts_settings[ 'background-image' ] ) ){
                $single .= "\tbackground-image: url('" . $posts_settings[ 'background-image' ] . "');\n";
                
                if( isset( $posts_settings[ 'background-position' ] ) && !empty( $posts_settings[ 'background-position' ] ) ){
                    $single .= "\tbackground-position: " . $posts_settings[ 'background-position' ] . ";\n";
                }

                if( isset( $posts_settings[ 'background-repeat' ] ) && !empty( $posts_settings[ 'background-repeat' ] ) ){
                    $single .= "\tbackground-repeat: " . $posts_settings[ 'background-repeat' ] . ";\n";
                }

                if( isset( $posts_settings[ 'background-attachment-type' ] ) && !empty( $posts_settings[ 'background-attachment-type' ] ) ){
                    $single .= "\tbackground-attachment: " . $posts_settings[ 'background-attachment-type' ] . ";\n";
                }
            }else{
                if( strlen( $background ) > 1 && !strpos( "none.png" , $background ) ){
                    $single .= "\tbackground-image: url('" . str_replace( "s.pattern" , "pattern" , $background ) . "');\n";
                    $single .= "\tbackground-repeat: repeat;\n";
                }
            }
            
            $single .= "}\n";
            
            echo $single;
        }else{
            /* GENERAL CUSTOM CSS */
            $background = _core::method( '_settings' , 'get' , 'settings' , 'style' , 'general' , 'background' );
            $color = _core::method( '_settings' , 'get' , 'settings' , 'style' , 'general' , 'background_color' );

            $general = '';
            if( strlen( $background ) > 1 && !strpos( "none.png" , $background ) ){
                $bgstyle .= "\tbackground-image: url('" . str_replace( "s.pattern" , "pattern" , $background ) . "');\n";
                $bgstyle .= "\tbackground-repeat: repeat;\n";
                $bgstyle .= "\tbackground-color: " . $color . ";\n";   
            }

            if( !empty( $bgstyle ) ){
                $general .= "\nbody {\n";
                $general .= $bgstyle;
                $general .= "}\n";
            }

            echo $general;
        }
    }else{
        /* GENERAL CUSTOM CSS */
        $background = _core::method( '_settings' , 'get' , 'settings' , 'style' , 'general' , 'background' );
        $color = _core::method( '_settings' , 'get' , 'settings' , 'style' , 'general' , 'background_color' );
        
        $general = '';
        if( strlen( $background ) > 1 && !strpos( "none.png" , $background ) ){
            $bgstyle .= "\tbackground-image: url('" . str_replace( "s.pattern" , "pattern" , $background ) . "');\n";
            $bgstyle .= "\tbackground-repeat: repeat;\n";
            $bgstyle .= "\tbackground-color: " . $color . ";\n";   
        }
        
        if( !empty( $bgstyle ) ){
            $general .= "\nbody {\n";
            $general .= $bgstyle;
            $general .= "}\n";
        }
        
        echo $general;
    }
    
    $slidepanel  = "div#slidePanel.slide-panel {\n";
    $slidepanel .= $bgstyle;
    $slidepanel .= "}\n";
    
    echo $slidepanel;
    
    
    if( _core::method( '_settings' , 'logic' , 'settings' , 'style' , 'general' , 'fixed-width-layout' ) ){
        $content = "div.b_content.clearfix {\n";
        $color = _core::method( '_settings' , 'get' , 'settings' , 'style' , 'general' , 'content_bg_color' );

        if( strlen( $color ) < 1 ){
            $content .= "\tbackground-color: #ffffff;\n";
        }else{
            $content .= "\tbackground-color: " . $color . ";\n";
        }
        $content .= "}\n";
        
        echo $content;
    }
    
    include '../lib/core/css/shortcode.css';
?>