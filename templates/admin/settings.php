<?php
/**
 * Admin
 * Settings page TPL
 */
?>
<div id="mapado-settings">
    
    <h1 class="mapado-settings-title">Mapado options</h1>

    <!-- Notifications -->
    <?php foreach ($vars['notification_list'] as $notification) : ?>
        <div id="mapado-settings-updated" class="mapado-notifications <?php echo $notification['type'] ?> settings-error">
            <p><strong><?php echo $notification['message'] ?></strong></p>
        </div>
    <?php endforeach; ?>

    <form class="mapado-settings-page" method="post" name="mapado_settings_form"
          action="?page=mapado_settings&step=<?= $vars['step'] ?>&noheader"
        >
        <div class="mapado-settings-page-content">

            <?php
                if ($vars['step'] !== 'options'):
            ?>
                <div class="mapado-setting-welcome">
                    <h1>Bonjour et bienvenue sur Mapado Events Plugin</h1>
                    <p>Vous allez pouvoir importer une liste d'événements facilement en quelques clics.</p>
                </div>
            <?php else: ?>
                <div class="mapado-setting-welcome">
                    <h1>Félicitations vos listes sont en lignes</h1>
                    <p>Vous pouvez maintenant personnaliser leur style si vous le souhaitez.</p>
                </div>
            <?php endif; ?>

            <div class="mapado-setting-nav">
                <a href="?page=mapado_settings&step=api" class="mapado-setting-nav-item
                    <?= ($vars['step'] == 'api')?'mapado-setting-nav-item--active':'' ?>"
                    title="Vos réglages API"
                >
                    1
                </a>
                <a href="?page=mapado_settings&step=imports" class="mapado-setting-nav-item
                    <?= ($vars['step'] == 'imports')?'mapado-setting-nav-item--active':'' ?>
                    <?= (!$vars['has_api'])?'mapado-setting-nav-item--disable':'' ?>"
                    title="Vos listes"
                >
                    2
                </a>
                <a href="?page=mapado_settings&step=options" class="mapado-setting-nav-item mapado-setting-nav-item--options
                    <?= ($vars['step'] == 'options')?'mapado-setting-nav-item--active':'' ?>
                    <?= (!$vars['has_imported_lists'])?'mapado-setting-nav-item--disable':'' ?>"
                    title="Vos options d'affichage"
                >
                    3
                </a>
            </div>

            <?php
            if ($vars['step'] == 'imports'):
            ?>
                <div class="mapado-setting-welcome">
                    <p>Choisissez les listes que vous souhaitez importer avec l'url souhaitée</p>
                </div>
            <?php endif; ?>

            <?php
            if ($vars['step'] == 'api') {
                include(dirname(__FILE__) . '/settings_api.php');
            } elseif ($vars['step'] == 'imports') {
                include(dirname(__FILE__) . '/settings_imports.php');
            } else {
                include(dirname(__FILE__) . '/settings_options_list.php');
                include(dirname(__FILE__) . '/settings_options_single.php');
            } ?>
        </div>


        <div class="mapado-settings-page-footer">
            <?php if ($vars['step'] != 'api'): ?>
                <a href = "?page=mapado_settings&step=<?= ($vars['step'] == 'imports')?'api':'imports' ?>" class="button-link">
                    &lt; &Eacute;tape précédantes
                </a>
            <?php endif; ?>
            &nbsp;
            <button name="mapado_settings_submit" value="submit" class="button button-primary">
                <?php
                if ($vars['step'] == 'options') {
                    echo 'Enregistrer les modifications';
                } else {
                    echo 'Continuer';
                } ?>
            </button>
        </div>
    </form>
</div>