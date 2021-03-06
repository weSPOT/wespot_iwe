<?php
/**
 * Elgg Pages
 *
 * @package ElggPages
 */

elgg_register_event_handler('init', 'system', 'help_init');

/**
 * Initialize the pages plugin.
 *
 */
function help_init() {

	// register a library of helper functions
	elgg_register_library('elgg:help', elgg_get_plugins_path() . 'wespot_help/lib/pages.php');

//	$item = new ElggMenuItem('help', elgg_echo('help'), 'help/all');
//	elgg_register_menu_item('site', $item);
	
	// Register a page handler, so we can have nice URLs
	elgg_register_page_handler('help', 'help_page_handler');

	// Register a url handler
	elgg_register_entity_url_handler('object', 'help_top', 'help_url');
	elgg_register_entity_url_handler('object', 'help', 'help_url');
	elgg_register_annotation_url_handler('help', 'help_revision_url');

	// Register some actions
	$action_base = elgg_get_plugins_path() . 'wespot_help/actions';
	elgg_register_action("help/edit", "$action_base/help/edit.php");
	elgg_register_action("help/delete", "$action_base/help/delete.php");
	elgg_register_action("annotations/help/delete", "$action_base/annotations/help/delete.php");

	// Extend the main css view
	elgg_extend_view('css/elgg', 'pages/css');

	// Register javascript needed for sidebar menu
	$js_url = 'mod/wespot_help/vendors/jquery-treeview/jquery.treeview.min.js';
	elgg_register_js('jquery-treeview', $js_url);
	$css_url = 'mod/wespot_help/vendors/jquery-treeview/jquery.treeview.css';
	elgg_register_css('jquery-treeview', $css_url);

	// Register entity type for search
//	elgg_register_entity_type('object', 'help');
//	elgg_register_entity_type('object', 'help_top');

	// Register granular notification for this type
	register_notification_object('object', 'help', elgg_echo('pages:new'));
	register_notification_object('object', 'help_top', elgg_echo('pages:new'));
	elgg_register_plugin_hook_handler('notify:entity:message', 'object', 'help_notify_message');

	// Language short codes must be of the form "pages:key"
	// where key is the array key below
	elgg_set_config('help', array(
		'title' => 'text',
		'description' => 'longtext',
		'tags' => 'tags',
		'parent_guid' => 'parent',
		'access_id' => 'access',
		'write_access_id' => 'write_access',
	));

//	elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'help_owner_block_menu');

	// write permission plugin hooks
	elgg_register_plugin_hook_handler('permissions_check', 'object', 'help_write_permission_check');
	elgg_register_plugin_hook_handler('container_permissions_check', 'object', 'help_container_permission_check');

	// icon url override
	elgg_register_plugin_hook_handler('entity:icon:url', 'object', 'help_icon_url_override');

	// entity menu
	elgg_register_plugin_hook_handler('register', 'menu:entity', 'help_entity_menu_setup');

	// hook into annotation menu
	elgg_register_plugin_hook_handler('register', 'menu:annotation', 'pages_annotation_menu_setup');

	// register ecml views to parse
	elgg_register_plugin_hook_handler('get_views', 'ecml', 'help_ecml_views_hook');
	
	elgg_register_event_handler('upgrade', 'system', 'help_run_upgrades');
}

/**
 * Dispatcher for pages.
 * URLs take the form of
 *  All pages:        pages/all
 *  User's pages:     pages/owner/<username>
 *  Friends' pages:   pages/friends/<username>
 *  View page:        pages/view/<guid>/<title>
 *  New page:         pages/add/<guid> (container: user, group, parent)
 *  Edit page:        pages/edit/<guid>
 *  History of page:  pages/history/<guid>
 *  Revision of page: pages/revision/<id>
 *  Group pages:      pages/group/<guid>/all
 *
 * Title is ignored
 *
 * @param array $page
 * @return bool
 */
function help_page_handler($page) {

	elgg_load_library('elgg:help');
	
	if (!isset($page[0])) {
		$page[0] = 'all';
	}

//	elgg_push_breadcrumb(elgg_echo('help'), 'help/all');

	$base_dir = elgg_get_plugins_path() . 'wespot_help/pages/pages';

	$page_type = $page[0];
	switch ($page_type) {
		case 'owner':
			include "$base_dir/owner.php";
			break;
		case 'friends':
			include "$base_dir/friends.php";
			break;
		case 'view':
			set_input('guid', $page[1]);
			include "$base_dir/view.php";
			break;
		case 'add':
			set_input('guid', $page[1]);
			include "$base_dir/new.php";
			break;
		case 'edit':
			set_input('guid', $page[1]);
			include "$base_dir/edit.php";
			break;
		case 'group':
			include "$base_dir/owner.php";
			break;
		case 'history':
			set_input('guid', $page[1]);
			include "$base_dir/history.php";
			break;
		case 'revision':
			set_input('id', $page[1]);
			include "$base_dir/revision.php";
			break;
		case 'all':
			include "$base_dir/world.php";
			break;
		default:
			return false;
	}
	return true;
}

/**
 * Override the page url
 * 
 * @param ElggObject $entity Page object
 * @return string
 */
function help_url($entity) {
	$title = elgg_get_friendly_title($entity->title);
	return "help/view/$entity->guid/$title";
}

/**
 * Override the page annotation url
 *
 * @param ElggAnnotation $annotation
 * @return string
 */
