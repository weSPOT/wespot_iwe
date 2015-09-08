<?php
if(elgg_is_active_plugin("developers")){
	$language = array (
	  'theme_preview:miscellaneous' => 'Overige',
	  'developers:label:system_cache' => 'Gebruik systeem cache',
	  'developers:help:system_cache' => 'Zet dit uit tijdens ontwikkeling. Zo niet, dan zullen de wijzigingen niet geregistreerd worden.',
	  'admin:develop_tools:unit_tests' => 'Unittesten',
	  'developers:unit_tests:description' => 'Elgg heeft unit en integratie testen voor het detecteren van bugs in de core classes en functies.',
	  'developers:unit_tests:warning' => 'Waarschuwing: Doe deze testen niet op een productie site. Deze kan de database beschadigen.',
	  'developers:unit_tests:run' => 'Uitvoeren',
	  'admin:develop_tools' => 'Tools',
	  'admin:develop_tools:preview' => 'Theming zandbak',
	  'admin:develop_tools:inspect' => 'Inspecteer',
	  'admin:developers' => 'Ontwikkelaars',
	  'admin:developers:settings' => 'Instellingen',
	  'elgg_dev_tools:settings:explanation' => 'Beheer je ontwikkel- en debuginstellingen hieronden. Sommige van de instellingen zijn ook beschikbaar op andere beheer pagina\'s.',
	  'developers:label:simple_cache' => 'Gebruik simple cache',
	  'developers:help:simple_cache' => 'Zet file cache uit tijdens het ontwikkelen. Anders zullen wijzigingen in je views (inclusief CSS) worden genegeerd.',
	  'developers:label:debug_level' => 'Trace-niveau',
	  'developers:help:debug_level' => 'Dit beheerd hoeveel informatie er gelogd wordt. Zie elgg_log() voor meer informatie',
	  'developers:label:display_errors' => 'Toon PHP fatale fouten',
	  'developers:help:display_errors' => 'Standaard verbergt de .htaccess van Elgg het weergeven van fatale fouten',
	  'developers:label:screen_log' => 'Log naar het scherm',
	  'developers:help:screen_log' => 'Dit toont het resultaat van elgg_log en elgg_dump() op de webpagina.',
	  'developers:label:show_strings' => 'Toon vertaling sleutels',
	  'developers:help:show_strings' => 'Dit toont de vertaling sleutels die gebruikt worden door elgg_echo().',
	  'developers:label:wrap_views' => 'Omcirkel views',
	  'developers:help:wrap_views' => 'Dit omcirkeld bijna alle views met HTML commentaar. Handig om erachter te komen uit welke view een stuk HTML komt.',
	  'developers:label:log_events' => 'Log events en plugin hooks',
	  'developers:help:log_events' => 'Schrijf events en plugin hook naar de log. Waarschuwing: dit zijn er veel per pagina.',
	  'developers:debug:off' => 'Uit',
	  'developers:debug:error' => 'Fout',
	  'developers:debug:warning' => 'Waarschuwing',
	  'developers:debug:notice' => 'Bericht',
	  'developers:inspect:help' => 'Inspecteer de configuratie van het Elgg framewerk',
	  'developers:event_log_msg' => '%s: \'%s, %s\' in %s',
	  'theme_preview:general' => 'Introductie',
	  'theme_preview:breakout' => 'Ontsnap uit iframe',
	  'theme_preview:buttons' => 'Knoppen',
	  'theme_preview:components' => 'Componenten',
	  'theme_preview:forms' => 'Formulieren',
	  'theme_preview:grid' => 'Raster',
	  'theme_preview:icons' => 'Iconen',
	  'theme_preview:modules' => 'Modules',
	  'theme_preview:navigation' => 'Navigatie',
	  'theme_preview:typography' => 'Typografie',
	  'developers:settings:success' => 'Instellingen opgeslagen',
	);
	add_translation("nl", $language);
}