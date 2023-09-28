<?php
class MooWP_Request extends MooWP_App {
    private $initiated = false;

    public function init() {
        if ( ! $this->initiated ) {
            //add_action( 'init', array($this, 'init_request'));
            add_action( 'rest_api_init', array( $this, 'register_routes' ) );
        }
    }

    public function register_routes() {
        $this->initiated = true;

        //http://wpsocial.local.com/wp-json/moosocial/site_online/status/123456
        /*register_rest_route( 'moosocial/site_online', '/status'. '/(?P<time>[a-zA-Z0-9-]+)', array(
            array(
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => array($this, 'do_check_moo_status' ),
                'permission_callback' => array($this, 'get_permissions_check' ),
            )
        ) );*/

        //http://wpsocial.local.com/wp-json/moosocial/notifications
        register_rest_route( 'moosocial/notifications', '/all', array(
            array(
                'methods'             => WP_REST_Server::CREATABLE,
                'callback'            => array($this, 'get_notifications' ),
                'permission_callback' => array($this, 'get_permissions_check' ),
            )
        ) );

        //http://wpsocial.local.com/wp-json/moosocial/v1/conversations
        register_rest_route( 'moosocial/conversations', '/all', array(
            array(
                'methods'             => WP_REST_Server::CREATABLE,
                'callback'            => array($this, 'get_conversations' ),
                'permission_callback' => array($this, 'get_permissions_check' ),
            )
        ) );

        register_rest_route('moosocial/notifications', '/mark_read', array(
            'methods'             => WP_REST_Server::CREATABLE,
            'callback'            => array($this, 'do_notification_mark_read' ),
            'permission_callback' => array($this, 'get_permissions_check' ),
        ));

        register_rest_route('moosocial/notifications', '/remove', array(
            'methods'             => WP_REST_Server::CREATABLE,
            'callback'            => array($this, 'do_notification_remove' ),
            'permission_callback' => array($this, 'get_permissions_check' ),
        ));

        register_rest_route( 'moosocial/notifications', '/clear_all_notifications', array(
            array(
                'methods'             => WP_REST_Server::CREATABLE,
                'callback'            => array($this, 'do_notification_clear_all' ),
                'permission_callback' => array($this, 'get_permissions_check' ),
            )
        ) );

        register_rest_route( 'moosocial/notifications', '/mark_all_read', array(
            array(
                'methods'             => WP_REST_Server::CREATABLE,
                'callback'            => array($this, 'do_notification_mark_all_read' ),
                'permission_callback' => array($this, 'get_permissions_check' ),
            )
        ) );

        register_rest_route( 'moosocial/notifications', '/refresh', array(
            array(
                'methods'             => WP_REST_Server::CREATABLE,
                'callback'            => array($this, 'get_notifications_refresh' ),
                'permission_callback' => array($this, 'get_permissions_check' ),
            )
        ) );

        register_rest_route( 'moosocial/user', '/sync_user'. '/(?P<moo_user_key>[a-zA-Z0-9-]+)', array(
            array(
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => array($this, 'do_sync_user_wp_to_moo' ),
                'permission_callback' => array($this, 'get_permissions_check' ),
            )
        ) );

        //http://wpsocial.local.com/wp-json/moosocial/user/admin_login_verification
        register_rest_route( 'moosocial/user', '/admin_login_verification', array(
            array(
                'methods'             => WP_REST_Server::CREATABLE,
                'callback'            => array($this, 'do_admin_login_verification' ),
                'permission_callback' => array($this, 'get_permissions_check' ),
            )
        ) );

        //http://wpsolution.local.com/wp-json/moosocial/user/admin_check_install_moo
        register_rest_route( 'moosocial/user', '/admin_check_install_moo', array(
            array(
                'methods'             => WP_REST_Server::CREATABLE,
                'callback'            => array($this, 'do_check_install_moo' ),
                'permission_callback' => array($this, 'get_permissions_check' ),
            )
        ) );

        //http://wpsolution.local.com/wp-json/moosocial/security/admin_confirm_update_security_key
        register_rest_route( 'moosocial/security', '/admin_confirm_update_security_key', array(
            array(
                'methods'             => WP_REST_Server::CREATABLE,
                'callback'            => array($this, 'do_confirm_update_security_key' ),
                'permission_callback' => array($this, 'get_permissions_check' ),
            )
        ) );

        /*register_rest_route( 'moosocial/user', '/check_sync'. '/(?P<moo_user_key>[a-zA-Z0-9-]+)', array(
            array(
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => array($this, 'do_user_check_sync' ),
                'permission_callback' => array($this, 'get_permissions_check' ),
            )
        ) );*/

        //http://wpsocial.local.com/wp-json/moosocial/user/generate_key
        /*register_rest_route( 'moosocial/user', '/generate_key'. '/(?P<user_id>[a-zA-Z0-9-]+)', array(
            array(
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => array($this, 'do_user_generate_moo_key' ),
                'permission_callback' => array($this, 'get_permissions_check' ),
            )
        ) );*/

        //https://wordpress.moosocial.com/wp-json/moosocial/test/test
        register_rest_route( 'moosocial/test', '/test', array(
            array(
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => array($this, 'test_test' ),
            )
        ) );

    }

