<?php
    _panel::$fields[ 'settings' ][ 'general' ][ 'upload' ][ 'enb_edit_delete' ] = array(
        'type' => 'st--logic-radio',
        'label' => __( 'Enable edit / delete options in meta'  , _DEV_ )
    );
    
    _panel::$fields[ 'settings' ][ 'general' ][ 'upload' ][ 'post_item_page' ] = array(
        'type' => 'st--search',
        'label' => __( 'Front-end submit page'  , _DEV_ ),
        'query' => array(
            'post_type' => 'page',
            'post_status' => 'publish'
        ),
        'hint' => __( 'Start typing <strong>page</strong> title' , _DEV_ )
    );


    _panel::$fields['settings']['general']['upload']['enb_image']             = array( 'type' => 'st--logic-radio' , 'label' => __( 'Enable image submitting' , _DEV_) , 'hint' => __('If enabled users will be able to submit Image posts from front end',_DEV_) );
	_panel::$fields['settings']['general']['upload']['enb_video']             = array( 'type' => 'st--logic-radio' , 'label' => __( 'Enable video submitting' , _DEV_) , 'hint' => __('If enabled users will be able to submit Video posts from front end',_DEV_) );
	_panel::$fields['settings']['general']['upload']['enb_text']              = array( 'type' => 'st--logic-radio' , 'label' => __( 'Enable text submitting' , _DEV_) , 'hint' => __('If enabled users will be able to submit Text posts from front end',_DEV_) );
	_panel::$fields['settings']['general']['upload']['enb_file']              = array( 'type' => 'st--logic-radio' , 'label' => __( 'Enable file submitting' , _DEV_) , 'hint' => __('If enabled users will be able to submit File posts (attachments) from front end',_DEV_) );
	_panel::$fields['settings']['general']['upload']['enb_audio']             = array( 'type' => 'st--logic-radio' , 'label' => __( 'Enable audio submitting' , _DEV_) , 'hint' => __('If enabled users will be able to submit Audio posts from front end',_DEV_) );

	$default_post_status = array('publish' => __('Published',_DEV_), 'pending' => __('Pending',_DEV_) );  
	_panel::$fields['settings']['general']['upload']['default_posts_status']  = array('type' => 'st--select' , 'label' => __( 'Default posts status' , _DEV_ ) ,'hint' => __('This is the status used for posts submited from front end',_DEV_), 'values' => $default_post_status, 'action' => "act.select( '#default_status' , { 'pending' : '.pending_email' } , 'sh' );", 'id' => 'default_status' );

    _panel::$fields['settings']['general']['upload']['pending_email']         = array('type' => 'st--text' , 'label' => __( 'Contact email for pending posts' , _DEV_ ), 'hint' => __('Enter a valid email address if you want to be notified via email when a new post is awaiting moderation',_DEV_)  );
?>