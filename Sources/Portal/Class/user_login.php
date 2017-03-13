<?php
/**
 * PortaMx Forum
 * @package PortaMx
 * @author PortaMx
 * @copyright 2017 PortaMx
 *
 * file user_login.php
 * Systemblock user_login
 *
 * @version 1.0 RC1
 */

if(!defined('PMX'))
	die('This file can\'t be run without PortaMx-Forum');

/**
* @class pmxc_user_login
* Systemblock user_login
* @see user_login.php
*/
class pmxc_user_login extends PortaMxC_SystemBlock
{
	/**
	* InitContent.
	*/
	function pmxc_InitContent()
	{
		global $user_info, $modSettings, $txt;

		if(!checkECL_Cookie())
			$this->visible = false;

		if($this->visible)
		{
			// show current time as realtime?
			if(!empty($this->cfg['config']['settings']['show_time']) && !empty($this->cfg['config']['settings']['show_realtime']))
			{
				$cdate = date('Y,n-1,j,G,', Forum_Time()) . intval(date('i', Forum_Time())) .','. intval(date('s', Forum_Time()));

				if(empty($modSettings['pmxUserLoginLoaded']))
					addInlineJavascript('
	var pmx_rtcFormat = {};');

				if(empty($this->cfg['config']['settings']['rtc_format']))
					addInlineJavascript('
	pmx_rtcFormat['. $this->cfg['id'] .'] = "'. (empty($user_info['time_format']) ? $modSettings['time_format'] : $user_info['time_format']) .'";');
				else
					addInlineJavascript('
	pmx_rtcFormat['. $this->cfg['id'] .'] = "'. $this->cfg['config']['settings']['rtc_format'] .'";');

				if(empty($modSettings['pmxUserLoginLoaded']))
				{
					addInlineJavascript('
	var pmx_rctMonths = new Array("'. implode('","', $txt['months']) .'");
	var pmx_rctShortMonths = new Array("'. implode('","', $txt['months_short']) .'");
	var pmx_rctDays = new Array("'. implode('","', $txt['days']) .'");
	var pmx_rctShortDays = new Array("'. implode('","', $txt['days_short']) .'");
	var pmx_rtcFormatTypes = new Array("%a", "%A", "%d", "%b", "%B", "%m", "%Y", "%y", "%H", "%I", "%M", "%S", "%p", "%%", "%D", "%e", "%R", "%T");
	var pmx_rtcOffset = new Date('. $cdate .') - new Date();');

					loadJavascriptFile(PortaMx_loadCompressed('PortalUser.js'), array('external' => true));
					$modSettings['pmxUserLoginLoaded'] = true;
				}
			}
		}

		// return the visibility flag (true/false)
		return $this->visible;
	}

	/**
	* ShowContent
	* Output the content.
	*/
	function pmxc_ShowContent()
	{
		global $context, $scripturl, $txt;

		// User logged in?
		if($context['user']['is_logged'])
		{
			// avatar
			if(!empty($context['user']['avatar']) && !empty($this->cfg['config']['settings']['show_avatar']))
			{
				$avasize = getimagesize($context['user']['avatar']['href']);
				$fact = $avasize[0] > $avasize[1] ? (50 / $avasize[0]) : (50 / $avasize[1]);
				$leftpad = strval(intval($avasize[0] * $fact + 8));
				echo '
									<div style="padding-left:'. $leftpad .'px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"' .(isset($this->cfg['config']['visuals']['hellotext']) ? ' class="'. $this->cfg['config']['visuals']['hellotext'] .'"' : ''). '>'.
										$txt['pmx_hello'] .'<br /><a href="'. $scripturl .'?action=profile;u='. $context['user']['id'] .'" title="'. $context['user']['name'] .'"><b>'. $context['user']['name'] .'</b></a>
									</div>';
				// avatar
				if(!empty($context['user']['avatar']) && !empty($this->cfg['config']['settings']['show_avatar']))
					echo '
									<div style="float:left;margin-top:-17px;">
										<a style="float:left;margin-top:-20px;" class="pmx_avatar" href="'. $scripturl .'?action=profile;u='. $context['user']['id'] .'" title="'. $context['user']['name'] .'">'. $context['user']['avatar']['image'] .'</a>
									</div>';
			}
			else
				echo '
									<div' .(isset($this->cfg['config']['visuals']['hellotext']) ? 'block;" class="'. $this->cfg['config']['visuals']['hellotext'] .'"' : ''). '>'.
										$txt['pmx_hello'] .'<a href="'. $scripturl .'?action=profile;u='. $context['user']['id'] .'"><b>'. $context['user']['name'] .'</b></a>
									</div>';

			$img = '<img src="'. $context['pmx_syscssurl'].'Images/bullet_blue.gif" alt="*" title="" />';
			$img1 = '<img src="'. $context['pmx_syscssurl'].'Images/bullet_red.gif" alt="*" title="" />';

			if(!empty($context['user']['avatar']) && !empty($this->cfg['config']['settings']['show_avatar']))
				echo '
									<ul style="margin-top:22px;" class="userlogin">';
			else
				echo '
									<ul class="userlogin smalltext">';

			// show pm?
			if(!empty($this->cfg['config']['settings']['show_pm']) && $context['allow_pm'])
				echo '
										<li>'.($context['user']['unread_messages'] > 0 ? $img1 : $img).'<span><a href="'. $scripturl .'?action=pm">'. $txt['pmx_pm'] .($context['user']['unread_messages'] > 0 ? ': '.$context['user']['unread_messages'].' <img src="'. $context['pmx_imageurl'].'newpm.gig" alt="*" title="'. $context['user']['unread_messages'] .'" />' : '').'</a></span></li>';

			// Are there any members waiting for approval?
			if(!empty($this->cfg['config']['settings']['show_unapprove']) && !empty($context['unapproved_members']))
				echo '
										<li>'.$img1.'<span><a href="'. $scripturl .'?action=admin;area=viewmembers;sa=browse;type=approve">'. $txt['pmx_unapproved_members'] .' <b>'. $context['unapproved_members']  .'</b></a></span></li>';

			// show post?
			if(!empty($this->cfg['config']['settings']['show_posts']))
			{
				echo '
										<li>'.$img.'<span><a href="'. $scripturl .'?action=unread">'. $txt['pmx_unread'] .'</a></span></li>
										<li>'.$img.'<span><a href="'. $scripturl .'?action=unreadreplies">'. $txt['pmx_replies'] .'</a></span></li>
										<li>'.$img.'<span><a href="'. $scripturl .'?action=profile;area=showposts;u='. $context['user']['id'] .'">'. $txt['pmx_showownposts'] .'</a></span></li>';
			}
			echo '
									</ul>';

			// Is the forum in maintenance mode?
			if($context['in_maintenance'] && $context['user']['is_admin'])
				echo '
									<b>'. $txt['pmx_maintenace'] .'</b><br />';

			// Show the total time logged in?
			if(!empty($context['user']['total_time_logged_in']) && isset($this->cfg['config']['settings']['show_logtime']) && $this->cfg['config']['settings']['show_logtime'] == 1)
			{
				$totm = $context['user']['total_time_logged_in'];
				$form = '%s: %s%s %s%s %s%s';
				echo sprintf($form, $txt['pmx_loggedintime'], $totm['days'], $txt['pmx_Ldays'], $totm['hours'], $txt['pmx_Lhours'], $totm['minutes'], $txt['pmx_Lminutes']);
				echo '<br />';
			}
		}

		// Otherwise they're a guest, ask them to register or login.
		else
		{
			if(!empty($this->cfg['config']['settings']['show_login']) && checkECL_Cookie(true))
			{
				echo '
									<div style="padding-top:4px;">
										<form action="', $scripturl, '?action=login2;quicklogin" method="post" accept-charset="', $context['character_set'], '">
											<input id="username" type="text" name="user" size="10" class="input_text" style="width:48%;float:left;margin-bottom:3px;" value="'. $txt['pmxdummyuser'] .'" onclick="usercheck(this)" />
											<input type="password" name="passwrd" size="10" class="input_password" value="" style="width:48%;float:right;margin-bottom:3px;margin-right:4px;" />
											<select name="cookielength">
												<option value="60">', $txt['one_hour'], '</option>
												<option value="1440">', $txt['one_day'], '</option>
												<option value="10080">', $txt['one_week'], '</option>
												<option value="43200">', $txt['one_month'], '</option>
												<option value="-1" selected="selected">', $txt['forever'], '</option>
											</select>
											<input type="hidden" name="hash_passwrd" value="" />
											<input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
											<input type="hidden" name="', $context['login_token_var'], '" value="', $context['login_token'], '" />
											<input style="float:right;margin-right:4px;" type="submit" value="'. $txt['login'] .'" />
										</form>
										<script type="text/javascript">function usercheck(elm){if(elm.value=="'. $txt['pmxdummyuser'] .'"){elm.value = "";}}</script>
									'. $txt['login_dec'] .'
									</div>';
			}
		}

		// show current time?
		if(!empty($this->cfg['config']['settings']['show_time']))
		{
			if(!empty($this->cfg['config']['settings']['show_realtime']))
			{
				$cdate = date('Y,n-1,j,G,', Forum_Time()) . intval(date('i', Forum_Time())) .','. intval(date('s', Forum_Time()));
				echo '
								<span id="ulClock'. $this->cfg['id'] .'"></span>
								<script type="text/javascript">
								ulClock("'. $this->cfg['id'] .'");
								</script>';
			}
			else
				echo $context['current_time'];
		}

		// show logout button?
		if($context['user']['is_logged'] && !empty($this->cfg['config']['settings']['show_logout']))
			echo '
								<br />
								<div style="text-align:center;margin-top:5px;">
									<input class="button_submit" type="button" value="'. $txt['logout'] .'" onclick="DoLogout()" />
								</div>
								<script type="text/javascript">
									function DoLogout()
									{
										window.location = "'. $scripturl .'?action=logout;'. $context['session_var'] .'='. $context['session_id'] .'";
									}
								</script>';


		// show a language dropdown selector
		if(!empty($this->cfg['config']['settings']['show_langsel']) && count($context['pmx']['languages']) > 1)
		{
			echo '
								<script type="text/javascript">
									if(document.getElementById("languages_form"))
									{
										document.getElementById("languages_form").style.display = "none";
										if(document.getElementsByName("search2"))
											document.getElementsByName("search2")[0].style.marginRight = "0";
									}
								</script>';

			if(isset($_GET['language']))
				unset($_GET['language']);

			echo '
								<form id="pmxlangchg'. $this->cfg['id'] .'" accept-charset="'. $context['character_set'] .'" method="post">
									<input type="hidden" id="pmxlangval'. $this->cfg['id'] .'" name="language" value="" />
								</form>
								<hr class="pmx_hr" />'. $txt['pmx_langsel'] .'
								<div style="padding-top:3px;">
									<select size="1" style="width:100%;" onchange="pmxSetlang(this, \''. $this->cfg['id'] .'\')">';

			foreach($context['pmx']['languages'] as $lang => $sel)
				echo '
										<option value="'. $lang .'"'. (!empty($sel) ? ' selected="selected"' : '') .'>'. ucfirst(strpos($lang, '-') !== false ? substr($lang, 0, strpos($lang, '-')) : $lang) .'</option>';

			echo '
									</select>
								</div>';
		}
	}
}
?>