<?php
// ----------------------------------------------------------------------
// eFiction 3.2
// Copyright (c) 2007 by Tammy Keefer
// Valid HTML 4.01 Transitional
// Based on eFiction 1.1
// Copyright (C) 2003 by Rebecca Smallwood.
// http://efiction.sourceforge.net/
// ----------------------------------------------------------------------
// LICENSE
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License (GPL)
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------

if(!defined("_CHARSET")) exit( );

 

if($action == "settings") {
$output .= "<h1>"._SETTINGS."</h1><div style='text-align: center;'>
	<a href='admin.php?action=settings&amp;sect=main'>"._MAINSETTINGS."</a> |
 
	<a href='admin.php?action=settings&amp;sect=sitesettings'>"._SITESETTINGS."</a> |
	<a href='admin.php?action=settings&amp;sect=display'>"._DISPLAYSETTINGS."</a> |
	<a href='admin.php?action=settings&amp;sect=reviews'>"._REVIEWSETTINGS."</a> |
	<a href='admin.php?action=settings&amp;sect=useropts'>"._USERSETTINGS."</a> |
	<a href='admin.php?action=settings&amp;sect=email'>"._EMAILSETTINGS."</a> |
	<a href='admin.php?action=censor'>"._CENSOR."</a> <br />
	<a href='admin.php?action=messages&message=welcome'>"._WELCOME."</a> | 
	<a href='admin.php?action=messages&message=copyright'>"._COPYRIGHT."</a> | 
	<a href='admin.php?action=messages&message=printercopyright'>"._PRINTERCOPYRIGHT."</a> | 
	<a href='admin.php?action=messages&message=tinyMCE'>"._TINYMCE."</a> | 
	<a href='admin.php?action=messages&message=nothankyou'>"._NOTHANKYOU."</a> | 
	<a href='admin.php?action=messages&message=thankyou'>"._THANKYOU."</a><br />";
	$settingsquery = dbquery("SELECT * FROM ".TABLEPREFIX."fanfiction_panels WHERE panel_type = 'AS' ORDER BY panel_title");
	while($os = dbassoc($settingsquery)) {
		if($os['panel_url']) $othersettings[] = "<a href='".$os['panel_url']."'>".$os['panel_title']."</a>";
	}
	if(isset($othersettings)) $output .= implode(" | ", $othersettings);
$output .= "</div> ";
}	

