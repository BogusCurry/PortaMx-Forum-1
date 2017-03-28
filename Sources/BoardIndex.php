<?php

/**
 * The single function this file contains is used to display the main
 * board index.
 *
 * PortaMx Forum
 * @package PortaMx
 * @author PortaMx & Simple Machines
 * @copyright 2017 PortaMx,  Simple Machines and individual contributors
 * @license http://www.simplemachines.org/about/smf/license.php BSD
 *
 * @version 1.0 RC2
 */

if (!defined('PMX'))
	die('No direct access...');

/**
 * This function shows the board index.
 * It uses the BoardIndex template, and main sub template.
 * It updates the most online statistics.
 * It is accessed by ?action=boardindex.
 */
function BoardIndex()
{
	global $txt, $user_info, $sourcedir, $modSettings, $context, $settings, $options, $scripturl;

	loadTemplate('BoardIndex');
	$context['template_layers'][] = 'boardindex_outer';
	
	// Set a canonical URL for this page.
	$context['canonical_url'] = $scripturl;

	// Do not let search engines index anything if there is a random thing in $_GET.
	//-> PortaMx
	if (!empty($_GET))
		$context['robot_no_index'] = (isset($_GET['action']) && $_GET['action'] == 'community') ? false : true;
	//<- PortaMx

	// Retrieve the categories and boards.
	require_once($sourcedir . '/Subs-BoardIndex.php');
	$boardIndexOptions = array(
		'include_categories' => true,
		'base_level' => 0,
		'parent_id' => 0,
		'set_latest_post' => true,
		'countChildPosts' => !empty($modSettings['countChildPosts']),
	);
	$context['categories'] = getBoardIndex($boardIndexOptions);

	// Now set up for the info center.
	$context['info_center'] = array();

	// Retrieve the latest posts if the theme settings require it.
	if (!empty($settings['number_recent_posts']))
	{
		if ($settings['number_recent_posts'] > 1)
		{
			$latestPostOptions = array(
				'number_posts' => $settings['number_recent_posts'],
			);
			$context['latest_posts'] = cache_quick_get('boardindex-latest_posts:' . md5($user_info['query_wanna_see_board'] . $user_info['language']), 'Subs-Recent.php', 'cache_getLastPosts', array($latestPostOptions));
		}

		if (!empty($context['latest_posts']) || !empty($context['latest_post']))
			$context['info_center'][] = array(
				'tpl' => 'recent',
				'txt' => 'recent_posts',
			);
	}

	// Load the calendar?
	if (!empty($modSettings['cal_enabled']) && allowedTo('calendar_view'))
	{
		// Retrieve the calendar data (events, birthdays, holidays).
		$eventOptions = array(
			'include_holidays' => $modSettings['cal_showholidays'] > 1,
			'include_birthdays' => $modSettings['cal_showbdays'] > 1,
			'include_events' => $modSettings['cal_showevents'] > 1,
			'num_days_shown' => empty($modSettings['cal_days_for_index']) || $modSettings['cal_days_for_index'] < 1 ? 1 : $modSettings['cal_days_for_index'],
		);
		$context += cache_quick_get('calendar_index_offset_' . ($user_info['time_offset'] + $modSettings['time_offset']), 'Subs-Calendar.php', 'cache_getRecentEvents', array($eventOptions));

		// Whether one or multiple days are shown on the board index.
		$context['calendar_only_today'] = $modSettings['cal_days_for_index'] == 1;

		// This is used to show the "how-do-I-edit" help.
		$context['calendar_can_edit'] = allowedTo('calendar_edit_any');

		if ($context['show_calendar'])
			$context['info_center'][] = array(
				'tpl' => 'calendar',
				'txt' => $context['calendar_only_today'] ? 'calendar_today' : 'calendar_upcoming',
			);
	}

	// And stats.
	$context['show_stats'] = allowedTo('view_stats') && !empty($modSettings['trackStats']);
	if ($settings['show_stats_index'])
		$context['info_center'][] = array(
			'tpl' => 'stats',
			'txt' => 'forum_stats',
			);

	// Now the online stuff
	require_once($sourcedir . '/Subs-MembersOnline.php');
	$membersOnlineOptions = array(
		'show_hidden' => allowedTo('moderate_forum'),
		'sort' => 'log_time',
		'reverse_sort' => true,
	);
	$context += getMembersOnlineStats($membersOnlineOptions);
	$context['show_buddies'] = !empty($user_info['buddies']);
	$context['show_who'] = allowedTo('who_view') && !empty($modSettings['who_enabled']);
	$context['info_center'][] = array(
		'tpl' => 'online',
		'txt' => 'online_users',
	);

	// Track most online statistics? (Subs-MembersOnline.php)
	if (!empty($modSettings['trackStats']))
		trackStatsUsersOnline($context['num_guests'] + $context['num_spiders'] + $context['num_users_online']);

	// Are we showing all membergroups on the board index?
	if (!empty($settings['show_group_key']))
		$context['membergroups'] = cache_quick_get('membergroup_list', 'Subs-Membergroups.php', 'cache_getMembergroupList', array());

	// And back to normality.
	$context['page_title'] = sprintf($txt['forum_index'], $context['forum_name']);

	// Mark read button
	$context['mark_read_button'] = array(
		'markread' => array('text' => 'mark_as_read', 'image' => 'markread.png', 'lang' => true, 'custom' => 'data-confirm="' . $txt['are_sure_mark_read'] . '"', 'class' => 'you_sure', 'url' => $scripturl . '?action=markasread;sa=all;' . $context['session_var'] . '=' . $context['session_id']),
	);

	// Allow mods to add additional buttons here
	call_integration_hook('integrate_mark_read_button');

	if (!empty($settings['show_newsfader']))
	{
		loadJavascriptFile('slippry.min.js', array(), 'pmx_jquery_slippry');
		loadCSSFile('slider.min.css', array(), 'pmx_jquery_slider');

		addInlineJavascript('
		jQuery("#pmx_slider").slippry({
			pause: '. $settings['newsfader_time'] .',
			adaptiveHeight: 0,
			captions: 0,
			controls: 0,
		});', false);
	}

	// Info center collapse object.
	if (!empty($context['info_center']))
	{
		if($user_info['is_guest'])
		{
			$curRQ = $_REQUEST;
			$_REQUEST = array('jscook' => '', 'mode' => 'get', 'name' => 'upshrinkIC');
			$IC_Cook = jsCookieHandling(true);
			$_REQUEST = $curRQ;
		}

		addInlineJavascript('
	var oInfoCenterToggle = new pmxc_Toggle({
		bToggleEnabled: true,
		bCurrentlyCollapsed: '. ($user_info['is_guest'] ? (empty($IC_Cook) ? 'false' : 'true') : (empty($options['collapse_header_ic']) ? 'false' : 'true')) .',
		aSwappableContainers: [
			\'upshrinkHeaderIC\'],
	aSwapImages: [{
		sId: \'upshrink_ic\',
		altExpanded: '. JavaScriptEscape($txt['hide_infocenter']) .',
		altCollapsed: '. JavaScriptEscape($txt['show_infocenter']) .'
	}],
	aSwapLinks: [{
		sId: \'upshrink_link\',
		msgExpanded: '. JavaScriptEscape(sprintf($txt['info_center_title'], $context['forum_name_html_safe'])) .',
		msgCollapsed: '. JavaScriptEscape(sprintf($txt['info_center_title'], $context['forum_name_html_safe'])) .'
	}],
	oThemeOptions: {
		bUseThemeSettings: '. ($user_info['is_guest'] ? 'false' : 'true') .',
		sOptionName: \'collapse_header_ic\',
		sSessionId: pmx_session_id,
		sSessionVar: pmx_session_var},
	oCookieOptions: {
		bUseCookie: '. ($user_info['is_guest'] ? 'true' : 'false') .',
		sCookieName: \'upshrinkIC\'}
	});', true);
	}
}
?>