<?php

/**
 * Class MapadoUtils
 * Utility functions
 */
class MapadoUtils
{
    /**
     * Mapado template call
     * @param path file in 'templates' folder
     * @param variables to send to the template
     */
    public static function template($file, $vars = array())
    {
        require MAPADO_PLUGIN_PATH . 'templates/' . $file . '.php';
    }


    /**
     * Build user list url based on WP permalink settings
     * @param string user list uuid
     * @param array of mapado pages ids
     * @param array of imported lists
     * @return string url
     */
    public static function getUserListUrl($list_slug, $page = 1)
    {
        $url = get_permalink(get_page_by_path($list_slug));

        if ($page > 1) {
            /* Rewrite url */
            if (get_option('permalink_structure') != '') {
                /* Adding the last slash when permalink structure doesn't have it */
                $last_slash = substr($url, -1);
                if ($last_slash != '/') {
                    $url .= '/';
                }
                $url .= 'page/' . $page;
            }
            /* Classic url */
            else {
                $url = add_query_arg('paged', $page);
            }
        }

        return user_trailingslashit($url);
    }

    /**
     * Build single event url based on WP permalink settings
     * @param string event uuid
     * @param array of mapado pages ids
     * @return string url
     */
    public static function getEventUrl($event_uuid, $list_slug)
    {
        $url = get_permalink(get_page_by_path($list_slug));

        /* Rewrite url */
        if (get_option('permalink_structure') != '') {
            /* Adding the last slash when permalink structure doesn't have it */
            $last_slash = substr($url, -1);
            if ($last_slash != '/') {
                $url .= '/';
            }

            $url .= $event_uuid;
        }
        /* Classic url */
        else {
            $url = add_query_arg('mapado_event', $event_uuid, $url);
        }

        return user_trailingslashit($url);
    }

    /**
     * Get event place URL or event URL if not
     * @param array event links
     * @return string url
     */
    public static function getPlaceUrl($links)
    {
        $url = '';
        if (!empty($links['mapado_place_url'])) {
            $url = $links['mapado_place_url']['href'];
        } elseif (!empty($links['mapado_url'])) {
            $url = $links['mapado_url']['href'];
        }

        return $url;
    }

    public static function link_back_to_event_list_home()
    {
        echo '<a href="';
        the_permalink();
        echo '">'.single_post_title("", false).'</a> ';
    }
}
