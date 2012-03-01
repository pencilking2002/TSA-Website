<?php
    /* SETTINGS LAYOUT STYLE */
    $layouts = array(
        'left' => __( 'Left sidebar' , _DEV_ ),
        'right' => __( 'Right sidebar' , _DEV_ ),
        'full' => __( 'Full width' , _DEV_ ) 
    );
    
    /* yes - list view , no - grid view */
    $view = array(
        'yes' => __( 'List view' , _DEV_ ),
        'no' => __( 'Grid view' , _DEV_ ) 
    );
    
    /* LAYOUT - front page */
    _panel::$fields[ 'settings' ][ 'layout' ][ 'style' ][ 'front_page_title' ] = array(
        'type' => 'ni--title',
        'title' => __( 'Mainpage' , _DEV_ )
    );
    
    _panel::$fields[ 'settings' ][ 'layout' ][ 'style' ][ 'front_page' ] = array(
        'type' => 'st--select',
        'label' => __( 'Layout for mainpage' , _DEV_ ),
        'values' => $layouts , 
        'action' => "tools.sh_.select( this , { 'left' : '.front_page_sidebar' , 'right' : '.front_page_sidebar' } , 'sh_' )" 
    );
    
    _panel::$fields[ 'settings' ][ 'layout' ][ 'style' ][ 'front_page_sidebar' ] = array(
        'type' => 'st--select',
        'label' => __( 'Select sidebar for mainpage template' , _DEV_ ),
        'values' => _sidebar::getList(), 
        'classes' => 'front_page_sidebar' 
    );
    
    if( _settings::get( 'settings' , 'layout' , 'style' , 'front_page' ) == 'full' ){
        _panel::$fields[ 'settings' ][ 'layout' ][ 'style' ][ 'front_page_sidebar' ]['classes'] = 'front_page_sidebar hidden';
    }
    
    /* LAYOUT - author */
    _panel::$fields[ 'settings' ][ 'layout' ][ 'style' ][ 'author_title' ] = array(
        'type' => 'ni--title',
        'title' => __( 'Author' , _DEV_ )
    );
    
    _panel::$fields[ 'settings' ][ 'layout' ][ 'style' ][ 'author' ] = array(
        'type' => 'st--select',
        'label' => __( 'Layout for author page' , _DEV_ ),
        'values' => $layouts , 
        'action' => "tools.sh_.select( this , { 'left' : '.author_sidebar' , 'right' : '.author_sidebar' } , 'sh_' )" 
    );
    
    _panel::$fields[ 'settings' ][ 'layout' ][ 'style' ][ 'author_sidebar' ] = array(
        'type' => 'st--select',
        'label' => __( 'Select sidebar for author template' , _DEV_ ),
        'values' => _sidebar::getList(), 
        'classes' => 'author_sidebar' 
    );
    
    if( _settings::get( 'settings' , 'layout' , 'style' , 'author' ) == 'full' ){
        _panel::$fields[ 'settings' ][ 'layout' ][ 'style' ][ 'author_sidebar' ]['classes'] = 'author_sidebar hidden';
    }

    _panel::$fields[ 'settings' ][ 'layout' ][ 'style' ]['author_view'] = array(
        'type' => 'st--select',
        'label' => __( 'View type for author page' , _DEV_ ),
        'values' => $view 
    );
    
    /* LAYOUT - page / single */
    _panel::$fields[ 'settings' ][ 'layout' ][ 'style' ][ 'page_title' ] = array(
        'type' => 'ni--title',
        'title' => __( 'Page and single post' , _DEV_ )
    );
    
    _panel::$fields[ 'settings' ][ 'layout' ][ 'style' ][ 'page' ] = array(
        'type' => 'st--select',
        'label' => __( 'Layout for page' , _DEV_ ),
        'values' => $layouts , 
        'action' => "tools.sh_.select( this , { 'left' : '.page_sidebar' , 'right' : '.page_sidebar' } , 'sh_' )" 
    );
    
    _panel::$fields[ 'settings' ][ 'layout' ][ 'style' ][ 'page_sidebar' ] = array(
        'type' => 'st--select',
        'label' => __( 'Select sidebar for page template' , _DEV_ ),
        'values' => _sidebar::getList(), 
        'classes' => 'page_sidebar' 
    );
    
    if( _settings::get( 'settings' , 'layout' , 'style' , 'page' ) == 'full' ){
        _panel::$fields[ 'settings' ][ 'layout' ][ 'style' ][ 'page_sidebar' ]['classes'] = 'page_sidebar hidden';
    }

	/* single */
    _panel::$fields[ 'settings' ][ 'layout' ][ 'style' ][ 'single' ] = array(
        'type' => 'st--select',
        'label' => __( 'Layout for single post' , _DEV_ ),
        'values' => $layouts , 
        'action' => "tools.sh_.select( this , { 'left' : '.single_sidebar' , 'right' : '.single_sidebar' } , 'sh_' )" 
    );
    
    _panel::$fields[ 'settings' ][ 'layout' ][ 'style' ][ 'single_sidebar' ] = array(
        'type' => 'st--select',
        'label' => __( 'Select sidebar for single post template' , _DEV_ ),
        'values' => _sidebar::getList(), 
        'classes' => 'single_sidebar' 
    );
    
    if( _settings::get( 'settings' , 'layout' , 'style' , 'single' ) == 'full' ){
        _panel::$fields[ 'settings' ][ 'layout' ][ 'style' ][ 'single_sidebar' ]['classes'] = 'single_sidebar hidden';
    }
    
    /* LAYOUT - blog page */
    _panel::$fields[ 'settings' ][ 'layout' ][ 'style' ][ 'blog_page_title' ] = array(
        'type' => 'ni--title',
        'title' => __( 'Blog page' , _DEV_ )
    );
    
    _panel::$fields[ 'settings' ][ 'layout' ][ 'style' ][ 'blog_page' ] = array(
        'type' => 'st--select',
        'label' => __( 'Layout for blog page' , _DEV_ ),
        'values' => $layouts , 
        'action' => "tools.sh_.select( this , { 'left' : '.blog_page_sidebar' , 'right' : '.blog_page_sidebar' } , 'sh_' )" 
    );
    
    _panel::$fields[ 'settings' ][ 'layout' ][ 'style' ][ 'blog_page_sidebar' ] = array(
        'type' => 'st--select',
        'label' => __( 'Select sidebar for blog page template' , _DEV_ ),
        'values' => _sidebar::getList(), 
        'classes' => 'blog_page_sidebar'
    );
    
    if( _settings::get( 'settings' , 'layout' , 'style' , 'blog_page' ) == 'full' ){
        _panel::$fields[ 'settings' ][ 'layout' ][ 'style' ][ 'blog_page_sidebar' ]['classes'] = 'blog_page_sidebar hidden';
    }

    _panel::$fields[ 'settings' ][ 'layout' ][ 'style' ]['blog_page_view'] = array(
        'type' => 'st--select',
        'label' => __( 'View type for blog page' , _DEV_ ),
        'values' => $view 
    );
    
    /* LAYOUT - search */
    _panel::$fields[ 'settings' ][ 'layout' ][ 'style' ][ 'lsearch_title' ] = array(
        'type' => 'ni--title',
        'title' => __( 'Search' , _DEV_ )
    );
    
    _panel::$fields[ 'settings' ][ 'layout' ][ 'style' ][ 'lsearch' ] = array(
        'type' => 'st--select',
        'label' => __( 'Layout for search page' , _DEV_ ),
        'values' => $layouts , 
        'action' => "tools.sh_.select( this , { 'left' : '.lsearch_sidebar' , 'right' : '.lsearch_sidebar' } , 'sh_' )" 
    );
    
    _panel::$fields[ 'settings' ][ 'layout' ][ 'style' ][ 'lsearch_sidebar' ] = array(
        'type' => 'st--select',
        'label' => __( 'Select sidebar for search template' , _DEV_ ),
        'values' => _sidebar::getList(), 
        'classes' => 'lsearch_sidebar'
    );
    
    if( _settings::get( 'settings' , 'layout' , 'style' , 'lsearch' ) == 'full' ){
        _panel::$fields[ 'settings' ][ 'layout' ][ 'style' ][ 'lsearch_sidebar' ]['classes'] = 'lsearch_sidebar hidden';
    }

    _panel::$fields[ 'settings' ][ 'layout' ][ 'style' ]['lsearch_view'] = array(
        'type' => 'st--select',
        'label' => __( 'View type for search page' , _DEV_ ),
        'values' => $view 
    );
    
    /* LAYOUT - archive */
    _panel::$fields[ 'settings' ][ 'layout' ][ 'style' ][ 'archive_title' ] = array(
        'type' => 'ni--title',
        'title' => __( 'Archive' , _DEV_ )
    );
    
    _panel::$fields[ 'settings' ][ 'layout' ][ 'style' ][ 'archive' ] = array(
        'type' => 'st--select',
        'label' => __( 'Layout for archive page' , _DEV_ ),
        'values' => $layouts , 
        'action' => "tools.sh_.select( this , { 'left' : '.archive_sidebar' , 'right' : '.archive_sidebar' } , 'sh_' )" 
    );
    
    _panel::$fields[ 'settings' ][ 'layout' ][ 'style' ][ 'archive_sidebar' ] = array(
        'type' => 'st--select',
        'label' => __( 'Select sidebar for archive template' , _DEV_ ),
        'values' => _sidebar::getList(), 
        'classes' => 'archive_sidebar'
    );
    
    if( _settings::get( 'settings' , 'layout' , 'style' , 'archive' ) == 'full' ){
        _panel::$fields[ 'settings' ][ 'layout' ][ 'style' ][ 'archive_sidebar' ]['classes'] = 'archive_sidebar hidden';
    }

    _panel::$fields[ 'settings' ][ 'layout' ][ 'style' ]['archive_view'] = array(
        'type' => 'st--select',
        'label' => __( 'View type for archive page' , _DEV_ ),
        'values' => $view 
    );
    
    /* LAYOUT - category */
    _panel::$fields[ 'settings' ][ 'layout' ][ 'style' ][ 'category_title' ] = array(
        'type' => 'ni--title',
        'title' => __( 'Categories' , _DEV_ )
    );
    
    _panel::$fields[ 'settings' ][ 'layout' ][ 'style' ][ 'category' ] = array(
        'type' => 'st--select',
        'label' => __( 'Layout for categories page' , _DEV_ ),
        'values' => $layouts , 
        'action' => "tools.sh_.select( this , { 'left' : '.category_sidebar' , 'right' : '.category_sidebar' } , 'sh_' )" 
    );
    
    _panel::$fields[ 'settings' ][ 'layout' ][ 'style' ][ 'category_sidebar' ] = array(
        'type' => 'st--select',
        'label' => __( 'Select sidebar for category template' , _DEV_ ),
        'values' => _sidebar::getList(), 
        'classes' => 'category_sidebar'
    );
    
    if( _settings::get( 'settings' , 'layout' , 'style' , 'category' ) == 'full' ){
        _panel::$fields[ 'settings' ][ 'layout' ][ 'style' ][ 'category_sidebar' ]['classes'] = 'category_sidebar hidden';
    }

    _panel::$fields[ 'settings' ][ 'layout' ][ 'style' ]['category_view'] = array(
        'type' => 'st--select',
        'label' => __( 'View type for categories page' , _DEV_ ),
        'values' => $view 
    );
    
    /* LAYOUT - tag */
    _panel::$fields[ 'settings' ][ 'layout' ][ 'style' ][ 'tag_title' ] = array(
        'type' => 'ni--title',
        'title' => __( 'Tags' , _DEV_ )
    );
    
    _panel::$fields[ 'settings' ][ 'layout' ][ 'style' ][ 'tag' ] = array(
        'type' => 'st--select',
        'label' => __( 'Layout for tags page' , _DEV_ ),
        'values' => $layouts , 
        'action' => "tools.sh_.select( this , { 'left' : '.tag_sidebar' , 'right' : '.tag_sidebar' } , 'sh_' )" 
    );
    
    _panel::$fields[ 'settings' ][ 'layout' ][ 'style' ][ 'tag_sidebar' ] = array(
        'type' => 'st--select',
        'label' => __( 'Select sidebar for tag template' , _DEV_ ),
        'values' => _sidebar::getList(), 
        'classes' => 'tag_sidebar'
    );
    
    if( _settings::get( 'settings' , 'layout' , 'style' , 'tag' ) == 'full' ){
        _panel::$fields[ 'settings' ][ 'layout' ][ 'style' ][ 'tag_sidebar' ]['classes'] = 'tag_sidebar hidden';
    }

    _panel::$fields[ 'settings' ][ 'layout' ][ 'style' ]['tag_view'] = array(
        'type' => 'st--select',
        'label' => __( 'View type for tags page' , _DEV_ ),
        'values' => $view 
    );
    
    /* LAYOUT - attachment */
    _panel::$fields[ 'settings' ][ 'layout' ][ 'style' ][ 'attachment_title' ] = array(
        'type' => 'ni--title',
        'title' => __( 'Attachments' , _DEV_ )
    );
    
    _panel::$fields[ 'settings' ][ 'layout' ][ 'style' ][ 'attachment' ] = array(
        'type' => 'st--select',
        'label' => __( 'Layout for attachment page' , _DEV_ ),
        'values' => $layouts , 
        'action' => "tools.sh_.select( this , { 'left' : '.attachment_sidebar' , 'right' : '.attachment_sidebar' } , 'sh_' )" 
    );
    
    _panel::$fields[ 'settings' ][ 'layout' ][ 'style' ][ 'attachment_sidebar' ] = array(
        'type' => 'st--select',
        'label' => __( 'Select sidebar for attachment template' , _DEV_ ),
        'values' => _sidebar::getList(), 
        'classes' => 'attachment_sidebar'
    );
    
    if( _settings::get( 'settings' , 'layout' , 'style' , 'attachment' ) == 'full' ){
        _panel::$fields[ 'settings' ][ 'layout' ][ 'style' ][ 'attachment_sidebar' ]['classes'] = 'attachment_sidebar hidden';
    }
    
    /* LAYOUT - 404 */
    _panel::$fields[ 'settings' ][ 'layout' ][ 'style' ][ 'l404_title' ] = array(
        'type' => 'ni--title',
        'title' => __( '404 - error page' , _DEV_ )
    );
    
    _panel::$fields[ 'settings' ][ 'layout' ][ 'style' ][ 'l404' ] = array(
        'type' => 'st--select',
        'label' => __( 'Layout for 404 page' , _DEV_ ),
        'values' => $layouts , 
        'action' => "tools.sh_.select( this , { 'left' : '.l404_sidebar' , 'right' : '.l404_sidebar' } , 'sh_' )" 
    );
    
    _panel::$fields[ 'settings' ][ 'layout' ][ 'style' ][ 'l404_sidebar' ] = array(
        'type' => 'st--select',
        'label' => __( 'Select sidebar for 404 template' , _DEV_ ),
        'values' => _sidebar::getList(), 
        'classes' => 'l404_sidebar' 
    );
    
    if( _settings::get( 'settings' , 'layout' , 'style' , 'l404' ) == 'full' ){
        _panel::$fields[ 'settings' ][ 'layout' ][ 'style' ][ 'l404_sidebar' ]['classes'] = 'l404_sidebar hidden';
    }
?>