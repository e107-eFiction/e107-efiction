<?php
/*
 * e107 website system
 *
 * Copyright (C) 2008-2013 e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 * Forum upgrade routines
 *
 */

if (!defined('e_ADMIN_AREA'))
{
    define('e_ADMIN_AREA', true);
}
require_once(__DIR__ . '/../../class2.php');

if (!getperms('0'))
{
    e107::redirect();
    exit;
}

e107::lan('efiction', "en");
e107::lan('efiction', "en_admin");
require_once(e_ADMIN . 'auth.php');

if (e_QUERY == "reset")
{
    unset($_SESSION['efictionUpgrade']);
    unset($_SESSION['efictionupdate']);
}


$timestart = microtime();

global $f;
$f = new efictionUpgrade;

$sql = e107::getDb();

if (!empty($_GET['reset']))
{
    unset($_SESSION['efictionUpgrade']);
    unset($_SESSION['efictionupdate']);
    $f->updateInfo['currentStep'] = intval($_GET['reset']);
    $f->setUpdateInfo();
}


if (e_AJAX_REQUEST)
{
    if (!vartrue($_GET['mode']))
    {
        echo "data-progress-mode not set!";
        exit;
    }

    $func = 'step' . intval($_GET['mode']) . "_ajax";

    if (function_exists($func))
    {
        call_user_func($func);
    }
    else
    {
        echo $func . "() doesn't exist!";
    }

    exit;
}

$upgradeNeeded = $f->checkUpdateNeeded();
$upgradeNeeded = true;

if (!$upgradeNeeded)
{
    $mes = e107::getMessage();

    $mes->addInfo("The efiction is already at the most recent version, no upgrade is required");
    $ns->tablerender('Efiction Upgrade', $mes->render());
    require(e_ADMIN . 'footer.php');
    exit;
}
 
if (isset($_GET) && isset($_GET['step'])) 
{
    $step  =  $_GET['step'];
 
    $f->updateInfo['currentStep'] =
    $step;
    $f->setUpdateInfo();
}

if (isset($_POST) && count($_POST))
{
    if (isset($_POST['skip_attach']))
    {
        $f->updateInfo['skip_attach'] = 1;
        $f->updateInfo['currentStep'] = 2;
        $f->setUpdateInfo();
    }

    if (isset($_POST['nextStep']))
    {
        $tmp = array_keys($_POST['nextStep']);
        $f->updateInfo['currentStep'] = $tmp[0];
        $f->setUpdateInfo();
    }
}



$currentStep = (isset($f->updateInfo['currentStep']) ? $f->updateInfo['currentStep'] : 1);
$stepParms = (isset($stepParms) ? $stepParms : '');

//echo "currentStep = $currentStep <br />";
$func = 'step' . $currentStep;
if (function_exists($func))
{
    $result = call_user_func($func, $stepParms);
}
else
{
    echo $func . "() doesn't exist!";
}

require(e_ADMIN . 'footer.php');


class efictionUpgrade
{

    private $logf;
    public $updateInfo = array();


    public function __construct()
    {
        $this->logf = e_LOG . 'efiction_upgrade.log';
    }

    public function checkUpdateNeeded()
    {

        return true;
        //	include_once(e_PLUGIN.'forum/forum_update_check.php');
        //	$needed = update_forum_08('check');
        //	return !$needed;
    }

    function getUpdateInfo()
    {

        $sql = e107::getDb();

        if (isset($_SESSION['efictionUpgrade']))
        {
            $this->updateInfo = $_SESSION['efictionUpgrade'];
        }
        else
        {
            $this->updateInfo = array();
        }

        return;
    }

    function setUpdateInfo()
    {

        $_SESSION['efictionUpgrade'] = $this->updateInfo;

        return;
    }


    public function log($msg, $append = true)
    {

        //		echo "logf = ".$this->logf."<br />";
        $txt = sprintf("%s - %s\n", date('m/d/Y H:i:s'), $msg);
        //		echo $txt."<br />";
        $flag = ($append ? FILE_APPEND : 0);
        file_put_contents($this->logf, $txt, $flag);
    }
}

