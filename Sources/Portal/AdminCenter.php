<?php
/**
 * PortaMx Forum
 * @package PortaMx
 * @author PortaMx
 * @copyright 2017 PortaMx
 *
 * file AdminCenter.php
 * Portal Admininistration Center.
 *
 * @version 1.0 RC1
 */

if(!defined('PMX'))
	die('This file can\'t be run without PortaMx-Forum');

/**
* Portal Admin Center.
* Finally load the templare.
*/
function Portal_AdminCenter()
{
	global $context, $txt;

	// fix the linktree
	foreach($context['linktree'] as $key => $data)
	{
		if(strpos($data['url'], 'pmx_center') !== false)
		{
			$context['linktree'][$key]['name'] = $txt['pmx_extension'];
			break;
		}
	}

	loadTemplate($context['pmx_templatedir'] .'AdminCenter');
}
?>
