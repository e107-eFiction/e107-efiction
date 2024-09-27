<?php
if (!defined('e107_INIT')) { exit; }


class fanfiction_panels_dashboard // include plugin-folder in the name.
{
	var $title = "eFiction Admin Area" ; // dynamic title.
 
	function adminarea()
	{
		$this->title = "eFiction Admin Area";  //it is not working

		$paneldata = e107::getEfiction()->getUserPanels( "A", 0, uLEVEL);  
 
		$output= "<div class='text-center'>";
		foreach($paneldata AS $panel)
		{
			if (!$panel['panel_url']) $panellist[$panel['panel_level']][] = "<a href=\"admin.php?action=" . $panel['panel_name'] . "\">" . $panel['panel_title'] . "</a>";
			else $panellist[$panel['panel_level']][] = "<a href=\"" . $panel['panel_url'] . "\">" . $panel['panel_title'] . "</a>";
		}
		foreach ($panellist as $accesslevel => $row)
		{
			$output .= implode(" | ", $row) . "<br />";
		}
		$output .=  '</div>';
		$text = $output;
		 
		return $text;
	}
 
}
