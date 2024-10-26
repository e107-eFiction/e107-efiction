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
if (!class_exists('e_efiction'))
{
    class e_efiction
    {

        public $efiction;
        public $panels;
        public $pagelinks;
        public $settings;

        /**
         * @param $force
         */
        public function __construct($force = false)
        {
            $ecache = e107::getCache();

            $this->setAccessConstants();

            $this->efiction = array();
            $force = true;
            //ciselniky
            if ($force == false && ($efictionCached = $ecache->retrieve_sys('nomd5_efiction')))
            {
                
                $this->efiction  = e107::unserialize($efictionCached);
            }
            else
            {
                //force is true, or cache doesn't exist, or system cache disabled, let's get it from table
                //panels data
                $paneltypes = array("A" => _ADMIN, "U" => _USERACCOUNT, "P" => _PROFILE, "F" => _FAVOR, "S" => _SUBMISSIONS, "B" => _BROWSE, "L" => _10LISTS);
                $levels = array("0" => _LEVEL . " 0", "1" => _LEVEL . " 1",  "2" => _LEVEL . " 2", "3" => _LEVEL . " 3", "4" => _LEVEL . " 4");

                $this->efiction['panel_types'] = $paneltypes;
                $this->efiction['levels'] = $levels;

                $sitekey = e107::getInstance()->getSitePath();
                $settings = e107::getDb()->retrieve('fanfiction_settings', "*", " WHERE sitekey = '" . $sitekey . "' ", false);

                $this->efiction['settings'] = $this->upgrade_settings($settings);
                // print_a($this->efiction['settings']);
                $panels = e107::getDb()->retrieve('fanfiction_panels', "*", null, true, 'panel_name');

                $this->efiction['panels'] = $this->upgrade_panels($panels);

                //pagelinks data
                $pagelinks = e107::getDb()->retrieve('fanfiction_pagelinks', "*", true, true);
                $this->efiction['pagelinks'] = $this->upgrade_pagelinks($pagelinks);
 
                $ecache->set_sys('nomd5_efiction', e107::serialize($this->efiction, false), $force);
            }


            //loading from cache failed   
            if (!isset($this->efiction['panels']) or !isset($this->efiction['pagelinks']) or !isset($this->efiction['settings']))
            {
                //panels data
                $paneltypes = array("A" => _ADMIN, "U" => _USERACCOUNT, "P" => _PROFILE, "F" => _FAVOR, "S" => _SUBMISSIONS, "B" => _BROWSE, "L" => _10LISTS);
                $levels = array("0" => _LEVEL . " 0", "1" => _LEVEL . " 1",  "2" => _LEVEL . " 2", "3" => _LEVEL . " 3", "4" => _LEVEL . " 4");

                $this->efiction['panel_types'] = $paneltypes;
                $this->efiction['levels'] = $levels;

                //settings
                $settings = e107::getDb()->retrieve('fanfiction_settings', "*", null, true);

               
                $this->efiction['settings'] = $this->upgrade_settings($settings);

                //pagelinks data
                $pagelinks = e107::getDb()->retrieve('fanfiction_pagelinks', "*", true, true, 'link_name');
                $this->efiction['pagelinks'] = $this->upgrade_pagelinks($pagelinks);

                $panels = e107::getDb()->retrieve('fanfiction_panels', "*", null, true, 'panel_name');

                $this->efiction['panels'] = $this->upgrade_panels($panels);


                $ecache->set_sys('nomd5_efiction', e107::serialize($this->efiction, false), true);
            }
        }



        function upgrade_panels($panels)
        {
            $plug = e107::getPlug();
            $data = $plug->getInstalled();

            if (!e107::isInstalled('fanfiction_panels'))  unset($panels['panels']);
            else
            {
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


        function replace_core_settings($settings)
        {

            $pref =  e107::getPref();
            $sitekey = e107::getInstance()->getSitePath();

            /* replace not used settings */
            $settings['sitekey'] = $sitekey;
            $settings['sitename'] = SITENAME;
            $settings['slogan'] = SITETAG;
            $settings['url'] = SITEURL;
            $settings['tableprefix'] = e107::getDB()->mySQLPrefix;
            $settings['siteemail'] = ADMINEMAIL;
            $settings['language'] = e_LANGUAGE;

            $currentTheme = e107::getPref('sitetheme');

            $settings['skin'] =  $currentTheme;
            $settings['smtp_host'] =  $pref['smtp_server'];
            $settings['smtp_username'] =  $pref['smtp_username'];
            $settings['smtp_password'] =  $pref['smtp_password'];

            return $settings;
     
        }

        function replace_plugins_settings($settings)
        {
            $sitekey = e107::getInstance()->getSitePath();
            $pref =  e107::getPref();
            $settings['linkstyle'] =  e107::pref('fanfiction_pagelinks', 'linkstyle');
            $settings['linkrange'] =  e107::pref('fanfiction_pagelinks', 'linkrange');
            return $settings;
        }

        function replace_efiction_settings($settings)
        {

                $settings['submissionsoff'] =  e107::pref('efiction', 'submissionsoff');
                $settings['autovalidate'] =  e107::pref('efiction', 'autovalidate');
                $settings['coauthallowed'] =  e107::pref('efiction', 'coauthallowed');
                $settings['roundrobins'] =  e107::pref('efiction', 'roundrobins');
                $settings['allowseries'] =  e107::pref('efiction', 'allowseries');
                $settings['imageupload'] =  e107::pref('efiction', 'imageupload');
                $settings['imageheight'] =  e107::pref('efiction', 'imageheight');
                $settings['imagewidth'] =  e107::pref('efiction', 'imagewidth');
                $settings['store'] =  e107::pref('efiction', 'store');
                $settings['storiespath'] =  e107::pref('efiction', 'storiespath');
                $settings['minwords'] =  e107::pref('efiction', 'minwords');
                $settings['maxwords'] =  e107::pref('efiction', 'maxwords');
                $settings['tinyMCE'] =  e107::pref('efiction', 'tinyMCE');
                $settings['allowed_tags'] =  e107::pref('efiction', 'allowed_tags');
                $settings['favorites'] =  e107::pref('efiction', 'favorites');
                $settings['multiplecats'] =  e107::pref('efiction', 'multiplecats');
                $settings['dateformat'] =  e107::pref('efiction', 'dateformat');
                $settings['timeformat'] =  e107::pref('efiction', 'timeformat');
                $settings['extendcats'] =  e107::pref('efiction', 'extendcats');
                $settings['displaycolumns'] =  e107::pref('efiction', 'displaycolumns');
                $settings['itemsperpage'] =  e107::pref('efiction', 'itemsperpage'); 
                $settings['recentdays'] =  e107::pref('efiction', 'recentdays');
                $settings['defaultsort'] =  e107::pref('efiction', 'defaultsort');
                $settings['displayindex'] =  e107::pref('efiction', 'displayindex');
                $settings['displayprofile'] =  e107::pref('efiction', 'displayprofile');
                $settings['reviewsallowed'] =  e107::pref('efiction', 'reviewsallowed');
                $settings['anonreviews'] =  e107::pref('efiction', 'anonreviews');
                $settings['revdelete'] =  e107::pref('efiction', 'revdelete');
                $settings['ratings'] =  e107::pref('efiction', 'ratings');
                $settings['rateonly'] =  e107::pref('efiction', 'rateonly');
                $settings['alertson'] =  e107::pref('efiction', 'alertson');
                $settings['disablepopups'] =  e107::pref('efiction', 'disablepopups');
                $settings['agestatement'] =  e107::pref('efiction', 'agestatement');
                $settings['pwdsetting'] =  e107::pref('efiction', 'pwdsetting');

            return $settings;
        }

        //all possible table fields - used only for testing and update 
        function notsolved_settings($settings)
        {
            $sitekey = e107::getInstance()->getSitePath();
            $pref =  e107::getPref();
            $settings['newscomments'] =  e107::pref('efiction', 'newscomments');
            $settings['logging'] =  e107::pref('efiction', 'logging');
            $settings['maintenance'] =  e107::pref('efiction', 'maintenance');
            $settings['debug'] =  e107::pref('efiction', 'debug');
            $settings['captcha'] =  e107::pref('efiction', 'captcha');
            unset($settings['newscomments']);
            unset($settings['logging']);
            unset($settings['maintenance']);
            unset($settings['debug']);
            unset($settings['captcha']);
            unset($settings['version']);
            unset($settings['words']);
            unset($settings['anonchallenges']);
            unset($settings['anonrecs']);
            unset($settings['rectarget']);
            unset($settings['autovalrecs']);
            unset($settings['hiddenskins']);
            
            return $settings;
        }


        function upgrade_settings($settings)
        {

            $settings = $this->replace_core_settings($settings);
            $settings = $this->replace_plugins_settings($settings);
            $settings = $this->replace_efiction_settings($settings);
            

            $pref =  e107::getPref();
 
            //default values if they are not set
            $settings['recentdays'] = varset($settings['recentdays'], 7);


            return $settings;
 
        }

        function upgrade_pagelinks($pagelinks)
        {
            $pagelinks['contactus']['link_url'] = e107::url('contact', 'index');

            return $pagelinks;
        }

        function upgrade_blocks($blocks)
        {
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
        public function getSettings()
        {
            return $this->efiction['settings'];
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

        /**
         * @return array
         */
        public function getUserPagelinks($type = NULL,  $hidden = false, $level = 5)
        {
            $pagelinks =  $this->efiction['pagelinks'];

            $userlinks = array();
            foreach ($pagelinks as $link)
            {
                if ($link['link_access'] && !isMEMBER)
                {
                    continue;
                }
                if ($link['link_access'] == 2 && uLEVEL < 1)
                {
                    continue;
                }
                if ($link['link_name'] == 'register' && isMEMBER)
                {
                    continue;
                }
                if (strpos($link['link_url'], 'http://') === false && strpos($link['link_url'], 'https://') === false)
                {
                    $link['link_url'] = e_HTTP . $link['link_url'];
                }

                $userlinks[$link['link_name']]  = $link;
            }

            return $userlinks;
        }
    }
}
