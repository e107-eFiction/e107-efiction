<?php
/*
 * e107 website system
 *
 * Copyright (C) 2008-2013 e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 * User information - shortcodes
 *
 */

if (!defined('e107_INIT')) { exit; }

e107::coreLan('user');

class user_shortcodes extends e_shortcode
{

	private $commentsDisabled;
	private $commentsEngine;

	function __construct()
	{
		parent::__construct();

		$pref = e107::getPref();

		$this->commentsDisabled = vartrue($pref['comments_disabled']);

		if(!empty($pref['comments_engine']))
		{
			$this->commentsEngine = $pref['comments_engine'];
		}
	}


	function sc_total_chatposts($parm = null) 
	{
		$sql = e107::getDb();

		if(!$chatposts = e107::getRegistry('total_chatposts'))
		{
		  $chatposts = 0; // In case plugin not installed
		  if(e107::isInstalled("chatbox_menu"))
		  {
			$chatposts = $sql->count("chatbox");
		  }
		  e107::setRegistry('total_chatposts', $chatposts);
		}

		return $chatposts;
	}
		
	
	function sc_total_commentposts($parm = null) 
	{
		$sql = e107::getDb();

		if(!$commentposts = e107::getRegistry('total_commentposts'))
		{
			$commentposts = $sql->count("comments");
			e107::setRegistry('total_commentposts', $commentposts);
		}

		return $commentposts;
	}
	
	
	function sc_total_forumposts($parm = null) 
	{
		$sql = e107::getDb();

		if(!$forumposts = e107::getRegistry('total_forumposts'))
		{
			$forumposts = $sql->count("forum_thread");
			e107::setRegistry('total_forumposts', $forumposts);
		}

		return $forumposts;
	}

	
	function sc_user_commentposts($parm = null) 
	{
		if($this->commentsDisabled)
		{
			return false;
		}
		return "<a href='".e_HTTP."userposts.php?0.comments.".$this->var['user_id']."'>".$this->var['user_comments']."</a>";
	}
	
	
	function sc_user_forumposts($parm = null) 
	{
		//return $this->var['user_forums']; //FIXME column not present in v2. Possible fix on next line.
		//return e107::getDb()->count("forum_thread","(*)","where thread_user=".$this->var['user_id']); // Does not account for pruned posts? #716. Possible fix on next line.
		return e107::getDb()->retrieve('user_extended', 'user_plugin_forum_posts', 'user_extended_id = '.$this->var['user_id']);
	}
	
	
	function sc_user_chatposts($parm = null) 
	{
		return $this->var['user_chats'];
	}
	

	function sc_user_downloads($parm = null) 
	{
		return e107::getDb()->count("download_requests","(*)","where download_request_userid=".$this->var['user_id']);
	}
	