function step1()
{
    $mes = e107::getMessage();
    $ns = e107::getRender();

    $stepCaption = 'Step 1: Some settings from fanfiction_settings table are now core site preferences ';

    $text = "
    This step only compare values from your fanfiction_settings table with preferences for this installation <br /> 
    <br />
    You should set those prefences in the related area of e107 Settings
    <br /> 
    Be sure that hash key in e107_config file is the same as your sitekey in fanfiction_settings table.
    <br /> <br /> 

    ";

    /** Display fanficton_settings to plugin efiction prefs  **/
    $sitekey = e107::getInstance()->getSitePath();
    $settingsresults = e107::getDb()->retrieve("SELECT * FROM #fanfiction_settings WHERE sitekey = '" . $sitekey . "'");

    //CORE SETTINGS
    $settingsCore = array();
    $settingsCore =  e107::getEfiction(true)->replace_core_settings($settingsCore);

    //if table exists, fill preferences
    if ($settingsCore)
    {

        // Start HTML table
        $text .= "<table class='table table-bordered adminform'>";
        $text .= "<tr><th>Key</th><th>Related Core e107 Value</th><th>Fanfiction Settings</th></tr>";

        foreach ($settingsCore as $key => $firstValue)
        {
            // Check if the key exists in the first array
            if (isset($settingsresults[$key]))
            {
                $secondValue = $settingsresults[$key];
                $style = "";
                if ($firstValue != $secondValue) $style = "class='alert-warning' ";
                switch ($key)
                {
                    case "allowed_tags":
                        $firstValue = print_a($firstValue, true);
                        $secondValue = print_a($secondValue, true);
                        break;
                    case "smtp_password":
                    case "smtp_username":
                    case "smtp_host":
                        $firstValue = "*******";
                        $secondValue =   "*******";
                        break;
                }

                $text .= "<tr {$style}>
                <td>{$key}</td>
                <td>{$firstValue}</td>
                <td>{$secondValue}</td>
              </tr>";
            }
        }

        // Close HTML table
        $text .= "</table>";


        $text .= "<form method='post' action='" . e_SELF . "?step=2'>
            <input class='btn btn-success' type='submit' name='nextStep[2]' value='Proceed to step 2' />
            </form>";

        $ns->tablerender($stepCaption, $mes->render() . $text);
    }
}

function step2()
{
    $mes = e107::getMessage();
    $ns = e107::getRender();

    $plug = e107::getPlug();
    $data = $plug->getInstalled();

    $stepCaption = 'Step 2: Some settings from fanfiction_settings table are now single plugin preferences ';

    $text = "
    This step only compare values from your fanfiction_settings table with plugin preferences for this installation <br /> 
    <br />
    You should set those prefences in the related area of those plugins
    <br /> 
    Be sure that hash key in e107_config file is the same as your sitekey in fanfiction_settings table.
    <br /> <br /> 

    ";

    if (!e107::isInstalled('fanfiction_pagelinks'))  {
        $mes->addError("fanfiction_pagelinks is not installed");
    }
    else {
        $mes->addSuccess("fanfiction_pagelinks is  installed");
    }

    if (!e107::isInstalled('fanfiction_pagelinks'))
    {
        $mes->addError("fanfiction_panels is not installed");
    }
    else
    {
        $mes->addSuccess("fanfiction_panels is  installed");
    }

    if (!e107::isInstalled('fanfiction_blocks'))
    {
        $mes->addError("fanfiction_blocks is not installed");
    }
    else
    {
        $mes->addSuccess("fanfiction_blocks is  installed");
    }

    if (!e107::isInstalled('fanfiction_messages'))
    {
        $mes->addError("fanfiction_messages is not installed");
    }
    else
    {
        $mes->addSuccess("fanfiction_messages is  installed");
    }

    if (!e107::isInstalled('econtact'))
    {
        $mes->addError("econtact is not installed");
    }
    else
    {
        $mes->addSuccess("econtact is  installed");
    }

    /** Display fanficton_settings to plugin efiction prefs  **/
    $sitekey = e107::getInstance()->getSitePath();
    $settingsresults = e107::getDb()->retrieve("SELECT * FROM #fanfiction_settings WHERE sitekey = '" . $sitekey . "'");
    
    //CORE SETTINGS
    $settingsCore = array();
    $settingsCore =  e107::getEfiction()->replace_plugins_settings($settingsCore);
 
    //if table exists, fill preferences
    if ($settingsCore)
    {

        // Start HTML table
        $text .= "<table class='table table-bordered adminform'>";
        $text .= "<tr><th>Key</th><th>Related Plugin Value</th><th>Fanfiction Settings</th></tr>";

        // Loop through the second array keys and compare with the first array
        foreach ($settingsCore as $key => $firstValue)
        {
            // Check if the key exists in the first array
            if (isset($settingsresults[$key]))
            {
                $secondValue = $settingsresults[$key];
                $text .= "<tr>
                <td>{$key}</td>
                <td>{$firstValue}</td>
                <td>{$secondValue}</td>
              </tr>";
            }
        }

        // Close HTML table
        $text .= "</table>";


        $text .= "<form method='post' action='" . e_SELF . "?step=2'>
            <input class='btn btn-success' type='submit' name='nextStep[2]' value='Proceed to step 2' />
            </form>";

        $ns->tablerender($stepCaption, $mes->render() . $text);
    }
}


