<?php

/**
 * Class MapadoPublicAuth
 * For public area
 */
class MapadoPublicAuth extends MapadoPlugin
{
    private $token;

    private $event_displayed = false;

    public function __construct()
    {
        $this->setDatas();
        $this->setToken();

        add_action('wp_enqueue_scripts', array(&$this, 'enqueuePublicStyleandScript'), 15);

        add_filter('the_content', array(&$this, 'mapadoPagesFactory'), 10);
        add_filter('the_title', array(&$this, 'eventPageTitle'), 15);
        add_filter('pre_get_document_title', array(&$this, 'eventWpTitle'), 30);
        add_filter('wp_title', array(&$this, 'eventWpTitle'), 30);
        add_filter('query_vars', array(&$this, 'initQueryVars'));

        add_action('init', array(&$this, 'canonical_init'));
    }


    public function canonical_init()
    {
        remove_action('wp_head', 'rel_canonical');
        add_action('wp_head', array(&$this, 'mapado_rel_canonical'));
    }

    public function is_uuid($value)
    {
        return (bool) preg_match('/^[0-9a-f]{8}-([0-9a-f]{4}-){3}[0-9a-f]{12}$/', $value);
    }

    public function mapado_rel_canonical()
    {
        # Patched wordpress rel_canonical function
        global $wp_query, $post;

        if (! is_singular()) {
            return;
        }

        if (! $id = get_queried_object_id()) {
            return;
        }

        # Specific canonical rules for mapado events
        if (is_page() && !empty($this->imported_lists) && (false !== $uuid = array_search($post->post_name, $this->imported_lists))) {
            if (!empty($wp_query->query_vars['mapado_event'])) {
                $current_event = $this->getActivity($wp_query->query_vars['mapado_event'], $this->token);
                if (!empty($current_event)) {
                    $links = $current_event->getLinks();

                    if (!empty($links['mapado_url'])) {
                        $url = $links['mapado_url']['href'];
                        echo '<link rel="canonical" href="'.$url.'" />';
                    }
                }
            } else {
                # Put here code for listing pages (when canonical url available in API)
            }
            return;
        }

        $url = get_permalink($id);

        $page = get_query_var('page');

        if ($page >= 2) {
            if ('' == get_option('permalink_structure')) {
                $url = add_query_arg('page', $page, $url);
            } else {
                $url = trailingslashit($url) . user_trailingslashit($page, 'single_paged');
            }
        }

        $cpage = get_query_var('cpage');
        if ($cpage) {
            $url = get_comments_pagenum_link($cpage);
        }
        echo '<link rel="canonical" href="' . esc_url($url) . "\" />\n";
    }

    /**
     * Settings token
     * Cached or not in WP database
     */
    private function setToken($forceRefresh = false)
    {
        $token_cache = get_option(parent::TOKEN_WP_INDEX);

        /* Get cached token */
        if (!$forceRefresh && !empty($token_cache) && $token_cache->getExpiresAt()->getTimestamp() > time()) {
            $this->token = $token_cache;
        } /* Refresh token */
        elseif (!empty($this->api['id']) && !empty($this->api['secret'])) {
            try {
                $oauth = \Mapado\Sdk\Oauth::createOauth($this->api['id'], $this->api['secret']);
                $this->token = $oauth->getClientToken('activity:all:read');

                update_option(parent::TOKEN_WP_INDEX, $this->token);
            } catch (GuzzleHttp\Exception\ClientException $e) {
                error_log($e->getResponse());
            }
        }
    }

    public function initQueryVars($queryVars)
    {
        $queryVars[]= 'mpd-rubric';
        $queryVars[]= 'mpd-address';
        $queryVars[]= 'mpd-when';

        return $queryVars;
    }

