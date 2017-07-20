<?php

$template = '[%title]
    <h3 class="mpd-card__title">
        <a href="[[eventUrl]]">
            [[title]]
        </a>
    </h3>
[title%]';

$eventUrl = 'http://example.com';
$title = 'Title';
$vars = array('eventUrl', 'title');
