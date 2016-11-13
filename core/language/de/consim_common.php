<?php
/**
* @package ConSim for phpBB3.1
*
* @copyright (c) 2015 Marco Candian (tacitus@strategie-zone.de)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*/

/**
* DO NOT CHANGE
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
//
// Some characters you may want to copy&paste:
// ’ » “ ” …
//

$lang = array_merge($lang, array(
	//ConSim header
	'CONSIM'					=> 'ConSim',
	'CONSIM_TITLE'				=> 'ConSim',
	'ACTIONLIST'				=> 'Aktvitäten',

	'ANNIVERSARY_NONE'			=> 'Keine historischen Ereignisse',
	'ANNIVERSARY_TODAY'			=> 'Heute',
	'ANNIVERSARY_TOMORROW'		=> 'Morgen',
	
	'REGISTER'					=> 'ConSim - Registrierung',
	'PERSON'					=> 'Charakterbeschreibung',
	'VORNAME'					=> 'Vorname',
	'NACHNAME'					=> 'Nachname',
	'GESCHLECHT'				=> 'Geschlecht',
	'GEBURTSLAND'				=> 'Geburtsland',
	'RELIGION'					=> 'Religion',
	'HAARFARBE'					=> 'Haarfarbe',
	'AUGENFARBE'				=> 'Augenfarbe',
	'BESONDERE_MERKMALE'		=> 'Besondere Merkmale',
	'EXPERIENCE_POINTS'			=> 'Erfahrungspunkte',

	'SKILL'						=> 'Fähigkeit',
	'INVENTORY'					=> 'Inventar',

	'ACTIVITY'					=> 'Aktivität',
	'PROVINCE'					=> 'Provinz',
	'TRAVELING'					=> 'Reisen',
	'BUILDING'					=> 'Gebäude',
	'BUILDINGS'					=> 'Gebäude',
	'BACK_TO'					=> 'Zurück nach',
	'OVERVIEW'					=> 'Übersicht',
	'LOCATION'					=> 'Standort',
	'ACCEPT'					=> 'Annehmen',

	'RESULT'					=> 'Ergebnis',
	'SUCCESSFUL'				=> 'Erfolgreich',
	'UNSUCCESSFUL'				=> 'Erfolglos',
	'FINISHED'					=> 'Beendet',
	'FINISH'					=> 'Beenden',
	'MIN_SKILL'					=> 'Mindestfähigkeit',
	'CONDITIONS'				=> 'Bedingungen',
	'OUTPUT'					=> 'Ertrag',
	'NOTHING'					=> 'keine',

	'WEATHER'					=> 'Wetter',
	'WIND'						=> 'Wind',
	'OWM_NO_CONNECTION'			=> '<span style="color:red">Wetterdaten nicht aktuell</span>',
	'ROADS'						=> 'Straßen',
	'MAP_LEGEND_TITLE'			=> 'Karte der Region',
	'ROAD_TYPE_1'				=> 'Feldweg',
	'ROAD_TYPE_2'				=> 'befestigte Straße',
	'ROAD_TYPE_3'				=> 'Schnellstraße',
	'ROAD_TYPE_4'				=> 'Bahnverbindung',
	'ROAD_TYPE_5'				=> 'Seeweg',
	'ROAD_BLOCKED'				=> 'Straße gesperrt',
	'ROAD_NOT_BLOCKED'			=> 'Sraße frei',
));
