<?php

/* * ************************************************
 * PluginLotto.com                                 *
 * Copyrights (c) 2005-2011. iZAP                  *
 * All rights reserved                             *
 * **************************************************
 * @author iZAP Team "<support@izap.in>"
 * @link http://www.izap.in/
 * Under this agreement, No one has rights to sell this script further.
 * For more information. Contact "Tarun Jangra<tarun@izap.in>"
 * For discussion about corresponding plugins, visit http://www.pluginlotto.com/pg/forums/
 * Follow us on http://facebook.com/PluginLotto and http://twitter.com/PluginLotto
 */

add_translation('fr', array(
    'izap-bridge:yes' => 'Oui',
    'izap-bridge:no' => 'Non',
    'izap-bridge:enable' => 'Actif',
    'izap-bridge:disable' => 'Inactif',
    'izap-bridge:invalid_entity' => 'Entité invalide',
    'item:annotation:blog_auto_save' => 'Brouillon',
    'item:annotation:generic_comment' => 'Commentaires',
    'izap-bridge:APIKEY' => 'Clé API',
    'izap-elgg-bridge:api_settings' => 'Paramètres Pluginlotto.com',
    'izap-bridge:API_MSG' => 'Si vous n\'en avez pas, allez vous enregistrer sur:
        <a href="http://www.pluginlotto.com/" target="_blank">http://www.pluginlotto.com/</a> en précisant le nom de domaine :  "<strong>'.$_SERVER['HTTP_HOST'].'</strong>".',
    'izap-bridge:delete' => 'Supprimer',
    'izap-bridge:are_you_sure' => 'Êtes vous sûr/e ?',
    'izap-bridge:delete_success' => 'Supprimé OK',
    'izap-bridge:delete_error' => 'Erreur lors de la suppression. Erreur :: %s',
    'menu:page:header:izap' => 'Human Community',
    'admin:help' => 'Aide',
    'admin:help:izap_help' => 'Aide pour le module',
    'admin:help:izap_help?plugin=izap-elgg-bridge' => 'iZAP Bridge',
    'izap-elgg-bridge:save' => 'Saucegarder',
    'izap-elgg-bridge:saving' => 'Enregistrement.....',
    'izap-elgg-bridge:edit' => 'Modifier',
    'izap-elgg-bridge:close' => 'Fermer',
    'izap-bridge:add_api' => 'Cliquer sur le lien pour entrer la clé API izap-elgg-bridge. <a href="/admin/plugin_settings/izap-elgg-bridge">izap-elgg-bridge</a>',
    
// for antispam
    'izap-bridge:consicutive_post_time' => 'Délai minimum entre deux posts (Secondes)',
    'izap-bridge:consicutive_post_time_msg' => 'Le membre ne pourra pas reposter avant ce délai',
    'izap-bridge:maximum_pings_for_spammer' => 'Nb de tentatives maximum par membre,
          avant de le/la marquer comme "spammeur"',
    'izap-bridge:maximum_pings_for_spammer_msg' => 'Ce membre sera signalé comme spammeur si il/elle dépasse ce nb de tentatives dans le temps imparti',
    'admin:users:marked-spammers' => 'Membres marqués comme spammeurs',
    'admin:users:suspected-spammers' => 'Spammeurs suspectés',
    'izap_antispam:delete' => 'Supprimer',
    'izap-antispam:submit_spam' => 'Confirmé comme spammeur',
    'izap-antispam:not_spammer' => 'Non spammeur',
    'admin:izap-antispam:submit' => 'Déclarer un spammeur',
    'izap-antispam:spam' => 'données sur le spammeur',
    'izap-antispam:table:name' => 'Nom',
    'izap-antispam:table:username' => 'Nom d\'utilisateur',
    'izap-antispam:table:registeredtime' => 'Heure d\'enregistrement',
    'izap-antispam:table:postcount' => 'Nb de posts',
    'izap-antispam:table:avgposttime' => 'Temps approximatif',
    'izap-antispam:no data' => 'aucune entrée pour ce membre existe',
    'izap-antispam:wrong_entity' => 'Mauvaise entité',
    'izap-antispam:user_banned' => 'Le membre a été désactivé',
    'izap_antispam:spam_log' => 'Relevé des Spams',
    'izap-antispam:spammer_data' => "Données du spammeur",
    'izap-antispam:table:totalfriends' => 'Nombre de contacts',
    'izap-antispam:table:totallogins' => 'Nombre de logins',
    'izap-antispam:name' => 'Affichage du nom: ',
    'izap-antispam:table:userdata' => 'Données du membre',
    'izap-antispam:username' => 'Nom d\'utilisateur: ',
    'izap-antispam:email' => 'Email: ',
    'izap-antispam:total' => 'Nb de ',
    'izap-antispam:confirm' => 'Êtes-vous sûr/e de vouloir marquer %s, comme spammeur ? Toutes ses données seront supprimées et le membre sera inactivé.',
    'izap-antispam:slowdown_warning' => 'Slowdown Beamer',
    'izap-antispam:spammer_notice' => 'Vous n\'avez plus la permission de poster de nouvelles données. Merci de contacter votre animateur/trice.',
    'izap-antispam:ban_reason' => 'Membre détecté comme spammeur, désactivé par l\'animateur/trice',
    'izap-antispam:spammer_probability' => 'Probabilité de spam',
    'izap-bridge:action_to_spammers' => 'Action contre le spammeur',
    'izap-bridge:spammer_act_yes' => 'Désactiver automatiquement',
    'izap-bridge:spammer_act_no' => 'M\'envoyer une notification et stopper les publications du membre.',
    'izap-bridge:antispam:enable' => 'Activer l\'antispam',
    'izap:bridge:mark_spammer' => 'Marquer comme spammeur',
    'izap:bridge:unmark_spammer' => 'De-marquer comme spammeur',
    'izap:bridge:suspected_spammer' =>'Spammeur suspecté',
    'izap-elgg-bridge:spammer_suspected' => 'Ce membre a été marqué comme spammeur',
   
    'izap:elgg:bridge:form_empty' => 'Le titre est obligatoire',
    // added on 06june11
    // payment gateway
    'izap_payment:no_gateway_found' => 'No payment gateway found, Please contact site administration',
    // PLUGIN SETTINGS
    'izap-elgg-bridge:bridge_settings' => 'Paramètres',
    'izap-elgg-bridge:enable_threaded_comments' => 'Activer les fils de commentaires pour toute la communauté',
    'izap-elgg-bridge:save' => 'Sauvegarder',
    'izap-elgg-bridge:settings_saved' => 'Paramètres sauvegardés.',
    'izap-elgg-bridge:error_saving_settings' => 'Erreur dans la sauvegarde des paramètres.',
    'izap-elgg-bridge:plugin_data_access' => 'L\'accès aux données de ce module doivent-elles être accessible à l\'admin seul',
    'izap-elgg-bridge:amazon_settings' => 'Paramètres Amazon',
    'izap-elgg-bridge:general_settings' => 'Paramètres généraux',
    'izap-bridge:AmazonsecretKEY' => 'Clé d\'accès',
    'izap-bridge:applicationkey' => 'Clé secrète',
    'izap-bridge:choose_currency_for_site'=>'Devise utilisée',
    'izap-bridge:choose_currency_msg'=>'Elle sera la devise utilisée par défaut',
    // errors and successes
    'izap-elgg-bridge:error:delete' => 'Erreur de suppression',
    'izap-elgg-bridge:error:setting_not_found' => 'Impossible de trouver les paramètres pour le module <b>%s</b> plugin, le module est désactivé.',
    // objects
    'item:object:IzapThreadedComments' => 'File de commentaires',
    'izap-elgg-bridge:file_not_exists' => "Mauvaise adresse URL, merci de vérifier à nouveau.",
    'izap-elgg-bridge:error_edit_permission' => "Vous ne pouvez pas sauvegarder ces données.",
    'izap-elgg-bridge:error_empty_input_fields' => "Merci de remplir tous les champs obligatoires.",
    'izap-elgg-bridge:deleted' => "Supprimé.",
    'izap-elgg-bridge:saved' => "Sauvegardé",
    'izap-elgg-bridge:mail_not_sent' => "Email non envoyé, le serveur est sur-utilisé en ce moment, veuillez essayer plus tard.",
    'izap-elgg-bridge:your_name' => "Votre nom *",
    'izap-elgg-bridge:your_email' => "Votre email *",
    'izap-elgg-bridge:your_contact' => "Contact no",
    'izap-elgg-bridge:your_friend_name' => "Le nom de votre ami *",
    'izap-elgg-bridge:your_friend_email' => "Son email *",
    'izap-elgg-bridge:message' => "Message *",
    'izap-elgg-bridge:cannotload' => 'Entity couldn\'t be loaded, please re-check the url, if you typed it manually.',
    'izap-elgg-bridge:comments' => 'Commentairess',
    'izap-elgg-bridge:send_to_friend' => 'Envoyer à un ami',
    'izap-elgg-bridge:not_valid_email' => 'adresse mail invalide',
    'izap-elgg-bridge:not_valid_entity' => 'Entité non valide',
    'izap-elgg-bridge:success_send_to_friend' => 'MEssage envoyé.',
    'izap-elgg-bridge:error_send_to_friend' => 'Erreur d\'expédition.',
    'izap-elgg-bridge:terms' => 'Conditions d\'utilisation',
    'izap-elgg-bridge:comment' => 'Commentaire',
    'izap-elgg-bridge:file_not_exists' => 'le fichier n\'existe pas',
    'izap-elgg-bridge:delete' => 'Suppprimer',
    'izap-elgg-bridge:by' => 'par',
    'izap-elgg-bridge:date_day' => 'dd',
    'izap-elgg-bridge:date_month' => 'mm',
    'izap-elgg-bridge:date_year' => 'yy',
    'izap-elgg-bridge:cancel' => 'Annuler',
    'izap-elgg-bridge:threaded_comment_notify_subject' => 'Vous avez un nouveau commentaire sur %s',
    'izap-elgg-bridge:new_comment' => 'Il y a un nouveau commentaire',
    'izap-elgg-bridge:yes' => 'oui',
    'izap-elgg-bridge:no' => 'non',
    'izap_elgg_bridge:error_empty_input_fields' => 'Des champs obligatoires manquent',
    'izap-elgg-bridge:rates' => "Votes",
    'izap-elgg-bridge:rateit' => "Votez",
    'izap-elgg-bridge:text' => "Aimez-vous cela?",
    'izap-elgg-bridge:rated' => "Vous avez déjà voté.",
    'izap-elgg-bridge:badguid' => "Error, we haven't found any item to rate.",
    'izap-elgg-bridge:badrate' => "Votre vote doit être entre 0 et 5.",
    'izap-elgg-bridge:saved' => "A voté!.",
    'izap-elgg-bridge:error' => "Your izap-elgg-bridge could not be saved. Please try again.",
    'izap-elgg-bridge:rate-0' => "Très mauvais (0)",
    'izap-elgg-bridge:rate-1' => "Mauvais (1)",
    'izap-elgg-bridge:rate-2' => "Pas mal(2)",
    'izap-elgg-bridge:rate-3' => "Bon (3)",
    'izap-elgg-bridge:rate-4' => "Très bon (4)",
    'izap-elgg-bridge:rate-5' => "Excellent ! (5)",

    // Exceptions
    'izap-elgg-bridge:Exception:mandatory_array_indexes' => 'Mandatory associative indexes: %s',
    'izap-elgg-bridge:Exception:no_metadata' => 'Undefined "%s" call.',
    'izap-elgg-bridge:Exception:no_method' => 'Undefined function name: "%s"',
    'izap-elgg-bridge:Exception:wrong_credential_or_connection_issue' => 'Could not obtain authenticated Http client object. %s',

    'izap-elgg-bridge:comments:on' => 'activés',
    'izap-elgg-bridge:comments' => 'Commentaires',
    'izap-elgg-bridge:comments:off' => 'Désactivés'
));
