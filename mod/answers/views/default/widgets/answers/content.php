<?php
/**
 * Answers widget content view
 */

$num = $vars['entity']->num_display;

elgg_load_library('answers:utilities');

// we get this here when saving widget preferences:
$phase = (int) $vars['entity']->phase;

if(!$phase) {
  $matches = null;
  preg_match("/tab\/(\d+)$/i", $_SERVER['REQUEST_URI'], $matches);
  $tab = $matches[1];
  if($tab) {
    $phase = get_entity($tab)->order;
  }
}

$activity_id = $vars['entity']->activity_id;

$options = array(
	'type' => 'object',
	'subtype' => 'question',
	'container_guid' => $vars['entity']->owner_guid,
	'limit' => $num,
	'full_view' => FALSE,
	'pagination' => FALSE,
);

$get_content = function ($options) use ($phase, $activity_id) {
    $filter = function($element) use ($phase, $activity_id) {
        return ($element->phase == $phase || (!$element->phase && $phase == 1)) &&
        ($element->activity_id == $activity_id || !$activity_id);
    };
    if($options['count']) { # because of how elgg_list_entities works
        $options['count'] = FALSE;
        return count(array_filter(answers_get_sorted_questions($options['container_guid'], 'votes'), $filter));
    } else {
        return array_filter(answers_get_sorted_questions($options['container_guid'], 'votes'), $filter);
    }
};

$content = $phase ? elgg_list_entities($options, $get_content, 'elgg_view_entity_list', true) : "";

echo $content;

$new_link = "";

if (elgg_get_page_owner_entity()->canWriteToContainer())
{
    $new_link = $phase ? elgg_view('output/url', array(
        'href' => "answers/add/".$vars['entity']->owner_guid . "?phase=" . $phase . "&activity_id=" . $activity_id,
        'text' => elgg_echo('answers:add'),
        'is_trusted' => true,
    )) : "Refresh to add question";
}

if ($content) {
	$url = "answers/group/" . elgg_get_page_owner_entity()->getGUID();
	$more_link = elgg_view('output/url', array(
		'href' => $url . "?phase=" . $phase . "&activity_id=" . $activity_id,
		'text' => elgg_echo('answers:more'),
		'is_trusted' => true,
	));
    if($new_link != "") { $new_link = ' | ' . "<span>" . $new_link . "</span>"; }
	echo "<span class=\"elgg-widget-more\">$more_link</span>" . $new_link;
} else {
	echo elgg_echo('answers:none');

	echo "<br><br>";
    echo $new_link;
}