$sects = array("main", "submissions", "sitesettings", "display", "reviews", "useropts", "email");
//if(!isset($_GET['sect'])) $sect = "main";
//else $sect = $_GET['sect'];
$sect = isset($_GET['sect']) ? $_GET['sect'] : "main";
if(isset($_POST['submit'])) {
	if($sect == "main") {
		if(!preg_match("!^[a-z0-9_]{3,30}$!i", $_POST['newsitekey'])) $output .= write_error(_BADSITEKEY);
		else {
			$oldsitekey = $sitekey;
			$sitekey = descript($_POST['newsitekey']);
			$sitename = escapestring(descript(strip_tags($_POST['newsitename'])));
			$slogan = escapestring(descript(strip_tags($_POST['newslogan'])));
			$url = escapestring(descript(strip_tags($_POST['newsiteurl'])));
			// if the https:// is missing add it.
			if(substr($url, 0, 8) != "https://" && substr($url, 0, 7) != "http://") $url = "https://".$url;
			// we also want to check for a trailing slash.
			if(substr($url, -1, 1) == "/") $url = substr($url, 0, strlen($url) - 1);
			$tableprefix = escapestring(descript(strip_tags($_POST['newtableprefix'])));
			$siteemail = escapestring(descript(strip_tags($_POST['newsiteemail'])));
			$skin = escapestring(descript(strip_tags($_POST['newskin'])));
			$language = escapestring(descript(strip_tags($_POST['newlanguage'])));
			if(empty($sitekey)) $output .= write_message(_SITEKEYREQUIRED);
			else {
				if($sitekey != $oldsitekey) $output .= write_message(_SITEKEYCHANGED);
				$result = dbquery("UPDATE ".$settingsprefix."fanfiction_settings SET sitekey = '$sitekey', sitename = '$sitename', slogan = '$slogan', url = '$url', tableprefix = '$tableprefix', siteemail = '$siteemail', skin = '$skin', language = '$language' WHERE sitekey = '$oldsitekey'");
			}
		}
	}
	else if($sect == "submissions") {
		$submissionsoff = $_POST['newsubmissionsoff'] == 1 ? 1 : 0;
		$autovalidate = $_POST['newautovalidate'] == 1 ? 1 : 0;
		$coauthallowed = $_POST['newcoauthallowed'] == 1 ? 1 : 0;
		$store = !empty($_POST['newstore']) ? ($_POST['newstore'] == "files" ? "files" : "mysql" ) : $store;
		$storiespath = descript($_POST['newstoriespath']);
		$minwords = isNumber($_POST['newminwords']) ? $_POST['newminwords'] : 0;
		$maxwords = isNumber($_POST['newmaxwords']) ? $_POST['newmaxwords'] : 0;
		$roundrobins = $_POST['newroundrobins'] == 1 ? 1 : 0;
		$allowseries = $_POST['newallowseries'] && isNumber($_POST['newallowseries']) ? $_POST['newallowseries'] : 0;
		$imageupload = $_POST['newimageupload'] == 1 ? 1 : 0;
		$imageheight = isNumber($_POST['newimageheight']) ? $_POST['newimageheight'] : 0;
		$imagewidth = isNumber($_POST['newimagewidth']) ? $_POST['newimagewidth'] : 0;
		$result = dbquery("UPDATE ".$settingsprefix."fanfiction_settings SET submissionsoff = '$submissionsoff', autovalidate = '$autovalidate', coauthallowed = '$coauthallowed', store = '$store', storiespath = '$storiespath', minwords = '$minwords', maxwords = '$maxwords', imageupload = '$imageupload', imageheight = '$imageheight', imagewidth = '$imagewidth', roundrobins = '$roundrobins', allowseries = '$allowseries' WHERE sitekey ='".SITEKEY."'");
		if($action == "settings") {
			dbquery("UPDATE ".TABLEPREFIX."fanfiction_panels SET panel_hidden = '".($imageupload ? "0" : "1")."' WHERE panel_name LIKE 'manageimages'");
			dbquery("UPDATE ".TABLEPREFIX."fanfiction_panels SET panel_hidden = '".($allowseries ? "0" : "1")."' WHERE (panel_name LIKE '%series%' OR panel_title LIKE '%series%') AND panel_type != 'A' AND panel_type != 'B'");
			updatePanelOrder( );
		}

	}
	else if($sect == "sitesettings") {
		$tinyMCE = ($_POST['newtinyMCE'] == 1  OR $_POST['newtinyMCE'] == 2) ? $_POST['newtinyMCE'] : 0;
		$allowed_tags = $_POST['newallowed_tags'];
		$favorites = $_POST['newfavorites'] == 1 ? 1 : 0;
		$multiplecats = $_POST['newmultiplecats'] == 1 ? 1 : 0;
		$newscomments = $_POST['newnewscomments'] == 1 ? 1 : 0;
		$logging = $_POST['newlogging'] == 1 ? 1 : 0;
		$maintenance = $_POST['newmaint'] == 1 ? 1 : 0;
		$debug = $_POST['newdebug'] == 1 ? 1 : 0;
		$captcha = $_POST['newcaptcha'] == 1 ? 1 : 0;
		$result = dbquery("UPDATE ".$settingsprefix."fanfiction_settings SET tinyMCE = '$tinyMCE', favorites = '$favorites', multiplecats = '$multiplecats', allowed_tags = '$allowed_tags', newscomments = '$newscomments', logging = '$logging', maintenance = '$maintenance', debug = '$debug', captcha = '$captcha' WHERE sitekey ='".SITEKEY."'");
	}
	else if($sect == "display") {
		$dateformat = $_POST['newdateformat'] ? descript(strip_tags($_POST['newdateformat'])) : descript(strip_tags($_POST['customdateformat']));
		$timeformat = $_POST['newtimeformat'] ? descript(strip_tags($_POST['newtimeformat'])) : descript(strip_tags($_POST['customtimeformat']));
		$extendcats = $_POST['newextendcats'] == 1 ? 1 : 0;
		if(isset($_POST['newdisplaycolumns']) && isNumber($_POST['newdisplaycolumns'])) $displaycolumns = $_POST['newdisplaycolumns'];
		if(isset($_POST['newitemsperpage']) && isNumber($_POST['newitemsperpage'])) $itemsperpage = $_POST['newitemsperpage'];
		if(isset($_POST['newlinkstyle']) && isNumber($_POST['newlinkstyle'])) $linkstyle = $_POST['newlinkstyle'];
		if(isset($_POST['newlinkrange']) && isNumber($_POST['newlinkrange'])) $linkrange = $_POST['newlinkrange'];
		$displayindex = $_POST['newstoryindex'] == 1 ? 1 : 0;
		$displayprofile = $_POST['newdisplayprofile'] == 1 ? 1 : 0;
		$defaultsort = $_POST['newdefaultsort'] == 1 ? 1 : 0;
		if(isNumber($_POST['newrecentdays'])) $recentdays = $_POST['newrecentdays'];
		$result = dbquery("UPDATE ".$settingsprefix."fanfiction_settings SET dateformat = '$dateformat', timeformat = '$timeformat', extendcats = '$extendcats', displaycolumns = '$displaycolumns', itemsperpage = '$itemsperpage', displayindex = '$displayindex', defaultsort = '$defaultsort', recentdays = '$recentdays', displayprofile = '$displayprofile', linkstyle = '$linkstyle', linkrange = '$linkrange' WHERE sitekey ='".SITEKEY."'");
	}
	else if($sect == "reviews") {
		$reviewsallowed = $_POST['newreviewsallowed'] == 1 ? 1 : 0;
		$anonreviews = $_POST['newanonreviews'] == 1 ? 1 : 0;
		$rateonly = $_POST['newrateonly'] == 1 ? 1 : 0;
		$ratings = isset($_POST['newratings']) && isNumber($_POST['newratings']) ? $_POST['newratings'] : 0;
		$revdelete = isset($_POST['newrevdelete']) && isNumber($_POST['newrevdelete']) ? $_POST['newrevdelete'] : 0;
		$result = dbquery("UPDATE ".$settingsprefix."fanfiction_settings SET reviewsallowed = '$reviewsallowed', anonreviews = '$anonreviews', rateonly = '$rateonly', ratings = '$ratings', revdelete = '$revdelete' WHERE sitekey ='".SITEKEY."'");
	}
	else if($sect == "useropts") {
		$alertson = $_POST['newalertson'] == 1 ? 1 : 0;
		$disablepopups = $_POST['newdisablepops'] == 1 ? 1 : 0;
		$agestatement  = $_POST['newagestatement'] == 1 ? 1 : 0;
		$pwdsetting = $_POST['newpwdsetting'] == 1 ? 1 : 0;
		$result = dbquery("UPDATE ".$settingsprefix."fanfiction_settings SET alertson = '$alertson', disablepopups = '$disablepopups', agestatement = '$agestatement', pwdsetting = '$pwdsetting' WHERE sitekey ='".SITEKEY."'");
	}
	else if($sect == "email") {
		$smtp_host = $_POST['newsmtp_host'];
		$smtp_username = $_POST['newsmtp_username'];
		$smtp_password = $_POST['newsmtp_password'];
		$result = dbquery("UPDATE ".$settingsprefix."fanfiction_settings SET smtp_host = '$smtp_host', smtp_username = '$smtp_username', smtp_password = '$smtp_password' WHERE sitekey ='".SITEKEY."'");
	}
	if($result) {
		$output .= write_message(_ACTIONSUCCESSFUL);
		$sect = $sects[(array_search($sect, $sects) + 1)];
		if(!$sect) $sect = $sects[0];
	}
	else $output .= write_error(_ERROR);
}
	$settingsresults = dbquery("SELECT * FROM ".$settingsprefix."fanfiction_settings WHERE sitekey ='".SITEKEY."'");
	$settings = dbassoc($settingsresults);
 
	foreach($settings as $var => $val) {
		if(is_NULL($val)) $val = '';
		$$var = stripslashes($val );
	}

 
?>
