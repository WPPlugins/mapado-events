<?php
/**
 * Mapado Plugin
 * Widget TPL
 */
?>

<aside id="mapado-widget" class="widget mapado-widget">
    <h2 class="widget-title"><?php echo $vars['event']->getFormattedSchedule() ?></h2>

    <?php if ($vars['event']->getFrontPlaceName() && $vars['event']->getAddress()) : ?>
        <div>
            <div>
                <a href="<?php echo MapadoUtils::getPlaceUrl($vars['event']->getLinks()) ?>"
                   target="_blank"><?php echo $vars['event']->getFrontPlaceName() ?></a>
                - <?php echo $vars['event']->getAddress()->getFormattedAddress() ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($vars['event']->getSimplePrice()) : ?>
        <div>
            <?php
            if (($price = $vars['event']->getSimplePrice()) == 0)
                echo 'Gratuit';
            else
                echo $price . ' €';
            ?>
        </div>
    <?php endif; ?>
</aside>