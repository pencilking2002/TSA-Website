<?php

// Pre-2.6 compatibility
if ( ! defined( 'WP_CONTENT_URL' ) )
      define( 'WP_CONTENT_URL', get_option( 'siteurl' ) . '/wp-content' );
if ( ! defined( 'WP_CONTENT_DIR' ) )
      define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
if ( ! defined( 'WP_PLUGIN_URL' ) )
      define( 'WP_PLUGIN_URL', WP_CONTENT_URL. '/themes' );
if ( ! defined( 'WP_PLUGIN_DIR' ) )
      define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR . '/themes' );

if (!class_exists('AudioPlayer')) {
    class AudioPlayer {
		// Name for serialized options saved in database
		var $optionsName = "AudioPlayer_options";
		
		var $version = "2.0.4.1";
		
		var $docURL = "http://wpaudioplayer.com/";
		
		// Internationalisation
		var $textDomain = _DEV_ ;
		var $languageFileLoaded = false;

		// Various path variables
		var $pluginURL = "";
		var $pluginPath = "";
		var $playerURL = "";
		var $audioRoot = "";
		var $audioAbsPath = "";
		var $isCustomAudioRoot = false;
		
		// Options page name
		var $optionsPageName = "audio-player-options";
		
		// Colour scheme keys
		var $colorKeys = array(
			"bg",
			"leftbg",
			"lefticon",
			"voltrack",
			"volslider",
			"rightbg",
			"rightbghover",
			"righticon",
			"righticonhover",
			"text",
			"track",
			"border",
			"loader",
			"tracker",
			"skip"
		);
		
		// Default colour scheme
		var $defaultColorScheme = array(
			"bg" => "E5E5E5",
			"text" => "333333",
			"leftbg" => "CCCCCC",
			"lefticon" => "333333",
			"volslider" => "666666",
			"voltrack" => "FFFFFF",
			"rightbg" => "B4B4B4",
			"rightbghover" => "999999",
			"righticon" => "333333",
			"righticonhover" => "FFFFFF",
			"track" => "FFFFFF",
			"loader" => "009900",
			"border" => "CCCCCC",
			"tracker" => "DDDDDD",
			"skip" => "666666",
			"pagebg" => "FFFFFF",
			"transparentpagebg" => true
		);
		
		// Declare instances global variable
		var $instances = array();
		
		// Used to track what needs to be inserted in the footer
		var $footerCode = "";
		
		// Initialise playerID (each instance gets unique ID)
		var $playerID = 0;
		
		// Flag for dealing with excerpts
		var $inExcerpt = false;
		
		/**
		 * Constructor
		 */
		function AudioPlayer() {
			// Get plugin URL and absolute path
			$template_directory = get_template_directory_uri();
			$template_directory_split = explode('wp-content',$template_directory);

			$this->pluginPath = WP_CONTENT_DIR . $template_directory_split[1]."/lib";

			$this->pluginURL = get_template_directory_uri() . "/lib";

			$this->playerURL = $this->pluginURL . "/assets/player.swf";
			
			// Load options
			$this->options = $this->getOptions();
			
			// Set audio root from options
			$this->setAudioRoot();
			
			// Add action and filter hooks to WordPress
			
			/*add_action("init", array(&$this, "optionsPanelAction"));*/
			
			
			add_filter("plugin_action_links", array(&$this, "addConfigureLink"), 10, 2);
			
			add_action("wp_head", array(&$this, "addHeaderCode"));
			add_action("wp_footer", array(&$this, "addFooterCode"));
			
			add_filter("the_content", array(&$this, "processContent"), 2);
			if (in_array("comments", $this->options["behaviour"])) {
				add_filter("comment_text", array(&$this, "processContent"));
			}
			add_filter("get_the_excerpt", array(&$this, "inExcerpt"), 1);
			add_filter("get_the_excerpt", array(&$this, "outOfExcerpt"), 12);
			add_filter("the_excerpt", array(&$this, "processContent"));
			add_filter("the_excerpt_rss", array(&$this, "processContent"));
			
			add_filter("attachment_fields_to_edit", array(&$this, "insertAudioPlayerButton"), 10, 2);
			add_filter("media_send_to_editor", array(&$this, "sendToEditor"));
			
			if ($this->options["disableEnclosures"]) {
				add_filter("rss_enclosure", array(&$this, "removeEnclosures"));
				add_filter("atom_enclosure", array(&$this, "removeEnclosures"));
			}
		}
		
		/**
		 * Removes all enclosures from feeds
		 * @return empty string
		 */
		function removeEnclosures() {
			return "";
		}
		
		/**
		 * Adds Audio Player options tab to admin menu
		 */
		
		
		/**
		 * Adds a settings link next to Audio Player on the plugins page
		 */
		function addConfigureLink($links, $file) {
			static $this_plugin;
			if (!$this_plugin) {
				$this_plugin = plugin_basename(__FILE__);
			}
			if ($file == $this_plugin) {
				$settings_link = '<a href="options-general.php?page=' . $this->optionsPageName . '">' . __( 'Settings' , _DEV_ ) . '</a>';
				array_unshift($links, $settings_link);
			}
			return $links;
		}
		
		/**
		 * Adds subtle plugin credits to WP footer
		 */
		function addFooterCredits() {
			$plugin_data = get_plugin_data(__FILE__);
			printf('%1$s plugin | Version %2$s<br />', $plugin_data['Name'], $plugin_data['Version']);
		}

		/**
		 * Loads language files according to locale (only does this once per request)
		 */
		function loadLanguageFile() {
			if(!$this->languageFileLoaded) {
				load_plugin_textdomain($this->textDomain, "wp-content/plugins/audio-player/languages", dirname( plugin_basename( __FILE__ ) ) . "/languages");
				$this->languageFileLoaded = true;
			}
		}
		
		/**
		 * Retrieves options from DB. Also sets defaults if options not set
		 * @return array of options
		 */
		function getOptions() {
			// Set default options array to make sure all the necessary options
			// are available when called
			$options = array(
				"audioFolder" => "/audio",
				"playerWidth" => "290",
				"enableAnimation" => true,
				"showRemaining" => false,
				"encodeSource" => true,
				"behaviour" => array("default"),
				"enclosuresAtTop" => false,
				"flashAlternate" => "",
				"rssAlternate" => "nothing",
				"rssCustomAlternate" => '',
				"excerptAlternate" => '',
				"introClip" => "",
				"outroClip" => "",
				"initialVolume" => "60",
				"bufferTime" => "5",
				"noInfo" => false,
				"checkPolicy" => false,
				"rtl" => false,
				"disableEnclosures" => false,

				"colorScheme" => $this->defaultColorScheme
			);
			
			$savedOptions = get_option($this->optionsName);
			if (!empty($savedOptions)) {
				foreach ($savedOptions as $key => $option) {
					$options[$key] = $option;
				}
			}
			
			// 1.x version upgrade
			if (!array_key_exists("version", $options)) {
				if (get_option("audio_player_web_path")) $options["audioFolder"] = get_option("audio_player_web_path");
				if (get_option("audio_player_behaviour")) $options["behaviour"] = explode(",", get_option("audio_player_behaviour"));
				if (get_option("audio_player_rssalternate")) $options["rssAlternate"] = get_option("audio_player_rssalternate");
				if (get_option("audio_player_rsscustomalternate")) $options["rssCustomAlternate"] = get_option("audio_player_rsscustomalternate");
				if (get_option("audio_player_prefixaudio")) $options["introClip"] = get_option("audio_player_prefixaudio");
				if (get_option("audio_player_postfixaudio")) $options["outroClip"] = get_option("audio_player_postfixaudio");

				if (get_option("audio_player_transparentpagebgcolor")) {
					$options["colorScheme"]["bg"] = str_replace("0x", "", get_option("audio_player_bgcolor"));
					$options["colorScheme"]["text"] = str_replace("0x", "", get_option("audio_player_textcolor"));
					$options["colorScheme"]["skip"] = str_replace("0x", "", get_option("audio_player_textcolor"));
					$options["colorScheme"]["leftbg"] = str_replace("0x", "", get_option("audio_player_leftbgcolor"));
					$options["colorScheme"]["lefticon"] = str_replace("0x", "", get_option("audio_player_lefticoncolor"));
					$options["colorScheme"]["volslider"] = str_replace("0x", "", get_option("audio_player_lefticoncolor"));
					$options["colorScheme"]["rightbg"] = str_replace("0x", "", get_option("audio_player_rightbgcolor"));
					$options["colorScheme"]["rightbghover"] = str_replace("0x", "", get_option("audio_player_rightbghovercolor"));
					$options["colorScheme"]["righticon"] = str_replace("0x", "", get_option("audio_player_righticoncolor"));
					$options["colorScheme"]["righticonhover"] = str_replace("0x", "", get_option("audio_player_righticonhovercolor"));
					$options["colorScheme"]["track"] = str_replace("0x", "", get_option("audio_player_trackcolor"));
					$options["colorScheme"]["loader"] = str_replace("0x", "", get_option("audio_player_loadercolor"));
					$options["colorScheme"]["border"] = str_replace("0x", "", get_option("audio_player_bordercolor"));
					$options["colorScheme"]["transparentpagebg"] = (bool) get_option("audio_player_transparentpagebgcolor");
					$options["colorScheme"]["pagebg"] = str_replace("#", "", get_option("audio_player_pagebgcolor"));
				}
			} else if (version_compare($options["version"], $this->version) == -1) {
				// Upgrade code
				$options["colorScheme"]["transparentpagebg"] = (bool) $options["colorScheme"]["transparentpagebg"];
			}
			
			// Record current version in DB
			$options["version"] = $this->version;

			// Update DB if necessary
			update_option($this->optionsName, $options);
			
			return $options;
		}
		
		/**
		 * Writes options to DB
		 */
		function saveOptions() {
			update_option($this->optionsName, $this->options);
		}
		
		/**
		 * Sets the real audio root from the audio folder option
		 */
		function setAudioRoot() {
			$this->audioRoot = $this->options["audioFolder"];

			$this->audioAbsPath = "";
			$this->isCustomAudioRoot = true;
			
			if (!$this->isAbsoluteURL($this->audioRoot)) {
				$sysDelimiter = '/';
				if (strpos(ABSPATH, '\\') !== false) $sysDelimiter = '\\';
				$this->audioAbsPath = preg_replace('/[\\\\\/]+/', $sysDelimiter, ABSPATH . $this->audioRoot);
		
				$this->isCustomAudioRoot = false;
				$this->audioRoot = get_option('siteurl') . $this->audioRoot;
			}
		}
		
		/**
		 * Builds and returns array of options to pass to Flash player
		 * @return array
		 */
		function getPlayerOptions() {
			$playerOptions = array();

			$playerOptions["width"] = $this->options["playerWidth"];
			
			$playerOptions["animation"] = $this->options["enableAnimation"];
			$playerOptions["encode"] = $this->options["encodeSource"];
			$playerOptions["initialvolume"] = $this->options["initialVolume"];
			$playerOptions["remaining"] = $this->options["showRemaining"];
			$playerOptions["noinfo"] = $this->options["noInfo"];
			$playerOptions["buffer"] = $this->options["bufferTime"];
			$playerOptions["checkpolicy"] = $this->options["checkPolicy"];
			$playerOptions["rtl"] = $this->options["rtl"];
			
			return array_merge($playerOptions, $this->options["colorScheme"]);
		}

		// ------------------------------------------------------------------------------
		// Excerpt helper functions
		// Sets a flag so we know we are in an automatically created excerpt
		// ------------------------------------------------------------------------------
		
		/**
		 * Sets a flag when getting an excerpt
		 * @return excerpt text
		 * @param $text String[optional] unchanged excerpt text
		 */
		function inExcerpt($text = '') {
			// Only set the flag when the excerpt is empty and WP creates one automatically)
			if('' == $text) $this->inExcerpt = true;
		
			return $text;
		}
		
		/**
		 * Resets a flag after getting an excerpt
		 * @return excerpt text
		 * @param $text String[optional] unchanged excerpt text
		 */
		function outOfExcerpt($text = '') {
			$this->inExcerpt = false;
		
			return $text;
		}

		/**
		 * Filter function (inserts player instances according to behaviour option)
		 * @return the parsed and formatted content
		 * @param $content String[optional] the content to parse
		 */
		function processContent($content = '') {
			global $comment;
			
			//$this->loadLanguageFile();
			
			// Reset instance array (this is so we don't insert duplicate players)
			$this->instances = array();
		
			// Replace mp3 links (don't do this in feeds and excerpts)
			if ( !is_feed() && !$this->inExcerpt && in_array( "links", $this->options["behaviour"] ) ) {
				$pattern = "/<a ([^=]+=['\"][^\"']+['\"] )*href=['\"](([^\"']+\.mp3))['\"]( [^=]+=['\"][^\"']+['\"])*>([^<]+)<\/a>/i";
				$content = preg_replace_callback( $pattern, array(&$this, "insertPlayer"), $content );
			}
			
			// Replace [audio syntax]
			if( in_array( "default", $this->options["behaviour"] ) ) {
				$pattern = "/(<p>)?\[audio:(([^]]+))\](<\/p>)?/i";
				$content = preg_replace_callback( $pattern, array(&$this, "insertPlayer"), $content );
			}
		
			// Enclosure integration (don't do this for feeds, excerpts and comments)
			if( !is_feed() && !$this->inExcerpt && !$comment && in_array( "enclosure", $this->options["behaviour"] ) ) {
				$enclosure = get_enclosed($post_id);
		
				// Insert intro and outro clips if set
				$introClip = $this->options["introClip"];
				if( $introClip != "" ) $introClip .= ",";
				$outroClip = $this->options["outroClip"];
				if( $outroClip != "" ) $outroClip = "," . $outroClip;
		
				if( count($enclosure) > 0 ) {
					for($i = 0;$i < count($enclosure);$i++) {
						// Make sure the enclosure is an mp3 file and it hasn't been inserted into the post yet
						if( preg_match( "/.*\.mp3$/", $enclosure[$i] ) == 1 && !in_array( $enclosure[$i], $this->instances ) ) {
							if ($this->options["enclosuresAtTop"]) {
								$content = $this->getPlayer( $introClip . $enclosure[$i] . $outroClip, null, $enclosure[$i] ) . "\n\n" . $content;
							} else {
								$content .= "\n\n" . $this->getPlayer( $introClip . $enclosure[$i] . $outroClip, null, $enclosure[$i] );
							}
						}
					}
				}
			}
			
			return $content;
		}
		
		/**
		 * Callback function for preg_replace_callback
		 * @return string to replace matches with
		 * @param $matches Array
		 */
		function insertPlayer($matches) {
			// Split options
			$data = preg_split("/[\|]/", $matches[3]);
			
			$files = array();
			
			// Alternate content for excerpts (don't do this for feeds)
			if($this->inExcerpt && !is_feed()) {
				return ''; /*$this->options["excerptAlternate"];*/
			}
			
			if (!is_feed()) {
				// Insert intro clip if set
				if ( $this->options["introClip"] != "" ) {
					$afile = $this->options["introClip"];
					if (!$this->isAbsoluteURL($afile)) {
						$afile = $this->audioRoot . "/" . $afile;
					}
					array_push( $files, $afile );
				}
			}
			
			$actualFiles = array();
			$actualFile = "";
			
			// Create an array of files to load in player
			foreach ( explode( ",", trim($data[0]) ) as $afile ) {
				$afile = trim($afile);
				
				// Get absolute URLs for relative ones
				if (!$this->isAbsoluteURL($afile)) {
					$afile = $this->audioRoot . "/" . $afile;
				}
				
				array_push( $actualFiles, $afile );
				
				array_push( $files, $afile );
		
				// Add source file to instances already added to the post
				array_push( $this->instances, $afile );
			}
			
			if (count($actualFiles) == 1) {
				$actualFile = $actualFiles[0];
			}
		
			if (!is_feed()) {
				// Insert outro clip if set
				if ( $this->options["outroClip"] != "" ) {
					$afile = $this->options["outroClip"];
					if (!$this->isAbsoluteURL($afile)) {
						$afile = $this->audioRoot . "/" . $afile;
					}
					array_push( $files, $afile );
				}
			}
		
			// Build runtime options array
			$playerOptions = array();
			for ($i = 1; $i < count($data); $i++) {
				$pair = explode("=", $data[$i]);
				$playerOptions[trim($pair[0])] = trim($pair[1]);
			}
			
			// Return player instance code
			return $this->getPlayer( implode( ",", $files ), $playerOptions, $actualFile );
		}
		
		/**
		 * Generic player instance function (returns player widget code to insert)
		 * @return String the html code to insert
		 * @param $source String list of mp3 file urls to load in player
		 * @param $playerOptions Object[optional] options to load in player
		 * @param $actualFile String[optional] url of main single file (empty if multiple files)
		 */
		function getPlayer($source, $playerOptions = array(), $actualFile = "") {
			// Decode HTML entities in file names
			if (function_exists("html_entity_decode")) {
				$source = html_entity_decode($source);
			}

			// Add source to options and encode if necessary
			
			if ($this->options["encodeSource"]) {
				$playerOptions["soundFile"] = $this->encodeSource($source);
			} else {
				$playerOptions["soundFile"] = $source;
			}
			
			if (is_feed()) {
				// We are in a feed so use RSS alternate content option
				switch ( $this->options["rssAlternate"] ) {
					case "download":
						// Get filenames from path and output a link for each file in the sequence
						$files = explode(",", $source);
						$links = "";
						for ($i = 0; $i < count($files); $i++) {
							$fileparts = explode("/", $files[$i]);
							$fileName = $fileparts[count($fileparts)-1];
							$links .= '<a href="' . $files[$i] . '">' . __('Download audio file', $this->textDomain) . ' (' . $fileName . ')</a><br />';
						}
						return $links;
						break;
			
					case "nothing":
						return "";
						break;
			
					case "custom":
						return $this->options["rssCustomAlternate"];
						break;
				}
			} else {
				// Not in a feed so return player widget
				$playerElementID = "audioplayer_" . ++$this->playerID;
				if (strlen($this->options["flashAlternate"]) > 0) {
					$playerCode = str_replace(array("%playerID%", "%downloadURL%"), array($playerElementID, $actualFile), $this->options["flashAlternate"]);
				} else {
					$playerCode = '<p class="audioplayer_container"><span style="display:block;padding:5px;border:1px solid #dddddd;background:#f8f8f8" id="' . $playerElementID . '">' . sprintf(__('Audio clip: Adobe Flash Player (version 9 or above) is required to play this audio clip. Download the latest version <a href="%s" title="Download Adobe Flash Player">here</a>. You also need to have JavaScript enabled in your browser.', $this->textDomain), 'http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash&amp;promoid=BIOW') . '</span></p>';
				}
				
				$this->footerCode .= 'AudioPlayer.embed("' . $playerElementID . '", ' . $this->php2js($playerOptions) . ');';
				$this->footerCode .= "\n";

				return $playerCode;
			}
		}

		
		
		
		/**
		 * Inserts Audio Player button into media library popup
		 * @return the amended form_fields structure
		 * @param $form_fields Object
		 * @param $post Object
		 */
		function insertAudioPlayerButton($form_fields, $post) {
			global $wp_version;
			
			$file = wp_get_attachment_url($post->ID);
			
			// Only add the extra button if the attachment is an mp3 file
			if ($post->post_mime_type == 'audio/mpeg') {
				$form_fields["url"]["html"] .= "<button type='button' class='button urlaudioplayer audio-player-" . $post->ID . "' value='[audio:" . esc_attr($file) . "]' title='[audio:" . esc_attr($file) . "]'>Audio Player</button>";
				
				if (version_compare($wp_version, "2.7", "<")) {
					$form_fields["url"]["html"] .= "<script type='text/javascript'>
					jQuery('button.audio-player-" . $post->ID . "').bind('click', function(){jQuery(this).siblings('input').val(this.value);});
					</script>\n";
				}
			}
			
			return $form_fields;
		}
		
		/**
		 * Format the html inserted when the Audio Player button is used
		 * @param $html String
		 * @return String 
		 */
		function sendToEditor($html) {
			if (preg_match("/<a ([^=]+=['\"][^\"']+['\"] )*href=['\"](\[audio:([^\"']+\.mp3)])['\"]( [^=]+=['\"][^\"']+['\"])*>([^<]*)<\/a>/i", $html, $matches)) {
				$html = $matches[2];
				if (strlen($matches[5]) > 0) {
					$html = preg_replace("/]$/i", "|titles=" . $matches[5] . "]", $html);
				}
			}
			return $html;
		}

		/**
		 * Output necessary stuff to WP head section
		 */
		function addHeaderCode() {
			echo '<script type="text/javascript" src="' . $this->pluginURL . '/assets/audio-player.js?ver=' . $this->version . '"></script>';
			echo "\n";
			echo '<script type="text/javascript">';
			$jsFormattedOptions = $this->php2js($this->getPlayerOptions());
			echo 'AudioPlayer.setup("' . $this->playerURL . '?ver=' . $this->version . '", ' . $jsFormattedOptions . ');';
			echo '</script>';
			echo "\n";
		}
		
		/**
		 * Output necessary stuff to WP footer section (JS calls to embed players)
		 */
		function addFooterCode() {
			if (strlen($this->footerCode) > 0) {
				echo '<script type="text/javascript">';
				echo "\n";
				echo $this->footerCode;
				echo '</script>';
				echo "\n";
				
				// Reset it now
				$this->footerCode = "";
			}
		}
		
		/**
		 * Override media-upload script to handle Audio Player inserts from media library
		 */
		function overrideMediaUpload() {
			echo '<script type="text/javascript" src="' . $this->pluginURL . '/assets/media-upload.js?ver=' . $this->version . '"></script>';
			echo "\n";
		}
		
		/**
		 * Output necessary stuff to WP admin head section
		 */
		function addAdminHeaderCode() {
			global $wp_version;
			echo '<link href="' . $this->pluginURL . '/assets/audio-player-admin.css?ver=' . $this->version . '" rel="stylesheet" type="text/css" />';
			echo "\n";
			echo '<link href="' . $this->pluginURL . '/assets/cpicker/colorpicker.css?ver=' . $this->version . '" rel="stylesheet" type="text/css" />';
			echo "\n";
			
			// Include jquery library if we are not running WP 2.5 or above
			if (version_compare($wp_version, "2.5", "<")) {
				echo '<script type="text/javascript" src="' . $this->pluginURL . '/assets/lib/jquery.js?ver=' . $this->version . '"></script>';
				echo "\n";
			}

			echo '<script type="text/javascript" src="' . $this->pluginURL . '/assets/cpicker/colorpicker.js?ver=' . $this->version . '"></script>';
			echo "\n";
			echo '<script type="text/javascript" src="' . $this->pluginURL . '/assets/audio-player-admin.js?ver=' . $this->version . '"></script>';
			echo "\n";
			echo '<script type="text/javascript" src="' . $this->pluginURL . '/assets/audio-player.js?ver=' . $this->version . '"></script>';
			echo "\n";
			echo '<script type="text/javascript">';
			echo "\n";
			echo 'var ap_ajaxRootURL = "' . $this->pluginURL . '/php/";';
			echo "\n";
			echo 'AudioPlayer.setup("' . $this->playerURL . '?ver=' . $this->version . '", ' . $this->php2js($this->getPlayerOptions()) . ');';
			echo "\n";
			echo '</script>';
			echo "\n";
		}
		
		/**
		 * Verifies that the given audio folder exists on the server (Ajax call)
		 */
		function checkAudioFolder() {
			$audioRoot = $_POST["audioFolder"];

			$sysDelimiter = '/';
			if (strpos(ABSPATH, '\\') !== false) $sysDelimiter = '\\';
			$audioAbsPath = preg_replace('/[\\\\\/]+/', $sysDelimiter, ABSPATH . $audioRoot);

			if (!file_exists($audioAbsPath)) {
				echo $audioAbsPath;
			} else {
				echo "ok";
			}
		}

		/**
		 * Parses theme style sheet
		 * @return array of colors from current theme
		 */
		function getThemeColors() {
			$current_theme_data = get_theme(get_current_theme());
		
			$theme_css = implode('', file( get_theme_root() . "/" . $current_theme_data["Stylesheet"] . "/style.css"));
		
			preg_match_all('/:[^:,;\{\}].*?#([abcdef1234567890]{3,6})/i', $theme_css, $matches);
		
			return array_unique($matches[1]);
		}

		/**
		 * Formats a php associative array into a javascript object
		 * @return formatted string
		 * @param $object Object containing the options to format
		 */
		function php2js($object) {
			$js_options = '{';
			$separator = "";
			$real_separator = ",";
			foreach($object as $key=>$value) {
				// Format booleans
				if (is_bool($value)) $value = $value?"yes":"no";
				else if (in_array($key, array("soundFile", "titles", "artists"))) {
					if (in_array($key, array("titles", "artists"))) {
						// Decode HTML entities in titles and artists
						if (function_exists("html_entity_decode")) {
							$value = html_entity_decode($value);
						}
					}

					$value = rawurlencode($value);
				}
				$js_options .= $separator . $key . ':"' . $value .'"';
				$separator = $real_separator;
			}
			$js_options .= "}";
			
			return $js_options;
		}

		/**
		 * @return true if $path is absolute
		 * @param $path Object
		 */
		function isAbsoluteURL($path) {
			if (strpos($path, "http://") === 0) {
				return true;
			}
			if (strpos($path, "https://") === 0) {
				return true;
			}
			if (strpos($path, "ftp://") === 0) {
				return true;
			}
			return false;
		}
		
		/**
		 * Encodes the given string
		 * @return the encoded string
		 * @param $string String the string to encode
		 */
		function encodeSource($string) {
			$source = utf8_decode($string);
			$ntexto = "";
			$codekey = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789_-";
			for ($i = 0; $i < strlen($string); $i++) {
				$ntexto .= substr("0000".base_convert(ord($string{$i}), 10, 2), -8);
			}
			$ntexto .= substr("00000", 0, 6-strlen($ntexto)%6);
			$string = "";
			for ($i = 0; $i < strlen($ntexto)-1; $i = $i + 6) {
				$string .= $codekey{intval(substr($ntexto, $i, 6), 2)};
			}
			
			return $string;
		}
	}
}

// Instantiate the class
if (class_exists('AudioPlayer')) {
	global $AudioPlayer;
	if (!isset($AudioPlayer)) {
		if (version_compare(PHP_VERSION, '5.0.0', '<')) {
			$AudioPlayer = &new AudioPlayer();
		} else {
			$AudioPlayer = new AudioPlayer();
		}
	}
}

/**
 * Experimental "tag" function for inserting players anywhere (yuk)
 * @return 
 * @param $source Object
 */
function insert_audio_player($source) {
	global $AudioPlayer;
	echo $AudioPlayer->processContent($source);
}

?>