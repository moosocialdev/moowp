<?php if ( ! defined( 'ABSPATH' ) ) {exit;} ?>
<?php $val = time(); ?>
<input type="text" class="regular-text" name="<?php echo esc_attr(self::$option_name.'_setup_isset_moo') ?>" id="<?php echo esc_attr(self::$option_name.'_setup_isset_moo') ?>" value="<?php echo absint($val) ?>">