    /**
     * Enqueue style in public area
     */
    public function enqueuePublicStyleandScript()
    {
        wp_enqueue_style('mapado-plugin', MAPADO_PLUGIN_URL . 'assets/mapado_plugin.css', false, '0.1.6');
        wp_enqueue_style('mapado-card', MAPADO_PLUGIN_URL . 'assets/mapado_card.css', false, '0.1.6');

        if ($this->settings->getValue('display_search')) {
            wp_enqueue_script('typeahead.jquery.min.js', '//cdn.jsdelivr.net/typeahead.js/0.10.5/typeahead.jquery.min.js', ['jquery']);
            wp_enqueue_script('algoliasearch.min.js', '//cdn.jsdelivr.net/algoliasearch/3/algoliasearch.min.js', ['jquery']);
            wp_enqueue_script('mapado-search.js', MAPADO_PLUGIN_URL . 'assets/js/search.js', ['algoliasearch.min.js', 'typeahead.jquery.min.js']);
        }
    }

    /**
     * Select template renderer bases on page type
     * @param post content
     * @return list html
     */
    public function mapadoPagesFactory($content)
    {
        global $wp_query, $post;
        $uuid = array_search($post->post_name, $this->imported_lists);
        $template_output = null;

        if (is_page() && in_the_loop() && !empty($this->imported_lists) && (false !== $uuid)) {
            if (empty($wp_query->query_vars['mapado_event'])) {
                # Listing page
                $template_output = $this->eventListingFactory($uuid);

                if (empty($wp_query->query_vars['paged']) || $wp_query->query_vars['paged'] == 0) {
                    # 1st listing page : display listing + post content
                    $template_output = str_replace("[mapado_list]", $template_output, $content);
                }
            } else {
                # Single event page
                $template_output = $this->eventSinglePageFactory();
            }
        }

        if (empty($template_output)) {
            return $content;
        } else {
            return $template_output;
        }
    }


    /**
     * Render event listing template
     * @param post content, list uuid
     * @return rendered template
     */
    public function eventListingFactory($uuid)
    {
        global $wp_query, $post;

        if (empty($this->event_displayed)) {
            $this->event_displayed = true;
        } else {
            return;
        }

        /* Check token validity */
        if (!$client = $this->getClient($this->token)) {
            return 'Page listing : Accès non autorisé, vérifiez vos identifiants Mapado.';
        }

        /* Pagination */
        $page = 1;
        $perpage = 10;
        if (!empty($this->settings['perpage'])) {
            $perpage = $this->settings['perpage'];
        }

        /* Sort */
        $sort = 'api_topevents-date';
        if (!empty($this->settings['list_sort'])) {
            $sort = $this->settings['list_sort'];
        }

        /* Display past events */
        $past_events = 'soon';
        if (!empty($this->settings['past_events'])) {
            $past_events = 'all';
        }

        /* List Depth */
        $list_depth = 1;
        if (!empty($this->settings['list_depth'])) {
            $list_depth = $this->settings['list_depth'];
        }


        if (!empty($wp_query->query_vars['paged'])) {
            $page = $wp_query->query_vars['paged'];
        }

        $start = ($page * $perpage) - $perpage;
        $params = array(
            'image_sizes' => array('200x300', '300x200', '300x300', '500x120', '500x200', '500x280', '700x250'),
            'offset' => $start,
            'limit' => $perpage,
            'list' => $uuid,
            'when' => $past_events,
            'specific_model' => $sort,
            'list_depth' => $list_depth
        );

        $searchFilters = [
            'rubric' => null,
            'address' => null,
        ];

        if ($mpdAddress = get_query_var('mpd-address')) {
            try {
                if ($this->is_uuid($mpdAddress)) {
                    $address = $this->client->address->findOne($mpdAddress);
                } else {
                    $address = $this->client
                        ->address
                        ->findBy([
                            'q' => $mpdAddress
                        ])
                        ->getIterator()
                        ->current();
                }
            } catch (GuzzleHttp\Exception\ClientException $e) {
                $address = "";
            }

            if ($address) {
                $searchFilters['address'] = [
                    'label' => $address->getFormattedAddress(),
                    'value' => $address->getUuid(),
                ];
                $params['address'] = $address->getUuid();
            }
        }
        if ($rubricUuid = get_query_var('mpd-rubric')) {
            try {
                $rubric = $this->client->rubric->findOne($rubricUuid);
            } catch (GuzzleHttp\Exception\ClientException $e) {
                $rubric = "";
            }

            if ($rubric) {
                $searchFilters['rubric'] = [
                    'value' => $rubric->getUuid(),
                    'label' => $rubric->getName(),
                ];
                $params['rubric'] = $rubric->getUuid();
            } else {
                $params['q'] = $rubricUuid;
            }
        }

        if ($when = get_query_var('mpd-when')) {
            $params['when'] = $when;
        }

        $results = $this->findActivityWithParams($client, $params);

        $pagination = array(
            'perpage' => $perpage,
            'page' => $page,
            'nb_pages' => ceil($results->getTotalHits() / $perpage)
        );

        /* Card design */
        $card_thumb_design = $this->getCardThumbDesign();
        $card_column_max = $this->settings->getValue('card_column_max');
        $template = new MapadoMicroTemplate($this->settings->getValue('card_template'));

        ob_start();

        MapadoUtils::template('events_list', array(
            'uuid'              => $uuid,
            'list_slug'         => $post->post_name,
            'events'            => $results,
            'pagination'        => $pagination,
            'card_column_max'   => $card_column_max,
            'card_thumb_design' => $card_thumb_design,
            'template'          => $template,
            'display_search'    => $this->settings->getValue('display_search'),
            'search_filters'    => $searchFilters,
        ));

        $template_output = ob_get_contents();
        ob_end_clean();

        return $template_output;
    }

