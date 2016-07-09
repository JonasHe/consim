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
	'ADD_ANNIVERSARY'			=> 'Feiertag hinzuf&uuml;gen',
	'ANNIVERSARY_DATE'			=> 'Datum',
	'ANNIVERSARY_DAY'			=> 'Tag (1-31)',
	'ANNIVERSARY_DELETE'		=> 'Feiertag l&ouml;schen',
	'ANNIVERSARY_DELETE_CONFIRM'=> 'Willst du den Feiertag wirklich l&ouml;schen?',
	'ANNIVERSARY_EVENT'			=> 'Ereignis',
	'ANNIVERSARY_LINK'			=> 'Verweis',
	'ANNIVERSARY_MONTH'			=> 'Monat (1-12)',
	'ANNIVERSARY_YEAR'			=> 'Jahr (optional)',
	'SAVE'						=> 'Speichern',
	
));
