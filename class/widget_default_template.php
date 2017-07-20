<?php
    $widget_card_template_default =
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
