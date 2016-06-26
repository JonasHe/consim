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
	'ADD_NEWS'					=> 'News hinzuf&uuml;gen',
	'ADD_CHANNEL'				=> 'Kanal hinzuf&uuml;gen',
	'ADD_TOPIC'					=> 'Themenbereich hinzufügen',
	'ALL_CHANNEL'				=> 'Alle Kan&auml;le',
	'ALL_NEWS'					=> 'Alle Neuigkeiten',
	'ALL_TOPICS'				=> 'Alle Themenbereiche',
	'CHANNEL'					=> 'Kanal',
	'CHANNEL_BACKGROUND'		=> 'Hintergrund des Kanals (bspw. "green","#efefef",...)',
	'CHANNEL_COLOR'				=> 'Schriftfarbe des Kanals',
	'CHANNEL_NAME'				=> 'Name des Kanals',
	'CONFIRM_DELETE_CHANNEL'	=> 'M&ouml;chtest du den Kanal wirklich l&ouml;schen?',
	'CONFIRM_DELETE_NEWS'		=> 'M&ouml;chtest du die Neuigkeit wirklich l&ouml;schen?',
	'CONFIRM_DELETE_TOPIC'		=> 'M&ouml;chtest du den Themenbereich wirklich l&ouml;schen?',
	'DELETE_CHANNEL'			=> 'Kanal l&ouml;schen',
	'DELETE_NEWS'				=> 'Neuigkeit l&ouml;schen',
	'DELETE_TOPIC'				=> 'Themenbereich l&ouml;schen',
	'EDIT_CHANNEL'				=> 'Kanal bearbeiten',
	'EDIT_NEWS'					=> 'News bearbeiten',
	'GROUPS_ALLOWED'			=> 'Folgende Gruppe kann den Kanal sehen',
	'NEWS_CONTENT'				=> 'Inhalt',
	'NEWS_TOPIC'				=> 'Themenbereich',
	'SAVE'						=> 'Speichern',
	'VREFRESH'					=> 'Vertikal aktualisieren',
));
