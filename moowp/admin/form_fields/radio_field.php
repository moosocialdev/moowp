<?php
    $val = get_option(self::$option_name.'_radio_bool');
?>
<fieldset>
    <label>
        <input type="radio" name="<?php echo self::$option_name.'_radio_bool' ?>" id="<?php echo self::$option_name.'_radio_bool' ?>" value="true" <?php checked( $val, 'true' ); ?>>
        <?php _e( 'True', 'moosocial' ); ?>
    </label>
    <br>
    <label>
        <input type="radio" name="<?php echo self::$option_name.'_radio_bool' ?>" value="false" <?php checked( $val, 'false' ); ?>>
        <?php _e( 'False', 'moosocial' ); ?>
    </label>
</fieldset>