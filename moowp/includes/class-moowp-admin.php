<?php
class MooWP_Admin extends MooWP_App{
    private $initiated = false;

    public function init() {
        if ( ! $this->initiated ) {
            //$this->init_nav_items();
            add_action( 'init', array( $this, 'init_hooks' ) );
        }
    }

    /**
     * Initializes WordPress hooks
     */
    public function init_hooks() {
        if(isset($_GET['moosocialmenu']) && $_GET['moosocialmenu'] == 'load' ){
            $this->init_nav_items();
            wp_redirect( admin_url( 'nav-menus.php' ) );
        }

        $this->initiated = true;

        add_action('update_option_'.self::$option_name . '_setup_isset_moo', array($this, 'update_option_setup_isset_moo'), 10, 2);

        /* nav menu left */
        add_action('admin_menu', array($this, 'setup_menu' ), 9);

        /* Settings mooWP */
        add_action('admin_init', array($this, 'register_settings'));

        /* Menus: update_option */
        //add_action('wp_update_nav_menu', array($this, 'update_nav_menu'), 10, 2);

        /* Add menu items Box */
        add_action('admin_head-nav-menus.php', array($this, 'add_nav_menu_metabox'));

        /* Menu structure: mooSocial Dynamic Link */
        add_filter('wp_setup_nav_menu_item', array($this, 'nav_menu_type_label'));

        /* Replace the #keyword# by the correct links with nonce ect */
        add_filter('wp_setup_nav_menu_item', array($this, 'setup_nav_menu_item'));
    }

    public function setup_menu() {
        add_menu_page(
            MOOWP_PLUGIN_NAME,
            __( 'mooWP', 'moowp' ),
            'manage_options',
            'moo-setting-page',
            array( $this, 'setting_admin_page_contents' ),
            'dashicons-buddicons-forums',
            3
        );
    }

    /*
     * setup_menu()
     * */
    public function setting_admin_page_contents(){
        include MOOWP_PLUGIN_DIR . 'admin/setting-page-content.php';
    }

