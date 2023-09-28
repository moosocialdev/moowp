<?php $val = get_option(self::$option_name.'_address_url'); ?>
<input type="text" class="regular-text" name="<?php echo self::$option_name.'_address_url' ?>" id="<?php echo self::$option_name.'_address_url' ?>" value="<?php echo $val ?>">

<script type="text/javascript">
    jQuery('document').ready(function (){
        jQuery('input#<?php echo self::$option_name.'_address_url' ?>').change(function () {
            var val = jQuery(this).val();
            var lastChar = val.slice(-1);
            if (lastChar == '?' || lastChar == '/') {
                val = val.slice(0, -1);
            }
            jQuery(this).val(val);
        });
    });
</script>