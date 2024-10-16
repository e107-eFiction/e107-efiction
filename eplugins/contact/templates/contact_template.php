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

if (!defined('e107_INIT'))
{
	exit;
}

 
// Shortcode wrappers
$CONTACT_WRAPPER['form'] = [
    'CONTACT_IMAGECODE'        => "<div class='control-group form-group'><label for='code-verify'>{CONTACT_IMAGECODE_LABEL}</label> {---}",
    'CONTACT_IMAGECODE_INPUT'  => "<span class='m-2'>{---}</span></div>",
    'CONTACT_EMAIL_COPY'       => "<div class='control-group form-group'>{---}{LAN=CONTACT_07}</div>",
    'CONTACT_PERSON'           => "<div class='control-group form-group'><label class='form-label' for='contactPerson'>{LAN=CONTACT_14}</label>{---}</div>"
];

// Contact form template
$CONTACT_TEMPLATE['form'] = "
    <form action='" . e_SELF . "' method='post' id='contactForm' class='mt-5'>
    
        {CONTACT_PERSON}
        
        <div class='control-group form-group mb-2'>
            <label class='form-label' for='contactName'>{LAN=CONTACT_03}</label>
            {CONTACT_NAME}
        </div>
        
        <div class='control-group form-group mb-2'>
            <label class='form-label' for='contactEmail'>{LAN=CONTACT_04}</label>
            {CONTACT_EMAIL}
        </div>
        
        <div class='control-group form-group mb-2'>
            <label class='form-label' for='contactSubject'>{LAN=CONTACT_05}</label>
            {CONTACT_SUBJECT}
        </div>

        {CONTACT_EMAIL_COPY}

        <div class='control-group form-group mb-2'>
            <label class='form-label' for='contactBody'>{LAN=CONTACT_06}</label>
            {CONTACT_BODY}
        </div>

        {CONTACT_IMAGECODE}
        {CONTACT_IMAGECODE_INPUT}

        <div class='form-group mb-2'>
            {CONTACT_GDPR_CHECK}
            {CONTACT_GDPR_LINK}
        </div>

        <div class='form-group mb-2'>
            {CONTACT_SUBMIT_BUTTON}
        </div>
    
    </form>
";

// Customize the email subject
$CONTACT_TEMPLATE['email']['subject'] = "{CONTACT_SUBJECT}";
