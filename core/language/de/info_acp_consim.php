<?php
/**
* @package ConSim for phpBB3.1
*
* @copyright (c) 2015 Marco Candian (tacitus@strategie-zone.de)
* @copyright (c) 2016 Jonas Heitmann (kommodoree@googlemail.com)
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
	'CONSIM_ANNIVERSARY'			=> 'ConSim Feiertage',
	'ACP_CAT_CONSIM'				=> 'ConSim',
	'ACP_CAT_CONSIMMODULES'			=> 'ConSim Module',
	'CONFIRM_DELETE_MARKER'			=> 'M&ouml;chtest du den Marker wirklich l&ouml;schen?',
	'CONSIM_MAP'					=> 'ConSim Karte',
	'CONSIM_MARKERS'				=> 'Eigene Marker',
	'CONSIM_NEWS'					=> 'ConSim News',
	'CONSIM_ROADS'					=> 'Straßen',
	'CONSIM_TITLE'					=> 'ConSim',

	'MARKER_ADD'					=> 'Marker hinzuf&uuml;gen',
	'MARKER_COORDS'					=> 'Position',
	'MARKER_TITLE'					=> 'Text',
	'MARKER_SAVE'					=> 'Marker speichern',
	'MARKER_STYLE'					=> 'Aussehen',

	'ROAD_IS_BLOCKED'				=> 'Straße benutzbar',
	'ROAD_TITLE'					=> 'Straße verbindet',
	'ROAD_TYPE'						=> 'Ausbaustatus der Straße',

	'SAVE'							=> 'Speichern',
));
