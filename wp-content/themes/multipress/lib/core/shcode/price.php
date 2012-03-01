<div class="standard-generic-field ">
    <div class="generic-label">
        <label for="nr_cols"><?php _e( 'Set number of cols', 'cosmotheme' ); ?></label>
    </div>
    <div class="generic-input generic-select">
        <select id="nr_cols" onchange="javascript:price( this.value );">
            <option selected="selected"><?php _e( 'select number of columns' , _DEV_ ); ?></option>
            <?php
                for($i = 1; $i < 10; $i++ ){
                    if( $i != 1 ){
                        $col = 'col';
                    }else{
                        $col = 'cols';
                    }
                    echo '<option value="'.$i.'"> ' . $i . ' ' . $col . '</option>';
                }
            ?>

        </select>
    </div>
    
    <div class="clear"></div>
    
    <br />
    <!-- pricing table -->
    <div class="container-price-cols">
    </div>
</div>
