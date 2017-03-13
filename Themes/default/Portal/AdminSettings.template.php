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
 * @version 1.0 RC1
 */

/**
* The main Subtemplate.
*/
function template_main()
{
	global $context, $modSettings, $txt, $scripturl, $PortaMx_cache;

	$curarea = isset($_GET['area']) ? $_GET['area'] : 'pmx_settings';
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

		$AdmTabs = array(
			'pmx_settings' => $txt['pmx_settings'],
			'pmx_blocks' => $txt['pmx_blocks'],
			'pmx_categories' => $txt['pmx_categories'],
			'pmx_articles' => $txt['pmx_articles'],
		);

		echo '
			<div style="height:2.6em;margin-top:6px;">
				<ul id="pmxmenu_nav" class="dropmenu sf-js-enabled">';

		foreach($AdmTabs as $name => $desc)
			echo '
					<li id="'. $name .'" class="subsections">
						<a '. ($name == $curarea ? 'class="active"' : '') .'href="'. $scripturl .'?action=portal;area='. $name .';'. $context['session_var'] .'=' .$context['session_id'] .';" onmousedown="pmxWinGetTop(\'adm\',\'set\')">'. $desc .'</a>
					</li>';

		echo '
				</ul>
			</div>';

			echo '
		<div class="cat_bar"><h3 class="catbg">'. $txt['pmx_adm_settings'] .'</h3></div>
		<p class="information">'. $Descriptions[$context['pmx']['subaction']] .'</p>
		<div class="adm_submenus" style="margin-bottom:6px;overflow:hidden;">
			<ul class="dropmenu">';

		foreach($MenuTabs as $name => $desc)
			echo '
				<li id="'. $name .'" class="subsections">
					<a class="firstlevel'. ($name == $context['pmx']['subaction'] ? ' active' : '') .'" href="'. $scripturl .'?action='. $context['pmx']['AdminMode'] .';area=pmx_settings;sa='. $name .';'. $context['session_var'] .'='. $context['session_id'] .';" onclick="pmxWinGetTop(\'adm\',\'set\')">
						<span class="firstlevel">'. $desc .'</span>
					</a>
				</li>';

		echo '
			</ul>
		</div>';
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
	<form id="pmx_form" accept-charset="', $context['character_set'], '" name="PMxAdminSettings" action="' . $scripturl . '?action='. $context['pmx']['AdminMode'] .';area='. $curarea . (!empty($context['pmx']['subaction']) ? ';sa='. $context['pmx']['subaction'] : '') .';'. $context['session_var'] .'=' .$context['session_id'] .'" method="post" style="margin: 0px;">
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
					<div class="information">
					<table class="pmx_table">
						<tr>
							<td style="padding:5px;width:50%;text-align:right">
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
							<td style="padding:5px;width:50%;text-align:right">
								<div style="min-height:25px;">'. $txt['pmx_settings_index_front'] .'
									<img class="info_toggle" onclick=\'Show_help("pmxH182")\' src="'. $context['pmx_imageurl'] .'information.png" alt="*" title="'. $txt['pmx_information_icon'] .'" />
								</div>
								<div id="pmxH182" class="info_frame" style="text-align:left;">
									'. $txt['pmx_settings_index_front_help'] .'
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
							<td style="padding:5px;width:50%;text-align:right">
								<div>'. $txt['pmx_settings_pages_hidefront'] .'
									<img class="info_toggle" onclick="Show_help(\'pmxH20\')" src="'. $context['pmx_imageurl'] .'information.png" alt="*" title="'. $txt['pmx_information_icon'] .'" />
								</div>
								<div id="pmxH20" class="info_frame" style="text-align:left">'. $txt['pmx_settings_pages_help'] .'</div>
							</td>
							<td style="padding:5px;width:50%;">
								<div>
									<textarea class="adm_textarea adm_w90" rows="1" cols="50" name="hidefrontonpages" style="min-height:10px; max-height:100px;">'. $admset['hidefrontonpages'] .'</textarea>
								</div>
							</td>
						</tr>
						<tr>
							<td style="padding:10px 5px 0 5px;width:50%;text-align:right;">
								<div>'. $txt['pmx_settings_download'] .'
									<img class="info_toggle" onclick="Show_help(\'pmxH01\', \'left\')" src="'. $context['pmx_imageurl'] .'information.png" alt="*" title="'. $txt['pmx_information_icon'] .'" />
								</div>
								<div id="pmxH01" class="info_frame">'. $txt['pmx_settings_downloadhelp'] .'</div>
							</td>
							<td style="padding:10px 5px 0 5px;width:50%;">
								<div style="margin-left:-4px;">
									<input type="hidden" name="download" value="0" />
									<input onchange="chk_dlbutton(this)" style="float:left;" class="input_check" type="checkbox" name="download" value="1"'. (!empty($admset['download']) ? ' checked="checked"' : '') .' />
								</div>
							</td>
						</tr>
						<tr id="dlbutchk1" style="display:'. (!empty($admset['download']) ? '' : 'none;') .'">
							<td style="padding:5px 5px 0 5px;width:50%;text-align:right;">
								<div>'. $txt['pmx_settings_download_action'] .'
									<img class="info_toggle" onclick="Show_help(\'pmxHdl20\', \'left\')" src="'. $context['pmx_imageurl'] .'information.png" alt="*" title="'. $txt['pmx_information_icon'] .'" />
								</div>
								<div id="pmxHdl20" class="info_frame">'. $txt['pmx_settings_dl_actionhelp'] .'</div>
							</td>
							<td style="padding:5px 5px 0 5px;width:50%;">
								<div style="min-height:25px;">
									<input class="adm_w80" type="text" name="dl_action" value="'. (!empty($admset['dl_action']) ? $admset['dl_action'] : '') .'" />
								</div>
							</td>
						</tr>
						<tr id="dlbutchk2" style="display:'. (!empty($admset['download']) ? '' : 'none;') .'">
							<td style="padding:5px;width:50%;text-align:right;">
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
							<td style="padding:10px 5px 0 5px;text-align:right;">
								<div>'. $txt['pmx_settings_other_actions'] .'
									<img class="info_toggle" onclick="Show_help(\'pmxH201\', \'left\')" src="'. $context['pmx_imageurl'] .'information.png" alt="*" title="'. $txt['pmx_information_icon'] .'" />
								</div>
								<div id="pmxH201" class="info_frame">'. $txt['pmx_settings_other_actionshelp'] .'</div>
							</td>
							<td style="padding:10px 5px 0 5px;width:50%;">
								<div style="min-height:25px;">
									<input min-height:25px;class="adm_w80" type="text" name="other_actions" value="'. (!empty($admset['other_actions']) ? $admset['other_actions'] : '') .'" />
								</div>
							</td>
						</tr>
						<tr>
							<td style="padding:10px 5px 0 5px;text-align:right;">
								<div style="min-height:25px;">'. $txt['pmx_settings_panelpadding'] .'
									<img class="info_toggle" onclick="Show_help(\'pmxHpenp\', \'left\')" src="'. $context['pmx_imageurl'] .'information.png" alt="*" title="'. $txt['pmx_information_icon'] .'" />
								</div>
								<div id="pmxHpenp" class="info_frame">'. $txt['pmx_settings_panelpadding_help'] .'</div>
							</td>
							<td style="padding:10px 5px 0 5px;width:50%;">
								<div style="min-height:25px;">
									<input onkeyup="check_numeric(this);" type="text" size="2" name="panelpad" value="'. (isset($admset['panelpad']) ? $admset['panelpad'] : '4') .'" />&nbsp;'. $txt['pmx_pixel'] .'
								</div>
							</td>
						</tr>
						<tr>
							<td style="padding:10px 5px 0 5px;width:50%;text-align:right;">
								<div>'. $txt['pmx_settings_restoretop'] .'
									<img class="info_toggle" onclick="Show_help(\'pmxHrst\', \'left\')" src="'. $context['pmx_imageurl'] .'information.png" alt="*" title="'. $txt['pmx_information_icon'] .'" />
								</div>
								<div id="pmxHrst" class="info_frame">'. $txt['pmx_settings_restoretop_help'] .'</div>
							</td>
							<td style="padding:10px 5px 0 5px;width:50%;">
								<div style="margin-left:-4px;">
									<input type="hidden" name="restoretop" value="0" />
									<input id="rstTop" class="input_check" type="checkbox" name="restoretop" value="1"'. (!empty($admset['restoretop']) ? ' checked="checked"' : '') .' onclick="chk_restore()" />
								</div>
							</td>
						</tr>
						<tr id="setspeed" style="display:none;">
							<td style="padding:10px 5px 0 5px;width:50%;text-align:right;">
								<div>'. $txt['pmx_settings_restorespeed'] .'
									<img class="info_toggle" onclick="Show_help(\'rstSpeed\', \'left\')" src="'. $context['pmx_imageurl'] .'information.png" alt="*" title="'. $txt['pmx_information_icon'] .'" />
								</div>
								<div id="rstSpeed" class="info_frame">'. $txt['pmx_settings_restorespeed_help'] .'</div>
							</td>
							<td style="padding:10px 5px 0 5px;width:50%;">
								<div>
									<input type="text" name="restorespeed" size="2" class="input_text" value="'. (isset($admset['restorespeed']) ? $admset['restorespeed'] : '500') .'" />'. $txt['pmx_settings_restorespeed_time'] .'
								</div>
							</td>
						</tr>

						<tr>
							<td style="padding:10px 5px 0 5px;width:50%;text-align:right;">
								<div>'. $txt['pmx_settings_colminwidth'] .'
									<img class="info_toggle" onclick="Show_help(\'twocolmw\', \'left\')" src="'. $context['pmx_imageurl'] .'information.png" alt="*" title="'. $txt['pmx_information_icon'] .'" />
								</div>
								<div id="twocolmw" class="info_frame">'. $txt['pmx_settings_colminwidth_help'] .'</div>
							</td>
							<td style="padding:10px 5px 0 5px;width:50%;">
								<div>
									<input type="text" name="colminwidth" size="2" class="input_text" value="'. (isset($admset['colminwidth']) ? $admset['colminwidth'] : '') .'" />&nbsp;'. $txt['pmx_pixel'] .'
								</div>
							</td>
						</tr>

						<tr>
							<td style="padding:10px 5px 0 5px;width:50%;text-align:right;">
								<div>'. $txt['pmx_settings_loadinactive'] .'
									<img class="info_toggle" onclick="Show_help(\'pmxLoadInac\', \'left\')" src="'. $context['pmx_imageurl'] .'information.png" alt="*" title="'. $txt['pmx_information_icon'] .'" />
								</div>
								<div id="pmxLoadInac" class="info_frame">'. $txt['pmx_settings_loadinactive_help'] .'</div>
							</td>
							<td style="padding:10px 5px 0 5px;width:50%;">
								<div style="margin-left:-4px;">
									<input type="hidden" name="loadinactive" value="0" />
									<input id="rstTop" class="input_check" type="checkbox" name="loadinactive" value="1"'. (!empty($admset['loadinactive']) ? ' checked="checked"' : '') .' />
								</div>
							</td>
						</tr>

						<tr>
							<td style="padding:15px 5px 0 5px;width:50%;text-align:right;">
								<script type="text/javascript">
									function chk_restore() {
										if(document.getElementById("rstTop").checked == true)
											document.getElementById("setspeed").style.display = "";
										else
											document.getElementById("setspeed").style.display = "none";
									}
									chk_restore();
								</script>
								<div>'. $txt['pmx_settings_teasermode'][0] .'
									<img class="info_toggle" onclick="Show_help(\'pmxteasecnt\', \'left\')" src="'. $context['pmx_imageurl'] .'information.png" alt="*" title="'. $txt['pmx_information_icon'] .'" />
								</div>
								<div id="pmxteasecnt" class="info_frame">'. $txt['pmx_settings_pmxteasecnthelp'] .'</div>
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
							<td style="padding:5px 5px 0 5px;width:50%;text-align:right;">
								<div>'. $txt['pmx_settings_blockcachestats'] .'
									<img class="info_toggle" onclick="Show_help(\'pmxH24a\', \'left\')" src="'. $context['pmx_imageurl'] .'information.png" alt="*" title="'. $txt['pmx_information_icon'] .'" />
								</div>
								<div id="pmxH24a" class="info_frame">'. $txt['pmx_settings_blockcachestatshelp'] .'</div>
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
							<td style="padding:10px 5px 0 5px;width:50%;text-align:right;">
								<div>'. $txt['pmx_settings_postcountacs'] .'
									<img class="info_toggle" onclick="Show_help(\'pmxH25\', \'left\')" src="'. $context['pmx_imageurl'] .'information.png" alt="*" title="'. $txt['pmx_information_icon'] .'" />
								</div>
								<div id="pmxH25" class="info_frame">'. $txt['pmx_settings_postcountacshelp'] .'</div>
							</td>
							<td style="padding:10px 5px 0 5px;width:50%;">
								<div style="margin-left:-4px;">
									<input type="hidden" name="postcountacs" value="0" />
									<input style="float:left;" class="input_check" type="checkbox" name="postcountacs" value="1"'. (!empty($admset['postcountacs']) ? ' checked="checked"' : '') .' />
								</div>
							</td>
						</tr>
						<tr>
							<td style="padding:10px 5px 0 5px;width:50%;text-align:right;">
								<div>'. $txt['pmx_settings_enable_xbarkeys'] .'
									<img class="info_toggle" onclick="Show_help(\'pmxH02\', \'left\')" src="'. $context['pmx_imageurl'] .'information.png" alt="*" title="'. $txt['pmx_information_icon'] .'" />
								</div>
								<div id="pmxH02" class="info_frame">'. $txt['pmx_settings_xbarkeys_help'] .'</div>
							</td>
							<td style="padding:10px 5px 0 5px;width:50%;">
								<div style="margin-left:-4px;">
									<input type="hidden" name="xbarkeys" value="0" />
									<input style="float:left;" class="input_check" type="checkbox" name="xbarkeys" value="1"'. (!empty($admset['xbarkeys']) ? ' checked="checked"' : '') .' />
								</div>
							</td>
						</tr>
						<tr>
							<td style="padding:5px 5px 0 5px;width:50%;text-align:right;">
								<div>'. $txt['pmx_settings_enable_xbars'] .'
									<img class="info_toggle" onclick="Show_help(\'pmxH03\', \'left\')" src="'. $context['pmx_imageurl'] .'information.png" alt="*" title="'. $txt['pmx_information_icon'] .'" />
								</div>
								<div id="pmxH03" class="info_frame">'. $txt['pmx_settings_xbars_help'] .'</div>
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
							<td style="padding:15px 5px 0 5px;width:50%;text-align:right;">
								<div style="min-height:25px;">'. $txt['pmx_settings_xbar_topoffset'] .'</div>
							</td>
							<td style="padding:15px 5px 0 5px;width:50%;">
								<div style="min-height:25px;">
									<input onkeyup="check_numeric(this);" type="text" size="2" name="xbaroffset_top" value="'. (isset($admset['xbaroffset_top']) ? $admset['xbaroffset_top'] : '40') .'" />&nbsp;'. $txt['pmx_pixel'] .'
								</div>
							</td>
						</tr>
						<tr>
							<td style="padding:5px 5px 0 5px;width:50%;text-align:right;">
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
					<div class="information">
					<table class="pmx_table">
						<tr>
							<td style="padding:10px 5px 0 5px;width:50%;text-align:right;">
								<div style="min-height:25px;">'. $txt['pmx_settings_collapse_visibility'] .'
									<img class="info_toggle" onclick=\'Show_help("pmxH05")\' src="'. $context['pmx_imageurl'] .'information.png" alt="*" title="'. $txt['pmx_information_icon'] .'" />
								</div>
								<div id="pmxH05" class="info_frame" style="text-align:left;">'. $txt['pmx_settings_collapse_vishelp'] .'</div>
							</td>
							<td style="padding:10px 5px 0 5px;width:50%;">
								<div style="margin-left:-4px;">
									<input type="hidden" name="manager[collape_visibility]" value="0" />
									<input style="float:left;" class="input_check" type="checkbox" name="manager[collape_visibility]" value="1"'. (!empty($admset['manager']['collape_visibility']) ? ' checked="checked"' : '') .' />
								</div>
							</td>
						</tr>
						<tr>
							<td style="padding:1px 5px;width:50%;text-align:right;">
								<input type="hidden" name="manager[follow]" value="0" />
								<div style="min-height:25px;">'. str_replace('[##]', '<img style="vertical-align:-3px;" src="'. $context['pmx_imageurl'] .'page_edit.gif" alt="*" title="" />', $txt['pmx_settings_quickedit']) .'
									<img class="info_toggle" onclick=\'Show_help("pmxH07")\' src="'. $context['pmx_imageurl'] .'information.png" alt="*" title="'. $txt['pmx_information_icon'] .'" />
								</div>
									<div id="pmxH07" class="info_frame" style="text-align:left;">'. $txt['pmx_settings_quickedithelp'] .'</div>
							</td>
							<td style="padding:1px 5px;width:50%;">
								<div style="margin-left:-4px;">
									<input type="hidden" name="manager[qedit]" value="0" />
									<input style="float:left;" class="input_check" type="checkbox" name="manager[qedit]" value="1"'. (!empty($admset['manager']['qedit']) ? ' checked="checked"' : '') .' />
								</div>
							</td>
						</tr>

						<tr>
							<td style="padding:1px 5px;width:50%;text-align:right;">
								<div style="min-height:25px;">'. $txt['pmx_settings_enable_promote'] .'
									<img class="info_toggle" onclick=\'Show_help("pmxH1promo")\' src="'. $context['pmx_imageurl'] .'information.png" alt="*" title="'. $txt['pmx_information_icon'] .'" />
								</div>
								<div id="pmxH1promo" class="info_frame" style="text-align:left;">'. $txt['pmx_settings_enable_promote_help'] .'</div>
							</td>
							<td style="padding:1px 5px;width:50%;">
								<div style="margin-left:-4px;">
									<input type="hidden" name="manager[promote]" value="0" />
									<input style="float:left;" class="input_check" type="checkbox" name="manager[promote]" value="1"'. (!empty($admset['manager']['promote']) ? ' checked="checked"' : '') .' />
								</div>
							</td>
						</tr>
						<tr>
							<td style="padding:2px 5px; width:50%; text-align:right;">
								<div>'. $txt['pmx_settings_promote_messages'] .'
									<img class="info_toggle" onclick="Show_help(\'pmxH2promo\', \'left\')" src="'. $context['pmx_imageurl'] .'information.png" alt="*" title="'. $txt['pmx_information_icon'] .'" />
								</div>
								<div id="pmxH2promo" class="info_frame" style="text-align:left;">'. $txt['pmx_settings_promote_messages_help'] .'</div>
							</td>
							<td style="padding:2px 5px; width:50%;">
								<div style="min-height:25px;">
									<textarea class="adm_textarea adm_w80" rows="1" cols="35" style="min-height:10px;max-height:150px;" name="promotes">'. implode(',', array_diff($context['pmx']['promotes'], array('0'))) .'</textarea>
								</div>
							</td>
						</tr>

						<tr>
							<td style="padding:1px 5px;width:50%;text-align:right;">
								<div style="min-height:25px;">', $txt['pmx_settings_article_on_page'] .'
									<img class="info_toggle" onclick=\'Show_help("pmxH10")\' src="'. $context['pmx_imageurl'] .'information.png" alt="*" title="'. $txt['pmx_information_icon'] .'" />
								</div>
								<div id="pmxH10" class="info_frame" style="text-align:left;">'. $txt['pmx_settings_article_on_pagehelp'] .'</div>
							</td>
							<td style="padding:1px 5px;width:50%;">
								<div>
									<input style="float:left;" type="text" name="manager[artpage]" size="3" value="'. (!empty($admset['manager']['artpage']) ? $admset['manager']['artpage'] : '25') .'" />
								</div>
							</td>
						</tr>
						<tr>
							<td style="padding:1px 5px;width:50%;text-align:right;">
								<div style="height:25px;">'. $txt['pmx_settings_adminpages'] .'
									<img class="info_toggle" onclick=\'Show_help("pmxH09")\' src="'. $context['pmx_imageurl'] .'information.png" alt="*" title="'. $txt['pmx_information_icon'] .'" />
								</div>
								<div id="pmxH09" class="info_frame" style="text-align:left;">'. $txt['pmx_settings_adminpageshelp'] .'</div>
							</td>
							<td style="padding:1px 5px;width:50%;text-align:right;">
								<div style="height:25px;">
									<img id="pmxTMP" class="adm_hover" onclick="ToggleCheckbox(this, \'modsel\', 0)" width="13" height="13" style="float:left;margin-top:4px;" src="'. $context['pmx_syscssurl'] .'Images/bullet_plus.gif" alt="*" title="'.$txt['pmx_settings_all_toggle'].'" />
								</div>
							</td>
						</tr>
						<tr>
							<td style="padding:1px 5px;width:50%;text-align:right;">';

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

		echo '
					<div class="adm_submenus" style="margin-bottom:6px;overflow:hidden;">
						<ul class="dropmenu">';

		$ActPanel = isset($_REQUEST['pn']) ? $_REQUEST['pn'] : 'head';

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
					</div>
					<input type="hidden" name="curPanel" value="'. $ActPanel .'" />
				</td>
			</tr>
			<tr>
				<td>
					<div class="cat_bar catbg_grid">
						<h4 class="catbg catbg_grid">
							<span class="normaltext cat_msg_title">'. $txt['pmx_settings_panel'. $ActPanel] .'</span>
						</h4>
					</div>

					<div class="information">
					<table class="pmx_table" style="padding:0 5px;">
						<tr>
							<td style="padding:5px; width:50%; text-align:right;">
								<div style="float:left; padding-left:2px; padding-top:4px;">
									<img src="'. $context['pmx_imageurl'] . $ActPanel .'_panel.gif" alt="*" title="'. $txt['pmx_settings_panel'. $ActPanel] .'" />
								</div>
								<div style="min-height:25px;">'. $txt['pmx_settings_panel_collapse'] .'</div>
								<div style="min-height:25px;padding-top:10px;">'. ($ActPanel == 'left' || $ActPanel == 'right' ? $txt['pmx_settings_panelwidth'] : $txt['pmx_settings_panelheight']) .'</div>';

				if(in_array($ActPanel, array('head', 'top', 'bottom', 'foot')))
					echo '
								<div style="min-height:25px;padding-top:7px;">'. $txt['pmx_settings_paneloverflow'] .'</div>';

				echo '
								<div style="padding-top:7px;">
									'. $txt['pmx_settings_panelhidetitle'] .'&nbsp;<img class="info_toggle" style="text-align:right;padding-top:2px;" onclick="Show_help(\'pmxH_'. $ActPanel .'\', \'left\')" src="'. $context['pmx_imageurl'] .'information.png" alt="*" title="'. $txt['pmx_information_icon'] .'" />
								</div>
								<div id="pmxH_'. $ActPanel .'" class="info_frame">'. $txt['pmx_settings_hidehelp'] .'</div>
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
							<td style="padding:2px 5px; width:50%; text-align:right;">
								<div>'. $txt['pmx_settings_panel_customhide'] .'
									<img class="info_toggle" onclick="Show_help(\'pmxH'. $ActPanel .'\', \'left\')" src="'. $context['pmx_imageurl'] .'information.png" alt="*" title="'. $txt['pmx_information_icon'] .'" />
								</div>
								<div id="pmxH'. $ActPanel .'" class="info_frame">'. $txt['pmx_settings_panel_custhelp'] .'</div>
							</td>
							<td style="padding:2px 5px; width:50%;">
								<div style="min-height:25px;">
									<textarea class="adm_textarea adm_w60" rows="2" cols="35" name="'. $ActPanel .'_panel[custom_hide]" style="min-height:10px; max-height:100px;">'. $cust .'</textarea>
								</div>
							</td>
						</tr>

						<tr>
							<td style="padding:2px 5px; width:50%; text-align:right;">
								<div style="min-height:25px;padding-top:4px;">'. $txt['pmx_settings_devices'] .'
									&nbsp;<img class="info_toggle" style="text-align:right;padding-top:2px;" onclick="Show_help(\'pmxDH_'. $ActPanel .'\', \'left\')" src="'. $context['pmx_imageurl'] .'information.png" alt="*" title="'. $txt['pmx_information_icon'] .'" />
								</div>
								<div id="pmxDH_'. $ActPanel .'" class="info_frame">'. $txt['pmx_settings_deviceshelp'] .'</div>
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
					<div class="information">
					<table class="pmx_table">
						<tr>
							<td style="padding:5px;width:50%;text-align:right">
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
							<td style="padding:5px;width:50%;text-align:right">
								<div style="min-height:25px;">'. $txt['pmx_settings_index_front'] .'
									<img class="info_toggle" onclick=\'Show_help("pmxH182")\' src="'. $context['pmx_imageurl'] .'information.png" alt="*" title="'. $txt['pmx_information_icon'] .'" />
								</div>
								<div id="pmxH182" class="info_frame" style="text-align:left;">
									'. $txt['pmx_settings_index_front_help'] .'
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
							<td style="padding:5px;width:50%;text-align:right">
								<div>'. $txt['pmx_settings_pages_hidefront'] .'
									<img class="info_toggle" onclick="Show_help(\'pmxH20\')" src="'. $context['pmx_imageurl'] .'information.png" alt="*" title="'. $txt['pmx_information_icon'] .'" />
								</div>
								<div id="pmxH20" class="info_frame" style="text-align:left">'. $txt['pmx_settings_pages_help'] .'</div>
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
					<div class="information">
					<table class="pmx_table">
						<tr>
							<td style="padding:5px;width:50%;text-align:right">
								<div>'. $txt['pmx_access_promote'] .'
									<img class="info_toggle" onclick="Show_help(\'pmxH50\', \'left\')" src="'. $context['pmx_imageurl'] .'information.png" alt="*" title="'. $txt['pmx_information_icon'] .'" />
								</div>
								<div id="pmxH50" class="info_frame">'. $txt['pmx_access_promote_help'] .'</div>
							</td>
							<td style="padding:5px;width:50%;">
								<select style="width:61%;" name="setaccess[pmx_promote][]" size="5" multiple="multiple">';

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
							<td style="padding:5px;width:50%;text-align:right">
								<div>'. $txt['pmx_access_articlecreate'] .'
									<img class="info_toggle" onclick="Show_help(\'pmxH30\', \'left\')" src="'. $context['pmx_imageurl'] .'information.png" alt="*" title="'. $txt['pmx_information_icon'] .'" />
								</div>
								<div id="pmxH30" class="info_frame">'. $txt['pmx_access_articlecreate_help'] .'</div>
							</td>
							<td style="padding:5px;width:50%;">
								<select style="width:61%;" name="setaccess[pmx_create][]" size="5" multiple="multiple">';

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
							<td style="padding:5px;width:50%;text-align:right">
								<div>'. $txt['pmx_access_articlemoderator'] .'
									<img class="info_toggle" onclick="Show_help(\'pmxH31\', \'left\')" src="'. $context['pmx_imageurl'] .'information.png" alt="*" title="'. $txt['pmx_information_icon'] .'" />
								</div>
								<div id="pmxH31" class="info_frame">'. $txt['pmx_access_articlemoderator_help'] .'</div>
							</td>
							<td style="padding:5px;width:50%;">
								<select style="width:61%;" name="setaccess[pmx_articles][]" size="5" multiple="multiple">';

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
							<td style="padding:5px;width:50%;text-align:right">
								<div>'. $txt['pmx_access_blocksmoderator'] .'
									<img class="info_toggle" onclick="Show_help(\'pmxH32\', \'left\')" src="'. $context['pmx_imageurl'] .'information.png" alt="*" title="'. $txt['pmx_information_icon'] .'" />
								</div>
								<div id="pmxH32" class="info_frame">'. $txt['pmx_access_blocksmoderator_help'] .'</div>
							</td>
							<td style="padding:5px;width:50%;">
								<select style="width:61%;" name="setaccess[pmx_blocks][]" size="5" multiple="multiple">';

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
							<td style="padding:5px;width:50%;text-align:right">
								<div>'. $txt['pmx_access_pmxadmin'] .'
									<img class="info_toggle" onclick="Show_help(\'pmxH33\', \'left\')" src="'. $context['pmx_imageurl'] .'information.png" alt="*" title="'. $txt['pmx_information_icon'] .'" />
								</div>
								<div id="pmxH33" class="info_frame">'. $txt['pmx_access_pmxadmin_help'] .'</div>
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