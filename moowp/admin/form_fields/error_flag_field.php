<?php $val = get_option(self::$option_name.'_error_flag'); ?>
<?php
    if($val == 1){
        esc_attr_e( 'Yes', 'moowp' );
    }else{
        esc_attr_e( 'No', 'moowp' );
    }
?>

<!--<select class="regular-text" name="<?php /*echo self::$option_name.'_error_flag' */?>" id="<?php /*echo self::$option_name.'_error_flag' */?>">
    <option value="1" <?php /*if($val == 1): */?>selected<?php /*endif; */?>><?php /*_e( 'Yes', 'moosocial' ); */?></option>
    <option value="0" <?php /*if($val == 0): */?>selected<?php /*endif; */?>><?php /*_e( 'No', 'moosocial' ); */?></option>
</select>-->