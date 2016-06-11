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

<<<<<<< HEAD
	'REGISTER'                 	=> 'ConSim - Registrierung',
	'PERSON'                   	=> 'Charakterbeschreibung',
	'VORNAME'                  	=> 'Vorname',
	'NACHNAME'           	   	=> 'Nachname',
	'GESCHLECHT'		       	=> 'Geschlecht',
	'GEBURTSLAND'              	=> 'Geburtsland',
	'RELIGION'                 	=> 'Religion',
	'HAARFARBE'                	=> 'Haarfarbe',
	'AUGENFARBE'               	=> 'Augenfarbe',
	'BESONDERE_MERKMALE'       	=> 'Besondere Merkmale',

	'ATTRIBUTE'                	=> 'Fähigkeit',
	'SPRACHE_TADSOWISCH'       	=> 'Sprache: Tadsowisch',
	'SPRACHE_BAKIRISCH'        	=> 'Sprache: Bakirisch',
	'SPRACHE_SURANISCH'        	=> 'Sprache: Suranisch',
	'RHETORIK'                 	=> 'Rhetorik',
	'ADMINISTRATION'            => 'Administration',
	'WIRTSCHAFT'               	=> 'Wirtschaft',
	'TECHNIK'                  	=> 'Technik',
	'NAHKAMPF'                 	=> 'Nahkampf',
	'SCHUSSWAFFEN'             	=> 'Schusswaffen',
	'SPRENGMITTEL'             	=> 'Sprengmittel',
	'MILITARKUNDE'             	=> 'Militärkunde',
	'SPIONAGE'                 	=> 'Spionage',
	'SCHMUGGEL'                 => 'Schmuggel',
	'MEDIZIN'                  	=> 'Medizin',
	'UBERLEBENSKUNDE'          	=> 'Überlebenskunde',

	'INVENTORY'                 => 'Inventar',
	'BAK_RUBEL'                 => 'Bakirischer Rubel',
	'BAK_RUBEL_ABBR'            => 'BR',
	'SUR_DINAR'                 => 'Suranischer Dinar',
	'SUR_DINAR_ABBR'            => 'SD',
	'FRT_DOLLAR'                => 'Tadsowischer Dollar',
	'FRT_DOLLAR_ABBR'           => 'TD',

	'PROVINCE'                  => 'Provinz',
	'TRAVELING'                 => 'Reisen',
	'BUILDING'                  => 'Gebäude',
	'BACK_TO'                   => 'Zurück nach',
	'LOCATION'                  => 'Standort',
=======
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

	'ATTRIBUTE'					=> 'Fähigkeit',
	'SPRACHE_TADSOWISCH'		=> 'Sprache: Tadsowisch',
	'SPRACHE_BAKIRISCH'			=> 'Sprache: Bakirisch',
	'SPRACHE_SURANISCH'			=> 'Sprache: Suranisch',
	'RHETORIK'					=> 'Rhetorik',
	'ADMINISTRATION'			=> 'Administration',
	'WIRTSCHAFT'				=> 'Wirtschaft',
	'TECHNIK'					=> 'Technik',
	'NAHKAMPF'					=> 'Nahkampf',
	'SCHUSSWAFFEN'				=> 'Schusswaffen',
	'SPRENGMITTEL'				=> 'Sprengmittel',
	'MILITARKUNDE'				=> 'Militärkunde',
	'SPIONAGE'					=> 'Spionage',
	'SCHMUGGEL'					=> 'Schmuggel',
	'MEDIZIN'					=> 'Medizin',
	'UBERLEBENSKUNDE'			=> 'Überlebenskunde',

	'MANNLICH'					=> 'Männlich',
	'WEIBLICH'					=> 'Weiblich',
	'FRT'						=> 'Tadsowien',
	'BAK'						=> 'Bakirien',
	'SUR'						=> 'Suranien',
	'ORTHODOX'					=> 'Orthodox',
	'KATHOLISCH'				=> 'Katholisch',
	'MUSLIMISCH'				=> 'Muslimisch',
	'ATHEISTISCH'				=> 'Atheistisch',
	'SCHWARZ'					=> 'Schwarz',
	'ROT'						=> 'Rot',
	'HELLBRAUN'					=> 'Hellbraun',
	'DUNKELBRAUN'				=> 'Dunkelbraun',
	'BLOND'						=> 'Blond',
	'DUNKELBLOND'				=> 'Dunkelblond',
	'GRUN'						=> 'Grün',
	'GRAU'						=> 'Grau',
	'BRAUN'						=> 'Braun',
	'GRUNBRAUN'					=> 'Grünbraun',
	'BLAU'						=> 'Blau',
	'BLAUGRUN'					=> 'Blaugrün',
	'BERNSTEIN'					=> 'Bernstein',
	'KEINE'						=> 'Keine',
	'NARBE'						=> 'Narbe',
	'SCHMUCK'					=> 'Schmuck',
	'DEFORMIERUNG'				=> 'Deformierung',
	'STARK_UBERGEWICHTIG'		=> 'Stark Übergewichtig',

	'PROVINCE'					=> 'Provinz',
	'TRAVELING'					=> 'Reisen',
	'BUILDING'					=> 'Gebäude',
	'BACK_TO'					=> 'Zurück nach',
>>>>>>> refs/remotes/origin/master
));
