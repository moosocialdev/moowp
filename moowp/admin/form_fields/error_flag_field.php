<?php $val = get_option(self::$option_name.'_error_flag'); ?>
<?php
    if($val == 1){
        _e( 'Yes', 'moosocial' );
    }else{
        _e( 'No', 'moosocial' );
    }
?>

<!--<select class="regular-text" name="<?php /*echo self::$option_name.'_error_flag' */?>" id="<?php /*echo self::$option_name.'_error_flag' */?>">
    <option value="1" <?php /*if($val == 1): */?>selected<?php /*endif; */?>><?php /*_e( 'Yes', 'moosocial' ); */?></option>
    <option value="0" <?php /*if($val == 0): */?>selected<?php /*endif; */?>><?php /*_e( 'No', 'moosocial' ); */?></option>
</select>-->