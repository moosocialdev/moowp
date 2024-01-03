<?php $val = absint(get_option(self::$option_name.'_radio_bool')); ?>
<fieldset>
    <label>
        <input type="radio" name="<?php echo self::$option_name.'_radio_bool' ?>" id="<?php echo self::$option_name.'_radio_bool' ?>" value="true" <?php checked( $val, 'true' ); ?>>
        <?php esc_attr_e( 'True', 'moowp' ); ?>
    </label>
    <br>
    <label>
        <input type="radio" name="<?php echo self::$option_name.'_radio_bool' ?>" value="false" <?php checked( $val, 'false' ); ?>>
        <?php esc_attr_e( 'False', 'moowp' ); ?>
    </label>
</fieldset>