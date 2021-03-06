<?php
/**
 * List a user's friends' wespot_mindmeister maps
 */

$owner = elgg_get_page_owner_entity();
if (!$owner) {
	forward('wespot_mindmeister/all');
}

elgg_push_breadcrumb($owner->name, "wespot_mindmeister/owner/$owner->username");
elgg_push_breadcrumb(elgg_echo('friends'));

$group = get_entity(elgg_get_page_owner_guid());
if (elgg_get_logged_in_user_guid() == $group->owner_guid) {
	elgg_register_title_button();
}

$title = elgg_echo('wespot_mindmeister:friends');

$content = list_user_friends_objects($owner->guid, 'mindmeistermap', 10, false);
if (!$content) {
	$content = elgg_echo('wespot_mindmeister:none');
}

$params = array(
	'filter_context' => 'friends',
	'content' => $content,
	'title' => $title,
);

$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);
