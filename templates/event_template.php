<?php

$template = $vars['template'];
$activity = $vars['activity'];

$template->reset();

$template['title'] = $activity->getTitle();
$template['description'] = apply_filters('the_content', $activity->getDescription(), true);
$template['shortDescription'] = $activity->getShortDescription();
$template['formattedSchedule'] = $activity->getFormattedSchedule();
$template['shortDate'] = $activity->getShortDate();
$price = $activity->getSimplePrice();
$template['price'] = ($price === 0) ? 'Gratuit' : ($price) ? $price . ' €' : false;
if ($activity->getRubric()) {
    $template['rubric'] = ucfirst($activity->getRubric()->getName());
}
else {
    $template['rubric'] = '';
}
$url_list = $activity->getUrlList();

$template['ticket_link'] = $url_list["ticket"];
$template['facebook_link'] = $url_list["facebook"];
$template['official_link'] = $url_list["official"];
$template['email'] = $activity->getEmailList()[0];
$template['phone'] = $activity->getPhoneList()[0];

$template['widgetActive'] = MapadoPlugin::widgetActive();

$template->assignIf('url', function($uuid, $list_slug) {
    return MapadoUtils::getEventUrl($uuid, $list_slug);
}, @$activity->getUuid(), @$vars['list_slug']);

$template->assignIf('imageUrl', function($imageUrl) {
    return $imageUrl;
}, @$activity->getImageUrlList()['700x250'][0]);

$template->assignIf('image', function($imageUrl) {
    return '<img src="' . $imageUrl . '" alt=""/>';
}, @$activity->getImageUrlList()['700x250'][0]);

$template->assignIf('thumb', function($imageUrl, $dimensions) {
    return <<<EOD
    <img width="{$dimensions[0]}"
         height="{$dimensions[1]}"
         src="{$imageUrl}"
         alt=""
        />
EOD;
}, @$activity->getImageUrlList()[@$vars['card_thumb_design']['id']][0], @$vars['card_thumb_design']['dimensions']);

$template['thumb_position_type'] = @$vars['card_thumb_design']['position_type'];
$template['thumb_position_side'] = @$vars['card_thumb_design']['position_side'];
$template['thumb_orientation'] = @$vars['card_thumb_design']['orientation'];
$template['thumb_size'] = @$vars['card_thumb_design']['size'];

$template['address'] = $activity->getAddress()->getFormattedAddress();
$template['place'] = $activity->getFrontPlaceName();
$template['placeUrl'] = MapadoUtils::getPlaceUrl($activity->getLinks());
$template['city'] = null;
if ($activity->getAddress()) {
    $template['city'] = $activity->getAddress()->getCity();
}
$template['mapActive'] = MapadoPlugin::mapActive();
$template['map'] = false;
if ($activity->getAddress()) {
    $template['map'] = function($options) use ($activity) {
        $zoom = (count($options) >= 1)?$options[0]:16;
        $map = <<<EOD
    <script src="//maps.googleapis.com/maps/api/js"></script>
    <script>
        var mpd_map; //<-- This is now available to both event listeners and the initialize() function
        var mpd_map_lat = {$activity->getAddress()->getLatitude()};
        var mpd_map_lng = {$activity->getAddress()->getLongitude()};
        function initialize() {
            var mapOptions = {
                center: new google.maps.LatLng(mpd_map_lat, mpd_map_lng),
                zoom: $zoom,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };
            mpd_map = new google.maps.Map(document.getElementById("mapado-map-canvas"),
                mapOptions);
            var marqueur = new google.maps.Marker({
                position: new google.maps.LatLng(mpd_map_lat, mpd_map_lng),
                map: mpd_map
            });
        }
        google.maps.event.addDomListener(window, 'load', initialize);
        google.maps.event.addDomListener(window, "resize", function () {
            var center = mpd_map.getCenter();
            google.maps.event.trigger(mpd_map, "resize");
            mpd_map.setCenter(center);
        });

    </script>
    <div id="mapado-map-canvas"></div>
EOD;
        return $map;
    };
}
echo $template->output();
?>