    /*public function do_check_moo_status($request){
        $params = $request->get_params();
        $time = $params['time'];

        $result = array(
            'success' => false,
            'messages' => '',
            'code' => 0,
            'data' => array(),
        );

        $url = $this->moosocial_address_url . '/install/get_status';

        $result_data = $this->curl_get($url, 'json');

        if($result_data == null){
            $result['success'] = false;
            $result['messages'] = __('mooSocial Site not found', 'moowp');
            $result['code'] = 0;
        }else{
            if(!empty($result_data) && $result_data['success'] == true){
                $result['success'] = true;
                $result['messages'] = $result_data['messages'];
                $result['code'] = $result_data['code'];
                if($result_data['code'] == 1){
                    $result['messages'] = __('mooSocial is not installed', 'moowp');
                }elseif ($result_data['code'] == 2){
                    $result['messages'] = __('mooSocial is installed', 'moowp');
                }elseif ($result_data['code'] == 3){
                    $result['messages'] = __('Plugin WordpressIntegration is disabled', 'moowp');
                }
            }
        }

        wp_reset_postdata();
        return new WP_REST_Response( $result, 200 );
    }*/

    public function get_permissions_check( $request ) {
        $params = $request->get_params();

        return true;
        /*if ((int)$params['userID']!==(int)$GLOBALS['user_id']) {
            return new \WP_Error( 'rest_forbidden', __('Sorry, you are not allowed to do that.', 'moowp'), ['status' => 401] );
        }else{
            return true;
        }*/
    }

    public function do_notification_mark_read($request){
        $params = $request->get_params();
        $user_id = $params['user_id'];
        $status = $params['status'];
        $notification_id = $params['notification_id'];

        $result = array(
            'success' => true,
            'messages' => '',
            'data' => $params,
        );

        $user_check = get_userdata($user_id);

        if(!empty($user_check)){
            $moo_user_key = $this->_generate_moo_key($user_id);

            $users = get_users(
                array(
                    'meta_key' => 'moo_user_key',
                    'meta_value' => $moo_user_key
                )
            );

            if(!empty($users)){
                $user_info = $users[0];

                $url = $this->moosocial_address_url.'/wordpress_integrations/api/mark_read';

                $post = [
                    'security_key' => $this->moosocial_security_key,
                    'id'   => $user_info->ID,
                    'user_key'   => $user_info->moo_user_key,
                    'email' => $user_info->user_email,
                    'username' => $user_info->user_login,
                    'firstname'   => $user_info->user_firstname,
                    'lastname'   => $user_info->user_lastname,
                    'status' => $status,
                    'notification_id' => $notification_id,
                ];

                $result_data = $this->curl_post($url, $post, 'json');

                if($result_data['success'] == true){
                    $result['success'] = true;
                    $result['messages'] = 'true';
                    $result['data'] = $result_data['data'];
                }else{
                    $result['success'] = false;
                    $result['messages'] = 'false';
                }
            }else{
                $result['success'] = false;
                $result['messages'] = 'false';
            }
        }else{
            $result['success'] = false;
            $result['messages'] = 'false';
        }

        return new WP_REST_Response( $result, 200 );
    }

