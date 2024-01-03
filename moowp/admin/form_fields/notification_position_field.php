<?php $val = esc_attr(get_option(self::$option_name.'_notification_position')); ?>
<select class="regular-text" name="<?php echo self::$option_name.'_notification_position' ?>" id="<?php echo self::$option_name.'_notification_position' ?>">
    <option value="bottom" <?php if($val == 'bottom'): ?>selected<?php endif; ?>><?php esc_attr_e( 'Bottom', 'moowp' ); ?></option>
    <option value="left" <?php if($val == 'left'): ?>selected<?php endif; ?>><?php esc_attr_e( 'Left', 'moowp' ); ?></option>
    <option value="right" <?php if($val == 'right'): ?>selected<?php endif; ?>><?php esc_attr_e( 'Right', 'moowp' ); ?></option>
</select>