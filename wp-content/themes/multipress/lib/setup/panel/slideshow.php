<?php
	$slideshows=get_posts( array(
            'post_type' => 'slideshow',
            'post_status' => 'publish'
        ) );

	if(count($slideshows)==0){
		_panel::$fields[ 'settings' ][ 'slideshow' ][ 'general' ][ 'item' ] = array(
			'type' => 'st--hint',
			'value' => __( 'No sliders. To create a slide go to '  , _DEV_ ) . '<a href="post-new.php?post_type=slideshow">' . __( 'Add New Slideshow' , _DEV_ ) . '</a>'
		);
	}else{
		if(count($slideshows)==1){
			_core::method( '_settings' , 'set' , 'settings', 'slideshow' , 'general' , 'item', $slideshows[0]->ID );
		}

		_panel::$fields[ 'settings' ][ 'slideshow' ][ 'general' ][ 'item' ] = array(
			'type' => 'st--search',
			'query' => array(
				'post_type' => 'slideshow',
				'post_status' => 'publish'
			),
			'label' => __( 'Default slideshow' , _DEV_ ),
			'hint' => __( 'Start typing the' , _DEV_ ) . ' <strong>' . __( 'slideshow' , _DEV_  ) . '</strong> ' . __( 'title' , _DEV_ )
		);
	}
   
    _panel::$fields[ 'settings' ][ 'slideshow' ][ 'general' ][ 'speed' ] = array(
        'type' => 'st--digit',
        'label' => __( 'Slideshow play speed' , _DEV_ ),
		'hint' => 'In milliseconds'
    );
    
    _panel::$fields[ 'settings' ][ 'slideshow' ][ 'general' ][ 'news-limit' ] = array(
        'type' => 'st--text',
        'label' => __( 'Set Quick-news length' , _DEV_ ),
		'hint' => 'Number of visible characters'
    );
    
    _panel::$fields[ 'settings' ][ 'slideshow' ][ 'general' ][ 'news-type' ] = array(
        'type' => 'st--select',
        'label' => __( 'Select type of Quick-news' , _DEV_ ),
        'values' => array(
            'latest-post' => __( 'Latest post ( any types custom posts ) ' , _DEV_ ),
            'latest-custom-post' => __( 'Latest custom post' , _DEV_ ),
        ),
        'action' => "tools.sh.select( this , { 'latest-custom-post' : '.latest-custom-post' })"
    );
    
    $news_type = _settings::get( 'settings' , 'slideshow' , 'general' , 'news-type' );
    
    if( $news_type == 'latest-post' ){
        $classes = 'latest-custom-post hidden';
    }else{
        $classes = 'latest-custom-post';
    }
            
    _panel::$fields[ 'settings' ][ 'slideshow' ][ 'general' ][ 'custom-post' ] = array(
        'type' => 'st--select',
        'label' => __( 'Select custom post for latest quick news' , _DEV_ ),
        'values' => _resources::getSlugs( true , array( 'slideshow' ) ),
        'classes' => $classes,
        'action' => "res.tax.r( 'getCustomTaxonomy' , [ this.value ] )"
    );
    
    /* custom taxonomy */
    _panel::$fields[ 'settings' ][ 'slideshow' ][ 'general' ][ 'code-custom-post-start' ] = array(
        'type' => 'cd--code-start',
        'content' => '<div class="custom-taxonomy ' . $classes . '">'
    );
    
    $custom_post_type = _settings::get( 'settings' , 'slideshow' , 'general' , 'custom-post' );
    
    if( strlen( $custom_post_type ) ){
        if( $custom_post_type != 'post' ){
            $resourceID = _resources::getResourceBySlug( $custom_post_type );
            $taxonomy = _taxonomy::getByResourceID( $resourceID );
        }else{
            $taxonomy = array(
                'post_tag' => __( 'Tags' , _DEV_ ),
                'category' => __( 'Category' , _DEV_ )
            );
        }
        
        if( !empty( $taxonomy ) && count( $taxonomy ) > 1 ){
            _panel::$fields[ 'settings' ][ 'slideshow' ][ 'general' ][ 'taxonomy' ] = array(
                'type' => 'st--select',
                'label' => __( 'Select taxonomy for latest quick news'  , _DEV_ ),
                'values' => $taxonomy,
                'action' => "res.tax.r( 'getTaxonomyTerms' , [ this.value ] )"
            );
        }
    }
    
    /* taxonomy terms */
    _panel::$fields[ 'settings' ][ 'slideshow' ][ 'general' ][ 'code-taxonomy-start' ] = array(
        'type' => 'cd--code-start',
        'content' => '<div class="taxonomy-terms ' . $classes . '">'
    );
    
    $terms = _taxonomy::getTerms( _settings::get( 'settings' , 'slideshow' , 'general' , 'taxonomy' ) );
    
    if( !empty( $terms ) ){
        
        if( !empty( $terms ) && count( $terms ) > 1 ){
            _panel::$fields[ 'settings' ][ 'slideshow' ][ 'general' ][ 'terms' ] = array(
                'type' => 'st--select',
                'label' => __( 'Select termen for latest quick news' , _DEV_ ),
                'values' => $terms
            );
        }
    }
    
    _panel::$fields[ 'settings' ][ 'slideshow' ][ 'general' ][ 'code-taxonomy-end' ] = array(
        'type' => 'cd--code-start',
        'content' => '</div>'
    );
    
    _panel::$fields[ 'settings' ][ 'slideshow' ][ 'general' ][ 'code-custom-post-end' ] = array(
        'type' => 'cd--code-start',
        'content' => '</div>'
    );
?>