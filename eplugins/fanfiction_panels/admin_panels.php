<?php

// Generated e107 Plugin Admin Area 

require_once('../../class2.php');
if (!getperms('P')) 
{
	e107::redirect('admin');
	exit;
}

e107::lan('fanfiction_panels', true);

 
require_once("class_fanfiction_adminarea.php");


class efiction_adminArea extends Fanfiction_adminArea
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

		'main/list'			=> array('caption'=> LAN_MANAGE, 'perm' => 'P'),
		'main/create'		=> array('caption'=> LAN_CREATE, 'perm' => 'P'),

		// 'main/div0'      => array('divider'=> true),
		// 'main/custom'		=> array('caption'=> 'Custom Page', 'perm' => 'P'),
		
	);

	protected $adminMenuAliases = array(
		'main/edit'	=> 'main/list'				
	);
  
     
  function renderMenu() {
     
	 //    include (e_PLUGIN.'efiction/admin_menu.php');
 
	} 	
 
}
 			
class fanfiction_panels_ui extends e_admin_ui
{
			
		protected $pluginTitle		= 'efiction';
		protected $pluginName		= 'efiction';
	//	protected $eventName		= 'efiction-fanfiction_panels'; // remove comment to enable event triggers in admin. 		
		protected $table			= 'fanfiction_panels';
		protected $pid				= 'panel_id';
		protected $perPage			= 99; 
		protected $batchDelete		= true;
		protected $batchExport     = true;
		protected $batchCopy		= true;

	//	protected $sortField		= 'somefield_order';
	//	protected $sortParent      = 'somefield_parent';
	//	protected $treePrefix      = 'somefield_title';

	//	protected $tabs				= array('Tabl 1','Tab 2'); // Use 'tab'=>0  OR 'tab'=>1 in the $fields below to enable. 
		
	//	protected $listQry      	= "SELECT * FROM `#tableName` WHERE field != '' "; // Example Custom Query. LEFT JOINS allowed. Should be without any Order or Limit.
	
		protected $listOrder		= 'panel_id DESC';
	
		protected $fields 		= array (
			'checkboxes'              => array (  'title' => '',  'type' => null,  'data' => null,  'width' => '5%',  'thclass' => 'center',  'forced' => true,  'class' => 'center',  'toggle' => 'e-multiselect',  'readParms' =>  array (),  'writeParms' =>  array (),),
			'panel_id'                => array (  'title' => LAN_ID,  'data' => 'int',  'type' => 'number' ),
			'panel_name'              => array (  'title' => LAN_NAME,  'type' => 'text',  'data' => 'int'),
			'panel_title'             => array (  'title' => LAN_TITLE,  'type' => 'text',  'data' => 'int', 'inline' => true  ),
			'panel_url'               => array (  'title' => LAN_URL,  'type' => 'text',  'data' => 'int'),
			'panel_level'             => array (  'title' => 'Level',  'type' => 'dropdown',  'data' => 'int', 'filter' => true),
			'panel_order'             => array (  'title' => LAN_ORDER,  'type' => 'number',  'data' => 'int'),
			'panel_hidden'            => array (  'title' => 'Hidden',  'type' => 'boolean',  'data' => 'int'),
			'panel_type'              => array (  'title' => LAN_TYPE,  'type' => 'dropdown',  'data' => 'int', 'filter' => true,  'inline' => true),
			'options'                 => array (  'title' => LAN_OPTIONS,  'type' => 'method',  'data' => null,  'width' => '10%',  'thclass' => 'center last',  'class' => 'center last',  'forced' => true,  'readParms' =>  array (),  'writeParms' =>  array (),),
		);		
		
		protected $fieldpref = array('panel_name', 'panel_title', 'panel_type');
		

	//	protected $preftabs        = array('General', 'Other' );
		protected $prefs = array(
		); 

	
		public function init()
		{
			// This code may be removed once plugin development is complete. 
 
  			$paneltypes = array("A" => _ADMIN, "U" => _USERACCOUNT, "P" => _PROFILE, "F" => _FAVOR, "S" => _SUBMISSIONS, "B" => _BROWSE, "L" => _10LISTS);
			$levels = array("0" => _LEVEL." 0", "1" => _LEVEL." 1",  "2" => _LEVEL." 2", "3" => _LEVEL." 3", "4" => _LEVEL." 4" );
			// Set drop-down values (if any). 
			$this->fields['panel_level']['writeParms']['optArray'] = $levels; // Example Drop-down array. 
			$this->fields['panel_type']['writeParms']['optArray'] = $paneltypes ; // Example Drop-down array. 
	                                              
			$this->postFiliterMarkup =  $this->AddButton()."<br>".$this->helpPage() ; 
		}

		function AddButton()
		{
 
			$text = "</fieldset></form><div class='e-container'>
			<table id='addbutton' style='".ADMIN_WIDTH."' class='table adminlist table-striped'>";
			$text .=  
			'<a href="'.e_PAGE.'?action=create"  
			class="btn batch e-hide-if-js btn-primary"><span>'._ADDNEWPANEL.'</span></a>';
			$text .= "</td></tr></table></div><form><fieldset>";
			return $text;
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

			return array('caption'=>$caption,'text'=> $text);

		}
			
 	
		// optional - a custom page.  
		public function helpPage()
		{
			$caption = LAN_HELP;
			$text = LAN_HELP_PANELS;
			return "<div class='well alert alert-info'>".$text."</div>";
			
		}
		
 
}

class fanfiction_panels_form_ui extends e_admin_form_ui
{
    /**
* Override the default Options field.
*
* @param $parms
* @param $value
* @param $id
* @param $attributes
*
* @return string
*/
function options($parms, $value, $id, $attributes)
{
$text = '';
if($attributes['mode'] == 'read')
{
 $text = "<div class='btn-group'>";
//add your button here
$text .= $this->renderValue('options',$value,$att,$id);
//or here
$text .= "</div>"; 
} 
return $text;
} 
}

new efiction_adminArea();
 
require_once(e_ADMIN."auth.php");


 e107::getAdminUI()->runPage();

require_once(e_ADMIN."footer.php");
exit;
?>
