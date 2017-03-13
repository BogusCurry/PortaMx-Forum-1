<?php
/**
 * PortaMx Forum
 * @package PortaMx
 * @author PortaMx
 * @copyright 2017 PortaMx
 *
 * file statistics_adm.php
 * Admin Systemblock statistics
 *
 * @version 1.0 RC1
 */

if(!defined('PMX'))
	die('This file can\'t be run without PortaMx-Forum');

/**
* @class pmxc_statistics_adm
* Admin Systemblock statistics_adm
* @see statistics_adm.php
*/
class pmxc_statistics_adm extends PortaMxC_SystemAdminBlock
{
	/**
	* AdmBlock_init().
	* Setup caching.
	*/
	function pmxc_AdmBlock_init()
	{
		$this->can_cached = 1;		// enable caching
	}

	/**
	* AdmBlock_settings().
	* Setup the config vars and output the block settings.
	* Returns the css classes they are used.
	*/
	function pmxc_AdmBlock_settings()
	{
		global $context, $txt;

		// define additional classnames and styles
		$used_classdef = $this->block_classdef;
		$used_classdef['stats_text'] = array(
			' '. $txt['pmx_default_none'] => '',
			' smalltext' => 'smalltext',
			' middletext' => 'middletext',
			'+normaltext' => 'normaltext',
			' largetext' => 'largetext',
		);

		// define the settings options
		echo '
					<td width="50%" valign="top" style="padding:4px;">
						<div style="min-height:195px;">
							<input type="hidden" name="config[settings]" value="" />';

		// define numeric vars to check
		echo '
							<input type="hidden" name="check_num_vars[]" value="[config][settings][stat_olheight], 10" />';

		// show the settings screen
		echo '
							<div class="cat_bar catbg_grid grid_padd">
								<h4 class="catbg catbg_grid"><span class="cat_left_title">'. sprintf($txt['pmx_blocks_settings_title'], $this->register_blocks[$this->cfg['blocktype']]['description']) .'</span></h4>
							</div>

							<div class="adm_check">
								<span class="adm_w80">'. $txt['pmx_admstat_member'] .'</span>
								<input type="hidden" name="config[settings][stat_member]" value="0" />
								<div><input class="input_check" type="checkbox" name="config[settings][stat_member]" value="1"' .(isset($this->cfg['config']['settings']['stat_member']) && !empty($this->cfg['config']['settings']['stat_member']) ? ' checked="checked"' : ''). ' /></div>
							</div>
							<div class="adm_check">
								<span class="adm_w80">'. $txt['pmx_admstat_stats'] .'</span>
								<input type="hidden" name="config[settings][stat_stats]" value="0" />
								<div><input class="input_check" type="checkbox" name="config[settings][stat_stats]" value="1"' .(isset($this->cfg['config']['settings']['stat_stats']) && !empty($this->cfg['config']['settings']['stat_stats']) ? ' checked="checked"' : ''). ' /></div>
							</div>
							<div class="adm_check">
								<span class="adm_w80">'. $txt['pmx_admstat_users'] .'</span>
								<input type="hidden" name="config[settings][stat_users]" value="0" />
								<div><input class="input_check" type="checkbox" name="config[settings][stat_users]" value="1"' .(isset($this->cfg['config']['settings']['stat_users']) && !empty($this->cfg['config']['settings']['stat_users']) ? ' checked="checked"' : ''). ' /></div>
							</div>
							<div class="adm_check">
								<span class="adm_w80">'. $txt['pmx_admstat_spider'] .'</span>
								<input type="hidden" name="config[settings][stat_spider]" value="0" />
								<div><input class="input_check" type="checkbox" name="config[settings][stat_spider]" value="1"' .(isset($this->cfg['config']['settings']['stat_spider']) && !empty($this->cfg['config']['settings']['stat_spider']) ? ' checked="checked"' : ''). ' /></div>
							</div>
							<div class="adm_input">
								<span class="adm_w80">'. $txt['pmx_admstat_olheight'] .'
									<img class="info_toggle" onclick=\'Show_help("pmxSTH01")\' src="'. $context['pmx_imageurl'] .'information.png" alt="*" title="'. $txt['pmx_information_icon'] .'" />
								</span>
								<div><input onkeyup="check_numeric(this);" size="3" type="text" name="config[settings][stat_olheight]" value="' .(isset($this->cfg['config']['settings']['stat_olheight']) ? $this->cfg['config']['settings']['stat_olheight'] : '10'). '" /></div>
							</div>
							<div id="pmxSTH01" class="info_frame" style="margin-top:4px;">'. $txt['pmx_admstat_olheight_help'] .'</div>
								<input type="hidden" name="config[show_sitemap]" value="0" />
							</div>
						</div>';

		// return the used classnames
		return $used_classdef;
	}
}
?>