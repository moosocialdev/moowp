<?php $val = absint(get_option(self::$option_name.'_is_connecting')); ?>
<?php
if($val == 1){
    esc_attr_e( 'Yes', 'moowp' );
}else{
    esc_attr_e( 'No', 'moowp' );
}
?>
<!--<select class="regular-text" name="<?php /*echo self::$option_name.'_is_connecting' */?>" id="<?php /*echo self::$option_name.'_is_connecting' */?>">
    <option value="1" <?php /*if($val == 1): */?>selected<?php /*endif; */?>><?php /*_e( 'Yes', 'moowp' ); */?></option>
    <option value="0" <?php /*if($val == 0): */?>selected<?php /*endif; */?>><?php /*_e( 'No', 'moowp' ); */?></option>
</select>-->