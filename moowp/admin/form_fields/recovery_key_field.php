<?php if ( ! defined( 'ABSPATH' ) ) {exit;} ?>
<?php $val = esc_html( get_option(self::$option_name.'_recovery_key')); ?>
<?php echo $val; ?>