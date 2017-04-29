<?php
/**
 * PortaMx Forum
 * @package PortaMx
 * @author PortaMx
 * @copyright 2017 PortaMx
 *
 * file AdminBlocks.template.php
 * Template for the Blocks Manager.
 *
 * @version 1.0 RC3
 */

/**
* The main Subtemplate.
*/
function template_main()
{
	global $context, $txt, $modSettings, $scripturl;

	$sections = (!isset($_REQUEST['sa']) || (isset($_REQUEST['sa']) && $_REQUEST['sa'] == 'all') ? array_keys($txt['pmx_admBlk_sides']) : Pmx_StrToArray($_REQUEST['sa']));

	if(!allowPmx('pmx_admin', true) && allowPmx('pmx_blocks', true))
	{
		if(!isset($context['pmx']['settings']['manager']['admin_pages']))
			$context['pmx']['settings']['manager']['admin_pages'] = array();

		$showBlocks = array_intersect($sections, $context['pmx']['settings']['manager']['admin_pages']);
		$MenuTabs = array_merge(array('all'), $context['pmx']['settings']['manager']['admin_pages']);
	}
	else
	{
		$showBlocks = $sections;
		$MenuTabs = array_keys($txt['pmx_admBlk_panels']);
	}

	if($context['pmx']['function'] == 'edit' || $context['pmx']['function'] == 'editnew')
		$active = array($context['pmx']['editblock']->getConfigData('side'));
	else
		$active = explode(',', $context['pmx']['subaction']);

	if(allowPmx('pmx_admin, pmx_blocks', true) && !in_array($context['pmx']['function'], array('edit', 'editnew')))
	{
		echo '
		<div class="cat_bar"><h3 class="catbg">'. $txt['pmx_adm_blocks'] .'</h3></div>
		<p class="information">'. $txt['pmx_admBlk_desc'] .'</p>
		<div class="generic_menu">
			<ul class="dropmenu sf-js-enabled">';

		foreach($txt['pmx_admBlk_panels'] as $name => $desc)
			echo '
				<li id="'. $name .'" class="subsections">
					<a class="firstlevel'. ($name == $context['pmx']['subaction'] ? ' active' : '') .'" href="'. $scripturl .'?action='. $context['pmx']['AdminMode'] .';area=pmx_blocks;sa='. $name .';'. $context['session_var'] .'='. $context['session_id'] .';#ptop">
						<span class="firstlevel">'. $desc .'</span>
					</a>
				</li>';

		echo '
			</ul>
		</div>

		<div><a class="menu_icon mobile_generic_menu_panels" style="margin-top:-5px;"></a></div>
		<div id="mobile_generic_menu_panels" class="popup_container">
			<div class="popup_window description">
				<div class="popup_heading">', $txt['pmx_allpanels'] ,'<a href="javascript:void(0);" class="generic_icons hide_popup"></a></div>
				<div class="generic_menu">
					<ul class="dropmenu sf-js-enabled">';

		foreach($txt['pmx_admBlk_panels'] as $name => $desc)
			echo '
						<li id="'. $name .'" class="subsections">
							<a class="firstlevel'. ($name == $context['pmx']['subaction'] ? ' active' : '') .'" href="'. $scripturl .'?action='. $context['pmx']['AdminMode'] .';area=pmx_blocks;sa='. $name .';'. $context['session_var'] .'='. $context['session_id'] .';#ptop">
								<span class="firstlevel">'. $desc .'</span>
							</a>
						</li>';

		echo '
					</ul>
				</div>
			</div>
		</div>
		<script>
			$(".mobile_generic_menu_panels" ).click(function(){$("#mobile_generic_menu_panels" ).show();});
			$(".hide_popup" ).click(function(){$( "#mobile_generic_menu_panels" ).hide();});
		</script>';
	}

	if (isset($_SESSION['saved_successful']))
	{
		unset($_SESSION['saved_successful']);
		echo '
		<div class="infobox">', $txt['settings_saved'], '</div>';
	}

	echo '
		<form id="pmx_form" accept-charset="'. $context['character_set'] .'" name="PMxAdminBlocks" action="' . $scripturl . '?action='. $context['pmx']['AdminMode'] .';area=pmx_blocks;sa='. $context['pmx']['subaction'] .';'. $context['session_var'] .'=' .$context['session_id'] .'" method="post" style="margin:0px;display:block;" onsubmit="submitonce(this);">
			<input type="hidden" name="sc" value="'. $context['session_id'] .'" />
			<input type="hidden" name="function" value="'. $context['pmx']['function'] .'" />
			<input type="hidden" name="sa" value="'. $context['pmx']['subaction'] .'" />
			<input id="common_field" type="hidden" value="" />
			<input id="extra_cmd" type="hidden" value="" />';

	// ---------------------
	// all Blocks overview
	// ---------------------
	if($context['pmx']['function'] == 'overview')
	{
		$cfg_titleicons = PortaMx_getAllTitleIcons();
		$cfg_smfgroups = PortaMx_getUserGroups();
		$allNames = array();
		$allGroups = array();

		foreach($cfg_smfgroups as $key => $grp)
		{
			$allGroups[] = $grp['id'];
			$allNames[] = str_replace(' ', '_', $grp['name']);
		}

		// common Popup input fields
		echo '
			<input id="pWind.icon.url" type="hidden" value="'. $context['pmx_Iconsurl'] .'" />
			<input id="pWind.image.url" type="hidden" value="'. $context['pmx_imageurl'] .'" />
			<input id="pWind.name" type="hidden" value="" />
			<input id="pWind.side" type="hidden" value="" />
			<input id="pWind.id" type="hidden" value="" />
			<input id="allAcsGroups" type="hidden" value="'. implode(',', $allGroups) .'" />
			<input id="allAcsNames" type="hidden" value="'. implode(',', $allNames) .'" />
			<script>
				var BlockActive = "'. $txt['pmx_status_activ'] .' - '. $txt['pmx_status_change'] .'";
				var BlockInactive = "'. $txt['pmx_status_inactiv'] .' - '. $txt['pmx_status_change'] .'";
			</script>';

		foreach($showBlocks as $side)
		{
			$blockCnt = (!empty($context['pmx']['blocks'][$side]) ? count($context['pmx']['blocks'][$side]) : 0);
			$paneldesc = htmlentities($txt['pmx_admBlk_sides'][$side], ENT_QUOTES, $context['pmx']['encoding']);
			echo '
			<div id="addnodes.'. $side .'"></div>
			<div style="margin-bottom:5px;">
				<div id="paneltop-'. $side .'" class="cat_bar catbg_grid">
					<h4 class="catbg catbg_grid">
						<span'. (allowPmx('pmx_admin') ? ' class="pmx_clickaddnew" title="'. sprintf($txt['pmx_add_sideblock'], $txt['pmx_admBlk_sides'][$side]) .'" onclick="SetpmxBlockType(\''. $side .'\', \''. $paneldesc .'\', this)"' : '') .'></span>
						<span class="cat_msg_title_center">
							<a href="'. $scripturl .'?action='. $context['pmx']['AdminMode'] .';area=pmx_blocks;sa='. $side .';'. $context['session_var'] .'=' .$context['session_id'] .'">'. $txt['pmx_admBlk_sides'][$side] .'</a>
						</span>
					</h4>
				</div>
				<div class="windowbg2 wdbgtop" style="margin-bottom:4px;">
					<div class="pmx_tbl" style="margin-bottom:3px;box-shadow: 0 0 0 transparent;">
						<div class="pmx_tbl_tr windowbg2 normaltext" style="height:27px;">
							<div class="pmx_tbl_tdgrid" style="width:45px;"><b>'. $txt['pmx_admBlk_order'] .'</b></div>';

			if(!empty($blockCnt))
				echo '
							<div class="pmx_tbl_tdgrid" onclick="pWindToggleLang(\'.'. $side .'\')" title="'. $txt['pmx_toggle_language'] .'" style="width:57%;cursor:pointer; padding:3px 5px;"><b>'. $txt['pmx_title'] .' [<b id="pWind.def.lang.'. $side .'">'. $context['pmx']['currlang'] .'</b>]</b></div>';
			else
				echo '
							<div class="pmx_tbl_tdgrid" title="'. $txt['pmx_toggle_language'] .'" style="width:57%;"><b>'. $txt['pmx_title'] .' [<b id="pWind.def.lang.'. $side .'">'. $context['pmx']['currlang'] .'</b>]</b></div>';

			echo '
							<div class="pmx_tbl_tdgrid" style="width:36%;"><b>'. $txt['pmx_admBlk_type'] .'</b></div>
							<div class="pmx_tbl_tdgrid opt_row" id="RowMove-'. $side .'" style="width:126px;"><b>'. $txt['pmx_options'] .'</b></div>
							<div class="pmx_tbl_tdgrid" style="width:43px;"><div style="width:40px;"><b>'. $txt['pmx_status'] .'</b></div></div>
							<div class="pmx_tbl_tdgrid" style="width:105px;"><div style="width:105px;"><b>'. $txt['pmx_functions'] .'</b></div></div>
						</div>';

			// call PmxBlocksOverview for each side / block
			$blockIDs = array();
			if(!empty($blockCnt))
			{
				foreach($context['pmx']['blocks'][$side] as $block)
				{
					if(PmxBlocksOverview($block, $side, $cfg_titleicons, $cfg_smfgroups) == true)
					{
						$blockIDs[] = $block['id'];
						$blocktypes[$side][$block['id']] = array(
							'type' => $block['blocktype'],
							'pos' => $block['pos'],
						);
					}
				}
			}

			echo '
					</div>';

			if(count($blockIDs) > 0)
			{
				// common Popup input fields
				echo '
					<input id="pWind.language.'. $side .'" type="hidden" value="'. $context['pmx']['currlang'] .'" />
					<input id="pWind.all.ids.'. $side .'" type="hidden" value="'. implode(',', $blockIDs) .'" />';

				$blockCnt = (!empty($context['pmx']['blocks'][$side]) ? count($context['pmx']['blocks'][$side]) : 0);
				$paneldesc = htmlentities($txt['pmx_admBlk_sides'][$side], ENT_QUOTES, $context['pmx']['encoding']);

				if(count($blockIDs) == 1 && allowPmx('pmx_admin'))
					echo '
					<script type="text/javascript">
						document.getElementById("Img.RowMove-'. $blockIDs[0] .'").className = "pmx_clickrow";
						document.getElementById("Img.RowMove-'. $blockIDs[0] .'").title = "";
					</script>';
			}

			echo '
				</div>
			</div>';
		}

/**
* Popup windows for overview
**/
	// start row move popup
	echo '
			<div style="height:5px;margin:-5px 5px 0 5px;border-color:transparent;background-color:transparent;z-index:900;">
				<div class="pmx_tbl" style="table-layout:fixed;">
					<div class="pmx_tbl_tr" id="popupRow">
						<div class="pmx_tbl_tdgrid" style="width:47px;border-color:transparent;">
							<div id="pmxRowMove" class="smalltext" style="width:345px;z-index:999;display:none;margin-top:-28px;">
								'. pmx_popupHeader('pmxRowMove', $txt['pmx_rowmove_title']) .'
									<input id="pWind.move.error" type="hidden" value="'. $txt['pmx_block_move_error'] .'" />
									<div style="float:left;width:94px;">
										'. $txt['pmx_block_rowmove'] .'<br />
										<div style="margin-top:5px;">'. $txt['pmx_blockmove_place'] .'</div><br />
										<div style="margin-top:-10px;">'. $txt['pmx_blockmove_to'] .'</div>
									</div>
									<div style="padding-left:94px;">
										<div style="margin-left:5px; margin-top:0px;" id="pWind.move.blocktyp"></div>
										<div style="margin-top:3px;height:20px">
											<input id="pWind.place.0" class="input_check" type="radio" value="before" name="_" checked="checked" style="vertical-align:-3px;" />
											<span style="padding:0 3px;">'. $txt['rowmove_before'] .'</span>
											<input id="pWind.place.1" class="input_check" type="radio" value="after"  name="_" style="vertical-align:-3px;" />
											<span style="padding:0 3px;">'. $txt['rowmove_after'] .'</span><br />
										</div>';

	// output blocktypes
	foreach($txt['pmx_admBlk_sides'] as $side => $d)
	{
		if(isset($blocktypes[$side]))
		{
			echo '
										<div style="width:145px;margin-top:8px;">
											<select id="pWind.select.'. $side .'" style="width:160px;margin-left:3px;display:none" size="1">';

			// output blocktypes
			foreach($blocktypes[$side] as $id => $data)
				echo '
												<option value="'. $id .'">['. $data['pos'] .'] '. $context['pmx']['RegBlocks'][$data['type']]['description'] .'</option>';

			echo '
											</select>
										</div>';
			}
		}
		echo '
									</div>
									<div style="float:right; margin-top:-1px;">
										<input class="button_submit" type="button" value="'. $txt['pmx_save'] .'" onclick="pmxSendRowMove()" />
									</div>
								</div>
							</div>
						</div>';
		// end Move popup

		// start title edit popup
		echo '
						<div class="pmx_tbl_tdgrid" style="width:57%;border-color:transparent;height:5px">
							<div id="pmxSetTitle" class="smalltext" style="width:420px;z-index:9999;display:none;margin-top:-28px;">
								'. pmx_popupHeader('pmxSetTitle', $txt['pmx_edit_titles'], '112px') .'
									<div style="float:left; width:75px;">'. $txt['pmx_edit_title'] .'</div>
									<input id="pWind.text" style="width:310px;" type="text" value="" />
									<input id="pWindID" type="hidden" value="" />
									<input id="pWind_event_status" type="hidden" value="" />
									<div style="clear:both; height:10px;">
										<img style="float:left;margin-top:-3px;" src="'. $context['pmx_imageurl'] .'arrow_down.gif" alt="*" title="" />
									</div>
									<div style="float:left; width:75px;">'. $txt['pmx_edit_title_lang'] .'</div>
									<select id="pWind.lang.sel" style="float:left; width:165px;" size="1" onchange="pmxChgTitles_Lang(this)">';

		// languages
		foreach($context['pmx']['languages'] as $lang => $sel)
			echo '
										<option value="'. $lang .'">'. $lang .'</option>';

		echo '
									</select>
									<div style="float:right;padding-right:1px;"><span style="vertical-align:6px;">'. $txt['pmx_edit_title_align'] .'</span>';

		// Title align
		foreach($txt['pmx_edit_title_align_types'] as $key => $val)
			echo '
										<img id="pWind.align.'. $key .'" src="'. $context['pmx_imageurl'] .'text_align_'. $key .'.gif" alt="*" title="'. $txt['pmx_edit_title_helpalign']. $val .'" style="vertical-align:2px; cursor:pointer;" onclick="pmxChgTitles_Align(\''. $key .'\')" />';

		echo '
									</div>
									<br style="clear:both;" />
									<input style="float:right; margin-top:9px;margin-right:1px;" class="button_submit" type="button" value="'.$txt['pmx_update_save'].'"  onclick="pmxUpdateTitles()" />
									<div style="float:left;width:75px; padding-top:8px;">'. $txt['pmx_edit_titleicon'] .'</div>';

		// Title icons
		echo '
									<div class="ttliconDiv" onclick="setNewIcon(document.getElementById(\'pWind.icon_sel\'), event)">
										<input id="post_image" type="hidden" name="config[title_icon]" value="" />
										<input id="iconDD" value="'. (isset($block['config']['title_icon']) ? ucfirst(str_replace('.png', '', $block['config']['title_icon'])) : 'NoneF') .'" readonly />
										<img id="pWind.icon" class="pwindicon" src="'. $context['pmx_shortIconsurl'] .'none.png" alt="*" />
										<img class="ddImage" src="'. $context['pmx_imageurl'] .'state_expand.png" alt="*" title="" />
									</div>
									<ul class="ttlicondd" id="pWind.icon_sel" onclick="updIcon(this)">';

		foreach($cfg_titleicons as $file => $name)
			echo '
										<li id="'. $file .'" class="ttlicon'. (isset($block['config']['title_icon']) && $block['config']['title_icon'] == $file ? ' active' : '') .'">
											<img src="'. $context['pmx_shortIconsurl'] . $file .'" alt="*" /><span>'. $name .'</span>
										</li>';

		echo '
									</ul>
									<script>$("li").hover(function(){$(this).toggleClass("active")});</script>
								</div>
							</div>
						</div>';
		// end title edit popup

		echo '
						<div class="pmx_tbl_tdgrid" style="width:36%;border-color:transparent;height:5px"><div>&nbsp;</div></div>
						<div class="pmx_tbl_tdgrid opt_row" style="width:126px;border-color:transparent;height:5px"><div>&nbsp;</div></div>
						<div class="pmx_tbl_tdgrid" style="width:43px;border-color:transparent;height:5px"><div>&nbsp;</div></div>
						<div id="func-row" class="pmx_tbl_tdgrid" style="width:105px;border-color:transparent;height:5px">';

		// start Access popup
		echo '
							<div id="pmxSetAcs" class="smalltext" style="width:310px;z-index:9999;display:none;margin-top:-28px;">
								'. pmx_popupHeader('pmxSetAcs', $txt['pmx_article_groups']) .'
									<div style="float:left;">
										<select id="pWindAcsGroup" style="width:120px;" multiple="multiple" size="5" onchange="changed(\'pWindAcsGroup\');">';

		foreach($cfg_smfgroups as $grp)
			echo '
											<option value="'. $grp['id'] .'=1">'. $grp['name'] .'</option>';

		echo '
										</select>
									</div>
									<div style="float:right;">
										<div style="margin-top:-5px;margin-left:-4px;height:68px;">
											<div style="height:18px;"><input id="pWindAcsModeupd" onclick="pmxSetAcsMode(\'upd\')" class="input_check" type="radio" name="_" value="" /><span style="vertical-align:3px;">'. $txt['pmx_acs_repl'] .'</span></div>
											<div style="height:18px;"><input id="pWindAcsModeadd" onclick="pmxSetAcsMode(\'add\')" class="input_check" type="radio" name="_" value="" /><span style="vertical-align:3px;">'. $txt['pmx_acs_add'] .'</span></div>
											<div style="height:18px;"><input id="pWindAcsModedel" onclick="pmxSetAcsMode(\'del\')" class="input_check" type="radio" name="_" value="" /><span style="vertical-align:3px;">'. $txt['pmx_acs_rem'] .'</span></div>
										</div>
										<div style="margin-top:-4px;">
											<input id="acs_all_button" class="button_submit" type="button" value="'. $txt['pmx_update_all'] .'" onclick="pmxUpdateAcs(\'all\')" />
											<input class="button_submit" type="button" value="'. $txt['pmx_update_save'] .'" style="margin:0;" onclick="pmxUpdateAcs()" />
										</div>
									</div>
									<script type="text/javascript">
										var pWindAcsGroup = new MultiSelect("pWindAcsGroup");
										var BlockActive = "'. $txt['pmx_status_activ'] .' - '. $txt['pmx_status_change'] .'";
										var BlockInactive = "'. $txt['pmx_status_inactiv'] .' - '. $txt['pmx_status_change'] .'";
									</script>
								</div>
							</div>';
		// end Access popup

		// start Clone / Move popup
		echo '
							<div id="pmxSetCloneMove" class="smalltext" style="width:230px;z-index:9999;display:none;margin-top:-28px;">
								'. pmx_popupHeader('pmxSetCloneMove', '<span id="title.clone.move"></span>') .'
									<input id="pWind.txt.clone" type="hidden" value="'. $txt['pmx_text_clone'] .'" />
									<input id="pWind.txt.move" type="hidden" value="'. $txt['pmx_text_move'] .'" />
									<input id="pWind.worktype" type="hidden" value="" />
									<input id="pWind.addoption" type="hidden" value="'. $txt['pmx_clone_move_toarticles'] .'" />
									<div id="pWind.clone.move.blocktype" style="float:left;"><b></b></div>
									<div style="clear:both; height:4px;"></div>
									<div>'. $txt['pmx_clone_move_side'] .'</div>
									<select id="pWind.sel.sides" style="width:115px;" size="1">';

		$sel = true;
		foreach($txt['pmx_admBlk_sides'] as $side => $desc)
		{
			echo '
										<option value="'. $side .'"'. (!empty($sel) ? ' selected="selected"' : '') .'>'. $desc .'</option>';
			$sel = false;
		}

		echo '
									</select>
									<input style="float:right;margin-top:-2px;" class="button_submit" type="button" value="'. $txt['pmx_save'] .'" onclick="pmxSendCloneMove()" />
								</div>
							</div>';
		// end Clone / Move popup

		// start delete popup
		echo '
							<div id="pmxSetDelete" class="smalltext" style="width:220px;z-index:9999;display:none;margin-top:-28px;">
								'. pmx_popupHeader('pmxSetDelete', $txt['pmx_delete_block']) .'
									<div><span id="pWind.delete.blocktype"></span></div>
									<div>'. $txt['pmx_confirm_blockdelete'] .'</div>
									<input id="pWind.blockid" type="hidden" value="" />
									<div style="height:20px"><input style="float:right;font-size:11px;" class="button_submit" type="button" value="'. $txt['pmx_delete_button'] .'" onclick="pmxSendDelete()" /></div>
								</div>
							</div>';
		// end delete popup

		// start blocktype selection popup
		$RegBlocks = eval($context['pmx']['registerblocks']);
		function cmpBDesc($a, $b){return strcasecmp(str_replace(' ', '', $a["description"]), str_replace(' ', '', $b["description"]));}
		uasort($RegBlocks, 'cmpBDesc');

		echo '
							<div id="pmxBlockType" class="smalltext" style="width:256px;margin-top:-34px;display:none;">
								'. pmx_popupHeader('pmxBlockType', '') .'
									<div style="margin:-4px 0 5px 0;">'. $txt['pmx_blocks_blocktype'] .'</div>
									<input id="pWind.blocktype.title" type="hidden" value="'. $txt['pmx_add_new_blocktype'] .'" />
									<select id="pmx.block.type" size="1" style="width:150px;">';

		foreach($RegBlocks as $blocktype => $blockDesc)
			echo '
										<option value="'. $blocktype .'">'. $blockDesc['description'] .'</option>';

		echo '
									</select>
									<div style="float:right;margin-top:-2px;">
										<input id="BType" class="button_submit" type="button" value="'. $txt['pmx_create'] .'" onclick="pmxSendBlockType()" />
									</div>
								</div>
							</div>';
		// end blocktype popup

		echo '
						</div>
					</div>
				</div>';

echo '
			</div>
		</form>';
	}

	// --------------------
	// single block edit
	// --------------------
	elseif($context['pmx']['function'] == 'edit' || $context['pmx']['function'] == 'editnew')
	{
		echo '
			<table class="pmx_table" style="table-layout:fixed;">
				<tr>
					<td style="text-align:center;">
						<div class="cat_bar">
							<h3 class="catbg">
							'. $txt['pmx_editblock'] .' '. $context['pmx']['RegBlocks'][$context['pmx']['editblock']->cfg['blocktype']]['description'] .'
							</h3>
						</div>
					</td>
				</tr>';

		// call the ShowAdmBlockConfig() methode
		$context['pmx']['editblock']->pmxc_ShowAdmBlockConfig();

		echo '
			</table>
		</form>';
	}
}

