<?php
/**
 * PDF output options selector popup
 */

	$page = elgg_extract("entity", $vars);
	
	error_log ("views/default/forms/groups/export.php"); # DEBUG
	
  // page size selector
	$format_options = array(
		"A4" => elgg_echo("group_tools:export:format:a4"),
		"letter" => elgg_echo("group_tools:export:format:letter"),
		"A3" => elgg_echo("group_tools:export:format:a3"),
		"A5" => elgg_echo("group_tools:export:format:a5"),
	);
	
	$body = "<div>";
	$body .= elgg_echo("group_tools:export:format");
	$body .= "&nbsp;" . elgg_view("input/dropdown", array("name" => "format", "options_values" => $format_options));
	$body .= "</div>";
	
  // include index checkbox
	$body .= "<div>";
	$body .= elgg_view("input/checkbox", array("name" => "include_children", "value" => 1));
	$body .= elgg_echo("group_tools:export:include_subpages");
	$body .= "<br />";
	$body .= elgg_view("input/checkbox", array("name" => "include_index", "value" => 1));
	$body .= elgg_echo("group_tools:export:include_index");
	$body .= "</div>";
	
	$body .= "<br />";
	
  // include sub pages checkbox
	$body .= "<div class='elgg-foot'>";
	$body .= elgg_view("input/hidden", array("name" => "guid", "value" => $page->getGUID()));
	$body .= elgg_view("input/submit", array("value" => elgg_echo("export")));
	$body .= elgg_view("input/reset", array("value" => elgg_echo("cancel"), "class" => "float-alt"));
	$body .= "</div>";
	
	echo elgg_view_module("info", elgg_echo("export"), $body);
  