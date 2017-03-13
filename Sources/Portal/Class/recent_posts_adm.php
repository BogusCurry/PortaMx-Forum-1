<?php
/**
 * PortaMx Forum
 * @package PortaMx
 * @author PortaMx
 * @copyright 2017 PortaMx
 *
 * file recent_posts_adm.php
 * Admin Systemblock recent_posts
 *
 * @version 1.0 RC1
 */

if(!defined('PMX'))
	die('This file can\'t be run without PortaMx-Forum');

/**
* @class pmxc_recent_posts_adm
* Admin Systemblock recent_posts_adm
* @see recent_posts_adm.php
*/
class pmxc_recent_posts_adm extends PortaMxC_SystemAdminBlock
{
	/**
	* AdmBlock_init().
	* Setup caching.
	*/
	function pmxc_AdmBlock_init()
	{
		$this->can_cached = 1;			// enable caching
	}

	/**
	* AdmBlock_settings().
	* Setup the config vars and output the block settings.
	* Returns the css classes they are used.
	*/
	function pmxc_AdmBlock_settings()
	{
		global $context, $txt;

		// define the settings options
		echo '
					<td width="50%" valign="top" style="padding:4px;">
						<div style="min-height:195px;">
							<input type="hidden" name="config[settings]" value="" />';

		// define numeric vars to check
		echo '
							<input type="hidden" name="check_num_vars[]" value="[config][settings][numrecent], 5" />';

		// show the settings screen
		echo '
							<div class="cat_bar catbg_grid grid_padd">
								<h4 class="catbg catbg_grid"><span class="cat_left_title">'. sprintf($txt['pmx_blocks_settings_title'], $this->register_blocks[$this->cfg['blocktype']]['description']) .'</span></h4>
							</div>

							<div class="adm_input adm_sel">
								<span class="adm_w80">'. $txt['pmx_recent_boards'] .'
									<img class="info_toggle" onclick=\'Show_help("pmxRPH1")\' src="'. $context['pmx_imageurl'] .'information.png" alt="*" title="'. $txt['pmx_information_icon'] .'" />
								</span>
								<select class="adm_w90" name="config[settings][recentboards][]" multiple="multiple" size="4">';

		$boards = isset($this->cfg['config']['settings']['recentboards']) ? $this->cfg['config']['settings']['recentboards'] : array();
		foreach($this->smf_boards as $brd)
			echo '
									<option value="'. $brd['id'] .'"'. (in_array($brd['id'], $boards) ? ' selected="selected"' : '') .'>'. $brd['name'] .'</option>';

		echo '
								</select>
								<div id="pmxRPH1" class="info_frame" style="margin-top:5px;">'. $txt['pmx_recent_boards_help'] .'</div>
							</div>

							<div class="adm_input">
								<span class="adm_w80">'. $txt['pmx_recentpostnum'] .'</span>
								<div><input onkeyup="check_numeric(this);" size="2" type="text" name="config[settings][numrecent]" value="' .(isset($this->cfg['config']['settings']['numrecent']) ? $this->cfg['config']['settings']['numrecent'] : '5'). '" /></div>
							</div>
							<div class="adm_check">
								<span class="adm_w80">'. $txt['pmx_recent_showboard'] .'</span>
								<input type="hidden" name="config[settings][showboard]" value="0" />
								<div><input class="input_check" type="checkbox" name="config[settings][showboard]" value="1"' .(!empty($this->cfg['config']['settings']['showboard']) ? ' checked="checked"' : '') .' /></div>
							</div>
							<div class="adm_check">
								<span class="adm_w80">'. $txt['pmx_recentsplit'] .'
									<img class="info_toggle" onclick=\'Show_help("pmxRPH2")\' src="'. $context['pmx_imageurl'] .'information.png" alt="*" title="'. $txt['pmx_information_icon'] .'" />
								</span>
								<div><input class="input_check" type="checkbox" name="config[settings][recentsplit]" value="1"' .(!empty($this->cfg['config']['settings']['recentsplit']) ? ' checked="checked"' : '') .' /></div>
								<div id="pmxRPH2" class="info_frame" style="margin-top:5px;">'. $txt['pmx_recentsplit_help'] .'</div>
							</div>
							<input type="hidden" name="config[show_sitemap]" value="0" />
						</div>';

		// return the default classnames
		return $this->block_classdef;
	}
}
?>