<?php $val = absint(get_option(self::$option_name.'_chat_plugin_enable')); ?>
<select class="regular-text" name="<?php echo self::$option_name.'_chat_plugin_enable' ?>" id="<?php echo self::$option_name.'_chat_plugin_enable' ?>">
    <option value="1" <?php if($val == 1): ?>selected<?php endif; ?>><?php esc_attr_e( 'Yes', 'moowp' ); ?></option>
    <option value="0" <?php if($val == 0): ?>selected<?php endif; ?>><?php esc_attr_e( 'No', 'moowp' ); ?></option>
</select>