    public function do_notification_remove($request){
        $params = $request->get_params();
        $user_id = $params['user_id'];
        $notification_id = $params['notification_id'];

        $result = array(
            'success' => true,
            'messages' => '',
            'data' => $params,
        );

        $user_check = get_userdata($user_id);

        if(!empty($user_check)){
            $moo_user_key = $this->_generate_moo_key($user_id);

            $users = get_users(
                array(
                    'meta_key' => 'moo_user_key',
                    'meta_value' => $moo_user_key
                )
            );

            if(!empty($users)){
                $user_info = $users[0];

                $url = $this->moosocial_address_url.'/wordpress_integrations/api/notification_remove';

                $post = [
                    'security_key' => $this->moosocial_security_key,
                    'id'   => $user_info->ID,
                    'user_key'   => $user_info->moo_user_key,
                    'email' => $user_info->user_email,
                    'username' => $user_info->user_login,
                    'firstname'   => $user_info->user_firstname,
                    'lastname'   => $user_info->user_lastname,
                    'notification_id' => $notification_id,
                ];

                $result_data = $this->curl_post($url, $post, 'json');

                if($result_data['success'] == true){
                    $result['success'] = true;
                    $result['messages'] = 'true';
                    $result['data'] = $result_data['data'];
                }else{
                    $result['success'] = false;
                    $result['messages'] = 'false';
                }
            }else{
                $result['success'] = false;
                $result['messages'] = 'false';
            }
        }else{
            $result['success'] = false;
            $result['messages'] = 'false';
        }

        return new WP_REST_Response( $result, 200 );
    }

    public function do_notification_mark_all_read($request){
        $params = $request->get_params();
        $user_id = $params['user_id'];

        $result = array(
            'success' => true,
            'messages' => '',
            'data' => array(),
        );

        $user_check = get_userdata($user_id);

        if(!empty($user_check)){
            $moo_user_key = $this->_generate_moo_key($user_id);

            $users = get_users(
                array(
                    'meta_key' => 'moo_user_key',
                    'meta_value' => $moo_user_key
                )
            );

            if(!empty($users)){
                $user_info = $users[0];

                $url = $this->moosocial_address_url.'/wordpress_integrations/api/mark_all_read';

                $post = [
                    'security_key' => $this->moosocial_security_key,
                    'id'   => $user_info->ID,
                    'user_key'   => $user_info->moo_user_key,
                    'email' => $user_info->user_email,
                    'username' => $user_info->user_login,
                    'firstname'   => $user_info->user_firstname,
                    'lastname'   => $user_info->user_lastname
                ];

                $result_data = $this->curl_post($url, $post, 'json');

                if($result_data['success'] == true){
                    $result['success'] = true;
                    $result['messages'] = 'true';
                }else{
                    $result['success'] = false;
                    $result['messages'] = 'false';
                }
            }else{
                $result['success'] = false;
                $result['messages'] = 'false';
            }
        }else{
            $result['success'] = false;
            $result['messages'] = 'false';
        }

        return new WP_REST_Response( $result, 200 );
    }

    public function do_notification_clear_all($request){
        $params = $request->get_params();
        $user_id = $params['user_id'];

        $result = array(
            'success' => true,
            'messages' => '',
            'data' => array(),
        );

        $user_check = get_userdata($user_id);

        if(!empty($user_check)){
            $moo_user_key = $this->_generate_moo_key($user_id);

            $users = get_users(
                array(
                    'meta_key' => 'moo_user_key',
                    'meta_value' => $moo_user_key
                )
            );

            if(!empty($users)){
                $user_info = $users[0];

                $url = $this->moosocial_address_url.'/wordpress_integrations/api/clear_all_notifications';

                $post = [
                    'security_key' => $this->moosocial_security_key,
                    'id'   => $user_info->ID,
                    'user_key'   => $user_info->moo_user_key,
                    'email' => $user_info->user_email,
                    'username' => $user_info->user_login,
                    'firstname'   => $user_info->user_firstname,
                    'lastname'   => $user_info->user_lastname
                ];

                $result_data = $this->curl_post($url, $post, 'json');

                if($result_data['success'] == true){
                    $result['success'] = true;
                    $result['messages'] = 'true';
                }else{
                    $result['success'] = false;
                    $result['messages'] = 'false';
                }
            }else{
                $result['success'] = false;
                $result['messages'] = 'false';
            }
        }else{
            $result['success'] = false;
            $result['messages'] = 'false';
        }

        return new WP_REST_Response( $result, 200 );
    }

