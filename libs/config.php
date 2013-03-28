<?php

define('URL', 'http://212.224.72.68/mvc/');
define('LIBS', 'libs/');
define("DEBUG_MODE", true);
define("SERVICE_MODE", 1);

define("DB_HOST", "localhost"); // Adresse des Datenbankservers, meistens localhost
define("DB_USER", "dm1php"); // MySQL Benutzername
define("DB_PASS", "BETy3b2Y"); // MySQL Passwort
define("DB_NAME", "modulverwaltung"); // Name der Datenbank
// The sitewide hashkey, do not change this because its used for passwords!
// This is for other hash keys... Not sure yet

//define the Database default errors
define("DB_ERR_SELECT", "DB-Fehler SELECT: ");
define("DB_ERR_UPT", "DB-Fehler UPDATE");
define("DB_ERR_INSERT", "DB-Fehler INSERT");
define("DB_ERR_DELETE", "DB-Fehler DELETE"); 

define("LOGIN_ERR", "Die eingebenen Kombination aus <br>Benutzername und Passwort ist falsch!");
define("INACTIV_ACCOUNT", "Ihr Account ist noch nicht freigeschalten, bitte pr&uuml;fen Sie die Aktivierungsmail");
define("PAGE_ACCESS_ERR", "Sie besitzen keine ausreichenden Rechte um die angeforderte Seite anzuzeigen.");
define("GLOBAL_PAGE_ERR", "Ups.. da ist wohl leider etwas schief gelaufen!<br> 
                           Sollte das Problem weiterhin bestehen, so kontaktieren Sie bitte den Webmaster");
define("LOGIN_SMODE_ALERT", "Die Seite befindet sich im Wartungsmodus!");
define("GLOBAL_SMODE_ALERT", "Die Seite befindet sich im Wartungsmodus! Nur Admin Anmeldung möglich!");
define("MOD_SUBSCRIBE_SUCC", "Einschreibung in Modul erfolgreich");
define("EXAM_SUBSCRIBE_SUCC", "Anmeldung zur Pr&uuml;fung erfolgreich");
define("MOD_SUBSCRIBE_ERR", "Keine Einschreibung m&ouml;glich! Maximale Anzahl an Teilnehmern erreicht!");
define("MOD_ALREADY_SUBSCRIBED", "Sie haben sich bereits eingeschrieben!");
define("MOD_SUBSCRIBE_ACCESS_ERR", "Sie haben keine Berechtigung zur Einschreibung!");
define("MOD_UNSUBSCRIBE_SUCC", "Erfolgreich ausgetragen!");
define("MOD_UNSUBSCRIBE_ACCESS_ERR", "Sie haben keine Berechtigung zum Austragen!");
define("EXAM_UNSUBSCRIBE_SUCC", "Abmeldung von Pr&uuml;fung erfolgreich");
define("EXAM_UNSUBSCRIBE_ERR", "Bitte erst von Pr&uuml;fung abmelden!");
define("MOD_SUBSCRIBE_ERR", "Keine Einschreibung m&ouml;glich! Maximale Anzahl an Teilnehmern erreicht!");
define("MOD_ALREADY_SUBSCRIBED", "Sie haben sich bereits eingeschrieben!");
define("MOD_SUBSCRIBE_ACCESS_ERR", "Sie haben keine Berechtigung zur Einschreibung!");
define("MOD_UNSUBSCRIBE_SUCC", "Erfolgreich ausgetragen!");
define("MOD_UNSUBSCRIBE_ACCESS_ERR", "Sie haben keine Berechtigung zum Austragen!");
define("EXAM_UNSUBSCRIBE_SUCC", "Abmeldung von Pr&uuml;fung erfolgreich");
define("EXAM_UNSUBSCRIBE_ERR", "Bitte erst von Pr&uuml;fung abmelden!");
define("MOD_ACT_ERR", "Bitte aktivieren Sie erst das Hauptmodul ");
define("MOD_ACT_ACCESS_ERR", "Keine Berechtigung zur Aktivierung!");
define("MOD_ACT_NOSUB_ERR", "Bitte erst ein Teilmodul anlegen!");
define("MOD_ACT_SUCC", "Modul erfolgreich aktiviert");
define("MOD_DEACT_SUCC", "Modul wurde deaktiviert!");
define("MOD_DEL_ACCESS_ERR", "Keine Berechtigung zum L&ouml;schen!");
define("MOD_DEL_SUCC", "Module erfolgreich gel&ouml;scht!");

