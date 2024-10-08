<?php
/*
 * e107 website system
 *
 * Copyright (C) 2008-2013 e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 */

if (!defined('e107_INIT'))
{
    exit;
}

class e_efiction
{

    public $efiction;
    public $panels;
    /**
     * @param $force
     */
    public function __construct($force = false)
    {
        $ecache = e107::getCache();

        $this->setAccessConstants();

        $this->efiction = array();

        //ciselniky
        if ($force == false && ($panels = $ecache->retrieve_sys('nomd5_efiction')))
        {
            $this->efiction = e107::unserialize($panels);
        }
        else
        {
            //force is true, or cache doesn't exist, or system cache disabled, let's get it from table
            $this->efiction = array();

            $paneltypes = array("A" => _ADMIN, "U" => _USERACCOUNT, "P" => _PROFILE, "F" => _FAVOR, "S" => _SUBMISSIONS, "B" => _BROWSE, "L" => _10LISTS);
            $levels = array("0" => _LEVEL . " 0", "1" => _LEVEL . " 1",  "2" => _LEVEL . " 2", "3" => _LEVEL . " 3", "4" => _LEVEL . " 4");

            $this->efiction['panel_types'] = $paneltypes;
            $this->efiction['levels'] = $levels;

            $panels = e107::getDb()->retrieve('fanfiction_panels', "*", null, true, 'panel_name');
            
            $this->efiction['panels'] = $this->update_panels($panels); 
           // print_a($panels['phpinfo']);
            $ecache->set_sys('nomd5_efiction', e107::serialize($this->efiction, false));
        }
    }

    function update_panels($panels) {
        $plug = e107::getPlug();
        $data = $plug->getInstalled();
        
        if (!e107::isInstalled('fanfiction_panels'))  unset($panels['panels']);
        else {
            $plug->load('fanfiction_panels');
            $url = $plug->getAdminUrl();
            $panels['panels']['panel_url'] =  $url;
        }

        if (!e107::isInstalled('fanfiction_pagelinks'))  unset($panels['links']);
        else
        {
            $plug->load('fanfiction_pagelinks');
            $url = $plug->getAdminUrl();
            $panels['links']['panel_url'] =  $url;
        }

        $panels['phpinfo']['panel_url'] =  e_ADMIN_ABS . 'phpinfo.php';
       

        return $panels;
    }

    function update_settings($panels)
    {
        // e107::pref('fanfiction_pagelinks','linkstyle');
        // e107::pref('fanfiction_pagelinks','linkrange');
    }

    function setAccessConstants()
    {
        if (e107::getUser()->isMainAdmin())  //e107 superadmin
        {
            define('uLEVEL', "1");
            define('isADMIN', true);
            define('isMEMBER', true);
        }
        elseif (e107::getUser()->isAdmin())
        {
            define('uLEVEL', "2");
            define('isADMIN', true);
            define('isMEMBER', true);
        }
        elseif (e107::getUser()->isUser())
        {
            define('uLEVEL', "0");
            define('isADMIN', false);
            define('isMEMBER', true);
        }
        elseif (e107::getUser()->isGuest())
        {
            define('uLEVEL', "0");
            define('isADMIN', false);
            define('isMEMBER', false);
        }
        else
        {
            define('uLEVEL', "0");
            define('isADMIN', false);
            define('isMEMBER', false);
            define('USERUID', 0);
        }
    }

    /**
     * @return array
     */
    public function getPanelTypes()
    {
        return $this->efiction['panel_types'];
    }

    /**
     * @return array
     */
    public function getUserLevels()
    {
        return $this->efiction['levels'];
    }

    /**
     * @return array
     */
    public function getUserPanels($type = NULL,  $hidden = false, $level = 5)
    {

        $panels =  $this->efiction['panels'];
        $level = uLEVEL;

        // Filter array where panel_type = $type, panel_hidden = $hidden, and panel_level >= $level
        $result = array_filter($panels, function ($panel) use ($type, $hidden, $level)
        {
            return $panel['panel_type'] == $type && $panel['panel_hidden'] == $hidden && $panel['panel_level'] >= $level;
        });

        return $result;
    }
}
