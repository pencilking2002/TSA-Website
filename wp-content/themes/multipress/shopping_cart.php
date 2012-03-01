<div id="shopping_cart_details">
	<?php 	echo _core::method('_cart','get_shopping_cart_details'); ?>
	
</div>

<?php if($_SESSION["Payment_Amount"] != 0){ ?>
<form action='<?php echo add_query_arg( 'eco', 'bar' ); ?>' METHOD='POST' class="txt_r fr" id="paypal_btn">

	<input type='image' name='submit' src='https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif' border='0' align='top' alt='Check out with PayPal'/>
	
</form>
<?php } ?>

<!--<p class="button blue">
		<input type="submit" value="Checkout with PayPal">
	</p>-->

<!--  <input type='image' id="expresscheckout" name='submit' src='https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif' border='0' align='top' alt='Check out with PayPal'/> -->