    public function get_notifications_refresh($request){
        $params = $request->get_params();
        $user_id = $params['user_id'];

        $result = array(
            'success' => true,
            'messages' => '',
            'code' => 0,
            'data' => array(),
        );

        $user_check = get_userdata($user_id);

        if(!empty($user_check)){
            $moo_user_key = $this->_generate_moo_key($user_id);

            $users = get_users(
                array(
                    'meta_key' => 'moo_user_key',
                    'meta_value' => $moo_user_key
                )
            );

            if(!empty($users)){
                $user_info = $users[0];

                $url = $this->moosocial_address_url.'/wordpress_integrations/api/notifications_refresh';

                $post = [
                    'security_key' => $this->moosocial_security_key,
                    'id'   => $user_info->ID,
                    'user_key'   => $user_info->moo_user_key,
                    'email' => $user_info->user_email,
                    'username' => $user_info->user_login,
                    'firstname'   => $user_info->user_firstname,
                    'lastname'   => $user_info->user_lastname
                ];

                $result_data = $this->curl_post($url, $post, 'json');

                if($result_data['success'] == true){
                    $result['success'] = true;
                    $result['messages'] = 'true';
                    $result['data'] = $result_data['data'];
                }else{
                    $result['success'] = false;
                    $result['messages'] = $result_data['messages'];
                    $result['code'] = $result_data['code'];
                }
            }else{
                $result['success'] = false;
                $result['messages'] = 'false';
            }
        }else{
            $result['success'] = false;
            $result['messages'] = 'false';
        }

        return new WP_REST_Response( $result, 200 );
    }

    public function get_notifications($request){
        $params = $request->get_params();
        $user_id = $params['user_id'];

        $result = array(
            'success' => true,
            'messages' => '',
            'data' => array(),
        );

        $user_check = get_userdata($user_id);

        if(!empty($user_check)){
            $moo_user_key = $this->_generate_moo_key($user_id);

            $users = get_users(
                array(
                    'meta_key' => 'moo_user_key',
                    'meta_value' => $moo_user_key
                )
            );

            if(!empty($users)){
                $user_info = $users[0];

                $url = $this->moosocial_address_url.'/wordpress_integrations/api/html_notifications';

                $post = [
                    'security_key' => $this->moosocial_security_key,
                    'id'   => $user_info->ID,
                    'user_key'   => $user_info->moo_user_key,
                    'email' => $user_info->user_email,
                    'username' => $user_info->user_login,
                    'firstname'   => $user_info->user_firstname,
                    'lastname'   => $user_info->user_lastname
                ];

                $result_data = $this->curl_post($url, $post, 'html');

                $result['success'] = true;
                $result['messages'] = 'true';
                $result['data'] = $result_data;
            }else{
                $result['success'] = false;
                $result['messages'] = 'false';
            }
        }else{
            $result['success'] = false;
            $result['messages'] = 'false';
        }

        return new WP_REST_Response( $result, 200 );
    }

    public function get_conversations($request){
        $params = $request->get_params();
        $user_id = $params['user_id'];

        $result = array(
            'success' => true,
            'messages' => '',
            'data' => array(),
        );

        $user_check = get_userdata($user_id);

        if(!empty($user_check)){
            $moo_user_key = $this->_generate_moo_key($user_id);

            $users = get_users(
                array(
                    'meta_key' => 'moo_user_key',
                    'meta_value' => $moo_user_key
                )
            );

            if(!empty($users)){
                $user_info = $users[0];

                $url = $this->moosocial_address_url.'/wordpress_integrations/api/html_conversations';

                $post = [
                    'security_key' => $this->moosocial_security_key,
                    'id'   => $user_info->ID,
                    'user_key'   => $user_info->moo_user_key,
                    'email' => $user_info->user_email,
                    'username' => $user_info->user_login,
                    'firstname'   => $user_info->user_firstname,
                    'lastname'   => $user_info->user_lastname
                ];

                $result_data = $this->curl_post($url, $post, 'html');

                $result['success'] = true;
                $result['messages'] = 'true';
                $result['data'] = $result_data;
            }else{
                $result['success'] = false;
                $result['messages'] = 'false';
            }
        }else{
            $result['success'] = false;
            $result['messages'] = 'false';
        }

        return new WP_REST_Response( $result, 200 );
    }

