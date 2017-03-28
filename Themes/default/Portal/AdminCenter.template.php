<?php
/**
 * PortaMx Forum
 * @package PortaMx
 * @author PortaMx
 * @copyright 2017 PortaMx
 *
 * file AdminCenter.template.php
 * Template for the Admin Center.
 *
 * @version 1.0 RC2
 */

function template_main()
{
	global $context, $scripturl, $txt;

	$curarea = isset($_GET['area']) ? $_GET['area'] : 'pmx_center';
	$context['pmx']['admmode'] = $_REQUEST['action'];

	if(allowPmx('pmx_admin', true))
		echo '
		<div class="cat_bar"><h3 class="catbg">'. $txt['pmx_admin_center'] .'</h3></div>
		<p class="information">'. sprintf($txt['pmx_admin_main_welcome'] ,'<span class="generic_icons help" title="'. $txt['help'] .'"></span>') .'</p>';

	echo '
			<div class="roundframe" style="padding:10px 5px;margin-top:2px;">
				<table class="pmx_table middletext" style="padding:5px;">
					<tr>
						<td style="width:50%; padding:5px;'. (!allowPmx('pmx_admin') ? 'display:none;"' : '') .'">
							<a href="'. $scripturl .'?action='. $context['pmx']['admmode'] .';area=pmx_settings;'. $context['session_var'] .'=' .$context['session_id'] .'"><img style="float:left;margin:5px 10px 20px;" src="'. $context['pmx_imageurl'] .'admc_settings.png" alt="*" title="" /></a>
							<a href="'. $scripturl .'?action='. $context['pmx']['admmode'] .';area=pmx_settings;'. $context['session_var'] .'=' .$context['session_id'] .'">'. $txt['pmx_center_mansettings'] .'</a><br /><span class="smalltext">'. $txt['pmx_center_mansettings_desc'] .'</span>
						</td>
						<td style="width:50%; padding:5px;'. (!allowPmx('pmx_admin, pmx_blocks') ? 'display:none;' : '') .'">
							<a href="'. $scripturl .'?action='. $context['pmx']['admmode'] .';area=pmx_blocks;'. $context['session_var'] .'=' .$context['session_id'] .'"><img style="float:left;margin:5px 10px 20px;" src="'. $context['pmx_imageurl'] .'admc_blocks.png" alt="*" title="" /></a>
							<a href="'. $scripturl .'?action='. $context['pmx']['admmode'] .';area=pmx_blocks;'. $context['session_var'] .'=' .$context['session_id'] .'">'. $txt['pmx_center_manblocks'] .'</a><br /><span class="smalltext">'. $txt['pmx_center_manblocks_desc'] .'</span>
						</td>
					</tr>
					<tr>
						<td style="width:50%; padding:5px;'. (!allowPmx('pmx_admin, pmx_articles, pmx_create') ? 'display:none;"' : '') .'">
							<a href="'. $scripturl .'?action='. $context['pmx']['admmode'] .';area=pmx_articles;'. $context['session_var'] .'=' .$context['session_id'] .'"><img style="float:left;margin:5px 10px 20px;" src="'. $context['pmx_imageurl'] .'admc_article.png" alt="*" title="" /></a>
							<a href="'. $scripturl .'?action='. $context['pmx']['admmode'] .';area=pmx_articles;'. $context['session_var'] .'=' .$context['session_id'] .'">'. $txt['pmx_center_manarticles'] .'</a><br /><span class="smalltext">'. $txt['pmx_center_manarticles_desc'] .'</span>
						</td>
						<td style="width:50%; padding:5px;'. (!allowPmx('pmx_admin') ? 'display:none;' : '') .'">
							<a href="'. $scripturl .'?action='. $context['pmx']['admmode'] .';area=pmx_categories;'. $context['session_var'] .'=' .$context['session_id'] .'"><img style="float:left;margin:5px 10px 20px;" src="'. $context['pmx_imageurl'] .'admc_category.png" alt="*" title="" /></a>
							<a href="'. $scripturl .'?action='. $context['pmx']['admmode'] .';area=pmx_categories;'. $context['session_var'] .'=' .$context['session_id'] .'">'. $txt['pmx_center_mancategories'] .'</a><br /><span class="smalltext">'. $txt['pmx_center_mancategories_desc'] .'</span>
						</td>
					</tr>
				</table>
			</div>';
}
?>