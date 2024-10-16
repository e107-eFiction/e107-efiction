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

// Wrapper for contact information elements
$CONTACT_INFO_WRAPPER['default'] = [
	'CONTACT_INFO' => "<div>{---}</div>",
	'CONTACT_INFO: type=organization' => "<h4>{---}</h4>",
	'CONTACT_INFO: type=message' => "<p>{---}</p>",
	'CONTACT_INFO: type=address' => "<address>{GLYPH=fa-map-marker} {---}</address>",
	'CONTACT_INFO: type=email1' => "<div>{GLYPH=fa-envelope} {---}</div>",
	'CONTACT_INFO: type=email2' => "<div>{GLYPH=fa-envelope} {---}</div>",
	'CONTACT_INFO: type=phone1' => "<div>{GLYPH=fas-phone} {---}</div>",
	'CONTACT_INFO: type=phone2' => "<div>{GLYPH=fas-phone} {---}</div>",
	'CONTACT_INFO: type=phone3' => "<div>{GLYPH=fas-phone} {---}</div>",
	'CONTACT_INFO: type=fax' => "<div>{GLYPH=fa-fax} {---}</div>",
	'CONTACT_INFO: type=hours' => "<div>{GLYPH=fa-clock} {---}</div>"
];

// Template for contact information display
$CONTACT_INFO_TEMPLATE['default'] = "
    <div id='contactInfo'>
        <!-- Backward Compatibility for Contact Info -->
        {SITECONTACTINFO}

        <!-- New Contact Info -->
        {CONTACT_INFO: type=organization}
        {CONTACT_INFO: type=message}

        <div class='row'>
            <div class='col-md-6 col-lg-4'>
                {CONTACT_INFO: type=address}

                <div class='form-group'>
                    {CONTACT_INFO: type=phone1}
                    {CONTACT_INFO: type=phone2}
                    {CONTACT_INFO: type=phone3}
                    {CONTACT_INFO: type=fax}
                </div>

                {CONTACT_INFO: type=email1}
                {CONTACT_INFO: type=email2}
                <br />
                {CONTACT_INFO: type=hours}
            </div>

            <div class='col-md-6 col-lg-8'>
                {CONTACT_MAP: zoom=city}
            </div>
        </div>
    </div>
";

 