function step3()
{
    $mes = e107::getMessage();
    $ns = e107::getRender();

    $stepCaption = 'Step 3: Move some settings from fanfiction_settings table to efiction plugin preferences';

    /** Convert fanficton_settings to plugin efiction prefs  **/
    $sitekey = e107::getInstance()->getSitePath();
    $settingsresults = e107::getDb()->retrieve("SELECT * FROM #fanfiction_settings WHERE sitekey = '" . $sitekey . "'");
    $settingsCore = array();
    $settingsCore =  e107::getEfiction()->replace_efiction_settings($settingsCore);
 
    $settingsresults = array_intersect_key($settingsresults, $settingsCore);

    if (!isset($_POST['move_settings']))
    {
        $text = "
		This step will move the fanficton_settings data to efiction plugin preferences. <br />
		Be sure that you backuped your fanfiction_settings table to be able repeat this step. <br />
		<br />
		This will not touch your eficton data any way. You can always set those preferences manually And skip this step.
		<br /> 
        Be sure that hash key in e107_config file is the same as your sitekey in fanfiction_settings table.
		<br /> <br /> 

		";

        

 
        //if table exists, fill preferences
        if ($settingsCore)
        {

            // Start HTML table
            $text .= "<table class='table table-bordered adminform'>";
            $text .= "<tr><th>Key</th><th>Related Plugin Value</th><th>Fanfiction Settings</th></tr>";

            // Loop through the second array keys and compare with the first array
            foreach ($settingsCore as $key => $firstValue)
            {
                // Check if the key exists in the first array
                if (isset($settingsresults[$key]))
                {
                    $secondValue = $settingsresults[$key];
                    $style = "";
                    if ($firstValue != $secondValue) $style = "class='alert-warning' ";
                    switch ($key)
                    {
                        case "allowed_tags":
                            $firstValue = print_a($firstValue, true);
                            $secondValue = print_a($secondValue, true);
                            break;
                        case "smtp_password":
                        case "smtp_username":
                        case "smtp_host":
                            $firstValue = "*******";
                            $secondValue =   "*******";
                            break;
                    }

                    $text .= "<tr {$style}>
                <td>{$key}</td>
                <td>{$firstValue}</td>
                <td>{$secondValue}</td>
              </tr>";
                }
            }

            // Close HTML table
            $text .= "</table>";

            if (empty(array_diff_assoc($settingsCore, $settingsresults)) && empty(array_diff_assoc($settingsCore, $settingsresults)))
            {
           
                $text .= "
                Values are the same </br>
                <form method='post' action='" . e_SELF . "?step=4'>
                <input class='btn btn-success' type='submit' name='nextStep[4]' value='Proceed to step 4' />
                </form>";

            }
            else {
                $text .=
                    "<form method='post'>
            <input class='btn btn-success' data-loading-text='Please wait...' type='submit' name='move_settings' value='Proceed with settings move' />
            </form>";
            }
 
 
        }
        $ns->tablerender($stepCaption, $mes->render(). $text);

        return;
    }
    
    $text = "";
 

    //if table exists, fill preferences
    if ($settingsresults)
    {
 

        // Filter $firstArray to keep only the keys that exist in $settings
        $filteredArray = array_intersect_key($settingsresults, $settingsCore);

        $fconf = e107::getPlugConfig('efiction', '', false);
        $efiction_prefs = array();
        foreach ($filteredArray as $key => $value)
        {

            $mes->addSuccess("Copying field $key to pref: $key");
            $efiction_prefs[$key] = $value;
        }
        $fconf->setPref($efiction_prefs)->save(false, true);

        $text = "<form method='post' action='" . e_SELF . "?step=4'>
            <input class='btn btn-success' type='submit' name='nextStep[4]' value='Proceed to step 4' />
            </form>";

        $ns->tablerender($stepCaption, $mes->render() . $text);
    }
}


