<?php
/**
 * Checkout Class
 *
 * The JigoShop checkout class handles the checkout process, collecting user data and processing the payment.
 *
 * DISCLAIMER
 *
 * Do not edit or add directly to this file if you wish to upgrade Jigoshop to newer
 * versions in the future. If you wish to customise Jigoshop core for your needs,
 * please use our GitHub repository to publish essential changes for consideration.
 *
 * @package		Jigoshop
 * @category	Checkout
 * @author		Jigowatt
 * @copyright	Copyright (c) 2011-2012 Jigowatt Ltd.
 * @license		http://jigoshop.com/license/commercial-edition
 */

class jigoshop_checkout extends jigoshop_singleton {

	public $posted;
	public $billing_fields;
	public $shipping_fields;
	private $must_register = true;
	private $show_signup = false;

	/** constructor */
	protected function __construct () {

		$this->must_register = ( get_option('jigoshop_enable_guest_checkout') != 'yes' && !is_user_logged_in() );
		$this->show_signup = ( get_option('jigoshop_enable_signup_form') == 'yes' && !is_user_logged_in() );

		add_action('jigoshop_checkout_billing',array(&$this,'checkout_form_billing'));
		add_action('jigoshop_checkout_shipping',array(&$this,'checkout_form_shipping'));

		$this->billing_fields = array(
			array( 'name'=>'billing-first_name', 'label' => __('First Name', 'jigoshop'), 'placeholder' => __('First Name', 'jigoshop'), 'required' => true, 'class' => array('form-row-first') ),
			array( 'name'=>'billing-last_name', 'label' => __('Last Name', 'jigoshop'), 'placeholder' => __('Last Name', 'jigoshop'), 'required' => true, 'class' => array('form-row-last') ),
			array( 'name'=>'billing-company', 'label' => __('Company', 'jigoshop'), 'placeholder' => __('Company', 'jigoshop') ),
			array( 'name'=>'billing-address', 'label' => __('Address', 'jigoshop'), 'placeholder' => __('Address 1', 'jigoshop'), 'required' => true, 'class' => array('form-row-first') ),
			array( 'name'=>'billing-address-2', 'label' => __('Address 2', 'jigoshop'), 'placeholder' => __('Address 2', 'jigoshop'), 'class' => array('form-row-last'), 'label_class' => array('hidden') ),
			array( 'name'=>'billing-city', 'label' => __('City', 'jigoshop'), 'placeholder' => __('City', 'jigoshop'), 'required' => true, 'class' => array('form-row-first') ),
			array( 'type'=>'postcode', 'validate' => 'postcode', 'format' => 'postcode', 'name'=>'billing-postcode', 'label' => __('Postcode', 'jigoshop'), 'placeholder' => __('Postcode', 'jigoshop'), 'required' => true, 'class' => array('form-row-last') ),
			array( 'type'=> 'country', 'name'=>'billing-country', 'label' => __('Country', 'jigoshop'), 'required' => true, 'class' => array('form-row-first'), 'rel' => 'billing-state' ),
			array( 'type'=> 'state', 'name'=>'billing-state', 'label' => __('State/County', 'jigoshop'), 'required' => true, 'class' => array('form-row-last'), 'rel' => 'billing-country' ),
			array( 'name'=>'billing-email', 'validate' => 'email', 'label' => __('Email Address', 'jigoshop'), 'placeholder' => __('you@yourdomain.com', 'jigoshop'), 'required' => true, 'class' => array('form-row-first') ),
			array( 'name'=>'billing-phone', 'validate' => 'phone', 'label' => __('Phone', 'jigoshop'), 'placeholder' => __('Phone number', 'jigoshop'), 'required' => true, 'class' => array('form-row-last') )
		);

		$this->billing_fields = apply_filters( 'jigoshop_billing_fields', $this->billing_fields );

		$this->shipping_fields = array(
			array( 'name'=>'shipping-first_name', 'label' => __('First Name', 'jigoshop'), 'placeholder' => __('First Name', 'jigoshop'), 'required' => true, 'class' => array('form-row-first') ),
			array( 'name'=>'shipping-last_name', 'label' => __('Last Name', 'jigoshop'), 'placeholder' => __('Last Name', 'jigoshop'), 'required' => true, 'class' => array('form-row-last') ),
			array( 'name'=>'shipping-company', 'label' => __('Company', 'jigoshop'), 'placeholder' => __('Company', 'jigoshop') ),
			array( 'name'=>'shipping-address', 'label' => __('Address', 'jigoshop'), 'placeholder' => __('Address 1', 'jigoshop'), 'required' => true, 'class' => array('form-row-first') ),
			array( 'name'=>'shipping-address-2', 'label' => __('Address 2', 'jigoshop'), 'placeholder' => __('Address 2', 'jigoshop'), 'class' => array('form-row-last'), 'label_class' => array('hidden') ),
			array( 'name'=>'shipping-city', 'label' => __('City', 'jigoshop'), 'placeholder' => __('City', 'jigoshop'), 'required' => true, 'class' => array('form-row-first') ),
			array( 'type'=>'postcode', 'validate' => 'postcode', 'format' => 'postcode', 'name'=>'shipping-postcode', 'label' => __('Postcode', 'jigoshop'), 'placeholder' => __('Postcode', 'jigoshop'), 'required' => true, 'class' => array('form-row-last') ),
			array( 'type'=> 'country', 'name'=>'shipping-country', 'label' => __('Country', 'jigoshop'), 'required' => true, 'class' => array('form-row-first'), 'rel' => 'shipping-state' ),
			array( 'type'=> 'state', 'name'=>'shipping-state', 'label' => __('State/County', 'jigoshop'), 'required' => true, 'class' => array('form-row-last'), 'rel' => 'shipping-country' )
		);

		$this->shipping_fields = apply_filters( 'jigoshop_shipping_fields', $this->shipping_fields );
	}

