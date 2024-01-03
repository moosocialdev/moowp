<?php $val = esc_attr(get_option(self::$option_name.'_security_key')); ?>
<input type="text" class="regular-text bg-accent <?php if(empty($val)): ?>hidden<?php endif; ?>" name="<?php echo self::$option_name.'_security_key' ?>" id="<?php echo self::$option_name.'_security_key' ?>" value="<?php echo $val ?>" readonly>
<!--<button id="<?php //echo self::$option_name.'_security_button' ?>" class="button button-secondary" type="button">
    <?php
//        if(empty($val)){
//            esc_attr_e( 'Generate Security Key', 'moowp' );
//        }else{
//            esc_attr_e( 'Change Security Key', 'moowp' );
//        }
    ?>
</button>-->

<script type="text/javascript">
    function generateToken(length) {
        var chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        var token = '';
        for(let i = 0; i < length; i++) {
            token += chars[Math.floor(Math.random() * chars.length)];
        }
        return token;
    }

    function updateToken(){
        let token = generateToken(32);
        jQuery('#<?php echo self::$option_name.'_security_key' ?>').val(token);
        return token;
    }

    jQuery(document).ready(function (){
        /*if(jQuery('#<?php //echo self::$option_name.'_security_key' ?>').val() == ''){
            updateToken();
        }*/

        let current_security_key = jQuery('#<?php echo self::$option_name.'_security_key' ?>').val();

        jQuery('#<?php echo self::$option_name.'_security_button' ?>').click(function (e){
            e.preventDefault();
            let token = updateToken();

            if(current_security_key != token){
                jQuery('#<?php echo self::$option_name.'_security_key' ?>').css('background-color', '#c8e5ff');
            }

            if(jQuery('#<?php echo self::$option_name.'_security_key' ?>').val() == ''){
                jQuery(this).text('<?php echo __( 'Generate Security Key', 'moowp' ) ?>');
            }else {
                jQuery(this).text('<?php echo __( 'Change Security Key', 'moowp' ) ?>');
            }

            if(jQuery('#<?php echo self::$option_name.'_security_key' ?>').hasClass('hidden')){
                jQuery('#<?php echo self::$option_name.'_security_key' ?>').removeClass('hidden');
            }


        });
    });
</script>