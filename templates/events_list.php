<?php
/**
 * Mapado Plugin
 * Events list TPL
 */
?>

<!-- List -->
<div id="mapado-plugin">
    <?php
    if ($vars['display_search']) {
        if (!empty($vars['search_filters']['rubric']['label'])) {
            $search_keyword_label = $vars['search_filters']['rubric']['label'];
            $search_keyword_value = $vars['search_filters']['rubric']['value'];
        }
        else {
            $search_keyword_value = get_query_var('mpd-rubric');
            $search_keyword_label = get_query_var('mpd-rubric');
        }
    ?>
        <div class="mpd-search-bar">
            Filtrer <br/>
            <form method="GET" action="<?php echo MapadoUtils::getUserListUrl($vars['list_slug']) ?>">
                <div class="mpd-form-group">
                    <!-- <label>Rubrique</label> -->
                    <input placeholder="rubrique, mot clé..." type="text" name="mpd-rubric" id="search-filter-rubric"
                        value="<?php echo $search_keyword_value; ?>"
                        data-label="<?php echo $search_keyword_label; ?>"/>
                </div>
                <div class="mpd-form-group">
                    <!-- <label>Ville</label> -->
                    <input placeholder="ville..." type="text" name="mpd-address" id="search-filter-address"
                        value="<?php echo $vars['search_filters']['address']['value']; ?>"
                        data-label="<?php echo $vars['search_filters']['address']['label']; ?>"/>
                </div>
                <div class="mpd-form-group">
                    <!-- <label>Quand</label> -->
                    <select name="mpd-when">
                        <option value="soon">Prochainement</option>
                        <option value="today">Aujourd&#039;hui</option>
                        <option value="tomorrow">Demain</option>
                        <option value="weekend">Ce week-end</option>
                    </select>
                </div>
                <div class="mpd-form-group">
                    <button type="submit">Valider</button>
                </div>
            </form>
        </div>
    <?php
    }
    ?>

    <?php
    $modifier = $vars['card_thumb_design']['size'];
    if ($vars['card_thumb_design']['position_side'] == 'top') {
        $modifier = 'top';
    }
    ?>
    <div class="chew-row chew-row--<?= $vars['card_column_max'] ?> chew-row--thumb-<?= $modifier ?>">
        <?php
        foreach ($vars['events'] as $activity) {
            $vars['activity'] = $activity;
            MapadoUtils::template('event_card', $vars);
        }
        $ghostSize = 5;
        if ($vars['card_column_max'] !== 'auto') {
            $ghostSize = $vars['card_column_max'] - 1;
        }
        for ($i = 0; $i < $ghostSize; $i++) : ?>
            <li class="chew-cell chew-cell--ghost">
            </li>
        <?php endfor; ?>
    </div>

    <div class="mpd-card-list__footer">

        <!-- Pagination -->
        <?php if ($vars['pagination']['nb_pages'] > 1) :
            $pagination_bounding = 2;
            $current_page = $vars['pagination']['page'];
            $start_page = 1;
            $end_page = $vars['pagination']['nb_pages'];
            $pagination = [];
            if ($current_page <= $start_page + ($pagination_bounding * 2 + 1)) {
                $begin_pagination = $start_page;
            } else {
                $begin_pagination = $current_page - $pagination_bounding;
            }
            if ($current_page >= $end_page - ($pagination_bounding * 2 + 1)) {
                $end_pagination = $end_page;
            } else {
                $end_pagination = $current_page + $pagination_bounding;
            }

            $list_slug = $vars['list_slug'];
            $pagination_link = function ($page, $label = false) use ($current_page, $list_slug) {
                if (!$label) {
                    $label = $page;
                }
                echo '<a href="'.MapadoUtils::getUserListUrl($list_slug, $page).'"'
                        .'class="mpd-pagination__item">'
                        .$label
                    .'</a>';
            };
            $pagination_span = function ($label, $class = '') use ($current_page, $list_slug) {
                if (!empty($class)) {
                    $class = 'mpd-pagination__item--'.$class;
                }
                echo '<span class="mpd-pagination__item '.$class.'">'
                        .$label
                    .'</span>';
            };
        ?>
            <div class="mpd-pagination">
                <?php
                if ($current_page > $start_page) {
                    $pagination_link($current_page - 1, '<');
                }
                if ($begin_pagination > $start_page) {
                    for ($page = $start_page; $page < $start_page + $pagination_bounding; $page++) {
                        $pagination_link($page);
                    }
                    $pagination_span('...');
                }
                for ($page = $begin_pagination; $page < $current_page; $page++) {
                    $pagination_link($page);
                }
                $pagination_span($current_page, 'current');
                for ($page = $current_page + 1; $page <= $end_pagination; $page++) {
                    $pagination_link($page);
                }
                if ($end_pagination < $end_page) {
                    $pagination_span('...');
                    for ($page = $end_page - $pagination_bounding + 1; $page <= $end_page; $page++) {
                        $pagination_link($page);
                    }
                }
                if ($current_page < $end_page) {
                    $pagination_link($current_page + 1, '>');
                }
                ?>
            </div>
        <?php endif; ?>
        <div class="mpd-credits">
            <?php
            if ( $current_page <= 1) {
                echo get_the_title()." avec <a href='https://www.mapado.com' target='_blank'>Mapado</a> (API et plugin Wordpress)";
            }
            else {
                echo "Agenda avec Mapado (API et plugin Wordpress)";
            }
            ?>
        </div>
    </div>
</div>

