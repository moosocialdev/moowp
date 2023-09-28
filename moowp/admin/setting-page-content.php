<div class="wrap">
    <h2><?php esc_html_e( 'mooWP Plugin', 'moowp' ); ?></h2>
    <form action="options.php" method="post">
        <?php
        settings_errors();
        settings_fields( MOOWP_PLUGIN_NAME );
        do_settings_sections( MOOWP_PLUGIN_NAME );
        submit_button(); ?>
    </form>
</div>