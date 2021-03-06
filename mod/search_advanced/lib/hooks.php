<?php
/**
 * Elgg core search.
 *
 * @package Elgg
 * @subpackage Core
 */

/**
 * Return default results for searches on objects.
 *
 * @param unknown_type $hook
 * @param unknown_type $type
 * @param unknown_type $value
 * @param unknown_type $params
 * @return unknown_type
 */
function search_advanced_objects_hook($hook, $type, $value, $params) {

	static $tag_name_ids;
	static $valid_tag_names;
	
	$db_prefix = elgg_get_config('dbprefix');

	$query = sanitise_string($params['query']);
	
	if (!isset($tag_name_ids)) {
		if ($valid_tag_names = elgg_get_registered_tag_metadata_names()) {
			$tag_name_ids = array();
			foreach($valid_tag_names as $tag_name){
				$tag_name_ids[] = add_metastring($tag_name);
			}
		} else {
			$tag_name_ids = false;
		}
	}
	
	if($tag_name_ids){
		$params['joins'] = array(
			"JOIN {$db_prefix}objects_entity oe ON e.guid = oe.guid",
			"JOIN {$db_prefix}metadata md on e.guid = md.entity_guid",
			"JOIN {$db_prefix}metastrings msv ON md.value_id = msv.id"
		);
	} else {
		$join = "JOIN {$db_prefix}objects_entity oe ON e.guid = oe.guid";
		$params['joins'] = array($join);
	}
	
	$fields = array('title', 'description');
	
	if($params["subtype"] === "page"){
		$params["subtype"] = array("page", "page_top");
	}
	
	$where = search_advanced_get_where_sql('oe', $fields, $params, FALSE);

	if($tag_name_ids){
		// get the where clauses for the md names
		// can't use egef_metadata() because the n_table join comes too late.
// 		$clauses = elgg_entities_get_metastrings_options('metadata', array(
// 				'metadata_names' => $valid_tag_names,
// 		));
	
// 		$params['joins'] = array_merge($clauses['joins'], $params['joins']);
		$md_where = "((md.name_id IN (" . implode(",", $tag_name_ids) . ")) AND msv.string = '$query')";
	
		$params['wheres'] = array("(($where) OR ($md_where))");
	} else {
		$params['wheres'] = array($where);
	}
	
	$params['count'] = TRUE;
	$count = elgg_get_entities($params);
	
	// no need to continue if nothing here.
	if (!$count) {
		return array('entities' => array(), 'count' => $count);
	}
	
	$params['count'] = FALSE;
	$entities = elgg_get_entities($params);

	// add the volatile data for why these entities have been returned.
	foreach ($entities as $entity) {
		if($valid_tag_names){
			$matched_tags_strs = array();
	
			// get tags for each tag name requested to find which ones matched.
			foreach ($valid_tag_names as $tag_name) {
				$tags = $entity->getTags($tag_name);
	
				// @todo make one long tag string and run this through the highlight
				// function.  This might be confusing as it could chop off
				// the tag labels.
				if (in_array(strtolower($query), array_map('strtolower', $tags))) {
					if (is_array($tags)) {
						$tag_name_str = elgg_echo("tag_names:$tag_name");
						$matched_tags_strs[] = "$tag_name_str: " . implode(', ', $tags);
					}
				}
			}
	
			$tags_str = implode('. ', $matched_tags_strs);
			$tags_str = search_get_highlighted_relevant_substrings($tags_str, $params['query']);
	
			$entity->setVolatileData('search_matched_extra', $tags_str);
		}
		
		$title = search_get_highlighted_relevant_substrings($entity->title, $params['query']);
		$entity->setVolatileData('search_matched_title', $title);

		$desc = search_get_highlighted_relevant_substrings($entity->description, $params['query']);
		$entity->setVolatileData('search_matched_description', $desc);
	}

	return array(
		'entities' => $entities,
		'count' => $count,
	);
}

/**
 * Return default results for searches on groups.
 *
 * @param unknown_type $hook
 * @param unknown_type $type
 * @param unknown_type $value
 * @param unknown_type $params
 * @return unknown_type
 */