	/** Output the billing information form */
	function checkout_form_billing() {

		if (jigoshop_cart::ship_to_billing_address_only()) :

			echo '<h3>'.__('Billing &amp; Shipping', 'jigoshop').'</h3>';

		else :

			echo '<h3>'.__('Billing Address', 'jigoshop').'</h3>';

		endif;

		// Billing Details
		foreach ($this->billing_fields as $field) :
			$field = apply_filters( 'jigoshop_billing_field', $field );
			$this->checkout_form_field( $field );
		endforeach;

		// Registration Form
		if ($this->show_signup) :

			echo '<p class="form-row"><input class="input-checkbox" id="createaccount" ';
			if ($this->get_value('createaccount')) echo 'checked="checked" ';
			echo 'type="checkbox" name="createaccount" /> <label for="createaccount" class="checkbox">'.__('Create an account?', 'jigoshop').'</label></p>';

			echo '<div class="create-account">';

			$this->checkout_form_field( array( 'type' => 'text', 'name' => 'account-username', 'label' => __('Account username', 'jigoshop'), 'placeholder' => __('Username', 'jigoshop') ) );
			$this->checkout_form_field( array( 'type' => 'password', 'name' => 'account-password', 'label' => __('Account password', 'jigoshop'), 'placeholder' => __('Password', 'jigoshop'),'class' => array('form-row-first')) );
			$this->checkout_form_field( array( 'type' => 'password', 'name' => 'account-password-2', 'label' => __('Account password', 'jigoshop'), 'placeholder' => __('Password again', 'jigoshop'),'class' => array('form-row-last'), 'label_class' => array('hidden')) );

			echo '<p><small>'.__('Save time in the future and check the status of your order by creating an account.', 'jigoshop').'</small></p></div>';

		endif;

	}

	/** Output the shipping information form */
	function checkout_form_shipping() {

		// Shipping Details
//		if (jigoshop_cart::needs_shipping() && !jigoshop_cart::ship_to_billing_address_only()) :
		// even if not calculating shipping, we still need to display second shipping address for free shipping
		if (!jigoshop_cart::ship_to_billing_address_only() && get_option('jigoshop_calc_shipping') == 'yes') :

			echo '<p class="form-row" id="shiptobilling"><input class="input-checkbox" ';

			if (!$_POST) $shiptobilling = apply_filters('shiptobilling_default', 1); else $shiptobilling = $this->get_value('shiptobilling');
			if ($shiptobilling) echo 'checked="checked" ';
			echo 'type="checkbox" name="shiptobilling" /> <label for="shiptobilling" class="checkbox">'.__('Ship to same address?', 'jigoshop').'</label></p>';

			echo '<h3>'.__('Shipping Address', 'jigoshop').'</h3>';

			echo'<div class="shipping-address">';


				foreach ($this->shipping_fields as $field) :
					$field = apply_filters( 'jigoshop_shipping_field', $field );
					$this->checkout_form_field( $field );
				endforeach;

			echo'</div>';

		elseif (jigoshop_cart::ship_to_billing_address_only()) :

			echo '<h3>'.__('Notes/Comments', 'jigoshop').'</h3>';

		endif;

		$this->checkout_form_field( array( 'type' => 'textarea', 'class' => array('notes'),  'name' => 'order_comments', 'label' => __('Order Notes', 'jigoshop'), 'placeholder' => __('Notes about your order.', 'jigoshop') ) );

	}

