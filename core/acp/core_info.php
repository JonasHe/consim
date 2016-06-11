<?php

/**
*
* @package ConSim for phpBB3.1
* @copyright (c) 2015 Marco Candian (tacitus@strategie-zone.de)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace consim\core\acp;
	
class core_info
{
	public function module()
	{
		return array(
			'filename'	=> '\consim\core\acp\core_module',
			'title'		=> 'CONSIM',
			'modes'		=> array(
				'news'			=> array('title' => 'CONSIM_NEWS', 'auth' => 'ext_consim/core && acl_a_extensions',	'cat' =>  array('ACP_CAT_CONSIM')),
			),
		);
	}
}