	function sc_user_chatper($parm = null) 
	{
		$sql = e107::getDb();
		
		if(!$chatposts = e107::getRegistry('total_chatposts'))
		{
			$chatposts = 0; // In case plugin not installed
	  		if (e107::isInstalled("chatbox_menu"))
	  		{
				$chatposts = intval($sql->count("chatbox"));
	  		}
	  		e107::setRegistry('total_chatposts', $chatposts);
		}
		return ($chatposts > 0) ? round(($this->var['user_chats']/$chatposts) * 100, 2) : 0;
	}
	
	
	function sc_user_commentper($parm='')
	{
		if($this->commentsDisabled)
		{
			return false;
		}


		$sql = e107::getDb();
		if(!$commentposts = e107::getRegistry('total_commentposts'))
		{
			$commentposts = intval($sql->count("comments"));
			e107::setRegistry('total_commentposts', $commentposts);
		}
		return ($commentposts > 0) ? "<a href='".e_HTTP."userposts.php?0.comments.".$this->var['user_id']."'>".round(($this->var['user_comments']/$commentposts) * 100, 2)."</a>" : 0;
	}
	
	
	function sc_user_forumper($parm='')
	{
		$sql = e107::getDb();
		if(!$total_forumposts = e107::getRegistry('total_forumposts'))
		{
			$total_forumposts = (e107::isInstalled("forum")) ? intval($sql->count("forum_post")) : 0;
			e107::setRegistry('total_forumposts', $total_forumposts);
			//$user_forumposts = $sql->count("forum_thread","(*)","where thread_user=".$this->var['user_id']);
			$user_forumposts = e107::getDb()->retrieve('user_extended', 'user_plugin_forum_posts', 'user_extended_id = '.$this->var['user_id']);

		}
		return ($total_forumposts > 0) ? round(($user_forumposts/$total_forumposts) * 100, 2) : 0;
	}

 
	
	
	function sc_user_lastvisit($parm='')
	{
		return $this->var['user_currentvisit'] ? e107::getDate()->convert_date($this->var['user_currentvisit'], "long") : e107::getDate()->convert_date($this->var['user_lastvisit'], "long");
	}
	
	
	function sc_user_lastvisit_lapse($parm='')
	{	
		return $this->var['user_currentvisit'] ? e107::getDate()->computeLapse($this->var['user_currentvisit']) : e107::getDate()->computeLapse($this->var['user_lastvisit']);
	}

	
	function sc_user_visits($parm='')
	{
		return $this->var['user_visits'];
	}


	function sc_user_join($parm='')
	{
		return e107::getDate()->convert_date($this->var['user_join'], "forum");
	}
	
	
	function sc_user_daysregged($parm='')
	{
		return e107::getDate()->computeLapse($this->var['user_join']);
	}

		
	function sc_user_realname_icon($parm='')
	{
		$ret = '';

		if(defined("USER_REALNAME_ICON"))
		{
			$ret = USER_REALNAME_ICON;
		}
		elseif(file_exists(THEME."images/user_realname.png"))
		{
			$ret = "<img src='".THEME_ABS."images/user_realname.png' alt='' style='vertical-align:middle;' /> ";
		}
		elseif(defined('IMODE') && file_exists( e_IMAGE."user_icons/user_realname_".IMODE.".png"))
		{
			$ret = "<img src='".e_IMAGE_ABS."user_icons/user_realname_".IMODE.".png' alt='' style='vertical-align:middle;' /> ";
		}

		return $ret;
	}
	
	
	function sc_user_realname($parm='')
	{
		return $this->var['user_login'] ? $this->var['user_login'] : "<i>".LAN_USER_33."</i>";
	}

	
	function sc_user_email_icon($parm='')
	{
		if(defined("USER_EMAIL_ICON"))
		{
			return USER_EMAIL_ICON;
		}
		if(file_exists(THEME."images/email.png"))
		{
			return "<img src='".THEME_ABS."images/email.png' alt='' style='vertical-align:middle;' /> ";
		}
		
		return "<img src='".e_IMAGE_ABS."generic/email.png' alt='' style='vertical-align:middle;' /> ";
	}


	function sc_user_email_link($parm='')
	{
		$tp = e107::getParser();
		return /* Condition             */ ($this->var['user_hideemail'] && !ADMIN) ?
		       /*  Hidden and Not Admin */ "<i>".LAN_USER_35."</i>" :
		       /*  Not Hidden or Admin  */ $tp->parseTemplate("{email={$this->var['user_email']}-link}");
	}

	
	function sc_user_email($parm='')
	{
		$tp = e107::getParser();
		
		$aCurUserData = e107::user(USERID);

		if( ($this->var['user_hideemail'] && !ADMIN ) && ( $this->var['user_email']!=$aCurUserData['user_email'] ) )
		{
			return "<i>".LAN_USER_35."</i>";
		}
		else
		{
			if($this->var['user_email']!=$aCurUserData['user_email'])
			{
				return $tp->emailObfuscate($this->var['user_email']);
				//list($user,$dom) = explode('@', $this->var['user_email']);
				//return "<span class='e-email' data-user='".$user."' data-dom='".$dom."'>&#64;</span>";
			}
			else
			{
				return $this->var['user_email'];
			}
		}
	}


