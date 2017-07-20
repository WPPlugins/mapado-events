<?php
/**
 * Mapado Plugin
 * Events list TPL
 */
?>

<!-- List -->
<div id="mapado-plugin-widget">
    <div class="chew-row chew-row--1 ?> chew-row--thumb-top ?>">
        <?php
        foreach ($vars['events'] as $activity) {
            $vars['activity'] = $activity;
            MapadoUtils::template('event_card', $vars);
        }
        ?>
    </div>

</div>

