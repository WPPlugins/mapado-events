<?php

/**
 * Class MapadoPrivateAuth
 * For admin area
 */
class MapadoPrivateAuth extends MapadoPlugin
{
    private $auth;
    private $token;
    private $param_list = array('widget', 'perpage', 'card_thumb_position', 'card_thumb_orientation', 'card_thumb_size', 'card_column_max', 'list_sort', 'past_events', 'list_depth', 'display_map', 'card_template', 'full_template', 'display_search');

    public function __construct()
    {
        $this->setAuth();
        $this->setToken();
        $this->setDatas();

        add_action('admin_menu', array($this, 'adminMenu'));

        add_action('wp_ajax_ajaxGetUserLists', array($this, 'ajaxGetUserLists'));
        add_action('wp_ajax_ajaxUpdateListSettings', array($this, 'ajaxUpdateListSettings'));

        add_action('admin_enqueue_scripts', array($this, 'enqueueScriptsStyle'));

        add_action('admin_init', array($this, 'pluginCheck'));
        add_action('mapado_settings_api', array($this, 'adminSettingsApi'));

        /* Plugin settings link */
        add_filter('plugin_action_links_' . $this->plugin_basename, array($this, 'settingsPluginLink'), 10, 2);
    }


    /**
     * Get the private auth key from WP settings
     */
    private function setAuth()
    {
        $this->auth = get_option(parent::AUTH_WP_INDEX);
    }

    /**
     * Generate token from auth key
     */
    private function setToken()
    {
        $this->token = new \Mapado\Sdk\Model\AccessToken();
        $this->token->setAccessToken($this->auth);
    }


    /**
     * Enqueue JS & CSS files in mapado admin settings page
     */
    public function enqueueScriptsStyle($hook)
    {
        /* If not mapado settings page */
        if ($hook != 'settings_page_mapado_settings') {
            return;
        }

        wp_enqueue_script('jquery');
        wp_enqueue_script('mapado_admin_script', MAPADO_PLUGIN_URL . 'assets/admin.js', '0.1.6');

        wp_register_style('mapado_admin_css', MAPADO_PLUGIN_URL . 'assets/admin.css', false, '0.1.6');
        wp_enqueue_style('mapado_admin_css');
    }

    /**
     * Check plugin version
     */
    public function pluginCheck()
    {
        $plugin_datas = get_plugin_data(plugin_dir_path(dirname(__FILE__)) . 'mapado.php');

        /* Force flush rewrite if the plugin has been updated */
        if (isset($this->settings['mapado_version']) && $plugin_datas['Version'] != $this->settings['mapado_version']) {
            flush_rewrite_rules();

            /* Update last plugin version on this site */
            $this->settings['mapado_version'] = $plugin_datas['Version'];
            $this->settings->update();
        }
    }

    /**
     * Adding mapado in admin settings menu
     */
    public function adminMenu()
    {
        add_submenu_page(
            'options-general.php',
            'Mapado plugin',
            'Paramètres mapado',
            'manage_options',
            'mapado_settings',
            array($this, 'adminSettings')
        );
    }

    /**
     * Admin settings page
     */
    public function adminSettings()
    {
        $hasApi = !empty($this->api['id']) && !empty($this->api['secret']) && !empty($this->auth);

        // Asked step
        $stepList = ['api', 'imports', 'options'];
        $step = 'options';
        if (isset($_GET['step']) && in_array($_GET['step'], $stepList) !== false) {
            $step = $_GET['step'];
        }

        // Step redirect
        if ($step == 'api') {
            return $this->adminSettingsApi();
        }
        if (!$hasApi) {
            MapadoNotification::error('Vous n\'avez pas renseigné vous réglages API');
            return $this->adminSettingsApi();
        }
        if ($step == 'imports') {
            return $this->adminSettingsImports();
        }
        if (!$this->imported_lists) {
            MapadoNotification::error('Vous n\'avez pas encore importé de liste');
            return $this->adminSettingsImports();
        }
        return $this->adminSettingsOptions();
    }

    public function adminSettingsApi()
    {

        /* API Settings submit */
        if (!empty($_POST['mapado_settings_submit'])) {
            $api = $auth = true;

            $api_settings = array(
                'id' => $_POST['mapado_api_id'],
                'secret' => $_POST['mapado_api_secret']
            );

            /* Check if API settings have been changed */
            if (empty($this->api) || (!empty($this->api) && ($this->api['id'] != $_POST['mapado_api_id'] || $this->api['secret'] != $_POST['mapado_api_secret']))) {
                $api = update_option(parent::API_WP_INDEX, $api_settings);
            }

            /* Check if auth key have been changed */
            if (!isset($this->auth) || (isset($this->auth) && $this->auth != $_POST['mapado_api_auth'])) {
                $auth = update_option(parent::AUTH_WP_INDEX, $_POST['mapado_api_auth']);
            }

            /* Refresh access, auth & token */
            $this->setAccess();
            $this->setAuth();
            $this->setToken();

            /* Something went wrong */
            if (!$api || !$auth) {
                MapadoNotification::error('Il y a eu un problème');

                /* Success */
            } else {
                MapadoNotification::success('Réglages enregistrés');
                if ($this->api['id'] && $this->api['secret'] && $this->auth) {
                    return $this->adminSettingRedirect('imports');
                }
            }
        }

        return $this->renderAdminSetting('api');
    }

