<?php
/*
* e107 website system
*
* Copyright (C) 2008-2013 e107 Inc (e107.org)
* Released under the terms and conditions of the
* GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
*
* Custom install/uninstall/update routines for efiction plugin
**
*/


if(!class_exists("efiction_setup"))
{
	class efiction_setup
	{

	    function install_pre($var)
		{
			// print_a($var);
			// echo "custom install 'pre' function<br /><br />";
		}

		/**
		 * For inserting default database content during install after table has been created by the efiction_sql.php file.
		 */
		function install_post($var)
		{
			$sql = e107::getDb();
			$mes = e107::getMessage();
			$sitekey = e107::getInstance()->getSitePath();
			$settingsresults = e107::getDb()->retrieve("SELECT * FROM #fanfiction_settings WHERE sitekey = '" . $sitekey . "'" );

			//if table exists, fill preferences
			if($settingsresults) {
 

			}
			die;

		}

		function uninstall_options()
		{

			 
			 
		}

 
		function uninstall_post($var)
		{
			// print_a($var);
		}


		/*
		 * Call During Upgrade Check. May be used to check for existance of tables etc and if not found return TRUE to call for an upgrade.
		 *
		 * @return bool true = upgrade required; false = upgrade not required
		 */
		function upgrade_required()
		{

			$sitekey = e107::getInstance()->getSitePath();
			$settingsresults = e107::getDb()->gen("SELECT * FROM #fanfiction_settings WHERE sitekey = '" . $sitekey . "'");

			if($settingsresults  == 0) return true;
 

			return false;
		}


		function upgrade_post($var)
		{
		     $sql = e107::getDb();

			$sitekey = e107::getInstance()->getSitePath();
			$settingsresults = $sql->gen("SELECT * FROM #fanfiction_settings WHERE sitekey = '" . $sitekey . "'");

			if($settingsresults  == 0) {
				$query = "INSERT INTO #fanfiction_settings (`sitekey`) VALUES('" . $sitekey . "')";
				$sql->gen($query);


			}
		}

	}

}
