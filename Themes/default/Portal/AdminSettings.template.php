<?php
/**
 * PortaMx Forum
 * @package PortaMx
 * @author PortaMx
 * @copyright 2017 PortaMx
 *
 * file AdminSettings.template.php
 * Template for the Settings Manager.
 *
 * @version 1.0 RC3
 */

/**
* The main Subtemplate.
*/
function template_main()
{
	global $context, $modSettings, $txt, $scripturl, $PortaMx_cache;

	$curSUB = $context['pmx']['subaction'] = isset($_REQUEST['sa']) ? $_REQUEST['sa'] : 'globals';
	if(allowPmx('pmx_admin', true))
	{
		$context['pmx_cancel_link'] = $scripturl . '?action=portal;area=pmx_center;'. $context['session_var'] .'=' .$context['session_id'];

		$MenuTabs = array(
			'globals' => $txt['pmx_admSet_globals'],
			'panels' => $txt['pmx_admSet_panels'],
			'control' => $txt['pmx_admSet_control'],
			'access' => $txt['pmx_admSet_access'],
		);

		$Descriptions = array(
			'globals' => $txt['pmx_admSet_desc_globals'],
			'panels' => $txt['pmx_admSet_desc_panels'],
			'control' => $txt['pmx_admSet_desc_control'],
			'access' => $txt['pmx_admSet_desc_access'],
		);

		if(allowPmx('pmx_admin', true))
			echo '
					<div class="cat_bar"><h3 class="catbg">'. $txt['pmx_adm_settings'] .'</h3></div>
					<p class="information portal_info">'. $Descriptions[$context['pmx']['subaction']] .'</p>';

		if(allowPmx('pmx_admin', true))
		{
			echo '
						<div style="min-height:25px;margin-top:-2px;">
							<a class="menu_icon mobile_generic_menu_settings"></a>
							<div class="generic_menu">
								<ul class="dropmenu sf-js-enabled">';

			foreach($MenuTabs as $name => $desc)
				echo '
									<li id="'. $name .'" class="subsections">
										<a class="firstlevel'. ($name == $curSUB ? ' active' : '') .'" href="'. $scripturl .'?action='. $context['pmx']['AdminMode'] .';area=pmx_settings;sa='. $name .';'. $context['session_var'] .'='. $context['session_id'] .';#ptop">
											<span class="firstlevel">'. $desc .'</span>
										</a>
									</li>';

			echo '
								</ul>
							</div>
						</div>';
		}

		echo '
					<div id="mobile_generic_menu_settings" class="popup_container">
						<div class="popup_window description">
							<div class="popup_heading">', $txt['pmx_allsettings'] ,'<a href="javascript:void(0);" class="generic_icons hide_popup"></a></div>
							<div class="generic_menu">
								<ul class="dropmenu sf-js-enabled">';

		foreach($MenuTabs as $name => $desc)
			echo '
								<li id="'. $name .'" class="subsections">
									<a class="firstlevel'. ($name == $curSUB ? ' active' : '') .'" href="'. $scripturl .'?action='. $context['pmx']['AdminMode'] .';area=pmx_settings;sa='. $name .';'. $context['session_var'] .'='. $context['session_id'] .';" onclick="pmxWinGetTop(\'adm\',\'set\')">
										<span class="firstlevel">'. $desc .'</span>
									</a>
								</li>';

		echo '
								</ul>
							</div>';

		echo '
						</div>
					</div>
					<script>
						$(".mobile_generic_menu_settings" ).click(function(){$("#mobile_generic_menu_settings" ).show();});
						$(".hide_popup" ).click(function(){$( "#mobile_generic_menu_settings" ).hide();});
					</script>';
	}
	else
		$context['pmx_cancel_link'] = $scripturl . '?action=admin;area=pmx_center;'. $context['session_var'] .'=' .$context['session_id'];

	if (isset($_SESSION['saved_successful']))
	{
		unset($_SESSION['saved_successful']);
		echo '
		<div class="infobox">', $txt['settings_saved'], '</div>';
	}

	$admset = $context['pmx']['settings'];
	echo '
	<form id="pmx_form" accept-charset="', $context['character_set'], '" name="PMxAdminSettings" action="' . $scripturl . '?action='. $context['pmx']['AdminMode'] .';area='. $_REQUEST['area'] . (!empty($context['pmx']['subaction']) ? ';sa='. $curSUB : '') .';'. $context['session_var'] .'=' .$context['session_id'] .'" method="post" style="margin:0px;display:block;">
		<input type="hidden" name="sc" value="', $context['session_id'], '" />
		<input id="common_field" type="hidden" value="" />';

	if($context['pmx']['subaction'] == 'globals')
	{
		// define numeric vars to check
		echo '
		<input type="hidden" name="check_num_vars[]" value="[left_panel][size], 170" />
		<input type="hidden" name="check_num_vars[]" value="[right_panel][size], 170" />
		<input type="hidden" name="check_num_vars[]" value="[panels][padding], 4" />';

		// Global settings
		echo '
		<table class="pmx_table pmx_fixedtable" style="margin-bottom:5px;">
			<tr>
				<td style="text-align:center">
					<div class="cat_bar">
						<h3 class="catbg">', $txt['pmx_global_settings'], '</h3>
					</div>
				</td>
			</tr>
			<tr>
				<td>
					<div class="information portal_info">
					<table class="pmx_table">
						<tr>
							<td style="padding:5px;width:50%">
								<div style="min-height:25px;">'. $txt['pmx_settings_frontpage_centered'] .'</div>
								<div style="min-height:25px;">'. $txt['pmx_settings_frontpage_none'] .'</div>
							</td>
							<td style="padding:5px;width:50%;">
								<div style="float:left; width:28px;">
									<div style="min-height:25px;margin-left:-4px;">
										<input class="input_radio" type="radio" name="frontpage" value="centered"'. (!isset($admset['frontpage']) || isset($admset['frontpage']) && $admset['frontpage'] == 'centered' ? ' checked="checked"' : '') .' />
									</div>
									<div style="min-height:25px;margin-left:-4px;">
										<input class="input_radio" type="radio" name="frontpage" value="none"'. (isset($admset['frontpage']) && $admset['frontpage'] == 'none' ? ' checked="checked"' : '') .' />
									</div>
								</div>
							</td>
						</tr>
						<tr>
							<td style="padding:5px;width:50%">
								<div style="min-height:25px;">
									<a href="', $scripturl, '?action=helpadmin;help=pmx_settings_index_front_help" onclick="return reqOverlayDiv(this.href);" class="help"><span class="generic_icons help" title="', $txt['help'],'"></span></a>
									<span>'. $txt['pmx_settings_index_front'] .'</span>
								</div>
							</td>
							<td style="padding:5px;width:50%;">
								<div style="float:left; width:28px;">
									<div style="min-height:25px; margin-left:-4px;">
										<input type="hidden" name="indexfront" value="0" />
										<input class="input_check" type="checkbox" name="indexfront" value="1"'. (!empty($admset['indexfront']) ? ' checked="checked"' : '') .' />
									</div>
								</div>
							</td>
						</tr>
						<tr>
							<td style="padding:5px;width:50%">
								<div>
									<a href="', $scripturl, '?action=helpadmin;help=pmx_settings_pages_help" onclick="return reqOverlayDiv(this.href);" class="help"><span class="generic_icons help" title="', $txt['help'],'"></span></a>
									<span>'. $txt['pmx_settings_pages_hidefront'] .'</span>
								</div>
							</td>
							<td style="padding:5px;width:50%;">
								<div>
									<textarea class="adm_textarea adm_w90" rows="1" cols="50" name="hidefrontonpages" style="min-height:10px; max-height:100px;">'. $admset['hidefrontonpages'] .'</textarea>
								</div>
							</td>
						</tr>
						<tr>
							<td style="padding:10px 5px 0 5px;width:50%;">
								<div>
									<a href="', $scripturl, '?action=helpadmin;help=pmx_settings_downloadhelp" onclick="return reqOverlayDiv(this.href);" class="help"><span class="generic_icons help" title="', $txt['help'],'"></span></a>
									<span>'. $txt['pmx_settings_download'] .'</span>
								</div>
							</td>
							<td style="padding:10px 5px 0 5px;width:50%;">
								<div style="margin-left:-4px;">
									<input type="hidden" name="download" value="0" />
									<input onchange="chk_dlbutton(this)" style="float:left;" class="input_check" type="checkbox" name="download" value="1"'. (!empty($admset['download']) ? ' checked="checked"' : '') .' />
								</div>
							</td>
						</tr>
						<tr id="dlbutchk1" style="display:'. (!empty($admset['download']) ? '' : 'none;') .'">
							<td style="padding:5px 5px 0 5px;width:50%;">
								<div>
									<a href="', $scripturl, '?action=helpadmin;help=pmx_settings_dl_actionhelp" onclick="return reqOverlayDiv(this.href);" class="help"><span class="generic_icons help" title="', $txt['help'],'"></span></a>
									<span>'. $txt['pmx_settings_download_action'] .'</span>
								</div>
							</td>
							<td style="padding:5px 5px 0 5px;width:50%;">
								<div style="min-height:25px;">
									<input class="adm_w80" type="text" name="dl_action" value="'. (!empty($admset['dl_action']) ? $admset['dl_action'] : '') .'" />
								</div>
							</td>
						</tr>
						<tr id="dlbutchk2" style="display:'. (!empty($admset['download']) ? '' : 'none;') .'">
							<td style="padding:5px;width:50%;">
								<div>'. $txt['pmx_settings_download_acs'] .'</div>
							</td>
							<td style="padding:10px 5px 0 5px;width:50%;">
								<select style="width:50%;" name="dl_access[]" size="5" multiple="multiple">';

		$dlaccess = !empty($admset['dl_access']) ? explode(',', $admset['dl_access']) : array();
		foreach($context['pmx']['acsgroups'] as $group)
			if($group['id'] != 1)
				echo '
									<option value="'. $group['id'] .'=1"'. (in_array($group['id'] .'=1', $dlaccess) ? ' selected="selected"' : '') .'>'. $group['name'] .'</option>';

		echo '
								</select>
								<script type="text/javascript">
									function chk_dlbutton(elm) {
										document.getElementById("dlbutchk1").style.display = (elm.checked == true ? "" : "none");
										document.getElementById("dlbutchk2").style.display = (elm.checked == true ? "" : "none");
									}
								</script>
							</td>
						</tr>
						<tr>
							<td style="padding:10px 5px 0 5px;">
								<div>
									<a href="', $scripturl, '?action=helpadmin;help=pmx_settings_other_actionshelp" onclick="return reqOverlayDiv(this.href);" class="help"><span class="generic_icons help" title="', $txt['help'],'"></span></a>
									<span>'. $txt['pmx_settings_other_actions'] .'</span>
								</div>
							</td>
							<td style="padding:10px 5px 0 5px;width:50%;">
								<div style="min-height:25px;">
									<input style="min-height:25px;" class="adm_w80" type="text" name="other_actions" value="'. (!empty($admset['other_actions']) ? $admset['other_actions'] : '') .'" />
								</div>
							</td>
						</tr>
						<tr>
							<td style="padding:10px 5px 0 5px;">
								<div style="min-height:25px;">
									<a href="', $scripturl, '?action=helpadmin;help=pmx_settings_panelpadding_help" onclick="return reqOverlayDiv(this.href);" class="help"><span class="generic_icons help" title="', $txt['help'],'"></span></a>
									<span>'. $txt['pmx_settings_panelpadding'] .'</span>
								</div>
							</td>
							<td style="padding:10px 5px 0 5px;width:50%;">
								<div style="min-height:25px;">
									<input onkeyup="check_numeric(this);" type="text" size="2" name="panelpad" value="'. (isset($admset['panelpad']) ? $admset['panelpad'] : '4') .'" />&nbsp;'. $txt['pmx_pixel'] .'
								</div>
							</td>
						</tr>
						<tr>
							<td style="padding:10px 5px 0 5px;width:50%;">
								<div>
									<a href="', $scripturl, '?action=helpadmin;help=pmx_settings_restoretop_help" onclick="return reqOverlayDiv(this.href);" class="help"><span class="generic_icons help" title="', $txt['help'],'"></span></a>
									<span>'. $txt['pmx_settings_restoretop'] .'</span>
								</div>
							</td>
							<td style="padding:10px 5px 0 5px;width:50%;">
								<div style="margin-left:-4px;">
									<input type="hidden" name="restoretop" value="0" />
									<input id="rstTop" class="input_check" type="checkbox" name="restoretop" value="1"'. (!empty($admset['restoretop']) ? ' checked="checked"' : '') .' onclick="chk_restore()" />
								</div>
							</td>
						</tr>

						<tr>
							<td style="padding:10px 5px 0 5px;width:50%;">
								<div>
									<a href="', $scripturl, '?action=helpadmin;help=pmx_settings_colminwidth_help" onclick="return reqOverlayDiv(this.href);" class="help"><span class="generic_icons help" title="', $txt['help'],'"></span></a>
									<span>'. $txt['pmx_settings_colminwidth'] .'</span>
								</div>
							</td>
							<td style="padding:10px 5px 0 5px;width:50%;">
								<div>
									<input type="text" name="colminwidth" size="2" class="input_text" value="'. (isset($admset['colminwidth']) ? $admset['colminwidth'] : '') .'" />&nbsp;'. $txt['pmx_pixel'] .'
								</div>
							</td>
						</tr>

						<tr>
							<td style="padding:10px 5px 0 5px;width:50%;">
								<div>
									<a href="', $scripturl, '?action=helpadmin;help=pmx_settings_loadinactive_help" onclick="return reqOverlayDiv(this.href);" class="help"><span class="generic_icons help" title="', $txt['help'],'"></span></a>
									<span>'. $txt['pmx_settings_loadinactive'] .'</span>
								</div>
							</td>
							<td style="padding:10px 5px 0 5px;width:50%;">
								<div style="margin-left:-4px;">
									<input type="hidden" name="loadinactive" value="0" />
									<input class="input_check" type="checkbox" name="loadinactive" value="1"'. (!empty($admset['loadinactive']) ? ' checked="checked"' : '') .' />
								</div>
							</td>
						</tr>

						<tr>
							<td style="padding:15px 5px 0 5px;width:50%;">
								<script type="text/javascript">
									function chk_restore() {
										if(document.getElementById("rstTop").checked == true)
											document.getElementById("setspeed").style.display = "";
										else
											document.getElementById("setspeed").style.display = "none";
									}
									chk_restore();
								</script>
								<div>
									<a href="', $scripturl, '?action=helpadmin;help=pmx_settings_pmxteasecnthelp" onclick="return reqOverlayDiv(this.href);" class="help"><span class="generic_icons help" title="', $txt['help'],'"></span></a>
									<span>'. $txt['pmx_settings_teasermode'][0] .'</span>
								</div>
							</td>
							<td style="padding:15px 5px 0 5px;width:50%;">
								<div style="min-height:25px; float:left; margin-left:-4px;">
									<input type="hidden" name="teasermode" value="0" />
									<div><input class="input_check" type="radio" name="teasermode" value="0"'. (empty($admset['teasermode']) ? ' checked="checked"' : '') .' />&nbsp;'. $txt['pmx_settings_teasermode'][1] .'</div>
									<div><input class="input_check" type="radio" name="teasermode" value="1"'. (!empty($admset['teasermode']) ? ' checked="checked"' : '') .' />&nbsp;'. $txt['pmx_settings_teasermode'][2] .'</div>
								</div>
							</td>
						</tr>';

		if(!empty($PortaMx_cache['vals']['mode']))
			echo '
						<tr>
							<td style="padding:5px 5px 0 5px;width:50%;">
								<div>
									<a href="', $scripturl, '?action=helpadmin;help=pmx_settings_blockcachestatshelp" onclick="return reqOverlayDiv(this.href);" class="help"><span class="generic_icons help" title="', $txt['help'],'"></span></a>
									<span>'. $txt['pmx_settings_blockcachestats'] .'</span>
								</div>
							</td>
							<td style="padding:5px 5px 0 5px;width:50%;">
								<div style="margin-left:-4px;">
									<input type="hidden" name="cachestats" value="0" />
									<input style="float:left;" class="input_check" type="checkbox" name="cachestats" value="1"'. (!empty($admset['cachestats']) ? ' checked="checked"' : '') .' />
									<span style="padding-left:5px;vertical-align:-1px">'. $txt['pmx_settings_blockcachedetect'] .' <b>'. $txt['cachemode'][$PortaMx_cache['vals']['mode']] .'</b></span>
								</div>
							</td>
						</tr>';

		echo '
						<tr>
							<td style="padding:10px 5px 0 5px;width:50%;">
								<div>
									<a href="', $scripturl, '?action=helpadmin;help=pmx_settings_postcountacshelp" onclick="return reqOverlayDiv(this.href);" class="help"><span class="generic_icons help" title="', $txt['help'],'"></span></a>
									<span>'. $txt['pmx_settings_postcountacs'] .'</span>
								</div>
							</td>
							<td style="padding:10px 5px 0 5px;width:50%;">
								<div style="margin-left:-4px;">
									<input type="hidden" name="postcountacs" value="0" />
									<input style="float:left;" class="input_check" type="checkbox" name="postcountacs" value="1"'. (!empty($admset['postcountacs']) ? ' checked="checked"' : '') .' />
								</div>
							</td>
						</tr>
						<tr>
							<td style="padding:10px 5px 0 5px;width:50%;">
								<div>
									<a href="', $scripturl, '?action=helpadmin;help=pmx_settings_xbarkeys_help" onclick="return reqOverlayDiv(this.href);" class="help"><span class="generic_icons help" title="', $txt['help'],'"></span></a>
									<span>'. $txt['pmx_settings_enable_xbarkeys'] .'</span>
								</div>
							</td>
							<td style="padding:10px 5px 0 5px;width:50%;">
								<div style="margin-left:-4px;">
									<input type="hidden" name="xbarkeys" value="0" />
									<input style="float:left;" class="input_check" type="checkbox" name="xbarkeys" value="1"'. (!empty($admset['xbarkeys']) ? ' checked="checked"' : '') .' />
								</div>
							</td>
						</tr>
						<tr>
							<td style="padding:5px 5px 0 5px;width:50%;">
								<div>
									<a href="', $scripturl, '?action=helpadmin;help=pmx_settings_xbars_help" onclick="return reqOverlayDiv(this.href);" class="help"><span class="generic_icons help" title="', $txt['help'],'"></span></a>
									<span>'. $txt['pmx_settings_enable_xbars'] .'</span>
								</div>
							</td>
							<td style="padding:5px 5px 0 5px;width:50%;">
								<div style="height:25px;">
									<img id="pmxTXB" class="adm_hover" onclick="ToggleCheckbox(this, \'xsel\', 0)" width="13" height="13" style="float:left;margin-top:5px;" src="'. $context['pmx_syscssurl'] .'Images/bullet_plus.gif" alt="*" title="'.$txt['pmx_settings_all_toggle'].'" />
								</div>
								<input type="hidden" name="xbars[]" value="" />';

		foreach($txt['pmx_block_sides'] as $side => $sidename)
		{
			if($side != 'front' && $side != 'pages')
			{
				echo '
								<div class="adm_clear" style="height:25px;margin-left:-4px;">
									<input id="xsel'.$side.'" class="input_check" type="checkbox" name="xbars[]" value="'. $side .'"'. (isset($admset['xbars']) && in_array($side, $admset['xbars']) ? ' checked="checked"' : '') .' />&nbsp;<span style="vertical-align:2px;">'. $sidename .'</span>
								</div>';
			}
		}

		echo '
								<script type="text/javascript">
									ToggleCheckbox(document.getElementById("pmxTXB"), \'xsel\', 1)
								</script>
							</td>
						</tr>
						<tr>
							<td style="padding:15px 5px 0 5px;width:50%;">
								<div style="min-height:25px;">'. $txt['pmx_settings_xbar_topoffset'] .'</div>
							</td>
							<td style="padding:15px 5px 0 5px;width:50%;">
								<div style="min-height:25px;">
									<input onkeyup="check_numeric(this);" type="text" size="2" name="xbaroffset_top" value="'. (isset($admset['xbaroffset_top']) ? $admset['xbaroffset_top'] : '40') .'" />&nbsp;'. $txt['pmx_pixel'] .'
								</div>
							</td>
						</tr>
						<tr>
							<td style="padding:5px 5px 0 5px;width:50%;">
								<div style="min-height:25px;">'. $txt['pmx_settings_xbar_botoffset'] .'</div>
							</td>
							<td style="padding:5px 5px 0 5px;width:50%;">
								<div style="min-height:25px;">
									<input onkeyup="check_numeric(this);" type="text" size="2" name="xbaroffset_foot" value="'. (isset($admset['xbaroffset_foot']) ? $admset['xbaroffset_foot'] : '5') .'" />&nbsp;'. $txt['pmx_pixel'] .'
								</div>
							</td>
						</tr>
					</table>
					<hr />
					<div style="text-align:right; margin:10px 0;">
						<input class="button_submit" style="float:none;" type="button" value="'. $txt['pmx_cancel'] .'" onclick=\'window.location.href="'. $context['pmx_cancel_link'] .'"\' />
						<input class="button_submit" style="float:none;" type="button" value="'. $txt['pmx_save'] .'" onclick="FormFunc(\'save_settings\', \'yes\')" />
					</div>
					</div>
				</td>
			</tr>
		</table>';
	}

	if($context['pmx']['subaction'] == 'control')
	{
		// Blockmanager control settings
		echo '
		<table class="pmx_table pmx_fixedtable" style="margin-bottom:5px;">
			<tr>
				<td style="text-align:center">
					<div class="cat_bar">
						<h3 class="catbg">'. $txt['pmx_global_program'] .'</h3>
					</div>
				</td>
			</tr>
			<tr>
				<td>
					<div class="information portal_info">
					<table class="pmx_table">
						<tr>
							<td style="padding:10px 5px 0 5px;width:50%;">
								<div style="min-height:25px;">
									<a href="', $scripturl, '?action=helpadmin;help=pmx_settings_collapse_vishelp" onclick="return reqOverlayDiv(this.href);" class="help"><span class="generic_icons help" title="', $txt['help'],'"></span></a>
									<span>'. $txt['pmx_settings_collapse_visibility'] .'</span>
								</div>
							</td>
							<td style="padding:10px 5px 0 5px;width:50%;">
								<div style="margin-left:-4px;">
									<input type="hidden" name="manager[collape_visibility]" value="0" />
									<input style="float:left;" class="input_check" type="checkbox" name="manager[collape_visibility]" value="1"'. (!empty($admset['manager']['collape_visibility']) ? ' checked="checked"' : '') .' />
								</div>
							</td>
						</tr>
						<tr>
							<td style="padding:1px 5px;width:50%;">
								<input type="hidden" name="manager[follow]" value="0" />
								<div style="min-height:25px;">
									<a href="', $scripturl, '?action=helpadmin;help=pmx_settings_quickedithelp" onclick="return reqOverlayDiv(this.href);" class="help"><span class="generic_icons help" title="', $txt['help'],'"></span></a>
									<span>'. str_replace('[##]', '<img style="vertical-align:-3px;" src="'. $context['pmx_imageurl'] .'page_edit.gif" alt="*" title="" />', $txt['pmx_settings_quickedit']) .'</span>
								</div>
							</td>
							<td style="padding:1px 5px;width:50%;">
								<div style="margin-left:-4px;">
									<input type="hidden" name="manager[qedit]" value="0" />
									<input style="float:left;" class="input_check" type="checkbox" name="manager[qedit]" value="1"'. (!empty($admset['manager']['qedit']) ? ' checked="checked"' : '') .' />
								</div>
							</td>
						</tr>

						<tr>
							<td style="padding:1px 5px;width:50%;">
								<div style="min-height:25px;">
									<a href="', $scripturl, '?action=helpadmin;help=pmx_settings_enable_promote_help" onclick="return reqOverlayDiv(this.href);" class="help"><span class="generic_icons help" title="', $txt['help'],'"></span></a>
									<span>'. $txt['pmx_settings_enable_promote'] .'</span>
								</div>
							</td>
							<td style="padding:1px 5px;width:50%;">
								<div style="margin-left:-4px;">
									<input type="hidden" name="manager[promote]" value="0" />
									<input style="float:left;" class="input_check" type="checkbox" name="manager[promote]" value="1"'. (!empty($admset['manager']['promote']) ? ' checked="checked"' : '') .' />
								</div>
							</td>
						</tr>
						<tr>
							<td style="padding:2px 5px; width:50%;">
								<div>
									<a href="', $scripturl, '?action=helpadmin;help=pmx_settings_promote_messages_help" onclick="return reqOverlayDiv(this.href);" class="help"><span class="generic_icons help" title="', $txt['help'],'"></span></a>
									<span>'. $txt['pmx_settings_promote_messages'] .'</span>
								</div>
							</td>
							<td style="padding:2px 5px; width:50%;">
								<div style="min-height:25px;">
									<textarea class="adm_textarea adm_w80" rows="1" cols="35" style="min-height:10px;max-height:150px;" name="promotes">'. implode(',', array_diff($context['pmx']['promotes'], array('0'))) .'</textarea>
								</div>
							</td>
						</tr>

						<tr>
							<td style="padding:1px 5px;width:50%;">
								<div style="min-height:25px;">
									<a href="', $scripturl, '?action=helpadmin;help=pmx_settings_article_on_pagehelp" onclick="return reqOverlayDiv(this.href);" class="help"><span class="generic_icons help" title="', $txt['help'],'"></span></a>
									<span>'. $txt['pmx_settings_article_on_page'] .'</span>
								</div>
							</td>
							<td style="padding:1px 5px;width:50%;">
								<div>
									<input style="float:left;" type="text" name="manager[artpage]" size="3" value="'. (!empty($admset['manager']['artpage']) ? $admset['manager']['artpage'] : '25') .'" />
								</div>
							</td>
						</tr>
						<tr>
							<td style="padding:1px 5px;width:50%;">
								<div style="height:25px;">
									<a href="', $scripturl, '?action=helpadmin;help=pmx_settings_adminpageshelp" onclick="return reqOverlayDiv(this.href);" class="help"><span class="generic_icons help" title="', $txt['help'],'"></span></a>
									<span>'. $txt['pmx_settings_adminpages'] .'</span>
								</div>
							</td>
							<td style="padding:1px 5px;width:50%;">
								<div style="height:25px;">
									<img id="pmxTMP" class="adm_hover" onclick="ToggleCheckbox(this, \'modsel\', 0)" width="13" height="13" style="float:left;margin-top:4px;" src="'. $context['pmx_syscssurl'] .'Images/bullet_plus.gif" alt="*" title="'.$txt['pmx_settings_all_toggle'].'" />
								</div>
							</td>
						</tr>
						<tr>
							<td style="padding:1px 5px;width:50%;">';

		foreach($txt['pmx_block_sides'] as $side => $sidename)
			echo '
								<div style="height:25px;">'. $sidename .':</div>';

		echo '
							</td>
							<td>';

		foreach($txt['pmx_block_sides'] as $side => $sidename)
		{
			echo '
								<div class="adm_clear" style="height:25px;margin-left:-4px;">
									<input style="margin-left:10px;" id="modsel'.$side.'" class="input_check" type="checkbox" name="manager[admin_pages][]" value="'. $side .'"'. (isset($admset['manager']['admin_pages']) && in_array($side, $admset['manager']['admin_pages']) ? ' checked="checked"' : '') .' />
								</div>';
		}

		echo '
									<script type="text/javascript">
									ToggleCheckbox(document.getElementById("pmxTMP"), \'modsel\', 1)
								</script>
							</td>
						</tr>
					</table>
					<hr />
					<div style="text-align:right; margin:10px 0;">
						<input class="button_submit" style="float:none;" type="button" value="'. $txt['pmx_cancel'] .'" onclick=\'window.location.href="'. $context['pmx_cancel_link'] .'"\' />
						<input class="button_submit" style="float:none;" type="button" value="'. $txt['pmx_save'] .'" onclick="FormFunc(\'save_settings\', \'yes\')" />
					</div>
					</div>
				</td>
			</tr>
		</table>';
	}

	if($context['pmx']['subaction'] == 'panels')
	{
		// Global panel settings
		echo '
		<table class="pmx_table pmx_fixedtable" style="margin-bottom:5px;">
			<tr>
				<td style="text-align:center">
					<div class="cat_bar round">
						<h3 class="catbg round">'. $txt['pmx_panel_settings'] .'</h3>
					</div>
				</td>
			</tr>
			<tr>
				<td>';

		$ActPanel = isset($_REQUEST['pn']) ? $_REQUEST['pn'] : 'head';

		echo '
					<div style="margin-top:6px;">
						<a class="menu_icon mobile_generic_menu_panels"></a>
						<div class="generic_menu">
							<ul class="dropmenu sf-js-enabled">';

		foreach($txt['pmx_block_sides'] as $side => $sidename)
		{
			if($side != 'front' && $side != 'pages')
				echo '
								<li>
									<a class="firstlevel'. ($side == $ActPanel ? ' active' : '') .'" href="'. $scripturl .'?action='. $context['pmx']['AdminMode'] .';area=pmx_settings;sa=panels;pn='. $side .';'. $context['session_var'] .'=' .$context['session_id'] .'">
										<span class="firstlevel">'. $txt['pmx_settings_panel'. $side] .'</span>
									</a>
								</li>';
		}

		echo '
							</ul>
						</div>
					</div>

					<div id="mobile_generic_menu_panels" class="popup_container">
						<div class="popup_window description">
							<div class="popup_heading">', $txt['pmx_allpanels'] ,'<a href="javascript:void(0);" class="generic_icons hide_popup"></a></div>
							<div class="generic_menu">
								<ul class="dropmenu sf-js-enabled">';

		foreach($txt['pmx_block_sides'] as $side => $sidename)
		{
			if($side != 'front' && $side != 'pages')
			{
				echo '
									<li>
										<a class="firstlevel'. ($side == $ActPanel ? ' active' : '') .'" href="'. $scripturl .'?action='. $context['pmx']['AdminMode'] .';area=pmx_settings;sa=panels;pn='. $side .';'. $context['session_var'] .'=' .$context['session_id'] .'">
											<span class="firstlevel">'. $txt['pmx_settings_panel'. $side] .'</span>
										</a>
									</li>';
			}
		}

		echo '
								</ul>
							</div>';

		echo '
						</div>
					</div>
					<script>
						$(".mobile_generic_menu_panels" ).click(function(){$("#mobile_generic_menu_panels" ).show();});
						$(".hide_popup" ).click(function(){$( "#mobile_generic_menu_panels" ).hide();});
					</script>';

		echo '
					<input type="hidden" name="curPanel" value="'. $ActPanel .'" />
				</td>
			</tr>
			<tr>
				<td>
					<div class="cat_bar catbg_grid">
						<h4 class="catbg catbg_grid">
							<span class="normaltext cat_msg_title adm_center">'. $txt['pmx_settings_panel'. $ActPanel] .'</span>
						</h4>
					</div>

					<div class="information portal_info">
					<table class="pmx_table" style="padding:0 5px;">
						<tr>
							<td style="padding:5px; width:50%;">
								<img class="panel_img" src="'. $context['pmx_imageurl'] . $ActPanel .'_panel.gif" alt="*" title="'. $txt['pmx_settings_panel'. $ActPanel] .'" />
								<div style="min-height:25px;">'. $txt['pmx_settings_panel_collapse'] .'</div>
								<div style="min-height:25px;padding-top:10px;">'. ($ActPanel == 'left' || $ActPanel == 'right' ? $txt['pmx_settings_panelwidth'] : $txt['pmx_settings_panelheight']) .'</div>';

				if(in_array($ActPanel, array('head', 'top', 'bottom', 'foot')))
					echo '
								<div style="min-height:25px;padding-top:7px;">'. $txt['pmx_settings_paneloverflow'] .'</div>';

				echo '
								<div style="padding-top:7px;">
									<a href="', $scripturl, '?action=helpadmin;help=pmx_settings_hidehelp" onclick="return reqOverlayDiv(this.href);" class="help"><span class="generic_icons help" title="', $txt['help'],'"></span></a>
									<span>'. $txt['pmx_settings_panelhidetitle'] .'</span>
								</div>
							</td>
							<td style="padding:5px; width:50%;">
								<input type="hidden" name="'. $ActPanel .'_panel[size]" value="0" />
								<input type="hidden" name="'. $ActPanel .'_panel[collapse]" value="0" />
								<div style="min-height:25px; margin-left:-4px;">
									<input class="input_check" type="checkbox" name="'. $ActPanel .'_panel[collapse]" value="1"'. (!empty($admset[$ActPanel .'_panel']['collapse']) ? ' checked="checked"' : '') .' />
								</div>
								<div style="min-height:25px;padding-top:10px;">
									<input id="pmx_size_'. $ActPanel .'" onkeyup="check_numeric(this);" type="text" size="3" name="'. $ActPanel .'_panel[size]" value="'. (!empty($admset[$ActPanel .'_panel']['size']) ? $admset[$ActPanel .'_panel']['size'] : '') .'" /> '. $txt['pmx_hw_pixel'][$ActPanel] .'
								</div>';

				if(in_array($ActPanel, array('head', 'top', 'bottom', 'foot')))
				{
					echo '
								<div style="min-height:25px;padding-top:7px;">
									<select id="pmx_chksize'. $ActPanel .'" class="adm_w60" size="1" name="'. $ActPanel .'_panel[overflow]">';

					foreach($txt['pmx_overflow_actions'] as $key => $text)
						echo '
										<option value="'. $key .'"'. (isset($admset[$ActPanel .'_panel']['overflow']) && $admset[$ActPanel .'_panel']['overflow'] == $key ? ' selected="selected"' : '') .'>'. $text .'</option>';
					echo '
									</select>
								</div>';
				}

				echo '
								<div style="min-height:25px;padding-top:7px;">
									<select id="pmxact_'. $ActPanel .'" onchange="changed(\'pmxact_'. $ActPanel .'\');" class="adm_w60" name="'. $ActPanel .'_panel[hide][]" multiple="multiple" size="5">';

				$data = array();
				if(!empty($admset[$ActPanel .'_panel']['hide']))
				{
					$hidevals = is_array($admset[$ActPanel .'_panel']['hide']) ? $admset[$ActPanel .'_panel']['hide'] : array($admset[$ActPanel .'_panel']['hide']);
					foreach($hidevals as $val)
					{
						$tmp = Pmx_StrToArray($val, '=');
						if(isset($tmp[0]) && isset($tmp[1]))
							$data[$tmp[0]] = $tmp[1];
					}
				}
				foreach($txt['pmx_action_names'] as $act => $actdesc)
					echo '
										<option value="'. $act .'='. (array_key_exists($act, $data) ? $data[$act] .'" selected="selected' : '1') .'">'. (array_key_exists($act, $data) ? ($data[$act] == 0 ? '^' : '') : '') . $actdesc .'</option>';

				echo '
									</select>
								</div>
								<script type="text/javascript">
									var pmxact_'. $ActPanel .' = new MultiSelect("pmxact_'. $ActPanel .'");
								</script>';

				$cust = isset($admset[$ActPanel .'_panel']['custom_hide']) ? $admset[$ActPanel .'_panel']['custom_hide'] : '';
				echo '
							</td>
						</tr>
						<tr>
							<td style="padding:2px 5px; width:50%;">
								<div>
									<a href="', $scripturl, '?action=helpadmin;help=pmx_settings_panel_custhelp" onclick="return reqOverlayDiv(this.href);" class="help"><span class="generic_icons help" title="', $txt['help'],'"></span></a>
									<span>'. $txt['pmx_settings_panel_customhide'] .'</span>
								</div>
							</td>
							<td style="padding:2px 5px; width:50%;">
								<div style="min-height:25px;">
									<textarea class="adm_textarea adm_w60" rows="2" cols="35" name="'. $ActPanel .'_panel[custom_hide]" style="min-height:10px; max-height:100px;">'. $cust .'</textarea>
								</div>
							</td>
						</tr>

						<tr>
							<td style="padding:2px 5px; width:50%;">
								<div style="min-height:25px;padding-top:4px;">
									<a href="', $scripturl, '?action=helpadmin;help=pmx_settings_deviceshelp" onclick="return reqOverlayDiv(this.href);" class="help"><span class="generic_icons help" title="', $txt['help'],'"></span></a>
									<span>'. $txt['pmx_settings_devices'] .'</span>
								</div>
							</td>
							<td style="padding:5px; width:50%;">
								<input type="hidden" name="'. $ActPanel .'_panel[device]" value="0" />
								<div style="min-height:60px; margin-left:-5px;">
									<div style="width:95%;"><input class="input_radio" type="radio" name="'. $ActPanel .'_panel[device]" value="0"'. (empty($admset[$ActPanel .'_panel']['device']) ? ' checked="checked"' : '') .' style="vertical-align:-3px;" /> '. $txt['pmx_devices']['all'] .'</div>
									<div style="width:95%;"><input class="input_radio" type="radio" name="'. $ActPanel .'_panel[device]" value="1"'. (!empty($admset[$ActPanel .'_panel']['device']) && $admset[$ActPanel .'_panel']['device'] == '1' ? ' checked="checked"' : '') .' style="vertical-align:-3px;" /> '. $txt['pmx_devices']['mobil'] .'</div>
									<div style="width:95%;"><input class="input_radio" type="radio" name="'. $ActPanel .'_panel[device]" value="2"'. (!empty($admset[$ActPanel .'_panel']['device']) && $admset[$ActPanel .'_panel']['device'] == '2' ? ' checked="checked"' : '') .' style="vertical-align:-3px;" /> '. $txt['pmx_devices']['desk'] .'</div>
								</div>
							</td>
						</tr>

						<tr>
							<td colspan="2">
								<hr class="pmx_hr" />
								<div style="text-align:right; margin:10px 0;">
									<input class="button_submit" style="float:none;" type="button" value="'. $txt['pmx_cancel'] .'" onclick=\'window.location.href="'. $context['pmx_cancel_link'] .'"\' />
									<input class="button_submit" style="float:none;" type="button" value="'. $txt['pmx_save'] .'" onclick="FormFunc(\'save_settings\', \'yes\')" />
								</div>
							</td>
						</tr>
					</table>
					</div>
				</td>
			</tr>
		</table>';
	}

	if($context['pmx']['subaction'] == 'frontpage')
	{
		// Frontpage settings
		echo '
		<table class="pmx_table pmx_fixedtable" style="margin-bottom:5px;">
			<tr>
				<td style="text-align:center">
					<div class="cat_bar">
						<h3 class="catbg">'. $txt['pmx_frontpage_settings'] .'</h3>
					</div>
				</td>
			</tr>
			<tr>
				<td>
					<div class="information portal_info">
					<table class="pmx_table">
						<tr>
							<td style="padding:5px;width:50%">
								<div style="min-height:25px;">'. $txt['pmx_settings_frontpage_centered'] .'</div>
								<div style="min-height:25px;">'. $txt['pmx_settings_frontpage_none'] .'</div>
							</td>
							<td style="padding:5px;width:50%;">
								<div style="float:left; width:28px;">
									<div style="min-height:25px;margin-left:-4px;">
										<input class="input_radio" type="radio" name="frontpage" value="centered"'. (!isset($admset['frontpage']) || isset($admset['frontpage']) && $admset['frontpage'] == 'centered' ? ' checked="checked"' : '') .' />
									</div>
									<div style="min-height:25px;margin-left:-4px;">
										<input class="input_radio" type="radio" name="frontpage" value="none"'. (isset($admset['frontpage']) && $admset['frontpage'] == 'none' ? ' checked="checked"' : '') .' />
									</div>
								</div>
							</td>
						</tr>
						<tr>
							<td style="padding:5px;width:50%">
								<div style="min-height:25px;">
									<a href="', $scripturl, '?action=helpadmin;help=pmx_settings_index_front_help" onclick="return reqOverlayDiv(this.href);" class="help"><span class="generic_icons help" title="', $txt['help'],'"></span></a>
									<span>'. $txt['pmx_settings_index_front'] .'</span>
								</div>
							</td>
							<td style="padding:5px;width:50%;">
								<div style="float:left; width:28px;">
									<div style="min-height:25px; margin-left:-4px;">
										<input type="hidden" name="indexfront" value="0" />
										<input class="input_check" type="checkbox" name="indexfront" value="1"'. (!empty($admset['indexfront']) ? ' checked="checked"' : '') .' />
									</div>
								</div>
							</td>
						</tr>
						<tr>
							<td style="padding:5px;width:50%">
								<div>
									<a href="', $scripturl, '?action=helpadmin;help=pmx_settings_pages_help" onclick="return reqOverlayDiv(this.href);" class="help"><span class="generic_icons help" title="', $txt['help'],'"></span></a>
									<span>'. $txt['pmx_settings_pages_hidefront'] .'</span>
								</div>
							</td>
							<td style="padding:5px;width:50%;">
								<div>
									<textarea class="adm_textarea adm_w90" rows="1" cols="50" name="hidefrontonpages" style="min-height:10px; max-height:100px;">'. $admset['hidefrontonpages'] .'</textarea>
								</div>
							</td>
						</tr>
					</table>
					<hr class="pmx_hr" />
					<div style="text-align:right; margin:10px 0;">
						<input class="button_submit" style="float:none;" type="button" value="'. $txt['pmx_cancel'] .'" onclick=\'window.location.href="'. $context['pmx_cancel_link'] .'"\' />
						<input class="button_submit" style="float:none;" type="button" value="'. $txt['pmx_save'] .'" onclick="FormFunc(\'save_settings\', \'yes\')" />
					</div>
					</div>
				</td>
			</tr>
		</table>';
	}

	// Access settings
	if($context['pmx']['subaction'] == 'access')
	{
		echo '
		<input type="hidden" name="update_access" value="1" />
		<table class="pmx_table pmx_fixedtable" style="margin-bottom:5px;">
			<tr>
				<td style="text-align:center">
					<div class="cat_bar">
						<h3 class="catbg">'. $txt['pmx_access_settings'] .'</h3>
					</div>
				</td>
			</tr>
			<tr>
				<td>
					<div class="information portal_info">
					<table class="pmx_table">
						<tr>
							<td style="padding:5px;width:50%">
								<div>
									<a href="', $scripturl, '?action=helpadmin;help=pmx_access_promote_help" onclick="return reqOverlayDiv(this.href);" class="help"><span class="generic_icons help" title="', $txt['help'],'"></span></a>
									<span>'. $txt['pmx_access_promote'] .'</span>
								</div>
							</td>
							<td style="padding:5px;width:50%;">
								<select style="width:95%;" name="setaccess[pmx_promote][]" size="5" multiple="multiple">';

		// 'pmx_articles' - Moderate articles
		foreach($context['pmx']['limitgroups'] as $group)
		{
			if($group['id'] != 1)
				echo '
									<option value="'. $group['id'] .'"'. (in_array($group['id'], $context['pmx']['permissions']['pmx_promote']) ? ' selected="selected"' : '') .'>'. $group['name'] .'</option>';
		}

		echo '
								</select>
							</td>
						</tr>

						<tr>
							<td style="padding:5px;width:50%">
								<div>
									<a href="', $scripturl, '?action=helpadmin;help=pmx_access_articlecreate_help" onclick="return reqOverlayDiv(this.href);" class="help"><span class="generic_icons help" title="', $txt['help'],'"></span></a>
									<span>'. $txt['pmx_access_articlecreate'] .'</span>
								</div>
							</td>
							<td style="padding:5px;width:50%;">
								<select style="width:95%;" name="setaccess[pmx_create][]" size="5" multiple="multiple">';

		// 'pmx_create' - Create and Write articles
		foreach($context['pmx']['limitgroups'] as $group)
		{
			if($group['id'] != 1)
				echo '
									<option value="'. $group['id'] .'"'. (in_array($group['id'], $context['pmx']['permissions']['pmx_create']) ? ' selected="selected"' : '') .'>'. $group['name'] .'</option>';
		}

		echo '
								</select>
							</td>
						</tr>

						<tr>
							<td style="padding:5px;width:50%">
								<div>
									<a href="', $scripturl, '?action=helpadmin;help=pmx_access_articlemoderator_help" onclick="return reqOverlayDiv(this.href);" class="help"><span class="generic_icons help" title="', $txt['help'],'"></span></a>
									<span>'. $txt['pmx_access_articlemoderator'] .'</span>
								</div>
							</td>
							<td style="padding:5px;width:50%;">
								<select style="width:95%;" name="setaccess[pmx_articles][]" size="5" multiple="multiple">';

		// 'pmx_articles' - Moderate articles
		foreach($context['pmx']['limitgroups'] as $group)
		{
			if($group['id'] != 1)
				echo '
									<option value="'. $group['id'] .'"'. (in_array($group['id'], $context['pmx']['permissions']['pmx_articles']) ? ' selected="selected"' : '') .'>'. $group['name'] .'</option>';
		}

		echo '
								</select>
							</td>
						</tr>

						<tr>
							<td style="padding:5px;width:50%">
								<div>
									<a href="', $scripturl, '?action=helpadmin;help=pmx_access_blocksmoderator_help" onclick="return reqOverlayDiv(this.href);" class="help"><span class="generic_icons help" title="', $txt['help'],'"></span></a>
									<span>'. $txt['pmx_access_blocksmoderator'] .'</span>
								</div>
							</td>
							<td style="padding:5px;width:50%;">
								<select style="width:95%;" name="setaccess[pmx_blocks][]" size="5" multiple="multiple">';

		// 'pmx_blocks' - Moderate blocks
		foreach($context['pmx']['limitgroups'] as $group)
		{
			if($group['id'] != 1)
				echo '
									<option value="'. $group['id'] .'"'. (in_array($group['id'], $context['pmx']['permissions']['pmx_blocks']) ? ' selected="selected"' : '') .'>'. $group['name'] .'</option>';
		}

		echo '
								</select>';

		if(allowedTo('admin_forum'))
		{
			echo '
							</td>
						</tr>
						<tr>
							<td style="padding:5px;width:50%">
								<div>
									<a href="', $scripturl, '?action=helpadmin;help=pmx_access_pmxadmin_help" onclick="return reqOverlayDiv(this.href);" class="help"><span class="generic_icons help" title="', $txt['help'],'"></span></a>
									<span>'. $txt['pmx_access_pmxadmin'] .'</span>
									
								</div>
							</td>
							<td style="padding:5px;width:50%;">
								<select style="width:61%;" name="setaccess[pmx_admin][]" size="5" multiple="multiple">';

			// 'pmx_admin' - PortaMx admin
			foreach($context['pmx']['limitgroups'] as $group)
			{
				if($group['id'] != 1)
					echo '
									<option value="'. $group['id'] .'"'. (in_array($group['id'], $context['pmx']['permissions']['pmx_admin']) ? ' selected="selected"' : '') .'>'. $group['name'] .'</option>';
			}

			echo '
								</select>
							</td>
						</tr>';
		}
		else
		{
			// 'pmx_admin' - PortaMx admin
			foreach($context['pmx']['limitgroups'] as $group)
			{
				if(in_array($group['id'], $context['pmx']['permissions']['pmx_admin']))
					echo '
								<input type="hidden" name="setaccess[pmx_admin][]" value="'. $group['id'] .'" />';
			}
			echo '
							</td>
						</tr>';
		}

		echo '
					</table>
					<hr class="pmx_hr" />
					<div style="text-align:right; margin:10px 0;">
						<input class="button_submit" style="float:none;" type="button" value="'. $txt['pmx_cancel'] .'" onclick=\'window.location.href="'. $context['pmx_cancel_link'] .'"\' />
						<input class="button_submit" style="float:none;" type="button" value="'. $txt['pmx_save'] .'" onclick="FormFunc(\'save_settings\', \'yes\')" />
					</div>
					</div>
				</td>
			</tr>
		</table>';
	}

	echo '
	</form>';
}
?>