<?php
    $val = get_option(self::$option_name.'_cookie_expire');
    if(empty($val)){
        $val = 60*12*7;
    }
?>
<input type="text" class="regular-text" name="<?php echo self::$option_name.'_cookie_expire' ?>" id="<?php echo self::$option_name.'_cookie_expire' ?>" value="<?php echo $val ?>">
<p class="description" id="home-description">
    <?php echo __( 'Set the cookie expiration time in minutes. e.g: 10', 'moowp' ) ?>
</p>