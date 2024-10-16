<?php

// Generated e107 Plugin Admin Area 

require_once('../../class2.php');
if (!getperms('P'))
{
	e107::redirect('admin');
	exit;
}

e107::lan('fanfiction_pagelinks', true);


class fanfiction_pagelinks_adminArea extends e_admin_dispatcher
{

	protected $modes = array(

		'main'	=> array(
			'controller' 	=> 'fanfiction_pagelinks_ui',
			'path' 			=> null,
			'ui' 			=> 'fanfiction_pagelinks_form_ui',
			'uipath' 		=> null
		),


	);


	protected $adminMenu = array(

		'main/list'			=> array('caption' => LAN_MANAGE, 'perm' => 'P'),
		'main/create'		=> array('caption' => LAN_CREATE, 'perm' => 'P'),

		'main/prefs' 		=> array('caption' => LAN_PREFS, 'perm' => 'P'),


	);

	protected $adminMenuAliases = array(
		'main/edit'	=> 'main/list'
	);

	protected $menuTitle = 'Fanfiction Pagelinks';
}





class fanfiction_pagelinks_ui extends e_admin_ui
{

	protected $pluginTitle		= 'Fanfiction Pagelinks';
	protected $pluginName		= 'fanfiction_pagelinks';
	//	protected $eventName		= 'fanfiction_pagelinks-fanfiction_pagelinks'; // remove comment to enable event triggers in admin. 		
	protected $table			= 'fanfiction_pagelinks';
	protected $pid				= 'link_id';
	protected $perPage			= 10;
	protected $batchDelete		= true;
	protected $batchExport     = true;
	protected $batchCopy		= true;

	//	protected $sortField		= 'somefield_order';
	//	protected $sortParent      = 'somefield_parent';
	//	protected $treePrefix      = 'somefield_title';

	//	protected $tabs				= array('tab1'=>'Tab 1', 'tab2'=>'Tab 2'); // Use 'tab'=>'tab1'  OR 'tab'=>'tab2' in the $fields below to enable. 

	//	protected $listQry      	= "SELECT * FROM `#tableName` WHERE field != '' "; // Example Custom Query. LEFT JOINS allowed. Should be without any Order or Limit.

	protected $listOrder		= 'link_id DESC';

	protected $fields 		= array(
		'checkboxes'              => array('title' => '', 'type' => null, 'data' => null, 'width' => '5%', 'thclass' => 'center', 'forced' => 'value', 'class' => 'center', 'toggle' => 'e-multiselect', 'readParms' => [], 'writeParms' => [],),
		'link_id'                 => array('title' => LAN_ID, 'type' => 'number', 'data' => 'int', 'width' => '5%', 'help' => '', 'readParms' => [], 'writeParms' => [], 'class' => 'left', 'thclass' => 'left',),
		'link_name'               => array(
			'title' => LAN_NAME,
			'type' => 'text',
			'data' => 'safestr',
			'width' => 'auto',
			'filter' => true,
			'inline' => false,
			'help' => '',
			'readParms' => [],
			'writeParms' => [],
			'class' => 'left',
			'thclass' => 'left',
		),
		'link_text'               => array(
			'title' => 'Text',
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
		'link_url'                => array(
			'title' => LAN_URL,
			'type' => 'url',
			'data' => 'safestr',
			'width' => 'auto',
			'inline' => false,
			'help' => '',
			'readParms' => [],
			'writeParms' => [],
			'class' => 'left',
			'thclass' => 'left',
		),
		'link_target'             => array('title' => LAN_TARGET, 'type' => 'dropdown', 'data' => 'int', 'width' => 'auto', 'batch' => true, 'help' => '', 'readParms' => [], 'writeParms' => [], 'class' => 'left', 'thclass' => 'left', 'filter' => false,),
		'link_access'             => array('title' => LAN_LINKACCESS, 'type' => 'dropdown', 'data' => 'int', 'width' => 'auto', 'batch' => true, 'help' => '', 'readParms' => [], 'writeParms' => [], 'class' => 'left', 'thclass' => 'left', 'filter' => false,),
		'options'                 => array('title' => LAN_OPTIONS, 'type' => null, 'data' => null, 'width' => '10%', 'thclass' => 'center last', 'class' => 'center last', 'forced' => 'value', 'readParms' => [], 'writeParms' => [],),
	);

	protected $fieldpref = array('link_name', 'link_text', 'link_url', 'link_target', 'link_access' );


	//	protected $preftabs        = array('General', 'Other' );
	protected $prefs = array(
		'linkstyle'		=> array(
			'title' => LAN_LINKRANGE,
			'tab' => 0,
			'type' => 'dropdown',
			'data' => 'str',
			'help' => LAN_HELP_LINKSTYLE,
			'writeParms' => ['optArray' => array(2 => LAN_BOTHLINKSTYLE, 1 => LAN_FIRSTLAST, 0 => LAN_NEXTPREV)]
		),

		'linkrange'		=> array(
			'title' => LAN_LINKSTYLE,
			'tab' => 0,
			'type' => 'number',
			'data' => 'int',
			'help' => LAN_HELP_LINKRANGE,
			'writeParms' => ['default' => 5]
		),


	);


	public function init()
	{

		// Set drop-down values (if any). 
		$this->fields['link_target']['writeParms']['optArray'] = array(0 => LAN_SAMEWINDOW, 1 => LAN_NEWWINDOW);
		$this->fields['link_access']['writeParms']['optArray'] = array(0 => LAN_ALL, 1 => LAN_MEMBERS, 2 => LAN_ADMIN);
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
		$text = "This page controls the page links used in the menu block and available for use in the skin as individual links.<br>";
		$text .= LAN_NAMECONVENTIONS;


		$text .= "<br><br><b>Style of Page links</b> - There are three possible styles of page links. 
    <ul>
      <li>Next and Previous - A range of pages plus &quot;Next&quot; and &quot;Previous&quot; 
        links. For Example: <a href='#prev'>Previous</a> <a href='#3'>3</a><a href='#4'>4</a><a href='#5'>5</a><a href='#6'>6</a><a href='#7'>7</a> 
        <a href='#next'>Next</a></li>
      <li>First and Last Pages - A range of pages plus the first and last page. 
        For Example: <a href='#1'>1</a>...<a href='#3'>3</a><a href='#4'>4</a><a href='#5'>5</a><a href='#6'>6</a><a href='#7'>7</a>...<a href='#10'>10</a> 
      </li>
      <li>Both - A range of pages plus both first and last and next and previous 
        links. For Example: <a href='#prev'>Previous</a> <a href='#1'>1</a>...<a href='#3'>3</a><a href='#4'>4</a><a href='#5'>5</a><a href='#6'>6</a><a href='#7'>7</a>...<a href='#10'>10</a> 
        <a href='#next'>Next</a></li>
    </ul>
  
   <b>Size of Range for Page Links </b>- The number of pages in the range of page 
    links to display. In the examples above, the range was 5 pages (3-7). ";

		return array('caption' => $caption, 'text' => $text);
	}
}



class fanfiction_pagelinks_form_ui extends e_admin_form_ui
{
}


new fanfiction_pagelinks_adminArea();

require_once(e_ADMIN . "auth.php");
e107::getAdminUI()->runPage();

require_once(e_ADMIN . "footer.php");
exit;
