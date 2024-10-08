<?php

// Generated e107 Plugin Admin Area 

require_once('../../class2.php');
if (!getperms('P'))
{
	e107::redirect('admin');
	exit;
}
//e107::addHandler('e_efiction', "{e_PLUGIN}efiction/efiction_handler.php");

e107::lan('fanfiction_panels', true);

$code = " 
$(document).ready(function() {
    var currentUrl = window.location.href;
    $('#admin-ui-nav-menu li').each(function() {
        var link = $(this).find('a').attr('href');
        if (link === currentUrl) {
            $('#admin-ui-nav-menu li').removeClass('active');
            $(this).addClass('active');
        }
    });
});
";

e107::js('footer-inline', $code);

class fanfiction_panels_adminArea extends e_admin_dispatcher
{

	protected $modes = array(

		'main'	=> array(
			'controller' 	=> 'fanfiction_panels_ui',
			'path' 			=> null,
			'ui' 			=> 'fanfiction_panels_form_ui',
			'uipath' 		=> null
		),


	);


	protected $adminMenu = array(
		//'main/prefs'		=> array('caption' => LAN_PREFS, 'perm' => 'P'),
		'main/list'			=> array('caption' => LAN_MANAGE,   'perm' => 'P'),
		'main/create'		=> array('caption' => _ADDNEWPANEL, 'perm' => 'P'),
		'main/div0'      => array('divider' => true),
 

	);

	protected $adminMenuAliases = array();

	protected $menuTitle = 'Fanfiction Panels';

	function init()
	{
		$panelTypes = e107::getEfiction()->getPanelTypes();

		foreach ($panelTypes as $key => $value)
		{
			$mode = "main";
			$action =  $key;

			$menu = $mode . '/' . $action;
			$this->adminMenu[$menu] = array(
				'caption' => $value,
				'perm' => 'P',
				'type' => $key
			);
		}

		$this->adminMenu['main/div1'] = array('divider' => true);

		$this->adminMenu['main/help'] = array(
			'caption' => 'Help Page',
			'perm' => 'P'
		);
		$this->adminMenu['main/maintenance'] = array(
			'caption' => _PANELORDER,
			'perm' => '0'
		);
	}
}





class fanfiction_panels_ui extends e_admin_ui
{

	protected $pluginTitle		= 'Fanfiction Panels';
	protected $pluginName		= 'fanfiction_panels';
	//	protected $eventName		= 'fanfiction_panels-'; // remove comment to enable event triggers in admin. 		
	protected $table			= 'fanfiction_panels';
	protected $pid				= 'panel_id';
	protected $perPage			= 20;
	protected $batchDelete		= true;
	protected $batchExport      = true;
	protected $batchCopy		= true;

	protected $sortField		= 'panel_order';
	//	protected $sortParent      = 'somefield_parent';
	//	protected $treePrefix      = 'somefield_title';

	//	protected $tabs				= array('tab1'=>'Tab 1', 'tab2'=>'Tab 2'); // Use 'tab'=>'tab1'  OR 'tab'=>'tab2' in the $fields below to enable. 


	protected $listOrder		= 'panel_order DESC';

	//large, xlarge, xxlarge, block-level	

	protected $fields 		= array(
		'checkboxes'              => array('title' => '', 'type' => null, 'data' => null, 'width' => '5%', 'thclass' => 'center', 'forced' => 'value', 'class' => 'center', 'toggle' => 'e-multiselect', 'readParms' => [], 'writeParms' => [],),
		'panel_id'                => array(
			'title' => LAN_ID,
			'type' => 'number',
			'data' => 'int',
			'width' => '5%',
			'forced' => true,
			'help' => '',
			'readParms' => [],
			'writeParms' => [],
			'class' => 'left',
			'thclass' => 'left',
		),
		'panel_name'              => array(
			'title' => LAN_NAME,
			'type' => 'text',
			'data' => 'safestr',
			'width' => 'auto',
			'inline' => true,
			'validate' => true,
			'help' => '',
			'readParms' => [],
			'writeParms' => ['size' => 'xlarge'],
			'class' => 'left',
			'thclass' => 'left',
		),
		'panel_title'             => array(
			'title' => LAN_TITLE,
			'type' => 'text',
			'data' => 'safestr',
			'width' => 'auto',
			'inline' => true,
			'validate' => true,
			'help' => '',
			'readParms' => [],
			'writeParms' => ['size' => 'block-level'],
			'class' => 'left',
			'thclass' => 'left',
		),
		'panel_url'               => array(
			'title' => LAN_URL,
			'type' => 'text',
			'data' => 'safestr',
			'width' => 'auto',
			'inline' => true,
			'help' => '',
			'readParms' => [],
			'writeParms' => ['size' => 'xlarge'],
			'class' => 'left',
			'thclass' => 'left',
		),
		'panel_level'             => array(
			'title' => 'Level',
			'type' => 'dropdown',
			'data' => 'int',
			'width' => 'auto',
			'batch' => true,
			'filter' => true,
			'inline' => true,
			'help' => '',
			'readParms' => [],
			'writeParms' => [],
			'class' => 'left',
			'thclass' => 'left',
		),
		'panel_order'             => array('title' => LAN_ORDER, 'type' => 'number', 'data' => 'int', 'width' => 'auto', 'inline' => true, 'help' => '', 'readParms' => [], 'writeParms' => [], 'class' => 'left', 'thclass' => 'left',),
		'panel_hidden'            => array(
			'title' => 'Hidden',
			'type' => 'boolean',
			'data' => 'int',
			'width' => 'auto',
			'batch' => true,
			'filter' => true,
			'inline' => true,
			'validate' => true,
			'help' => '',
			'readParms' => ['enabled' => LAN_VISIBLE, 'disabled' => LAN_HIDDEN],
			'writeParms' => ['enabled' => LAN_VISIBLE, 'disabled' => LAN_HIDDEN],
			'class' => 'left',
			'thclass' => 'left',
		),
		'panel_type'              => array('title' => LAN_TYPE, 'type' => 'dropdown', 'data' => 'safestr', 'width' => 'auto', 'batch' => true, 'filter' => true, 'inline' => true, 'validate' => true, 'help' => '', 'readParms' => [], 'writeParms' => [], 'class' => 'left', 'thclass' => 'left',),
		'options'                 => array(
			'title' => LAN_OPTIONS,
			'type' => null,
			'data' => null,
			'width' => '10%',
			'thclass' => 'center last',
			'class' => 'center last',
			'forced' => 'value',
			'readParms' => ['sort' => 1],
			'writeParms' => [],
		),
	);

