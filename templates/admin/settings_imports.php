<!-- AJAX user lists -->
<table id="mapado_user_lists_table" class="mapado_table widefat striped">
    <thead>
    <tr>
        <th colspan="2">
            <strong>Vos listes</strong>
        </th>
        <th style="text-align: right">
            <?php if (!empty($vars['api'])) : ?>
                <a href="#" id="mapado_user_lists_refresh"><strong>Actualiser</strong></a>
            <?php endif; ?>
        </th>
    </tr>
    </thead>

    <tbody></tbody>
</table>

<?php /* For the AJAX call; Inform that API settings are filled */ ?>
<script>
    var ajaxUserLists;
    <?php if ( !empty($vars['api']) && !empty($vars['api']['id']) && !empty($vars['api']['secret']) && !empty($vars['auth']) ) : ?>
    ajaxUserLists = {
        'load': true,
        'msg': 'Chargement...'
    };
    <?php else: ?>
    ajaxUserLists = {
        'load': false,
        'msg': 'Vous devez renseigner vos identifiants API'
    };
    <?php endif; ?>
</script>