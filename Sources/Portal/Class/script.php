<?php
/**
 * PortaMx Forum
 * @package PortaMx
 * @author PortaMx
 * @copyright 2017 PortaMx
 *
 * file script.php
 * Systemblock SCRIPT
 *
 * @version 1.0 RC2
 */

if(!defined('PMX'))
	die('This file can\'t be run without PortaMx-Forum');

/**
* @class pmxc_script
* Systemblock SCRIPT
* @see script.php
*/
class pmxc_script extends PortaMxC_SystemBlock
{
	/**
	* ShowContent
	* Check for PHP inside and output the content.
	*/
	function pmxc_ShowContent()
	{
		global $context, $modSettings, $txt;

		// check for inside php code
		$havePHP = PortaMx_GetInsidePHP($this->cfg['content']);

		// remove or add highslide code
		$noLB = !empty($modSettings['dont_use_lightbox']) || !empty($this->cfg['config']['settings']['disableHSimg']);
		$context['lbimage_data'] = array('lightbox_id' => (empty($noLB) ? $this->cfg['blocktype'] .'-'. $this->cfg['id'] : null));
		$this->cfg['content'] = pmx_ContentLightBox($this->cfg['content']);

		// Write out the content
		if(!empty($this->cfg['config']['settings']['printing']))
		{
			$printdir = 'ltr';
			$printChars = $context['character_set'];

			echo '
			<img class="pmx_printimg" src="'. $context['pmx_imageurl'] .'Print.png" alt="Print" title="'. $txt['pmx_text_printing'] .'" onclick="PmxPrintPage(\''. $printdir .'\', \''. $this->cfg['id'] .'\', \''. $printChars .'\', \''. $this->getUserTitle() .'\', \''. $txt['lightbox_help'] .'\', \''. $txt['lightbox_label'] .'\')" />
			<div id="print'. $this->cfg['id'] .'">';
		}

		if(!empty($havePHP))
			eval($this->cfg['content']);
		else
			echo $this->cfg['content'];

		if(!empty($this->cfg['config']['settings']['printing']))
			echo '
			</div>';
	}
}
?>