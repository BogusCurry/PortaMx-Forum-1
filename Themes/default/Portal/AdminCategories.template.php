<?php
/**
 * PortaMx Forum
 * @package PortaMx
 * @author PortaMx
 * @copyright 2017 PortaMx
 *
 * file AdminCategories.template.php
 * Template for the Categories Manager.
 *
 * @version 1.0 RC2
 */

/**
* The main Subtemplate.
*/
function template_main()
{
	global $context, $txt, $scripturl;
	global $cfg_titleicons, $cfg_smfgroups;

	$curarea = isset($_GET['area']) ? $_GET['area'] : 'pmx_settings';

	if(allowPmx('pmx_admin', true))
	{
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
						<a '. ($name == $curarea ? 'class="active"' : '') .'href="'. $scripturl .'?action=portal;area='. $name .';'. $context['session_var'] .'=' .$context['session_id'] .'">'. $desc .'</a>
					</li>';

		echo '
				</ul>
			</div>';

		if(allowPmx('pmx_admin', true) && !in_array($context['pmx']['subaction'], array('edit', 'editnew')))
			echo '
			<div class="cat_bar">
				<h3 class="catbg">'. $txt['pmx_adm_categories'] .'</h3>
			</div>
			<p class="information">'. $txt['pmx_categories_desc'] .'</p>
			<div style="height:0.5em;"></div>';
	}

	if (isset($_SESSION['saved_successful']))
	{
		unset($_SESSION['saved_successful']);
		echo '
		<div class="infobox">', $txt['settings_saved'], '</div>';
	}

	echo '
		<form id="pmx_form" accept-charset="', $context['character_set'], '" name="PMxAdminCategories" action="' . $scripturl . '?action='. $context['pmx']['AdminMode'] .';area=pmx_categories;'. $context['session_var'] .'=' .$context['session_id'] .'" method="post" style="margin: 0px 0px 45px 0;">
			<input type="hidden" name="sc" value="', $context['session_id'], '" />
			<input type="hidden" name="sa" value="', $context['pmx']['subaction'], '" />
			<input id="common_field" type="hidden" value="" />
			<input id="extra_cmd" type="hidden" value="" />';

	// ------------------------
	// all categories overview
	// ------------------------
	if($context['pmx']['subaction'] == 'overview')
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
		$categoryCnt = 0;
		$catIDs = array();

		// common Popup input fields
		echo '
			<input id="pWind.language.cat" type="hidden" value="'. $context['pmx']['currlang'] .'" />
			<input id="pWind.icon.url" type="hidden" value="'. $context['pmx_Iconsurl'] .'" />
			<input id="pWind.image.url" type="hidden" value="'. $context['pmx_imageurl'] .'" />
			<input id="pWind.name" type="hidden" value="" />
			<input id="pWind.id" type="hidden" value="" />
			<input id="pWind.side" type="hidden" value="" />
			<input id="allAcsGroups" type="hidden" value="'. implode(',', $allGroups) .'" />
			<input id="allAcsNames" type="hidden" value="'. implode(',', $allNames) .'" />
			<div id="addnodes" style="display:none"></div>';

		echo '
			<div class="cat_bar catbg_grid">
				<h4 class="catbg catbg_grid">
					<span class="pmx_clickaddnew" title="'. $txt['pmx_categories_add'] .'" onclick="FormFunc(\'add_new_category\', \'1\')"></span>
					<span class="cat_msg_title_center">'. $txt['pmx_categories_overview'] .'</span>
				</h4>
			</div>
			<div class="windowbg2 wdbgtop" style="margin-bottom:4px;">
				<div class="pmx_tbl" style="margin-bottom:3px;box-shadow: 0 0 0 transparent;">
					<div class="pmx_tbl_tr windowbg2 normaltext" style="height:27px;">
						<div class="pmx_tbl_tdgrid" style="width:46px;"><b>'. $txt['pmx_categories_order'] .'</b></div>
						<div class="pmx_tbl_tdgrid" style="width:57%;cursor:pointer;" onclick="pWindToggleLang(\'cat\')" title="'. $txt['pmx_toggle_language'] .'"><b>'. $txt['pmx_title'] .' [<b id="pWind.def.lang.cat">'. $context['pmx']['currlang'] .'</b>]</b></div>
						<div class="pmx_tbl_tdgrid" style="width:34%;"><b>'. $txt['pmx_categories_name'] .'</b></div>
						<div class="pmx_tbl_tdgrid" style="width:84px;"><b>'. $txt['pmx_options'] .'</b></div>
						<div class="pmx_tbl_tdgrid" style="width:84px;"><b>'. $txt['pmx_functions'] .'</b></div>
					</div>';

		// call PmxCategoryOverview for each category
		foreach($context['pmx']['catorder'] as $catorder)
		{
			$cat = PortaMx_getCatByOrder($context['pmx']['categories'], $catorder);
			PmxCategoryOverview($cat);
			$catIDs[] = $cat['id'];
		}

		echo '
				</div>
				<input id="pWind.all.ids.cat" type="hidden" value="'. implode(',', $catIDs) .'" />
			</div>';

/**
* Popup windows for overview
**/
		echo '
			<div style="height:5px;margin:-10px 5px 0 5px;border-color:transparent;background-color:transparent;z-index:900;">
				<div class="pmx_tbl" style="table-layout:fixed;">
					<div class="pmx_tbl_tr" id="popupRow">
						<div class="pmx_tbl_tdgrid" style="width:47px;border-color:transparent;">';

		// start Move popup
		echo '
							<div id="pmxSetMove" class="smalltext" style="width:355px;z-index:9999;margin-top:-33px;display:none;">
								'. pmx_popupHeader('pmxSetMove', $txt['pmx_categories_movecat']) .'
									<input id="pWind.move.error" type="hidden" value="'. $txt['pmx_categories_move_error'] .'" />
									<div style="float:left;width:130px;">'. $txt['pmx_categories_move'] .'</div>
									<div style="margin-left:130px;margin-bottom:3px;" id="pWind.move.catname">&nbsp;</div>
									<div style="float:left;width:126px;">'. $txt['pmx_categories_moveplace'] .'</div>
									<div style="margin-left:126px;">';

		$opt = 0;
		foreach($txt['pmx_categories_places'] as $artType => $artDesc)
		{
			echo '
									<input id="pWind.place.'. $opt .'" class="input_check" type="radio" name="_" value="'. $artType .'"'. ($artType == 'after' ? ' checked="checked"' : '') .' /><span style="vertical-align:3px; padding:0 5px;">'. $artDesc .'</span>'. ($opt == 1 ? '<br />' : '');
			$opt++;
		}

		// all exist categories
		echo '
									</div>
									<div style="float:left; width:130px;margin-top:4px;">'. $txt['pmx_categories_tomovecat'] .'</div>
									<div style="margin-left:130px;margin-top:6px;">
										<select id="pWind.sel.destcat" style="width:145px;" size="1">';

		// output cats
		foreach($context['pmx']['catorder'] as $catorder)
		{
			$cat = PortaMx_getCatByOrder($context['pmx']['categories'], $catorder);
			echo '
										<option value="'. $cat['id'] .'">['. $catorder .']'. str_repeat('&bull;', $cat['level']) .' '. $cat['name'] .'</option>';
		}

		echo '
										</select>
									</div>
									<div style="text-align:right; margin-top:6px;height:18px;">
										<input class="button_submit" type="button" value="'. $txt['pmx_save'] .'" onclick="pmxSaveMove()" />
									</div>
								</div>
							</div>';
			// end Move popup

		echo '
						</div>
						<div class="pmx_tbl_tdgrid" style="width:57%;border-color:transparent;">';

		// start title edit popup
		echo '
							<div id="pmxSetTitle" class="smalltext" style="width:420px;z-index:9999;display:none;margin-top:-32px;">
								'. pmx_popupHeader('pmxSetTitle', $txt['pmx_edit_titles'], '112px') .'
									<div style="float:left; width:75px;">'. $txt['pmx_edit_title'] .'</div>
									<input id="pWind.text" style="width:310px;" type="text" value="" />
									<input id="pWindID" type="hidden" value="" />
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
										<input id="iconDD" value="'. (isset($category['config']['title_icon']) ? ucfirst(str_replace('.png', '', $category['config']['title_icon'])) : 'None') .'" readonly />
										<img id="pWind.icon" class="pwindicon" src="'. $context['pmx_shortIconsurl'] .'none.png" alt="*" />
										<img class="ddImage" src="'. $context['pmx_imageurl'] .'state_expand.png" alt="*" title="" />
									</div>
									<ul class="ttlicondd" id="pWind.icon_sel" onclick="updIcon(this)">';

		foreach($cfg_titleicons as $file => $name)
			echo '
										<li id="'. $file .'" class="ttlicon'. (isset($category['config']['title_icon']) && $category['config']['title_icon'] == $file ? ' active' : '') .'">
											<img src="'. $context['pmx_shortIconsurl'] . $file .'" alt="*" /><span>'. $name .'</span>
										</li>';

		echo '
									</ul>
									<script>$("li").hover(function(){$(this).toggleClass("active")});</script>
								</div>
							</div>';
		// end title edit popup

		echo '
						</div>
						<div class="pmx_tbl_tdgrid" style="width:34%;border-color:transparent;">';

		// Categorie name popup
		echo '
							<div id="pmxSetCatName" class="smalltext" style="width:280px;z-index:9999;margin-top:-29px;display:none;">
								'. pmx_popupHeader('pmxSetCatName', $txt['pmx_categories_setname']) .'
									<div style="float:left;width:140px; height:25px;">'. $txt['pmx_categories_name'] .':
										<a href="', $scripturl, '?action=helpadmin;help=pmx_edit_pagenamehelp" onclick="return reqOverlayDiv(this.href);" class="help"><span class="generic_icons help" title="', $txt['help'],'"></span></a>
									</div>
									<span id="check.name.error" style="display:none;">'. sprintf($txt['namefielderror'], $txt['pmx_categories_name']) .'</span>
									<div style="height:25px;">
										<input id="check.name" style="width:160px;" onkeyup="check_requestname(this)" onkeypress="check_requestname(this)" type="text" value="" />
									</div>
									<div style="text-align:right; height:20px;">
										<input class="button_submit" type="button" value="'. $txt['pmx_update_save'] .'" onclick="pmxUpdateCatName()" />
									</div>
								</div>
							</div>';
							// end Categorie name popup

		echo '
						</div>
						<div class="pmx_tbl_td" style="width:91px;">';

		// start articles in cat popup
		echo '
							<div id="pmxShowArt" class="smalltext" style="width:250px;z-index:9999;margin-top:-32px;display:none;">
								'. pmx_popupHeader('pmxShowArt', $txt['pmx_categories_showarts']) .'
									<div id="artsorttxt" style="margin-top:-5px;"></div>
									<div id="artsort" class="smalltext" style="max-height: 30px; overflow:auto;"></div><hr class="pmx_hr" />
									<div id="showarts" style="max-height: 170px; overflow:auto;"></div>
								</div>
							</div>';
		// start articles in cat popup

		echo '
						</div>
						<div class="pmx_tbl_td" style="width:91px;">';

		// start Access popup
		echo '
							<div id="pmxSetAcs" class="smalltext" style="width:310px;z-index:9999;display:none;margin-top:-32px;">
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

		// start Clone popup
		echo '
						<div id="pmxSetCatClone" class="smalltext" style="width:220px;margin-top:-32px;z-index:9999;display:none;">
							'. pmx_popupHeader('pmxSetCatClone', $txt['pmx_cat_clone']) .'
								<div>'. $txt['pmx_confirm_catclone'] .'</div>
								<input id="pWind.catcloneid" type="hidden" value="" />
								<input style="float:right;font-size:11px;margin-top:5px" class="button_submit" type="button" value="'. $txt['pmx_delete_button'] .'" onclick="pmxSendCatClone()" />
								<div style="height:25px;"></div>
							</div>
						</div>';
		// end Clone popup

			// start delete popup
			echo '
						<div id="pmxSetCatDelete" class="smalltext" style="width:220px;;margin-top:-32px;z-index:9999;display:none;">
							'. pmx_popupHeader('pmxSetCatDelete', $txt['pmx_cat_delete']) .'
								<div>'. $txt['pmx_confirm_catdelete'] .'</div>
								<input id="pWind.catdelid" type="hidden" value="" />
								<input style="float:right;font-size:11px;margin-top:5px;" class="button_submit" type="button" value="'. $txt['pmx_delete_button'] .'" onclick="pmxSendCatDelete()" />
								<div style="height:25px;"></div>
							</div>
						</div>';
			// end delete popup

		echo '
					</div>
				</div>
			</div>
		</div>';
	}

	// --------------------
	// singlecategorie edit
	// --------------------
	elseif($context['pmx']['subaction'] == 'edit' || $context['pmx']['subaction'] == 'editnew')
	{
		echo '
			<table class="pmx_table" style="margin-bottom:5px;table-layout:fixed;">
				<tr>
					<td style="text-align:center">
						<div class="cat_bar" style="border-bottom-left-radius:6px;border-bottom-right-radius:6px">
							<h3 class="catbg">
								'. $txt['pmx_categories_edit'] .'
							</h3>
						</div>
					</td>
				</tr>';

		// call the ShowAdmCategoryConfig() methode
		$context['pmx']['editcategory']->pmxc_ShowAdmCategoryConfig();

		echo '
			</table>';
	}

	echo '
		</form>';
}

