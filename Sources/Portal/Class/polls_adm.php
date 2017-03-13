<?php
/**
 * PortaMx Forum
 * @package PortaMx
 * @author PortaMx
 * @copyright 2017 PortaMx
 *
 * file polls_adm.php
 * Admin Systemblock polls
 *
 * @version 1.0 RC1
 */

if(!defined('PMX'))
	die('This file can\'t be run without PortaMx-Forum');

/**
* @class pmxc_polls_adm
* Admin Systemblock polls_adm
* @see polls_adm.php
*/
class pmxc_polls_adm extends PortaMxC_SystemAdminBlock
{
	var $smf_polls;

	/**
	* AdmBlock_init().
	* get all available Polls
	* Setup caching and exist polls.
	*/
	function pmxc_AdmBlock_init()
	{
		global $modSettings, $pmxcFunc;

		// get all Polls
		$this->smf_polls = array();

		$request = $pmxcFunc['db_query']('', '
				SELECT t.id_poll, p.question, p.voting_locked, p.expire_time
				FROM {db_prefix}topics as t
				LEFT JOIN {db_prefix}polls as p on (t.id_poll = p.id_poll)
				WHERE t.id_poll > 0'. (!empty($modSettings['recycle_enable']) && $modSettings['recycle_board'] > 0 ? ' AND t.id_board != {int:recyleboard}' : '') .'
				ORDER BY p.id_poll DESC',
			array(
				'recyleboard' => $modSettings['recycle_board'],
			)
		);
		while($row = $pmxcFunc['db_fetch_assoc']($request))
			$this->smf_polls[$row['id_poll']] = array(
				'question' => $row['question'],
				'locked' => !empty($row['voting_locked']),
				'expired' => !empty($row['expire_time']) && $row['expire_time'] < time(),
			);
		$pmxcFunc['db_free_result']($request);

		$this->can_cached = 1;		// enable cache
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
		$used_classdef['questiontext'] = array(
			' '. $txt['pmx_default_none'] => '',
			' smalltext' => 'smalltext',
			' middletext' => 'middletext',
			'+normaltext' => 'normaltext',
			' largetext' => 'largetext',
		);

		// define the settings options
		echo '
					<td width="50%" valign="top" style="padding:4px;">
						<div style="min-height:227px;">
							<input type="hidden" name="config[settings]" value="" />
							<input type="hidden" name="content" value="" />';

		// show the settings screen
		echo '
							<div class="cat_bar catbg_grid grid_padd">
								<h4 class="catbg catbg_grid"><span class="cat_left_title">'. sprintf($txt['pmx_blocks_settings_title'], $this->register_blocks[$this->cfg['blocktype']]['description']) .'</span></h4>
							</div>

							<div class="adm_input">
								<span>'. $txt['pmx_select_polls'] .'</span>
								<img class="info_toggle" style="padding:0 5px;" onclick=\'Show_help("pmxPOLLH01")\' src="'. $context['pmx_imageurl'] .'information.png" alt="*" title="'. $txt['pmx_information_icon'] .'" />';

		if(!empty($this->smf_polls))
		{
			echo '
								<select class="adm_w90" name="config[settings][polls][]" size="3" multiple="multiple">';

			foreach($this->smf_polls as $pid => $data)
				echo '
									<option value="'. $pid .'"'. (!empty($this->cfg['config']['settings']['polls']) && in_array($pid, $this->cfg['config']['settings']['polls']) ? ' selected="selected"' : '') .'>'. $data['question'] .($data['locked'] ? $txt['pmx_poll_select_locked'] : '').($data['expired'] ? $txt['pmx_poll_select_expired'] : '') .'</option>';

			echo '
								</select>';
		}
		else
			echo '
								<div class="tborder adm_w90" style="margin-top:25px; height:1.3em;">'. $txt['pmx_no_polls'] .'</div>';

		echo '
								<div id="pmxPOLLH01" class="info_frame" style="margin-top:5px;">'. $txt['pmx_polls_hint'] .'</div>
							</div>
							<input type="hidden" name="config[show_sitemap]" value="0" />
						</div>';

		// return the default classnames
		return $used_classdef;
	}
}
?>