    public function register_settings() {
        $class_hide = 'hidden';
        $class = (empty($this->moosocial_is_connecting)) ? $class_hide : '';
        $isSetupIssetMoo = false;

        if($this->moosocial_is_connecting == 0 && isset($_GET['setup_isset_moo']) && $_GET['setup_isset_moo'] == 1){
            $isSetupIssetMoo = true;
        }

        // Add a General section
        add_settings_section(
            self::$option_name . '_general',
            __( 'General Settings', 'moowp' ),
            array( $this, self::$option_name . '_general_field' ),
            $this->plugin_name
        );

        /* Field: Cookie Expire */
        add_settings_field(
            self::$option_name . '_cookie_expire',
            __( 'Cookie Expire', 'moowp' ),
            array( $this, self::$option_name . '_cookie_expire_field' ),
            $this->plugin_name,
            self::$option_name . '_general',
            array( 'label_for' => self::$option_name . '_cookie_expire', 'class' => $class_hide )
        );

        /* Field: Security key */
        add_settings_field(
            self::$option_name . '_security_key',
            __( 'Security key', 'moowp' ),
            array( $this, self::$option_name . '_security_key_field' ),
            $this->plugin_name,
            self::$option_name . '_general',
            array( 'label_for' => self::$option_name . '_security_key', 'class' => '' )
        );

        /* Field: Moosocial Address (URL) */
        $class_address_url = '';
        if($this->moosocial_is_connecting == 0){
            if(!$isSetupIssetMoo){
                $class_address_url = $class_hide;
            }
        }
	
        add_settings_field(
            self::$option_name . '_address_url',
            __( 'Your mooSocial website URL', 'moowp' ),
            array( $this, self::$option_name . '_address_url_field' ),
            $this->plugin_name,
            self::$option_name . '_general',
            array( 'label_for' => self::$option_name . '_address_url', 'class' => $class_address_url )
        );

        /* Field: notification_position */
        $show_in_header = apply_filters('theme_notification_show_in_header', false);
        if($show_in_header){
            $class_notification_position = $class_hide;
        }else{
            $class_notification_position = empty($this->moosocial_security_key) ? $class_hide : '';
        }

        add_settings_field(
            self::$option_name . '_notification_position',
            __( 'Social notification icons position on your wordpress site', 'moowp' ),
            array( $this, self::$option_name . '_notification_position_field' ),
            $this->plugin_name,
            self::$option_name . '_general',
            array( 'label_for' => self::$option_name . '_notification_position', 'class' => $class_notification_position )
        );

        /* Field: chat_plugin_enable */
        add_settings_field(
            self::$option_name . '_chat_plugin_enable',
            __( 'Moosocial Enable Plugin Chat', 'moowp' ),
            array( $this, self::$option_name . '_chat_plugin_enable_field' ),
            $this->plugin_name,
            self::$option_name . '_general',
            array( 'label_for' => self::$option_name . '_chat_plugin_enable', 'class' => $class )
        );

        add_settings_field(
            self::$option_name . '_moosocial_toolbar',
            '',
            array( $this, self::$option_name . '_moosocial_toolbar_field' ),
            $this->plugin_name,
            self::$option_name . '_general',
            array( 'label_for' => self::$option_name . '_moosocial_toolbar', 'class' => '' )
        );

        //------------
        //------------
        /* Field: Error Flag */
        add_settings_field(
            self::$option_name . '_error_flag',
            __( 'Error Flag', 'moowp' ),
            array( $this, self::$option_name . '_error_flag_field' ),
            $this->plugin_name,
            self::$option_name . '_general',
            array( 'label_for' => self::$option_name . '_error_flag', 'class' => $class_hide )
        );
        /* Field: is mapping */
        add_settings_field(
            self::$option_name . '_is_connecting',
            __( 'Mapping with mooSocial', 'moowp' ),
            array( $this, self::$option_name . '_is_connecting_field' ),
            $this->plugin_name,
            self::$option_name . '_general',
            array( 'label_for' => self::$option_name . '_is_connecting', 'class' => $class_hide )
        );
        /* Field: user_map_root */
        add_settings_field(
            self::$option_name . '_user_map_root',
            __( 'Root User ID', 'moowp' ),
            array( $this, self::$option_name . '_user_map_root_field' ),
            $this->plugin_name,
            self::$option_name . '_general',
            array( 'label_for' => self::$option_name . '_user_map_root', 'class' => $class_hide )
        );
        /* Field: Moosocial Pages Menu */
        add_settings_field(
            self::$option_name . '_pages_menu',
            __( 'Moosocial Pages Menu', 'moowp' ),
            array( $this, self::$option_name . '_pages_menu_field' ),
            $this->plugin_name,
            self::$option_name . '_general',
            array( 'label_for' => self::$option_name . '_pages_menu', 'class' => $class_hide )
        );
        /* Field: Moosocial Pages Menu */
        add_settings_field(
            self::$option_name . '_recovery_key',
            __( 'Recovery Key', 'moowp' ),
            array( $this, self::$option_name . '_recovery_key_field' ),
            $this->plugin_name,
            self::$option_name . '_general',
            array( 'label_for' => self::$option_name . '_recovery_key', 'class' => $class_hide )
        );

        if($isSetupIssetMoo){
            /* Field: is setup with isset moosocial */
            add_settings_field(
                self::$option_name . '_setup_isset_moo',
                __( 'setup isset moo', 'moowp' ),
                array( $this, self::$option_name . '_setup_isset_moo_field' ),
                $this->plugin_name,
                self::$option_name . '_general',
                array( 'label_for' => self::$option_name . '_setup_isset_moo', 'class' => $class_hide )
            );
        }
        /*
        add_settings_field(
            self::$option_name . '_radio_bool',
            __( 'Boolean setting', 'moowp' ),
            array( $this, self::$option_name . '_radio_bool_field' ),
            $this->plugin_name,
            self::$option_name . '_general',
            array( 'label_for' => self::$option_name . '_radio_bool' )
        );
        add_settings_field(
            self::$option_name . '_number',
            __( 'Number setting', 'moowp' ),
            array( $this, self::$option_name . '_number_cb' ),
            $this->plugin_name,
            self::$option_name . '_general',
            array( 'label_for' => self::$option_name . '_number' )
        );
        */
        // Register the field
        register_setting( $this->plugin_name, self::$option_name . '_address_url', 'text' );
        register_setting( $this->plugin_name, self::$option_name . '_cookie_expire', 'integer' );
        register_setting( $this->plugin_name, self::$option_name . '_notification_position', 'text' );
        register_setting( $this->plugin_name, self::$option_name . '_chat_plugin_enable', 'boolean' );
        register_setting( $this->plugin_name, self::$option_name . '_security_key', 'text' );
        register_setting( $this->plugin_name, self::$option_name . '_pages_menu', 'text' );
        register_setting( $this->plugin_name, self::$option_name . '_user_map_root', 'integer' );
        register_setting( $this->plugin_name, self::$option_name . '_error_flag', 'boolean' );
        register_setting( $this->plugin_name, self::$option_name . '_is_connecting', 'boolean' );
        register_setting( $this->plugin_name, self::$option_name . '_setup_isset_moo', 'text' );
        register_setting( $this->plugin_name, self::$option_name . '_notification_position', 'text' );
        register_setting( $this->plugin_name, self::$option_name . '_chat_plugin_enable', 'boolean' );
        register_setting( $this->plugin_name, self::$option_name . '_user_map_root', 'integer' );
        //register_setting( $this->plugin_name, self::$option_name . '_radio_bool', '');
        //register_setting( $this->plugin_name, self::$option_name . '_number', 'integer' );
    }