function search_advanced_groups_hook($hook, $type, $value, $params) {
	$db_prefix = elgg_get_config('dbprefix');

	$query = sanitise_string($params['query']);

	$profile_fields = array_keys(elgg_get_config('group'));
	if($profile_fields){
		$params['joins'] = array(
				"JOIN {$db_prefix}groups_entity ge ON e.guid = ge.guid",
				"JOIN {$db_prefix}metadata md on e.guid = md.entity_guid",
				"JOIN {$db_prefix}metastrings msv ON md.value_id = msv.id"
				);
	} else {
		$join = "JOIN {$db_prefix}groups_entity ge ON e.guid = ge.guid";
		$params['joins'] = array($join);
	}
	
	$fields = array('name', 'description');

	// force into boolean mode because we've having problems with the
	// "if > 50% match 0 sets are returns" problem.
	$where = search_advanced_get_where_sql('ge', $fields, $params, FALSE);

	if($profile_fields){
		// get the where clauses for the md names
		// can't use egef_metadata() because the n_table join comes too late.
// 		$clauses = elgg_entities_get_metastrings_options('metadata', array(
// 				'metadata_names' => $profile_fields,
// 		));
		
// 		$params['joins'] = array_merge($clauses['joins'], $params['joins']);
		
		$tag_name_ids = array();
		foreach($profile_fields as $field){
			$tag_name_ids[] = add_metastring($field);
		}
		
		$md_where = "((md.name_id IN (" . implode(",", $tag_name_ids) . ")) AND msv.string LIKE '%$query%')";
		$params['wheres'] = array("(($where) OR ($md_where))");
	} else {
		$params['wheres'] = array($where);
	}
	
	// override subtype -- All groups should be returned regardless of subtype.
	$params['subtype'] = ELGG_ENTITIES_ANY_VALUE;

	$params['count'] = TRUE;
	$count = elgg_get_entities($params);
	
	// no need to continue if nothing here.
	if (!$count) {
		return array('entities' => array(), 'count' => $count);
	}
	
	$params['count'] = FALSE;
	$entities = elgg_get_entities($params);

	// add the volatile data for why these entities have been returned.
	foreach ($entities as $entity) {
// 		if($profile_fields){
// 			$matched_tags_strs = array();
			
// 			// get tags for each tag name requested to find which ones matched.
// 			foreach ($profile_fields as $tag_name) {
// 				$tags = $entity->getTags($tag_name);
			
// 				// @todo make one long tag string and run this through the highlight
// 				// function.  This might be confusing as it could chop off
// 				// the tag labels.
// 				if (in_array(strtolower($query), array_map('strtolower', $tags))) {
// 					if (is_array($tags)) {
// 						$tag_name_str = elgg_echo("tag_names:$tag_name");
// 						$matched_tags_strs[] = "$tag_name_str: " . implode(', ', $tags);
// 					}
// 				}
// 			}
			
// 			$tags_str = implode('. ', $matched_tags_strs);
// 			$tags_str = search_get_highlighted_relevant_substrings($tags_str, $params['query']);
			
// 			$entity->setVolatileData('search_matched_extra', $tags_str);
// 		}
		
		$name = search_get_highlighted_relevant_substrings($entity->name, $query);
		$entity->setVolatileData('search_matched_title', $name);

		$description = search_get_highlighted_relevant_substrings($entity->description, $query);
		$entity->setVolatileData('search_matched_description', $description);
	}

	return array(
		'entities' => $entities,
		'count' => $count,
	);
}

/**
 * Return default results for searches on users.
 *
 * @todo add profile field MD searching
 *
 * @param unknown_type $hook
 * @param unknown_type $type
 * @param unknown_type $value
 * @param unknown_type $params
 * @return unknown_type
 */
