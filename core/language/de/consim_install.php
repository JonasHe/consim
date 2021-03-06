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
	'FRT'					=> 'Freie Republik Tadsowien',
	'VRB'					=> 'Volksrepublik Bakirien',
	'SUR'					=> 'Suranische Republik',
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
	'STARK_UBERGEWICHTIG'	=> 'Stark übergewichtig',

	//Locations
	'NOBRI'					=> 'Nobri',
	'NOBRI_DESC'			=> 'Nobri ist die jüngste und kleinste unter den Provinzhauptstädten der Volksrepublik Bakirien mit ca. 385.000 Einwohnern. Erst im Jahr 1984 wurde die Stadt auf Initiative der regionalen Kommunistischen Partei gegründet und bildete seitdem das Zentrum der Provinz Isoria. '
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
	'ASTRAKAN_DESC'			=> 'Eingebettet in die ganzjährig schneebedeckten Gipfel des Kaukasusgebirges liegt Astrakan. Ein beinahe malerisches Panorama umgibt diese Stadt, abgerundet durch Wälder mit urtümlicher Flora. Im Gegensatz zu den vielen Industriestädten Bakirien findet man hier selbst im Stadtgebiet kristallklare Gebirgsbäche und grüne Obstwiesen. Der "Jurgori"-Naturschutzpark ist das größte Reservat für seltene Vogelarten wie das bakirische Gebirgshuhn und den Jevgeni-Wisent. Als historischer Passwächter gen Südwesten in die Provinz Aljaria diente die Stadt schon immer als Rastplatz für Handelskarawanen und Garnisonsstadt. Heute sind die Handelskarawanen den Fernfahrern gewichen, die an manchen Tag dicht gedrängt die stellenweise engen Bergstraßen blockieren. In der Winterzeit kann Astrakan über Wochen einschneien. Seine Bewohner ertragen den Wechsel aus tiefen, langen Wintern und kurzen, heißen Sommern mit blühenden Tälern mit gleichsam stoischer Ruhe. Auch politisch scheint Astrakan nur mittelbar den regelmäßigen Erschütterungen des Staatsführung ausgesetzt zu sein. Der Spruch "sobald es in Astrakan angekommen ist", ist schon staatsweit bekannt: Wenn ein neues Gesetz oder eine neue Mode auch hier Beachtung finden, dann halt tatsächlich jeder Staatsbürger in der VRB davon Kenntnis genommen.',
	'KUBISHEVSK'			=> 'Kubishevsk',
	'KUBISHEVSK_DESC'		=> 'Kubishevsk mit seinen circa 215.000 Einwohnern wurde in den Jahren 1984-1987 als spätsowjetische Musterstadt geplant und teilweise auch noch angelegt. '
							  .'Unproportional breite Straßen boten Raum für Mengen von motorisiertem Verkehr, den die Bevölkerung durch die geringe Verbreitung von Privatfahrzeugen nie wirklich ausnutzen konnte. '
							  .'Nach dem Niedergang der UDSSR hielt Kubishevsk das sowjetische Aussehen aufrecht und noch heute tragen Fassaden, Straßenschilder und Plakattafeln die Farben, Motive und Sprüche seiner Anfangszeiten. '
							  .'Die breiten Straßen werden beidseitig durch provisorisch errichtete Verkaufszelte verschmälert, in denen lokale Güter angeboten werden. '
							  .'Kubishevsk verzeichnet in den letzten Jahren einen leichten wirtschaftlichen Aufschwung, hervorgerufen durch Schwerindustrie, Ressourcenförderung und eine relativ erhaltene Infrastruktur. '
							  .'Im Umfeld der Stadt liegt ein mit großen Mühen abgeschotteter militärischer Sperrbezirk, dessen Personal an Wochenenden den geringen Sold in der Innenstadt ausgibt, welche neben traditionellen Restaurants wie "der sternlosen Internationale" auch andere, tolerierte Vergnügungen bereithält.',
	'VARNOGRAD'					=> 'Varnograd',
	'VARNOGRAD_DESC'			=> 'Qualmende Schornsteine, ratternde Maschinen und Fließbänder, unablässige Stahlwalzen, dies sind die deutlich sicht- und hörbaren Symbole der bakirischen Schwerpunkt. Varnograd mit seinen schätzungsweise 260.000 Einwohnern ist ein stetig wachsender Moloch aus Industrieanlagen, Wohnblöcken und post-sowjetischer Betriebsamkeit. Viele Bakirier aus ärmeren Regionen versuchen in dieser Großstadt eine neue Existenz aufzubauen. Entsprechend dicht ist so auch das politische Raster. Die Staatsführung ist bemüht, möglichst viele Arbeiter als Parteimitglieder zu gewinnen und scheut sich ebenso nicht, in großen Umfang Propaganda zu betreiben. An den Stadtgrenzen sind gigantische Plakate mit Motivsprüchen aufgestellt, es gibt kostenlose Filmtage in Kinos und die sporadischen Paraden der Werktätigen. Abseits von der offiziellen Kulisse ist Varnograd aber auch ein notorisches Zentrum für politische Oppositon und organisierte Kriminalität. In dunklen Fabrikhinterhöfen und hinter verhängten Wohnungsfenstern finden sich einige der meistgesuchten Spionagegruppen anderer Staaten, wie auch Zellen verschiedener Terrorgruppen. Besonders die zahlreichen Logistiktätigkeiten und Waffenfabriken erlauben zahlreiche Möglichkeiten für Personen, die gewillt sind schwere Strafen durch die Staatspolizei oder den Geheimdienst zu riskieren.',
	'JEGENSK'					=> 'Jegensk',
	'JEGENSK_DESC'				=> 'Jegensk besitzt innerhalb der Volksrepulik Bakirien einen Ruf als Bade- und Kurort. Mehrere therapeutische und touristische Anlagen datieren bereits auf zaristische Zeiten zurück, u.a. das wohlbekannte "Schwarzmeer-Kurbad Braschenski". Die ständige Bevölkerung beträgt ungefähr 37.000',
	'TURAV'						=> 'Turav',
	'TURAV_DESC'				=> 'An der nordwestlichen Provinzgrenze zu Warandi gelegen, ist die Kleinstadt Turav mit ihren knapp 30.000 Einwohnern. Landwirtschaftliche Produktion und Holzschlag sind die Haupteinnahmen, entsprechend traditionell und konservativ ist die lokale Bevölkerung geprägt. In Turav befindet sich das überregional bekannte Kloster "Heilige Maria", dessen traditionreiche Geschichte man in ausgedehnten Rundgängen durch die ausgedehnten Anlagen erkunden kann. Turav leidet unter der häufig unterentwickelten Infrastruktur innerhalb der VRB, auch wenn das lokale Parteibüro dieses niemals zugeben würde. Am Rand der Stadt befindet sich ein sowjetischer Heldenfriendhof aus der Zeit des Großen Vaterländischen Krieges.',
	'KIRGANOV'					=> 'Kirganov',
	'KIRGANOV_DESC'				=> 'Im südlichen Teil der Provinz Isoria befindet sich die Kleinstadt Kirganov mit schätungsweise 40.000 Einwohnern. Es ist im wesentlichen eine typische, bakirische Siedlung mit Einrichtungen der Staatspartei, ausgedehneter privater und vergemeinschafteter Landwirtschaft (Getreide, Kartoffeln, etwas Tabak) und ausbaufähiger Infrastruktur. Seit mehreren Jahren befindet sich hier in staatlich finanziertes Krankenkaus mit angeschlossenen Unterkünften, das Bakirier behandelt und versorgt welche aus neutralen Provinzen fliehen mussten und hier auch die Zuteilung einer neuer Heimstätte warten.',
	'PKD_74'					=> 'PKD 74',
	'PKD_74_DESC'				=> 'Im Osten der Provinz, mit relativer Nähe zur neutralen Provinz XYZ, liegt der militärische Sperrbereich PKD 47. Dieser umfasst mehrere "Großbunker", wie die Staatsführung Bunkerkomplexe mit Mehrfachnutzung deklariert. Mehrere hundert Panzerfahrzeuge werden hier, sicher vor fremden Augen und den meisten Bombentypen, unter einer dicken Schicht Stahlbeton gelagert. Munitionslager, Treibstofftanks und Werkstätten sind ebenfalls Teil dieser Anlage. Das PKD 74 dient auch als Erprobungsgelände neuer Panzermodifikationen und Typen. Das Umfeld des ca. 55 km² großen Areals ist durch einen Minensperrgürtel und Patroullien geschützt. Die einheimische Bevölkerung meidet den Bereich großräumig. Die sporadischen, verirrten Touristen in den Sommermonaten werden zum Teil ausgiebigen Verhören durch Mitglieder verschiedener Sicherheitsdienste unterzogen, bevor man diese nach Zahlung einer erstaunlicherweise kleinen Geldstrafe wieder fahren lässt. Der offizielle Vorwurf lautet bis heute: "Unbefugtes Betreten eines Wasserschutzgebietes". Abgesehen davon ist der PKD 74 auch dauerhafte Garnison und Ausbildungsstätte für zahlreiche Soldaten und Offiziere der bakirischen Volksarmee. Bei vorbildlicher Diensterfüllung ist die Erteilung eines Reisescheines zum nächsten Strand des Schwarzen Meeres eine äußerst begehrte Belohnung; im Übrigen auch im Winter zum Eisangeln. ',
	'SMTU_567_C'				=> 'SMTU 567-C',
	'SMTU_567_C_DESC'			=> 'Eingebettet in die Täler des Kaukasusgebirges im Südwesten von Isoria ist der größte, militärische Sperrbereich der gesamten VRB. Das Gelände wird bis heute mit großem Aufwand geheim gehalten, auch wenn sich natürlich die Existenz kaum verbergen lässt. Offiziell als Naturschutzpark deklariert, ist dieses ca. 80 km² große Areal sowohl ein gigantisches Munitions- und Fahrzeugdepot, Radar- und Abhörposten und Standort mehrerer Abfang- und Bomberstaffeln der bakirischen Luftwaffe. Niemand weiß genau, welche Funktion die weitläufigen Gebäudekomplexe besitzen, in denen täglich einige Hundertschaften Uniformierter ihren Dienst tun. Gerüchtehalber befinden sich unterhalb mancher Gebäude Bunkerkomplexe, die Forschungsanlagen für biologische und chemische Waffen beinhalten. Die wenigen Landwirte in der Umgebung sind, so munkelt man, keine Zivilisten sondern allesamt Mitglieder verschiedener Sicherheitsapparate, die jeden ungebetenen Gast schon weit vor dem eigentlichen Gelände filmen und gegebenenfalls von "ihren privaten Äckern" vertreiben. Die bakirische Bevölkerung jedenfalls hat gelernt, den gesamten Großbereich des SMTU zu meiden.',
	'KRASNOGORSK'				=> 'Krasnogorsk',
	'KRASNOGORSK_DESC'			=> 'Krasnogorsk ist zugleich Staatshauptstadt der Volksrepublik Bakirien und die Provinzhauptstadt Warandis. Mit einer Gesamtbevölkerung von über 560.000 Einwohnern ist sie die einwohnerreichste Stadt der gesamten VRB. Krasnogorsk vereint alle wichtigen Teile der zentralistischen Staatsbürokratie, dazu mehrere Dutzend sozialistische Insitutionen von Parteizentralen bis hin zu Akademien. Auch die Bakirische Volksarmee (BVA) hat in Krasnogorsk ihr Hauptquartier.',
	'RYZHOM'					=> 'Ryzhom',
	'RYZHOM_DESC'				=> 'Ryzhom ist eine alte, zaristische Stadt mit Wurzeln im 14. Jahrhundert. Bemerkenswert ist die alte "Erlöser"-Kathedrale und zwei orthodoxe Klöster. Im Spannungsfeld zwischen atheistischer Staatspolitik und noch immer fest verwurzeltem Glauben der einfachen Bevölkerung sind diese Orte die deutlichsten Symbole widerstrebender Lebensanschauungen. Ryzhom verfügt über schätzungsweise 172.000 Einwohner, von denen ein signifikanter Teil in Agrar- und Forstwirtschaft tätig ist.',
	'SEWERMORSK'				=> 'Sewermorsk',
	'SEWERMORSK_DESC'			=> 'Sewermorsk hat circa 140.000 Einwohner und ist eine Neugründung aus dem Jahr 1968. Charakteristisch ist das konsequente Schachbrettmuster der Stadt, das manchen Einwohner zu Vergleichen mit U.S.-amerikanischen Straßennetzen verleitet. Derartige Vergleiche werden allerdings nicht gerne gehört, besonders von der örtlichen Parteielite. Sewermorsk hat den Ruf als eine der reaktionärsten Bastionen des Sozialimus, nicht umsonst befinden sich hier zwei überregional bekannte Kaderschmieden. Während des Zerfalls der UDSSR rückten deren Kadetten aus und lynchten die örtlichen Parteisekretäre im Zorn über deren mangelnden Widerstand gegen Gorbatschows Perestroika. Seit einigen Jahren gehört es so auch zum guten Ton führender Politiker der VRB, zumindest alle paar Jahre in Sewermorsk eine Rede zu halten und die Linientreue des sozialistischen Nachwuchses zu loben.',
	'ISCHEWSK'					=> 'Ischewsk',
	'ISCHEWSK_DESC'				=> 'Ischewsk besitzt ungefährt 80.000 Einwohner und ist im wesentlichen eine Stadt im Niedergang. Seit Aufgabe mehrere Silber- und Erzminen hat sich die Bevölkerung innerhalb von einer Dekade um mehr als 75% reduziert. Große Leerstände erwecken in manchen Straßenzügen den Eindruck, als handele es sich um eine Geisterstadt. Teilweise zieht es Spekulanten und Geschäftsmänner aus Krasnogorsk nach Ischewsk, um Immobilienprojekte zu wagen. Nach einigen fehlgeschlagenen Initiativen zur Wiederbelebung der Stadt sind heute nur noch die landesweit unerreichte Konsummenge an Vodka und die Dichte an verschiedenen Krebserkrankungen einsame, traurige Charakteristika.',
	'KIROVOGRAD'				=> 'Kirovograd',
	'KIROVOGRAD_DESC'			=> 'Kirovograd verfügt über ungefährt 110.000 Einwohner und liegt eingebettet zwischen mehreren hier zusammentreffenden Flüssen. Bekanntsheitswert hat die Stadt durch ihre Nähe zu einem Gefechtsraum des Zweiten Weltkrieges, auf welchem die Rote Armee eine Panzereinheit der Wehrmacht aufgrund deren Treibstoffmengel einkesseln und vernichten konnte. Außerdem ist die Stadt Geburtstplatz der Ischygarov-Familie, die bereits mehrere Minister in der VRB stellte. Eine bescheidene Industrie umfasst Herstellungsbetriebe von Metallwaren, Nahrungsmitteln und Telekommunikationsmitteln. (Das sozialistische Smartphone-Modell MIR-3 wird in Kirovograd gebaut und ist landesweit bekannt für seine spontan detonierenden Litium-Akkus. Scherzhaft bezeichnen Nutzer ihre Geräte auch als falsch deklarierte Handgranaten der Bakirischen Volksarmee.)',
	'PROLETARSK'				=> 'Proletarsk',
	'PROLETARSK_DESC'			=> 'Als Vorzeigeprojekt der UDSSR wurde der Industriebezirk Proletarsk 1974 gegründet. Eine bombastische Infrastruktur mit breiten Straßen, großflächigem Straßenbahnnetz und zahlreichen, öffentlichen Einrichtungen kann allerdings nicht über den vorherrschenden Substanzmangel hinwegtäuschen. Provisorisch geöffnete Bäder, halbleere Bibliotheken und verwilderte Sportplätze sind die Überreste einer proletarischen Vision. Immerhin ist Proletarsk bis heute der produktivste und auch größte Industriebezirk der gesamten VRB mit über 350.000 Einwohnern. Beinahe alle Industriezweige sind in Proletarsk vertreten, vom Stahlschmelzwerk bis zur Aeronautik-Manufaktur.',
	'SOLIKAMSK'					=> 'Solikamsk',
	'SOLIKAMSK_DESC'			=> 'Solikamsk ist eine gigantische Waffenschmiede. Über 80% aller in der VRB produzierten Handfeuerwaffen, über 70% aller Handfeuerwaffenmunition und über 60% aller schweren Waffen werden in Solikamsk gefertigt. Tag und Nacht rauchen die unzählbaren Schornsteine, während die Arbeiter der häufig traditionsreichen Fabriken in 2 oder 3 Schichten die Sicherheit des bakirischen Sozialismus durch feuerkräfte Verteidigung garantieren. Solikamsk verfügt über ungefährt 230.000 Einwohner und ist eine der wenigen Städte (offiziell: Industriebezirke), die mit einer wachsenen Bevölkerungszahl angeben können.',
	'MICHAILOWSK'				=> 'Michailowsk',
	'MICHAILOWSK_DESC'			=> 'Das beschaulich gelegene Michailowsk ist eine deutlich überteuerte Datschen-Siedlung, deren Bevölkerung zwischen wenigen Hundert und bis zu 3.000 Einwohnern schwankt. Der Grund hierfür liegt in den zahlreichen Datschen, die VRB-Funktionäre und wohlhabende Bakiren hier erwerben, um jedes Jahr einigen Wochen im Sommer, oder je nach persönlicher Vorliebe, auch im Winter hier zu verbingen. Es existiert das Sprichwort: "Wer in der VRB etwas zu sagen hat, besitzt eine Hausadresse in Michailowsk." Die Kleinstadt Michailowsk besitzt somit "erstaunlicherweise" auch trotz ihrer eigentlichen Bedeutungslosigkeit eine hervorragende Infrastruktur und eine der niedrigsten Kriminalitätsraten der VRB. Zwischen Leibwachen mit Geheimdiensthintergrund und Armeeangehörigen, welche die Anwesen der VRB-Granden bewachen, scheint die Verlockung von erfolgreichen Einbruchsserien plötzlich nur noch als Marginalie.',
	'KOSLOWKA'					=> 'Koslowka',
	'KOSLOWKA_DESC'				=> 'Koslowka ist eine Kleinstadt par excellence. Mit ihren nicht mehr 2.500 Einwohnern und der verstreuten Siedlungsdichte qualifiziert sie sich eigentlich mehr als bevölkerungsreiche Bauernschaft. Eine von der bakirischen Bürokratie getroffene Feststellung hinegegen stuft Koslowka als Kleinstadt ein. Die damit normalerweise einhergehenden Förderungen für Bildungseinrichtungen und Infrastruktur sind jedenfalls entgegen den Erwartungen der Bewohner bis heute nicht spürbar geworden.',
	'GKBLF_TAIP' 				=> 'GKBLF / TAIP',
	'GKBLF_TAIP_DESC'			=> 'Das GKBLF (Generalkommando Bakirische Luftflotte) und das TAIP (Technisches Avionik-Insitut Pokryschkin) sind Herz und Hirn der stolzen, bakirischen Luftwaffe. Die Rote Bakirische Luftflotte (RBL) verfügt aufgrund enger politischer Verbindungen zur Russischen Förderation über Mikoyan MiG-31 Abfangjäger, Sukhoi Su-27 Kampfflugzeuge, sowie über verschiedene Hubschrauber und Bombermodelle. Sowohl die offizielle Anzahl wie auch genauere, technische Details sind hier streng gehütete Militärgeheimnisse. Ignorante "Experten" des kapitalistisch-revanchistischen Auslandes gehen davon aus, dass nur ein Bruchteil der Luftflotte tatsächlich einsatzbereit ist und gegenüber modernen Luftstreitkräften nur geringe Kapazitäten entfalten könnte. Zum Einsatz bei Paraden und gegen irreguläre Kräfte ist die RBL allerdings bestens geeignet, so verfügen die Piloten fast vollständig über eine sehr solide Ausbildung und auch Kampferfahrungen.',
	'BBSOK'						=> 'BBSOK',
	'BBSOK_DESC'				=> 'Das BBSOK (Bakirisches Bodenstreitkraft Oberkommando) ist die traditionsreichste und personalstärkste Führungsinstitution innerhalb der Bakirischen Volksarmee (VBA). Sie ist verantwortlich für Ausbildung, Bewaffnung, Doktrin und Gefechtsführung aller bakirischen Bodenstreitkräfte, sowie zugleich auch für Ausbildung, Auswahl und gemeindienstliche Kontrolle von Mannschaften und ganz besonders Offizieren.',
	'SPA_BRW'					=> 'SPA BRW',
	'SPA_BRW_DESC'				=> 'Die SPA (Schukow Panzer-Akademie) und BRW (Bakirische Rüstungswerke) sind Kernelemente eines traditionsreichen und prestigeträchtgen Teiles der bakirischen Bodenstreitkräfte: Den gepanzerten Kräften. Dazu gehören insbesondere die Panzertruppe, aber auch mechanisierte Truppenteile und Schützenpanzer-Abteilungen. Die räumliche Nähe und organisatorische Vernetzung von Produktion und Ausbildung hat einen für bakirische Verhältnisse relativ effizienten und vor allem unbestritten erfolgreichen Komplex erschaffen, der verlässliches Material und versiertes Personal gleichermaßen hervorbringt. Zahlreiche Offiziere, darunter viele bekannte Generäle, wie auch Politfunktionäre haben die Ausbildnug an der Schukow Panzer-Akademie durchlaufen, sodass beispielsweise in der Staatshauptstadt Krasnogorsk ein regelmäßiges Zusammentreffen von Absolventen stattfindet.',
	'CHAROWSK'					=> 'Charwosk',
	'CHAROWSK_DESC'				=> 'Charwosk verfügt über circa 95.000 Einwohner. Urkundlich bekannt seit dem 12. Jahrhundert als freie Bauernschaft mit Wurzeln mit überwiegend Kosackenbevölkerung, erfolgte ab 1922 ein stetiger Bevölkerungsanstieg durch den Bau der neuen Kaukasus-Eisenbahnstrecke. Hunderte Arbeiter aus anderen Kosackengemeiden und deren Familien wurden in Charowsk angesiedelt und noch heute können viele der Anwohner ihre Familienwurzeln auf diese historische Verbindung stützen. Als Infrastruktur-Knotenpunkt mit Nähe zur Russischen Föderation ist Charowsk auch eine sehr russisch-geprägte Stadt und Sitz vieler Kooperationen zwischen RF und VRB.',
	'MOROSOWOGRAD'				=> 'Morosowograd',
	'MOROSOWOGRAD_DESC'			=> 'Benannt nach dem früheren Staatspräsidenten Morosow, dem 7. Staatsoberhaupt der VRB, stellt Morosowograd die wohl erfolgreichste Modellstadt der Provinz Dmnasi dar. Saubere, breite Straßen mit sorgsam gepflegten Grünanlagen, ein effizienter öffentlicher Nahverkehr und großzügig geplante Wohneinheiten zu erschwinglichen Preisen sind Merkmale dieser stetig wachsenden Stadt.',
	'USSINSK'					=> 'Ussinsk',
	'USSINSK_DESC'				=> 'Ussinsk mit seinen ca. 60.000 Einwohnern lebt hauptsächlich von Agrarwirtschaft. Nährstoffhaltige Böden erlauben den Anbau von selteneren Erzeugnissen wie Trauben und Himbeeren in großen Plantagen. Ein Großteil der Erzeugnisse ist für den Export in die Russische Förderation bestimmt.',
	'ROSHDESTVENSKOJE'			=> 'Roshdestvenskoje',
	'ROSHDESTVENSKOJE_DESC'		=> 'Der Industriebezirk Roshdeshtvenskoje ist ein gigantisches Konglomerat von Raffinerien, Fabriken und Lagerhäusern. Als größter Industriebezirk der gesamten VRB mit über 80.000 Arbeitskräften und deren Familien werden hier Güter aller Art produziert.',
	'TUPRUNKA'					=> 'Tuprunka',
	'TUPRUNKA_DESC'				=> 'Vergiftete Wasserläufe, übersäuerte Böden und toxische Quecksilbernebel, das ist Tuprunka. Nirgendwo sonst in der VRB wurde die Umwelt so nachhaltig zerstört wie hier. Selbst mit doppeltem Gehalt und Ehrungen durch die sozialistische Partei hat man Probleme, ausreichend qualifizierte Arbeiter (und deren Familien!) anzuziehen. Zugleich ist Tuprunka der größte Erzeuger von chemischen Grundstoffen, die andernorts in der Industrie Verwendung finden.',
	'LIWNIJOL'					=> 'Liwnijol',
	'LIWIJOL_DESC'				=> 'Liwnijol ist eine unauffällige Kleinstadt ohne besondere Merkmale.',
	'JAROWOJE'					=> 'Jarowoje',
	'JAROWOJE_DESC'				=> 'Jarowoje ist eine unauffällige Kleinstadt ohne besondere Merkmale.',
	'KEDROWY'					=> 'Kedrowy',
	'KEDROWY_DESC'				=> 'Kedrowy ist eine unauffällige Kleinstadt ohne besondere Merkmale.',
	'MALMIRSHK'					=> 'Malmirshk',
	'MALMIRSHK_DESC'			=> 'Malmirshk ist eine unauffällige Kleinstadt ohne besondere Merkmale.',
	'GKA_IGGSG'					=> 'GKA / IGGSG',
	'GKA_IGGSG_DESC'			=> 'Die bakrische Gebirgskampf-Akademie ist eine historische Einrichtung, die schon auf die Zarenzeit zurückgeht. Hier wurden Generationen von patriotischen Kämpfern ausgebildet für den Einsatz im Kaukasus. Viele der Absolventen wurden erfolgreiche Offiziere, die in verschiedenen Kriegen an allen Fronten Dienst taten. Seit 1968 ist die GKA angeschlossen an die IGGSG (Infanterie Garde Garnison - Strategische Gruppe), eine Ausbildungs- und Reserveeinheit aus deren Reihen Kräfte für "besondere Anforderungen" rekrutiert werden, so zumindest steht es im allgemeinen Bericht des Verteidigungsministeriums. Hinter endlosen Hürden von Geheimhaltung und bürokratischen Winkelzügen versteckt, bildet die IGGSG eine Institution die sowohl hervorragende Offiziere, Geheimdienstler und Wissenschaftler vereint und verschiedene, zumeist geheime Projekte zur Landesverteidigung organisiert.',
	'NSG_71'					=> 'NSG 71',
	'NSG_71_DESC'				=> 'Der militärische Sperrbereich Naturschutzgebiet 71 existiert nicht.',
	'RK_BATS'					=> 'RK BATS',
	'RK_BATS_DESC'				=> 'Die Rokossovsky Kaserne ist benannt nach einem Helden der UDSSR und dem Großen Vaterländischen Krieg. Sie dient als Ausbildungs-, Waffentest-, und Manövereinrichtung für die bakrischen Artillerietruppen. Verwendete Waffensysteme sind stationiäre Artilleriegeschütze, mechanisierte Systeme und Panzerhaubitzen.',
	
	//LocationTypes
	'TOWN'						=> 'Kleinstadt',
	'CITY'						=> 'Stadt',
	'PROVINCIAL_CAPITAL'		=> 'Provinzhauptstadt',
	'CAPITAL'					=> 'Hauptstadt',
	'INDUSTRY_AREA'				=> 'Industriebezirk',
	'MILITARY_AREA'				=> 'Militärischer Sperrbereich',

	//Provinces
	'ISORIA'					=> 'Isoria',
	'WARANDI'					=> 'Warandi',
	'DMNASI'					=> 'Dmnasi',
	'WASTELAND'                 => 'Ausland',

	// Skills Category
	'SKILL_CAT_1'				=> 'Sprachen',
	'SKILL_CAT_2'				=> 'Politisch / Administrativ',
	'SKILL_CAT_3'				=> 'Militärisch',
	'SKILL_CAT_4'				=> 'Wirtschaftlich / Technisch',
	'SKILL_CAT_5'				=> 'Verdeckt / Überleben',

	// Skills
	'SKILL_1'					=> 'Bakirisch',
	'SKILL_2'					=> 'Suranisch',
	'SKILL_3'					=> 'Tadsowisch',
	'SKILL_4'					=> 'Administration',
	'SKILL_5'					=> 'Finanzen',
	'SKILL_6'					=> 'Führung',
	'SKILL_7'					=> 'Politik',
	'SKILL_8'					=> 'Rhetorik',
	'SKILL_9'					=> 'Befehlskunst',
	'SKILL_10'					=> 'Militärkunde',
	'SKILL_11'					=> 'Nahkampf',
	'SKILL_12'					=> 'Schusswaffen',
	'SKILL_13'					=> 'Sprengmittel',
	'SKILL_14'					=> 'Chemie',
	'SKILL_15'					=> 'Forschung',
	'SKILL_16'					=> 'Medizin',
	'SKILL_17'					=> 'Technik',
	'SKILL_18'					=> 'Wirtschaft',
	'SKILL_19'					=> 'Einbrechen',
	'SKILL_20'					=> 'Schmuggel',
	'SKILL_21'					=> 'Spionage',
	'SKILL_22'					=> 'Sprengmittel',
	'SKILL_23'					=> 'Überlebenskunde',

	// Inventory Items
	/*
	'ITEM_1'					=> 'Bakirischer Rubel',
	'ITEM_1_SHORT'				=> 'BR',
	'ITEM_2'					=> 'Suranischer Dinar',
	'ITEM_2_SHORT'				=> 'SD',
	'ITEM_3'					=> 'Tadsowischer Dollar',
	'ITEM_3_SHORT'				=> 'TD',
	*/
	
	// Countries
	'COUNTRY_1'					=> 'Volksrepublik Bakirien',
	'COUNTRY_2'					=> 'Suranische Repubik',
	'COUNTRY_3'					=> 'Freie Repubik Tadsowien',
	'COUNTRY_4'					=> 'Unabhängig',

	// locations
	'LOCATION_1'				=> 'Kubishevsk',

	// ConSim buildings
	'BUILDING_TYPE_1'			=> 'Lokale Administration',
	'BUILDING_TYPE_2'			=> 'Industrie',
	'BUILDING_TYPE_3'			=> 'Unterwelt',

	// location_buildings
	'BUILDING_1'				=> '',
	'BUILDING_1_EXPLAIN'		=> 'Die Verwaltung der Provinzhauptstadt Nobri ist zugleich eine wichtige Parteiinstanz als auch Anlaufstelle für Bürger. Hier werden alltägliche Verwaltungsaufgaben bewältigt, Infrastrukturprojekte geplant und im Sinne der sozialistischen Ideologie Bürger"initiativen" unterstützt. Außerdem ist die Administration verantwortlich für die Zusammenarbeit mit Polizei und Zivilschutz, um die Sicherheit der Bevölkerung zu gewährleisten. Die Verwaltung in Nobri gilt dabei als weniger korrupt als vergleichbare Institutionen in anderen bakirischen Städten. Möglicherweise liegt dies im höheren Anteil junger Kader begründet, die hier mit Idealismus und Vaterlandsliebe einen Einsteig in die Verwaltungslaufbahn beginnen.',
	'BUILDING_2'				=> '',
	'BUILDING_2_EXPLAIN'		=> '',
	'BUILDING_3'				=> '',
	'BUILDING_3_EXPLAIN'		=> '',
	//'BUILDING_2'				=> 'Rotstahl',
	//'BUILDING_2_EXPLAIN'		=> 'Das örtliche Industriekombinat "Rotstahl" gilt als eines der fortschrittlicheren innerhalb der VRB. Zum Einen ist die lokale Administration mit liberaleren (sofern man diesen Ausdruck hier nutzen kann) Elementen durchmischt. Andererseits besteht eine intensive Partnerschaft mit der Technik-Akademie "Tscherenkow". Schließlich gibt es eine deutliche Anzahl von "Rückkehrern" aus dem Westen. Diese Schüler oder Studenten aus wohlhabenderen Familien haben einige Jahre im Ausland verbracht (häufig London, Paris oder Berlin) und dort mit staatlicher Unterstützung Bildungsabschlüsse erworben. Ihre Rückkehr ist zumeist mehr oder minder freiwillig, schließlich ist ihre Familie weiterhin fest "eingebunden" im sozialistischen Kollektiv. Das Industriekombinat produziert hochwertige Industriegüter, sowohl für den Zivil- wie auch Rüstungssektor. Elektronische Komponenten, chemische Produkte und zahlreiche Apparaturen bzw. Einzelteile. Arbeitsangebote sind hier reichlich zu finden, sofern angemessene Qualifikationen vorhanden sind.',
	//'BUILDING_3'				=> 'Tretmine',
	//'BUILDING_3_EXPLAIN'		=> 'Prositution erreicht im sozialistischen Idealstaat nur mit Schwierigkeiten den Status der Legalität. Laut ideologischer Lehre ist Prostitution eines der zahlreichen, subversiven Kampfmittel des korrupten Westens und für Sowjetmenschen der neuen Generation kein attraktives Angebot. Die menschliche Natur ist aber noch immer fest verankert im sozialistischen Typus. So floriert auch in der VRB Prostitution, teilweise unter dem Schutz von lokalen Offiziellen, die sich ihre Unterstützung großzügig kompensieren lassen. Die "Tretmine" ist ein weithin bekanntes Etablissement, das Mitarbeiterinnen verschiedener eurasischer Kulturen anbietet. Unter der Hand werden hier zu sündhaft teuren Preisen auch westliche Getränke angeboten, wie schottischer Whiskey. Die lokale Administration drückt hierbei ein Auge zu.',
	//'BUILDING_4'				=> '',
	//'BUILDING_4_EXPLAIN'		=> 'Die Verwaltung der Provinzhauptstadt Nobri ist zugleich eine wichtige Parteiinstanz als auch Anlaufstelle für Bürger.',
	//'BUILDING_5'				=> '',
	//'BUILDING_5_EXPLAIN'		=> 'Die weniger gut begüterten Gegenden der Stadt Alatyr mögen tagsüber einen friedlichen Anschein machen. Aber in den dunklen Nächten regiert hier das organisierte Verbrechen. Trotz offizieller Stellungnahmen der Behörden dass es in der VRB keine "kapitalischen Banden" gibt, blühen Schmuggel, Waffen- und Drogenhandel sowie Erpressungen.',
	//'BUILDING_6'				=> 'Zum Benzinschlucker',
	//'BUILDING_6_EXPLAIN'		=> 'Böse Zungen behaupten, dass der Name der bekanntesten Fernfahrerkneipe bei weitem keine Übertreibung darstellt. In Zeiten wirtschaftlicher Schwierigkeiten greifen verzweifelte Einwohner notgedrungen auf mehr oder minder gefährliche Ersatzmittel zurück, um ihre Alkolholsucht zu befriedigen. Herstellung und Konsum dieser Ersatzmittel sind nicht nur verboten, sondern werden durch die Polizei aktiv verfolgt als "volksfeindliche Schädigung kollektiver Gesundheit". Die einfachen Straßenpolizisten mit minimalen Gehältern allerdings sehen regelmäßig über kleinere Verstöße hinweg und kassieren als Gegenleistung ein kleines Handgeld.',

	// works
	'WORK_1'					=> 'Assistent des Archviars',
	'WORK_2'					=> 'Archivar',
	'WORK_3'					=> 'Archivleitung',
	'WORK_4'					=> 'Verwaltungshilfe',
	'WORK_5'					=> 'Verwaltungskraft',
	'WORK_6'					=> 'Verwaltungsprüfer',
	'WORK_7'					=> 'Kohleschaufler',
	'WORK_8'					=> 'Hilfsarbeiter',
	'WORK_9'					=> 'Vorarbeiter',
	'WORK_10'					=> 'Produkt-Tester',
	'WORK_11'					=> 'Produktentwurf-Berater',
	'WORK_12'					=> 'Produkt-Entwickler',
	'WORK_13'					=> 'Türsteher',
	'WORK_14'					=> 'Reifenstecher',
	'WORK_15'					=> 'Auftragsschläger',
	'WORK_16'					=> 'Straßenschmuggler',
	'WORK_17'					=> 'Schmugglerführer',
	'WORK_18'					=> 'Grenzschmuggler',

	// Assets
	'CURRENCIES'				=> 'Währungen',
	'CURRENCY_1'				=> 'Bakirischer Rubel',
	'CURRENCY_1_SHORT'			=> 'BR',
	'CURRENCY_2'				=> 'Suranischer Dinar',
	'CURRENCY_2_SHORT'			=> 'SD',
	'CURRENCY_3'				=> 'Tadsowischer Dollar',
	'CURRENCY_3_SHORT'			=> 'TD',
	'BONDS'						=> 'Anleihen',
	'BOND_1'					=> 'Bakirische Volksanleihe',
	'BOND_1_SHORT'				=> 'BaVo',
	'BOND_2'					=> 'Suranische Kapitalgabe',
	'BOND_2_SHORT'				=> 'SuKa',
	'BOND_3'					=> 'Tadsowische Freiheitsaktie',
	'BOND_3_SHORT'				=> 'TaFr',
	'SHARES'					=> 'Aktien',
));
