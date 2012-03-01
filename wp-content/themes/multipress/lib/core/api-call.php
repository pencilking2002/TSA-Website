<?php
class _api_call{

	function getCosmoNews(){
		$key = 'cosmo_news_alert';

		$last_news = array();  
		// Let's see if we have a cached version
		$saved_cosmo_news_alert = get_transient($key);
		if ($saved_cosmo_news_alert !== false ){
			$last_news = $saved_cosmo_news_alert;
		}else{
			// If there's no cached version we ask is from Cosmothemes
			//$response = wp_remote_get("http://cosmothemes.com/api/news.php?key=D9a0ee79GEHdD");
			$response = wp_remote_get("http://dev.cosmothemes.com/tst/api/news.php?key=D9a0ee79GEHdD");
			
			if (is_wp_error($response))
			{
				// In case Cosmothemes is down we return the last successful info
				$saved_option = get_option($key);
				//var_dump($saved_option);
				if(is_array($saved_option) && sizeof($saved_option)){
					$last_news = get_option($key);
				}
			}
			else
			{
				// If everything's okay, parse the body and json_decode it

				$json = json_decode(wp_remote_retrieve_body($response));

				if(sizeof($json)){
					$responce_size = 0;
					foreach($json as $news ){
						$responce_size ++;
					}
					$counter = 0;	
					foreach($json as $index => $news ){
						$counter ++;
						if(  $responce_size == $counter  ){
							$last_news[$index] = $news;
						}
					}
				}	
				
				if(sizeof($last_news) ){	
					
						// Store the result in a transient, expires after 1 day
						// Also store it as the last successful using update_option
						set_transient($key, $last_news, 60*60*24); //1 day cache
						
						update_option($key, $last_news);
					
				
				}

			}

			
		}

		if(sizeof($last_news) ){
			
			foreach($last_news as $ind => $msg){
				$msg_key = $ind;
				$message = $msg;
			}	
	
			if(get_option($msg_key.'_closed') == ''){  

				$fn = "closeCosmoMsg(\'".trim($msg_key)."\');";	  
				$alert_msg1 =  '<div id="cosmo_news" >'.$message;
				$alert_msg1 .= '<span class="close_msg" onclick="'.$fn.'" >'.__('Close',_DEV_).'</span>';   
				$alert_msg1 .= '</div>'; 
				
				/*insert the notification message in wphead */
				$result = '<script type="text/javascript">
							  jQuery(document).ready(function() {    
										jQuery("#wphead").append(\''.$alert_msg1.'\');	
								
							});	
						  </script>';  
			}else{
				$result ='';	  
			}
			
		}else{
			$result ='';	
		}	  

		return $result;
	}
	
	function getLastThemeVersion(){
		$key = ZIP_NAME . '__theme_version';

		// Let's see if we have a cached version
		$saved_theme_version = get_transient($key);
		if ($saved_theme_version !== false){
			return $saved_theme_version;
		}else{
			// If there's no cached version we ask Twitter
			$response = wp_remote_get("http://cosmothemes.com/api/versions.php?key=D9a0ee79GEHdD&tn=".ZIP_NAME);
			if (is_wp_error($response))
			{
				// In case Twitter is down we return the last successful count
				return get_option($key);
			}
			else
			{
				// If everything's okay, parse the body and json_decode it
				$json = json_decode(wp_remote_retrieve_body($response));
				
				if(isset($json->version)){	
					$available_theme_version = $json->version;
					
					if(is_numeric($available_theme_version)){   
						// Store the result in a transient, expires after 1 day
						// Also store it as the last successful using update_option
						set_transient($key, $available_theme_version, 60*60*24); /*1 day cache*/
						
						update_option($key, $available_theme_version);
					}
					return $available_theme_version;
				}else{
					return;
				}

			}
		}

	}

	/*if there is available a newer version then we will return some js code that will be appended to the head*/  
	function compareVersions(){
		$last_version = self::getLastThemeVersion();
		
		$theme_data = get_theme_data(get_stylesheet_uri());
		$this_theme_version = $theme_data['Version'];
	  
		if(is_numeric($last_version) && is_numeric($this_theme_version) && $this_theme_version < $last_version){
			$alert_msg =  '<div id="cosmo_new_version">'.$theme_data["Name"].' '.__("version", _DEV_ ).' '.$last_version.' '.__("is available, please update now.", _DEV_ ).'</div>'; 
			
			/*insert the notification message in wphead */
			$result = '<script type="text/javascript">
						  jQuery(document).ready(function() {    
									jQuery("#wphead").append(\''.$alert_msg.'\');	
							
						});	
					  </script>';  
			return $result;
		}
	}
}
?>