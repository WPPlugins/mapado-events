<?php
/**
 * Mapado Plugin
 * Admin AJAX user list TPL
 */
?>

<?php foreach ($vars['user_lists'] as $k => $list) :
    $exist = false;

    if (!empty($vars['imported_lists'])) {
        $exist = array_key_exists($list->getUuid(), $vars['imported_lists']);
    }
    ?>
    <tr id="list-<?php echo $list->getUuid() ?>" class="list">
        <td>
            <?php if ($exist) : ?>
                <a href="<?php echo MapadoUtils::getUserListUrl($vars['imported_lists'][$list->getUuid()]) ?>" title=""
                   target="_blank"><?php echo $list->getTitle() ?></a>
            <?php else :
                echo $list->getTitle();
            endif; ?>
        </td>
        <td colspan="2">
            <form>
                <?php if ($exist) : ?>
                    <span class="mpd-aligned-input">
                        <strong>Slug actuel :</strong>
                        <?php bloginfo('url') ?>/<?php echo $vars['imported_lists'][$list->getUuid()] ?>
                    </span>
                    <input type="hidden" name="list-slug" value="<?php echo $vars['imported_lists'][$list->getUuid()] ?>"/>
                    <input type="hidden" name="action" value="delete" />
                    <button type="submit" class="button button-delete right">Supprimer la liste</button>
                <?php else : ?>
                    <label for="list-slug-<?php echo $list->getUuid() ?>">
                        Choisir le slug :
                        <span style="font-weight: normal;"><?php bloginfo('url') ?>/</span>
                    </label>
                    <input type="text" id="list-slug-<?php echo $list->getUuid() ?>"
                           name="list-slug" placeholder="slug-de-ma-liste" class="list-slug">
                    <input type="hidden" name="action" value="import" />
                    <button type="submit" class="button button-primary right">Importer la liste</button>
                <?php endif; ?>

                <input type="hidden" name="list-title" value="<?php echo $list->getTitle() ?>">
                <input type="hidden" name="uuid" value="<?php echo $list->getUuid() ?>" />
            </form>
        </td>
    </tr>
<?php endforeach; ?>