	/**
	 * USER_ICON Shortcode
	 * v2.x usage - always provide $parm to determine type. 
	 * @param string $parm 
	 * @example {USER_ICON=email}
	 * 
	 */
	function sc_user_icon($parm='') 
	{
		$boot = deftrue('BOOTSTRAP');
		$tp = e107::getParser();
		
		switch ($parm) 
		{
			case 'email':
				return ($boot) ? $tp->toGlyph('fa-envelope') : $this->sc_user_email_icon();
			break;
			
			case 'lastvisit':
				return ($boot) ? $tp->toGlyph('fa fa-clock-o') : '';
			break;
			
			case 'birthday':
				return ($boot) ? $tp->toGlyph('fa-calendar') : $this->sc_user_birthday_icon();
			break;

			case 'level':
				return ($boot) ? $tp->toGlyph('fa-signal') : '';
			break;
			
			case 'website':
				return ($boot) ? $tp->toGlyph('fa-home') : '';
			break;
			
			case 'location':
				return ($boot) ? $tp->toGlyph('fa-map-marker') : '';
			break;

			case 'msn':
			case 'icq':
				return ($boot) ? $tp->toGlyph('fa-comment') : '';
			break;

			default:
			case 'realname':
			case 'user':
				return ($boot) ? $tp->toGlyph('fa-user') : $this->sc_user_realname_icon();
			break;
		}

	
		/*
		if(defined("USER_ICON"))
		{
			return USER_ICON;
		}
		if(file_exists(THEME."images/user.png"))
		{
			return "<img src='".THEME_ABS."images/user.png' alt='' style='vertical-align:middle;' /> ";
		}
		
		return "<img src='".e_IMAGE_ABS."user_icons/user.png' alt='' style='vertical-align:middle;' /> ";
		*/
	}


	function sc_user_icon_link($parm='')
	{

		$uparams = array('id' => $this->var['user_id'], 'name' => $this->var['user_name']);
		$url = e107::getUrl();
		if(defined("USER_ICON"))
		{
			$icon = USER_ICON;
		}
		else if(file_exists(THEME."images/user.png"))
		{
			$icon = "<img src='".THEME_ABS."images/user.png' alt='' style='vertical-align:middle;' /> ";
		}
		else
		{
			$icon = "<img src='".e_IMAGE_ABS."user_icons/user.png' alt='' style='vertical-align:middle;' /> ";
		}
		return "<a href='".$url->create('user/profile/view', $uparams)."'>{$icon}</a>";
	}


	function sc_user_id($parm='')
	{
		return $this->var['user_id'];
	}


	function sc_user_name($parm='')
	{
		return $this->var['user_name'];
	}


	function sc_user_name_link($parm='')
	{
	   $url = $this->sc_user_profile_url(); 
	   return "<a href='".$url."'>".$this->var['user_name']."</a>";
	}


	function sc_user_profile_url($parm='')
	{
	    $uparams = array('id' => $this->var['user_id'], 'name' => $this->var['user_name']);
	    return e107::getUrl()->create('user/profile/view', $uparams);
	}


	function sc_user_loginname($parm='')
	{
		if(ADMIN && getperms("4"))
		 {
			return $this->var['user_loginname'];
		}
	}


	function sc_user_birthday_icon($parm='')
	{
		if(defined("USER_BIRTHDAY_ICON"))
		{
			return USER_BIRTHDAY_ICON;
		}
		if(file_exists(THEME."images/user_birthday.png"))
		{
			return "<img src='".THEME_ABS."images/user_birthday.png' alt='' style='vertical-align:middle;' /> ";
		}

		return "<img src='".e_IMAGE_ABS."user_icons/user_birthday.png' alt='' style='vertical-align:middle;' /> ";
	}