    public function adminSettingsImports()
    {
        if (!empty($_POST['mapado_settings_submit'])) {
            return $this->adminSettingRedirect('options');
        }
        return $this->renderAdminSetting('imports');
    }

    public function adminSettingsOptions()
    {

        /* Additional settings page submit */
        if (!empty($_POST['mapado_settings_submit'])) {
            $upToDate = true;

            foreach ($this->param_list as $param) {
                $newValue = stripslashes($_POST['mapado_' . $param]);
                if ($this->settings[$param] != $newValue) {
                    if (empty($newValue)) {
                        unset($this->settings[$param]);
                        $upToDate = false;
                    } else {
                        $this->settings[$param] = stripslashes($_POST['mapado_' . $param]);
                        $upToDate = false;
                    }
                }
            }
            if (!$upToDate) {
                $upToDate = $this->settings->update();
            }

            /* Something went wrong */
            if (!$upToDate) {
                MapadoNotification::error('Il y a eu un problème');

            /* Success */
            } else {
                MapadoNotification::success('Paramètres enregistrés');
            }
        }

        $vars = array();
        foreach ($this->param_list as $param) {
            $vars[$param] = $this->settings->getDefinition($param);
        }
        $vars['imported_lists'] = $this->getImportedLists();

        return $this->renderAdminSetting('options', $vars);
    }

    protected function renderAdminSetting($step, $extraVars = array())
    {
        if (isset($_GET['noheader'])) {
            require_once(ABSPATH . 'wp-admin/admin-header.php');
        }
        $hasApi = !empty($this->api['id']) && !empty($this->api['secret']) && !empty($this->auth);
        $hasImportedLists = $hasApi && $this->imported_lists;
        $vars = array(
            'step' => $step,
            'notification_list' => MapadoNotification::pull(),
            'settings' => $this->settings,
            'api' => $this->api,
            'auth' => $this->auth,
            'has_api' => $hasApi,
            'has_imported_lists' => $hasImportedLists
        );
        $vars = array_merge($extraVars, $vars);

        MapadoUtils::template('admin/settings', $vars);
        return true;
    }

    protected function adminSettingRedirect($step)
    {
        wp_redirect(admin_url('options-general.php?page=mapado_settings&step=' . $step));
        return true;
    }

    /**
     * AJAX
     * Get user lists
     */
    public function ajaxGetUserLists()
    {
        $userUuid = $this->getUser($this->token)->getUuid();
        $user_lists = $this->getClient($this->token)->userList->findByUserUuid($userUuid);

        MapadoUtils::template('admin/user_lists', array(
            'user_lists' => $user_lists,
            'imported_lists' => $this->imported_lists
        ));

        exit;
    }

    protected function getImportedLists()
    {
        $importedLists = [];
        $userUuid = $this->getUser($this->token)->getUuid();
        $userLists = $this->getClient($this->token)->userList->findByUserUuid($userUuid);
        foreach ($userLists as $list) {
            $uuid = $list->getUuid();
            if (isset($this->imported_lists[$uuid])) {
                $importedList = [];
                $importedList['slug'] = $this->imported_lists[$uuid];
                $importedList['title'] = $list->getTitle();
                $importedLists[] = $importedList;
            }
        }
        return $importedLists;
    }

    /**
     * AJAX
     * Save a user list settings
     */
    public function ajaxUpdateListSettings()
    {
        global $wpdb;

        if (empty($this->imported_lists)) {
            $this->imported_lists = array();
        }

        /* Add a list */
        if ($_POST['mapado_action'] == 'import') {

            /* Slugify list slug */
            $slug = sanitize_title($_POST['slug']);

            /* Check if slug already exist */
            if ($wpdb->get_row("SELECT post_name FROM " . $wpdb->prefix . "posts WHERE post_name = '" . $slug . "'", 'ARRAY_A')) {
                echo json_encode(array(
                    'state' => 'error',
                    'msg' => "L'identifiant est déjà utilisé"
                ));

                exit;
            }

            $page = wp_insert_post(array(
                'post_title' => $_POST['title'],
                'post_name' => $slug,
                'post_content' => '[mapado_list]',
                'post_status' => 'publish',
                'post_type' => 'page',
                'post_author' => 1,
                'comment_status' => 'closed'
            ), false);

            if (empty($page)) {
                echo json_encode(array(
                    'state' => 'error',
                    'msg' => 'Problème lors de la création'
                ));
            } else {
                $this->imported_lists[$_POST['uuid']] = $slug;
            }

            /* Delete a list & the associate page */
        } elseif ($_POST['mapado_action'] == 'delete') {
            $page = get_page_by_path($_POST['slug']);
            wp_delete_post($page->ID, true);

            unset($this->imported_lists[$_POST['uuid']]);
        }

        /* Something went wrong */
        if (!update_option('mapado_user_lists', $this->imported_lists)) {
            echo json_encode(array(
                'state' => 'error',
                'msg' => 'Erreur lors de la mise à jour'
            ));

            /* Success */
        } else {
            $this->registerRewriteRules();
            flush_rewrite_rules();
            echo json_encode(array(
                'state' => 'updated',
                'msg' => 'Listes mises à jour',
                'count' => count($this->imported_lists)
            ));
        }

        exit;
    }

    /**
     * Adding plugin settings link in extensions list
     * @param array of existing links
     * @param plugin basename
     * @return array of links updated
     */
    public function settingsPluginLink($links, $file)
    {
        array_unshift($links, '<a href="' . admin_url('options-general.php?page=mapado_settings') . '">Réglages</a>');

        return $links;
    }
}