    public function update_option_setup_isset_moo( $old_value, $value ) {
        update_option(self::$option_name . '_is_connecting', true);
    }

    public function update_nav_menu($id, $data = NULL){
        $this->init_nav_items();
    }

    private function init_nav_items(){
        global $pagenow;

        $url = $this->moosocial_address_url.'/'.MOOWP_CORE_API_NAMESPACE.'/load-moo-menu';

        $post_data = array(
            'security_key' => $this->moosocial_security_key
        );

        if($pagenow == 'nav-menus.php' && !defined('DOING_AJAX')) {
            $result_data = $this->curl_post($url, $post_data,'json');
            if(isset($result_data['success']) && $result_data['success'] == true){
                update_option(self::$option_name.'_pages_menu', maybe_serialize($result_data['data']));
                $this->moosocial_pages_menu = $result_data['data'];
            }
        }
    }

    public function add_nav_menu_metabox() {
        add_meta_box(
            'add-moosocial-links',
            __( 'mooSocial', 'moowp' ),
            array($this, 'nav_menu_metabox'),
            'nav-menus',
            'side',
            'default'
        );
    }

    /*
     * add_nav_menu_metabox()
     * */
    public function nav_menu_metabox() {
        include MOOWP_PLUGIN_DIR . 'admin/class-moowp-nav-items.php';

        global $nav_menu_selected_id;

        $elems = $this->moosocial_pages_menu;

        $elems_obj = array();

        foreach($elems as $key => $item) {
            $elems_obj[$item['title']]              = new MooWP_Nav_Items();
            $elems_obj[$item['title']]->object_id       = esc_attr($key);
            $elems_obj[$item['title']]->title             = esc_attr($item['title']);
            $elems_obj[$item['title']]->url             = esc_attr($key);
            //$elems_obj[$item['title']]->url               = esc_attr($item['url']);
        }

        $walker = new Walker_Nav_Menu_Checklist(array());

        include MOOWP_PLUGIN_DIR . 'admin/menu_metabox_view.php';
    }

    /* Modify the "type_label" */
    public function nav_menu_type_label($menu_item) {
        $elems = array_keys($this->moosocial_pages_menu);

        if(isset($menu_item->object, $menu_item->url) && 'custom' == $menu_item->object && in_array($menu_item->url, $elems)) {
            $menu_item->type_label = __('mooSocial Dynamic Link', 'moowp');
        }

        return $menu_item;
    }

    /* The main code, this replace the #keyword# by the correct links with nonce ect */
    public function setup_nav_menu_item($item) {
        global $pagenow;

        if($pagenow != 'nav-menus.php' && !defined('DOING_AJAX') && isset($item->url) && strstr($item->url, '#moosocial') != '') {
            if(isset($this->moosocial_pages_menu[$item->url]) && !empty($this->moosocial_pages_menu[$item->url])){
                $item->url = $this->moosocial_pages_menu[$item->url]['url'];
            }else{
                $item->url = home_url();
            }
            if(empty($item->title)){
                $item->title = 'Moosocial';
            }
        }

        return $item;
    }

    public function moowp_setting_general_field() {
        include MOOWP_PLUGIN_DIR . 'admin/form_fields/general_field.php';
    }

    public function moowp_setting_address_url_field() {
        include MOOWP_PLUGIN_DIR . 'admin/form_fields/address_url_field.php';
    }

    public function moowp_setting_security_key_field() {
        include MOOWP_PLUGIN_DIR . 'admin/form_fields/security_key_field.php';
    }

    public function moowp_setting_pages_menu_field() {
        include MOOWP_PLUGIN_DIR . 'admin/form_fields/pages_menu_field.php';
    }

    public function moowp_setting_recovery_key_field() {
        include MOOWP_PLUGIN_DIR . 'admin/form_fields/recovery_key_field.php';
    }

    public function moowp_setting_cookie_expire_field() {
        include MOOWP_PLUGIN_DIR . 'admin/form_fields/cookie_expire_field.php';
    }

    public function moowp_setting_user_map_root_field() {
        include MOOWP_PLUGIN_DIR . 'admin/form_fields/user_map_root_field.php';
    }

    public function moowp_setting_notification_position_field() {
        include MOOWP_PLUGIN_DIR . 'admin/form_fields/notification_position_field.php';
    }

