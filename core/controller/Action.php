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
class Action
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

	public function travel($travel_id)
	{
        //Check the request
        if (!$this->is_valid($travel_id) || !check_link_hash($this->request->variable('hash', ''), 'travel_' . $travel_id))
		{
			throw new \phpbb\exception\http_exception(403, 'NO_AUTH_OPERATION');
		}

        //Load ConsimUser
        $consim_user = $this->container->get('consim.core.entity.ConsimUser')->load($this->user->data['user_id']);

        //Check, if user not active
        if($consim_user->getActive())
        {
            throw new \phpbb\exception\http_exception(403, 'NO_AUTH_OPERATION');
        }

        //Get Infos about the Route
        $route = $this->container->get('consim.core.entity.Route')->load($consim_user->getLocation(), $travel_id);

        //Add new Travel Action
        $consim_user = $this->container->get('consim.core.entity.Travel')
                                       ->insert($consim_user->getUserId(),
                                                time(),
                                                (time() + $route->getTime()),
                                                $consim_user->getLocation(),
                                                $travel_id);

		//$consim_user->setLocation($travel_id);
        //$consim_user->save();

		//Reload the Consim Index
		redirect($this->helper->route('consim_core_index'));
    }

    protected function is_valid($value)
	{
		return !empty($value) && preg_match('/^\w+$/', $value);
	}
}
