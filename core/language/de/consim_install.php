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
	'MANNLICH'				=> 'Männlich',
	'WEIBLICH'				=> 'Weiblich',
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
	'NOBRI'					=> 'Nobri',
	'NOBRI_DESC'			=> 'Nobri ist die jüngste und kleinste unter den Provinzhauptstädten der Volksrepublik Bakirien mit ca. 385.000 Einwohnern, erst im Jahr 1984 wurde die Stadt auf die Initiative der regionalen Kommunistischen Partei gegründet und bildete seitdem das Zentrum der Provinz Isoria. '
							  .'Die am 21. Januar dem Todestag Lenins gegründete Stadt sollte ein Modellprojekt der Kommunistischen Partei werden und einen neuen Typus moderne Verwaltungsstadt begründen, Nobri liegt weder strategisch exponiert, noch liegt die Provinzhauptstadt in der Nähe eines der großen Ressourcenvorkommen der Provinz. '
							  .'Ihre Lage zeichnet sich nur durch die Nähe zum größten Hafen der Volksrepublik und ihrer einzigartig zusammengesetzt Bevölkerung aus. Nobri weist die höchste Dichte von Parteimitgliedern in der gesamten Volksrepublik Bakirien aus, weiterhin übersteigt auch das Bruttoeinkommen pro Kopf, dass der beiden anderen wichtigen Städte bei weitem. '
							  .'Die Clique der aus Nobri stammenden Politiker ist im ganzen Land für ihren Einfluss in den wichtigsten Gremien der Partei und des Staates bekannt. <br />'
							  .'Die Stadt wurde um den im Jahr 2009 aufwendig renovierten Leonid Iljitsch Breschnew Turm herum errichtet, die Innenstadt ist geprägt von funktionsmäßigen Verwaltungsbauten.'
							  .'Die Architektur der Stadt ist ganz dem Modellcharakter seiner Gründung unterworfen, klare Abgrenzungen der Bezirke und deutliche Richtlinien für das Bauen neuer Gebäude und deren Verwendungszweck sind in Nobri Alltag.'
							  .'Während sich die Innenstadt hauptsächlich in Staats- oder Parteihand befindet, sind die Außenbezirke um die Innenstadt geprägt von den Bauten der aus Nobri stammenden Politiker und der dortigen Verwaltungseliten.'
							  .'Die Hügel nördlich der Innenstadt gehören zu der gefragtesten Wohngegend in der ganzen Volksrepublik, hinter vorgehaltener Hand wird diese Gegend von neidischen Beobachtern auch das "Sozialistische Beverly Hills" genannt.'
							  .'Auch fehlen in Nobri sämtliche Anzeichne der in der Provinz Isoria vorhandenen suranischen oder tadsowischen Minderheit.',
	'ALATYR'				=> 'Alatyr',
	'ALATYR_DESC'			=> 'Alatyr ist eine sichtbar abgewirtschaftete Planstadt inmitten endloser Äcker und abgeholzter Wälder. '
							  .'Die verbliebenen 85.000 Einwohner sind zu großem Teil eingewanderte Kosaken auf der Suche nach Arbeit. '
							  .'Eine hohe Kriminalitätsrate und grassierende Arbeitslosigkeit machen Alatyr zu einem sozialen Brennpunkt der Provinz, wenn auch die lokale Parteiführung offiziell nichts von diesen Problemen wissen will. '
							  .'Nach offizieller Stellungnahme sind es die politische Agitation subversiver Elemente wie ausländische NGOs und Schmuggel von europäischen Waren zum Schaden der einheimischen Wirtschaft, welche die Lage zu verantworten haben. '
							  .'Einzig verbleibende Quellen bescheidener Einnahmen sind die Profite aus der Logistik zwischen dem Industriebezirk Varnograd und der Hauptstadt Nobri, sowie einige Motoren- und Fahrzeugfabriken. Alatyr besitzt keinen historischen Stadtkern. '
							  .'Neben dem Rathausplatz aus der Regierungszeit Breschnews (gebaut 1974) gibt es ein bekanntes postsowjetisches Lichttheater (Dauerklassiker: "Sturm auf Berlin") und die Fernfahrer-Kneipe "Zum Benzinschlucker".',
	'GRUSHEVSK'				=> 'Grushevsk',
	'GRUSHEVSK_DESC'		=> 'Grushevsk ist in mehrfacher Hinsicht eine post-sowjetische Grenzstadt. Trotz einer eher geringen Einwohnerzahl von 104.000 umfasst das Stadtgebiet zahlreiche Befestigungen, Bunkeranlagen und Sperrgebiete. ',
	'ASTRAKAN'				=> 'Astrakan',
	'ASTRAKAN_DESC'			=> '',
	'KUBISHEVSK'			=> 'Kubishevsk',
	'KUBISHEVSK_DESC'		=> 'Kubishevsk mit seinen circa 215.000 Einwohnern wurde in den Jahren 1984-1987 als spätsowjetische Musterstadt geplant und teilweise auch noch angelegt. '
							  .'Unproportional breite Straßen boten Raum für Mengen von motorisiertem Verkehr, den die Bevölkerung durch die geringe Verbreitung von Privatfahrzeugen nie wirklich ausnutzen konnte. '
							  .'Nach dem Niedergang der UDSSR hielt Kubishevsk das sowjetische Aussehen aufrecht und noch heute tragen Fassaden, Straßenschilder und Plakattafeln die Farben, Motive und Sprüche seiner Anfangszeiten. '
							  .'Die breiten Straßen werden beidseitig durch provisorisch errichtete Verkaufszelte verschmälert, in denen lokale Güter angeboten werden. '
							  .'Kubishevsk verzeichnet in den letzten Jahren einen leichten wirtschaftlichen Aufschwung, hervorgerufen durch Schwerindustrie, Ressourcenförderung und eine relativ erhaltene Infrastruktur. '
							  .'Im Umfeld der Stadt liegt ein mit großen Mühen abgeschotteter militärischer Sperrbezirk, dessen Personal an Wochenenden den geringen Sold in der Innenstadt ausgibt, welche neben traditionellen Restaurants wie "der sternlosen Internationale" auch andere, tolerierte Vergnügungen bereithält.',
	'VARNOGRAD'					=> 'Varnograd',
	'VARNOGRAD_DESC'			=> '',
	'JEGENSK'					=> 'Jegensk',
	'JEGENSK_DESC'				=> 'Jegensk besitzt innerhalb der Volksrepulik Bakirien einen Ruf als Bade- und Kurort. Mehrere therapeutische und touristische Anlagen datieren bereits auf zaristische Zeiten zurück, u.a. das wohlbekannte "Schwarzmeer-Kurbad Braschenski". Die ständige Bevölkerung beträgt ungefähr 37.000',
	'TURAV'						=> 'Turav',
	'TURAV_DESC'				=> '',
	'KIRGANOV'					=> 'Kirganov',
	'KIRGANOV_DESC'				=> '',
	'PKD_74'					=> 'PKD 74',
	'PKD_74_DESC'				=> '',
	'SMTU_567_C'				=> 'SMTU 567-C',
	'SMTU_567_C_DESC'			=> '',

	//LocationTypes
	'TOWN'						=> 'Kleinstadt',
	'CITY'						=> 'Stadt',
	'CAPITAL'					=> 'Hauptstadt',
	'INDUSTRY_AREA'				=> 'Industriebezirk',
	'MILITARY_AREA'				=> 'Militärischer Sperrbereich',

	//Provinces
	'ISORIA'					=> 'Isoria',

	// Skills
	'SKILL_1'					=> 'Sprache: Bakirisch',
	'SKILL_2'					=> 'Sprache: Suranisch',
	'SKILL_3'					=> 'Sprache: Tadsowisch',
	'SKILL_4'					=> 'Rhetorik',
	'SKILL_5'					=> 'Administration',
	'SKILL_6'					=> 'Wirtschaft',
	'SKILL_7'					=> 'Technik',
	'SKILL_8'					=> 'Nahkampf',
	'SKILL_9'					=> 'Schusswaffen',
	'SKILL_10'					=> 'Sprengmittel',
	'SKILL_11'					=> 'Militärkunde',
	'SKILL_12'					=> 'Spionage',
	'SKILL_13'					=> 'Schmuggel',
	'SKILL_14'					=> 'Medizin',
	'SKILL_15'					=> 'Überlebenskunde',

	// Inventory Items
	'ITEM_1'					=> 'Bakirischer Rubel',
	'ITEM_1_SHORT'				=> 'BR',
	'ITEM_2'					=> 'Suranischer Dinar',
	'ITEM_2_SHORT'				=> 'SD',
	'ITEM_3'					=> 'Tadsowischer Dollar',
	'ITEM_3_SHORT'				=> 'TD',
	
	// Countries
	'COUNTRY_1'					=> 'Bakirien',
	'COUNTRY_2'					=> 'Suranien',
	'COUNTRY_3'					=> 'Tadsowien',

	// locations
	'LOCATION_1'				=> 'Kubishevsk',

	// ConSim buildings
	'BUILDING_TYPE_1'			=> 'Lokale Administration',
	'BUILDING_TYPE_2'			=> 'Industriekombinat',
	'BUILDING_TYPE_3'			=> 'Bordell',
	'BUILDING_TYPE_4'			=> 'Armenviertel',
	'BUIDLING_TYPE_5'			=> 'Fernfahrerkneipe',

	// location_buildings
	'BUILDING_1'				=> '',
	'BUILDING_1_EXPLAIN'		=> 'Die Verwaltung der Provinzhauptstadt Nobri ist zugleich eine wichtige Parteiinstanz als auch Anlaufstelle für Bürger. Hier werden alltägliche Verwaltungsaufgaben bewältigt, Infrastrukturprojekte geplant und im Sinne der sozialistischen Ideologie Bürger"initiativen" unterstützt. Außerdem ist die Administration verantwortlich für die Zusammenarbeit mit Polizei und Zivilschutz, um die Sicherheit der Bevölkerung zu gewährleisten. Die Verwaltung in Nobri gilt dabei als weniger korrupt als vergleichbare Institutionen in anderen bakirischen Städten. Möglicherweise liegt dies im höheren Anteil junger Kader begründet, die hier mit Idealismus und Vaterlandsliebe einen Einsteig in die Verwaltungslaufbahn beginnen.',
	'BUILDING_2'				=> 'Rotstahl',
	'BUILDING_2_EXPLAIN'		=> 'Das örtliche Industriekombinat "Rotstahl" gilt als eines der fortschrittlicheren innerhalb der VRB. Zum Einen ist die lokale Administration mit liberaleren (sofern man diesen Ausdruck hier nutzen kann) Elementen durchmischt. Andererseits besteht eine intensive Partnerschaft mit der Technik-Akademie "Tscherenkow". Schließlich gibt es eine deutliche Anzahl von "Rückkehrern" aus dem Westen. Diese Schüler oder Studenten aus wohlhabenderen Familien haben einige Jahre im Ausland verbracht (häufig London, Paris oder Berlin) und dort mit staatlicher Unterstützung Bildungsabschlüsse erworben. Ihre Rückkehr ist zumeist mehr oder minder freiwillig, schließlich ist ihre Familie weiterhin fest "eingebunden" im sozialistischen Kollektiv. Das Industriekombinat produziert hochwertige Industriegüter, sowohl für den Zivil- wie auch Rüstungssektor. Elektronische Komponenten, chemische Produkte und zahlreiche Apparaturen bzw. Einzelteile. Arbeitsangebote sind hier reichlich zu finden, sofern angemessene Qualifikationen vorhanden sind.',
	'BUILDING_3'				=> 'Tretmine',
	'BUILDING_3_EXPLAIN'		=> 'Prositution erreicht im sozialistischen Idealstaat nur mit Schwierigkeiten den Status der Legalität. Laut ideologischer Lehre ist Prostitution eines der zahlreichen, subversiven Kampfmittel des korrupten Westens und für Sowjetmenschen der neuen Generation kein attraktives Angebot. Die menschliche Natur ist aber noch immer fest verankert im sozialistischen Typus. So floriert auch in der VRB Prostitution, teilweise unter dem Schutz von lokalen Offiziellen, die sich ihre Unterstützung großzügig kompensieren lassen. Die "Tretmine" ist ein weithin bekanntes Etablissement, das Mitarbeiterinnen verschiedener eurasischer Kulturen anbietet. Unter der Hand werden hier zu sündhaft teuren Preisen auch westliche Getränke angeboten, wie schottischer Whiskey. Die lokale Administration drückt hierbei ein Auge zu.',
	'BUILDING_4'				=> '',
	'BUILDING_4_EXPLAIN'		=> 'Die Verwaltung der Provinzhauptstadt Nobri ist zugleich eine wichtige Parteiinstanz als auch Anlaufstelle für Bürger.',
	'BUILDING_5'				=> '',
	'BUILDING_5_EXPLAIN'		=> 'Die weniger gut begüterten Gegenden der Stadt Alatyr mögen tagsüber einen friedlichen Anschein machen. Aber in den dunklen Nächten regiert hier das organisierte Verbrechen. Trotz offizieller Stellungnahmen der Behörden dass es in der VRB keine "kapitalischen Banden" gibt, blühen Schmuggel, Waffen- und Drogenhandel sowie Erpressungen.',
	'BUILDING_6'				=> 'Zum Benzinschlucker',
	'BUILDING_6_EXPLAIN'		=> 'Böse Zungen behaupten, dass der Name der bekanntesten Fernfahrerkneipe bei weitem keine Übertreibung darstellt. In Zeiten wirtschaftlicher Schwierigkeiten greifen verzweifelte Einwohner notgedrungen auf mehr oder minder gefährliche Ersatzmittel zurück, um ihre Alkolholsucht zu befriedigen. Herstellung und Konsum dieser Ersatzmittel sind nicht nur verboten, sondern werden durch die Polizei aktiv verfolgt als "volksfeindliche Schädigung kollektiver Gesundheit". Die einfachen Straßenpolizisten mit minimalen Gehältern allerdings sehen regelmäßig über kleinere Verstöße hinweg und kassieren als Gegenleistung ein kleines Handgeld.',

	// works
	'WORK_1'					=> 'Assistent des Archviars',
	'WORK_2'					=> 'Externe Steuerprüfung',
	'WORK_3'					=> 'Sicherheitspersonal',
	'WORK_4'					=> 'Produkt-Kontrolleur',
	'WORK_5'					=> 'Produktdesign Studien-Evaluierung',
	'WORK_6'					=> 'Gerüchte lauschen',
	'WORK_7'					=> 'Türsteher',
	'WORK_8'					=> 'Gassengespenst',
	'WORK_9'					=> 'Straßenschreck',
	'WORK_10'					=> 'Des Teufels Destillateur',
));