	function sc_user_birthday($parm='')
	{
		if(!empty($this->var['user_birthday']) && $this->var['user_birthday'] != "0000-00-00" && preg_match("/([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})/", $this->var['user_birthday'], $regs))
		{
			return "$regs[3].$regs[2].$regs[1]";
		}
		else
		{
			return "<i>".LAN_USER_33."</i>";
		}
	}


	function sc_user_signature($parm=null)
	{
		return !empty($this->var['user_signature']) ? e107::getParser()->toHTML($this->var['user_signature'], TRUE) : null;
	}


	function sc_user_comments_link($parm=null)
	{
		if($this->commentsDisabled)
		{
			return false;
		}
		return $this->var['user_comments'] ? "<a href='".e_HTTP."userposts.php?0.comments.".$this->var['user_id']."'>".LAN_USER_36."</a>" : "";
	}


	function sc_user_forum_link($parm=null)
	{
		$user_forumposts = e107::getDb()->count("forum_thread","(*)","where thread_user=".$this->var['user_id']);
		return $user_forumposts ? "<a href='".e_HTTP."userposts.php?0.forums.".$this->var['user_id']."'>".LAN_USER_37."</a>" : "";
	}

	
	function sc_user_sendpm($parm=null)
	{
		$pref = e107::getPref();
		$tp = e107::getParser();
		if(e107::isInstalled("pm") && ($this->var['user_id'] > 0 && $this->var['user_id'] != USERID ))
		{
		  return $tp->parseTemplate("{SENDPM={$this->var['user_id']}}");
		}
	}


	function sc_user_rating($parm=null)
	{
		$pref = e107::getPref();
		$frm = e107::getForm();
		
		if(!vartrue($pref['profile_rate'])){ return; }
		
		if(!USER)
		{ 
			return LAN_USER_87; 
		} 
		else
		{
			switch ($parm) 
			{
				case 'like':
					return $frm->like('user',$this->var['user_id']);	
				break;
				case 'legacy':
					$rater = e107::getRate();
					$ret = "<span>";
					if($rating = $rater->getrating('user', $this->var['user_id']))
					{
						$num = $rating[1];
						for($i=1; $i<= $num; $i++)
						{
							$ret .= "<img src='".e_IMAGE_ABS."rate/star.png' alt='' />";
						}
					}
					if(!$rater->checkrated('user', $this->var['user_id']))
					{
						$ret .= " &nbsp; &nbsp;".$rater->rateselect('', 'user', $this->var['user_id']);
					}
					$ret .= "</span>";
					return $ret;	
				break;
				default:
					return $frm->rate('user',$this->var['user_id']);	
				break;
			}		

			return "";
		}
	}

	
	function sc_user_update_link($parm=null)
	{
		$label = null;
 
		if (USERID == $this->var['user_id']) 
		{
			$label = LAN_USER_38;
		}
		else if(ADMIN && getperms("4") && !$this->var['user_admin']) 
		{
			$label = LAN_USER_39;
		}

		if(empty($label))
		{
			return null;
		}

		return "<a class='btn btn-default btn-secondary' href='".$this->sc_user_settings_url()."'>".$label."</a>";
	}


