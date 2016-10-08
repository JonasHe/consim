<?php
/**
 * @package ConSim for phpBB3.1
 *
 * @copyright (c) 2015 Marco Candian (tacitus@strategie-zone.de)
 * @copyright (c) 2016 Jonas Heitmann (kommodoree@googlemail.com)
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace consim\core\service;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Operator for Inventory
 */
class MapService
{
	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\controller\helper */
	protected $helper;

	/** @var ContainerInterface */
	protected $container;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/**
	 * Constructor
	 *
	 * @param \phpbb\config\config				$config			Config object
	 * @param \phpbb\controller\helper			$helper			Controller helper object
	 * @param ContainerInterface					$container		Service container interface
	 * @param \phpbb\user						$user			User object
	 * @param \phpbb\template\template			$template		Template object
	 * @param \phpbb\request\request				$request		Request object
	 * @param \phpbb\db\driver\driver_interface	$db				Database object
	 * @return \consim\core\service\MapService
	 * @access public
	 */
	public function __construct(\phpbb\config\config $config,
		ContainerInterface $container,
		\phpbb\controller\helper $helper,
		\phpbb\user $user,
		\phpbb\template\template $template,
		\phpbb\request\request $request,
		\phpbb\db\driver\driver_interface $db)
	{
		$this->config = $config;
		$this->container = $container;
		$this->helper = $helper;
		$this->user = $user;
		$this->template = $template;
		$this->request = $request;
		$this->db = $db;

		return $this;
	}

	/**
	 * Display map in template
	 *
	 * @param string $divName		The name of the div where the map should be shown
	 * @param string $map			The map which should be loaded
	 * @param string $css			Css to make it look nicer
	 * @param array  $args			Parts that shouldn't be loaded
	 * @param int    $focus			The ID of the province to be focused
	 * @param int    $highlight		The ID of the province to be highlighted
	 * @return void
	 */
	public function showMap($divName, $map, $css, $args = array(), $focus = 0, $highlight = 0)
	{
		$map = $this->load_map($divName, $map, $args, $focus, $highlight);

		$this->template->assign_vars(array(
			'MAP' => '<div id="'. $divName .'" style="'. $css .'background-size: cover;"></div>'. $map,
		));
	}