	/**
	 * Outputs a form field
	 *
	 * @param   array	args	contains a list of args for showing the field, merged with defaults (below)
	 */
	function checkout_form_field( $args ) {

		$defaults = array(
			'type' => 'input',
			'name' => '',
			'label' => '',
			'placeholder' => '',
			'required' => false,
			'class' => array(),
			'label_class' => array(),
			'rel' => '',
			'return' => false
		);

		$args = wp_parse_args( $args, $defaults );

		if ($args['required']) {
			$required = ' <span class="required">*</span>';
			$input_required = ' input-required';
		} else {
			$required = '';
			$input_required = '';
		}

		if (in_array('form-row-last', $args['class'])) {
			$after = '<div class="clear"></div>';
		} else {
			$after = '';
		}

		$field = '';

		switch ($args['type']) :
			case "country" :

                //Remove 'Select a Country' option from drop-down menu for countries.
                // There is no need to have it, because was assume when user hasn't selected
                // a country that they are from the shop base country.
                $field = '<p class="form-row '.implode(' ', $args['class']).'">
                <label for="'.$args['name'].'" class="'.implode(' ', $args['label_class']).'">'.$args['label'].$required.'</label>
                <select name="'.$args['name'].'" id="'.$args['name'].'" class="country_to_state" rel="'.$args['rel'].'">';

				foreach(jigoshop_countries::get_allowed_countries() as $key=>$value) :
					$field .= '<option value="'.esc_attr($key).'"';
					if ($this->get_value($args['name'])==$key) $field .= 'selected="selected"';
					elseif (!$this->get_value($args['name']) && jigoshop_customer::get_country()==$key) $field .= 'selected="selected"';
					$field .= '>'.__($value, 'jigoshop').'</option>';
				endforeach;

				$field .= '</select></p>'.$after;

			break;
			case "state" :

				$field = '<p class="form-row '.implode(' ', $args['class']).'">
					<label for="' . esc_attr( $args['name'] ) . '" class="'.implode(' ', $args['label_class']).'">'.$args['label'].$required.'</label>';

				$current_cc = $this->get_value($args['rel']);
				if (!$current_cc) $current_cc = jigoshop_customer::get_country();

				$current_r = $this->get_value($args['name']);
				if (!$current_r) $current_r = jigoshop_customer::get_state();

				$states = jigoshop_countries::get_states( $current_cc );

				if (isset( $states[$current_cc][$current_r] )) :
					// Dropdown
					$field .= '<select name="'.esc_attr($args['name']).'" id="'.esc_attr($args['name']).'" class="'.esc_attr($input_required).'"><option value="">'.__('Select a state&hellip;', 'jigoshop').'</option>';
					foreach($states[$current_cc] as $key=>$value) :
						$field .= '<option value="'.esc_attr($key).'"';
						if ($current_r==$key) $field .= 'selected="selected"';
						$field .= '>'.__($value, 'jigoshop').'</option>';
					endforeach;
					$field .= '</select>';
				else :
					// Input
					$field .= '<input type="text" class="input-text" value="'.esc_attr($current_r).'" placeholder="'.__('State/County', 'jigoshop').'" name="'.esc_attr($args['name']).'" id="'.esc_attr($args['name']).'" />';
				endif;

				$field .= '</p>'.$after;

			break;
			case "postcode" :
				$current_pc = $this->get_value($args['name']);
				$is_shipping_pc = strpos($args['name'], 'shipping');
				if (!$current_pc) :
					if ($is_shipping_pc === false) $current_pc = jigoshop_customer::get_postcode();
					else $current_pc = jigoshop_customer::get_shipping_postcode();
				endif;

				$field = '<p class="form-row '.implode(' ', $args['class']).'">
					<label for="' . esc_attr( $args['name'] ) . '" class="'.implode(' ', $args['label_class']).'">'.$args['label'].$required.'</label>
					<input type="'.$args['type'].'" class="input-text" name="'.esc_attr($args['name']).'" id="'.esc_attr($args['name']).'" placeholder="'.$args['placeholder'].'" value="' . esc_attr( $current_pc ) . '" />
				</p>'.$after;
			break;
			case "textarea" :

				$field = '<p class="form-row '.implode(' ', $args['class']).'">
					<label for="' . esc_attr( $args['name'] ) . '" class="'.implode(' ', $args['label_class']).'">'.$args['label'].$required.'</label>
					<textarea name="'.esc_attr($args['name']).'" class="input-text' . esc_attr( $input_required ) . '" id="'.esc_attr($args['name']).'" placeholder="'.$args['placeholder'].'" cols="5" rows="2">'. esc_textarea( $this->get_value( $args['name'] ) ).'</textarea>
				</p>'.$after;

			break;
			default :

				$field = '<p class="form-row '.implode(' ', $args['class']).'">
					<label for="' . esc_attr( $args['name'] ) . '" class="'.implode(' ', $args['label_class']).'">'.$args['label'].$required.'</label>
					<input type="'.$args['type'].'" class="input-text' . esc_attr( $input_required ) . '" name="'.esc_attr($args['name']).'" id="'.esc_attr($args['name']).'" placeholder="'.$args['placeholder'].'" value="'. $this->get_value( $args['name'] ).'" />
				</p>'.$after;

			break;
		endswitch;

		if ($args['return']) return $field; else echo $field;
	}

