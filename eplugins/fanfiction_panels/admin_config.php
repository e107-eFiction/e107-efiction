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
		'main/list'			=> array('caption'=> LAN_MANAGE,   'perm' => 'P'),
		'main/create'		=> array('caption'=> _ADDNEWPANEL, 'perm' => 'P'   ),
		'main/typeA'		=> array('caption' => LAN_MANAGE,  'perm' => 'P'),
		// 'main/div0'      => array('divider'=> true),
		  'main/help'		=> array('caption'=> 'Help Page', 'perm' => 'P'),
		
	);

	protected $adminMenuAliases = array(
		'main/edit'	=> 'main/list'				
	);	
	
	protected $menuTitle = 'Fanfiction Panels';
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
		
	//	protected $listQry      	= "SELECT * FROM `#tableName` WHERE field != '' "; // Example Custom Query. LEFT JOINS allowed. Should be without any Order or Limit.
	
		protected $listOrder		= 'panel_order DESC';

	//large, xlarge, xxlarge, block-level	
	
		protected $fields 		= array (
			'checkboxes'              => array ( 'title' => '', 'type' => null, 'data' => null, 'width' => '5%', 'thclass' => 'center', 'forced' => 'value', 'class' => 'center', 'toggle' => 'e-multiselect', 'readParms' => [], 'writeParms' => [],),
			'panel_id'                => array ( 'title' => LAN_ID, 'type' => 'number', 'data' => 'int', 'width' => '5%', 'forced'=> true, 
			  'help' => '', 'readParms' => [], 'writeParms' => [], 'class' => 'left', 'thclass' => 'left',),
			'panel_name'              => array ( 'title' => LAN_NAME, 'type' => 'text', 
			'data' => 'safestr', 'width' => 'auto', 'inline' => true, 'validate' => true, 'help' => '', 'readParms' => [], 'writeParms' => ['size' => 'xlarge'], 'class' => 'left', 'thclass' => 'left',),
			'panel_title'             => array ( 'title' => LAN_TITLE, 'type' => 'text', 'data' => 'safestr', 'width' => 'auto', 'inline' => true, 
			'validate' => true, 'help' => '', 'readParms' => [], 'writeParms' => ['size'=>'block-level'], 'class' => 'left', 'thclass' => 'left',),
			'panel_url'               => array ( 'title' => LAN_URL, 'type' => 'text', 'data' => 'safestr', 'width' => 'auto', 'inline' => true, 'help' => '', 
			'readParms' => [], 'writeParms' => ['size' => 'xlarge'], 'class' => 'left', 'thclass' => 'left',),
			'panel_level'             => array ( 'title' => 'Level', 'type' => 'dropdown', 'data' => 'int', 'width' => 'auto', 'batch' => true, 
			'filter' => true, 'inline' => true,   'help' => '', 'readParms' => [], 'writeParms' => [], 'class' => 'left', 'thclass' => 'left',),
			'panel_order'             => array ( 'title' => LAN_ORDER, 'type' => 'number', 'data' => 'int', 'width' => 'auto', 'inline' => true, 'help' => '', 'readParms' => [], 'writeParms' => [], 'class' => 'left', 'thclass' => 'left',),
			'panel_hidden'            => array ( 'title' => 'Hidden', 'type' => 'boolean', 'data' => 'int', 'width' => 'auto', 'batch' => true, 'filter' => true, 
			'inline' => true, 'validate' => true, 'help' => '', 'readParms' => ['enabled' =>LAN_VISIBLE, 'disabled' => LAN_HIDDEN], 'writeParms' => ['enabled'=> LAN_VISIBLE, 'disabled' => LAN_HIDDEN], 'class' => 'left', 'thclass' => 'left',),
			'panel_type'              => array ( 'title' => LAN_TYPE, 'type' => 'dropdown', 'data' => 'safestr', 'width' => 'auto', 'batch' => true, 'filter' => true, 'inline' => true, 'validate' => true, 'help' => '', 'readParms' => [], 'writeParms' => [], 'class' => 'left', 'thclass' => 'left',),
			'options'                 => array ( 'title' => LAN_OPTIONS, 'type' => null, 'data' => null, 'width' => '10%', 'thclass' => 'center last', 
			'class' => 'center last', 'forced' => 'value', 'readParms' => ['sort'=>1], 'writeParms' => [],),
		);		
		
		protected $fieldpref = array('panel_id', 'panel_name', 'panel_title', 'panel_type');
		

	//	protected $preftabs        = array('General', 'Other' );
		protected $prefs = array(
		); 

	
		public function init()
		{
			// This code may be removed once plugin development is complete. 
			if(!e107::isInstalled('fanfiction_panels'))
			{
				e107::getMessage()->addWarning("This plugin is not yet installed. Saving and loading of preference or table data will fail.");
			}

		$paneltypes = e107::getEfiction()->getPanelTypes();
		$levels =   e107::getEfiction()->getUserLevels();

		// Set drop-down values (if any). 
		$this->fields['panel_level']['writeParms']['optArray'] = $levels; // Example Drop-down array. 
		$this->fields['panel_type']['writeParms']['optArray'] = $paneltypes; // Example Drop-down array. 

	
		}

		// ------- Customize Create --------
		
		public function beforeCreate($new_data,$old_data)
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
			$text = LAN_HELP_PANELS;

			return array('caption' => $caption, 'text' => $text);

		}

		// optional - a custom page.  
		public function helpPage()
		{
			$caption = LAN_HELP;
			$text = LAN_HELP_PANELS;
			// Add the HTML structure to $text
			$text = "<div class='well alert alert-info'>" . $text ;

			// Read the content of the help/default_panels.html file
			$htmlContent = file_get_contents('help/default_panels.htm');

			// Concatenate the content of the HTML file to $text
			$text .=
		$htmlContent . "</div>"; 

			// Return the final content
			return $text;
		}
			
	/*	
		// optional - a custom page.  
		public function customPage()
		{
			if($this->getPosted('custom-submit')) // after form is submitted. 
			{
				e107::getMessage()->addSuccess('Changes made: '. $this->getPosted('example'));
			}

			$this->addTitle('My Custom Title');


			$frm = $this->getUI();
			$text = $frm->open('my-form', 'post');

				$tab1 = "<table class='table table-bordered adminform'>
					<colgroup>
						<col class='col-label'>
						<col class='col-control'>
					</colgroup>
					<tr>
						<td>Label ".$frm->help('A help tip')."</td>
						<td>".$frm->text('example', $this->getPosted('example'), 80, ['size'=>'xlarge'])."</td>
					</tr>
					</table>";

			// Display Tab
			$text .= $frm->tabs([
				'general'   => ['caption'=>LAN_GENERAL, 'text' => $tab1],
			]);

			$text .= "<div class='buttons-bar text-center'>".$frm->button('custom-submit', 'submit', 'submit', LAN_CREATE)."</div>";
			$text .= $frm->close();

			return $text;
			
		}
			
		
		
	*/
			
}
				


class fanfiction_panels_form_ui extends e_admin_form_ui
{

}		
		
		
new fanfiction_panels_adminArea();

require_once(e_ADMIN."auth.php");
e107::getAdminUI()->runPage();

require_once(e_ADMIN."footer.php");
exit;