    public function do_user_generate_moo_key($request){
        $params = $request->get_params();
        $user_id = $params['user_id'];

        $result = array(
            'success' => false,
            'messages' => '',
            'data' => array(),
        );

        $user_check = get_userdata($user_id);

        if(!empty($user_check)){
            $moo_user_key = $this->_generate_moo_key($user_id);

            $users = get_users(
                array(
                    'meta_key' => 'moo_user_key',
                    'meta_value' => $moo_user_key
                )
            );

            if(!empty($users)){
                $user_info = $users[0];

                $url = $this->moosocial_address_url.'/wordpress_integrations/api/create_user';

                $post = [
                    'security_key' => $this->moosocial_security_key,
                    'id'   => $user_info->ID,
                    'user_key'   => $user_info->moo_user_key,
                    'email' => $user_info->user_email,
                    'username' => $user_info->user_login,
                    'firstname'   => $user_info->user_firstname,
                    'lastname'   => $user_info->user_lastname
                ];
                $result_data = $this->curl_post($url, $post, 'json');

                if($result_data['success'] == true){
                    $result['success'] = true;
                    $result['messages'] = 'true';
                    $result['data'] = $result_data['data'];
                }else{
                    $result['success'] = false;
                    $result['messages'] = 'false';
                }
            }else{
                $result['success'] = false;
                $result['messages'] = 'false';
            }
        }else{
            $result['success'] = false;
            $result['messages'] = 'false';
        }

        /* Restore original Post Data */
        wp_reset_postdata();

        return new WP_REST_Response( $result, 200 );
    }

    /*public function do_user_check_sync($request){
        $params = $request->get_params();
        $moo_user_key = $params['moo_user_key'];

        $result = array(
            'success' => true,
            'messages' => '',
            'data' => array(),
        );

        $users = get_users(
            array(
                'meta_key' => 'moo_user_key',
                'meta_value' => $moo_user_key
            )
        );

        if(!empty($users)) {
            $user_info = $users[0];
            $url = $this->moosocial_address_url . '/wordpress_integrations/api/get_user';
            $post = [
                'security_key' => $this->moosocial_security_key,
                'id'   => $user_info->ID,
                'user_key'   => $user_info->moo_user_key,
                'email' => $user_info->user_email,
                'username' => $user_info->user_login,
                'firstname' => $user_info->user_firstname,
                'lastname' => $user_info->user_lastname
            ];

            $result_data = $this->curl_post($url, $post, 'json');

            if($result_data['success'] == true){
                $result['success'] = true;
                $result['messages'] = 'true';
                $result['data'] = $result_data['data'];
            }else{
                $result['success'] = false;
                $result['messages'] = 'false';
            }
        }else{
            $result['success'] = false;
            $result['messages'] = 'false';
        }

                wp_reset_postdata();
        return new WP_REST_Response( $result, 200 );
    }*/


    public function do_sync_user_wp_to_moo($request){
        $params = $request->get_params();
        $moo_user_key = $params['moo_user_key'];

        $result = array(
            'success' => false,
            'messages' => '',
            'data' => array(),
        );

        $users = get_users(
            array(
                'meta_key' => 'moo_user_key',
                'meta_value' => $moo_user_key
            )
        );

        if(!empty($users)){
            $user_info = $users[0];

            $url = $this->moosocial_address_url.'/wordpress_integrations/api/create_user';

            $post = [
                'security_key' => $this->moosocial_security_key,
                'roles' => json_encode($user_info->roles),
                'id'   => $user_info->ID,
                'user_key'   => $user_info->moo_user_key,
                'email' => $user_info->user_email,
                'username' => $user_info->user_login,
                'firstname'   => $user_info->user_firstname,
                'lastname'   => $user_info->user_lastname
            ];
            $result_data = $this->curl_post($url, $post, 'json');

            if($result_data['success'] == true){
                $result['success'] = true;
                $result['messages'] = 'true';
                $result['data'] = $result_data['data'];
            }else{
                $result['messages'] = $result_data['messages'];
            }
        }else{
            $result['messages'] = 'User not found!';
        }

        wp_reset_postdata();

        return new WP_REST_Response( $result, 200 );
    }

