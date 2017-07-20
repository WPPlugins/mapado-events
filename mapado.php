<?php
/**
 * Plugin Name: Mapado for Wordpress
 * Plugin URI: https://www.mapado.com/
 * Description: Official Mapado plugin for Wordpress. Display lists of events curated on Mapado into your Wordpress blog.
 * Version: 0.2.40
 * Author: Mapado
 * Author URI:  https://www.mapado.com/
 * License: GPL2 license
 */
session_start();

define('MAPADO_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('MAPADO_PLUGIN_URL', plugin_dir_url(__FILE__));

require_once MAPADO_PLUGIN_PATH . 'vendor/autoload.php';

/* Require classes */
require_once MAPADO_PLUGIN_PATH . 'class/microtemplate.class.php';
require_once MAPADO_PLUGIN_PATH . 'class/notification.class.php';
require_once MAPADO_PLUGIN_PATH . 'class/private.auth.php';
require_once MAPADO_PLUGIN_PATH . 'class/public.auth.php';
require_once MAPADO_PLUGIN_PATH . 'class/setting.class.php';
require_once MAPADO_PLUGIN_PATH . 'class/utils.class.php';
require_once MAPADO_PLUGIN_PATH . 'class/widget.class.php';

class MapadoPlugin
{
    protected $api,
                $settings,
                $client,
                $plugin_basename;

    private $activityCache = [];
    public $imported_lists;

    /* Options names in settings array, stored in wp_options table */
    const API_WP_INDEX            = 'mapado_api';
    const SETTINGS_WP_INDEX    = 'mapado_settings';
    const AUTH_WP_INDEX        = 'mapado_settings_auth';
    const TOKEN_WP_INDEX        = 'mapado_token_cache';
    const USERLISTS_WP_INDEX    = 'mapado_user_lists';


    /**
     * Not used
     */
    public function __construct()
    {
    }

    /**
     * Utils function to get & set settings from WP DB
     */
    public function setDatas()
    {
        $this->plugin_basename    = plugin_basename(__FILE__);

        $this->setAccess();
        $this->setSettings();
        $this->setUserImportedLists();
        $this->initRewriteRules();
        $this->registerRewriteRules();

        add_action('widgets_init', array(&$this, 'initDetailWidget'));
        add_action('widgets_init', array(&$this, 'initEventListingWidget'));
    }

    /**
     * Get & set the API settings from WP DB
     */
    protected function setAccess()
    {
        $this->api    = get_option(self::API_WP_INDEX);
    }

    /**
     * Get & set the additionnal settings from WP DB
     */
    protected function setSettings()
    {
        $this->settings    = new MapadoSetting(self::SETTINGS_WP_INDEX);
    }

    /**
     * Get & set imported user lists from WP DB
     */
    protected function setUserImportedLists()
    {
        $this->imported_lists    = get_option(self::USERLISTS_WP_INDEX);
    }

    /**
     * Get the Client
     * @return object client
     */
    public function getClient($token = false, $forceGeneration = false)
    {
        if (empty($this->client) && empty($token)) {
            return false;
        } elseif (($forceGeneration === true || empty($this->client)) && !empty($token)) {
            $this->client    = \Mapado\Sdk\Client::createClient($token, $this->getLocale());
        }

        return $this->client;
    }

    /**
     * Get the User
     * @return object user
     */
    public function getUser($token)
    {
        if (empty($this->user) && empty($token)) {
            return false;
        } elseif (empty($this->user) && !empty($token)) {
            $this->user    = $this->getClient($token)->user->me();
        }

        return $this->user;
    }

    /**
     * Get activities
     * @param API parameters
     * @return activities list
     */
    private function getActivities($params = array())
    {
        if (!$this->getToken()) {
            return false;
        }
        if (!$this->getAPIKey()) {
            return false;
        }

        $client    = \Mapado\Sdk\Client::createClient($this->getToken(), $this->getLocale());

        return $client->activity->findBy($params);
    }

    /**
     * Get activity
     * @param activity uuid
     * @param token
     * @param array of image sizes
     * @return activity object
     */
    protected function getActivity($uuid, $token)
    {
        /* Check token validity */
        $parametersKeys = $uuid;
        if (empty($this->activityCache[$parametersKeys])) {
            if (!$client = $this->getClient($token)) {
                return false;
            }
            $this->activityCache[$parametersKeys] = $client->activity->findOne($uuid, array('image_sizes' => array('700x250')));
        }
        return $this->activityCache[$parametersKeys];
    }

    /**
     * Get the locale lang
     * @return object client
     */
    public function getLocale()
    {
        /* Get locale language */
        $lang    = 'fr';
        $locale    = substr(get_locale(), 0, 2);

        if (!empty($locale)) {
            $lang    = $locale;
        }

        return $lang;
    }

    /**
     * Detail Widget init
     * Class 'Mapado_Detail_Widget' in class/widget.class.php
     */
    public function initDetailWidget()
    {
        register_widget('Mapado_Detail_Widget');
    }

    /**
     * Event listing Widget init
     * Class 'Mapado_Event_Widget' in class/widget.class.php
     */
    public function initEventListingWidget()
    {
        register_widget('Mapado_Event_Widget');
    }

    /**
     * Check widget activation
     * @return boolean
     */
    public static function widgetActive()
    {
        $settings = new MapadoSetting(self::SETTINGS_WP_INDEX);
        return $settings->getValue('widget');
    }

    /**
     * Check map activation
     * @return boolean
     */
    public static function mapActive()
    {
        $settings = new MapadoSetting(self::SETTINGS_WP_INDEX);
        return $settings->getValue('display_map');
    }

    /**
     * Init rewrite rules
     */
    protected function initRewriteRules()
    {
        add_filter('query_vars', array(&$this, 'insertQueryVars'));
    }

    /**
     * WP adding & flushing rewrite rules
     * @param force bool Forcing flush rewrite
     */
    public function registerRewriteRules()
    {
        global $wp_rewrite;

        /* Get pages for slug */
        if (!empty($this->imported_lists)) {
            $rules = get_option('rewrite_rules');
            /* For each list page */
            foreach ($this->imported_lists as $slug) {
                /* List pagination rules */
                add_rewrite_rule($slug . '/page/([0-9]+)/?$', 'index.php?pagename=' . $slug . '&paged=$matches[1]', 'top');
                /* Activity single page rules */
                add_rewrite_rule($slug . '/([^/]+)/?$', 'index.php?pagename=' . $slug . '&mapado_event=$matches[1]', 'top');
            }
        }
    }

    /**
     * Inserting custom query vars
     */
    public function insertQueryVars($vars)
    {
        array_push($vars, 'mapado_event');

        return $vars;
    }

    /**
     * Install on plugin activation
     * Create events page & event single page
     */
    public function install()
    {
        $settings    = get_option(self::SETTINGS_WP_INDEX);

        /* Single event page */
        if (empty($settings['activity_page']) || (!empty($settings['activity_page']) && get_post_status($settings['activity_page']) === false)) {
            $activity_page    = wp_insert_post(array(
                'post_title'        => 'Événement',
                'post_name'            => 'evenement',
                'post_content'        => "MAPADO_EVENEMENT",
                'post_status'        => 'publish',
                'post_type'            => 'page',
                'post_author'        => 1
            ), false);
        }

        if (!empty($activity_page)) {
            $settings['activity_page']    = $activity_page;

            if (!update_option(self::SETTINGS_WP_INDEX, $settings)) {
                /* Deleting pages to try again without duplicates */
                wp_delete_post($activity_page, true);

                die('Mapado for Wordpress : Problem to save settings, please try again.');
            }
        }
        flush_rewrite_rules();
    }

    /**
     * Plugin uninstall
     * Delete posts & storage datas
     */
    public function uninstall()
    {
        $user_lists    = get_option(self::USERLISTS_WP_INDEX);
        $settings    = get_option(self::SETTINGS_WP_INDEX);

        /* Deleting pages */
        if (!empty($settings['activity_page'])) {
            wp_delete_post($settings['activity_page'], true);
        }

        /* Deleting lists pages */
        foreach ($user_lists as $list_slug) {
            $page    = get_page_by_path($list_slug);
            wp_delete_post($page->ID, true);
        }

        delete_option(self::API_WP_INDEX);
        delete_option(self::SETTINGS_WP_INDEX);
        delete_option(self::AUTH_WP_INDEX);
        delete_option(self::TOKEN_WP_INDEX);
        delete_option(self::USERLISTS_WP_INDEX);
    }
}


/**
 * Plugin initialisation
 */
add_action('init', 'mapado_plugin', 0);

function mapado_plugin()
{
    global $mapado;

    if (is_admin()) {
        $mapado    = new MapadoPrivateAuth();
    } else {
        $mapado    = new MapadoPublicAuth();
    }
}

/* Register plugin install function */
register_activation_hook(__FILE__, array($mapado, 'install'));
register_uninstall_hook(__FILE__, array($mapado, 'uninstall'));