function step4()
{
    $mes = e107::getMessage();
    $ns = e107::getRender();

    $stepCaption = 'Step 4: Last check before replacing efiction files and using e107 settings handler';

 
    /** Display fanficton_settings to plugin efiction prefs  **/
    $sitekey = e107::getInstance()->getSitePath();
    $settingsresults = e107::getDb()->retrieve("SELECT * FROM #fanfiction_settings WHERE sitekey = '" . $sitekey . "'");
    $settingsresults = e107::getEfiction()->notsolved_settings($settingsresults);
    //CORE SETTINGS
    $settingsCore = array();
    $settingsCore =  e107::getEfiction()->getSettings();
    $text = "";
    
    //if table exists, fill preferences
    if ($settingsCore)
    {
 

        // Find keys that are in one array but not the other
        $keysOnlyInFirst = array_diff_key($settingsCore, $settingsresults);
 
        echo "<h3>Keys only in fanfiction_settings table (not updated yet):</h3>";
        if (!empty($keysOnlyInFirst))
        {
            echo "<pre>";
            print_r($keysOnlyInFirst);
            echo "</pre>";
        }
        else
        {
            echo "No unique keys in the fanfiction_settings table.\n";
        }
 
        // Start HTML table
        $text .= "<table class='table table-bordered adminform'>";
        $text .= "<tr><th>Key</th><th>Related Core e107 Value</th><th>Fanfiction Settings</th></tr>";

        // Loop through the second array keys and compare with the first array
        foreach ($settingsCore as $key => $firstValue)
        {
            // Check if the key exists in the first array
            if (isset($settingsresults[$key]))
            {
                $secondValue = $settingsresults[$key];
                $style = "";
                if ($firstValue != $secondValue) $style = "class='alert-warning' ";
                switch ($key)
                {
                    case "allowed_tags":
                        $firstValue = print_a($firstValue, true);
                        $secondValue = print_a($secondValue, true);
                        break;
                    case "smtp_password":
                    case "smtp_username":
                    case "smtp_host":  
                        $firstValue = "*******";
                        $secondValue =   "*******";
                        break;    
                }
                
                $text .= "<tr {$style}>
                <td>{$key}</td>
                <td>{$firstValue}</td>
                <td>{$secondValue}</td>
              </tr>";
            }
        }

        // Close HTML table
        $text .= "</table>";


        $text .= "<form method='post' action='" . e_SELF . "?step=2'>
            <input class='btn btn-success' type='submit' name='nextStep[2]' value='Proceed to step 2' />
            </form>";

        $ns->tablerender($stepCaption, $mes->render() . $text);
    }
}


function efiction_update_adminmenu()
{

    $action = 1;

    $var[1]['text'] = '1 - Core Settings';
    $var[1]['link'] = e_SELF . "?step=1";

    $var[2]['text'] = '2 - efiction Plugin prefs';
    $var[2]['link'] =  e_SELF . "?step=2";

    $var[3]['text'] = '3 - Update efiction Prefs';
    $var[3]['link'] =  e_SELF . "?step=3";

    $var[4]['text'] = '4 - Compare settings in e107 and efiction';
    $var[4]['link'] =  e_SELF . "?step=4";

    // $var[5]['text'] = '5 - Migrate forum config';
    // $var[5]['link'] = '#';

    // $var[6]['text'] = '6 - Migrate threads/replies';
    // $var[6]['link'] = '#';

    // $var[7]['text'] = '7 - Recalc all counts';
    // $var[7]['link'] = '#';

    // $var[8]['text'] = '8 - Calc lastpost data';
    // $var[8]['link'] = '#';

    // $var[9]['text'] = '9 - Migrate any poll data';
    // $var[9]['link'] = '#';

    // $var[10]['text'] = '10 - Migrate any attachments';
    // $var[10]['link'] = '#';

    // $var[11]['text'] = '11 - Delete old attachments';
    // $var[11]['link'] = '#';

    // $var[12]['text'] = '12 - Delete old forum data';
    // $var[12]['link'] = '#';

    if (E107_DEBUG_LEVEL)
    {
        $var[13]['divider'] = true;

        $var[14]['text'] = 'Reset';
        $var[14]['link'] = e_SELF . "?reset";

        $var[15]['text'] = 'Reset to 3';
        $var[15]['link'] = e_SELF . "?step=3&reset=3";

        $var[16]['text'] = 'Reset to 6';
        $var[16]['link'] = e_SELF . "?step=6&reset=6";

        $var[17]['text'] = 'Reset to 7';
        $var[17]['link'] = e_SELF . "?step=7&reset=7";

        $var[18]['text'] = 'Reset to 10';
        $var[18]['link'] = e_SELF . "?step=10&reset=10";
    }


    if (isset($_GET['step']))
    {
        //	$action = key($_POST['nextStep']);
        $action = intval($_GET['step']);
    }

    show_admin_menu('Efiction Upgrade', $action, $var);
}
