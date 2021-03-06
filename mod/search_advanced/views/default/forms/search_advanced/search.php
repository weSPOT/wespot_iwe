<?php

if($vars["search_type"]){
	echo elgg_view("input/hidden", array("name" => "search_type", "value" => $vars["search_type"]));
}

if($vars["type"]){
	echo elgg_view("input/hidden", array("name" => "entity_type", "value" => $vars["type"]));
}

if($vars["subtype"]){
	echo elgg_view("input/hidden", array("name" => "entity_subtype", "value" => $vars["subtype"]));
}

echo elgg_view("input/text", array("name" => "q", "value" => $vars["query"]));

if(($user = elgg_get_logged_in_user_entity()) && elgg_trigger_plugin_hook("search_multisite", "search", array("user" => $user), false)){
	$current_value = 0;
	if(!empty($_SESSION["search_advanced:multisite"])){
		$current_value = $_SESSION["search_advanced:multisite"];
	}

	echo "<div class='float-alt'>";
	echo elgg_echo("search_advanced:multisite:label") . " ";
	echo elgg_view("input/dropdown", array("name" => "multisite", "value" => $current_value, "options_values" => array(0 => elgg_echo("search_advanced:multisite:current"), 1 => elgg_echo("search_advanced:multisite:mine"))));
	echo "</div>";
}