/**
* AdmCategoryOverview
* Called for each category.
*/
function PmxCategoryOverview($category)
{
	global $context, $txt;
	global $cfg_smfgroups;

	$category['config'] = pmx_json_decode($category['config'], true);
	if(empty($category['config']['title_align']))
		$category['config']['title_align'] = 'left';
	if(empty($category['config']['title_icon']))
		$category['config']['title_icon'] = 'none.png';

	echo '
					<div class="pmx_tbl_tr">';

	// Move row
	echo '
						<div class="pmx_tbl_tdgrid" id="RowMove-'. $category['id'] .'" style="white-space:nowrap;">
							<div class="pmx_clickrow'. (count($context['pmx']['catorder']) > 1 ? ' pmx_moveimg" title="'. $txt['pmx_move_categories'] .'" onclick="pmxSetMove(\''. $category['id'] .'\', this)"' : '"') .'>
								<div style="padding-left:20px;margin-top:-2px;width:22px;">'. $category['catorder'] .'</div>
							</div>
						</div>';

	// title row
	echo '
						<div class="pmx_tbl_tdgrid">
							<div onclick="pmxSetTitle(\''. $category['id'] .'\', \'cat\', this)"  title="'. $txt['pmx_click_edit_ttl'] .'" style="cursor:pointer;">
								<img id="uTitle.icon.'. $category['id'] .'" style="padding-right:4px;" src="'. $context['pmx_Iconsurl'] . $category['config']['title_icon'] .'" alt="*" title="'. substr($txt['pmx_edit_titleicon'], 0, -1) .'" />
								<img id="uTitle.align.'. $category['id'] .'" src="'. $context['pmx_imageurl'] .'text_align_'. $category['config']['title_align'] .'.gif" alt="*" title="'. $txt['pmx_edit_title_align'] . $txt['pmx_edit_title_align_types'][$category['config']['title_align']] .'" />
								<span id="sTitle.text.'. $category['id'] .'.cat">'. (isset($category['config']['title'][$context['pmx']['currlang']]) ? htmlspecialchars($category['config']['title'][$context['pmx']['currlang']], ENT_QUOTES) : '') .'</span>';

	foreach($context['pmx']['languages'] as $lang => $sel)
		echo '
								<input id="sTitle.text.'. $lang .'.'. $category['id'] .'.cat" type="hidden" value="'. (isset($category['config']['title'][$lang]) ? htmlspecialchars($category['config']['title'][$lang], ENT_QUOTES) : '') .'" />';

	echo '
								<input id="sTitle.icon.'. $category['id'] .'" type="hidden" value="'. $category['config']['title_icon'] .'" />
								<input id="sTitle.align.'. $category['id'] .'" type="hidden" value="'. $category['config']['title_align'] .'" />
							</div>
						</div>';

	// name row
	$details = PortaMx_getCatDetails($category, $context['pmx']['categories']);
	echo '
						<div class="pmx_tbl_tdgrid" style="cursor:pointer;" onclick="pmxSetCatName(\''. $category['id'] .'\', this)">
							<input id="pWind.parent.id.'. $category['id'] .'" type="hidden" value="'. $category['parent'] .'" />
							<input id="pWind.move.cat.'. $category['id'] .'" type="hidden" value="['. $category['catorder'] .']'. ($category['level'] > 0 ? ' ' : '') . str_repeat('&bull;', $category['level']) .' '. $category['name'] .'" />
							<div id="pmxSetMove.'. $category['id'] .'" title="'. $details['parent'] . $txt['pmx_editname_categories'] .'" class="'. $details['class'] .'"><b>'. $details['level'] .'</b>
								<span id="pmxSetAcs.'. $category['id'] .'"><span id="pWind.cat.name.'. $category['id'] .'" class="cat_names">'. $category['name'] .'</span></span>
							</div>
						</div>';

	if(!empty($category['acsgrp']))
		list($grpacs, $denyacs) = Pmx_StrToArray($category['acsgrp'], ',', '=');
	else
		$grpacs = $denyacs = array();

	$groups = array();
	foreach($cfg_smfgroups as $grp)
	{
		if(in_array($grp['id'], $grpacs))
			$groups[] = '+'. $grp['id'] .'='. intval(!in_array($grp['id'], $denyacs));
		else
			$groups[] = ':'. $grp['id'] .'=1';
	}

	$sort = array();
	$catarts = array();
	$sorts = explode(',', $category['artsort']);
	foreach($sorts as $s)
		$sort[] = htmlentities($txt['pmx_categories_artsort'][str_replace(array('=0', '=1'), array('', ''), $s)], ENT_QUOTES, $context['pmx']['encoding']) . $txt['pmx_artsort'][intval(substr($s, -1, 1))];

	if(!empty($category['articles']))
		foreach($category['articles'] as $arts)
			$catarts[] = '['. $arts['id'] .'] '. $arts['name'];

	// create acs groups for acs Popup
	if(!empty($category['acsgrp']))
		list($grpacs, $denyacs) = Pmx_StrToArray($category['acsgrp'], ',', '=');
	else
		$grpacs = $denyacs = array();

	// options row
	echo '
						<div class="pmx_tbl_tdgrid">
							<input id="grpAcs.'. $category['id'] .'" type="hidden" value="'. implode(',', $grpacs) .'" />
							<input id="denyAcs.'. $category['id'] .'" type="hidden" value="'. implode(',', $denyacs) .'" />
							<input id="pWind.catarts.'. $category['id'] .'" type="hidden" value="'. implode('|', $catarts) .'" />
							<input id="pWind.artsorttxt.'. $category['id'] .'" type="hidden" value="'. $txt['pmx_categorie_articlesort'] .'" />
							<input id="pWind.artsort.'. $category['id'] .'" type="hidden" value="'. implode('|', $sort) .'" />
							<div id="pWind.grp.'. $category['id'] .'" class="pmx_clickrow'. (!empty($category['acsgrp']) ? ' pmx_access" title="'. $txt['pmx_categories_groupaccess'] : '') .'"></div>
							<div class="pmx_clickrow'. (!empty($category['config']['cssfile']) ? ' pmx_custcss" title="'. $txt['pmx_categories_cssfile'] : '') .'"></div>
							<div class="pmx_clickrow'. (!empty($category['config']['check_ecl']) ? ' pmx_eclsettings" title="'. $txt['pmx_have_catecl_settings'] : '') .'"></div>
							<div class="pmx_clickrow'. (!empty($category['artsum']) ? ' pmx_articles" title="'. sprintf($txt['pmx_categories_articles'], $category['artsum']) .'" onclick="pmxShowArt(\''. $category['id'] .'\', this)"' : '"') .'></div>
						</div>';

	// functions row
	echo '
						<div class="pmx_tbl_tdgrid" id="func-row">
							<div class="pmx_clickrow pmx_pgedit" title="'. $txt['pmx_edit_categories'].'" onclick="FormFunc(\'edit_category\', \''. $category['id'] .'\')"></div>
							<div class="pmx_clickrow pmx_grpacs" title="'. $txt['pmx_chg_categoriesaccess'] .'" onclick="pmxSetAcs(\''. $category['id'] .'\', \'cat\', this)"></div>
							<div class="pmx_clickrow pmx_pgclone" title="'. $txt['pmx_clone_categories'] .'"  onclick="pmxSetCatClone(\''. $category['id'] .'\', this)"></div>
							<div class="pmx_clickrow pmx_pgdelete" title="'. $txt['pmx_delete_categories'] .'" onclick="pmxSetCatDelete(\''. $category['id'] .'\', this)"></div>
						</div>
					</div>';
}

/**
* Popup Header bar
**/
function pmx_popupHeader($tag, $title = '', $height = 0)
{
	global $context, $txt;

	return '
				<div class="cat_bar catbg_grid" style="cursor:pointer;margin-bottom:0;margin-top:9px;" onclick="pmxRemovePopup()" title="'. $txt['pmx_clickclose'] .'">
					<h4 class="catbg catbg_grid">
						<img class="grid_click_image pmxright" src="'. $context['pmx_imageurl'] .'cross.png" alt="close" style="padding-left:10px;" />
						<span>'. $title .'</span>
					</h4>
				</div>
				<div id="'. $tag .'.body" class="roundframe" style="padding-top:8px;border-radius: 0 0 5px 5px;margin-top:-2px'. (!empty($height) ? ';height:'. $height .';overflow:hidden' : '') .';">';
}
?>