
<?php 
	/* Note!  if you add new values in this arrays, don't forget to do the same in /lib/shcode.php */
	$box_type = array('default','info','warning','download','error','tick','demo','comment');
	$box_size = array('medium','large');
?>
<div class="standard-generic-field generic-field-type">
    <div class="generic-label"><label for="box_type"><?php _e( 'Type' , _DEV_ ); ?></label></div>
    <div class="generic-input generic-input-select">
        <select id="box_type" class="select_medium">
            <?php
                foreach ($box_type as $type) {
                    echo "<option value='$type'>".$type."</option>";
                }
            ?>
        </select>
    </div>
    <div class="clear"></div>
</div>

<div class="standard-generic-field generic-field-size">
    <div class="generic-label"><label for="box_text_size"><?php _e( 'Size' , _DEV_ ); ?></label></div>
    <div class="generic-input generic-input-select">
        <select id="box_text_size" class="select_medium">
            <?php
                foreach ($box_size as $box_size) {
                    echo "<option value='$box_size'>".$box_size."</option>";
                }
            ?>
        </select>
    </div>
    <div class="clear"></div>
</div>


<div class="standard-generic-field generic-field-size">
    <div class="generic-label"><label for="box_style"><?php _e( 'Style' , _DEV_ ); ?></label></div>
    <div class="generic-input generic-input-select">
        <select id="box_style" class="select_medium" onchange="javascript:tools.hs.select( this , { 'default' : '.default-infobox' } )">
            <option value="default"  selected="selected"><?php _e( 'Default' , _DEV_ ); ?></option>
            <option value="arrow"><?php _e( 'Arrow' , _DEV_ ); ?></option>
            <option value="color"><?php _e( 'Color' , _DEV_ ); ?></option>
            
        </select>
    </div>
    <div class="clear"></div>
</div>

<div class="standard-generic-field generic-field-content">
	<div class="generic-label"><label for="box_content"><?php _e( 'Title' , _DEV_ ); ?></label></div>
	<div class="generic-input generic-input-text"> <input type="text" id="box_title" class="box-text"> </div>
    <div class="clear"></div>
</div>

<div class="standard-generic-field generic-field-content">
	<div class="generic-label"><label for="box_content"><?php _e( 'Content' , _DEV_ ); ?></label></div>
	<div class="generic-input generic-input-text"> <textarea type="text" id="box_content" class="box-text"> </textarea></div>
    <div class="clear"></div>
</div>


<div class="standard-generic-field generic-field-content">
	<div class="generic-label"><label for="box_content"><?php _e( 'Box url' , _DEV_ ); ?></label></div>
	<div class="generic-input generic-input-text"> <input type="text" id="box_url" class="box-text"> </div>
    <div class="clear"></div>
</div>  

<div class="standard-generic-field generic-field-content default-infobox hidden">
	<div class="generic-label"><label for="box_content"><?php _e( 'Right Title' , _DEV_ ); ?></label></div>
	<div class="generic-input generic-input-text"> <input type="text" id="box_right_title" class="box-text"> </div>
    <div class="clear"></div>
</div>

<div class="standard-generic-field generic-field-content default-infobox hidden">
	<div class="generic-label"><label for="box_content"><?php _e( 'Right Description' , _DEV_ ); ?></label></div>
	<div class="generic-input generic-input-text"> <input type="text" id="box_right_description" class="box-text"> </div>
    <div class="clear"></div>
</div>

<div class="standard-generic-field generic-field-size default-infobox hidden">
    <div class="generic-label"><label for="box_color"><?php _e( 'Right color' , _DEV_ ); ?></label></div>
    <div class="generic-input generic-input-select">
        <select id="box_color" class="select_medium">
            <option value="green" selected="selected"><?php _e( 'Green' , _DEV_ ); ?></option>
            <option value="blue"><?php _e( 'Blue' , _DEV_ ); ?></option>
        </select>
    </div>
    <div class="clear"></div>
</div>

<div class="standard-generic-field generic-field-preview">
    <div class="generic-label"></div>
    <div class="generic-input generic-input-preview">
        <p style="margin-left: 0px;margin-top: 18px;" class="cosmo-box default " id="box_sample"><span class="cosmo-ico"></span><?php _e( 'Box content' , _DEV_); ?></p>
    </div>
    <div class="clear"></div>
</div>


<div class="standard-generic-field generic-field-button">
    <div class="generic-label"></div>
    <div class="generic-input generic-input-button">
        <input type="button" onclick="resetBoxSettings();" class="button-secondary button" value="<?php _e( 'Reset' , _DEV_ ); ?>">
        <input type="button" onclick="insertBox()" id="insert_box_btn" value="<?php _e( 'Insert infobox' , _DEV_ ); ?>" class="button-primary">
    </div>
    <div class="clear"></div>
</div>