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
			'controller' 	=> 'fanfiction_settings_ui',
			'path' 			=> null,
			'ui' 			=> 'fanfiction_settings_form_ui',
			'uipath' 		=> null
		)
	);


	protected $adminMenu = array(

		'main/prefs' 		=> array('caption' => LAN_PREFS, 'perm' => 'P'),
		'main/settings'		=> array('caption' => _MAINSETTINGS, 'perm' => 'P'),


	);

	protected $adminMenuAliases = array(
		'main/edit'	=> 'main/list'
	);

	protected $menuTitle = _SETTINGS;

 

}


class fanfiction_settings_ui extends e_admin_ui
{

	protected $pluginTitle		= _SETTINGS;
	protected $pluginName		= 'fanfiction_settings';
 
 
	protected $preftabs        =  array(

		0 => _SUBMISSIONSETTINGS,  //submissions
		1 => _SITESETTINGS,        //sitesettings ?to replace
		2 => _DISPLAYSETTINGS,
		3 => _REVIEWSETTINGS,
		4 => _USERSETTINGS,
		// 5 => _EMAILSETTINGS,
		// 6 => _CENSOR,
		// 7 => _WELCOME,
		// 8 => _COPYRIGHT,
		// 9 => _PRINTERCOPYRIGHT,
		// 10 => _TINYMCE,
		// 11 => _NOTHANKYOU,
		// 12 => _THANKYOU
	);