	function sc_user_settings_url($parm=null)
	{
		if (USERID == $this->var['user_id'])
		{
			return e107::getUrl()->create('user/myprofile/edit');
		}
		else if(ADMIN && getperms("4") && !$this->var['user_admin'])
		{
			return e107::getUrl()->create('user/profile/edit', array('id' => $this->var['user_id']));
		}
	}

	
	/**
	* @example {USER_JUMP_LINK=prev|class=btn-secondary} 
	*/
	function sc_user_jump_link($parm = null) 
	{
		$parms = eHelper::scDualParams($parm);

		//print_a($parms);
		global $full_perms;
		$sql = e107::getDb();
		$tp = e107::getParser();
		
		if (!$full_perms) return;
		$url = e107::getUrl();
		if(!$userjump = e107::getRegistry('userjump'))
		{
		  $sql->gen("SELECT user_id, user_name FROM `#user` FORCE INDEX (PRIMARY) WHERE `user_id` > ".intval($this->var['user_id'])." AND `user_ban`=0 ORDER BY user_id ASC LIMIT 1 ");
		  if ($row = $sql->fetch())
		  {
			$userjump['next']['id'] = $row['user_id'];
			$userjump['next']['name'] = $row['user_name'];
		  }

		  $sql->gen("SELECT user_id, user_name FROM `#user` FORCE INDEX (PRIMARY) WHERE `user_id` < ".intval($this->var['user_id'])." AND `user_ban`=0 ORDER BY user_id DESC LIMIT 1 ");
		  if ($row = $sql->fetch())
		  {
			$userjump['prev']['id'] = $row['user_id'];
			$userjump['prev']['name'] = $row['user_name'];
		  }
		  e107::setRegistry('userjump', $userjump);
		}
		
		$class  = empty($parms[2]['class']) ? 'e-tip page-link' : $parms[2]['class'];
	
		if($parms[1] == 'prev')
		{
		
			$icon = (deftrue('BOOTSTRAP')) ? $tp->toGlyph('fa-chevron-left') : '&lt;&lt;';
	    	return isset($userjump['prev']['id']) ? "<a class='".$class."' href='".$url->create('user/profile/view', $userjump['prev']) ."' title=\"".$userjump['prev']['name']."\">".$icon." ".LAN_USER_40."</a>\n" : "&nbsp;";
		
			// return isset($userjump['prev']['id']) ? "&lt;&lt; ".LAN_USER_40." [ <a href='".$url->create('user/profile/view', $userjump['prev'])."'>".$userjump['prev']['name']."</a> ]" : "&nbsp;";
		
		}
		else
		{
			$icon = (deftrue('BOOTSTRAP')) ? $tp->toGlyph('fa-chevron-right') : '&gt;&gt;';
			return isset($userjump['next']['id']) ? "<a class='".$class."' href='".$url->create('user/profile/view', $userjump['next'])."' title=\"".$userjump['next']['name']."\">".LAN_USER_41." ".$icon."</a>\n" : "&nbsp;";
			// return isset($userjump['next']['id']) ? "[ <a href='".$url->create('user/profile/view', $userjump['next'])."'>".$userjump['next']['name']."</a> ] ".LAN_USER_41." &gt;&gt;" : "&nbsp;";
		}
	}
	

	function sc_user_photo($parm=null)
	{
		$row = array('user_image'=>$this->var['user_sess']);

		return e107::getParser()->toAvatar($row, $parm);
	}

	
	function sc_user_picture($parm=null)
	{
		return e107::getParser()->toAvatar($this->var, $parm);

		/*

		return $tp->parseTemplate("{USER_AVATAR=".$this->var['user_sess']."}",true);
		
		if ($this->var['user_sess'] && file_exists(e_MEDIA."avatars/".$this->var['user_sess']))
		{
			//return $tp->parseTemplate("{USER_AVATAR=".$this->var['user_image']."}", true); // this one will resize. 
			 return "<img src='".e_UPLOAD_ABS."public/avatars/".$this->var['user_sess']."' alt='' />";
		}
		else
		{
			return LAN_USER_42;
		}*/
	}
	
	/*  sc_USER_AVATAR - see single/user_avatar.php */ 
		
		
	function sc_user_picture_name($parm=null)
	{
		if (ADMIN && getperms("4"))
		{
			return $this->var['user_sess'];
		}
	}
	
	
	function sc_user_picture_delete($parm=null)
	{
		if (USERID == $this->var['user_id'] || (ADMIN && getperms("4")))
		{
			return "
			<form method='post' action='".e_SELF."?".e_QUERY."'>
			<input class='btn btn-default btn-secondary button' type='submit' name='delp' value='".LAN_USER_43."' />
			</form>
			";
		}
	}

	/**
	 * @example: {USER_USERCLASS_ICON} returns the icons of all userclasses the user belongs to, seperated by a whitespace
	 * @example: {USER_USERCLASS_ICON: amount=1} // returns only one icon
	 * @example: {USER_USERCLASS_ICON: seperator=|} // returns the icons seperated by |
	 * @param array $parm
	 * @return string
	*/

