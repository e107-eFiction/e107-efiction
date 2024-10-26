<?php

// Generated e107 Plugin Admin Area 

require_once('../../class2.php');
if (!getperms('P'))
{
	e107::redirect('admin');
	exit;
}

e107::lan('efiction', "en");
e107::lan('efiction', "en_admin");

$code = ' .bootstrap-switch-handle-on, .bootstrap-switch-handle-off {white-space: nowrap!important;} ';
e107::css('inline', $code);

class efiction_adminArea extends e_admin_dispatcher
{

	protected $modes = array(

		'main'	=> array(
			'controller' 	=> 'efiction_ui',
			'path' 			=> null,
			'ui' 			=> 'efiction_form_ui',
			'uipath' 		=> null
		)
	);


	protected $adminMenu = array(

		'main/prefs' 		=> array('caption' => LAN_PREFS, 'perm' => 'P'),


	);

	protected $adminMenuAliases = array(
		'main/edit'	=> 'main/list'
	);

	protected $menuTitle = _SETTINGS;

	function init()
	{
		//if (E107_DEBUG_LEVEL > 0)
		if (TRUE)
		{
			//$this->adminMenu['opt3'] = array('divider' => true);
			$this->adminMenu['main/update'] = array('caption' => "Update from efiction > 3.5", 
			'perm' => 0, 'uri' => '{e_PLUGIN}efiction/efiction_update.php');
		}
	}

}


class efiction_ui extends e_admin_ui
{

	protected $pluginTitle		= _SETTINGS;
	protected $pluginName		= 'efiction';
	//	protected $eventName		= 'efiction-'; // remove comment to enable event triggers in admin. 		
	protected $table			= 'efiction';
	protected $pid				= 'sitekey';
	protected $perPage			= 10;
	protected $batchDelete		= true;
	protected $batchExport     = true;
	protected $batchCopy		= true;

	//	protected $sortField		= 'somefield_order';
	//	protected $sortParent      = 'somefield_parent';
	//	protected $treePrefix      = 'somefield_title';


	//	protected $listQry      	= "SELECT * FROM `#tableName` WHERE field != '' "; // Example Custom Query. LEFT JOINS allowed. Should be without any Order or Limit.

	protected $listOrder		= 'sitekey DESC';

	protected $fields 		= array( );
 

	protected $preftabs        =  array( );


	protected $prefs = array( 
	);


	public function init()
	{ 
	}

	// ------- Customize Create --------

	public function beforeCreate($new_data, $old_data)
	{
		return $new_data;
	}

	public function afterCreate($new_data, $old_data, $id)
	{
		// do something
	}

	public function onCreateError($new_data, $old_data)
	{
		// do something		
	}


	// ------- Customize Update --------

	public function beforeUpdate($new_data, $old_data, $id)
	{
		return $new_data;
	}

	public function afterUpdate($new_data, $old_data, $id)
	{
		// do something	
	}

	public function onUpdateError($new_data, $old_data, $id)
	{
		// do something		
	}

	// left-panel help menu area. (replaces e_help.php used in old plugins)
	public function renderHelp()
	{
		$caption = LAN_HELP;
		$text = 'Disabled settings are set in Core e107 preferences';

		return array('caption' => $caption, 'text' => $text);
	}

 
}




class efiction_form_ui extends e_admin_form_ui
{
}


new efiction_adminArea();

require_once(e_ADMIN . "auth.php");
e107::getAdminUI()->runPage();

require_once(e_ADMIN . "footer.php");
exit;
