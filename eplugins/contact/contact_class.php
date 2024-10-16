<?php

/*
 * e107 website system
 *
 * Copyright (C) 2008-2025 e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 * #######################################
 * #     e107 contact plugin    		 #
 * #     by Jimako                       #
 * #     https://www.e107sk.com          #
 * #######################################
 */


class Contact
{


	/**
	 * Plugin Preferences
	 * contact::prefs['xy']; 
	 */

	private static $contactPrefs = [];

	/**
	 * Class construcotr
	 */
	function __construct()
	{

		$this->setProperties();
	}


	/**
	 * Define the required plugin properties
	 *
	 * @return void
	 */
	public function setProperties()
	{
		$contactPrefs =  e107::pref('contact');

		if (empty($contactPrefs['contact_pages'])  )
		{
			e107::redirect(e107::url('home', ''));
		}
		else
		{
			$contactPrefs['contact_pages'] = e107::unserialize($contactPrefs['contact_pages']);
		}
 
		self::$contactPrefs  = $contactPrefs;
 
	}

	public function setContactLayouts($page = "contact")
	{
		// Check if the specified page layout exists and is greater than zero
		$layoutId = self::$contactPrefs['contact_pages']['themelayout'][$page] ?? null;

		if ($layoutId > 0)
		{
			// Define the layout constant if a valid layout ID is found
			define('THEME_LAYOUT', $layoutId);
		}
	}

	public function setRedirection($page = "contact")
	{
		// Check if the specified page key exists
		if (
			!array_key_exists($page, self::$contactPrefs['contact_pages']['activate'])
			|| self::$contactPrefs['contact_pages']['activate'][$page] <= 0
		)
		{
			// Redirect to the homepage if the page is not activated or doesn't exist
			e107::redirect(); // homepage
		}
	}


	public function renderContactForm($page = "contact")
	{

		// check if form is allowed to render, don't display form without owner control   
		if (!array_key_exists($page, self::$contactPrefs['contact_pages']['contactform']))
		{
			return '';
		}
		$active = self::$contactPrefs['contact_pages']['contactform'][$page];

		if (!check_class($active))
		{
			if ($active == e_UC_MEMBER)
			{
				//render message
				return  $this->renderSignupRequired();
			}
		}
		else
		{
			$CONTACT_FORM = e107::getTemplate('contact', $page, 'form');
			$contact_shortcodes = e107::getScBatch('form', 'contact', false);
			$contact_shortcodes->wrapper($page . '/form');

			$text = e107::getParser()->parseTemplate($CONTACT_FORM, true, $contact_shortcodes);

			if (trim($text) !== '')
			{
				return e107::getRender()->tablerender(LAN_CONTACT_02, $text, "contact-form", true);
			}
		}
	}


	public function renderContactInfo($page = "contact")
	{

		// check if form is allowed to render, don't display form without owner control  

		if (!array_key_exists($page, self::$contactPrefs['contact_pages']['contactinfo']))
		{
			return '';
		}
		$active = self::$contactPrefs['contact_pages']['contactform'][$page];

		if (!check_class($active))
		{
			if ($active == e_UC_MEMBER)
			{
				//render message
				return  $this->renderSignupRequired();
			}
		}
		else
		{
			$CONTACT_INFO = e107::getTemplate('contact', 'contact_info', 'default');
 
			$contact_shortcodes = e107::getScBatch('form', 'contact', false);
			$contact_shortcodes->wrapper('contact/info');

			$text = e107::getParser()->parseTemplate($CONTACT_INFO, true, $contact_shortcodes);

			return e107::getRender()->tablerender(LAN_CONTACT_01, $text, "contact-info", true);
		}
	}


	public function renderSignupRequired()
	{

		$srch = array("[", "]");
		$repl = array("<a class='alert-link' href='" . e_SIGNUP . "'>", "</a>");
		$message = LAN_CONTACT_16; // "You must be [registered] and signed-in to use this form.";

		$text = e107::getRender()->tablerender(LAN_CONTACT_02, "<div class='alert alert-info'>" . str_replace($srch, $repl, $message) . "</div>", "contact", true);
		return $text;
	}

	public function getPageLayout($page = "contact")
	{
		$LAYOUT = '{---CONTACT-INFO---} {---CONTACT-FORM---}  ';
		$layout_key = self::$contactPrefs['contact_pages']['contactlayout'][$page];
		$layout_key = varset($layout_key, 'default');
		$LAYOUT = e107::getTemplate('contact', 'contact_layout', $layout_key);

		return $LAYOUT;
	}
}
