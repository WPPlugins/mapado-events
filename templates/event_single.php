<?php
/**
 * Mapado Plugin
 * Single Event TPL
 */
?>



<div id="mapado-plugin" class="mapado_activity_single">

<?php
    MapadoUtils::template('event_template', $vars);
?>
    <div class="mpd-credits">
		<?php
		    echo '<div class="">Retour à l\'accueil de l\'agenda : ';
			MapadoUtils::link_back_to_event_list_home();
		    echo '</div>';
		?>
		<div>
			Agenda avec Mapado (API et plugin Wordpress)
		</div>
	</div>
</div>