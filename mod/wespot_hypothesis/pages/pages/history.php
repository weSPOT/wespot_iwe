<?php
/**
 * History of revisions of a page
 *
 * @package ElggPages
 */

$page_guid = get_input('guid');

$page = get_entity($page_guid);
if (!$page) {

}

$container = $page->getContainerEntity();
if (!$container) {

}

elgg_set_page_owner_guid($container->getGUID());

if (elgg_instanceof($container, 'group')) {
	elgg_push_breadcrumb(elgg_echo('hypothesis:owner', array($container->name)), "hypothesis/group/$container->guid/all");
} else {
	elgg_push_breadcrumb(elgg_echo('hypothesis:owner', array($container->name)), "hypothesis/owner/$container->username");
}
pages_prepare_parent_breadcrumbs($page);
elgg_push_breadcrumb($page->title, $page->getURL());
elgg_push_breadcrumb(elgg_echo('hypothesis:history'));

$title = $page->title . ": " . elgg_echo('hypothesis:history');

$content = elgg_list_annotations(array(
		'guid' => $page_guid,
		'annotation_name' => 'hypothesis',
		'limit' => 20,
		'order_by' => "n_table.time_created desc"
));

$body = elgg_view_layout('content', array(
	'filter' => '',
	'content' => $content,
	'title' => $title,
//	'sidebar' => elgg_view('pages/sidebar/navigation', array('page' => $page)),
));

echo elgg_view_page($title, $body);
