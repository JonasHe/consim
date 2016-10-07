<?php

/**
*
* @package ConSim for phpBB3.1
* @copyright (c) 2015 Marco Candian (tacitus@strategie-zone.de)
* @copyright (c) 2016 Jonas Heitmann (kommodoree@googlemail.com)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace consim\core\controller\acp;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
* Anniversary controller
*/
class Map
{
	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\controller\helper */
	protected $controller_helper;

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
	* @return Map
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
	* Prüfe die eingehenden Daten
	*
	* @param string[] $errors
	* @param string[] $fields All required fields
	* @return null
	* @access private
	*/
	private function check_data(&$errors, $fields)
	{
		// Add language file
		$this->user->add_lang_ext('consim/core', 'exceptions');

		foreach($fields as $value)
		{
			if(trim($this->request->variable($value, '')) == '')
			{
				$errors[] = $this->user->lang('EXCEPTION_FIELD_MISSING');
			}
		}
	}
	
	/**
	* Default page
	*
	* @return null
	* @access public
	*/
	public function overview()
	{	
        // Load the map for use on this site
		$this->container->get('consim.core.service.map')
			->showMap("addMarkers", "mainMap", 'width: 750px; height: 511px;');

		// Catch all markers from the database
		$sql = 'SELECT id, name FROM phpbb_consim_markers';
		$result = $this->db->sql_query($sql);
		while($row = $this->db->sql_fetchrow($result))
		{
			$this->template->assign_block_vars('markers', array(
				'delete'	=> build_url("action").'&action=delete_marker&marker_id='.$row['id'],
				'title' 	=> $row['name'],
			));
		}

		// Catch all roads from the database
		$sql = 'SELECT id, title, blocked, type FROM phpbb_consim_roads';
		$result = $this->db->sql_query($sql);
		while($row = $this->db->sql_fetchrow($result))
		{
			$this->template->assign_block_vars('Roads', array(
				'TITLE' 		=> $row['title'],
				'BLOCKED' 		=> $row['blocked'],
				'TYPE'			=> $row['type'],
				'ID'			=> $row['id'],
			));
		}
	}

	/**
	* Add a marker
	*
	* @return null
	* @access public
	*/
	public function marker_add()
	{
		// Create a form key for preventing CSRF attacks
		add_form_key('consim_marker_add');
		// Create an array to collect errors that will be output to the user
		$errors = array();

		//get the consimUser Entity
		/** @var  \consim\core\entity\NewsChannel */
		$entity = $this->container->get('consim.core.entity.markers');

		// Is the form being submitted to us?
		if ($this->request->is_set_post('marker_add'))
		{
			// Test if the submitted form is valid
			if (!check_form_key('consim_marker_add'))
			{
				$errors[] = $this->user->lang('FORM_INVALID');
			}
			
			// Check if all required fields are set
			$this->check_data($errors,array('marker_x','marker_y','marker_title','marker_style'));

			// If no errors, process the form data
			if (empty($errors))
			{
				$entity->setX($this->request->variable('marker_x',0))->
					setY($this->request->variable('marker_y',0))->
					setName($this->request->variable('marker_title','',true))->
					setType($this->request->variable('marker_style',0))->insert();
				redirect(build_url(array("action")));
			}
		}

		// Add the error messages to the template
		$this->template->assign_vars(array(
			'S_ERROR'	=> (sizeof($errors)) ? true : false,
			'ERROR_MSG'	=> (sizeof($errors)) ? implode('<br />', $errors) : '',
		));
	}

	/**
	* Delete a marker
	*
	* @param int $id The id of the marker to be deleted
	* @return null
	* @access public
	*/
	public function marker_delete($id)
	{
		if(confirm_box(true))
		{
			$this->container->get('consim.core.entity.markers')->setId($id)->delete();
		}
		else
		{
			// Request confirmation from the user to delete the topic
			confirm_box(false, $this->user->lang('CONFIRM_DELETE_MARKER'), build_hidden_fields(array(
				'mode' 			=> 'map',
				'action' 		=> 'delete_marker',
				'marker_id'		=> $id,
			)));
		}
		redirect(build_url(array("action","channel_id")));
	}

    /**
	* Default page
	*
    * @param int $id The ID of the road
	* @return null
	* @access public
	*/
	public function road_update($id)
	{	
		$this->container->get('consim.core.entity.roads')->setBlocked($this->request->variable('blocked_'.$id,0))
			->setType($this->request->variable('road_type_'.$id,0))->setId($id)->save();
	}
}
