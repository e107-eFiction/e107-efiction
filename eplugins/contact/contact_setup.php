<?php
/*
 * e107 website system
 *
 * Copyright (C) 2008-2025 e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 * #######################################
 * #     e107 contact plugin             #
 * #     by Jimako                       #
 * #     https://www.e107sk.com          #
 * #######################################
 */

if (!class_exists("contact_setup"))
{
	class contact_setup
	{
		/**
		 * For inserting default database content during install after the table has been created by the blank_sql.php file.
		 */
		public function install_post($var)
		{
			$sql = e107::getDb();

			// Set default preferences for contact pages
			$contactPrefs = [];
			$contactPrefs['contact_pages'] = [
				'activate' => [
					'contact' => 1,
					'report' => 1
				],
				'contactform' => [
					'contact' => 253,
					'report' => 253
				],
				'contactinfo' => [
					'contact' => 255,
					'report' => 255
				]
			];

			// Serialize the contact pages array
			$contactPrefs['contact_pages'] = e107::serialize($contactPrefs['contact_pages']);

			// Get contact information from the core preferences
			$contactInfo = e107::getPref('contact_info');
			$contactPrefs['contact_email1'] = $contactInfo['email1'];
			$contactPrefs['contact_email2'] = $contactInfo['email2'];

			// Get the config handler for the 'contact' plugin
			$config = e107::getConfig('contact');

			// Set and save the updated preferences
			$config->setPref($contactPrefs);
			if ($config->save())
			{
				e107::getMessage()->addSuccess("Default Preferences saved successfully.");
			}
			else
			{
				$message = $sql->getLastErrorText();
				e107::getMessage()->addError("Failed to save default preferences. " . $message);
			}
		}
	}
}
