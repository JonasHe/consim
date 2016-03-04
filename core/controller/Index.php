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
    * Class-Variables
    **/
    protected $consim_user;

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

        //Starting with the init
        $this->init();
	}

	/**
	* Display index
	*
	* @return null
	* @access public
	*/
	public function display()
	{
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

        //Is User active?
        if($this->consim_user->getActive())
        {
            //get current action
            $action = $this->container->get('consim.core.operators.ActionLists')->getCurrentActionFromUser($this->user->data['user_id']);
            //Is User traveling?
            if($action instanceof \consim\core\entity\TravelLocation)
            {
                return $this->showTraveling($action);
            }
        }
        else
        {
            return $this->showLocation();
        }
	}

    /**
	* Display all traveling routes
	*
    * @param \consim\core\entity\TravelLocation $travel
	* @return null
	* @access private
	*/
    private function showTraveling($travel)
    {
        $now = time();
        $time = $travel->getEndTime() - $now;

        // Set output vars for display in the template
		$this->template->assign_vars(array(
            'START_LOCATION_NAME'       => $travel->getStartLocation()->getName(),
            'START_LOCATION_TYPE'       => $travel->getStartLocation()->getType(),
            'START_LOCATION_PROVINCE'   => $travel->getStartLocation()->getProvince(),
            'START_LOCATION_COUNTRY'    => $travel->getStartLocation()->getCountry(),
            'START_TIME'                => date("d.m.Y - H:i:s", $travel->getStartTime()),
            'END_LOCATION_NAME'         => $travel->getEndLocation()->getName(),
            'END_LOCATION_TYPE'         => $travel->getEndLocation()->getType(),
            'END_LOCATION_PROVINCE'     => $travel->getEndLocation()->getProvince(),
            'END_LOCATION_COUNTRY'      => $travel->getEndLocation()->getCountry(),
            'END_TIME'                  => date("d.m.Y - H:i:s", $travel->getEndTime()),
            'COUNTDOWN'                 => date("i:s", $time),
		));

		// Send all data to the template file
		return $this->helper->render('consim_travel.html', $this->user->lang('INDEX'));
    }

    /**
	* Display a location
	*
    * @param int $location_id
	* @return null
	* @access public
	*/
    public function showLocation($location_id = 0)
    {
        //must be an integer
        $location_id = (int) $location_id;

        $location = $this->container->get('consim.core.entity.Location');
        $location_op = $this->container->get('consim.core.operators.Locations');

        //location from location_id or from position of user?
        if($location_id == 0)
        {
            $location_id = $this->consim_user->getLocationId();

            //Create the Travelpopup
            $location_op->setAllRouteDestinationsToTemplate($location_id, $this->template, $this->helper);
        }
        $location->load($location_id);
        $buildings = $location_op->getAllBuildings($location_id);

        //Put all Buildings in the Template
        foreach ($buildings as $entity)
        {
            $building = array(
				'NAME'	     	=> ($entity->getName() != '')? '"' . $entity->getName() . '"' : '',
                'TYPE'  		=> $entity->getType(),
                'URL'           => $this->helper->route('consim_core_location_building',
                                                    array(
                                                        'location_id' => $location_id,
                                                        'building_id' => $entity->getId()
                                                    )),
			);

            $this->template->assign_block_vars('buildings', $building);
        }

        // Set output vars for display in the template
		$this->template->assign_vars(array(
            'CAN_TRAVEL'                    => ($location_id === $this->consim_user->getLocationId())? TRUE : FALSE,
            'LOCATION'                      => $location->getName(),
            'LOCATION_DESC'                  => $location->getDescription(),
            'LOCATION_IMAGE'                => $location->getImage(),
            'LOCATION_TYPE'                 => $location->getType(),
            'PROVINCE'                      => $location->getProvince(),
            'COUNTRY'                       => $location->getCountry(),
		));

		// Send all data to the template file
		return $this->helper->render('consim_index.html', $this->user->lang('INDEX'));
    }

    /**
	* Display a building in a location
	*
    * @param int $location_id
    * @param int $building_id
	* @return null
	* @access public
	*/
    public function showLocationBuilding($location_id, $building_id)
    {
        //must be an integer
        $location_id = (int) $location_id;
        $building_id = (int) $building_id;

        if($location_id === 0 || $building_id === 0)
        {
            redirect($this->helper->route('consim_core_location', array('location_id' => $location_id)));
        }

        $location = $this->container->get('consim.core.entity.Location')->load($location_id);
        $building = $this->container->get('consim.core.entity.LocationBuilding')->load($building_id);

        // Set output vars for display in the template
		$this->template->assign_vars(array(
            'BUILDING_NAME'         => ($building->getName() != '')? '"' . $building->getName() . '"' : '',
            'BUILDING_TYP'          => $building->getType(),
            'LOCATION'              => $location->getName(),
            'BACK_TO_LOCATION'      => $this->helper->route('consim_core_location', array('location_id' => $location_id)),
		));

        // Send all data to the template file
        return $this->helper->render('consim_building.html', $this->user->lang('INDEX'));
    }

    /**
	* Initiated all important variable
    * and check if it a consim-user
	*
	* @return null
	* @access private
	*/
    private function init()
    {
        if($this->user->data['consim_register'] == 0)
		{
			redirect($this->helper->route('consim_core_register'));
			return;
		}

        // Add language file
		$this->user->add_lang_ext('consim/core', 'consim_common');

        //Check all finished Actions
        $this->container->get('consim.core.operators.ActionLists')->finishedActions();

        //Get the ConSim-User
		$this->consim_user = $this->container->get('consim.core.entity.ConsimUser')->load($this->user->data['user_id']);

        // Set output vars for display in the template
		$this->template->assign_vars(array(
	    	'SPRACHE_TADSOWISCH'			=> $this->consim_user->getSpracheTadsowisch(),
	    	'SPRACHE_BAKIRISCH'				=> $this->consim_user->getSpracheBakirisch(),
	    	'SPRACHE_SURANISCH'				=> $this->consim_user->getSpracheSuranisch(),
	    	'RHETORIK'						=> $this->consim_user->getRhetorik(),
	    	'WIRTSCHAFT'					=> $this->consim_user->getWirtschaft(),
            'ADMINISTRATION'				=> $this->consim_user->getAdministration(),
	      	'TECHNIK'						=> $this->consim_user->getTechnik(),
	      	'NAHKAMPF'						=> $this->consim_user->getNahkampf(),
			'SCHUSSWAFFEN'					=> $this->consim_user->getSchusswaffen(),
            'SPRENGMITTEL'					=> $this->consim_user->getSprengmittel(),
	      	'MILITARKUNDE'					=> $this->consim_user->getMilitarkunde(),
	      	'SPIONAGE'						=> $this->consim_user->getSpionage(),
            'SCHMUGGEL'					    => $this->consim_user->getSchmuggel(),
			'MEDIZIN'						=> $this->consim_user->getMedizin(),
	      	'UBERLEBENSKUNDE'				=> $this->consim_user->getUberlebenskunde(),
		));
    }
}
