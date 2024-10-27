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

class fanfiction_messages_shortcodes extends e_shortcode
{
    static $messages = array();

    public function __construct() {

        self::$messages =  e107::getEfiction()->getMessages();
      
    }

    /*
      {EMESSAGES: key=welcome&strip=blocks} removes div and p tags  = {WELCOME}
      {EMESSAGES: key=rules}  used in pages
      {EMESSAGES: key=maintenance}
      {EMESSAGES: key=printercopyright} in viewstory
      {EMESSAGES: key=copyright}
      {EMESSAGES: key=thankyou}  in yesletter.php
      {EMESSAGES: key=nothankyou} in noletter.php
    */

    /**
     * Render specific eMessages based on key and optional stripping of HTML blocks.
     * Available keys:
     *  - welcome, rules, maintenance, printercopyright, copyright, thankyou, nothankyou or any custom name
     */

    public function sc_emessages($parm = null)
    {
        if (empty($parm['key']))
        {
            return '';
        }

        $key = $parm['key'];
        $text = self::$messages[$key]['message_text'] ?? '';

        // Strip block tags if specified
        if (!empty($parm['strip']) && $parm['strip'] === 'blocks')
        {
            $text = e107::getParser()->stripBlockTags($text);
        }

        return $text ? e107::getParser()->toHTML($text, true, 'BODY') : '';
    }

    //{WELCOME: strip=blocks} removes div and p tags
    public function sc_welcome($parm = null)
    {
       
        $text = self::$messages['welcome']['message_text'] ?? '';

        // Strip block tags if specified
        if (!empty($parm['strip']) && $parm['strip'] === 'blocks')
        {
            $text = e107::getParser()->stripBlockTags($text);
        }
        return $text;
         
    }

    //{COPYRIGHT}  
    public function sc_copyright($parm = null)
    {
 
        $text = self::$messages['copyright']['message_text'] ?? '';

        // Strip block tags if specified
        if (!empty($parm['strip']) && $parm['strip'] === 'blocks')
        {
            $text = e107::getParser()->stripBlockTags($text);
        }

        return $text;
    }

    //{PRINTERCOPYRIGHT}  
    public function sc_printercopyright($parm = null)
    {

        $text = self::$messages['printercopyright']['message_text'] ?? '';

        // Strip block tags if specified
        if (!empty($parm['strip']) && $parm['strip'] === 'blocks')
        {
            $text = e107::getParser()->stripBlockTags($text);
        }

        return $text;
    }  
 
}
