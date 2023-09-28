<?php $val = get_option(self::$option_name.'_notification_position'); ?>
<select class="regular-text" name="<?php echo self::$option_name.'_notification_position' ?>" id="<?php echo self::$option_name.'_notification_position' ?>">
    <option value="bottom" <?php if($val == 'bottom'): ?>selected<?php endif; ?>><?php _e( 'Bottom', 'moosocial' ); ?></option>
    <option value="left" <?php if($val == 'left'): ?>selected<?php endif; ?>><?php _e( 'Left', 'moosocial' ); ?></option>
    <option value="right" <?php if($val == 'right'): ?>selected<?php endif; ?>><?php _e( 'Right', 'moosocial' ); ?></option>
</select>