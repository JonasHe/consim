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

/*
* These are errors which can be triggered by sending invalid data to the
* ConSim extension API.
*/
$lang = array_merge($lang, array(
	'EXCEPTION_FIELD_MISSING'		=> 'Erforderliches Feld fehlt',
	'EXCEPTION_INVALID_ARGUMENT'	=> 'Ungültige Eingabe in `%1$s`. Grund: %2$s',
	'EXCEPTION_OUT_OF_BOUNDS'		=> 'Feld `%1$s` enthält unzulässige Zeichen',
	'EXCEPTION_TOO_LONG'			=> 'Eingabe zu lang.',
	'EXCEPTION_TOO_SHORT'			=> 'Eingabe zu kurz.',
	'EXCEPTION_NOT_UNIQUE'			=> 'Uneindeutige Eingabe. (Bezeichnung bereits vergeben)',
	'EXCEPTION_UNEXPECTED_VALUE'	=> 'Unerwartete Zeichen in Feld `%1$s`. Grund: %2$s',
	'EXCEPTION_ILLEGAL_CHARACTERS'	=> 'Eingabe enthält für dieses Feld nicht zulässige Zeichen.',
));
