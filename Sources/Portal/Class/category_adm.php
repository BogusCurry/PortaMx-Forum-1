<?php
/**
 * PortaMx Forum
 * @package PortaMx
 * @author PortaMx
 * @copyright 2017 PortaMx
 *
 * file category_adm.php
 * Admin Systemblock category
 *
 * @version 1.0 RC1
 */

if(!defined('PMX'))
	die('This file can\'t be run without PortaMx-Forum');

/**
* @class pmxc_category_adm
* Admin Systemblock category_adm
* @see category_adm.php
*/
class pmxc_category_adm extends PortaMxC_SystemAdminBlock
{
	var $categories;

	/**
	* AdmBlock_init().
	* Setup caching and get categories.
	*/
	function pmxc_AdmBlock_init()
	{
		$this->can_cached = 1;		// enable caching
		$this->categories = PortaMx_getCategories();
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
							<input type="hidden" name="config[settings]" value="" />
							<input type="hidden" name="config[static_block]" value="1" />';

		// show the settings screen
		echo '
							<div class="cat_bar catbg_grid grid_padd">
								<h4 class="catbg catbg_grid"><span class="cat_left_title">'. sprintf($txt['pmx_blocks_settings_title'], $this->register_blocks[$this->cfg['blocktype']]['description']) .'</span></h4>
							</div>
							<div class="adm_input adm_sel">
								<span>'. $txt['pmx_catblock_cats'] .'</span>
								<select style="width:83%" name="config[settings][category]" size="1">';

		// output cats
		foreach($context['pmx']['catorder'] as $order)
		{
			$cat = PortaMx_getCatByOrder($this->categories, $order);
			echo '
									<option value="'. $cat['name'] .'"' .(isset($this->cfg['config']['settings']['category']) && $this->cfg['config']['settings']['category'] == $cat['name'] ? ' selected="selected"' : '') .'>'. str_repeat('&bull;', $cat['level']) .' '. $cat['name'] .'</option>';
		}

		echo '
								</select>
							</div>';

		// show mode (titelbar/frame)
		$this->cfg['config']['settings']['usedframe'] = !isset($this->cfg['config']['settings']['usedframe']) ? 'block' : $this->cfg['config']['settings']['usedframe'];
		echo '
							<div class="adm_check">
								<span class="adm_w80">'. $txt['pmx_catblock_blockframe'] .'</span>
								<div><input class="input_check" type="radio" name="config[settings][usedframe]" value="block"' .(isset($this->cfg['config']['settings']['usedframe']) && $this->cfg['config']['settings']['usedframe'] == 'block' ? ' checked="checked"' : '') .' /></div>
							</div>
							<div class="adm_check">
								<span class="adm_w80">'. $txt['pmx_catblock_catframe'] .'</span>
								<div><input class="input_check" type="radio" name="config[settings][usedframe]" value="cat"' .(isset($this->cfg['config']['settings']['usedframe']) && $this->cfg['config']['settings']['usedframe'] == 'cat' ? ' checked="checked"' : '') .' /></div>
							</div>
							<div class="adm_check">
								<span class="adm_w80">'. $txt['pmx_catblock_inherit'] .'
									<img class="info_toggle" onclick=\'Show_help("pmxcatH01")\' src="'. $context['pmx_imageurl'] .'information.png" alt="*" title="'. $txt['pmx_information_icon'] .'" />
								</span>
								<input type="hidden" name="config[settings][inherit_acs]" value="0" />
								<div><input class="input_check" type="checkbox" name="config[settings][inherit_acs]" value="1"' .(!empty($this->cfg['config']['settings']['inherit_acs']) ? ' checked="checked"' : '') .' /></div>
							</div>
							<div id="pmxcatH01" class="info_frame" style="margin-top:4px;">'. $txt['pmx_catblock_inherithelp'] .'</div>
							<input type="hidden" name="config[show_sitemap]" value="0" />
						</div>';

		// return the used classnames
		return PortaMx_getdefaultClass(false, true);  // default classdef
	}
}
?>