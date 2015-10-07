<?php

/**
 * update old profile tabs with default metadata
 * @param type $profile
 * @return type
 */
function tabbed_profile_default_metadata($profile) {
  $context = elgg_get_context();
  elgg_set_context('tabbed_profile_permissions');
  $ignore = elgg_set_ignore_access(true);

  // save our metadata
  $profile->order = $profile->order ? $profile->order : 1;
  $profile->default = 1;
  $profile->profile_type = $profile->profile_type ? $profile->profile_type : 'widgets';
  $profile->widget_layout = $profile->widget_layout ? $profile->widget_layout : elgg_instanceof($page_owner, 'user') ? 3 : 2;
  $profile->widget_profile_display = $profile->widget_profile_display ? $profile->widget_profile_display : 'yes';
  $profile->group_sidebar = $profile->group_sidebar ? $profile->group_sidebar : 'yes';
  $profile->md_version = '1.5';

  elgg_set_ignore_access($ignore);
  elgg_set_context($context);

  return $profile;
}

function tabbed_profile_draw_user_profile($profile) {
  if ($profile->default && ($profile->md_version != '1.5')) {
	$profile = tabbed_profile_default_metadata($profile);
  }

  $layout = 'tabbed_profile_' . $profile->profile_type;
  $owner = $profile->getContainerEntity();

  elgg_push_breadcrumb($owner->name, $owner->getURL());
  elgg_push_breadcrumb($profile->title);

  $params = array('profile' => $profile);

  if ($profile->profile_type == 'widgets') {
    if ($profile->widget_profile_display == 'yes') {
      $params['content'] = elgg_view('profile/wrapper');
    }

    $params['num_columns'] = (int) $profile->widget_layout;
  }

	$content = elgg_view_layout($layout, $params);

  $body = elgg_view_layout('one_column', array(
      'content' => $content,
      'title' => $owner->name
  ));

  echo elgg_view_page($profile->title, $body);
}


function tabbed_profile_draw_group_profile($profile) {
  // turn this into a core function
	global $autofeed;
	$autofeed = true;

	// this context issue is unique to the AU theme, not necessary for core compatibility
	if ($profile->profile_type == 'widgets' && $profile->group_sidebar == 'no') {
	  elgg_push_context('group_profile_tabs');
	}
	else {
	  // default
	  elgg_push_context('group_profile');
	}

	$group = elgg_get_page_owner_entity();
	$title = $group->name;

	elgg_push_breadcrumb(elgg_echo("groups"), "groups/all");
	elgg_push_breadcrumb($group->name);

	groups_register_profile_buttons($group);

  $layout = 'content';
  $content = '';
  if ($profile->group_sidebar == 'no') {
	$layout = 'one_column';
	$content .= elgg_view_menu('title');
  }

	if (group_gatekeeper(false) && $profile->group_sidebar == 'yes') {
		$sidebar = elgg_view('groups/sidebar/external_tools', array('entity' => $group));
		if (elgg_is_active_plugin('wespot_medoky')) {
			$sidebar .= elgg_view('wespot_medoky/sidebar', array('entity' => $group));
		}
		if (elgg_is_active_plugin('search')) {
			$sidebar .= elgg_view('groups/sidebar/search', array('entity' => $group));
		}
		$sidebar .= elgg_view('groups/sidebar/members', array('entity' => $group));
	} else {
		$sidebar = '';
	}

  if ($profile->profile_type == 'iframe') {
    $content .= elgg_view_layout('tabbed_profile_iframe', array('profile' => $profile));
  }
  else {
    $content .= elgg_view('groups/profile/layout_top', array('entity' => $group, 'profile' => $profile)) . elgg_view('groups/profile/layout_bottom', array('entity' => $group, 'profile' => $profile));
  }

	$params = array(
		'content' => $content,
		'sidebar' => $sidebar,
		'title' => $title,
		'filter' => '',
	);
	$body = elgg_view_layout($layout, $params);

	echo elgg_view_page($group->name, $body);
}



function tabbed_profile_generate_default_profile($page_owner) {
  if (!elgg_instanceof($page_owner, 'user') && !elgg_instanceof($page_owner, 'group')) {
    return false;
  }

  $context = elgg_get_context();
  elgg_set_context('tabbed_profile_permissions');
  $ignore = elgg_set_ignore_access(true);

  $profile = new ElggObject();
  $profile->subtype = 'tabbed_profile';
  $profile->title = elgg_echo('tabbed_profile:default');
  $profile->owner_guid = elgg_get_logged_in_user_guid();
  $profile->container_guid = $page_owner->getGUID();
  $profile->access_id = ACCESS_PUBLIC;
  $profile->save();

  // save our metadata
  $profile->order = 1;
  $profile->default = 1;
  $profile->profile_type = 'widgets';
  $profile->widget_layout = elgg_instanceof($page_owner, 'user') ? 3 : 2;
  $profile->widget_profile_display = 'yes';
  $profile->group_sidebar = 'yes';
  $profile->md_version = '1.5';

  $page_owner->tabbed_profile_setup = 1;

  elgg_set_ignore_access($ignore);
  elgg_set_context($context);

  return $profile;
}


