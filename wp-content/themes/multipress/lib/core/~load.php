<?php
	class includes{
        static $fonts = array(
			'Arvo&v1' => 'http://fonts.googleapis.com/css?family=Arvo&v1',
			'Cousine&v1' => 'http://fonts.googleapis.com/css?family=Cousine&v1',
			'Quattrocento&v1' => 'http://fonts.googleapis.com/css?family=Quattrocento&v1',
			'Wire+One&v1' => 'http://fonts.googleapis.com/css?family=Wire+One&v1',
			'Forum&v1' => 'http://fonts.googleapis.com/css?family=Forum&v1',
			'Patrick+Hand&v1' => 'http://fonts.googleapis.com/css?family=Patrick+Hand&v1',
			'Love+Ya+Like+A+Sister&v1' => 'http://fonts.googleapis.com/css?family=Love+Ya+Like+A+Sister&v1',
			'Loved+by+the+King&v1' => 'http://fonts.googleapis.com/css?family=Loved+by+the+King&v1',
			'Bowlby+One+SC&v1' => 'http://fonts.googleapis.com/css?family=Bowlby+One+SC&v1',
			'Stardos+Stencil&v1' => 'http://fonts.googleapis.com/css?family=Stardos+Stencil&v1',
			'Varela&v1' => 'http://fonts.googleapis.com/css?family=Varela&v1',
			'Lobster+Two&v1' => 'http://fonts.googleapis.com/css?family=Lobster+Two&v1',
			'Hammersmith+One&v1' => 'http://fonts.googleapis.com/css?family=Hammersmith+One&v1',
			'Redressed&v1' => 'http://fonts.googleapis.com/css?family=Redressed&v1',
			'Lekton&v1' => 'http://fonts.googleapis.com/css?family=Lekton&v1',
			'Gravitas+One&v1' => 'http://fonts.googleapis.com/css?family=Gravitas+One&v1',
			'Asset&v1' => 'http://fonts.googleapis.com/css?family=Asset&v1',
			'Goblin+One&v1' => 'http://fonts.googleapis.com/css?family=Goblin+One&v1',
			'Aclonica&v1' => 'http://fonts.googleapis.com/css?family=Aclonica&v1',
			'Didact+Gothic&v1' => 'http://fonts.googleapis.com/css?family=Didact+Gothic&v1',
			'Maiden+Orange&v1' => 'http://fonts.googleapis.com/css?family=Maiden+Orange&v1',
			'Tenor+Sans&v1' => 'http://fonts.googleapis.com/css?family=Tenor+Sans&v1',
			'Jura&v1' => 'http://fonts.googleapis.com/css?family=Jura&v1',
			'Francois+One&v1' => 'http://fonts.googleapis.com/css?family=Francois+One&v1',
			'Paytone+One&v1' => 'http://fonts.googleapis.com/css?family=Paytone+One&v1',
			'Zeyada&v1' => 'http://fonts.googleapis.com/css?family=Zeyada&v1',
			'Monofett&v1' => 'http://fonts.googleapis.com/css?family=Monofett&v1',
			'Caudex&v1' => 'http://fonts.googleapis.com/css?family=Caudex&v1',
            /* 18.07.2011 */
			'Oswald&v1' => 'http://fonts.googleapis.com/css?family=Oswald&v1',
            'Sunshiney&v1' => 'http://fonts.googleapis.com/css?family=Sunshiney&v1',
            'Istok+Web&v1' => 'http://fonts.googleapis.com/css?family=Istok+Web&v1',
            'Bowlby+One&v1' => 'http://fonts.googleapis.com/css?family=Bowlby+One&v1',
            'Modern+Antiqua&v1' => 'http://fonts.googleapis.com/css?family=Modern+Antiqua&v1',
            'Give+You+Glory&v1' => 'http://fonts.googleapis.com/css?family=Give+You+Glory&v1',
            'Yeseva+One&v1' => 'http://fonts.googleapis.com/css?family=Yeseva+One&v1',
		);
		function load_css( ){
            /* admin panel ( options , meta-box ) */
            wp_register_style('settings', get_template_directory_uri() . '/lib/css/settings.css?v=0.2');
            
            wp_register_style('generic-fields', get_template_directory_uri() . '/lib/css/generic-fields.css?v=0.1');
            wp_register_style('meta-box', get_template_directory_uri() . '/lib/css/meta.box.css');

            wp_enqueue_style( 'ui-lightness');
            wp_enqueue_style( 'settings' );
            wp_enqueue_style( 'generic-fields' );
            wp_enqueue_style( 'meta-box' );
            wp_enqueue_style('thickbox');

            /* google fonts */
            if( isset( $_GET['page'] ) && substr( $_GET['page'] , 0 , 11 ) == 'cosmothemes' ){
                foreach( self::$fonts as $title => $url ){
					wp_register_style( str_replace( '+' , '-' ,  rtrim( $title , '&v1') ) , $url );
					wp_enqueue_style( str_replace( '+' , '-' ,  rtrim( $title , '&v1') ) );
				}
            }

            /* shortcodes */
            wp_register_style('tabs', get_template_directory_uri() . '/lib/css/shcode/tabs.css' );
            wp_register_style('shcode_forms', get_template_directory_uri() . '/lib/css/shcode/style.css' );
            wp_register_style('columns', get_template_directory_uri() . '/lib/css/shcode/columns.css' );
            /* used in frontend / backend */
            wp_register_style('shortcode', get_template_directory_uri() . '/lib/css/shortcode.css' );

            wp_enqueue_style( 'tabs');
            wp_enqueue_style( 'shcode_forms');
            wp_enqueue_style( 'columns');
            wp_enqueue_style( 'shortcode');
		}

        function load_js( ){

            if( is_admin() ){
                wp_register_script( 'extra' ,  get_template_directory_uri().'/lib/js/extra.js' );
                wp_register_script( 'actions', get_template_directory_uri().'/lib/js/actions.js' , array( 'jquery' , 'media-upload' , 'thickbox' ) );
				wp_register_script( 'meta' ,  get_template_directory_uri().'/lib/js/meta.js' );

                /* shorcodes */
                wp_register_script( 'button', get_template_directory_uri().'/lib/js/shcode/button.js');
                wp_register_script( 'tabs', get_template_directory_uri().'/lib/js/shcode/tabs.js');
				wp_register_script( 'columns_generator', get_template_directory_uri().'/lib/js/shcode/columns.js');

				wp_register_script( 'toggle', get_template_directory_uri().'/lib/js/shcode/toggle.js');

                wp_register_script( 'tabs_shcode', get_template_directory_uri().'/lib/js/shcode/tabs_shcode.js');
                wp_register_script( 'box', get_template_directory_uri().'/lib/js/shcode/box.js');
                wp_register_script( 'list', get_template_directory_uri().'/lib/js/shcode/list.js');
                wp_register_script( 'devider', get_template_directory_uri().'/lib/js/shcode/devider.js');
                wp_register_script( 'quote', get_template_directory_uri().'/lib/js/shcode/quote.js');
                wp_register_script( 'google-map' ,  get_template_directory_uri().'/js/google.map.js' );

                wp_enqueue_script( 'jquery' );
                wp_enqueue_script( 'google-map' );

				
                wp_enqueue_script('media-upload');
                wp_enqueue_script('thickbox');
                
                wp_enqueue_script( 'extra' );
                wp_enqueue_script( 'actions' );
				wp_enqueue_script( 'meta' );

                /* shortcodes */
                wp_enqueue_script( 'button' );
                wp_enqueue_script( 'tabs' );
				wp_enqueue_script( 'columns_generator' );
				wp_enqueue_script( 'toggle' );

                wp_enqueue_script( 'tabs_shcode' );
                wp_enqueue_script( 'box' );
                wp_enqueue_script( 'list' );
                wp_enqueue_script( 'devider' );
                wp_enqueue_script( 'quote' );
            }
		}

        function fonts(){
			$result = array();
			foreach( self::$fonts as $title => $url ){
				$result[ $title ] = str_replace( '+' , ' ' , rtrim( $title , '&v1' ) );
			}

			return $result;
		}
	} 
?>