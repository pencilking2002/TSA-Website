<iframe id="registration_iframe" name="registration_iframe" class="hidden"></iframe>
<?php if(_core::method("_settings","logic","settings","general","theme","enb-login")){
			$register_link=get_permalink();
			$recover_link=$register_link;
			if(strpos($register_link,"?")){
				$register_link.="&action=register";
				$recover_link.="&action=recover";
			}else{
				$register_link.="?action=register";
				$recover_link.="?action=recover";
			}

			if(isset($_GET['action']) && $_GET['action']=='register'){?>
			<div class="register">
				
				<form action="<?php echo site_url('wp-login.php?action=register', 'login_post') ?>" method="post" class="form txt" id="register_form" target="registration_iframe">
					<fieldset>
						<p>
							<label for="name"><?php echo __( 'Your name' , _DEV_ );?></label>
							<input type="text" id="user_login" name="user_login" value="">
						</p>
						<p>
							<label for="email"><?php echo __( 'Your email' , _DEV_ );?></label>
							<input type="text" id="user_email" name="user_email" value="">
						</p>
						<p>
							<label for="email-2"><?php echo __( 'Repeat your email' , _DEV_ );?></label>
							<input type="text" id="check_email" name="email-2" value=""><br>
							<span class="error" style="border:none" id="registration_error"></span>
						</p>
						<p>
							<span id="registration_info"><?php echo __( "Your password will be e-mailed to you." , _DEV_ ); ?></span>
						</p>
						<p class="button fl">
							<?php do_action('register_form'); ?> 
							<input type="submit" value="Register" class="button" id="register_button">
						</p>
						<input type="hidden" name="testcookie" value="1">
					</fieldset>
				</form>
			</div>
			<div class="login-box">
				<p class="box">
					<span><?php echo __( 'Already a member?' , _DEV_ ); ?> <a href="<?php the_permalink();?>" id="login" class="try"><?php echo __( 'Log in here' , _DEV_ ); ?></a></span>
				</p>
			</div>
		<?php }elseif( isset( $_GET['action'] ) && $_GET['action'] == "recover" ){ ?>
			<div class="login">
				<form name="lostpasswordform" id="lostpasswordform" action="<?php echo get_template_directory_uri();?>/wp-login.php?action=lostpassword" method="post" class="form txt" target="registration_iframe">
					<fieldset>
						<p>
							<label for="name"><?php echo __( 'Your name' , _DEV_ );?></label>
							<input type="text" id="user_login" name="user_login" value="">
						</p>
						<p>
							<span class="error" style="border:none" id="registration_error"></span>
						</p>
						<p class="button fl">
							<input type="submit" value="Recover" class="button">
						</p>
					</fieldset>
				</form>
			</div>
			<div class="login-box">
				<p class="box">
					<span><?php echo __( 'Already a member?' , _DEV_ ); ?> <a href="<?php the_permalink();?>" id="login" class="try"><?php echo __( 'Log in here' , _DEV_ ); ?></a></span>
				</p>
			</div>
		<?php }else{ ?>
			<div class="login">
				<form name="loginform" id="loginform" action="<?php echo get_template_directory_uri();?>/wp-login.php" method="post" class="form txt" target="registration_iframe">
					<fieldset>
						<p>
							<label for="username"><?php echo __( 'Username:' , _DEV_ );?></label>
							<input name="log" id="username" type="text" class="">
						</p>
						<p>
							<label for="password"><?php echo __( 'Password:' , _DEV_ );?></label>
							<input name="pwd" id="password" type="password" class="">
						</p>
						<p>
							<label class="remeberme"><input name="rememberme" type="checkbox" id="rememberme" value="forever" tabindex="90"><?php echo __( 'Remember Me' , _DEV_ );?></label>
						</p>
						<p>
							<span class="error" style="border:none" id="registration_error"></span>
						</p>
						<p class="button">
							<input type="submit" id="login_button" value="Login" class="button">
						</p>
                        <?php
                            if( !( _core::method( '_settings' , 'get' , 'settings' , 'social' , 'facebook' , 'secret' ) == '' || _core::method( '_settings' , 'get' , 'settings' , 'social' , 'facebook' , 'app_id' ) == '' ) ){
                                ?>
                                <div class="facebook">
                                    <?php _core::method( '_facebook' , 'login' ); ?>
                                </div>    
                                <?php
                            }
                        ?>
						<p class="pswd">
							<span><a href="<?php echo $recover_link;?>"><?php echo __( 'Lost your password?' , _DEV_ );?></a></span><?php if( _core::method( "_settings" , "logic" , "settings" , "general" , "theme" , "enb-login" ) ){?> | <span><a href="<?php echo $register_link;?>"><?php echo __( 'Register' , _DEV_ );?></a></span><?php } ?>
						</p>
					</fieldset>
					<input type="hidden" name="testcookie" value="1">
				</form>
			</div>
			<?php if(_core::method( "_settings" , "logic" , "settings" , "general" , "theme" , "enb-login" ) ){?>
			<div class="login-box">
				<p class="box">
					<span><?php echo __( 'No account?' , _DEV_ );?> <a href="<?php echo $register_link;?>" id="login" class="try"><?php echo __( 'Register here' , _DEV_ );?></a></span>
				</p>
			</div>
	<?php	}
		}?>
		<script type="text/javascript">
		jQuery(function(){
			jQuery("#register_form").submit(function(event){
				if(!jQuery("#check_email").val().length || !jQuery("#user_email").val().length || !(jQuery("#check_email").val().length>1) || !(jQuery("#user_email").val().length>1) )
					{
						jQuery("#registration_error").html("<?php echo __( "Enter your e-mail and e-mail verification" , _DEV_ );?>");
						event.preventDefault();
					}
				else if(jQuery("#check_email").val()!=jQuery("#user_email").val())
					{
						jQuery("#registration_error").html("<?php echo __( "Emails don't match" , _DEV_ );?>");
						event.preventDefault();
					}
				else if(!jQuery("#user_login").val().length || !(jQuery("#user_login").val().length>1))
					{
						jQuery("#registration_error").html("<?php echo __( "Enter a username" , _DEV_ );?>");
						event.preventDefault();
					}
			});

			jQuery("#loginform").submit(function(event){
				if(!(jQuery("#username").val().length && jQuery("#username").val().length>1)){
					jQuery("#registration_error").html("<?php echo __('Enter a username' , _DEV_ );?>");
					event.preventDefault();
				}
			});

			jQuery("#registration_iframe").load(function(){
				var iframeObject = document.getElementById('registration_iframe');
				var doc;
				if (iframeObject.contentDocument) {
					doc = iframeObject.contentDocument;
				} 
				else if (iframeObject.contentWindow) {
					doc = iframeObject.contentWindow.document;
				}
				if(doc.getElementById("login_error"))
					{
						if(doc.getElementById("login_error").innerHTML.indexOf("Lost your password")!=-1)
							{
								jQuery("#registration_error").html('<strong><?php _e( 'The password you entered' , _DEV_ ); ?><strong> <?php _e( 'is incorrect.' , _DEV_ ); ?> <a href="<?php echo $recover_link; ?>" title="<?php _e( 'Password Lost and Found' , _DEV_ ); ?>"><?php _e( 'Lost your password' , _DEV_ ); ?></a>?');
							}
						else jQuery("#registration_error").html(doc.getElementById("login_error").innerHTML);
					}
				else if(doc.getElementsByTagName("div")[0])
					{
						jQuery("#registration_info").html("<?php echo __('Registration successful' , _DEV_ );?>");
						window.location.href=document.referrer;
					}
			});
		});
	</script>
<?php } ?>