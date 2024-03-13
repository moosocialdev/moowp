<?php if ( ! defined( 'ABSPATH' ) ) {exit;} ?>
<?php $val = esc_html(get_option(self::$option_name.'_pages_menu')); ?>
<?php echo $val; ?>
<!--<textarea class="regular-text" name="<?php /*echo esc_attr(self::$option_name.'_pages_menu') */?>" id="<?php /*echo esc_attr(self::$option_name.'_pages_menu') */?>" readonly><?php /*echo $val */?></textarea>-->