	/** Process the checkout after the confirm order button is pressed */
	function process_checkout() {

		global $wpdb;

		if (!defined('JIGOSHOP_CHECKOUT')) define('JIGOSHOP_CHECKOUT', true);

        // always calculate totals when coming to checkout, as we need the total calculated on the cart here
        jigoshop_cart::calculate_totals();

		if (isset($_POST) && $_POST && !isset($_POST['login'])) :

			jigoshop::verify_nonce('process_checkout');

			if (sizeof(jigoshop_cart::$cart_contents)==0) :
				jigoshop::add_error( sprintf(__('Sorry, your session has expired. <a href="%s">Return to homepage &rarr;</a>','jigoshop'), home_url()) );
			endif;

			// Checkout fields
			$this->posted['shiptobilling'] = isset($_POST['shiptobilling']) ? jigowatt_clean($_POST['shiptobilling']) : '';
			$this->posted['payment_method'] = isset($_POST['payment_method']) ? jigowatt_clean($_POST['payment_method']) : '';
			if (isset($_POST['shipping_method'])) :
				$shipping_method = jigowatt_clean($_POST['shipping_method']);
				$shipping_data = explode(":", $shipping_method);
				$this->posted['shipping_method'] = $shipping_data[0];
				$this->posted['shipping_service'] = $shipping_data[1];
			else :
				$this->posted['shipping_method'] = '';
				$this->posted['shipping_service'] = '';
			endif;
			$this->posted['order_comments'] = isset($_POST['order_comments']) ? jigowatt_clean($_POST['order_comments']) : '';
			$this->posted['terms'] = isset($_POST['terms']) ? jigowatt_clean($_POST['terms']) : '';
			$this->posted['createaccount'] = isset($_POST['createaccount']) ? jigowatt_clean($_POST['createaccount']) : '';
			$this->posted['account-username'] = isset($_POST['account-username']) ? jigowatt_clean($_POST['account-username']) : '';
			$this->posted['account-password'] = isset($_POST['account-password']) ? jigowatt_clean($_POST['account-password']) : '';
			$this->posted['account-password-2'] = isset($_POST['account-password-2']) ? jigowatt_clean($_POST['account-password-2']) : '';

			if (jigoshop_cart::ship_to_billing_address_only()) $this->posted['shiptobilling'] = 'true';

			// Billing Information
			foreach ($this->billing_fields as $field) :
				$field = apply_filters( 'jigoshop_billing_field', $field );

				$this->posted[$field['name']] = isset($_POST[$field['name']]) ? jigowatt_clean($_POST[$field['name']]) : '';

				// Format
				if (isset($field['format'])) switch ( $field['format'] ) :
					case 'postcode' : $this->posted[$field['name']] = strtolower(str_replace(' ', '', $this->posted[$field['name']])); break;
				endswitch;

				// Required
				if ( isset($field['required']) && $field['required'] && empty($this->posted[$field['name']]) ) jigoshop::add_error( $field['label'] . __(' (billing) is a required field.','jigoshop') );

				// Validation
				if (isset($field['validate']) && !empty($this->posted[$field['name']])) switch ( $field['validate'] ) :
					case 'phone' :
						if (!jigoshop_validation::is_phone( $this->posted[$field['name']] )) : jigoshop::add_error( $field['label'] . __(' (billing) is not a valid number.','jigoshop') ); endif;
					break;
					case 'email' :
						if (!jigoshop_validation::is_email( $this->posted[$field['name']] )) : jigoshop::add_error( $field['label'] . __(' (billing) is not a valid email address.','jigoshop') ); endif;
					break;
					case 'postcode' :
						if (!jigoshop_validation::is_postcode( $this->posted[$field['name']], $_POST['billing-country'] )) : jigoshop::add_error( $field['label'] . __(' (billing) is not a valid postcode/ZIP.','jigoshop') );
						else :
							$this->posted[$field['name']] = jigoshop_validation::format_postcode( $this->posted[$field['name']], $_POST['billing-country'] );
						endif;
					break;
				endswitch;

			endforeach;

			// Shipping Information
			if (jigoshop_cart::needs_shipping() && !jigoshop_cart::ship_to_billing_address_only() && empty($this->posted['shiptobilling'])) :

				foreach ($this->shipping_fields as $field) :
					$field = apply_filters( 'jigoshop_shipping_field', $field );

					if (isset( $_POST[$field['name']] )) $this->posted[$field['name']] = jigowatt_clean($_POST[$field['name']]); else $this->posted[$field['name']] = '';

					// Format
					if (isset($field['format'])) switch ( $field['format'] ) :
						case 'postcode' : $this->posted[$field['name']] = strtolower(str_replace(' ', '', $this->posted[$field['name']])); break;
					endswitch;

					// Required
					if ( isset($field['required']) && $field['required'] && empty($this->posted[$field['name']]) ) jigoshop::add_error( $field['label'] . __(' (shipping) is a required field.','jigoshop') );

					// Validation
					if (isset($field['validate']) && !empty($this->posted[$field['name']])) switch ( $field['validate'] ) :
						case 'postcode' :
							if (!jigoshop_validation::is_postcode( $this->posted[$field['name']], $this->posted['shipping-country'] )) : jigoshop::add_error( $field['label'] . __(' (shipping) is not a valid postcode/ZIP.','jigoshop') );
							else :
								$this->posted[$field['name']] = jigoshop_validation::format_postcode( $this->posted[$field['name']], $this->posted['shipping-country'] );
							endif;
						break;
					endswitch;

				endforeach;

			endif;

			if ($this->must_register || ( empty($user_id) && ($this->posted['createaccount'])) ) :

				if ( !$this->show_signup ) jigoshop::add_error( __('Sorry, the shop owner has disabled guest purchases.','jigoshop') );

				if ( empty($this->posted['account-username']) ) jigoshop::add_error( __('Please enter an account username.','jigoshop') );
				if ( empty($this->posted['account-password']) ) jigoshop::add_error( __('Please enter an account password.','jigoshop') );
				if ( $this->posted['account-password-2'] !== $this->posted['account-password'] ) jigoshop::add_error( __('Passwords do not match.','jigoshop') );

				// Check the username
				if ( !validate_username( $this->posted['account-username'] ) ) :
					jigoshop::add_error( __('Invalid email/username.','jigoshop') );
				elseif ( username_exists( $this->posted['account-username'] ) ) :
					jigoshop::add_error( __('An account is already registered with that username. Please choose another.','jigoshop') );
				endif;

				// Check the e-mail address
				if ( email_exists( $this->posted['billing-email'] ) ) :
					jigoshop::add_error( __('An account is already registered with your email address. Please login.','jigoshop') );
				endif;
			endif;

			// Terms
			if (!isset($_POST['update_totals']) && empty($this->posted['terms']) && jigoshop_get_page_id('terms')>0 ) jigoshop::add_error( __('You must accept our Terms &amp; Conditions.','jigoshop') );

			if (jigoshop_cart::needs_shipping()) :

				// Shipping Method
				$available_methods = jigoshop_shipping::get_available_shipping_methods();
				if (!isset($available_methods[$this->posted['shipping_method']]))
					jigoshop::add_error( __('Invalid shipping method.','jigoshop') );

			endif;

			if (jigoshop_cart::needs_payment()) :
				// Payment Method
				$available_gateways = jigoshop_payment_gateways::get_available_payment_gateways();
				if (!isset($available_gateways[$this->posted['payment_method']])) :
					jigoshop::add_error( __('Invalid payment method.','jigoshop') );
				else :
					// Payment Method Field Validation
					$available_gateways[$this->posted['payment_method']]->validate_fields();
				endif;
			endif;

			// hook, to be able to use the validation, but to be able to do something different afterwards
			do_action( 'jigoshop_after_checkout_validation', $this->posted, $_POST, jigoshop::error_count() );

			if (!isset($_POST['update_totals']) && jigoshop::error_count()==0) :

				$user_id = get_current_user_id();

				while (1) :

					// Create customer account and log them in
					if ($this->show_signup && !$user_id && $this->posted['createaccount']) :

						$reg_errors = new WP_Error();
						do_action('register_post', $this->posted['billing-email'], $this->posted['billing-email'], $reg_errors);
						$errors = apply_filters( 'registration_errors', $reg_errors, $this->posted['billing-email'], $this->posted['billing-email'] );

		                // if there are no errors, let's create the user account
						if ( !$reg_errors->get_error_code() ) :

			                $user_pass = $this->posted['account-password'];
			                $user_id = wp_create_user( $this->posted['account-username'], $user_pass, $this->posted['billing-email'] );
			                if ( !$user_id ) {
			                	jigoshop::add_error( sprintf(__('<strong>ERROR</strong>: Couldn&#8217;t register you... please contact the <a href="mailto:%s">webmaster</a> !', 'jigoshop'), get_option('jigoshop_email')));
			                    break;
							}
		                    // Change role
		                    wp_update_user( array ('ID' => $user_id, 'role' => 'customer', 'first_name' => $this->posted['billing-first_name'], 'last_name' => $this->posted['billing-last_name']) ) ;

		                    // send the user a confirmation and their login details
		                    wp_new_user_notification( $user_id, $user_pass );

		                    // set the WP login cookie
		                    $secure_cookie = is_ssl() ? true : false;
		                    wp_set_auth_cookie($user_id, true, $secure_cookie);

						else :
							jigoshop::add_error( $reg_errors->get_error_message() );
		                	break;
						endif;

					endif;

					$shipping_first_name = $shipping_last_name = $shipping_company = $shipping_address_1 =
					$shipping_address_2 = $shipping_city = $shipping_state = $shipping_postcode = $shipping_country = '';

					// Get shipping/billing
					if ( !empty($this->posted['shiptobilling']) ) :

						$shipping_first_name = $this->posted['billing-first_name'];
						$shipping_last_name = $this->posted['billing-last_name'];
						$shipping_company = $this->posted['billing-company'];
						$shipping_address_1 = $this->posted['billing-address'];
						$shipping_address_2 = $this->posted['billing-address-2'];
						$shipping_city = $this->posted['billing-city'];
						$shipping_state = $this->posted['billing-state'];
						$shipping_postcode = $this->posted['billing-postcode'];
						$shipping_country = $this->posted['billing-country'];

					elseif ( jigoshop_cart::needs_shipping() ) :

						$shipping_first_name = $this->posted['shipping-first_name'];
						$shipping_last_name = $this->posted['shipping-last_name'];
						$shipping_company = $this->posted['shipping-company'];
						$shipping_address_1 = $this->posted['shipping-address'];
						$shipping_address_2 = $this->posted['shipping-address-2'];
						$shipping_city = $this->posted['shipping-city'];
						$shipping_state = $this->posted['shipping-state'];
						$shipping_postcode = $this->posted['shipping-postcode'];
						$shipping_country = $this->posted['shipping-country'];

					endif;

					// Save billing/shipping to user meta fields
					if ($user_id>0) :
						update_user_meta( $user_id, 'billing-first_name', $this->posted['billing-first_name'] );
						update_user_meta( $user_id, 'billing-last_name', $this->posted['billing-last_name'] );
						update_user_meta( $user_id, 'billing-company', $this->posted['billing-company'] );
						update_user_meta( $user_id, 'billing-email', $this->posted['billing-email'] );
						update_user_meta( $user_id, 'billing-address', $this->posted['billing-address'] );
						update_user_meta( $user_id, 'billing-address-2', $this->posted['billing-address-2'] );
						update_user_meta( $user_id, 'billing-city', $this->posted['billing-city'] );
						update_user_meta( $user_id, 'billing-postcode', $this->posted['billing-postcode'] );
						update_user_meta( $user_id, 'billing-country', $this->posted['billing-country'] );
						update_user_meta( $user_id, 'billing-state', $this->posted['billing-state'] );
						update_user_meta( $user_id, 'billing-phone', $this->posted['billing-phone'] );

						if ( empty($this->posted['shiptobilling']) && jigoshop_cart::needs_shipping() ) :
							update_user_meta( $user_id, 'shipping-first_name', $this->posted['shipping-first_name'] );
							update_user_meta( $user_id, 'shipping-last_name', $this->posted['shipping-last_name'] );
							update_user_meta( $user_id, 'shipping-company', $this->posted['shipping-company'] );
							update_user_meta( $user_id, 'shipping-address', $this->posted['shipping-address'] );
							update_user_meta( $user_id, 'shipping-address-2', $this->posted['shipping-address-2'] );
							update_user_meta( $user_id, 'shipping-city', $this->posted['shipping-city'] );
							update_user_meta( $user_id, 'shipping-postcode', $this->posted['shipping-postcode'] );
							update_user_meta( $user_id, 'shipping-country', $this->posted['shipping-country'] );
							update_user_meta( $user_id, 'shipping-state', $this->posted['shipping-state'] );
						elseif ( $this->posted['shiptobilling'] && jigoshop_cart::needs_shipping() ) :
							update_user_meta( $user_id, 'shipping-first_name', $this->posted['billing-first_name'] );
							update_user_meta( $user_id, 'shipping-last_name', $this->posted['billing-last_name'] );
							update_user_meta( $user_id, 'shipping-company', $this->posted['billing-company'] );
							update_user_meta( $user_id, 'shipping-address', $this->posted['billing-address'] );
							update_user_meta( $user_id, 'shipping-address-2', $this->posted['billing-address-2'] );
							update_user_meta( $user_id, 'shipping-city', $this->posted['billing-city'] );
							update_user_meta( $user_id, 'shipping-postcode', $this->posted['billing-postcode'] );
							update_user_meta( $user_id, 'shipping-country', $this->posted['billing-country'] );
							update_user_meta( $user_id, 'shipping-state', $this->posted['billing-state'] );
						endif;

					endif;

					// Create Order (send cart variable so we can record items and reduce inventory). Only create if this is a new order, not if the payment was rejected last time.

					$_tax = new jigoshop_tax();

					$order_data = array(
						'post_type' => 'shop_order',
						'post_title' => 'Order &ndash; '.date('F j, Y @ h:i A'),
						'post_status' => 'publish',
						'post_excerpt' => $this->posted['order_comments'],
						'post_author' => 1
					);

					// Order meta data
					$data = array();
					$data['billing_first_name'] 	= $this->posted['billing-first_name'];
					$data['billing_last_name'] 		= $this->posted['billing-last_name'];
					$data['billing_company'] 		= $this->posted['billing-company'];
					$data['billing_address_1'] 		= $this->posted['billing-address'];
					$data['billing_address_2'] 		= $this->posted['billing-address-2'];
					$data['billing_city'] 			= $this->posted['billing-city'];
					$data['billing_postcode'] 		= $this->posted['billing-postcode'];
					$data['billing_country'] 		= $this->posted['billing-country'];
					$data['billing_state'] 			= $this->posted['billing-state'];
					$data['billing_email']			= $this->posted['billing-email'];
					$data['billing_phone']			= $this->posted['billing-phone'];
					$data['shipping_first_name'] 	= $shipping_first_name;
					$data['shipping_last_name'] 	= $shipping_last_name;
					$data['shipping_company']	 	= $shipping_company;
					$data['shipping_address_1']		= $shipping_address_1;
					$data['shipping_address_2']		= $shipping_address_2;
					$data['shipping_city']			= $shipping_city;
					$data['shipping_postcode']		= $shipping_postcode;
					$data['shipping_country']		= $shipping_country;
					$data['shipping_state']			= $shipping_state;
					$data['shipping_method']		= $this->posted['shipping_method'];
					$data['shipping_method_title']	= $available_methods[$this->posted['shipping_method']]->title;
					$data['shipping_service']		= $this->posted['shipping_service'];
					$data['payment_method']			= $this->posted['payment_method'];
					$data['payment_method_title']	= $available_gateways[$this->posted['payment_method']]->title;
                    $data['order_subtotal']			= jigoshop_cart::get_cart_subtotal(false);
                    $data['order_subtotal_inc_tax'] = jigoshop_cart::get_subtotal_inc_tax(false);
                    $data['order_shipping']			= jigoshop_cart::get_cart_shipping_total(false);
					$data['order_discount']			= number_format(jigoshop_cart::$discount_total, 2, '.', '');
					$data['order_tax']              = jigoshop_cart::get_taxes_as_string();
                    $data['order_tax_divisor']      = jigoshop_cart::get_tax_divisor();
					$data['order_shipping_tax']		= number_format(jigoshop_cart::$shipping_tax_total, 2, '.', '');
					$data['order_total']			= jigoshop_cart::get_total(false);

					$applied_coupons = array();
					foreach ( jigoshop_cart::$applied_coupons as $coupon ) :
						$applied_coupons[] = jigoshop_coupons::get_coupon( $coupon );
					endforeach;
					$data['order_discount_coupons']	= $applied_coupons;

					// Cart items
					$order_items = array();

					foreach (jigoshop_cart::$cart_contents as $cart_item_key => $values) :

						$_product = $values['data'];

						// Calc item tax to store
                                                //TODO: need to change this so that the admin pages can use all tax data on the page
						$rate = jigoshop_cart::get_total_tax_rate();

						$order_items[] = apply_filters('new_order_item', array(
					 		'id' 			=> $values['product_id'],
					 		'variation_id' 	=> $values['variation_id'],
                            'variation'     => $values['variation'],
					 		'name' 			=> $_product->get_title(),
					 		'qty' 			=> (int) $values['quantity'],
					 		'cost' 			=> $_product->get_price_excluding_tax(),
					 		'taxrate' 		=> $rate
					 	));

					 	// Check stock levels
					 	if ($_product->managing_stock()) :
							if (!$_product->is_in_stock() || !$_product->has_enough_stock( $values['quantity'] )) :
								$errormsg = (get_option('jigoshop_show_stock') == 'yes') ? (sprintf(__('Sorry, we do not have enough "%s" in stock to fulfill your order.  We have %d available at this time. Please edit your cart and try again. We apologize for any inconvenience caused.', 'jigoshop'), $_product->get_title(), $_product->get_stock() )) : (sprintf(__('Sorry, we do not have enough "%s" in stock to fulfill your order. Please edit your cart and try again. We apologize for any inconvenience caused.', 'jigoshop'), $_product->get_title() ));
								jigoshop::add_error($errormsg);
		                		break;
							endif;
						else :
							if (!$_product->is_in_stock()) :
								$errormsg = (get_option('jigoshop_show_stock') == 'yes') ? (sprintf(__('Sorry, we do not have enough "%s" in stock to fulfill your order.  We have %d available at this time. Please edit your cart and try again. We apologize for any inconvenience caused.', 'jigoshop'), $_product->get_title(), $_product->get_stock() )) : (sprintf(__('Sorry, we do not have enough "%s" in stock to fulfill your order. Please edit your cart and try again. We apologize for any inconvenience caused.', 'jigoshop'), $_product->get_title() ));
								jigoshop::add_error($errormsg);
		                		break;
							endif;

						endif;

					endforeach;

					if (jigoshop::error_count()>0) break;

					// Insert or update the post data
					// @TODO: This first bit over-writes an existing uncompleted order.  Do we want this?  -JAP-
					// UPDATE: commenting out for now. multiple orders now created.
// 					if (isset($_SESSION['order_awaiting_payment']) && $_SESSION['order_awaiting_payment'] > 0) :
//
// 						$order_id = (int) $_SESSION['order_awaiting_payment'];
// 						$order_data['ID'] = $order_id;
// 						wp_update_post( $order_data );
//
// 					else :
						$order_id = wp_insert_post( $order_data );

						if (is_wp_error($order_id)) :
							jigoshop::add_error( 'Error: Unable to create order. Please try again.' );
			                break;
						endif;
//					endif;

					// Update post meta
					update_post_meta( $order_id, 'order_data', $data );
					update_post_meta( $order_id, 'order_key', uniqid('order_') );
					update_post_meta( $order_id, 'customer_user', (int) $user_id );
					update_post_meta( $order_id, 'order_items', $order_items );
					wp_set_object_terms( $order_id, 'pending', 'shop_order_status' );

					$order = new jigoshop_order($order_id);

					// Inserted successfully
					do_action('jigoshop_new_order', $order_id);

					if (jigoshop_cart::needs_payment()) :

						// Store Order ID in session so it can be re-used after payment failure
						jigoshop_session::instance()->order_awaiting_payment = $order_id;

						// Process Payment
						$result = $available_gateways[$this->posted['payment_method']]->process_payment( $order_id );

						// Redirect to success/confirmation/payment page
						if ($result['result']=='success') :

							if (is_ajax()) :
								ob_clean();
								echo json_encode(apply_filters('jigoshop_is_ajax_payment_successful', $result));
								exit;
							else :
								wp_safe_redirect( apply_filters('jigoshop_is_ajax_payment_successful', $result['redirect']) );
								exit;
							endif;

						endif;

					else :

						// No payment was required for order
						$order->payment_complete();

						// Empty the Cart
						jigoshop_cart::empty_cart();

						// Redirect to success/confirmation/payment page
						$checkout_redirect = apply_filters( 'jigoshop_get_checkout_redirect_page_id', jigoshop_get_page_id('thanks') );
						if (is_ajax()) :
							ob_clean();
							echo json_encode( array( 'redirect'	=> get_permalink( $checkout_redirect ) ) );
							exit;
						else :
							wp_safe_redirect( get_permalink( $checkout_redirect ) );
							exit;
						endif;

					endif;

					// Break out of loop
					break;

				endwhile;

			endif;

			// If we reached this point then there were errors
			if (is_ajax()) :
				ob_clean();
				jigoshop::show_messages();
				exit;
			else :
				jigoshop::show_messages();
			endif;

		endif;
	}

