<?php
/**
 * PortaMx Forum
 * @package PortaMx
 * @author PortaMx
 * @copyright 2017 PortaMx
 *
 * file bbc_script.php
 * Systemblock BBC_SCRIPT
 *
 * @version 1.0 RC3
 */

if(!defined('PMX'))
	die('This file can\'t be run without PortaMx-Forum');

/**
* @class pmxc_bbc_script
* Systemblock BBC_SCRIPT
* @see bbc_script.php
*/
class pmxc_bbc_script extends PortaMxC_SystemBlock
{
	/**
	* ShowContent
	*/
	function pmxc_ShowContent()
	{
		global $context, $txt;

		$noLB = empty($modSettings['use_lightbox']) || !empty($this->cfg['config']['settings']['disableHSimg']);
		$context['lbimage_data'] = array('lightbox_id' => (empty($noLB) ? $this->cfg['blocktype'] .'-'. $this->cfg['id'] : null));

		if(!empty($this->cfg['config']['settings']['printing']))
		{
			$printdir = 'ltr';
			$printChars = $context['character_set'];

			echo '
			<img class="pmx_printimg" src="'. $context['pmx_imageurl'] .'Print.png" alt="Print" title="'. $txt['pmx_text_printing'] .'" onclick="PmxPrintPage(\''. $printdir .'\', \''. $this->cfg['id'] .'\', \''. $printChars .'\', \''. $this->getUserTitle() .'\', \''. $txt['lightbox_help'] .'\', \''. $txt['lightbox_label'] .'\')" />
			<div id="print'. $this->cfg['id'] .'">';
		}

		// Write out bbc parsed content
		echo '
			'. parse_bbc($this->cfg['content'], true);

		if(!empty($this->cfg['config']['settings']['printing']))
		{
			echo '
			</div>';
		}
	}
}
?>