    /**
     * Filtering post content for event single page
     * Replace page content by event content
     * @param original content
     * @return filtered content
     */
    public function eventSinglePageFactory()
    {
        global $wp_query;

        $current_event = $this->getActivity($wp_query->query_vars['mapado_event'], $this->token);
        if (empty($current_event)) {
            return 'Page événement : Accès non autorisé, vérifiez vos identifiants Mapado.';
        }

        if (empty($this->event_displayed)) {
            $this->event_displayed = true;
        } else {
            return;
        }

        $template = new MapadoMicroTemplate($this->settings->getValue('full_template'));

        ob_start();

        MapadoUtils::template('event_single', array(
            'activity' => $current_event,
            'template' => $template,
        ));

        $template_output = ob_get_contents();
        ob_end_clean();

        return $template_output;
    }


    /**
     * Show event details in a widget
     */
    public function eventDetailWidget()
    {
        global $post, $wp_query;

        if (is_page() && $post->ID == $this->settings['activity_page'] && !empty($this->settings['widget'])) {
            $current_event = $this->getActivity($wp_query->query_vars['mapado_event'], $this->token);
            MapadoUtils::template('widget', array('event' => $current_event));
        }
    }

    /**
     * Show event listing in a widget
     */
    public function eventListingWidget($widget_template, $thumbnail_width, $thumbnail_height, $list_uuid, $nb_displayed, $list_depth)
    {
        global $wp_query, $post;

        if (empty($this->event_listing_widget_displayed)) {
            $this->event_listing_widget_displayed = true;
        } else {
            return;
        }

        /* Check token validity */
        if (!$client = $this->getClient($this->token)) {
            return 'Page listing : Accès non autorisé, vérifiez vos identifiants Mapado.';
        }

        /* Sort */
        $sort_model = 'api_topevents-date';
        $list_slug = $this->imported_lists[$list_uuid];

        $params = array(
                'image_sizes' => array('300x200'),
                'offset' => 0,
                'limit' => $nb_displayed,
                'list' => $list_uuid,
                'specific_model' => $sort_model,
                'list_depth' => $list_depth
            );

        $results = $this->findActivityWithParams($client, $params);

        /* Card design */
        $card_thumb_design = array(
            'position_type' => "bandeau",
            'position_side' => "top",
            'orientation' => "landscape",
            'size' => 'm',
            'dimensions' => array($thumbnail_width, $thumbnail_height),
            'id' => '300x200'
        );

        $template = new MapadoMicroTemplate($widget_template);
        MapadoUtils::template('events_list_widget', array(
            'uuid'              => $list_uuid,
            'list_slug'         => $list_slug,
            'events'            => $results,
            'card_thumb_design' => $card_thumb_design,
            'template'          => $template,
        ));
    }

