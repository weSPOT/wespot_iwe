<?php
/**
 * Revision view for history a MindMeister map */

$annotation = $vars['annotation'];
$mindmap = get_entity($annotation->entity_guid);

$icon = elgg_view("wespot_mindmeister/icon", array(
	'annotation' => $annotation,
	'size' => 'small',
));

$owner_guid = $annotation->owner_guid;
$owner = get_entity($owner_guid);
if (!$owner) {

}
$owner_link = elgg_view('output/url', array(
	'href' => $owner->getURL(),
	'text' => $owner->name,
	'is_trusted' => true,
));

$date = elgg_view_friendly_time($annotation->time_created);

$title_link = elgg_view('output/url', array(
	'href' => $annotation->getURL(),
	'text' => $mindmap->title,
	'is_trusted' => true,
));

$subtitle = elgg_echo('wespot_mindmeister:revision:subtitle', array($date, $owner_link));

$body = <<< HTML
<h3>$title_link</h3>
<p class="elgg-subtext">$subtitle</p>
HTML;

echo elgg_view_image_block($icon, $body);