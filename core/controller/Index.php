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
class Index
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
	* @param \phpbb\config\config               $config          	Config object
	* @param \phpbb\controller\helper			$controller_helper	Controller helper object
	* @param ContainerInterface                	$container       	Service container interface
	* @param \phpbb\user                        $user            	User object
	* @param \phpbb\template\template           $template        	Template object
	* @param \phpbb\request\request         	$request        	Request object
	* @param \phpbb\db\driver\driver_interface	$db             	Database object
	* @return \consim\core\controller\index
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
	* Display index
	*
	* @return null
	* @access public
	*/
	public function display()
	{
		if($this->user->data['consim_register'] == 0)
		{
			redirect($this->helper->route('consim_core_register'));
			return;
		}

		// Add language file
		$this->user->add_lang_ext('consim/core', 'consim_common');

		$consim_user = $this->container->get('consim.core.entity.ConsimUser')->load($this->user->data['user_id']);

        $location = $this->container->get('consim.core.entity.Location')->load($consim_user->getLocation());

        $this->container->get('consim.core.operators.TravelLocations')->setAllDestinationsToTemplate($consim_user->getLocation(),$this->template, $this->helper);

		// Is the form being submitted to us?
        // Delete UserProfile
		if ($this->request->is_set_post('delete'))
		{
			$sql = 'UPDATE ' . USERS_TABLE . '
				SET consim_register = 0
				WHERE user_id = ' . $this->user->data['user_id'];
			$this->db->sql_query($sql);

			$sql = 'DELETE FROM phpbb_consim_user
				WHERE user_id = ' . $this->user->data['user_id'];
			$this->db->sql_query($sql);

			//Leite den User weiter zum Consim Register
			redirect($this->helper->route('consim_core_register'));
		}

		// Set output vars for display in the template
		$this->template->assign_vars(array(
            'LOCATION'                      => $location->getName(),
            'LOCATION_TYPE'                 => $location->getType(),
            'PROVINCE'                      => $location->getProvince(),
            'COUNTRY'                       => $location->getCountry(),

	    	'SPRACHE_TADSOWISCH'			=> $consim_user->getSpracheTadsowisch(),
	    	'SPRACHE_BAKIRISCH'				=> $consim_user->getSpracheBakirisch(),
	    	'SPRACHE_SURANISCH'				=> $consim_user->getSpracheSuranisch(),
	    	'RHETORIK'						=> $consim_user->getRhetorik(),
	    	'WIRTSCHAFT'					=> $consim_user->getWirtschaft(),
            'ADMINISTRATION'				=> $consim_user->getAdministration(),
	      	'TECHNIK'						=> $consim_user->getTechnik(),
	      	'NAHKAMPF'						=> $consim_user->getNahkampf(),
			'SCHUSSWAFFEN'					=> $consim_user->getSchusswaffen(),
            'SPRENGMITTEL'					=> $consim_user->getSprengmittel(),
	      	'MILITARKUNDE'					=> $consim_user->getMilitarkunde(),
	      	'SPIONAGE'						=> $consim_user->getSpionage(),
            'SCHMUGGEL'					    => $consim_user->getSchmuggel(),
			'MEDIZIN'						=> $consim_user->getMedizin(),
	      	'UBERLEBENSKUNDE'				=> $consim_user->getUberlebenskunde(),
		));

		// Send all data to the template file
		return $this->helper->render('consim_index.html', $this->user->lang('INDEX'));
	}

	public function travel($travel_id)
	{
        if (!$this->is_valid($travel_id) || !check_link_hash($this->request->variable('hash', ''), 'travel_' . $travel_id))
		{
			throw new \phpbb\exception\http_exception(403, 'NO_AUTH_OPERATION');
		}

        $consim_user = $this->container->get('consim.core.entity.ConsimUser')->load($this->user->data['user_id']);
		$consim_user->setLocation($travel_id);
        $consim_user->save();

		//Reload the Consim Index
		redirect($this->helper->route('consim_core_index'));
    }

    protected function is_valid($value)
	{
		return !empty($value) && preg_match('/^\w+$/', $value);
	}
}