	/** Gets the value either from the posted data, or from the users meta data */
	function get_value( $input ) {
		if (isset( $this->posted[$input] ) && !empty($this->posted[$input])) :
			return $this->posted[$input];
		elseif (is_user_logged_in()) :
			if (get_user_meta( get_current_user_id(), $input, true )) return get_user_meta( get_current_user_id(), $input, true );

			$current_user = wp_get_current_user();

			switch ( $input ) :

				case "billing-email" :
					return $current_user->user_email;
				break;

			endswitch;
		endif;
	}

    static function get_shipping_dropdown() {

        if (jigoshop_cart::needs_shipping()) :
          ?><tr>
                <td colspan="2"><?php _e('Shipping', 'jigoshop'); ?></td>
                <td>
                    <?php
                    $available_methods = jigoshop_shipping::get_available_shipping_methods();

                    if (sizeof($available_methods) > 0) :

                        echo '<select name="shipping_method" id="shipping_method">';

                        foreach ($available_methods as $method) :

                            if ($method instanceof jigoshop_calculable_shipping) :
                                $selected_service = NULL;
                                if ($method->is_chosen()) :

                                    if (is_numeric( jigoshop_session::instance()->selected_rate_id )) :
                                        $selected_service = $method->get_selected_service( jigoshop_session::instance()->selected_rate_id );
                                    else :
                                        $selected_service = $method->get_cheapest_service();
                                    endif;
                                endif;
                                for ($i = 0; $i < $method->get_rates_amount(); $i++) :
                                    echo '<option value="' . $method->id . ':' . $method->get_selected_service($i) . ':' . $i . '" ';
                                    if ($method->get_selected_service($i) == $selected_service) :
                                        echo 'selected="selected"';
                                    endif;
                                    echo '>' . $method->get_selected_service($i) . ' via ' . $method->title . ' &ndash; ';
                                    echo jigoshop_price($method->get_selected_price($i));
                                    if ($method->shipping_tax > 0) : echo __(' (ex. tax)', 'jigoshop');
                                    endif;
                                    echo '</option>';
                                endfor;
                            else :
                                echo '<option value="' . esc_attr( $method->id  ) . '::" ';
                                if ($method->is_chosen())
                                    echo 'selected="selected"';

                                echo '>' . $method->title . ' &ndash; ';

                                if ($method->shipping_total > 0) :
                                    echo jigoshop_price($method->shipping_total);
                                    if ($method->shipping_tax > 0) : echo __(' (ex. tax)', 'jigoshop');
                                    endif;
                                else :
                                    echo __('Free', 'jigoshop');
                                endif;

                                echo '</option>';
                            endif;

                        endforeach;

                        echo '</select>';

                    else :

                        echo '<p>' . __(jigoshop_shipping::get_shipping_error_message(), 'jigoshop') . '</p>';

                    endif;
                    ?></td>
            </tr><?php
        endif;

    }
}