	protected $prefs = array(
		'submissionsoff'          => array('tab' => 0,   'title' => _NOSUBS,  'help' => _HELP_SUBSOFF, 'type' => 'boolean',  'data' => 'int',),
		'autovalidate'            => array('tab' => 0,   'title' => _AUTOVALIDATE,  'help' => _HELP_AUTOVALIDATE, 'type' => 'boolean',  'data' => 'int',),
		'coauthallowed'           => array('tab' => 0,  'title' => _COAUTHALLOW,  'help' => _HELP_COAUTHORS, 'type' => 'boolean',  'data' => 'int',),
		'roundrobins'             => array(
			'tab' => 0,
			'title' => _ALLOWRR,
			'help' => _HELP_ROUNDROBINS,
			'type' => 'boolean',
			'data' => 'int',
		),
		'allowseries'             => array(
			'tab' => 0,
			'title' => _ALLOWSERIES,
			'help' => _HELP_ALLOWSERIES,
			'type' => 'dropdown',
			'data' => 'int',
			'readParms' => ['optArray' => array('2' => _ALLMEMBERS, '1' => _AUTHORSONLY, '0' => _ADMINS)],
			'writeParms' => ['optArray' => array('2' => _ALLMEMBERS, '1' => _AUTHORSONLY, '0' => _ADMINS)]
		),
		'imageheader1'             => array(
			'tab' => 0,
			'title' =>  _IMAGESIZE,
			'help' => _HELP_IMAGESIZE,
			'type' => null,
			'data' => false,
			'write_parms' => array("readonly" => true)
		),
		'imageupload'             => array('tab' => 0, 'title' => _IMAGEUPLOAD,  'help' => _HELP_IMAGEUPLOAD, 'type' => 'boolean',  'data' => 'int',),
		'imageheight'             => array(
			'tab' => 0,
			'title' =>  _MAXHEIGHT,
			'type' => 'number',
			'write_parms' => array("default" => 200),
			'data' => 'int'
		),
		'imagewidth'              => array(
			'tab' => 0,
			'title' =>  _MAXWIDTH,
			'write_parms' => array("default" => 200),
			'type' => 'number',
			'data' => 'int',
		),

		'store'                   => array(
			'tab' => 0,
			'title' => _HOWSTORE,
			'help' => _HELP_STORE,
			'type' => 'dropdown',
			'data' => 'str',
			'readParms' => ['optArray' => array('files' => 'files', 'mysql' => 'mysql')],
			'writeParms' => ['optArray' => array('files' => 'files', 'mysql' => 'mysql')]
		),

		'storiespath'             => array(
			'tab' => 0,
			'title' => _STORIESPATH,
			'help' => _HELP_STORIESPATH,
			'type' => 'text',
			'data' => 'str',
			'writeParms' => ['default' => 'stories']
		),
		'wordsheader1'             => array(
			'tab' => 0,
			'title' =>  _MAXMINWORDS,
			'help' => _HELP_MINMAXWORDS,
			'type' => null,
			'data' => false,
			'write_parms' => array("readonly" => true)
		),
		'minwords'                => array('tab' => 0,  'title' => _MIN, 'type' => 'number',  'data' => 'int',),
		'maxwords'                => array('tab' => 0,  'title' => _MAX, 'type' => 'number',  'data' => 'int',),


		'tinyMCE'                 => array(
			'tab' => 1,
			'title' => _USETINYMCE,
			'help' => _HELP_TINYMCE . " " . _TINYMCENOTE,
			'type' => 'dropdown',
			'data' => 'int',
			'readParms' => ['optArray' => array('0' => _NO, '1' => 'TinyMce3', '2' => 'TinyMce4')],
			'writeParms'
			=> ['optArray' => array('0' => _NO, '1' => 'TinyMce3', '2' => 'TinyMce4')],
		),
		'allowed_tags'            => array(
			'tab' => 1,
			'title' => _TAGS,
			'help' => _HELP_SITEKEY,
			'type' => 'text',
			'data' => 'str',
			'readParms' => 'block-level',
			'writeParms' => [
				'default' => '<strong><em><br /><br><blockquote><strike><font><b><i><u><center><img><a><hr><p><ul><li><ol>',
				'size' => 'block-level'
			]
		),
		'favorites'               => array('tab' => 1, 'title' => _FAVORITES, 'help' => _HELP_FAVORITES,  'type' => 'boolean',  'data' => 'int',),
		'multiplecats'            => array(
			'tab' => 1,
			'title' => _NUMCATS,
			'help' => _HELP_NUMCATS,
			'type' => 'boolean',
			'data' => 'int',
			'readParms' => array("disabled" => _ONLYONE, "enabled" => _MORETHANONE),
			'writeParms' => array("disabled" => _ONLYONE, "enabled" => _MORETHANONE)
		),
		'newscomments'            => array(
			'tab' => 1,
			'title' => _NEWSCOMMENTS,
			'help' => _HELP_NEWSCOMMENTS,
			'type' => 'boolean',
			'readParms' => ['readonly' => 1],
			'writeParms' => ['readonly' => 1],
			'data' => 'int',
		),
		'logging'                 => array(
			'tab' => 1,
			'title' => _LOGGING,
			'help' => _HELP_LOGGING,
			'type' => 'boolean',
			'readParms' => ['readonly' => 1],
			'writeParms' => ['readonly' => 1],
			'data' => 'int',
		),
		'maintenance'           => array(
			'tab' => 1,
			'title' => _MAINTENANCE,
			'help' => _HELP_MAINTENANCE,
			'type' => 'boolean',
			'readParms' => ['readonly' => 1],
			'writeParms' => ['readonly' => 1],
			'data' => 'int',
		),
		'debug'                   => array(
			'tab' => 1,
			'title' => _DEBUG,
			'help' => _HELP_SITEKEY,
			'type' => 'boolean',
			'readParms' => ['readonly' => 1],
			'writeParms' => ['readonly' => 1],
			'data' => 'int',
		),
		'captcha'                 => array(
			'tab' => 1,
			'title' => _CAPTCHA,
			'help' => _HELP_CAPTCHA,
			'type' => 'boolean',
			'readParms' => ['readonly' => 1],
			'writeParms' => ['readonly' => 1],
			'data' => 'int',
		),
 
		'dateformat'              => array('tab' => 2, 'title' => _DATEFORMAT,  'help' => _HELP_DATEFORMAT, 'type' => 'dropdown',  'data' => 'str',),
		'timeformat'              => array('tab' => 2, 'title' => _TIMEFORMAT,  'help' => _HELP_TIMEFORMAT, 'type' => 'dropdown',  'data' => 'str',),

		'extendcats'              => array(
			'tab' => 2,
			'title' => _EXTENDCATS,
			'help' => _HELP_EXTENDCATS,
			'readParms' => array("disabled" => _NO, "enabled" => _YES),
			'writeParms' => array("disabled" => _NO, "enabled" => _YES),
			'type' => 'boolean',
			'data' => 'int',
		),
		'displaycolumns'          => array(
			'tab' => 2,
			'title' => _COLUMNS,
			'help' => _HELP_COLUMNS,
			'type' => 'number',
			'writeParms' => ['default' => 2],

			'data' => 'int',
		),
		'itemsperpage'            => array(
			'tab' => 2,
			'title' => _NUMITEMS,
			'help' => _HELP_ITEMSPERPAGE,
			'type' => 'number',
			'writeParms' => ['default' => 24],
			'data' => 'int',
		),

		'recentdays'              => array(
			'tab' => 2,
			'title' => _RECENTDAYS,
			'help' => _HELP_RECENTDAYS,
			'type' => 'number',

			'writeParms' => ['default' => 7],
			'data' => 'int',
		),
		'defaultsort'             => array(
			'tab' => 2,
			'title' => _DEFAULTSORT,
			'help' => _HELP_DEFAULTSORT,
			'type' => 'boolean',
			'data' => 'int',
			'readParms' => array("disabled" => _ALPHA, "enabled" => _MOSTRECENT),
			'writeParms' => array("disabled" => _ALPHA, "enabled" => _MOSTRECENT)
		),


		'displayindex'            => array(
			'tab' => 2,
			'title' => _STORYINDEX,
			'help' => _HELP_DISPLAYINDEX,
			'type' => 'boolean',
			'data' => 'int',
			'readParms' => array("disabled" => _NO, "enabled" => _YES),
			'writeParms' => array("disabled" => _NO, "enabled" => _YES)
		),
		'displayprofile'          => array(
			'tab' => 2,
			'title' => _DISPLAYPROFILE,
			'help' => _HELP_DISPLAYPROFILE,
			'type' => 'boolean',
			'readParms' => array("disabled" => _NO, "enabled" => _YES),
			'writeParms' => array("disabled" => _NO, "enabled" => _YES),
			'data' => 'int',
		),

		'reviewsallowed'          => array(
			'title' => _ONREVIEWS,
			'tab' => 3,
			'help' => _HELP_REVIEWSON,
			'type' => 'boolean',
			'readParms' => array("disabled" => _NO, "enabled" => _YES),
			'writeParms' => array("disabled" => _NO, "enabled" => _YES),
			'data' => 'int',
		),
		'anonreviews'             => array(
			'title' => _ANONREVIEWS,
			'tab' => 3,
			'help' => _HELP_ANONREVIEWS,
			'type' => 'boolean',
			'readParms' => array("disabled" => _NO, "enabled" => _YES),
			'writeParms' => array("disabled" => _NO, "enabled" => _YES),
			'data' => 'int',
		),
		'revdelete'               => array(
			'title' => _REVDELETE,
			'tab' => 3,
			'help' => _HELP_REVDELETE,
			'type' => 'dropdown',
			'readParms' => ['optArray' => array('2' => _ALLREV, '1' => _ANONREV, '0' => _NONE)],
			'writeParms'
			=> ['optArray' => array('2' => _ALLREV, '1' => _ANONREV, '0' => _NONE)],

			'data' => 'int',
		),
		'ratings'                 => array(
			'title' => _WHATRATINGS,
			'tab' => 3,
			'help' => _HELP_RATINGS,
			'type' => 'dropdown',
			'writeParms'
			=> ['optArray' => array('3' => _LIKES_NUMBER,  '2' => _LIKESYS, '1' => _STARS, '0' => _NONE)],

			'data' => 'int',
		),
		'rateonly'                => array(
			'title' => _ALLOWRATEONLY,
			'tab' => 3,
			'help' => _HELP_SITEKEY,
			'readParms' => array("disabled" => _NO, "enabled" => _YES),
			'writeParms' => array("disabled" => _NO, "enabled" => _YES),
			'type' => 'boolean',
			'data' => 'int'
		),

		'alertson'                => array(
			'title' => _ALERTSON,
			'tab' => 4,
			'help' => _HELP_ALERTSON,
			'readParms' => array("disabled" => _NO, "enabled" => _YES),
			'writeParms' => array("disabled" => _NO, "enabled" => _YES),

			'type' => 'boolean',
			'data' => 'int',
		),
		'disablepopups'           => array(
			'title' => _USERPOPS,
			'tab' => 4,
			'help' => _HELP_POPUPS,
			'readParms' => array("disabled" => _NO, "enabled" => _YES),
			'writeParms' => array("disabled" => _NO, "enabled" => _YES),
			'type' => 'boolean',
			'data' => 'int',
		),
		'agestatement'            => array(
			'title' => _AGESTATEMENT,
			'tab' => 4,
			'help' => _HELP_AGECONSENT,
			'readParms' => array("disabled" => _NO, "enabled" => _YES),
			'writeParms' => array("disabled" => _NO, "enabled" => _YES),
			'type' => 'boolean',
			'data' => 'int',
		),


		'pwdsetting'            => array(
			'title' => _PWDSETTING,
			'tab' => 4,
			'help' => _HELP_PWD,
			'readParms' => array("disabled" => _RANDOM, "enabled" => _SELF),
			'writeParms' => array("disabled" => _RANDOM, "enabled" => _SELF),
			'type' => 'boolean',
			'data' => 'int',
		),
	);