function search_advanced_users_hook($hook, $type, $value, $params) {
	
	
	$db_prefix = elgg_get_config('dbprefix');

	$query = sanitise_string($params['query']);

	$params['joins'] = array(
		"JOIN {$db_prefix}users_entity ue ON e.guid = ue.guid",
		"JOIN {$db_prefix}metadata md on e.guid = md.entity_guid",
		"JOIN {$db_prefix}metastrings msv ON md.value_id = msv.id"
	);
	
	if(isset($params["container_guid"])){
		$entity = get_entity($params["container_guid"]);
	}
	
	if(isset($entity) && $entity instanceof ElggGroup) {
		// check for group membership relation
		$params["relationship"] = "member";
		$params["relationship_guid"] = $params["container_guid"];
		$params["inverse_relationship"] = TRUE;
	} else {
		// check for site relation ship
		if(empty($_SESSION["search_advanced:multisite"])){
			$params["relationship"] = "member_of_site";
			$params["relationship_guid"] = elgg_get_site_entity()->getGUID();
			$params["inverse_relationship"] = TRUE;
		}
	}
	
	$fields = array('username', 'name');
	$where = search_advanced_get_where_sql('ue', $fields, $params, FALSE);
	
	// profile fields
	$profile_fields = array_keys(elgg_get_config('profile_fields'));
	if ($profile_fields) {
		// get the where clauses for the md names
		// can't use egef_metadata() because the n_table join comes too late.
// 		$clauses = elgg_entities_get_metastrings_options('metadata', array(
// 				'metadata_names' => $profile_fields,
// 		));
	
// 		$params['joins'] = array_merge($clauses['joins'], $params['joins']);

		// no fulltext index, can't disable fulltext search in this function.
		// $md_where .= " AND " . search_get_where_sql('msv', array('string'), $params, FALSE);
		$tag_name_ids = array();
		foreach($profile_fields as $field){
			$tag_name_ids[] = add_metastring($field);
		}
		
		$md_where = "((md.name_id IN (" . implode(",", $tag_name_ids) . ")) AND msv.string LIKE '%$query%')";
		
		$params['wheres'] = array("(($where) OR ($md_where))");
	} else {
		$params['wheres'] = array($where);
	}
	// override subtype -- All users should be returned regardless of subtype.
	$params['subtype'] = ELGG_ENTITIES_ANY_VALUE;

	$params['count'] = TRUE;
	$count = elgg_get_entities_from_relationship($params);

	// no need to continue if nothing here.
	if (!$count) {
		return array('entities' => array(), 'count' => $count);
	}
	
	$params['count'] = FALSE;
	$entities = elgg_get_entities_from_relationship($params);

	// add the volatile data for why these entities have been returned.
	foreach ($entities as $entity) {
		$username = search_get_highlighted_relevant_substrings($entity->username, $query);
		$entity->setVolatileData('search_matched_title', $username);

		$name = search_get_highlighted_relevant_substrings($entity->name, $query);
		$entity->setVolatileData('search_matched_description', $name);
	}

	return array(
		'entities' => $entities,
		'count' => $count,
	);
}

/**
 * Return default results for searches on tags.
 *
 * @param unknown_type $hook
 * @param unknown_type $type
 * @param unknown_type $value
 * @param unknown_type $params
 * @return unknown_type
 */