/**
* Called for each block.
*/
function PmxBlocksOverview($block, $side, $cfg_titleicons, $cfg_smfgroups)
{
	global $context, $txt;

	if(!allowPmx('pmx_admin', true) && allowPmx('pmx_blocks', true))
	{
		if(empty($block['config']['can_moderate']))
			return false;
	}

	if(empty($block['config']['title_align']))
		$block['config']['title_align'] = 'left';
	if(empty($block['config']['title_icon']))
		$block['config']['title_icon'] = 'none.png';

	// pos row
	echo '
							<div class="pmx_tbl_tr">
								<div class="pmx_tbl_tdgrid" id="RowMove-'. $block['id'] .'">
									<div'. (allowPmx('pmx_admin') ? ' onclick="pmxRowMove(\''. $block['id'] .'\', \''. $side .'\', this)"' : '') .'>
										<div id="Img.RowMove-'. $block['id'] .'" style="white-space:nowrap;" class="pmx_clickrow'. (allowPmx('pmx_admin') ? ' pmx_moveimg" title="'. $txt['row_move_updown'] : '') .'">
											<div id="pWind.pos.'. $side .'.'. $block['id'] .'" style="padding-left:20px;margin-top:-2px;width:22px;">'. $block['pos'] .'</div>
										</div>
									</div>
								</div>';

	// title row
	echo '
								<div class="pmx_tbl_tdgrid">
									<div onclick="pmxSetTitle(\''. $block['id'] .'\', \''. $side .'\', this)"  title="'. $txt['pmx_click_edit_ttl'] .'" style="cursor:pointer;">
										<img id="uTitle.icon.'. $block['id'] .'" style="text-align:left;padding-right:4px;" src="'. $context['pmx_Iconsurl'] . $block['config']['title_icon'] .'" alt="*" title="'. substr($txt['pmx_edit_titleicon'], 0, -1) .'" />
										<img id="uTitle.align.'. $block['id'] .'" style="text-align:right;" src="'. $context['pmx_imageurl'] .'text_align_'. $block['config']['title_align'] .'.gif" alt="*" title="'. $txt['pmx_edit_title_align'] . $txt['pmx_edit_title_align_types'][$block['config']['title_align']] .'" />
										<span id="sTitle.text.'. $block['id'] .'.'. $side .'">'. (isset($block['config']['title'][$context['pmx']['currlang']]) ? htmlspecialchars($block['config']['title'][$context['pmx']['currlang']], ENT_QUOTES) : '') .'</span>';

	foreach($context['pmx']['languages'] as $lang => $sel)
		echo '
										<input id="sTitle.text.'. $lang .'.'. $block['id'] .'.'. $side .'" type="hidden" value="'. (isset($block['config']['title'][$lang]) ? htmlspecialchars($block['config']['title'][$lang], ENT_QUOTES) : '') .'" />';

	echo '
										<input id="sTitle.icon.'. $block['id'] .'" type="hidden" value="'. $block['config']['title_icon'] .'" />
										<input id="sTitle.align.'. $block['id'] .'" type="hidden" value="'. $block['config']['title_align'] .'" />
									</div>
								</div>';

	// type row
	echo '
								<div class="pmx_tbl_tdgrid">
									<div id="pWind.desc.'. $side .'.'. $block['id'] .'" title="'. $context['pmx']['RegBlocks'][$block['blocktype']]['blocktype'] .' '. $context['pmx']['RegBlocks'][$block['blocktype']]['description'] .' (ID:'. $block['id'] .')'. ($block['side'] == 'pages' ? ', Name: '. $block['config']['pagename'] : '') .'"><img src="'. $context['pmx_imageurl'] .'type_'. $context['pmx']['RegBlocks'][$block['blocktype']]['icon'] .'.gif" alt="*" />&nbsp;<span style="cursor:default;">'. $context['pmx']['RegBlocks'][$block['blocktype']]['description'] .'</span></div>
								</div>';

	// create acs groups for acs Popup
	if(!empty($block['acsgrp']))
		list($grpacs, $denyacs) = Pmx_StrToArray($block['acsgrp'], ',', '=');
	else
		$grpacs = $denyacs = array();

	// check extent options
	$extOpts = false;
	if(!empty($block['config']['ext_opts']))
	{
		foreach($block['config']['ext_opts'] as $k => $v)
			$extOpts = !empty($v) ? true : $extOpts;
	}

	// options row
	echo '
								<div class="pmx_tbl_tdgrid opt_row" id="RowAccess.'. $block['id'] .'">
									<input id="grpAcs.'. $block['id'] .'" type="hidden" value="'. implode(',', $grpacs) .'" />
									<input id="denyAcs.'. $block['id'] .'" type="hidden" value="'. implode(',', $denyacs) .'" />
									<div>
										<div id="pWind.grp.'. $block['id'] .'" class="pmx_clickrow'. (!empty($block['acsgrp']) ? ' pmx_access" title="'. $txt['pmx_have_groupaccess'] : '') .'"></div>
										<div class="pmx_clickrow'. (!empty($block['config']['can_moderate']) ? ' pmx_moderate"  title="'. $txt['pmx_have_modaccess'] : '') .'"></div>
										<div class="pmx_clickrow'. (!empty($extOpts) ? ' pmx_dynopts" title="'. $txt['pmx_have_dynamics'] : '') .'"></div>
										<div class="pmx_clickrow'. (!empty($block['config']['cssfile']) ? ' pmx_custcss" title="'. $txt['pmx_have_cssfile'] : '') .'"></div>
										<div class="pmx_clickrow'. (!empty($block['config']['check_ecl']) ? ' pmx_eclsettings" title="'. $txt['pmx_have_ecl_settings'] : '') .'"></div>
										<div class="pmx_clickrow'. (!empty($block['cache']) ? ' pmx_cache" title="'. $txt['pmx_have_caching'] . $block['cache'] . $txt['pmx_edit_cachetimesec'] : '') .'"></div>
									</div>
								</div>';

	// status row
	echo '
								<div class="pmx_tbl_tdgrid" style="text-align:center">
									<div style="margin-left:14px;" id="status.'. $block['id'] .'" class="pmx_clickrow'. ($block['active'] ? ' pmx_active" title="'. $txt['pmx_status_activ'] : ' pmx_notactive" title="'. $txt['pmx_status_inactiv']) .' - '. $txt['pmx_status_change'] .'" onclick="pToggleStatus('. $block['id'].', \''. $block['side'] .'\')"></div>
								</div>';

	// functions row
	echo '
								<div class="pmx_tbl_tdgrid">
									<div class="pmx_clickrow pmx_pgedit" title="'. $txt['pmx_edit_sideblock'].'" onclick="FormFunc(\'edit_block\', \''. $block['id'] .'\')"></div>
									<div class="pmx_clickrow pmx_grpacs" title="'. $txt['pmx_chg_blockaccess'] .'" onclick="pmxSetAcs(\''. $block['id'] .'\', \''. $block['side'] .'\', this)"></div>
									<div class="pmx_clickrow'. (allowPmx('pmx_admin') ? ' pmx_pgclone" title="'. $txt['pmx_clone_sideblock'] .'" onclick="pmxSetCloneMove(\''. $block['id'] .'\', \''. $block['side'] .'\', \'clone\', \''. $block['blocktype'] .'\', this)"' : '"') .'></div>
									<div class="pmx_clickrow'. (allowPmx('pmx_admin') ? ' pmx_pgmove" title="'. $txt['pmx_move_sideblock'] .'" onclick="pmxSetCloneMove(\''. $block['id'] .'\', \''. $block['side'] .'\', \'move\', \''. $block['blocktype'] .'\', this)"' : '"') .'></div>
									<div class="pmx_clickrow'. (allowPmx('pmx_admin') ? ' pmx_pgdelete" title="'. $txt['pmx_delete_sideblock'] .'" onclick="pmxSetDelete(\''. $block['id'] .'\', \''. $block['side'] .'\', this)"' : '"') .'></div>
								</div>
							</div>';
	return true;
}

/**
* Popup Header bar
**/
function pmx_popupHeader($tag, $title = '', $height = 0)
{
	global $context, $txt;

	return '
				<div class="cat_bar catbg_grid" style="cursor:pointer;margin-bottom:0;margin-top:5px;" onclick="pmxRemovePopup()" title="'. $txt['pmx_clickclose'] .'">
					<h4 class="catbg catbg_grid">
						<img class="grid_click_image pmxright" src="'. $context['pmx_imageurl'] .'cross.png" alt="close" style="padding-left:10px;" />
						<span'. (empty($title) ? ' id="pWind.title.bar"' : '') .'>'. $title .'</span>
					</h4>
				</div>
				<div id="'. $tag .'.body" class="roundframe" style="padding-top:8px;border-radius: 0 0 5px 5px;margin-top:-2px'. (!empty($height) ? ';height:'. $height .';overflow:hidden' : '') .';">';
}
?>