	protected $fieldpref = array('panel_id', 'panel_name', 'panel_title', 'panel_type');


	//	protected $preftabs        = array('General', 'Other' );
	protected $prefs = array();


	public function init()
	{
		// This code may be removed once plugin development is complete. 
		if (!e107::isInstalled('fanfiction_panels'))
		{
			e107::getMessage()->addWarning("This plugin is not yet installed. Saving and loading of preference or table data will fail.");
		}

		$paneltypes = e107::getEfiction()->getPanelTypes();
		$levels =   e107::getEfiction()->getUserLevels();

		// Set drop-down values (if any). 
		$this->fields['panel_level']['writeParms']['optArray'] = $levels;
		$this->fields['panel_type']['writeParms']['optArray'] = $paneltypes;

		//	protected $listQry      	= "SELECT * FROM `#tableName` WHERE field != '' "; // Example Custom Query. LEFT JOINS allowed. Should be without any Order or Limit.
		$action = $this->getAction();
		if (array_key_exists($action, $paneltypes))
		{
			$this->listQry = "SELECT * FROM `#{$this->table}` WHERE panel_type = '{$action}' ";
		}
	}


	// ------- Customize Create --------

	public function beforeCreate($new_data, $old_data)
	{
		return $new_data;
	}

	public function afterCreate($new_data, $old_data, $id)
	{
		$this->updatePanelOrder();
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
		$text = LAN_HELP_PANELS;

		return array('caption' => $caption, 'text' => $text);
	}

	// optional - a custom page.  
	public function helpPage()
	{
		$caption = LAN_HELP;
		$text = LAN_HELP_PANELS;
		// Add the HTML structure to $text
		$text = "<div class='well alert alert-info'>" . $text;

		// Read the content of the help/default_panels.html file
		$htmlContent = file_get_contents('help/default_panels.htm');

		// Concatenate the content of the HTML file to $text
		$text .=
			$htmlContent . "</div>";

		// Return the final content
		return $text;
	}


	public function maintenancePage()
	{
		$this->addTitle(_ARCHIVEMAINT);
		$frm = $this->getUI();

		$text = _HELP_PANELORDER;

		if ($this->getPosted('maint-panels')) // after form is submitted. 
		{
			$this->updatePanelOrder();
			return e107::getMessage()->addSuccess(_ACTIONSUCCESSFUL)->render();	 
		}
		$text .= $frm->open('maint-panels', 'post');
		$text .= "<div class='buttons-bar text-left'>" . $frm->button('maint-panels', 'submit', 'submit', _PANELORDER) . "</div>";
		$text .= $frm->close();

		return $text;
	}

	public function updatePanelOrder()
	{

		$ptypes_query = "SELECT panel_type FROM " . MPREFIX . "fanfiction_panels GROUP BY panel_type";

		$ptypes = e107::getDb()->retrieve($ptypes_query, true);
		foreach ($ptypes as $ptype)
		{
			if ($ptype['panel_type'] == "A")
			{
				for ($x = 0; $x < 5; $x++)
				{
					$count = 1;

					$panels_query = "SELECT panel_name, panel_id FROM " . MPREFIX . "fanfiction_panels WHERE panel_hidden = '0'  AND panel_type = '" . $ptype['panel_type'] . "' AND panel_level = '$x' ORDER BY panel_level, panel_order";
					$panels = e107::getDb()->retrieve($panels_query, true);

					foreach ($panels as $p)
					{
						e107::getDb()->gen("UPDATE " . MPREFIX . "fanfiction_panels SET panel_order = '$count' WHERE panel_id = '" . $p['panel_id'] . "' LIMIT 1", true);
						$count++;
					}
				}
			}
			else
			{
				$count = 1;
				$panels_query = "SELECT panel_name, panel_id FROM " . MPREFIX . "fanfiction_panels WHERE panel_hidden = '0' AND panel_type = '" . $ptype['panel_type'] . "' AND panel_level = '$x' ORDER BY panel_level, panel_order";
				$panels = e107::getDb()->retrieve($panels_query, true);

				foreach ($panels as $p)
				{
					e107::getDb()->gen("UPDATE " . MPREFIX . "fanfiction_panels SET panel_order = '$count' WHERE panel_id = '" . $p['panel_id'] . "' LIMIT 1");
					$count++;
				}
				$count++;
			}
		}
	}
}



class fanfiction_panels_form_ui extends e_admin_form_ui
{
}


new fanfiction_panels_adminArea();

require_once(e_ADMIN . "auth.php");
e107::getAdminUI()->runPage();

require_once(e_ADMIN . "footer.php");
exit;