    public function do_admin_login_verification($request){
        $params = $request->get_params();
        $user_id = $params['user_id'];
        $moo_login_as = $params['moo_login_as'];

        $result = array(
            'success' => false,
            'messages' => '',
            'data' => array(),
        );

        $user_check = get_userdata($user_id);
        if(!empty($user_check)){
            $moo_user_key = $this->_generate_moo_key($user_id);

            $users = get_users(
                array(
                    'meta_key' => 'moo_user_key',
                    'meta_value' => $moo_user_key
                )
            );

            if(!empty($users)){
                $user_info = $users[0];

                $isAdminWP = in_array('administrator', $user_info->roles, true);

                if($isAdminWP){
                    $url = $this->moosocial_address_url.'/wordpress_integrations/api/login_admin_verification';

                    $post = [
                        'security_key' => $this->moosocial_security_key,
                        'id'   => $user_info->ID,
                        'user_key'   => $user_info->moo_user_key,
                        'moo_login_as' => $moo_login_as,
                        'email' => $user_info->user_email,
                        'username' => $user_info->user_login,
                        'firstname'   => $user_info->user_firstname,
                        'lastname'   => $user_info->user_lastname,
                    ];
                    $result_data = $this->curl_post($url, $post, 'json');

                    if($result_data['success'] == true){
                        $result['success'] = true;
                        $result['messages'] = $result_data['messages'];
                        $result['data'] = $result_data['data'];
                        /*$admin_login_token = $moo_login_as.'|'.$result_data['data']['admin_login_token'];
                        $expiration = 2*60;
                        $secure = ( 'https' === parse_url( wp_login_url(), PHP_URL_SCHEME ) );
                        if ( COOKIEPATH != SITECOOKIEPATH ) {
                            setcookie(LOGGED_ADMIN_COOKIE, $admin_login_token, $expiration, SITECOOKIEPATH, COOKIE_DOMAIN, $secure);
                        }else{
                            setcookie(LOGGED_ADMIN_COOKIE, $admin_login_token, $expiration, COOKIEPATH, COOKIE_DOMAIN, $secure);
                        }*/
                    }else{
                        $result['success'] = false;
                        $result['messages'] = $result_data['messages'];
                    }
                }
            }
        }

        wp_reset_postdata();

        return new WP_REST_Response( $result, 200 );
    }

    public function do_check_install_moo($request){
        $params = $request->get_params();
        $user_id = $params['user_id'];
        $user_check = get_userdata($user_id);

        $result = array(
            'success' => false,
            'messages' => '',
            'data' => array(),
        );

        if(!empty($user_check)){
            $isAdminWP = in_array('administrator', $user_check->roles, true);
            if($isAdminWP){
                update_option(self::$option_name.'_user_map_root', $user_check->ID);

                $result['success'] = true;
            }
        }

        wp_reset_postdata();

        return new WP_REST_Response( $result, 200 );
    }

    public function do_confirm_update_security_key($request){
        $params = $request->get_params();
        $recovery_key = $params['recovery_key'];

        $result = array(
            'success' => false,
            'messages' => '',
            'data' => array(),
        );

        if(!empty($recovery_key) && $recovery_key == $this->moosocial_recovery_key){
            $url = $this->moosocial_address_url.'/wordpress_integrations/api/confirm_update_security_key';
            $post = [
                'security_key' => $this->moosocial_security_key,
                'recovery_key'   => $this->moosocial_recovery_key,
            ];
            $result_data = $this->curl_post($url, $post, 'json');

            if(isset($result_data['success']) && $result_data['success'] == true){
                update_option(self::$option_name . '_recovery_key', '');
                $result['success'] = true;
            }
        }

        wp_reset_postdata();

        return new WP_REST_Response( $result, 200 );
    }

    public function test_test(){
        wp_reset_postdata();
        $result = $_COOKIE;
        return new WP_REST_Response( $result, 200 );
        /*
        $user_id = 1;
        $moo_login_as = 'root_admin';

        $result = array(
            'success' => false,
            'messages' => '',
            'data' => array(),
        );

        $user_check = get_userdata($user_id);
        if(!empty($user_check)){
            $moo_user_key = $this->_generate_moo_key($user_id);

            $users = get_users(
                array(
                    'meta_key' => 'moo_user_key',
                    'meta_value' => $moo_user_key
                )
            );

            if(!empty($users)){
                $user_info = $users[0];

                $isAdminWP = in_array('administrator', $user_info->roles, true);

                if($isAdminWP){
                    $url = $this->moosocial_address_url.'/wordpress_integrations/api/login_admin_verification';

                    $post = [
                        'security_key' => $this->moosocial_security_key,
                        'id'   => $user_info->ID,
                        'user_key'   => $user_info->moo_user_key,
                        'moo_login_as' => $moo_login_as,
                        'email' => $user_info->user_email,
                        'username' => $user_info->user_login,
                        'firstname'   => $user_info->user_firstname,
                        'lastname'   => $user_info->user_lastname,
                    ];
                    $result_data = $this->curl_post($url, $post, 'json');

                    if($result_data['success'] == true){
                        $result['success'] = true;
                        $result['messages'] = $result_data['messages'];
                        $result['data'] = $result_data['data'];
                    }else{
                        $result['success'] = false;
                        $result['messages'] = $result_data['messages'];
                    }
                }
            }
        }

        var_dump($result);
        //wp_reset_postdata();

        //return new WP_REST_Response( $result, 200 );
        */
    }
}
?>