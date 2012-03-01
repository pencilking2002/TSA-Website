<?php
    _panel::$fields[ 'settings' ][ 'front_page' ][ 'resource' ][ 'type' ] = array(
        'type' => 'st--select',
        'label' => __( 'Select mainpage resource type' , _DEV_ ),
        'values' => array(
            'latest-post' => __( 'Latest post' , _DEV_ ),
            'selected-post' => __( 'Selected post' , _DEV_ ),
            'selected-page' => __( 'Selected page' , _DEV_ ),
            'widgets' => __( 'Widgets' , _DEV_ )
        ),
        'action' => "tools.sh.select( this , { 'selected-post':'.post-options' , 'selected-page':'.page-options' })"
    );
    
    switch( _core::method( '_settings' , 'get' , 'settings' , 'front_page' , 'resource' , 'type' ) ){
        case 'selected-post' : {
            $page_classes = 'page-options hidden';
            $post_classes = 'post-options';
            break;
        }
        
        case 'selected-page' : {
            $page_classes = 'page-options';
            $post_classes = 'post-options hidden';
            break;
        }
        
        default : {
            $page_classes = 'page-options hidden';
            $post_classes = 'post-options hidden';
            break;
        }
    }
    
    $resources = _core::method( '_resources' , '_get' );
    
    $type = array( 'post' );
    if( is_array( $resources ) && !empty( $resources ) ){
        foreach( $resources as $resource ){
            if( isset( $resource[ 'slug' ] ) ){
                array_push( $type , $resource[ 'slug' ] );
            }
        }
    }
    
    _panel::$fields[ 'settings' ][ 'front_page' ][ 'resource' ][ 'post' ] = array(
        'type' => 'st--search',
        'label' => __( 'Select post to display on mainpage' , _DEV_ ),
        'query' => array(
            'post_type' => $type,
            'post_status' => 'publish'
        ),
        'hint' => __( 'start typing post title' , _DEV_ ),
        'classes' => $post_classes 
    );
    
    _panel::$fields[ 'settings' ][ 'front_page' ][ 'resource' ][ 'page' ] = array(
        'type' => 'st--search',
        'label' => __( 'Select page to display on mainpage' , _DEV_ ),
        'query' => array(
            'post_type' => 'page',
            'post_status' => 'publish'
        ),
        'hint' => __( 'start typing page title' , _DEV_ ),
        'classes' => $page_classes
    )
?>