	/**
	 * Function to load map the map
	 *
	 * @param string $divName The name of the div where the map should be shown
	 * @param string $map The map which should be loaded
	 * @param array $args Parts that shouldn't be loaded
	 * @param int $focus The ID of the province to be focused
	 * @param int $highlight The ID of the province to be highlighted
	 * @return string
	 * @access protected
	 */
	// args = no_roads, no_markers, no_buildings (Includes Roads), no_additional_buildings, no_zoom, no_legend
	protected function load_map($divName, $map, $args = array(), $focus = 0, $highlight = 0)
	{
		$markers = $roads = $cities = $regions = array();

		if(!in_array('no_roads',$args) && !in_array('no_buildings',$args) && !in_array('no_additional_buildings',$args))
		{
			$roads = $this->load_roads($regions);
			$cities = $this->load_cities($map);
		}

		if(in_array('no_roads',$args) && !in_array('no_buildings',$args) && !in_array('no_additional_buildings',$args))
		{
			$cities = $this->load_cities($map);
		}

		if(in_array('no_roads',$args) && !in_array('no_buildings',$args) && in_array('no_additional_buildings',$args))
		{
			$cities = $this->load_cities($map, $focus);
		}

		if(!in_array('no_markers',$args))
		{
			$markers = $this->load_markers($map);
		}

		if(in_array('no_additional_buildings',$args) && !in_array('no_buildings', $args))
		{
			$roads = $this->load_roads($regions, $focus);
			$cities = $this->load_cities($map, $focus);
		}

		//why?
		//$buildings = (!in_array('no_buildings',$args)) ? true : false;
		$zoom = (!in_array('no_zoom', $args)) ? true : false;
		$legend = (!in_array('no_legend', $args)) ? true : false;

		$this->user->add_lang_ext('consim/core', 'consim_install');

		//Alle erforderlichen Daten wurden geladen und werden für die Ausgabe gespeichert
		$data = '<div style="display: none;" class="map_config">'.json_encode(array("zoom" => $zoom, "legend" => $legend, "divName" => $divName,
																						  "focus" => $focus, "map_name" => $map, "board_url" => generate_board_url())).'</div>
            <div style="display: none;" id="map_data">'.json_encode(array("roads" => $roads, "cities" => array_merge($cities,$markers),
																		  "provinces" => $this->load_provinces($regions, $highlight), "regions" => $regions, "language" => array(
					"ROAD_TYPE_1" => $this->user->lang('ROAD_TYPE_1'), "ROAD_TYPE_2" => $this->user->lang('ROAD_TYPE_2'),
					"ROAD_TYPE_3" => $this->user->lang('ROAD_TYPE_3'), "ROAD_TYPE_4" => $this->user->lang('ROAD_TYPE_4'),
					"ROAD_TYPE_5" => $this->user->lang('ROAD_TYPE_5'), "ROAD_BLOCKED" => $this->user->lang('ROAD_BLOCKED'),
					"ROAD_NOT_BLOCKED" => $this->user->lang('ROAD_NOT_BLOCKED'), "MAP_LEGEND_TITLE" => $this->user->lang('MAP_LEGEND_TITLE'),
					"BUILDING_TYPE_1" => $this->user->lang('TOWN'), "BUILDING_TYPE_2" => $this->user->lang('CITY'),
					"BUILDING_TYPE_3" => $this->user->lang('PROVINCIAL_CAPITAL'), "BUILDING_TYPE_4" => $this->user->lang('CAPITAL'),
					"BUILDING_TYPE_5" => $this->user->lang('INDUSTRY_AREA'), "BUILDING_TYPE_6" => $this->user->lang('MILITARY_AREA'),
					"COUNTRY_1" => $this->user->lang('COUNTRY_1'),"COUNTRY_2" => $this->user->lang('COUNTRY_2'),"COUNTRY_3" => $this->user->lang('COUNTRY_3'),
					"COUNTRY_4" => $this->user->lang('COUNTRY_4'),))).'</div>
            <link rel="stylesheet" type="text/css" href="'.generate_board_url().'/ext/consim/core/styles/SZ-Style/theme/jvectormap.css">
            <script type="text/javascript" src="'.generate_board_url().'/assets/javascript/jquery.min.js"></script>
            <script type="text/javascript" src="'.generate_board_url().'/ext/consim/core/styles/SZ-Style/template/js/jvectormap.js"></script>
            <script type="text/javascript" src="'.generate_board_url().'/ext/consim/core/styles/SZ-Style/template/js/'.$map.'.js"></script>
            <script type="text/javascript" src="'.generate_board_url().'/ext/consim/core/styles/SZ-Style/template/js/mapConfig.js"></script>';

		return $data;
	}

	/**
	 * Load all roads and their status
	 *
	 * @param array $regions
	 * @param int $id Not null if only the roads of one province should be loaded
	 * @return array $roads    Contains all requested roads and their status
	 * @access protected
	 */
	protected function load_roads(&$regions, $id = 0)
	{
		$roads = array();

		// If the ID is not null only load the roads of a specific province
		if($id != 0)
		{
			$sql = $this->db->sql_build_query("SELECT",array(
				'SELECT' 			=> 'r.id, r.blocked, r.type, r.title',
				'FROM' 				=> array('phpbb_consim_roads' => 'r',),
				'WHERE'             => 'r.prvnce_id = '.$id));

		}
		else
		{
			$sql = $this->db->sql_build_query("SELECT",array(
				'SELECT' 			=> 'r.id, r.blocked, r.type, r.title',
				'FROM' 				=> array('phpbb_consim_roads' => 'r',)));
		}

		$result = $this->db->sql_query($sql);
		while($row = $this->db->sql_fetchrow($result))
		{
			$roads[$row['id']] = array('road_type' => 'ROAD_TYPE_'.$row['type'], 'blocked' => $row['blocked'], 'title' => $row['title']);
			$regions[] = array('id' => $row['id'], 'country' => 'ROAD_TYPE_'.$row['type']);
		}

		return $roads;
	}

	/**
	 * Load all cities
	 *
	 * @param string $map
	 * @param int $id Not null if only the cities of one province should be loaded
	 * @return array $cities Contains all requested cities and their data
	 * @access protected
	 */
	protected function load_cities($map, $id = 0)
	{
		$cities = array();
		//$this->user->add_lang_ext('consim/core', 'consim_install');

		// If the ID is not null only load the roads of a specific province
		if($id != 0)
		{
			$sql = $this->db->sql_build_query("SELECT",array(
				'SELECT' 			=> 'l.name, l.x, l.y, l.type_id',
				'FROM' 				=> array('phpbb_consim_locations' => 'l',),
				'WHERE'             => 'l.province_id = '.$id.' AND l.map_name = "'. $map .'"'));

		}
		else
		{
			$sql = $this->db->sql_build_query("SELECT",array(
				'SELECT' 			=> 'l.name, l.x, l.y, l.type_id',
				'FROM' 				=> array('phpbb_consim_locations' => 'l',),
				'WHERE'             => 'l.map_name = "'. $map . '"'));
		}

		$result = $this->db->sql_query($sql);

		while($row = $this->db->sql_fetchrow($result))
		{
			$cities[] = array('name' => $row['name'], 'coords' => array($row['x'], $row['y']), 'type' => $row['type_id']);
		}

		return $cities;
	}

	/**
	 * Load all provinces and their data
	 *
	 * @param array $regions
	 * @param int $highlight
	 * @return array $provinces Contains all provinces and their data
	 * @access protected
	 */
	protected function load_provinces(&$regions, $highlight)
	{
		$provinces = array();

		$sql = $this->db->sql_build_query("SELECT",array(
			'SELECT' 			=> 'p.id, p.name, p.country_id',
			'FROM' 				=> array('phpbb_consim_provinces' => 'p',)));

		$result = $this->db->sql_query($sql);

		while($row = $this->db->sql_fetchrow($result))
		{
			$provinces[$row['id']] = array('name' => $row['name'], 'country' => $row['country_id']);
			$regions[] = array('id' => $row['id'], 'country' => (($row['id']==$highlight) ?  'HIGHLIGHT' : $row['country_id']));
		}

		return $provinces;
	}

	/**
	 * Load all custom markers
	 *
	 * @param string $map
	 * @param int $id Not null if only the roads of one province should be loaded
	 * @return array $markers Contains all requested markers
	 * @access protected
	 */
	protected function load_markers($map, $id = 0)
	{
		$markers = array();

		// If the ID is not nill only load the roads of a specific province
		if($id != 0)
		{
			$sql = $this->db->sql_build_query("SELECT",array(
				'SELECT' 			=> 'm.id, m.name, m.x, m.y, m.type',
				'FROM' 				=> array('phpbb_consim_markers' => 'm',),
				'WHERE'             => 'm.prvnce_id = '. $id.' AND m.map_name = "'. $map .'"'));
		}
		else
		{
			$sql = $this->db->sql_build_query("SELECT",array(
				'SELECT' 			=> 'm.id, m.name, m.x, m.y, m.type',
				'FROM' 				=> array('phpbb_consim_markers' => 'm',),
				'WHERE'             => 'm.map_name = "'. $map .'"'));
		}

		$result = $this->db->sql_query($sql);
		while($row = $this->db->sql_fetchrow($result))
		{
			$markers[] = array('name' => $row['name'], 'coords' => array($row['x'], $row['y']), 'type' => 'custom_'.$row['type']);
		}

		return $markers;
	}
}