function search_advanced_tags_hook($hook, $type, $value, $params) {
	$db_prefix = elgg_get_config('dbprefix');

	$valid_tag_names = elgg_get_registered_tag_metadata_names();

	// @todo will need to split this up to support searching multiple tags at once.
	$query = sanitise_string($params['query']);

	// if passed a tag metadata name, only search on that tag name.
	// tag_name isn't included in the params because it's specific to
	// tag searches.
	if ($tag_names = get_input('tag_names')) {
		if (is_array($tag_names)) {
			$search_tag_names = $tag_names;
		} else {
			$search_tag_names = array($tag_names);
		}

		// check these are valid to avoid arbitrary metadata searches.
		foreach ($search_tag_names as $i => $tag_name) {
			if (!in_array($tag_name, $valid_tag_names)) {
				unset($search_tag_names[$i]);
			}
		}
	} else {
		$search_tag_names = $valid_tag_names;
	}

	if (!$search_tag_names) {
		return array('entities' => array(), 'count' => $count);
	}

	// don't use elgg_get_entities_from_metadata() here because of
	// performance issues.  since we don't care what matches at this point
	// use an IN clause to grab everything that matches at once and sort
	// out the matches later.
	$params['joins'][] = "JOIN {$db_prefix}metadata md on e.guid = md.entity_guid";
	$params['joins'][] = "JOIN {$db_prefix}metastrings msn on md.name_id = msn.id";
	$params['joins'][] = "JOIN {$db_prefix}metastrings msv on md.value_id = msv.id";

	$access = get_access_sql_suffix('md');
	$sanitised_tags = array();

	foreach ($search_tag_names as $tag) {
		$sanitised_tags[] = '"' . sanitise_string($tag) . '"';
	}

	$tags_in = implode(',', $sanitised_tags);

	$multi_tag_query = explode(" ", $query);
	if(count($multi_tag_query) > 1){ 
		$multi_tag_query[] = $query;
		$params['wheres'][] = "(msn.string IN ($tags_in) AND msv.string IN ('" . implode("', '", $multi_tag_query) . "') AND $access)";
	} else {
		$params['wheres'][] = "(msn.string IN ($tags_in) AND msv.string = '$query' AND $access)";
	}
	$params['count'] = TRUE;
	
	if(empty($_SESSION["search_advanced:multisite"])) {
		$site_guid = elgg_get_site_entity()->getGUID();
		$params['site_guids'] = false;
		$params['wheres'][] = "((e.site_guid = " . $site_guid . ") OR (e.type = 'user' AND e.guid IN (select r.guid_one from " . elgg_get_config("dbprefix") . "entity_relationships r where r.relationship = 'member_of_site' and r.guid_two = " . $site_guid . ")))" ;
	}
	
	$count = elgg_get_entities($params);

	// no need to continue if nothing here.
	if (!$count) {
		return array('entities' => array(), 'count' => $count);
	}
	
	$params['count'] = FALSE;
	$entities = elgg_get_entities($params);

	// add the volatile data for why these entities have been returned.
	foreach ($entities as $entity) {
		$matched_tags_strs = array();

		// get tags for each tag name requested to find which ones matched.
		foreach ($search_tag_names as $tag_name) {
			$tags = $entity->getTags($tag_name);

			// @todo make one long tag string and run this through the highlight
			// function.  This might be confusing as it could chop off
			// the tag labels.
			if (in_array(strtolower($query), array_map('strtolower', $tags))) {
				if (is_array($tags)) {
					$tag_name_str = elgg_echo("tag_names:$tag_name");
					$matched_tags_strs[] = "$tag_name_str: " . implode(', ', $tags);
				}
			}
		}

		// different entities have different titles
		switch($entity->type) {
			case 'site':
			case 'user':
			case 'group':
				$title_tmp = $entity->name;
				break;

			case 'object':
				$title_tmp = $entity->title;
				break;
		}

		// Nick told me my idea was dirty, so I'm hard coding the numbers.
		$title_tmp = strip_tags($title_tmp);
		if (elgg_strlen($title_tmp) > 297) {
			$title_str = elgg_substr($title_tmp, 0, 297) . '...';
		} else {
			$title_str = $title_tmp;
		}

		$desc_tmp = strip_tags($entity->description);
		if (elgg_strlen($desc_tmp) > 297) {
			$desc_str = elgg_substr($desc_tmp, 0, 297) . '...';
		} else {
			$desc_str = $desc_tmp;
		}

		$tags_str = implode('. ', $matched_tags_strs);
		$tags_str = search_get_highlighted_relevant_substrings($tags_str, $params['query']);

		$entity->setVolatileData('search_matched_title', $title_str);
		$entity->setVolatileData('search_matched_description', $desc_str);
		$entity->setVolatileData('search_matched_extra', $tags_str);
	}

	return array(
		'entities' => $entities,
		'count' => $count,
	);
}

/**
 * Register tags as a custom search type.
 *
 * @param unknown_type $hook
 * @param unknown_type $type
 * @param unknown_type $value
 * @param unknown_type $params
 * @return unknown_type
 */
// function search_custom_types_tags_hook($hook, $type, $value, $params) {
// 	$value[] = 'tags';
// 	return $value;
// }


/**
 * Get comments that match the search parameters.
 *
 * @param string $hook   Hook name
 * @param string $type   Hook type
 * @param array  $value  Empty array
 * @param array  $params Search parameters
 * @return array
 */
