<?php
/**
 * PortaMx Forum
 * @package PortaMx
 * @author PortaMx
 * @copyright 2017 PortaMx
 *
 * file fader.php
 * Systemblock FADER
 *
 * @version 1.0 RC3
 */

if(!defined('PMX'))
	die('This file can\'t be run without PortaMx-Forum');

/**
* @class pmxc_fader
* Systemblock FADER
* @see fader.php
*/
class pmxc_fader extends PortaMxC_SystemBlock
{
	var $faderdata;

	/**
	* InitContent.
	* Checks the cache status and create the content.
	*/
	function pmxc_InitContent()
	{
		global $pmxCacheFunc, $modSettings;

		// if visible init the content
		if($this->visible)
		{
			if($this->cfg['cache'] > 0)
			{
				// check the block cache
				if(($this->faderdata = $pmxCacheFunc['get']($this->cache_key, $this->cache_mode)) === null)
				{
					$this->getFaderData();
					$pmxCacheFunc['put']($this->cache_key, $this->faderdata, $this->cache_time, $this->cache_mode);
				}
			}
			else
				$this->getFaderData();

			if(empty($modSettings['pmxFaderLoaded']))
			{
				loadJavascriptFile(PortaMx_loadCompressed('PortalFader.js'), array('external' => true));
				$modSettings['pmxFaderLoaded'] = true;
			}
		}
		// return the visibility flag (true/false)
		return $this->visible;
	}

	/**
	* Get Fader Data.
	*/
	function getFaderData()
	{
		$this->faderdata = array(
			'lines' => '',
			'up' => '',
			'down' => '',
			'hold' => ''
		);
		preg_match_all('~\{(.*)((\}.?=.?\(([0-9\.\,\s]+)\))|\})(\s+|\t+|\r|\n|$|\r|\n|$)~Ums', str_replace("'", "\'", $this->cfg['content']), $faderlines, PREG_PATTERN_ORDER);
		if(isset($faderlines[1]) && !empty($faderlines[1]))
		{
			foreach($faderlines[1] as $i => $value)
			{
				$fdata = trim(preg_replace(array('~>\s+<~', '~\s+~', '~\n+~', '~\r+~', '~\t+~'), array('><', ' ', '', '', ''), $value));
				if(!empty($fdata))
				{
					$this->faderdata['lines'] .= "\n".'\''. $fdata .'\',';
					$fdt = array();
					if(!empty($faderlines[4][$i]))
					{
						$fdt = explode(',', $faderlines[4][$i]);
						array_walk($fdt, create_function('&$v,$k', '$v = floatval(trim($v));'));
					}
					$this->faderdata['up'] .= (!empty($fdt[0]) ? $fdt[0] * 1000 : $this->cfg['config']['settings']['uptime'] * 1000) .',';
					$this->faderdata['down'] .= (!empty($fdt[1]) ? $fdt[1] * 1000 : $this->cfg['config']['settings']['downtime'] * 1000) .',';
					$this->faderdata['hold'] .= (!empty($fdt[2]) ? $fdt[2] * 1000 : $this->cfg['config']['settings']['holdtime'] * 1000) .',';
				}
			}
			$this->faderdata['lines'] = '['. rtrim($this->faderdata['lines'], ',') .']';
			$this->faderdata['up'] = '['. rtrim($this->faderdata['up'], ',') .']';
			$this->faderdata['down'] = '['. rtrim($this->faderdata['down'], ',') .']';
			$this->faderdata['hold'] = '['. rtrim($this->faderdata['hold'], ',') .']';
		}
	}

	/**
	* ShowContent
	* Create the fader object and output the content.
	*/
	function pmxc_ShowContent()
	{
		global $context, $txt;

		if(!empty($this->faderdata))
		{
			// create the fader object
			$start = get_cookie('pmx_oFader'. $this->cfg['id']);
			echo '
				<div id="pmxfader'. $this->cfg['id'] .'"></div>
				<script type="text/javascript">
				var oFader'. $this->cfg['id'] .' = new PmxOpacFader({
					fadeName: \'oFader'. $this->cfg['id'] .'\',
					fadeUptime: '. $this->faderdata['up'] .',
					fadeDowntime: '. $this->faderdata['down'] .',
					fadeHoldtime: '. $this->faderdata['hold'] .',
					fadeChangetime: '. $this->cfg['config']['settings']['changetime'] * 1000 .',
					fadeContId: \'pmxfader'. $this->cfg['id'] .'\',
					fadeData: '. $this->faderdata['lines'] .',
					fadeCsr: '. (is_null($start) ? 0 : $start) .'
				});
				</script>';
		}
	}
}
?>