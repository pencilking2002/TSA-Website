<?php
    class _contact{
        public static function mail( ){
            if( isset( $_POST[ 'btn_send' ] ) && !empty( $_POST[ 'btn_send' ] ) && isset( $_POST[ 'contact_email' ] ) && is_email( $_POST[ 'contact_email' ] )  ){

                $tomail = $_POST['contact_email'];
                $result = '';
                if( isset( $_POST['name'] ) && strlen( $_POST['name'] ) ) {
                    $name =  trim( $_POST['name'] );
                }else{
                    $result .=  '<span class="error-mail">' . __('error, fill all required fields ( name )', _DEV_ ) . '</span>';
                }

                if( isset( $_POST['email'] ) && is_email( $_POST['email'] ) ){
                    $frommail = trim( $_POST['email'] );
                }else{
                    if( strlen( $result ) ){
                        $result .= ',<br/>';
                    }
                    $result .=  '<span class="error-mail">' . __('error, fill all required fields ( email )', _DEV_ ) . '</span>';

                }

                if( isset( $_POST['message'] ) && strlen($_POST['message']) ){
                	$message = '';
                	if( isset($_POST['name']) ){
                		$message .= __( 'Contact name: ' , _DEV_ ) . trim( $_POST[ 'name' ] ) . "\n";
                	}
                	if( isset($_POST['email']) ){
                		$message .= __( 'Contact email: ' , _DEV_ ) . trim( $_POST[ 'email' ] ) . "\n";
                	}
                	if( isset($_POST['phone']) ){
                		$message .= __( 'Contact phone: ' , _DEV_ ) . trim( $_POST[ 'phone' ] ) . "\n\n";
                	}

                    $message .= trim( $_POST['message'] );
                }else{
                    if( strlen( $result ) ){
                        $result .= ',<br/>';
                    }
                    $result .= '<span class="error-mail">' . __( 'error, fill all required fields ( message )' , _DEV_ ) . '</span>';
                }

                if( strlen( $result ) ){
                    echo $result;
                    exit();
                }
                
                if( is_email( $tomail ) && strlen( $tomail ) && strlen( $frommail ) &&  strlen( $name ) && strlen( $message ) ){
                	$subject = __( 'New email from' , _DEV_ ) . ' ' . get_bloginfo( 'name' ) .  '.' . __( 'Sent via contact form.' , _DEV_ );
                    wp_mail($tomail, $subject , $message);
                    echo '<span class="success-mail">' . __( 'Email sent successfully ' , _DEV_ ) . '</span>';
                }else{
                    echo '<span class="error-mail">' .  __( 'error, sending email failed' , _DEV_ ) . '</span>';
                }
            }
            exit;
        }

        public static function form( $email ){
?>
            <form id="comment_form" class="form comments b_contact" method="post" action="<?php echo home_url() ?>/">
			  <fieldset>
                  <p id="send_mail_result">
				  </p>
				  <p class="input">
					  <input type="text" onfocus="if (this.value == '<?php _e( 'Your name' , _DEV_ ); ?> *') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php _e( 'Your name' , _DEV_ ); ?> *';}" value="<?php _e( 'Your name' , _DEV_ ); ?> *" name="name" id="name" />
				  </p>
				  <p class="input">
					  <input type="text" onfocus="if (this.value == '<?php _e( 'Your email' , _DEV_ ); ?> *') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php _e( 'Your email' , _DEV_ ); ?> *';}" value="<?php _e( 'Your email' , _DEV_ ); ?> *" name="email" id="email" />
				  </p>
				  <p class="textarea">
					  <textarea onfocus="if (this.value == '<?php _e( 'Message' , _DEV_ ); ?> *') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php _e( 'Message' , _DEV_ ); ?> *';}" tabindex="4" cols="50" rows="10" id="comment" name="message"><?php _e( 'Message' , _DEV_ ); ?> *</textarea>
				  </p>
				  <p class="button hover">
					  <input type="button" value="<?php _e( 'Submit form' , _DEV_ ); ?>" name="btn_send" onclick="javascript:contact( 'contact' , '#comment_form' , 'p#send_mail_result' );" class="inp_button" />
				  </p>
                  
				  <input type="hidden" value="<?php echo $email; ?>" name="contact_email"  />
			  </fieldset>
		  </form>
<?php

        }
    }
?>