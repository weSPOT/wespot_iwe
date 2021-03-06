<?php
/**
 * Create a new MindMeister map
 */

elgg_load_library('elgg:wespot_mindmeister');

gatekeeper();

$container_guid = (int) get_input('guid');
$container = get_entity($container_guid);
if (!$container) {

}

$parent_guid = 0;
$mindmap_owner = $container;
if (elgg_instanceof($container, 'object')) {
	$parent_guid = $container->getGUID();
	$mindmap_owner = $container->getContainerEntity();
}

elgg_set_page_owner_guid($mindmap_owner->getGUID());

$title = elgg_echo('wespot_mindmeister:add');
elgg_push_breadcrumb($title);

$vars = wespot_mindmeister_prepare_form_vars(null, $parent_guid);
$content = elgg_view_form('wespot_mindmeister/new', array(), $vars);

$body = elgg_view_layout('content', array(
	'filter' => '',
	'content' => $content,
	'title' => $title,
));

echo elgg_view_page($title, $body);
