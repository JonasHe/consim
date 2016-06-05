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
	//Figure
	'MANNLICH'              => 'Männlich',
	'WEIBLICH'              => 'Weiblich',
	'FRT'					=> 'Tadsowien',
	'BAK'					=> 'Bakirien',
	'SUR'					=> 'Suranien',
	'ORTHODOX'				=> 'Orthodox',
	'KATHOLISCH'			=> 'Katholisch',
	'MUSLIMISCH'			=> 'Muslimisch',
	'ATHEISTISCH'			=> 'Atheistisch',
	'SCHWARZ'				=> 'Schwarz',
	'ROT'					=> 'Rot',
	'HELLBRAUN'				=> 'Hellbraun',
	'DUNKELBRAUN'			=> 'Dunkelbraun',
	'BLOND'					=> 'Blond',
	'DUNKELBLOND'			=> 'Dunkelblond',
	'GRUN'					=> 'Grün',
	'GRAU'					=> 'Grau',
	'BRAUN'					=> 'Braun',
	'GRUNBRAUN'				=> 'Grünbraun',
	'BLAU'					=> 'Blau',
	'BLAUGRUN'				=> 'Blaugrün',
	'BERNSTEIN'				=> 'Bernstein',
	'KEINE'					=> 'Keine',
	'NARBE'					=> 'Narbe',
	'SCHMUCK'				=> 'Schmuck',
	'DEFORMIERUNG'			=> 'Deformierung',
	'STARK_UBERGEWICHTIG'	=> 'Stark Übergewichtig',

	//Locations
	'NOBRI'                 => 'Nobri',
	'NOBRI_DESC'            => 'Nobri ist die jüngste und kleinste unter den Provinzhauptstädten der Volksrepublik Bakirien mit ca. 385.000 Einwohnern, erst im Jahr 1984 wurde die Stadt auf die Initiative der regionalen Kommunistischen Partei gegründet und bildete seitdem das Zentrum der Provinz Isoria. '
							  .'Die am 21. Januar dem Todestag Lenins gegründete Stadt sollte ein Modellprojekt der Kommunistischen Partei werden und einen neuen Typus moderne Verwaltungsstadt begründen, Nobri liegt weder strategisch exponiert, noch liegt die Provinzhauptstadt in der Nähe eines der großen Ressourcenvorkommen der Provinz. '
							  .'Ihre Lage zeichnet sich nur durch die Nähe zum größten Hafen der Volksrepublik und ihrer einzigartig zusammengesetzt Bevölkerung aus. Nobri weist die höchste Dichte von Parteimitgliedern in der gesamten Volksrepublik Bakirien aus, weiterhin übersteigt auch das Bruttoeinkommen pro Kopf, dass der beiden anderen wichtigen Städte bei weitem. '
							  .'Die Clique der aus Nobri stammenden Politiker ist im ganzen Land für ihren Einfluss in den wichtigsten Gremien der Partei und des Staates bekannt. <br />'
							  .'Die Stadt wurde um den im Jahr 2009 aufwendig renovierten Leonid Iljitsch Breschnew Turm herum errichtet, die Innenstadt ist geprägt von funktionsmäßigen Verwaltungsbauten.'
							  .'Die Architektur der Stadt ist ganz dem Modellcharakter seiner Gründung unterworfen, klare Abgrenzungen der Bezirke und deutliche Richtlinien für das Bauen neuer Gebäude und deren Verwendungszweck sind in Nobri Alltag.'
							  .'Während sich die Innenstadt hauptsächlich in Staats- oder Parteihand befindet, sind die Außenbezirke um die Innenstadt geprägt von den Bauten der aus Nobri stammenden Politiker und der dortigen Verwaltungseliten.'
							  .'Die Hügel nördlich der Innenstadt gehören zu der gefragtesten Wohngegend in der ganzen Volksrepublik, hinter vorgehaltener Hand wird diese Gegend von neidischen Beobachtern auch das "Sozialistische Beverly Hills" genannt.'
							  .'Auch fehlen in Nobri sämtliche Anzeichne der in der Provinz Isoria vorhandenen suranischen oder tadsowischen Minderheit.',
	'ALATYR'                => 'Alatyr',
	'ALATYR_DESC'           => 'Alatyr ist eine sichtbar abgewirtschaftete Planstadt inmitten endloser Äcker und abgeholzter Wälder. '
							  .'Die verbliebenen 85.000 Einwohner sind zu großem Teil eingewanderte Kosaken auf der Suche nach Arbeit. '
							  .'Eine hohe Kriminalitätsrate und grassierende Arbeitslosigkeit machen Alatyr zu einem sozialen Brennpunkt der Provinz, wenn auch die lokale Parteiführung offiziell nichts von diesen Problemen wissen will. '
							  .'Nach offizieller Stellungnahme sind es die politische Agitation subversiver Elemente wie ausländische NGOs und Schmuggel von europäischen Waren zum Schaden der einheimischen Wirtschaft, welche die Lage zu verantworten haben. '
							  .'Einzig verbleibende Quellen bescheidener Einnahmen sind die Profite aus der Logistik zwischen dem Industriebezirk Varnograd und der Hauptstadt Nobri, sowie einige Motoren- und Fahrzeugfabriken. Alatyr besitzt keinen historischen Stadtkern. '
							  .'Neben dem Rathausplatz aus der Regierungszeit Breschnews (gebaut 1974) gibt es ein bekanntes postsowjetisches Lichttheater (Dauerklassiker: "Sturm auf Berlin") und die Fernfahrer-Kneipe "Zum Benzinschlucker".',
	'GRUSHEVSK'             => 'Grushevsk',
	'GRUSHEVSK_DESC'        => 'Grushevsk ist in mehrfacher Hinsicht eine post-sowjetische Grenzstadt. Trotz einer eher geringen Einwohnerzahl von 104.000 umfasst das Stadtgebiet zahlreiche Befestigungen, Bunkeranlagen und Sperrgebiete. ',
	'ASTRAKAN'              => 'Astrakan',
	'ASTRAKAN_DESC'         => '',
	'KUBISHEVSK'            => 'Kubishevsk',
	'KUBISHEVSK_DESC'       => 'Kubishevsk mit seinen circa 215.000 Einwohnern wurde in den Jahren 1984-1987 als spätsowjetische Musterstadt geplant und teilweise auch noch angelegt. '
							  .'Unproportional breite Straßen boten Raum für Mengen von motorisiertem Verkehr, den die Bevölkerung durch die geringe Verbreitung von Privatfahrzeugen nie wirklich ausnutzen konnte. '
							  .'Nach dem Niedergang der UDSSR hielt Kubishevsk das sowjetische Aussehen aufrecht und noch heute tragen Fassaden, Straßenschilder und Plakattafeln die Farben, Motive und Sprüche seiner Anfangszeiten. '
							  .'Die breiten Straßen werden beidseitig durch provisorisch errichtete Verkaufszelte verschmälert, in denen lokale Güter angeboten werden. '
							  .'Kubishevsk verzeichnet in den letzten Jahren einen leichten wirtschaftlichen Aufschwung, hervorgerufen durch Schwerindustrie, Ressourcenförderung und eine relativ erhaltene Infrastruktur. '
							  .'Im Umfeld der Stadt liegt ein mit großen Mühen abgeschotteter militärischer Sperrbezirk, dessen Personal an Wochenenden den geringen Sold in der Innenstadt ausgibt, welche neben traditionellen Restaurants wie "der sternlosen Internationale" auch andere, tolerierte Vergnügungen bereithält.',
	'VARNOGRAD'             => 'Varnograd',
	'VARNOGRAD_DESC'        => '',
	'JEGENSK'               => 'Jegensk',
	'JEGENSK_DESC'          => 'Jegensk besitzt innerhalb der Volksrepulik Bakirien einen Ruf als Bade- und Kurort. Mehrere therapeutische und touristische Anlagen datieren bereits auf zaristische Zeiten zurück, u.a. das wohlbekannte "Schwarzmeer-Kurbad Braschenski". Die ständige Bevölkerung beträgt ungefähr 37.000',
	'TURAV'                 => 'Turav',
	'TURAV_DESC'            => '',
	'KIRGANOV'              => 'Kirganov',
	'KIRGANOV_DESC'         => '',
	'PKD_74'                => 'PKD 74',
	'PKD_74_DESC'           => '',
	'SMTU_567_C'            => 'SMTU 567-C',
	'SMTU_567_C_DESC'       => '',

	//LocationTypes
	'TOWN'                  => 'Kleinstadt',
	'CITY'                  => 'Stadt',
	'CAPITAL'               => 'Hauptstadt',
	'INDUSTRY_AREA'         => 'Industriebezirk',
	'MILITARY_AREA'         => 'Militärischer Sperrbereich',

	//Provinces
	'ISORIA'                => 'Isoria',

	//Countries
	'BAKIRIEN'              => 'Bakirien',
	'TADSOWIEN'             => 'Tadsowien',
	'SURANIEN'              => 'Suranien',

	//BuildingTypes
	'LOCAL_ADMINISTRATION'  => 'Lokale Administration',
	'INDUSTRY'              => 'Industriekombinat',
	'BROTHEL'               => 'Bordell',

	//Buildings
	'ROTSTAHL'              => 'Rotstahl',
	'TRETMINE'              => 'Tretmine',

));
