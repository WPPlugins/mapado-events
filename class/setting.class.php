<?php

/**
 * Class MapadoSetting
 */
class MapadoSetting extends ArrayObject
{
    private $index;

    private $perpage_options = array(3, 5, 10, 20, 30, 40);
    private $perpage_default = 10;

    private $card_thumb_position_options = array('left' => 'à gauche', 'right' => 'à droite', 'top' => 'en bandeau');
    private $card_thumb_position_default = 'right';

    private $card_thumb_orientation_options = array('portrait' => 'portrait', 'landscape' => 'paysage', 'square' => 'carré');

    private $card_thumb_size_options = array('l' => 'grandes', 'm' => 'moyennes', 's' => 'petites');
    private $card_thumb_size_default = 'l';

    private $card_column_max_options = array('1' => 'toujours 1','2' => 'jusqu\'à 2',  '3' => 'jusqu\'à 3', '4' => 'jusqu\'à 4');
    private $card_column_max_default = '2';

    private $widget_default = false;
    private $display_map_default = false;

    private $card_template_default =
        '[%thumb]
    <div class="mpd-card__thumb
                mpd-card__thumb--[[thumb_position_type]]
                mpd-card__thumb--[[thumb_position_side]]
                mpd-card__thumb--[[thumb_orientation]]
                mpd-card__thumb--[[thumb_size]]">
        <a href="[[url]]">
          [[thumb]]
        </a>
    </div>
[thumb%]

<div class="mpd-card__body">
    [%title]
        <h3 class="mpd-card__title">
            <a href="[[url]]">
                [[title]]
            </a>
        </h3>
    [title%]

    [%rubric]
        <p class="mpd-card__rubric">
            [[rubric]]
        </p>
    [rubric%]

    [%shortDate]
        <p class="mpd-card__date">
            [[shortDate]]
        </p>
    [shortDate%]

    [%city]
        <p class="mpd-card__address">
            [%place]
                <a href="[[placeUrl]]" target="_blank">
                    [[place]]
                </a>
            [place%]
            <span class="mpd-card__city">
                - [[city]]
            </span>
        </p>
    [city%]

    [%shortDescription]
        <p class="mpd-card__description">
            [[shortDescription]]
            <a href="[[url]]" class="mpd-card__read-more-link">→ Lire la suite</a>
        </p>
    [shortDescription%]
</div>';


    private $full_template_default =
        '[%image]
    <div class="mapado_activity_thumb">
        [[image]]
    </div>
[image%]

[%!widgetActive]
    <div class="mapado_activity_infos">
        [%formattedSchedule]
            <div>
                <div class="mapado_activity_label">Dates</div>
                <div class="mapado_activity_value">[[ formattedSchedule ]]</div>
            </div>
        [formattedSchedule%]

        [%address]
            <div>
                <div class="mapado_activity_label">Lieu</div>
                <div class="mapado_activity_value">
                    [%place]
                        <a href="[[placeUrl]]" target="_blank">
                            [[place]]
                        </a>
                    [place%]
                    <div class="mpd-card__city">
                        [[address]]
                    </div>
                </div>
            </div>
        [address%]

        [%rubric]
            <div>
                <div class="mapado_activity_label">Rubrique</div>
                <div class="mapado_activity_value">[[ rubric ]]</div>
            </div>
        [rubric%]

        <div>
            <div class="mapado_activity_label">Infos / Contact</div>
            <div class="mapado_activity_value">
                [%official_link]
                    <div class="mapado_activity_link_official">
                        <a href="[[ official_link ]]" target=_blank>Site officiel</a>
                    </div>
                [official_link%]

                [%email]
                    <div class="mapado_activity_email">
                        <a href="mailto:[[ email ]]" target=_blank>Envoyer un email</a>
                    </div>
                [email%]

                [%phone]
                    <div class="mapado_activity_phone">
                        Téléphone : [[ phone ]]
                    </div>
                [phone%]
            </div>
        </div>

        [%price]
            <div>
                <div class="mapado_activity_label">Tarif</div>
                <div class="mapado_activity_value">
                    <div class="mapado_activity_price">
                        [[price]]
                    </div>
                    [%ticket_link]
                    <div class="mapado_activity_buy">
                        <a href="[[ticket_link]]" target="_blank">Réserver</a>
                    </div>
                    [ticket_link%]
                </div>
            </div>
        [price%]

    </div>
[widgetActive%]

[%description]
    <div class="mapado_activity_desc">
        [[description]]
    </div>
[description%]

[%mapActive]
    [%address]
        <div class="mpd-google-maps">
            [[map]]
        </div>
    [address%]
[mapActive%]';


    /**
     * Initialization
     */
    public function __construct($index)
    {
        $this->index = $index;
        $settings = get_option($index);
        if (is_array($settings)) {
            $this->exchangeArray($settings);
        }
    }


    /**
     * Wordpress methods
     */
    public function update()
    {
        return update_option($this->index, $this->getArrayCopy());
    }

    public function delete($index)
    {
        unset($this[$index]);
        return $this;
    }


    /**
     * Administration methods
     */
    public function getDefinition($name)
    {
        return array('options' => $this->getOptions($name), 'value' => $this->getValue($name));
    }

    public function getOptions($name)
    {
        $optionsAttribute = $name . '_options';
        if (isset($this->$optionsAttribute)) {
            return $this->$optionsAttribute;
        }
        return array();
    }

    public function getValue($name)
    {
        if (isset($this[$name])) {
            return $this[$name];
        }
        return $this->getDefaultValue($name);
    }

    public function getDefaultValue($name)
    {
        $defaultAttribute = $name . '_default';
        if (isset($this->$defaultAttribute)) {
            return $this->$defaultAttribute;
        }
        $options = $this->getOptions($name);
        return reset($options);
    }
}
