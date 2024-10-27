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
    $stepCaption = 'Step 1: Some settings from the fanfiction_settings table are now core site preferences.';

    // Instructional text
    $text = "
        This step compares values from your <strong>fanfiction_settings</strong> table with core preferences for this installation. <br /> 
        You should set these preferences in the corresponding section of e107 Settings. <br /> 
        Ensure that the hash key in the <strong>e107_config</strong> file matches your <strong>sitekey</strong> in the fanfiction_settings table. <br /><br />
        If the Fanfiction Settings column is empty, this means the <strong>fanfiction_settings</strong> table does not exist, which is expected for e107.
        <br /><br />
    ";

    // Retrieve site-specific settings
    $sitekey = e107::getInstance()->getSitePath();
    $settingsFFTable = e107::getDb()->retrieve("SELECT * FROM #fanfiction_settings WHERE sitekey = '" . $sitekey . "'");

    $settingsCore = array();
    // Get core settings from efiction plugin
    $settingsCore = e107::getEfiction(true)->replace_core_settings($settingsCore);

    // Only proceed if core settings are available
    if (!empty($settingsCore))
    {
        // Generate comparison table
        $text .= compare_table($settingsCore, $settingsFFTable, 'Related Core e107 Value', 'Fanfiction Settings');

        // Proceed button for next step
        $text .= "<form method='post' action='" . e_SELF . "?step=2'>
            <input class='btn btn-success' type='submit' name='nextStep[2]' value='Proceed to step 2' />
        </form>";
    }
    else
    {
        // Display error message if core settings are missing
        $mes->addError("Core settings could not be retrieved. Please verify your e107 configuration.");
    }

    // Render the content
    $ns->tablerender($stepCaption, $mes->render() . $text);
}


