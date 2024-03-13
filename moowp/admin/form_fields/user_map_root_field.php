<?php if ( ! defined( 'ABSPATH' ) ) {exit;} ?>
<?php
    $val = absint(get_option(self::$option_name.'_user_map_root'));
    echo $val;
?>
<!--<input type="text" class="regular-text" name="<?php /*echo esc_attr(self::$option_name.'_user_map_root') */?>" id="<?php /*echo esc_attr($this->option_name.'_user_map_root') */?>" value="<?php /*echo $val */?>">-->