	function sc_user_userclass_icon($parm = null)
	{
		$icons 	= array();
		$i 		= 0;
		$amount = 0;

		if(isset($parm['amount']))
		{
			$amount	= intval($parm['amount']);
		}

		// Get all userclasses that the user belongs to (comma separated)
		$userclasses = explode(',', $this->var['user_class']);
		//print_a($userclasses);
 		
 		// Loop through userclasses
		foreach($userclasses as $userclass)
		{
			// Break the foreach if we have reached the maximum amount of icons to return (set by shortcode)
			if($i === $amount)
			{
				break;
			}

			// Retrieve icon path for each userclass
			$icon_path 	= e107::getUserClass()->uc_get_classicon($userclass); 
			//print_a($icon_path);
			
			// Check if icon path is set, and if so, add to $icons array
			if($icon_path)
			{
				// Use parser to transform path into html
				$icons[] = e107::getParser()->toIcon($icon_path);
				$i++;
			}
		}

		$separator = varset($parm['separator'], " "); // default separater is a whitespace

		// Return all icons in html format
		return implode($separator, $icons);
	}


	// v2.x extended user field data.
	/**
	 * Usage {USER_EUF: field=xxxx} (excluding the 'user_' )
	 * @param string $parm
	 * @return string
	*/
	function sc_user_euf($parm=null)
	{
		if(!empty($parm['field']))
		{

			$ext = e107::getUserExt();

			$fld = 'user_'.$parm['field'];

			if(!$ext->hasPermission($fld,'read'))
			{
			//	e107::getDebug()->log("Wrong permissions for user_euf: ".$fld);
				return false;
			}
 
			$val = $this->var[$fld];
			$type = $ext->getFieldType($fld);

			return $ext->renderValue($val,$type, $fld);

		}

		return false;
	}


	function sc_user_extended_all($parm=null)
	{
		$sql = e107::getDb();
		$tp = e107::getParser();
		$frm = e107::getForm();

		if(THEME_LEGACY === true) // v1.x
        {
            global $EXTENDED_CATEGORY_START, $EXTENDED_CATEGORY_END, $EXTENDED_CATEGORY_TABLE;
        }
        else // v2.x
        {
            $template = e107::getCoreTemplate('user','extended');
            $EXTENDED_CATEGORY_START    = $template['start'];
            $EXTENDED_CATEGORY_END	    = $template['end'];
            $EXTENDED_CATEGORY_TABLE 	= $template['item'];;
        }

     /*
		$qry = "SELECT f.*, c.user_extended_struct_name AS category_name, c.user_extended_struct_id AS category_id FROM #user_extended_struct as f
			LEFT JOIN #user_extended_struct as c ON f.user_extended_struct_parent = c.user_extended_struct_id
			ORDER BY c.user_extended_struct_order ASC, f.user_extended_struct_order ASC
		";
		*/

		$ue = e107::getUserExt();
		$ueCatList = $ue->getCategories();
		$ueFieldList = $ue->user_extended_get_fields();


		
		$ueCatList[0][0] = array('user_extended_struct_name' => LAN_USER_44, 'user_extended_struct_text' => '');
		
	//	print_a($ueFieldList);
		
		$ret = "";
		foreach($ueCatList as $catnum => $cat)
		{
			$key = $cat[0]['user_extended_struct_text'] ? $cat[0]['user_extended_struct_text'] : $cat[0]['user_extended_struct_name'];
		//	$cat_name = $tp->parseTemplate("{USER_EXTENDED={$key}.text.{$this->var['user_id']}}", TRUE); //XXX FIXME Fails

		//	$cat_name = true; //XXX TEMP Fix.
			
			if(/*$cat_name != FALSE && */isset($ueFieldList[$catnum]) && count($ueFieldList[$catnum]))
			{
					
				$ret .= str_replace("{EXTENDED_NAME}", $key, $EXTENDED_CATEGORY_START);
				foreach($ueFieldList[$catnum] as $f)
				{
					
					$key = $f['user_extended_struct_name'];
					$field = 'user_'.$key; 
								
					if($ue->hasPermission($field) && $ue_name = $tp->parseTemplate("{USER_EXTENDED={$key}.text.{$this->var['user_id']}}", TRUE))
					{

						$extended_record = str_replace("EXTENDED_ICON","USER_EXTENDED={$key}.icon", $EXTENDED_CATEGORY_TABLE);
					 	$extended_record = str_replace("{EXTENDED_NAME}", $tp->toHTML($ue_name,"","defs"), $extended_record);
						$extended_record = str_replace("EXTENDED_VALUE","USER_EXTENDED={$key}.value.{$this->var['user_id']}", $extended_record);
						$extended_record = str_replace('{EXTENDED_ID}',$frm->name2id('user_'.$key), $extended_record);

						if(defined('HIDE_EMPTY_FIELDS') && HIDE_EMPTY_FIELDS === TRUE)
						{
							$this_value = $tp->parseTemplate("{USER_EXTENDED={$key}.value.{$this->var['user_id']}}", TRUE);

							if($this_value != "")
							{
								$ret .= $tp->parseTemplate($extended_record, TRUE);
							}
						}
						else
						{
							$ret .= $tp->parseTemplate($extended_record, TRUE);
						}
					}
				}
			}
			$ret .= $EXTENDED_CATEGORY_END;
		}
		return $ret;
	}

 