function step2()
{
    $mes = e107::getMessage();
    $ns = e107::getRender();

    $plug = e107::getPlug();
    $data = $plug->getInstalled();

    $stepCaption = 'Step 2: Some settings from fanfiction_settings table are now single plugin preferences ';

    $text = "
    This step compares values from your <strong>fanfiction_settings</strong> table with plugin preferences for this installation.<br /> 
    Please set these preferences in the corresponding plugin settings section.<br /> 
    Ensure that the hash key in the <strong>e107_config</strong> file matches the <strong>sitekey</strong> in the fanfiction_settings table.<br /><br />
    - If the <strong>Fanfiction Settings</strong> column is empty, it means the fanfiction_settings table does not exist, which is expected in some configurations.<br />
    - If the first column is empty, the plugin preferences have not been saved yet.<br /><br />
    ";


    // Array of plugins to check
    $plugins = [
        'efiction',
        'fanfiction_settings',
        'fanfiction_pagelinks',
        'fanfiction_panels',
        'fanfiction_blocks',
        'fanfiction_messages',
        'econtact'
    ];

    // Loop through each plugin and check if installed
    foreach ($plugins as $plugin)
    {
        if (!e107::isInstalled($plugin))
        {
            $mes->addError("$plugin is not installed");
        }
        else
        {
            $mes->addSuccess("$plugin is installed");
        }
    }


    /** Display fanficton_settings to plugin efiction prefs  **/
    $sitekey = e107::getInstance()->getSitePath();
    $settingsFFTable = e107::getDb()->retrieve("SELECT * FROM #fanfiction_settings WHERE sitekey = '" . $sitekey . "'");

    //CORE SETTINGS
    $settingsPlugins = array();
    $settingsPlugins =  e107::getEfiction()->replace_plugins_settings($settingsPlugins);


    //if table exists, fill preferences
    if ($settingsPlugins)
    {

        $text .= compare_table($settingsPlugins, $settingsFFTable  = NULL,  'Related Plugin Value',  'Fanfiction Settings');

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
    $settingsFFTable = e107::getDb()->retrieve("SELECT * FROM #fanfiction_settings WHERE sitekey = '" . $sitekey . "'");
    $settingsCore = array();
    $settingsCore =  e107::getEfiction()->replace_efiction_settings($settingsCore);

    $settingsFFTable = array_intersect_key($settingsFFTable, $settingsCore);

    if (!isset($_POST['move_settings']))
    {
        $text = "
    This step will transfer data from the <strong>fanfiction_settings</strong> table to the <strong>efiction</strong> plugin preferences.<br /> 
    <strong>Important:</strong> Please back up your <strong>fanfiction_settings</strong> table before proceeding, so you can repeat this step if needed.<br /> 
    This process will not modify any existing <strong>efiction</strong> data. You may also choose to manually set these preferences and skip this step.<br /> 
    Ensure that the hash key in the <strong>e107_config</strong> file matches the <strong>sitekey</strong> in the fanfiction_settings table.<br /><br />
    - If the <strong>Fanfiction Settings</strong> column is empty, the fanfiction_settings table does not exist, which is expected for e107.<br />
    - If the first column is empty, you have not saved the plugin preferences yet.<br /> <br />
        ";
 
        //if table exists, fill preferences
        if ($settingsCore)
        {

            $text .= compare_table($settingsCore, $settingsFFTable  = NULL,  'Related Plugin Value',  'Fanfiction Settings');

            if (empty(array_diff_assoc($settingsCore, $settingsFFTable)) && empty(array_diff_assoc($settingsCore, $settingsFFTable)))
            {
                if ($settingsFFTable) $text .= "Values are the same </br>";
                
                $text .= "
                
                <form method='post' action='" . e_SELF . "?step=4'>
                <input class='btn btn-success' type='submit' name='nextStep[4]' value='Proceed to step 4' />
                </form>";
            }
            else
            {
                if ($settingsFFTable)
                {
                    $text .=
                        "<form method='post'>
                        <input class='btn btn-success' data-loading-text='Please wait...' type='submit' name='move_settings' value='Proceed with settings move' />
                        </form>";
                }
            }
        }
        $ns->tablerender($stepCaption, $mes->render() . $text);

        return;
    }

    $text = "";


    //if table exists, fill preferences
    if ($settingsFFTable)
    {
        // Filter $firstArray to keep only the keys that exist in $settings
        $filteredArray = array_intersect_key($settingsFFTable, $settingsCore);

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


    /** Fanficton_settings  table**/
    $sitekey = e107::getInstance()->getSitePath();
    $settingsFFTable = e107::getDb()->retrieve("SELECT * FROM #fanfiction_settings WHERE sitekey = '" . $sitekey . "'");

    /** Display fanficton_settings that are known and not supported yet  **/
    $settingsUnSupported = e107::getEfiction()->notsolved_settings($settingsFFTable);

    /** supported settings  **/
    $settingsCore = array();
    $settingsCore =  e107::getEfiction()->getSettings();
    $text = "";

    $text .= "<h6>Keys only in fanfiction_settings table (not updated yet):</h6>";
    //if table exists, fill preferences
    if ($settingsCore && !empty($settingsFFTable))
    {
        

        // Find keys that are in one array but not the other
        $keysOnlyInFirst = array_diff_key($settingsCore, $settingsFFTable);

        
        if (!empty($keysOnlyInFirst) && !in_array($keysOnlyInFirst, $settingsUnSupported))
        {
            $text .= "<pre>";
            $text .=  print_r($keysOnlyInFirst, true);
            $text .= "</pre>";
        }
        else
        {
            echo "No unique keys in the fanfiction_settings table.\n";
        }
    }
    $text .= "<h6>Supported settings:</h6>";
 
         $text .= compare_table($settingsCore, $settingsFFTable  = NULL,  'Related e107 Value',  'Fanfiction Settings');

    $text .= "<h6>Not Supported settings yet:</h6>";

        $text .= compare_table($settingsUnSupported, $settingsCore  = NULL,  'Related e107 Value',  'Fanfiction Settings');
 
        $text .= "<form method='post' action='" . e_SELF . "?step=1'>
            <input class='btn btn-success' type='submit' name='nextStep[1]' value='Proceed to step 1' />
            </form>";

        $ns->tablerender($stepCaption, $mes->render() . $text);
   
}

function compare_table($firstArray = NULL, $secondArray = NULL, $firstCaption = '', $secondCaption = '')
{
    // Sensitive keys to mask
    $sensitiveKeys = ['smtp_password', 'smtp_username', 'smtp_host'];
    $text = "<table class='table table-bordered adminform'>";
    $text .= "<tr><th>Key</th><th>{$firstCaption}</th><th>{$secondCaption}</th></tr>";

    foreach ($firstArray as $key => $firstValue)
    {
        $secondValue = $secondArray[$key] ?? '';

        // Mask sensitive data only if the values are not empty
        if (in_array($key, $sensitiveKeys))
        {
            $firstValue = !empty($firstValue) ? "*******" : $firstValue;
            $secondValue = !empty($secondValue) ? "*******" : $secondValue;
        }
        elseif ($key === "allowed_tags")
        {
            // Handle 'allowed_tags' formatting
            $firstValue = !empty($firstValue) ? print_a($firstValue, true) : $firstValue;
            $secondValue = !empty($secondValue) ? print_a($secondValue, true) : $secondValue;
        }

        // Highlight rows with differences
        $style = ($firstValue !== $secondValue) ? "class='alert-warning'" : "";

        $text .= "<tr {$style}>
            <td>{$key}</td>
            <td>{$firstValue}</td>
            <td>{$secondValue}</td>
        </tr>";
    }

    $text .= "</table>";
    return $text;
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
