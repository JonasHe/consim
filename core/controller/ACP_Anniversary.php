<?php

/**
*
* @package ConSim for phpBB3.1
* @copyright (c) 2015 Marco Candian (tacitus@strategie-zone.de)
* @copyright (c) 2016 Jonas Heitmann (kommodoree@googlemail.com)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace consim\core\controller;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
* Anniversary controller
*/
class ACP_Anniversary
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
	* @return News
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
	* Show all anniversaries
	*
	* @return null
	* @access public
	*/
	public function overview()
	{	
		// Catch all anniversaries from the database
		$sql = 'SELECT anniversary_id, day, month, year, event, link FROM phpbb_consim_anniversary';
		$result = $this->db->sql_query($sql);
		while($row = $this->db->sql_fetchrow($result))
		{
			$this->template->assign_block_vars('Anniversaries', array(
				'EVENT' 		=> $row['event'],
				'ODATE'			=> (($row['year']!=0) ? date("Y")-(int)$row['year'].". ": ""),
				'DAY' 			=> $row['day'],
				'MONTH'			=> $row['month'],
				'YEAR'			=> $row['year'],
				'LINK' 			=> $row['link'],
				'ID'			=> $row['anniversary_id'],
				'DELETE'		=> build_url()."&action=delete_anniversary&anniversary_id=".$row['anniversary_id'],
			));
		}
	}

	/**
	* Add an anniversary
	*
	* @return null
	* @access public
	*/
	public function anniversary_add()
	{
		// Check if all required fields are set
		$this->check_data($errors,array('event','day','month'));

		// If no errors, process the form data
		if (empty($errors))
		{
			$this->container->get('consim.core.entity.anniversary')->setDay($this->request->variable('day',0,true))
			->setMonth($this->request->variable('month',0,true))->setYear(str_pad($this->request->variable('year',0,true),2,'0', STR_PAD_LEFT))
			->setEvent($this->request->variable('event','',true))->setLink($this->request->variable('link','',true))->insert();
		}

		$this->template->assign_vars(array(
			'S_ERROR'	=> (sizeof($errors)) ? true : false,
			'ERROR_MSG'	=> (sizeof($errors)) ? implode('<br />', $errors) : '',
		));
	}

	/**
	* Delete an anniversary
	*
	* @param int $id The id of the anniversary to be deleted
	* @return null
	* @access public
	*/
	public function anniversary_delete($id)
	{
		if(confirm_box(true))
		{
			$this->container->get('consim.core.entity.anniversary')->setId($id)->delete();
		}
		else
		{
			// Request confirmation from the user to delete the topic
			confirm_box(false, $this->user->lang('ANNIVERSARY_DELETE_CONFIRM'), build_hidden_fields(array(
				'mode' 			=> 'anniversary',
				'action' 		=> 'delete_anniversary',
				'anniversary_id'=> $id,
			)));
		}
		redirect(build_url(array("action","anniversary_id")));
	}

	/**
	* Edit an Anniversary
	*
	* @param int $id The id of the anniversary to be edited
	* @return null
	* @access public
	*/
	public function anniversary_update($id)
	{
		// Check if all required fields are set
		$this->check_data($errors,array('event','day','month'));

		// If no errors, process the form data
		if (empty($errors))
		{
			$link = ($this->request->variable('link','',true)!="") ? $this->request->variable('link','',true) : "http://";

			$this->container->get('consim.core.entity.anniversary')->setDay($this->request->variable('day',0,true))
			->setMonth($this->request->variable('month',0,true))->setYear(str_pad($this->request->variable('year',0,true),2,'0', STR_PAD_LEFT))
			->setEvent($this->request->variable('event','',true))->setLink($link)->setId($id)->save();
		}

		$this->template->assign_vars(array(
			'S_ERROR'	=> (sizeof($errors)) ? true : false,
			'ERROR_MSG'	=> (sizeof($errors)) ? implode('<br />', $errors) : '',
		));
	}
}
