<table id="mapado_settings_table" class="mapado_table widefat striped">
    <thead>
    <tr>
        <th colspan="2"><strong>Entrez vos réglages API</strong></th>
        <th style="text-align:right">
            <a href="//mag.mapado.com/api-keys-mapado/" title="Trouver ma clé API" target="_blank">
                Où trouver ces informations ?
            </a>
        </th>
    </tr>
    </thead>

    <tbody>
    <tr>
        <td>
            <label class="mapado_table__label" for="mapado_api_id">ID</label>
            <input name="mapado_api_id" id="mapado_api_id" type="text" step="1" min="1"
                   value="<?php if (!empty($vars['api']['id'])) echo $vars['api']['id'] ?>" style="width:100%">
        </td>

        <td>
            <label class="mapado_table__label" for="mapado_api_secret">Code secret</label>
            <input name="mapado_api_secret" id="mapado_api_secret" type="text" step="1" min="1"
                   value="<?php if (!empty($vars['api']['secret'])) echo $vars['api']['secret'] ?>"
                   style="width:100%">
        </td>

        <td>
            <label class="mapado_table__label" for="mapado_api_auth">Jeton d'autentification</label>
            <input name="mapado_api_auth" id="mapado_api_auth" type="text" step="1" min="1"
                   value="<?php if ($vars['auth']) echo $vars['auth'] ?>" style="width:100%">
        </td>
    </tr>
    </tbody>
</table>
