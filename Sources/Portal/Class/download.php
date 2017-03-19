<?php
/**
 * PortaMx Forum
 * @package PortaMx
 * @author PortaMx
 * @copyright 2017 PortaMx
 *
 * file download.php
 * Systemblock Downlaod
 *
 * @version 1.0 RC1
 */

if(!defined('PMX'))
	die('This file can\'t be run without PortaMx-Forum');

/**
* @class pmxc_download
* Systemblock Download
* @see download.php
*/
class pmxc_download extends PortaMxC_SystemBlock
{
	var $download_content;

	/**
	* InitContent.
	* Checks the autocache and create the content if necessary.
	*/
	function pmxc_InitContent()
	{
		global $pmxcFunc, $context, $user_info, $scripturl, $txt;

		// custom sort function
		function CustomSort($a, $b) 
		{
			if ($a['order'] == $b['order'])
				return 0;
			return ($a < $b) ? -1 : 1;
		}

		if($this->visible)
		{
			$this->download_content = $this->cfg['content'];

			if(isset($this->cfg['config']['settings']['download_board']) && !empty($this->cfg['config']['settings']['download_board']))
			{
				// get downloads from the board
				$request = $pmxcFunc['db_query']('', '
						SELECT a.id_attach, a.size, a.downloads, t.id_topic, t.locked, m.subject, m.body
						FROM {db_prefix}attachments a
						LEFT JOIN {db_prefix}messages m ON (a.id_msg = m.id_msg)
						LEFT JOIN {db_prefix}topics t ON (m.id_topic = t.id_topic)
						WHERE m.id_board = {int:board} AND a.mime_type NOT LIKE {string:likestr} AND t.locked = 0',
					array(
						'board' => $this->cfg['config']['settings']['download_board'],
						'likestr' => 'IMAGE%'
					)
				);

				$data = array();
				$entrys = $pmxcFunc['db_num_rows']($request);
				if($entrys > 0)
				{
					while($row = $pmxcFunc['db_fetch_assoc']($request))
					{
						preg_match('/^[0-9]+/', substr($row['subject'], 0, 4), $order);
						$data[] = array(
							'order' => (isset($order[0]) ? intval($order[0]) : 0),
							'subject' => (isset($order[0]) ? trim(substr($row['subject'], strlen($order[0]))) : trim($row['subject'])),
							 'body' => $row['body'],
							 'downloads' => $row['downloads'],
							 'id_attach' => $row['id_attach'],
							 'id_topic' => $row['id_topic'],
							 'locked' => $row['locked'],
							 'size' => $row['size']
						);
					}
					$pmxcFunc['db_free_result']($request);

					// sort by custom subject field [1234 SubjectText]
					uasort($data, 'CustomSort');
				}

				$dlacs = implode('=1,', $this->cfg['config']['settings']['download_acs']);
				if(count($data > 0))
				{
					foreach($data as $row)
					{
						$this->download_content .= '
						<div style="text-align:left;">';

						if(allowPmxGroup($dlacs))
							$this->download_content .= '
							<a href="'. $scripturl .'?action=dlattach;id='. $row['id_attach'] .';fld='. $this->cfg['id'] .'">
								<img style="vertical-align:middle;" src="'. $context['pmx_imageurl'] .'download.png" alt="*" title="'. $row['subject'] .'" /></a>';

						if($user_info['is_admin'])
							$this->download_content .= '
							<a href="'. $scripturl .'?topic='. $row['id_topic'] .'">
								<strong>'. $row['subject'] .'</strong>
							</a>';
						else
							$this->download_content .= '
							<strong>'. $row['subject']  .'</strong>';

						$this->download_content .= '
							<div class="dlcomment">'. parse_bbc(trim($row['body'])) .'</div>
							<b>['. round($row['size'] / 1000, 3) .'</b> '. $txt['pmx_kb_downloads'] .'<b>'. $row['downloads'] .'</b>]
						</div>' . ($entrys > 1 ? '<hr class="pmx_hr" />' : '');
						$entrys--;
					}
				}
				else
					$this->download_content .= '<br />'. $txt['pmx_download_empty'];
			}
			else
				$this->download_content .= '<br />'. $txt['pmx_download_empty'];
		}
		// return the visibility flag (true/false)
		return $this->visible;
	}

	/**
	* ShowContent
	* Output the content.
	*/
	function pmxc_ShowContent()
	{
		echo '
		'. $this->download_content;
	}
}
?>