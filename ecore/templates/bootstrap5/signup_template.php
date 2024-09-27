<?php
/*
 * e107 website system
 *
 * Copyright (C) 2008-2016 e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 */

if (!defined('e107_INIT'))
{
	exit;
}

 

$SIGNUP_WRAPPER['SIGNUP_DISPLAYNAME'] =
"<div class='row align-items-center my-2'>
	<label class='col-md-4 col-form-label' for='username'>{LAN=SIGNUP_89}{SIGNUP_IS_MANDATORY=true}</label>
	<div class='col-md-8'>{---}</div>
</div>";

$SIGNUP_WRAPPER['SIGNUP_LOGINNAME'] =
"<div class='row align-items-center my-2'>
	<label class='col-md-4 col-form-label' for='loginname'>{LAN=SIGNUP_81}{SIGNUP_IS_MANDATORY=loginname}</label>
	<div class='col-md-8'>{---}</div>
</div>";

$SIGNUP_WRAPPER['SIGNUP_REALNAME'] =
"<div class='row align-items-center my-2'>
	<label class='col-md-4 col-form-label' for='realname'>{LAN=SIGNUP_91}{SIGNUP_IS_MANDATORY=realname}</label>
	<div class='col-md-8'>{---}</div>
</div>";

$SIGNUP_WRAPPER['SIGNUP_EMAIL'] =
"<div class='row align-items-center my-2'>
	<label class='col-md-4 col-form-label' for='email'>{LAN=LAN_USER_60}{SIGNUP_IS_MANDATORY=email}</label>
	<div class='col-md-8'>{---}</div>
</div>";

$SIGNUP_WRAPPER['SIGNUP_EMAIL_CONFIRM'] =
"<div class='row align-items-center my-2'>
	<label class='col-md-4 col-form-label' for='email-confirm'>{LAN=SIGNUP_39}{SIGNUP_IS_MANDATORY=true}</label>
	<div class='col-md-8'>{---}</div>
</div>";

$SIGNUP_WRAPPER['SIGNUP_HIDE_EMAIL'] =
"<div class='row align-items-center my-2'>
	<label class='col-md-4 col-form-label' for='email-confirm'>{LAN=USER_83</label>
	<div class='col-md-8'>{---}</div>
</div>";

$SIGNUP_WRAPPER['SIGNUP_PASSWORD1'] =
"<div class='row align-items-center my-2'>
	<label class='col-md-4 col-form-label' for='password1'>{LAN=SIGNUP_83}{SIGNUP_IS_MANDATORY=true}</label>
	<div class='col-md-8'>{---}</div>
</div>";

$SIGNUP_WRAPPER['SIGNUP_PASSWORD2'] =
"<div class='row align-items-center my-2'>
	<label class='col-md-4 col-form-label' for='password2'>{LAN=SIGNUP_84}{SIGNUP_IS_MANDATORY=true}</label>
	<div class='col-md-8'>{---}</div>
</div>";

$SIGNUP_WRAPPER['SIGNUP_USERCLASS_SUBSCRIBE'] =
"<div class='row align-items-center my-2'>
	<label class='col-md-4 col-form-label' for='password2'>{LAN=SIGNUP_113}{SIGNUP_IS_MANDATORY=subscribe}</label>
	<div class='col-md-8'>{---}</div>
</div>";

$SIGNUP_WRAPPER['SIGNUP_GDPR_INFO'] =
"<div class='form-text'>{---}</div> ";
 
$SIGNUP_WRAPPER['SIGNUP_IMAGES'] =
"<div class='row align-items-center my-2'>
	<label class='col-md-4 col-form-label' for='avatar'>{LAN=SIGNUP_94}{SIGNUP_IS_MANDATORY=avatar}</label>
	<div class='col-md-8'>{---}</div>
</div>";

$SIGNUP_WRAPPER['SIGNUP_IMAGECODE'] =
"<div class='row align-items-center my-2'>
	<label class='col-md-4 col-form-label' for='code-verify'>" . e107::getSecureImg()->renderLabel() . "{SIGNUP_IS_MANDATORY=true}</label>
	<div class='col-md-8'>{---}</div>
</div>";
 
$SIGNUP_WRAPPER['SIGNUP_GDPR_INFO'] = 
"<div class='form-text'>{---}</div> ";

$SIGNUP_WRAPPER['SIGNUP_SIGNATURE'] =
"<div class='row align-items-center my-2'>
	<label class='col-md-4 col-form-label' for='signature'>{LAN=SIGNUP_93}{SIGNUP_IS_MANDATORY=signature}</label>
	<div class='col-md-8'>{---}</div>
</div>";
  
$SIGNUP_TEMPLATE['start'] = "
	{SIGNUP_FORM_OPEN} {SIGNUP_ADMINOPTIONS} {SIGNUP_SIGNUP_TEXT}";


$SIGNUP_TEMPLATE['body'] = "
 
	<div id='default'>
 
		{SIGNUP_DISPLAYNAME}
		{SIGNUP_LOGINNAME}
		{SIGNUP_REALNAME}
		{SIGNUP_EMAIL}
		{SIGNUP_EMAIL_CONFIRM}
		{SIGNUP_PASSWORD1}
		{SIGNUP_PASSWORD2}
		{SIGNUP_HIDE_EMAIL}
		{SIGNUP_USERCLASS_SUBSCRIBE}
		{SIGNUP_EXTENDED_USER_FIELDS}
		{SIGNUP_SIGNATURE}
		{SIGNUP_IMAGES}
		{SIGNUP_IMAGECODE}
		{SIGNUP_GDPR_INFO}
		<div class='row g-3 my-3 align-items-center  w-50 m-auto'>
			{SIGNUP_BUTTON}
		</div>	 
	</div>
	{SIGNUP_FORM_CLOSE}";

$SIGNUP_TEMPLATE['end']                     = '';

$SIGNUP_TEMPLATE['coppa']                   = "{SIGNUP_COPPA_TEXT}<br /><br />
											<div style='text-align:center'><b>{LAN=LAN_SIGNUP_17}</b>
												{SIGNUP_COPPA_FORM}
											</div>";

$SIGNUP_TEMPLATE['coppa-fail']              = "<div class='alert alert-danger alert-block' style='text-align:center'>{LAN=LAN_SIGNUP_9}</div>";
 
 
 
$SIGNUP_TEMPLATE['extended-category'] =
"<div class='row align-items-center my-2'>
	<label class='col-md-4 col-form-label'>&nbsp;</label>
	<div class='col-md-8'>{EXTENDED_CAT_TEXT}</div>
</div>";

$SIGNUP_TEMPLATE['extended-user-fields'] =
"<div class='row align-items-center my-2'>
	<label class='col-md-4 col-form-label'>{EXTENDED_USER_FIELD_TEXT}{EXTENDED_USER_FIELD_REQUIRED}</label>
	<div class='col-md-8'>{EXTENDED_USER_FIELD_EDIT}</div>
</div>";