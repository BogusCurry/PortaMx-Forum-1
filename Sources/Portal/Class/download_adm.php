<?php
/**
 * PortaMx Forum
 * @package PortaMx
 * @author PortaMx
 * @copyright 2017 PortaMx
 *
 * file download_adm.php
 * Admin Systemblock download
 *
 * @version 1.0 RC1
 */

if(!defined('PMX'))
	die('This file can\'t be run without PortaMx-Forum');

/**
* @class pmxc_download_adm
* Admin Systemblock download_adm
* @see download_adm.php
*/
class pmxc_download_adm extends PortaMxC_SystemAdminBlock
{
	/**
	* AdmBlock_init().
	* Setup caching.
	*/
	function pmxc_AdmBlock_init()
	{
		$this->can_cached = 0;		// disable caching
	}

	/**
	* AdmBlock_settings().
	* Setup the config vars and output the block settings.
	* Returns the css classes they are used.
	*/
	function pmxc_AdmBlock_settings()
	{
		global $txt;

		// define the settings options
		echo '
					<td width="50%" valign="top" style="padding:4px;">
						<div style="min-height:195px">
							<input type="hidden" name="config[settings]" value="" />';

		// show the settings screen
		echo '
							<div class="cat_bar catbg_grid grid_padd">
								<h4 class="catbg catbg_grid"><span class="cat_left_title">'. sprintf($txt['pmx_blocks_settings_title'], $this->register_blocks[$this->cfg['blocktype']]['description']) .'</span></h4>
							</div>

							<div class="adm_input">
								<span>'. $txt['pmx_download_board'] .'</span>
								<select class="adm_w90 adm_select" name="config[settings][download_board]">';

		$dlboard = isset($this->cfg['config']['settings']['download_board']) ? $this->cfg['config']['settings']['download_board'] : 0;
		foreach($this->smf_boards as $brd)
			echo '
									<option value="'. $brd['id'] .'"'. ($brd['id'] == $dlboard ? ' selected="selected"' : '') .'>'. $brd['name'] .'</option>';

		echo '
								</select>
							</div>

							<div class="adm_input adm_select">
								<span>'. $txt['pmx_download_groups'] .'</span>
								<input type="hidden" name="config[settings][download_acs][]" value="" />
								<select class="adm_w90" name="config[settings][download_acs][]" multiple="multiple" size="5">';

		foreach($this->smf_groups as $grp)
			echo '
									<option value="'. $grp['id'] .'"'. (!empty($this->cfg['config']['settings']['download_acs']) && in_array($grp['id'], $this->cfg['config']['settings']['download_acs']) ? ' selected="selected"' : '') .'>'. $grp['name'] .'</option>';
		echo '
								</select>
							</div>
							<input type="hidden" name="config[show_sitemap]" value="0" />
						</div>';

		// return the used classnames
		return $this->block_classdef;
	}

	/**
	* AdmBlock_content().
	* Load the BBC Editor, to create or edit the content.
	* Returns the AdmBlock_settings
	*/
	function pmxc_AdmBlock_content()
	{
		global $context, $txt;

		// show the content area
		echo '
					<td valign="top" colspan="2" style="padding:4px;">
						<div class="cat_bar catbg_grid" style="margin-right:1px;">
							<h4 class="catbg catbg_grid"><span class="cat_left_title">'. $txt['pmx_edit_content'] .'</span></h4>
						</div>
						<input type="hidden" id="smileyset" value="PortaMx" />
						<div id="bbcBox_message"></div>
						<div id="smileyBox_message"></div>
						<div style=padding-right:3px;margin-top:-10px;">', template_control_richedit($context['pmx']['editorID'], 'smileyBox_message', 'bbcBox_message'), '</div>
					</td>
				</tr>
				<tr>';

		// return the default settings
		return $this->pmxc_AdmBlock_settings();
	}
}
?>