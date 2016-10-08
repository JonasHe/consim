<?php
/**
*
* @package ConSim for phpBB3.1
* @copyright (c) 2015 Marco Candian (tacitus@strategie-zone.de)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace consim\core\controller;

use consim\core\entity\ConsimFigure;
use consim\core\entity\ConsimUser;
use consim\core\entity\Skill;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
* Main controller
*/
class RegisterController
{
	/** @var \phpbb\config\config */
	protected $config;

	/** @var ContainerInterface */
	protected $container;

	/** @var \phpbb\controller\helper */
	protected $helper;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	const MIN_POINTS = 5;
	const FREE_POINTS = 50;
	//vordefinierte Punkte => anzahlskill * MIN_POINTS
	const DEFAULT_POINTS = 115;
	//extra language skill
	const EXTRA_LANG = 25;


	protected $sum_skill = self::DEFAULT_POINTS;
	/* @var Skill[] $skills */
	protected $skills = array();
	/* @var int[] $skills_values */
	protected  $skills_values = array();

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
	* @return \consim\core\controller\RegisterController
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
	* Display register
	*
	* @return null
	* @access public
	*/
	public function displayAction()
	{
		//Ist User schon registriert?
		if($this->user->data['consim_register'])
		{
			//Wenn ja, schicke ihn zum consim index
			redirect($this->helper->route('consim_core_index'));
			return null;
		}

		// User is a guest
		if ($this->user->data['user_id'] == ANONYMOUS)
		{
			// Set output vars for display in the template
			$this->template->assign_vars(array(
				'S_ANONYMOUS'				=> true,
			));
		}

		// Create a form key for preventing CSRF attacks
		add_form_key('consim_register');

		// Add language file
		$this->user->add_lang_ext('consim/core', 'consim_common');
		// Add language file
		$this->user->add_lang_ext('consim/core', 'consim_register');

		// Create an array to collect errors that will be output to the user
		/** @var string[] $errors */
		$errors = array();

		//get the consimUser Entity
		$consim_user = $this->container->get('consim.core.entity.consim_user');
		$figure = $consim_user->getFigureData();
		$this->skills = $this->container->get('consim.core.service.user_skill')->getSkills();

		// Is the form being submitted to us?
		if ($this->request->is_set_post('submit'))
		{
			// Test if the submitted form is valid
			if (!check_form_key('consim_register'))
			{
				$errors[] = $this->user->lang('FORM_INVALID');
			}

			//Check die eingehenden request data
			$this->check_data($errors, $consim_user);

			// If no errors, process the form data
			if (empty($errors))
			{
				//Speichere die Daten und füge User zum Consim hinzu
				try
				{
					$this->addUserToConsim($consim_user);
				}
				catch (\consim\core\exception\base $e)
				{
					$errors[] = $e->get_message($this->user);
				}

				if(empty($errors))
				{
					//Leite den User weiter zum Consim Index
					redirect($this->helper->route('consim_core_index'));
				}
			}
		}

		$this->createSelection($figure);

		//Skills to template
		foreach ($this->container->get('consim.core.service.user_skill')->sortSkillsByCategory($this->skills) as $cat => $skills)
		{
			$this->template->assign_block_vars(
				'skill_groups',
				array(
					'NAME'			=> $cat,
				)
			);

			/** @var Skill[] $skills */
			foreach ($skills as $skill) {
				$this->template->assign_block_vars(
					'skill_groups.skills',
					array(
						'ID'		=> 'skill_'.$skill->getId(),
						'NAME'		=> $skill->getName(),
						'IS_LANG'	=> ($skill->getCountryId() > 0) ? true : false,
						'COUNTRY_ID'=> $skill->getCountryId(),
						'VALUE'		=> $this->request->variable('skill_'.$skill->getId(), 5),
					)
				);
			}
		}

		// Set output vars for display in the template
		$this->template->assign_vars(array(
			'S_ERROR'		=> (sizeof($errors)) ? true : false,
			'ERROR_MSG'		=> (sizeof($errors)) ? implode('<br />', $errors) : '',

			'U_ACTION'		=> $this->helper->route('consim_core_register'),
			'FREIE_PUNKTE' 	=> self::FREE_POINTS + self::DEFAULT_POINTS - $this->sum_skill,
			'MIN_POINTS'	=> self::MIN_POINTS,

			'VORNAME'						=> $this->request->variable('vorname', ''),
			'NACHNAME'						=> $this->request->variable('nachname', ''),
			'GESCHLECHT'					=> $this->request->variable('geschlecht', ''),
			'GEBURTSLAND'					=> $this->request->variable('geburtsland', ''),
			'RELIGION'						=> $this->request->variable('religion', ''),
			'HAARFARBE'						=> $this->request->variable('haarfarbe', ''),
			'AUGENFARBE'					=> $this->request->variable('augenfarbe', ''),
			'BESONDERE_MERKMALE'			=> $this->request->variable('besondere_merkmale', ''),
		));

		// Send all data to the template file
		return $this->helper->render('consim_register.html', $this->user->lang('REGISTER'));
	}

