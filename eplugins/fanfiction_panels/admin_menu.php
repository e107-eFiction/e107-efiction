<?php
/*
+ -----------------------------------------------------------------+
| e107: efiction plugin                                            |
| ===========================                                      |
|                                                                  |
| Copyright (c) 2020 Jimako                               				 |
| https://www.e107sk.com/                                          |
|                                                                  |
|   |
+------------------------------------------------------------------+
					 Inspired by
// ----------------------------------------------------------------------
// Copyright (c) 2007 by Tammy Keefer
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


*/
// Ensure this program is loaded in admin theme before calling class2
$eplug_admin = true;
 
require_once("init.php");      
require_once("header.php");
                            
if(isset($_GET['action']) && ($_GET['action'] == "categories" || $_GET['action'] == "admins")) $displayform = 1;
if(isset($_GET['action'])) $action = strip_tags($_GET['action']);
else $action = false;

	$panelquery =  "SELECT * FROM ".TABLEPREFIX."fanfiction_panels WHERE panel_hidden != '1' AND panel_type = 'A' AND panel_level >= ".uLEVEL." ORDER BY panel_level DESC, panel_order ASC, panel_title ASC";
	if(!e107::getDb()->rowCount($panelquery)) $output .= _FATALERROR;
	$panelresult =  e107::getDb()->retrieve($panelquery, true);      
	foreach($panelresult AS $panel)  {
	  
		if(!$panel['panel_url']) {
		  $panellist[$panel['panel_level']][]= "<a href=\"admin_main.php?action=".$panel['panel_name']."\">".$panel['panel_title']."</a>";
		  $panellist[$panel['panel_level']]['var'][$panel['panel_name']]['text'] = $panel['panel_title'];
		  $panellist[$panel['panel_level']]['var'][$panel['panel_name']]['link'] = _BASEDIR."admin_main.php?action=".$panel['panel_name'];
 
		}
		else {
		 $panellist[$panel['panel_level']][] = "<a href=\"".$panel['panel_url']."\">".$panel['panel_title']."</a>";
		 
		 $panellist[$panel['panel_level']]['var'][$panel['panel_name']]['text'] = $panel['panel_title'];
		 $panellist[$panel['panel_level']]['var'][$panel['panel_name']]['link'] = $panel['panel_url'];
		}
		
	}
 
	foreach($panellist as $accesslevel => $row) {
 
		$output .= implode(" | ", $row)."<br />";
		$var = $row['var'];
	 	show_admin_menu('Admin Panel [Level '.$accesslevel.']' , $action, $var);
	}	
  
  
	
 