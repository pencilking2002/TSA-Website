<?php           
    $register = _core::method( '_meta' , 'get' , $post -> ID , 'register'  );
    $currency = _core::method( '_settings' , 'get' , 'settings' , 'payment' , 'paypal' , 'currency' );            
?>
    <div class="cosmo-cart">
        <table class="t_subscript cosmotable default" id="checkout">
            <thead>
                <tr>
                    <th><?php _e( 'My cart' , _DEV_ ); ?></th>
                </tr>
            </thead>			
            <tbody>
                <tr class="elements">
                    <td>
                        <div class="itemPrice"><?php echo( _cart::get_currency_symbol( $currency ) . ' ' . $register[ 'value' ] ); ?></div>
                        <div class="itemName"><?php _e( 'per 1 person' , _DEV_ ); ?></div>
                    </td>
                </tr>
                <tr class="subtotal">
                    <td>
                        <?php echo _core::method( '_cart' , 'get_btn' , $post -> ID ); ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>