	public function init()
	{

		$defaultdates = array(
			"m/d/y",
			"m/d/Y",
			"m/d/Y",
			"d/m/Y",
			"d/m/y",
			"d M Y",
			"d.m.y",
			"Y.m.d",
			"m.d.Y",
			"d-m-y",
			"m-d-y",
			"M d Y",
			"M d, Y",
			"F d Y",
			"F d, Y"
		);
		$defaulttimes = array("h:i a", "h:i A", "H:i", "g:i a", "g:i A", "G:i", "h:i:s a", "H:i:s", "g:i:s a", "g:i:s A", "G:i:s");

		// $this->prefs['imageheight']['readParms']['pre'] = "<fieldset style='margin: 0 auto;'><legend>" . _IMAGESIZE . " <a href='#' class='pophelp'>[?]<span>" . _HELP_IMAGESIZE . "</span></a></legend>";
		// $this->prefs['imagewidth']['readParms']['post'] = "</fieldset>";		 
		// 	$this->prefs['imageheight']['writeParms']['pre'] = "<fieldset style='margin: 0 auto;'><legend>" . _IMAGESIZE . " <a href='#' class='pophelp'>[?]<span>" . _HELP_IMAGESIZE . "</span></a></legend>";
		// 	$this->prefs['imagewidth']['writeParms']['post'] = "</fieldset>";

		$this->prefs['dateformat']['writeParms']['optArray'] = $defaultdates;
		$this->prefs['timeformat']['writeParms']['optArray'] = $defaulttimes; // Example Drop-down array. 

		$action = $this->getAction();
		$mode = $this->getMode();
		$sect = $_GET['sect'];

		if ($mode == 'submissions' or $sect == 'submissions')
		{
			print_a($_GET);
			$this->tabs = array();
			$this->fields = array();
			$this->fields['submissionsoff'] =  array(
				'tab' => 1,
				'title' => _NOSUBS,
				'help' => _HELP_SUBSOFF,
				'type' => 'boolean',
				'data' => 'int',

			);
		}
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


	// optional - a custom page.  
	public function settingsPage()
	{
		$this->addTitle(_MAINSETTINGS);

		$frm = $this->getUI();

		$text = $frm->open('main', 'post');
		$settings = e107::getEfiction()->getSettings();

		$tab1 = "<table class='table table-bordered adminform'>
                <colgroup>
                    <col class='col-label'>
                    <col class='col-control'>
                </colgroup>
                <tr>
                    <td>" . _SITEKEY . " " . $frm->help(_HELP_SITEKEY) . "</td>
                    <td>" . $frm->text(
			'sitekey',
			$settings['sitekey'],
			80,
			['size' => 'xlarge', 'disabled' => 1]
		) . "</td>
                </tr>
				<tr>
                    <td>" . _SITENAME . " " . $frm->help(_HELP_SITENAME) . "</td>
                    <td>" . $frm->text(
			'sitename',
			$settings['sitename'],
			80,
			['size' => 'xlarge', 'disabled' => 1]
		) . "</td>
                </tr>
				<tr>
                    <td>" . _SITESLOGAN . " " . $frm->help(_HELP_SITENAME) . "</td>
                    <td>" . $frm->text('slogan', $settings['slogan'], 80, ['size' => 'xlarge', 'disabled' => 1]) . "</td>
                </tr>
				<tr>
                    <td>" . _SITEURL . " " . $frm->help(_HELP_URL) . "</td>
                    <td>" . $frm->text('url', $settings['url'], 80, ['size' => 'xlarge', 'disabled' => 1]) . "</td>
                </tr>
 
