<?php
/**
 * Remove a page
 *
 * Subpages are not deleted but are moved up a level in the tree
 *
 * @package ElggPages
 */

$guid = get_input('guid');
$page = get_entity($guid);
if (elgg_instanceof($page, 'object', 'conclusions') || elgg_instanceof($page, 'object', 'conclusions_top')) {
	// only allow owners and admin to delete
	if (elgg_is_admin_logged_in() || elgg_get_logged_in_user_guid() == $page->getOwnerGuid()) {
		$container = get_entity($page->container_guid);

		// Bring all child elements forward
		$parent = $page->parent_guid;
		$children = elgg_get_entities_from_metadata(array(
			'metadata_name' => 'parent_guid',
			'metadata_value' => $page->getGUID()
		));
		if ($children) {
			$db_prefix = elgg_get_config('dbprefix');
			$subtype_id = (int)get_subtype_id('object', 'conclusions_top');
			$newentity_cache = is_memcache_available() ? new ElggMemcache('new_entity_cache') : null;

			foreach ($children as $child) {
				if ($parent) {
					$child->parent_guid = $parent;
				} else {
					// If no parent, we need to transform $child to a page_top
					$child_guid = (int)$child->guid;

					update_data("UPDATE {$db_prefix}entities
						SET subtype = $subtype_id WHERE guid = $child_guid");

					elgg_delete_metadata(array(
						'guid' => $child_guid,
						'metadata_name' => 'parent_guid',
					));

					_elgg_invalidate_cache_for_entity($child_guid);
					if ($newentity_cache) {
						$newentity_cache->delete($child_guid);
					}
				}
			}
		}

    $phase = $page->phase;
    $activity_id = $page->activity_id;

		if ($page->delete()) {
			system_message(elgg_echo('conclusions:delete:success'));
      elgg_trigger_event('delete', 'annotation_from_ui', $page);
			if ($parent) {
				if ($parent = get_entity($parent)) {
					forward($parent->getURL());
				}
			}
			if (elgg_instanceof($container, 'group')) {
				forward("conclusions/group/$container->guid/all?phase=".$phase . '&activity_id=' . $activity_id);
			} else {
				forward("conclusions/owner/$container->username");
			}
		}
	}
}

register_error(elgg_echo('conclusions:delete:failure'));
forward(REFERER);
