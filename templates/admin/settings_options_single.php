<table id="mapado_option_single_table" class="mapado_table widefat striped">
    <thead>
    <tr>
        <th colspan="2"><strong>Options des pages événement</strong></th>
    </tr>
    </thead>

    <tbody>
    <tr>
        <td>
            <label class="mapado_table__label" for="mapado_widget">Afficher les informations principales dans un widget ?</label>
        </td>
        <td>
            <input name="mapado_widget" id="mapado_widget" type="checkbox"
                   value="1" <?php if (!empty($vars['settings']['widget'])) echo 'checked="checked"' ?>>
        </td>
    </tr>

    <!-- Google Maps Display -->
    <tr>
        <td>
            <label class="mapado_table__label" for="mapado_display_map">Afficher une carte sous les événements ?</label>
        </td>
        <td>
            <input name="mapado_display_map" id="mapado_display_map" type="checkbox"
                   value="1" <?php if (!empty($vars['settings']['display_map'])) echo 'checked="checked"' ?>>
        </td>
    </tr>

    <!-- Advanced settings -->
    <tr>
        <td colspan="2"><a href="javascript:;" class="mpd-table-dropdown__trigger">Paramètres avancés</a></td>
    </tr>
    <tr class="mpd-table-dropdown__body">
        <td colspan="2">
            <table id="mapado_option_single_advanced_table" class="mapado_table widefat striped">

                <!-- Template -->
                <tr>
                    <td colspan="2">
                        <button type="button" class="button button-delete right js-mapado_template_reset">
                            Réinitialiser
                        </button>
                        <label class="mapado_table__label" for="mapado_full_template">Modèle d'affichage</label>
                        <textarea name="mapado_full_template" id="mapado_full_template" class="js-mapado_template_input"><?php
                            echo $vars['settings']->getValue('full_template')
                            ?></textarea>
                        <div style="display: none;" class="js-mapado_template_default"><?php
                            echo $vars['settings']->getDefaultValue('full_template')
                            ?></div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    </tbody>
</table>