				<tr>
                    <td>" . _TABLEPREFIX . " " . $frm->help(_HELP_TABLEPREFIX) . "</td>
                    <td>" . $frm->text('tableprefix', $settings['tableprefix'], 80, ['size' => 'xlarge', 'disabled' => 1]) . "</td>
                </tr>
				<tr>
                    <td>" . _ADMINEMAIL . " " . $frm->help(_HELP_SITEEMAIL) . "</td>
                    <td>" . $frm->text('siteemail', $settings['siteemail'], 80, ['size' => 'xlarge', 'disabled' => 1]) . "</td>
                </tr>
			    <tr>
                    <td>" . _DEFAULTSKIN . " " . $frm->help(_HELP_SITESKIN) . "</td>
                    <td>" . $frm->text('skin', $settings['skin'], 80, ['size' => 'xlarge', 'disabled' => 1]) . "</td>
                </tr>
				<tr>
                    <td>" . _LANGUAGE . " " . $frm->help(_HELP_LANGUAGE) . "</td>
                    <td>" . $frm->text('language', $settings['language'], 80, ['size' => 'xlarge', 'disabled' => 1]) . "</td>
                </tr>
            </table>";

		$tab2 = "<table class='table table-bordered adminform'>
                <colgroup>
                    <col class='col-label'>
                    <col class='col-control'>
                </colgroup>
                <tr>
                    <td>" . _SMTPHOST . " " . $frm->help(_HELP_SMTPHOST) . "</td>
                    <td>" . $frm->text(
			'smtp_host',
			$settings['smtp_host'],
			80,
			['size' => 'xlarge', 'disabled' => 1]
		) . "</td>
                </tr>
		                <tr>
                    <td>" . _SMTPUSER . " " . $frm->help(_SMTPUSER) . "</td>
                    <td>" . $frm->text(
			'smtp_username',
			$settings['smtp_username'],
			80,
			['size' => 'xlarge', 'disabled' => 1]
		) . "</td>
                </tr>
				                <tr>
                    <td>" . _SMTPPASS . " " . $frm->help(_HELP_SMTPPWD) . "</td>
                    <td>" . $frm->text(
			'smtp_password',
			$settings['smtp_password'],
			80,
			['size' => 'xlarge', 'disabled' => 1]
		) . "</td>
                </tr>		 
            </table>";

		// Display Tab
		$text .= $frm->tabs([
			'siteinfo' => ['caption' => _SITEINFO, 'text' => $tab1],
			'emailsettings' => ['caption' => _EMAILSETTINGS, 'text' => $tab2],
		]);

		$text .= "<div class='buttons-bar text-center'>" . $frm->button('custom-submit', 'submit', 'submit', LAN_SAVE) . "</div>";
		$text .= $frm->close();

		return $text;
	}
}




class fanfiction_settings_form_ui extends e_admin_form_ui
{
}


new efiction_adminArea();

require_once(e_ADMIN . "auth.php");
e107::getAdminUI()->runPage();

require_once(e_ADMIN . "footer.php");
exit;