	/**
	* Prüfe die eingehenden Daten
	*
	* @param string[] $errors
	* @param ConsimUser $consim_user
	* @access private
	*/
	private function check_data(&$errors, &$consim_user)
	{
		$person = array(
			'Vorname'						=> $this->request->variable('vorname', ''),
			'Nachname'						=> $this->request->variable('nachname', ''),
			'Geschlecht'					=> $this->request->variable('geschlecht', ''),
			'Geburtsland'					=> $this->request->variable('geburtsland', ''),
			'Religion'						=> $this->request->variable('religion', ''),
			'Haarfarbe'						=> $this->request->variable('haarfarbe', ''),
			'Augenfarbe'					=> $this->request->variable('augenfarbe', ''),
			'BesondereMerkmale'				=> $this->request->variable('besondere_merkmale', ''),
		);

		// set skills_values from request
		foreach ($this->skills as $skill)
		{
			$this->skills_values[$skill->getId()] = $this->request->variable('skill_' . $skill->getId(), self::MIN_POINTS);
			if($this->skills_values[$skill->getId()] < self::MIN_POINTS)
			{
				$errors[] = $this->user->lang('TOO_LOW_SKILL', $skill->getName());
			}
		}

		$this->sum_skill = array_sum($this->skills_values);

		//Die Summe der Attribute darf einen bestimmten Wert nicht überschreiten
		if($this->sum_skill > self::FREE_POINTS + self::DEFAULT_POINTS)
		{
			$errors[] = $this->user->lang('TOO_HIGH_ATTRIBUTE');
		}

		//Die Summe der Attribute darf einen bestimmten Wert nicht überschreiten
		if($this->sum_skill < self::FREE_POINTS + self::DEFAULT_POINTS)
		{
			$errors[] = $this->user->lang('TOO_LOW_ATTRIBUTE');
		}

		// Set the data in the entity
		foreach ($person as $entity_function => $wert)
		{
			try
			{
				// Calling the set_$entity_function on the entity and passing it $consim_user
				call_user_func_array(array($consim_user, 'set' . $entity_function), array($wert));
			}
			catch (\consim\core\exception\base $e)
			{
				// Catch exceptions and add them to errors array
				$errors[] = $e->get_message($this->user);
			}
		}
	}

	/**
	* Speichere die ConSim daten
	* und speichere den User als Consim register
	*
	* @param ConsimUser $consim_user
	* @access private
	* @throws \consim\core\exception\out_of_bounds
	*/
	private function addUserToConsim(ConsimUser $consim_user)
	{
		$user_id = $this->user->data['user_id'];
		$consim_user->insert($user_id);
		$this->container->get('consim.core.service.inventory')->setStartInventory($user_id);

		foreach ($this->skills as $skill)
		{
			if($skill->getCountryId() > 0 && (
					($consim_user->getGeburtsland()->getValue() == 'bak' && $skill->getId() == 1) ||
					($consim_user->getGeburtsland()->getValue() == 'sur' && $skill->getId() == 2) ||
					($consim_user->getGeburtsland()->getValue() == 'frt' && $skill->getId() == 3)
				)
			)
			{
				$this->skills_values[$skill->getId()] += self::EXTRA_LANG;
			}

			$this->container->get('consim.core.entity.consim_user_skill')
				->insert($user_id, $skill->getId(), $this->skills_values[$skill->getId()]);
		}

		$sql = 'UPDATE ' . USERS_TABLE . '
			SET consim_register = 1
			WHERE user_id = ' . $user_id;
		$this->db->sql_query($sql);
	}

	/**
	* Erzeugt die Auswahl
	*
	* @param ConsimFigure[] $figure
	* @access private
	*/
	private function createSelection($figure)
	{
		foreach($figure as $element)
		{
			$selected = 0;
			if ($this->request->variable($element->getGroups(), '') == $element->getValue())
			{
				$selected = 1;
			}

			$select = array(
				'NAME'		=> $element->getName(),
				'WERT'		=> $element->getValue(),
				'SELECTED'	=> $selected,
			);

			$this->template->assign_block_vars($element->getGroups(), $select);
		}
	}

}

