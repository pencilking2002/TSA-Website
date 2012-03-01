<?php
    class _load{
        static $fonts = array(
			'Arial' =>'',
			'Helvetica'=>'',
			'Arvo' => 'http://fonts.googleapis.com/css?family=Arvo',
			'Cousine' => 'http://fonts.googleapis.com/css?family=Cousine',
			'Quattrocento' => 'http://fonts.googleapis.com/css?family=Quattrocento',
			'Wire+One' => 'http://fonts.googleapis.com/css?family=Wire+One',
			'Forum' => 'http://fonts.googleapis.com/css?family=Forum',
			'Patrick+Hand' => 'http://fonts.googleapis.com/css?family=Patrick+Hand',
			'Love+Ya+Like+A+Sister' => 'http://fonts.googleapis.com/css?family=Love+Ya+Like+A+Sister',
			'Loved+by+the+King' => 'http://fonts.googleapis.com/css?family=Loved+by+the+King',
			'Bowlby+One+SC' => 'http://fonts.googleapis.com/css?family=Bowlby+One+SC',
			'Stardos+Stencil' => 'http://fonts.googleapis.com/css?family=Stardos+Stencil',
			'Varela' => 'http://fonts.googleapis.com/css?family=Varela',
			'Lobster+Two' => 'http://fonts.googleapis.com/css?family=Lobster+Two',
			'Hammersmith+One' => 'http://fonts.googleapis.com/css?family=Hammersmith+One',
			'Redressed' => 'http://fonts.googleapis.com/css?family=Redressed',
			'Lekton' => 'http://fonts.googleapis.com/css?family=Lekton',
			'Gravitas+One' => 'http://fonts.googleapis.com/css?family=Gravitas+One',
			'Asset' => 'http://fonts.googleapis.com/css?family=Asset',
			'Goblin+One' => 'http://fonts.googleapis.com/css?family=Goblin+One',
			'Aclonica' => 'http://fonts.googleapis.com/css?family=Aclonica',
			'Didact+Gothic' => 'http://fonts.googleapis.com/css?family=Didact+Gothic',
			'Maiden+Orange' => 'http://fonts.googleapis.com/css?family=Maiden+Orange',
			'Tenor+Sans' => 'http://fonts.googleapis.com/css?family=Tenor+Sans',
			'Jura' => 'http://fonts.googleapis.com/css?family=Jura',
			'Francois+One' => 'http://fonts.googleapis.com/css?family=Francois+One',
			'Paytone+One' => 'http://fonts.googleapis.com/css?family=Paytone+One',
			'Zeyada' => 'http://fonts.googleapis.com/css?family=Zeyada',
			'Monofett' => 'http://fonts.googleapis.com/css?family=Monofett',
			'Caudex' => 'http://fonts.googleapis.com/css?family=Caudex',
            /* 18.07.2011 */
			'Oswald' => 'http://fonts.googleapis.com/css?family=Oswald',
            'Sunshiney' => 'http://fonts.googleapis.com/css?family=Sunshiney',
            'Istok+Web' => 'http://fonts.googleapis.com/css?family=Istok+Web',
            'Bowlby+One' => 'http://fonts.googleapis.com/css?family=Bowlby+One',
            'Modern+Antiqua' => 'http://fonts.googleapis.com/css?family=Modern+Antiqua',
            'Give+You+Glory' => 'http://fonts.googleapis.com/css?family=Give+You+Glory',
            'Yeseva+One' => 'http://fonts.googleapis.com/css?family=Yeseva+One',
            'Carme' => 'http://fonts.googleapis.com/css?family=Carme',
            'Gentium+Basic' => 'http://fonts.googleapis.com/css?family=Gentium+Basic',
            'Black+Ops+One' => 'http://fonts.googleapis.com/css?family=Black+Ops+One',
            'Gloria+Hallelujah' => 'http://fonts.googleapis.com/css?family=Gloria+Hallelujah',
            'Delius' => 'http://fonts.googleapis.com/css?family=Delius',
            'Aubrey' => 'http://fonts.googleapis.com/css?family=Aubrey',
            'Ovo' => 'http://fonts.googleapis.com/css?family=Ovo',
            'Leckerli+One' => 'http://fonts.googleapis.com/css?family=Leckerli+One',
            'Modern+Antiqua' => 'http://fonts.googleapis.com/css?family=Modern+Antiqua',
            'Tienne' => 'http://fonts.googleapis.com/css?family=Tienne',
            'Istok+Web' => 'http://fonts.googleapis.com/css?family=Istok+Web',
            'Varela+Round' => 'http://fonts.googleapis.com/css?family=Varela+Round',
            'Annie+Use+Your+Telescope' => 'http://fonts.googleapis.com/css?family=Annie+Use+Your+Telescope',
            'Nothing+You+Could+Do' => 'http://fonts.googleapis.com/css?family=Nothing+You+Could+Do',
            'Swanky+and+Moo+Moo' => 'http://fonts.googleapis.com/css?family=Swanky+and+Moo+Moo',
            'Lora' => 'http://fonts.googleapis.com/css?family=Lora',
            'Give+You+Glory' => 'http://fonts.googleapis.com/css?family=Give+You+Glory',
            'Yeseva+One' => 'http://fonts.googleapis.com/css?family=Yeseva+One',
            'Podkova' => 'http://fonts.googleapis.com/css?family=Podkova',
            'Limelight' => 'http://fonts.googleapis.com/css?family=Limelight',
            'Maven+Pro' => 'http://fonts.googleapis.com/css?family=Maven+Pro',
            'Architects+Daughter' => 'http://fonts.googleapis.com/css?family=Architects+Daughter',
            'Open+Sans+Condensed' => 'http://fonts.googleapis.com/css?family=Open+Sans+Condensed',
            'Mako' => 'http://fonts.googleapis.com/css?family=Mako',
            'Muli' => 'http://fonts.googleapis.com/css?family=Muli',
            'Nova+Square' => 'http://fonts.googleapis.com/css?family=Nova+Square',
            'Ruslan+Display' => 'http://fonts.googleapis.com/css?family=Ruslan+Display',
            'Anton' => 'http://fonts.googleapis.com/css?family=Anton',
            'Miltonian' => 'http://fonts.googleapis.com/css?family=Miltonian',
            'Kreon' => 'http://fonts.googleapis.com/css?family=Kreon',
            'Nixie+One' => 'http://fonts.googleapis.com/css?family=Nixie+One',
            'Coda+Caption' => 'http://fonts.googleapis.com/css?family=Coda+Caption',
            'Play' => 'http://fonts.googleapis.com/css?family=Play',
            'Expletus+Sans' => 'http://fonts.googleapis.com/css?family=Expletus+Sans',
            'IM+Fell+French+Canon+SC' => 'http://fonts.googleapis.com/css?family=IM+Fell+French+Canon+SC',
            'Paytone+One' => 'http://fonts.googleapis.com/css?family=Paytone+One',
            'Brawler' => 'http://fonts.googleapis.com/css?family=Brawler',
            'Irish+Grover' => 'http://fonts.googleapis.com/css?family=Irish+Grover',
            'PT+Serif+Caption' => 'http://fonts.googleapis.com/css?family=PT+Serif+Caption',
            'PT+Serif' => 'http://fonts.googleapis.com/css?family=PT+Serif',
            'PT+Sans+Narrow' => 'http://fonts.googleapis.com/css?family=PT+Sans+Narrow',
            'Wallpoet' => 'http://fonts.googleapis.com/css?family=Wallpoet',
            'Cedarville+Cursive' => 'http://fonts.googleapis.com/css?family=Cedarville+Cursive',
            'IM+Fell+English+SC' => 'http://fonts.googleapis.com/css?family=IM+Fell+English+SC',
            'Lato' => 'http://fonts.googleapis.com/css?family=Lato',
            'Quattrocento+Sans' => 'http://fonts.googleapis.com/css?family=Quattrocento+Sans',
            'Sniglet' => 'http://fonts.googleapis.com/css?family=Sniglet',
            'Open+Sans' => 'http://fonts.googleapis.com/css?family=Open+Sans',
            'Geo' => 'http://fonts.googleapis.com/css?family=Geo',
            'Anonymous+Pro' => 'http://fonts.googleapis.com/css?family=Anonymous+Pro',
            'News+Cycle' => 'http://fonts.googleapis.com/css?family=News+Cycle',
            'Just+Another+Hand' => 'http://fonts.googleapis.com/css?family=Just+Another+Hand',
            'EB+Garamond' => 'http://fonts.googleapis.com/css?family=EB+Garamond',
            'Dancing+Script' => 'http://fonts.googleapis.com/css?family=Dancing+Script',
            'Shanti' => 'http://fonts.googleapis.com/css?family=Shanti',
            'Metrophobic' => 'http://fonts.googleapis.com/css?family=Metrophobic',
            'Syncopate' => 'http://fonts.googleapis.com/css?family=Syncopate',
            'Crimson+Text' => 'http://fonts.googleapis.com/css?family=Crimson+Text',
            'Orbitron' => 'http://fonts.googleapis.com/css?family=Orbitron',
            'Cuprum' => 'http://fonts.googleapis.com/css?family=Cuprum',
            'Cardo' => 'http://fonts.googleapis.com/css?family=Cardo',
            'Nobile' => 'http://fonts.googleapis.com/css?family=Nobile',
            'Inconsolata' => 'http://fonts.googleapis.com/css?family=Inconsolata',
            'IM+Fell+Double+Pica+SC' => 'http://fonts.googleapis.com/css?family=IM+Fell+Double+Pica+SC',
            'Neuton' => 'http://fonts.googleapis.com/css?family=Neuton',
            'Vollkorn' => 'http://fonts.googleapis.com/css?family=Vollkorn',
            'Indie+Flower' => 'http://fonts.googleapis.com/css?family=Indie+Flower',
            'Special+Elite' => 'http://fonts.googleapis.com/css?family=Special+Elite',
            'Fontdiner+Swanky' => 'http://fonts.googleapis.com/css?family=Fontdiner+Swanky',
            'Crushed' => 'http://fonts.googleapis.com/css?family=Crushed',
            'Bentham' => 'http://fonts.googleapis.com/css?family=Bentham',
            'IM+Fell+Great+Primer+SC' => 'http://fonts.googleapis.com/css?family=IM+Fell+Great+Primer+SC',
            'Coming+Soon' => 'http://fonts.googleapis.com/css?family=Coming+Soon',
            'Merriweather' => 'http://fonts.googleapis.com/css?family=Merriweather',
            'Cabin+Sketch' => 'http://fonts.googleapis.com/css?family=Cabin+Sketch',
            'Maiden+Orange' => 'http://fonts.googleapis.com/css?family=Maiden+Orange',
            'Tangerine' => 'http://fonts.googleapis.com/css?family=Tangerine',
            'UnifrakturMaguntia' => 'http://fonts.googleapis.com/css?family=UnifrakturMaguntia',
            'Homemade+Apple' => 'http://fonts.googleapis.com/css?family=Homemade+Apple',
            'Molengo' => 'http://fonts.googleapis.com/css?family=Molengo',
            'Walter+Turncoat' => 'http://fonts.googleapis.com/css?family=Walter+Turncoat',
            'Yanone+Kaffeesatz' => 'http://fonts.googleapis.com/css?family=Yanone+Kaffeesatz',
            'Raleway' => 'http://fonts.googleapis.com/css?family=Raleway',
            'Bevan' => 'http://fonts.googleapis.com/css?family=Bevan',
            'PT+Sans+Caption' => 'http://fonts.googleapis.com/css?family=PT+Sans+Caption',
            'Josefin+Slab' => 'http://fonts.googleapis.com/css?family=Josefin+Slab',
            'Rock+Salt' => 'http://fonts.googleapis.com/css?family=Rock+Salt',
            'Kranky' => 'http://fonts.googleapis.com/css?family=Kranky',
            'Covered+By+Your+Grace' => 'http://fonts.googleapis.com/css?family=Covered+By+Your+Grace',
		);
        
        static function css(){
            
            /* shortcodes */
            wp_register_style( 'tabs' , get_template_directory_uri() . '/lib/core/css/shcode/tabs.css' );
            wp_register_style( 'shcode_forms' , get_template_directory_uri() . '/lib/core/css/shcode/style.css' );
            wp_register_style( 'columns' , get_template_directory_uri() . '/lib/core/css/shcode/columns.css' );
            /* used in frontend / backend */
            wp_register_style( 'shortcode' , get_template_directory_uri() . '/lib/core/css/shortcode.css' );
            
            wp_enqueue_style( 'tabs' );
            wp_enqueue_style( 'shcode_forms' );
            wp_enqueue_style( 'columns' );
            
            if( is_admin( ) ){
                wp_enqueue_style( 'farbtastic' );
                wp_register_style( 'all' ,  get_template_directory_uri().'/lib/core/css/all.css.php' );
                wp_enqueue_style( 'all' );
                wp_enqueue_style( 'shortcode' );
                
                wp_enqueue_style( 'ui-lightness');
                wp_enqueue_style('thickbox');
            }
            
            
        }
        
        static function js(){
            if( is_admin() ){
                
                wp_register_script( 'button' , get_template_directory_uri().'/lib/core/js/shcode/button.js');
                wp_register_script( 'tabs' , get_template_directory_uri().'/lib/core/js/shcode/tabs.js');
				wp_register_script( 'columns_generator' , get_template_directory_uri().'/lib/core/js/shcode/columns.js');

				wp_register_script( 'toggle' , get_template_directory_uri().'/lib/core/js/shcode/toggle.js');

                wp_register_script( 'tabs_shcode' , get_template_directory_uri().'/lib/core/js/shcode/tabs_shcode.js');
                wp_register_script( 'box' , get_template_directory_uri().'/lib/core/js/shcode/box.js');
                wp_register_script( 'list' , get_template_directory_uri().'/lib/core/js/shcode/list.js');
                wp_register_script( 'devider' , get_template_directory_uri().'/lib/core/js/shcode/devider.js');
                wp_register_script( 'quote' , get_template_directory_uri().'/lib/core/js/shcode/quote.js');
                wp_register_script( 'price' , get_template_directory_uri().'/lib/core/js/shcode/price.js');
				wp_register_script( 'table' ,  get_template_directory_uri().'/lib/core/js/shcode/table.js' );
                
                wp_register_script( 'map' , 'http://maps.googleapis.com/maps/api/js?sensor=true' );
                wp_register_script( 'all' ,  get_template_directory_uri().'/lib/core/js/all.js.php' , array( 'jquery' , 'media-upload' , 'thickbox' ) ) ;
                
                wp_enqueue_script( 'jquery' );
                wp_enqueue_script( 'media-upload' );
                wp_enqueue_script( 'thickbox' );
                
                wp_enqueue_script( 'button' );
                wp_enqueue_script( 'tabs' );
                wp_enqueue_script( 'columns_generator' );
                wp_enqueue_script( 'toggle' );
                wp_enqueue_script( 'tabs_shcode' );
                wp_enqueue_script( 'box' );
                wp_enqueue_script( 'list' );
                wp_enqueue_script( 'devider' );
                wp_enqueue_script( 'quote' );
                wp_enqueue_script( 'price' );
                wp_enqueue_script( 'table' );
                
                wp_enqueue_script( 'map' );
                wp_enqueue_script( 'all' );
            }
        }
        
        function fonts( $family = '' ){
			$result = array( '' => __( '- Select google font -' , _DEV_ ) );
			foreach( self::$fonts as $title => $url ){
				$result[ $title ] = str_replace( '+' , ' ' , $title );
			}
            
            /* order ASC by key */
			ksort( $result );
            if( strlen( $family ) ){
                if( isset( $result[ $family ] ) ){
                    return $result[ $family ];
                }
            }else{
                return $result;
            }
		}
    }
?>