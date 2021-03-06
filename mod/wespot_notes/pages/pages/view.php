<?php
/**
 * View a single page
 *
 * @package ElggPages
 */

$page_guid = get_input('guid');
$page = get_entity($page_guid);
if (!$page) {
	register_error(elgg_echo('noaccess'));
	$_SESSION['last_forward_from'] = current_page_url();
	forward('');
}

elgg_set_page_owner_guid($page->getContainerGUID());

group_gatekeeper();

$container = elgg_get_page_owner_entity();
if (!$container) {
}

$title = $page->title;

if (elgg_instanceof($container, 'group')) {
	elgg_push_breadcrumb(elgg_echo('notes:owner', array($container->name)), "notes/group/$container->guid/all?phase=" . $page->phase . '&activity_id=' . $page->activity_id);
} else {
	elgg_push_breadcrumb(elgg_echo('notes:owner', array($container->name)), "notes/owner/$container->username");
}
pages_prepare_parent_breadcrumbs($page);
elgg_push_breadcrumb($title);

elgg_register_title_button(null, 'add', 'phase=' . $page->phase . '&activity_id=' . $page->activity_id);

$content = elgg_view_entity($page, array('full_view' => true));
$content .= elgg_view_comments($page);

// can add subpage if can edit this page and write to container (such as a group)
/* REMOVED by David --> we don't need "Create a sub-notes/page" button for notes"
 *
if ($page->canEdit() && $container->canWriteToContainer(0, 'object', 'page')) {
	$url = "pages/add/$page->guid";
	elgg_register_menu_item('title', array(
			'name' => 'subpage',
			'href' => $url,
			'text' => elgg_echo('pages:newchild'),
			'link_class' => 'elgg-button elgg-button-action',
	));
}*/

$body = elgg_view_layout('content', array(
	'filter' => '',
	'content' => $content,
	'title' => $title,
//	'sidebar' => elgg_view('pages/sidebar/navigation'),
));

echo elgg_view_page($title, $body);
