<?php

/**
 * This file has the important job of taking care of help messages and the help center.
 *
 * PortaMx Forum
 * @package PortaMx
 * @author PortaMx & Simple Machines
 * @copyright 2017 PortaMx,  Simple Machines and individual contributors
 * @license http://www.simplemachines.org/about/smf/license.php BSD
 *
 * @version 1.0 RC3
 */

if (!defined('PMX'))
	die('No direct access...');

/**
 * Redirect to the user help ;).
 * It loads information needed for the help section.
 * It is accessed by ?action=help.
 * @uses Help template and Manual language file.
 */
function ShowHelp()
{
	loadTemplate('Help');

	$subActions = array(
		'index' => 'HelpIndex',
		'rules' => 'HelpRules',
	);

	// CRUD $subActions as needed.
	call_integration_hook('integrate_manage_help', array(&$subActions));

	$sa = isset($_GET['sa'], $subActions[$_GET['sa']]) ? $_GET['sa'] : 'index';
	call_helper($subActions[$sa]);
}

/**
 * The main page for the Help section
 */
function HelpIndex()
{
	global $scripturl, $context, $txt;

	// Lastly, some minor template stuff.
	$context['page_title'] = $txt['manual_user_help'];
	$context['sub_template'] = 'manual';
}

/**
 * Displays forum rules
 */
function HelpRules()
{
	global $context, $txt, $boarddir, $user_info, $scripturl;

	// Build the link tree.
	$context['linktree'][] = array(
		'url' => $scripturl . '?action=help',
		'name' => $txt['help'],
	);
	$context['linktree'][] = array(
		'url' => $scripturl . '?action=help;sa=rules',
		'name' => $txt['terms_and_rules'],
	);

	// Have we got a localized one?
	$context['lbimage_data'] = null;
	if (file_exists($boarddir . '/agreement.' . $user_info['language'] . '.txt'))
		$context['agreement'] = parse_bbc(file_get_contents($boarddir . '/agreement.' . $user_info['language'] . '.txt'), true, 'agreement_' . $user_info['language']);
	elseif (file_exists($boarddir . '/agreement.txt'))
		$context['agreement'] = parse_bbc(file_get_contents($boarddir . '/agreement.txt'), true, 'agreement');
	else
		$context['agreement'] = '';

	// Nothing to show, so let's get out of here
	if (empty($context['agreement']))
	{
		// No file found or a blank file! Just leave...
		redirectexit();
	}

	$context['canonical_url'] = $scripturl . '?action=help;sa=rules';

	$context['page_title'] = $txt['terms_and_rules'];
	$context['sub_template'] = 'terms';
}

/**
 * Show some of the more detailed help to give the admin an idea...
 * It shows a popup for administrative or user help.
 * It uses the help parameter to decide what string to display and where to get
 * the string from. ($helptxt or $txt?)
 * It is accessed via ?action=helpadmin;help=?.
 * @uses ManagePermissions language file, if the help starts with permissionhelp.
 * @uses Help template, popup sub template, no layers.
 */
function ShowAdminHelp()
{
	global $txt, $helptxt, $context, $scripturl;

	if (!isset($_GET['help']) || !is_string($_GET['help']))
		fatal_lang_error('no_access', false);

	if (!isset($helptxt))
		$helptxt = array();

	if (isset($_GET['help']) && substr($_GET['help'], 0, 4) == 'pmx_')
			loadLanguage('Portal/PortalHelp');
	else
	{
		// Load the admin help language file and template.
		loadLanguage('Help+ToolsHelp');

		// Permission specific help?
		if (isset($_GET['help']) && substr($_GET['help'], 0, 14) == 'permissionhelp')
			loadLanguage('ManagePermissions');
	}

	loadTemplate('Help');

	// Allow mods to load their own language file here
	call_integration_hook('integrate_helpadmin');

	// Set the page title to something relevant.
	$context['page_title'] = $context['forum_name'] . ' - ' . $txt['help'];

	// Don't show any template layers, just the popup sub template.
	$context['template_layers'] = array();
	$context['sub_template'] = 'popup';

	// What help string should be used?
	if (isset($helptxt[$_GET['help']]))
		$context['help_text'] = $helptxt[$_GET['help']];
	elseif (isset($txt[$_GET['help']]))
		$context['help_text'] = $txt[$_GET['help']];
	else
		$context['help_text'] = $_GET['help'];

	// Does this text contain a link that we should fill in?
	if (preg_match('~%([0-9]+\$)?s\?~', $context['help_text'], $match))
		$context['help_text'] = sprintf($context['help_text'], $scripturl, $context['session_id'], $context['session_var']);
}

?>