function search_advanced_comments_hook($hook, $type, $value, $params) {
	$db_prefix = elgg_get_config('dbprefix');

	$query = sanitise_string($params['query']);
	$limit = sanitise_int($params['limit']);
	$offset = sanitise_int($params['offset']);
	$params['annotation_names'] = array('generic_comment', 'group_topic_post');

	$params['joins'] = array(
		"JOIN {$db_prefix}annotations a on e.guid = a.entity_guid",
		"JOIN {$db_prefix}metastrings msn on a.name_id = msn.id",
		"JOIN {$db_prefix}metastrings msv on a.value_id = msv.id"
	);

	$fields = array('string');

	// force IN BOOLEAN MODE since fulltext isn't
	// available on metastrings (and boolean mode doesn't need it)
	$search_where = search_get_where_sql('msv', $fields, $params, FALSE);

	$container_and = '';
	if ($params['container_guid'] && $params['container_guid'] !== ELGG_ENTITIES_ANY_VALUE) {
		$container_and = 'AND e.container_guid = ' . sanitise_int($params['container_guid']);
	}

	$site_and = "";
	if(empty($_SESSION["search_advanced:multisite"])) {
		$site_guid = elgg_get_site_entity()->getGUID();
		$site_and = "AND ((e.site_guid = " . $site_guid . ") OR (e.type = 'user' AND e.guid IN (select r.guid_one from " . elgg_get_config("dbprefix") . "entity_relationships r where r.relationship = 'member_of_site' and r.guid_two = " . $site_guid . ")))" ;
	}
	
	$e_access = get_access_sql_suffix('e');
	$a_access = get_access_sql_suffix('a');
	// @todo this can probably be done through the api..
	$q = "SELECT count(DISTINCT a.id) as total FROM {$db_prefix}annotations a
		JOIN {$db_prefix}metastrings msn ON a.name_id = msn.id
		JOIN {$db_prefix}metastrings msv ON a.value_id = msv.id
		JOIN {$db_prefix}entities e ON a.entity_guid = e.guid
		WHERE msn.string IN ('generic_comment', 'group_topic_post')
			AND ($search_where)
			AND $e_access
			AND $a_access
			$container_and
			$site_and
		";

	if (!$result = get_data($q)) {
		return FALSE;
	}
	
	$count = $result[0]->total;
	
	// don't continue if nothing there...
	if (!$count) {
		return array ('entities' => array(), 'count' => 0);
	}
	
	$order_by = search_get_order_by_sql('e', null, $params['sort'], $params['order']);
	if ($order_by) {
		$order_by = "ORDER BY $order_by";
	}
	
	$q = "SELECT DISTINCT a.*, msv.string as comment FROM {$db_prefix}annotations a
		JOIN {$db_prefix}metastrings msn ON a.name_id = msn.id
		JOIN {$db_prefix}metastrings msv ON a.value_id = msv.id
		JOIN {$db_prefix}entities e ON a.entity_guid = e.guid
		WHERE msn.string IN ('generic_comment', 'group_topic_post')
			AND ($search_where)
			AND $e_access
			AND $a_access
			$container_and
			$site_and
		
		$order_by
		LIMIT $offset, $limit
		";

	$comments = get_data($q);

	// @todo if plugins are disabled causing subtypes
	// to be invalid and there are comments on entities of those subtypes,
	// the counts will be wrong here and results might not show up correctly,
	// especially on the search landing page, which only pulls out two results.

	// probably better to check against valid subtypes than to do what I'm doing.

	// need to return actual entities
	// add the volatile data for why these entities have been returned.
	$entities = array();
	foreach ($comments as $comment) {
		$entity = get_entity($comment->entity_guid);

		// hic sunt dracones
		if (!$entity) {
			//continue;
			$entity = new ElggObject();
			$entity->setVolatileData('search_unavailable_entity', TRUE);
		}

		$comment_str = search_get_highlighted_relevant_substrings($comment->comment, $query);
		$entity->setVolatileData('search_match_annotation_id', $comment->id);
		$entity->setVolatileData('search_matched_comment', $comment_str);
		$entity->setVolatileData('search_matched_comment_owner_guid', $comment->owner_guid);
		$entity->setVolatileData('search_matched_comment_time_created', $comment->time_created);
		$entities[] = $entity;
	}

	return array(
		'entities' => $entities,
		'count' => $count,
	);
}

/**
 * Register comments as a custom search type.
 *
 * @param unknown_type $hook
 * @param unknown_type $type
 * @param unknown_type $value
 * @param unknown_type $params
 * @return unknown_type
 */
// function search_custom_types_comments_hook($hook, $type, $value, $params) {
// 	$value[] = 'comments';
// 	return $value;
// }