function tabbed_profile_get_last_order($container) {
  $profiles = elgg_get_entities_from_metadata(array(
    'types' => array('object'),
    'subtypes' => array('tabbed_profile'),
    'container_guids' => array($container->getGUID()),
    'metadata_names' => array('order'),
    'order_by_metadata' => array('name' => 'order', 'direction' => 'DESC', 'as' => 'integer'),
    'limit' => 1
  ));

  if (!$profiles || !is_array($profiles)) {
    return 0;
  }

  return $profiles[0]->order;
}


function tabbed_profile_get_widgets($owner_guid, $context, $profile) {
  $dbprefix = elgg_get_config('dbprefix');

  $options = array(
	'type' => 'object',
	'subtype' => 'widget',
	'owner_guid' => $owner_guid,
	'private_setting_name' => 'context',
	'private_setting_value' => $context,
	'limit' => 0
  );

  // for default profiles widgets won't have a relationship
  // for other tabs there will be a relationship to the profile
  if ($profile->default) {
	$options['wheres'] = array(
			"NOT EXISTS (
				SELECT 1 FROM {$dbprefix}entity_relationships r
				WHERE r.guid_one = e.guid
				AND r.relationship = '" . TABBED_PROFILE_WIDGET_RELATIONSHIP . "')"
	);
  }
  else {
	$options['wheres'] = array(
		"EXISTS (
		  SELECT 1 FROM {$dbprefix}entity_relationships r
		  WHERE r.guid_one = e.guid
		  AND r.relationship = '" . TABBED_PROFILE_WIDGET_RELATIONSHIP . "'
		  AND r.guid_two = {$profile->guid})"
	);
  }

  $widgets = elgg_get_entities_from_private_settings($options);
  if (!$widgets) {
	return array();
  }

  $sorted_widgets = array();
  foreach ($widgets as $widget) {
  	if (!isset($sorted_widgets[(int)$widget->column])) {
		$sorted_widgets[(int)$widget->column] = array();
	}
	$sorted_widgets[(int)$widget->column][$widget->order] = $widget;
  }

  foreach ($sorted_widgets as $col => $widgets) {
	ksort($sorted_widgets[$col]);
  }

  return $sorted_widgets;
}

// creates or removes group tabs according to group options
function create_or_edit_all_tabs($group){

	// get tabs
	$options = array(
		'type' => 'object',
		'subtype' => 'tabbed_profile',
		'container_guid' => $group->guid,
		'limit' => false,
	);
	$content = elgg_get_entities($options);

	$group->tabbed_profile_setup = 1; //causes the link to group to redirect to the first (smallest 'order') tab -> doesn't check for default flag, gladly

    $tab_exists = false;
    foreach ($content as $tab){
        if ($tab->order == 1)
            $tab_exists = true;
    }
    if(!$tab_exists)
        create_tab('tabbed_profile:phase1:label', $group, 1);

    $tab_exists = false;
    foreach ($content as $tab){
        if ($tab->order == 2)
            $tab_exists = true;
    }
    if(!$tab_exists)
        create_tab('tabbed_profile:phase2:label', $group, 2);

    $tab_exists = false;
    foreach ($content as $tab){
        if ($tab->order == 3)
            $tab_exists = true;
    }
    if(!$tab_exists)
        create_tab('tabbed_profile:phase3:label', $group, 3);

    $tab_exists = false;
    foreach ($content as $tab){
        if ($tab->order == 4)
            $tab_exists = true;
    }
    if(!$tab_exists)
        create_tab('tabbed_profile:phase4:label', $group, 4);

    $tab_exists = false;
    foreach ($content as $tab){
        if ($tab->order == 5)
            $tab_exists = true;
    }
    if(!$tab_exists)
        create_tab('tabbed_profile:phase5:label', $group, 5);

    $tab_exists = false;
    foreach ($content as $tab){
        if ($tab->order == 6)
            $tab_exists = true;
    }
    if(!$tab_exists)
        create_tab('tabbed_profile:phase6:label', $group, 6);
}

// checks if a group option is on or off
function check_group_option($group_option){
	$hasTasksModule = true;
	$option_toggle_name = $group_option->name . "_enable";
	$taskOptionName = $option_toggle_name;
	$option_default = $group_option->default_on ? 'yes' : 'no';
	$nextOn = get_input($option_toggle_name, $option_default);
	if ($nextOn == 'yes')
		return true;
	else
		return false;
}

// creates a tab
function create_tab($tab_name, $group, $order){

	$profile = new ElggObject();
	$profile->subtype = 'tabbed_profile';
	$profile->owner_guid = elgg_get_logged_in_user_entity()->guid;
	$profile->container_guid = $group->guid;
	$profile->order = $order;
	$profile->title = $tab_name;
	$profile->access_id = get_default_access();
	$profile->save();
	$profile->profile_type = "widgets";
	$profile->widget_layout = "2";
	$profile->widget_profile_display = "yes";
	$profile->iframe_url = "http://";
	$profile->iframe_height = 500;
	$profile->group_sidebar = "yes";

}