    /**
     * Filtering WP title for event single page
     * @param original title
     * @return filtered title
     */
    public function eventWpTitle($title)
    {
        global $post, $wp_query;

        if (is_page() && !empty($this->imported_lists) && (false !== $uuid = array_search($post->post_name, $this->imported_lists)) && !empty($wp_query->query_vars['mapado_event'])) {
            $current_event = $this->getActivity($wp_query->query_vars['mapado_event'], $this->token);
            if (!empty($current_event)) {
                $title = $current_event->getTitle() . ' | ' . get_bloginfo('name');
            }
        }

        return $title;
    }

    /**
     * Filtering post title for event single page
     * @param original title
     * @return filtered title
     */
    public function eventPageTitle($title)
    {
        global $post, $wp_query;

        if (is_page() && ($title == single_post_title("", false)) && !empty($this->imported_lists) && (false !== $uuid = array_search($post->post_name, $this->imported_lists)) && !empty($wp_query->query_vars['mapado_event'])) {
            $current_event = $this->getActivity($wp_query->query_vars['mapado_event'], $this->token);
            if (!empty($current_event)) {
                $title = $current_event->getTitle();
            }
        }

        return $title;
    }

    /**
     * Calculate the thumb size in card listing according to admin settings
     */
    protected function getCardThumbDesign()
    {
        $card_thumb_position_type = 'side';
        $card_thumb_position_side = $this->settings->getValue('card_thumb_position');
        $card_thumb_orientation = $this->settings->getValue('card_thumb_orientation');
        $card_thumb_size = $this->settings->getValue('card_thumb_size');

        $card_thumb_ratio = 2;
        if ($card_thumb_size == 'm') {
            $card_thumb_ratio = 1;
        } elseif ($card_thumb_size == 's') {
            $card_thumb_ratio = 0;
        }

        if ($card_thumb_position_side == 'top') {
            $card_thumb_position_type = 'bandeau';
            $card_thumb_dimensions = array(500, (120 + $card_thumb_ratio * 80));
            $card_thumb_id = implode('x', $card_thumb_dimensions);
        } else {
            $card_thumb_dimensions = array(200, 300);
            if ($card_thumb_orientation == 'landscape') {
                $card_thumb_dimensions = array(300, 200);
            } elseif ($card_thumb_orientation == 'square') {
                $card_thumb_dimensions = array(300, 300);
            }

            $card_thumb_id = implode('x', $card_thumb_dimensions);

            foreach ($card_thumb_dimensions as $dimension => $val) {
                $card_thumb_dimensions[$dimension] = $val * (2 + $card_thumb_ratio) / 4;
            }
        }

        return array(
            'position_type' => $card_thumb_position_type,
            'position_side' => $card_thumb_position_side,
            'orientation' => $card_thumb_orientation,
            'size' => $card_thumb_size,
            'dimensions' => $card_thumb_dimensions,
            'id' => $card_thumb_id
        );
    }

    /**
     * findActivityWithParams
     *
     * @param sdkclient $client
     * @param array $params
     * @access private
     * @return array|Traversable
     */
    private function findActivityWithParams($client, $params)
    {
        try {
            $results = $client->activity->findBy($params);
        } catch (GuzzleHttp\Exception\ClientException $e) {
            $this->setToken(true);
            try {
                $client = $this->getClient($this->token, true);
                $results = $client->activity->findBy($params);
            } catch (GuzzleHttp\Exception\ClientException $e) {
                error_log($e->getResponse());

                echo sprintf(
                    'Une erreur est survenue lors de l\'accès aux données: %s',
                    $e->getMessage()
                );

                $results = [];
            }
        }

        return $results;
    }
}