function help_revision_url($annotation) {
	return "help/revision/$annotation->id";
}

/**
 * Override the default entity icon for pages
 *
 * @return string Relative URL
 */
function help_icon_url_override($hook, $type, $returnvalue, $params) {
	$entity = $params['entity'];
	if (elgg_instanceof($entity, 'object', 'help_top') ||
		elgg_instanceof($entity, 'object', 'help')) {
		switch ($params['size']) {
			case 'topbar':
			case 'tiny':
			case 'small':
				return 'mod/wespot_help/images/pages.gif';
				break;
			default:
				return 'mod/wespot_help/images/pages_lrg.gif';
				break;
		}
	}
}

/**
 * Add a menu item to the user ownerblock
 */
function help_owner_block_menu($hook, $type, $return, $params) {
	if (elgg_instanceof($params['entity'], 'user')) {
		$url = "help/owner/{$params['entity']->username}";
		$item = new ElggMenuItem('help', elgg_echo('help'), $url);
		$return[] = $item;
	} else {
		if ($params['entity']->pages_enable != "no") {
			$url = "help/group/{$params['entity']->guid}/all";
			$item = new ElggMenuItem('help', elgg_echo('pages:group'), $url);
			$return[] = $item;
		}
	}

	return $return;
}

/**
 * Add links/info to entity menu particular to pages plugin
 */
function help_entity_menu_setup($hook, $type, $return, $params) {
	if (elgg_in_context('widgets')) {
		return $return;
	}

	$entity = $params['entity'];
	$handler = elgg_extract('handler', $params, false);
	if ($handler != 'help') {
		return $return;
	}

	// remove delete if not owner or admin
	if (!elgg_is_admin_logged_in() && elgg_get_logged_in_user_guid() != $entity->getOwnerGuid()) {
		foreach ($return as $index => $item) {
			if ($item->getName() == 'delete') {
				unset($return[$index]);
			}
		}
	}

	$options = array(
		'name' => 'history',
		'text' => elgg_echo('pages:history'),
		'href' => "help/history/$entity->guid",
		'priority' => 150,
	);
	$return[] = ElggMenuItem::factory($options);

	return $return;
}

/**
* Returns a more meaningful message
*
* @param unknown_type $hook
* @param unknown_type $entity_type
* @param unknown_type $returnvalue
* @param unknown_type $params
*/
function help_notify_message($hook, $entity_type, $returnvalue, $params) {
	$entity = $params['entity'];
	$to_entity = $params['to_entity'];
	$method = $params['method'];

	if (elgg_instanceof($entity, 'object', 'help') || elgg_instanceof($entity, 'object', 'help_top')) {
		$descr = $entity->description;
		$title = $entity->title;
		$owner = $entity->getOwnerEntity();
		
		return elgg_echo('pages:notification', array(
			$owner->name,
			$title,
			$descr,
			$entity->getURL()
		));
	}
	return null;
}

/**
 * Extend permissions checking to extend can-edit for write users.
 *
 * @param string $hook
 * @param string $entity_type
 * @param bool   $returnvalue
 * @param array  $params
 */
function help_write_permission_check($hook, $entity_type, $returnvalue, $params) {
	if ($params['entity']->getSubtype() == 'help'
		|| $params['entity']->getSubtype() == 'help_top') {

		$write_permission = $params['entity']->write_access_id;
		$user = $params['user'];

		if ($write_permission && $user) {
			switch ($write_permission) {
				case ACCESS_PRIVATE:
					// Elgg's default decision is what we want
					return;
					break;
				case ACCESS_FRIENDS:
					$owner = $params['entity']->getOwnerEntity();
					if ($owner && $owner->isFriendsWith($user->guid)) {
						return true;
					}
					break;
				default:
					$list = get_access_array($user->guid);
					if (in_array($write_permission, $list)) {
						// user in the access collection
						return true;
					}
					break;
			}
		}
	}
}

/**
 * Extend container permissions checking to extend can_write_to_container for write users.
 *
 * @param unknown_type $hook
 * @param unknown_type $entity_type
 * @param unknown_type $returnvalue
 * @param unknown_type $params
 */
function help_container_permission_check($hook, $entity_type, $returnvalue, $params) {

	if (elgg_get_context() == "help") {
		if (elgg_get_page_owner_guid()) {
			if (can_write_to_container(elgg_get_logged_in_user_guid(), elgg_get_page_owner_guid())) return true;
		}
		if ($page_guid = get_input('page_guid',0)) {
			$entity = get_entity($page_guid);
		} else if ($parent_guid = get_input('parent_guid',0)) {
			$entity = get_entity($parent_guid);
		}
		if ($entity instanceof ElggObject) {
			if (
					can_write_to_container(elgg_get_logged_in_user_guid(), $entity->container_guid)
					|| in_array($entity->write_access_id,get_access_list())
				) {
					return true;
			}
		}
	}

}

/**
 * Return views to parse for pages.
 *
 * @param unknown_type $hook
 * @param unknown_type $entity_type
 * @param unknown_type $return_value
 * @param unknown_type $params
 */
function help_ecml_views_hook($hook, $entity_type, $return_value, $params) {
	$return_value['object/help'] = elgg_echo('item:object:help');
	$return_value['object/help_top'] = elgg_echo('item:object:help_top');

	return $return_value;
}