    public function moowp_setting_chat_plugin_enable_field() {
        include MOOWP_PLUGIN_DIR . 'admin/form_fields/chat_plugin_enable_field.php';
    }

    public function moowp_setting_error_flag_field() {
        include MOOWP_PLUGIN_DIR . 'admin/form_fields/error_flag_field.php';
    }

    public function moowp_setting_is_connecting_field() {
        include MOOWP_PLUGIN_DIR . 'admin/form_fields/is_connecting_field.php';
    }

    public function moowp_setting_setup_isset_moo_field() {
        $isSetupIssetMoo = false;
        if(isset($_GET['setup_isset_moo']) && $_GET['setup_isset_moo'] == 1){
            $isSetupIssetMoo = true;
        }
        include MOOWP_PLUGIN_DIR . 'admin/form_fields/setup_isset_moo_field.php';
    }

    public function moowp_setting_moosocial_toolbar_field() {
        $current_user = wp_get_current_user();
        $isAdminWP = in_array('administrator', $current_user->roles, true);

        $check_result_data = null;
        $message_error = '';
        $message_ok = '';
        $error_flag = false;
        $is_access_community_panel = false;
        $is_update_community_security_key = false;
        $is_confirm_update_community_security_key = false;
        $is_setup_community_site = false;

        if($this->moosocial_is_connecting == 0 && isset($_GET['setup_isset_moo']) && $_GET['setup_isset_moo'] == 1){
            $is_setup_community_site = true;
        }

        if($this->moosocial_is_connecting == 1){
            if(!empty($this->moosocial_address_url) && !empty($this->moosocial_security_key)){
                $url = $this->moosocial_address_url.'/'.MOOWP_CORE_API_NAMESPACE.'/get-status';

                $post = [
                    'security_key' => $this->moosocial_security_key
                ];

                $result_data = $this->curl_post($url, $post, 'json');

                if(isset($result_data['success']) && !empty($result_data['code']) ){
                    switch ($result_data['code']){
                        case 'plugin_moowp_disabled':
                            $error_flag = true;
                            $this->update_connecting_error();
                            $message_error = __('Plugin WordpressIntegration is disabled', 'moowp');
                            break;
                        case 'security_key_false':
                            $error_flag = true;
                            $this->update_connecting_error();
                            $message_error = __('Security Key is not correct', 'moowp');
                            if(!empty($this->moosocial_recovery_key)){
                                $is_confirm_update_community_security_key = true;
                            }else{
                                $message_error = __('Security Key is not correct', 'moowp');
                                $is_update_community_security_key = true;
                            }
                            break;
                        case 'connected_success':
                            if($isAdminWP){
                                $is_access_community_panel = true;
                            }
                            $this->update_connecting_ok();
                            $message_ok = __('mooSocial Site is connected', 'moowp');
                            break;
                        default:
                            $error_flag = true;
                            $this->update_connecting_error();
                            $message_error = __('Settings error!', 'moowp');
                            break;
                    }
                }else{
                    $error_flag = true;
                    $this->update_connecting_error();
                    $message_error = __('Connection to mooSocial site failed!', 'moowp');
                }
            }else{
                if(empty($this->moosocial_address_url)){
                    $error_flag = true;
                    $this->update_connecting_error();
                    $message_error = __('"Your mooSocial website URL" field is not empty!', 'moowp');
                }elseif (empty($this->moosocial_security_key)){
                    $error_flag = true;
                    $this->update_connecting_error();
                    $message_error = __('Please click "Generate Security Key" button and click "Save Changes" button.', 'moowp');
                }else{
                    $error_flag = true;
                    $this->update_connecting_error();
                    $message_error = __('Settings error!', 'moowp');
                }
            }
        }else{
            if(!empty($this->moosocial_security_key)){
                $error_flag = true;
                $this->update_connecting_error();
                $message_error = __('Please setup mooSocial Site', 'moowp');

                $debug_code = 11;
            }else{
                $error_flag = true;
                $this->update_connecting_error();
                $message_error = __('Please click "Generate Security Key" button and click "Save Changes" button.', 'moowp');

                $debug_code = 12;
            }
        }

        include MOOWP_PLUGIN_DIR . 'admin/form_fields/moosocial_toolbar_field.php';
    }

    /**
     * Render the number input for this plugin
     *
     * @since  1.0.0
     * @access public
     */
    public static function moowp_setting_number_cb() {
        include MOOWP_PLUGIN_DIR . 'admin/form_fields/number_field.php';
    }

    /**
     * Render the radio input field for boolean option
     *
     * @since  1.0.0
     * @access public
     */
    public static function moowp_setting_radio_bool_field() {
        include MOOWP_PLUGIN_DIR . 'admin/form_fields/radio_field.php';
    }
}
?>