	/**
	 * @deprecated
	 * @param string $parm
	 */
	function sc_profile_comment_form($parm='') // deprecated. 
	{
		return;
	}
	
	
	
	function sc_total_users($parm=null)
	{
		global $users_total;
		return (int) $users_total;
	}
	
	
	function sc_user_form_records($parm=null)
	{
		global $records;

		$opts = array(5,10,20,30,50);
		return e107::getForm()->select('records', $opts, $records,'useValues=1');
	}
	

	function sc_user_form_order($parm=null)
	{
		global $order;

		if ($order == "ASC")
		{
			$ret = "<select name='order' class='form-control tbox'>
			<option value='DESC'>".LAN_USER_45."</option>
			<option value='ASC' selected='selected'>".LAN_USER_46."</option>
			</select>";
		}
		else
		{
			$ret = "<select name='order' class='form-control tbox'>
			<option value='DESC' selected='selected'>".LAN_USER_45."</option>
			<option value='ASC'>".LAN_USER_46."</option>
			</select>";
		}
		return $ret;
	}
	
	
	function sc_user_form_start($parm=null)
	{
		global $from;

		return "
		<form method='post' action='".e_SELF."'>
		<p><input type='hidden' name='from' value='$from' /></p>
		";
	}
	
	
	
	function sc_user_form_end($parm=null)
	{
		return "</form>";
	}


	function sc_user_form_submit($parm=null)
	{
		return "<input class='btn btn-default btn-secondary button' type='submit' name='submit' value='".LAN_GO."' />";
	}


	function sc_user_addons($parm=null)
	{
		$template 	= e107::getCoreTemplate('user','addon');
		$tp 		= e107::getParser();
		$data 		= e107::getAddonConfig('e_user',null,'profile',$this->var);
		
		if(empty($data))
		{
			return;
		}
		
		$text = '';	
			
		foreach($data as $plugin=>$val)
		{
			foreach($val as $v)
			{
				$value = vartrue($v['url']) ? "<a href=\"".$v['url']."\">".$v['text']."</a>" : $v['text'];		

				$array = array(
					'USER_ADDON_LABEL' => $v['label'],
					'USER_ADDON_TEXT' => $value
				);

				$text .= $tp->parseTemplate($template, true, $array);
			}		
				
		}
			
		return $text;			
	}


 
	
	
	function sc_user_customtitle($parm=null)
	{
		return $this->var['user_customtitle'];
	}

}
