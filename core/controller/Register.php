<?php
/**
*
* @package ConSim for phpBB3.1
* @copyright (c) 2015 Marco Candian (tacitus@strategie-zone.de)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace consim\core\controller;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
* Main controller
*/
class Register
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

	const FREE_POINTS = 50;
	const DEFAULT_POINTS = 15;

	protected $sum_skill = self::DEFAULT_POINTS;

	/**
	* Constructor
	*
	* @param \phpbb\config\config               $config     	Config object
	* @param \phpbb\controller\helper    		$helper			Controller helper object
	* @param ContainerInterface                	$container      Service container interface
	* @param \phpbb\user                        $user           User object
	* @param \phpbb\template\template           $template       Template object
	* @param \phpbb\request\request         	$request        Request object
	* @param \phpbb\db\driver\driver_interface	$db             Database object
	* @return \consim\core\controller\Register
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
	* Display register
	*
	* @return null
	* @access public
	*/
	public function display()
	{
		//Ist User schon registriert?
		if($this->user->data['consim_register'])
		{
			//Wenn ja, schicke ihn zum consim index
			redirect($this->helper->route('consim_core_index'));
			return;
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
		$errors = array();

		//get the consimUser Entity
		$consim_user = $this->container->get('consim.core.entity.ConsimUser');
		$figure = $consim_user->getFigureData();

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
				$this->addUserToConsim($consim_user);

				//Leite den User weiter zum Consim Index
				redirect($this->helper->route('consim_core_index'));
			}
		}

		$this->createSelection($figure);

		// Set output vars for display in the template
		$this->template->assign_vars(array(
			'S_ERROR'	=> (sizeof($errors)) ? true : false,
			'ERROR_MSG'	=> (sizeof($errors)) ? implode('<br />', $errors) : '',

			'U_ACTION'	=> $this->helper->route('consim_core_register'),
			'FREIE_PUNKTE' => self::FREE_POINTS + self::DEFAULT_POINTS - $this->sum_skill,

			'VORNAME'						=> $this->request->variable('vorname', ''),
			'NACHNAME'						=> $this->request->variable('nachname', ''),
			'GESCHLECHT'					=> $this->request->variable('geschlecht', ''),
			'GEBURTSLAND'					=> $this->request->variable('geburtsland', ''),
			'RELIGION'						=> $this->request->variable('religion', ''),
			'HAARFARBE'						=> $this->request->variable('haarfarbe', ''),
			'AUGENFARBE'					=> $this->request->variable('augenfarbe', ''),
			'BESONDERE_MERKMALE'			=> $this->request->variable('besondere_merkmale', ''),
			'SPRACHE_TADSOWISCH'			=> $this->request->variable('sprache_tadsowisch', 1),
			'SPRACHE_BAKIRISCH'				=> $this->request->variable('sprache_bakirisch', 1),
			'SPRACHE_SURANISCH'				=> $this->request->variable('sprache_suranisch', 1),
			'RHETORIK'						=> $this->request->variable('rhetorik', 1),
			'ADMINISTRATION'				=> $this->request->variable('administration', 1),
			'WIRTSCHAFT'					=> $this->request->variable('wirtschaft', 1),
			'TECHNIK'						=> $this->request->variable('technik', 1),
			'NAHKAMPF'						=> $this->request->variable('nahkampf', 1),
			'SCHUSSWAFFEN'					=> $this->request->variable('schusswaffen', 1),
			'SPRENGMITTEL'					=> $this->request->variable('sprengmittel', 1),
			'MILITARKUNDE'					=> $this->request->variable('militarkunde', 1),
			'SPIONAGE'						=> $this->request->variable('spionage', 1),
			'SCHMUGGEL'						=> $this->request->variable('schmuggel', 1),
			'MEDIZIN'						=> $this->request->variable('medizin', 1),
			'UBERLEBENSKUNDE'				=> $this->request->variable('uberlebenskunde', 1),
		));

		// Send all data to the template file
		return $this->helper->render('consim_register.html', $this->user->lang('REGISTER'));
	}

	/**
	* Prüfe die eingehenden Daten
	*
	* @param $errors Array
	* @param $consim_user consim.core.entity.ConsimUser -Object
	* @return null
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
		$attribute = array(
			'SpracheTadsowisch'				=> $this->request->variable('sprache_tadsowisch', 1),
			'SpracheBakirisch'				=> $this->request->variable('sprache_bakirisch', 1),
			'SpracheSuranisch'				=> $this->request->variable('sprache_suranisch', 1),
			'Rhetorik'						=> $this->request->variable('rhetorik', 1),
			'Administration'				=> $this->request->variable('administration', 1),
			'Wirtschaft'					=> $this->request->variable('wirtschaft', 1),
			'Technik'						=> $this->request->variable('technik', 1),
			'Nahkampf'						=> $this->request->variable('nahkampf', 1),
			'Schusswaffen'					=> $this->request->variable('schusswaffen', 1),
			'Sprengmittel'					=> $this->request->variable('sprengmittel', 1),
			'Militarkunde'					=> $this->request->variable('militarkunde', 1),
			'Spionage'						=> $this->request->variable('spionage', 1),
			'Schmuggel'						=> $this->request->variable('schmuggel', 1),
			'Medizin'						=> $this->request->variable('medizin', 1),
			'Uberlebenskunde'				=> $this->request->variable('uberlebenskunde', 1),
		);

		$this->sum_skill = array_sum($attribute);

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

		$data = array_merge($person, $attribute);

		// Set the data in the entity
		foreach ($data as $entity_function => $wert)
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
	* @param $consim_user Object
	* @return null
	* @access private
	*/
	private function addUserToConsim($consim_user)
	{
		$consim_user->insert($this->user->data['user_id']);

		$sql = 'UPDATE ' . USERS_TABLE . '
			SET consim_register = 1
			WHERE user_id = ' . $this->user->data['user_id'];
		$this->db->sql_query($sql);
	}

	/**
	* Erzeugt die Auswahl
	*
	* @param $figure Array
	* @return null
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
