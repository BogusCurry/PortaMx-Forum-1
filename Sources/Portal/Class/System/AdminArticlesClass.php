<?php
/**
 * PortaMx Forum
 * @package PortaMx
 * @author PortaMx
 * @copyright 2017 PortaMx
 *
 * file PortaMx_AdminArticlesClass.php
 * Global Articles Admin class
 *
 * @version 1.0 RC1
 */

if(!defined('PMX'))
	die('This file can\'t be run without PortaMx-Forum');

/**
* @class PortaMxC_AdminArticles
* The Global Class for Articles Administration.
* @see PortaMx_AdminArticlesClass.php
*/
class PortaMxC_AdminArticles
{
	var $cfg;						///< common config

	/**
	* The Contructor.
	* Saved the config, load the article css file if exist.
	* Have the article a css file, the class definition is extracted from ccs header
	*/
	function __construct($config)
	{
		// get the article config array
		if(isset($config['config']))
			$config['config'] = pmx_json_decode($config['config'], true);
		$this->cfg = $config;
	}
}

/**
* @class PortaMxC_SystemAdminArticle
* This is the Global Admin class to create or edit a Article.
* This class prepare the settings screen and the and content.
* @see PortaMx_AdminArticlesClass.php
*/
class PortaMxC_SystemAdminArticle extends PortaMxC_AdminArticles
{
	var $smf_groups;				///< all usergroups
	var $title_icons;				///< array with title icons
	var $custom_css;				///< custom css definitions
	var $usedClass;					///< used class types
	var $categories;				///< all exist categories

	/**
	* This Methode is called on loadtime.
	* After all variables initiated, it calls the block dependent init methode.
	* Finaly the css is loaded if exist
	*/
	function pmxc_AdmArticle_loadinit()
	{
		global $context;
	
		$this->smf_groups = PortaMx_getUserGroups();										// get all usergroups
		$this->title_icons = PortaMx_getAllTitleIcons();								// get all title icons
		$this->custom_css = PortaMx_getCustomCssDefs();									// custom css definitions
		$this->usedClass = PortaMx_getdefaultClass(false, true);				// default class types
		$this->categories = PortaMx_getCategories();										// exist categories

		addInlineJavascript(str_replace("\n", "\n\t", PortaMx_compressJS('
		function pack(chrstr) {
			var hexstr = "";
			for(var i = 0; i < chrstr.length; i++)
			{
				var c = chrstr.charCodeAt(i);
				var h = "00" + c.toString(16);
				hexstr += h.substr(h.length-2);
			}
			return hexstr;
		}
		function php_syntax(elmid)
		{
			document.getElementById("check_" + elmid).innerHTML = "<img onclick=\"Hide_SyntaxCheck(this.parentNode)\" style=\"padding-left:10px;cursor:pointer;\" alt=\"close\" src=\"'. $context['pmx_imageurl'] .'cross.png\" class=\"pmxright\" />";
			document.getElementById("check_" + elmid).className = "info_frame";
			var result = pmxCookie("syntax", "check", pack(document.getElementById(elmid).value));
			result = result.replace(/@elm@/, elmid);
			result = result.replace(/<br \/>/gi, "");
			temp = result.replace(/<b>/gi, "");
			temp = temp.replace(/<\/b>/gi, "");
			var patt = /(on\s+line\D+)(\d+)/;
			res = patt.exec(temp);
			LineNo = "";
			if(res)
				LineNo = res[2];
			if(result.indexOf("in <b>") != -1)
			{
				result = result.substring(result.indexOf("<b>Parse error</b>:  "), result.indexOf("in <b>"));
				result = result.replace(/<b>/gi, "");
				result = result + " on line " + LineNo;
			}
			document.getElementById("check_" + elmid).innerHTML = document.getElementById("check_" + elmid).innerHTML + result;
			Show_help("check_" + elmid);
			php_showerrline(elmid, LineNo);
		}
		function php_showerrline(elmid, errLine)
		{
			if(errLine != "" && errLine != "0" && !isNaN(errLine))
			{
				errLine = parseInt(errLine) -1;
				var lines = document.getElementById(elmid).value.split("\n");
				var count = 0;
				for(var i = 0; i < errLine -1; i++)
					count += lines[i].length +1;

				if(document.getElementById(elmid).setSelectionRange)
				{
					document.getElementById(elmid).focus();
					document.getElementById(elmid).setSelectionRange(count, count+lines[i].length);
				}
				else if(document.getElementById(elmid).createTextRange)
				{
					range=document.getElementById(elmid).createTextRange();
					range.collapse(true);
					range.moveStart("character", count);
					range.moveEnd("character", count+lines[i].length);
					range.select();
				}
			}
		}')));
	}

	/**
	* Output the Article config screen
	*/
	function pmxc_ShowAdmArticleConfig()
	{
		global $context, $settings, $modSettings, $boarddir, $boardurl, $options, $txt;


		echo '
				<tr>
					<td>
						<div class="windowbg">
						<table class="pmx_table">
							<tr>
								<td style="width:50%;padding:4px;">
									<input type="hidden" name="id" value="'. $this->cfg['id'] .'" />
									<input type="hidden" name="owner" value="'. $this->cfg['owner'] .'" />
									<input type="hidden" name="contenttype" value="'. $this->cfg['ctype'] .'" />
									<input type="hidden" name="config[settings]" value="" />
									<input type="hidden" name="active" value="'. $this->cfg['active'] .'" />
									<input type="hidden" name="approved" value="'. $this->cfg['approved'] .'" />
									<input type="hidden" name="approvedby" value="'. $this->cfg['approvedby'] .'" />
									<input type="hidden" name="created" value="'. $this->cfg['created'] .'" />
									<input type="hidden" name="updated" value="'. $this->cfg['updated'] .'" />
									<input type="hidden" name="updatedby" value="'. $this->cfg['updatedby'] .'" />
									<input type="hidden" name="check_num_vars[]" value="[config][maxheight], \'\'" />
									<div style="height:61px;">
										<div style="float:left;width:90px; padding-top:1px;">'. $txt['pmx_article_title'] .'</div>';

		// all titles depend on language
		$curlang = '';
		foreach($context['pmx']['languages'] as $lang => $sel)
		{
			$curlang = !empty($sel) ? $lang : $curlang;
			echo '
										<span id="'. $lang .'" style="white-space:nowrap;'. (!empty($sel) ? '' : ' display:none;') .'">
											<input style="width:65%;" type="text" name="config[title]['. $lang .']" value="'. (isset($this->cfg['config']['title'][$lang]) ? htmlspecialchars($this->cfg['config']['title'][$lang], ENT_QUOTES) : '') .'" />
										</span>';
		}

		echo '
										<input id="curlang" type="hidden" value="'. $curlang .'" />
										<div style="clear:both; height:10px;">
											<img style="float:left;" src="'. $context['pmx_imageurl'] .'arrow_down.gif" alt="*" title="" />
										</div>
										<div style="float:left; width:90px;">'. $txt['pmx_edit_title_lang'] .'
											<img class="info_toggle" onclick=\'Show_help("pmxBH01")\' src="'. $context['pmx_imageurl'] .'information.png" alt="*" title="'. $txt['pmx_information_icon'] .'" />
										</div>
										<select style="float:left; width:165px;" size="1" onchange="setTitleLang(this)">';

		foreach($context['pmx']['languages'] as $lang => $sel)
			echo '
											<option value="'. $lang .'"' .(!empty($sel) ? ' selected="selected"' : '') .'>'. $lang .'</option>';

		echo '
										</select>
										<div style="margin-left:265px;margin-top:2px;">
											<span style="vertical-align:7px;">'. $txt['pmx_edit_title_align'] .'</span><br />';

		// title align
		if(!isset($this->cfg['config']['title_align']))
			$this->cfg['config']['title_align'] = 'left';

		echo '
											<input type="hidden" id="titlealign" name="config[title_align]" value="'. $this->cfg['config']['title_align'] .'" />';

		foreach($txt['pmx_edit_title_align_types'] as $key => $val)
			echo '
											<img id="img'. $key .'" src="'. $context['pmx_imageurl'] .'text_align_'. $key .'.gif" alt="*" title="'. $txt['pmx_edit_title_helpalign']. $val .'" style="cursor:pointer;vertical-align:1px;'. ($this->cfg['config']['title_align'] == $key ? 'background-color:#e02000;' : '') .'" onclick="setAlign(\'\', \''. $key .'\')" />';

		echo '
										</div>
									</div>
									<div id="pmxBH01" style="margin-top:5px;width:243px" class="info_frame">'.
										$txt['pmx_edit_titlehelp'] .'
									</div>';

			// Title icons
		$this->cfg['config']['title_icon'] = (empty($this->cfg['config']['title_icon']) || $this->cfg['config']['title_icon'] == 'none.gif') ? 'none.png' : $this->cfg['config']['title_icon'];
		echo '
									<div style="float:left;height:40px;">
										<div style="float:left;width:90px; padding-top:8px;">'. $txt['pmx_edit_titleicon'] .'</div>
										<div class="ttliconDiv" onclick="setNewIcon(document.getElementById(\'pWind.icon_sel\'), event)">
											<input id="post_image" type="hidden" name="config[title_icon]" value="'. $this->cfg['config']['title_icon'] .'" />
											<input id="iconDD" value="'. ucfirst(str_replace('.png', '', $this->cfg['config']['title_icon'])) .'" readonly />
											<img id="pWind.icon" class="pwindiconBlk" src="'. $context['pmx_Iconsurl'] . $this->cfg['config']['title_icon'] .'" alt="*" />
											<img class="ddImageBlk" src="'. $context['pmx_imageurl'] .'state_expand.png" alt="*" title="" />
										</div>
										<ul class="ttlicondd Artedit" id="pWind.icon_sel" onclick="updIcon(this)">';

		foreach($this->title_icons as $file => $name)
			echo '
											<li id="'. $file .'" class="ttlicon'. ($this->cfg['config']['title_icon'] == $file ? ' active' : '') .'">
												<img src="'. $context['pmx_Iconsurl'] . $file .'" alt="*" /><span>'. $name .'</span>
											</li>';

		echo '
										</ul>
										<script>$("li").hover(function(){$(this).toggleClass("active")});</script>
									</div>';

		// show article types
		echo '
								</td>
								<td width="50%" style="padding:4px;">
									<div style="float:left;width:130px;">'. $txt['pmx_article_type'] .'</div>';

		$RegBlocks = $context['pmx']['RegBlocks'];
		foreach($RegBlocks as $key =>$val)
			if(!in_array($key, array('html', 'script', 'bbc_script', 'php')))
				unset($RegBlocks[$key]);

		function cmpBDesc($a, $b){return strcasecmp(str_replace(' ', '', $a["description"]), str_replace(' ', '', $b["description"]));}
		uasort($RegBlocks, 'cmpBDesc');

		if(allowPmx('pmx_admin, pmx_create'))
		{
			echo '
									<select style="width:60%;" size="1" name="ctype" onchange="FormFunc(\'edit_change\', \'1\')">';

			foreach($RegBlocks as $type => $articleType)
				echo '
										<option value="'. $type .'"'. ($this->cfg['ctype'] == $type ? ' selected="selected"' : '') .'>'. $articleType['description'] .'</option>';

			echo '
									</select>';
		}
		else
			echo '
									<input type="hidden" name="ctype" value="'. $this->cfg['ctype'] .'" />
									<input style="width:60%;" value="'. $RegBlocks[$this->cfg['ctype']]['description'] .'" disabled="disabled" />';

		// all exist categories
		$selcats = array_merge(array(PortaMx_getDefaultCategory($txt['pmx_categories_none'])), $this->categories);
		$ordercats = array_merge(array(0), $context['pmx']['catorder']);
		$isWriter = allowPmx('pmx_create, pmx_articles', true);
		$isAdm = allowPmx('pmx_admin');
		echo '
										<div style="float:left;width:130px;margin-top:9px;">'. $txt['pmx_article_cats'] .'</div>
										<select style="width:60%;margin-top:9px;" size="1" name="catid">';

		foreach($ordercats as $catorder)
		{
			$cat = PortaMx_getCatByOrder($selcats, $catorder);
			$cfg = pmx_json_decode($cat['config'], true);
			if(!empty($isAdm) || (!empty($isWriter) && empty($cfg['global'])))
				echo '
											<option value="'. $cat['id'] .'"'. ($cat['id'] == $this->cfg['catid'] ? ' selected="selected"' : '') .'">'. str_repeat('&bull;', $cat['level']).' '. $cat['name'] .'</option>';
		}

		echo '
										</select>
									</div>';

		// articlename
		echo '
									<div style="float:left;width:130px;margin-top:9px">'. $txt['pmx_article_name'] .'
										<img class="info_toggle" onclick=\'Show_help("pmxBH11")\' src="'. $context['pmx_imageurl'] .'information.png" alt="*" title="'. $txt['pmx_information_icon'] .'" />
									</div>
									<input id="check.name" style="width:60%;margin-top:9px" onkeyup="check_requestname(this)" onkeypress="check_requestname(this)" type="text" name="name" value="'. $this->cfg['name'] .'" />
									<span id="check.name.error" style="display:none;">'. sprintf($txt['namefielderror'], $txt['pmx_article_name']) .'</span>
									<div id="pmxBH11" class="info_frame" style="margin-top:5px;">'.
										$txt['pmx_edit_pagenamehelp'] .'
									</div>
								</td>
							</tr>';

		// the editor area dependent on article type
		echo '
							<tr>
								<td colspan="2" style="padding:4px 4px 10px 4px;">';

		// show the editor
		if($this->cfg['ctype'] == 'html')
		{
			$allow = allowPmx('pmx_admin') || allowPmx('pmx_blocks');
			$fnd = explode('/', str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']));
			$smfpath = str_replace('\\', '/', $boarddir);
			foreach($fnd as $key => $val) { $fnd[$key] = $val; $rep[] = ''; }
			$filepath = trim(str_replace($fnd, $rep, $smfpath), '/') .'/CustomImages';
			if(count($fnd) == count(explode('/', $smfpath)))
				$filepath = '/'. $filepath;
			$_SESSION['pmx_ckfm'] = array('ALLOW' => $allow, 'FILEPATH' => $filepath);

			echo '
								<div class="cat_bar catbg_grid">
									<h4 class="catbg catbg_grid"><span class="cat_left_title">'. $txt['pmx_edit_content'] .'</span></h4>
								</div>
								<textarea name="'. $context['pmx']['htmledit']['id'] .'">'. $context['pmx']['htmledit']['content'] .'</textarea>
								<script type="text/javascript">
									CKEDITOR.replace("'. $context['pmx']['htmledit']['id'] .'", {filebrowserBrowseUrl: "ckeditor/fileman/index.php"});
								</script>';
		}

		// show the content area
		elseif($this->cfg['ctype'] == 'bbc_script')
			echo '
								<style type="text/css">
									.sceditor-container iframe{width:99.1% !important;}
									.sceditor-container{max-width:inherit;width:inherit !important; margin-right:-2px;}
									textarea{max-width:99% !important;width:99.2% !important;}
								</style>
								<div class="cat_bar catbg_grid" style="margin-right:1px;">
									<h4 class="catbg catbg_grid"><span class="cat_left_title">'. $txt['pmx_edit_content'] .'</span></h4>
								</div>
								<input type="hidden" id="smileyset" value="PortaMx" />
								<div id="bbcBox_message"></div>
								<div id="smileyBox_message"></div>
								<div style="padding-right:3px;margin-top:-10px;">', template_control_richedit($context['pmx']['editorID'], 'smileyBox_message', 'bbcBox_message'), '</div>';

		elseif($this->cfg['ctype'] == 'php')
		{
			addInlineJavascript(str_replace("\n", "\n\t", PortaMx_compressJS('
			function pack(chrstr) {
				var hexstr = "";
				for(var i = 0; i < chrstr.length; i++)
				{
					var c = chrstr.charCodeAt(i);
					var h = "00" + c.toString(16);
					hexstr += h.substr(h.length-2);
				}
				return hexstr;
			}
			function php_syntax(elmid)
			{
				document.getElementById("check_" + elmid).innerHTML = "<img onclick=\"Hide_SyntaxCheck(this.parentNode)\" style=\"padding-left:10px;cursor:pointer;\" alt=\"close\" src=\"'. $context['pmx_imageurl'] .'cross.png\" class=\"pmxright\" />";
				document.getElementById("check_" + elmid).className = "info_frame";
				var result = pmxCookie("syntax", "check", pack(document.getElementById(elmid).value));
				result = result.replace(/@elm@/, elmid);
				result = result.replace(/<br \/>/gi, "");
				temp = result.replace(/<b>/gi, "");
				temp = temp.replace(/<\/b>/gi, "");
				var patt = /(on\s+line\D+)(\d+)/;
				res = patt.exec(temp);
				LineNo = "";
				if(res)
					LineNo = res[2];
				if(result.indexOf("in <b>") != -1)
				{
					result = result.substring(result.indexOf("<b>Parse error</b>:  "), result.indexOf("in <b>"));
					result = result.replace(/<b>/gi, "");
					result = result + " on line " + LineNo;
				}
				document.getElementById("check_" + elmid).innerHTML = document.getElementById("check_" + elmid).innerHTML + result;
				Show_help("check_" + elmid);
				php_showerrline(elmid, LineNo);
			}
			function php_showerrline(elmid, errLine)
			{
				if(errLine != "" && errLine != "0" && !isNaN(errLine))
				{
					errLine = parseInt(errLine) -1;
					var lines = document.getElementById(elmid).value.split("\n");
					var count = 0;
					for(var i = 0; i < errLine -1; i++)
						count += lines[i].length +1;

					if(document.getElementById(elmid).setSelectionRange)
					{
						document.getElementById(elmid).focus();
						document.getElementById(elmid).setSelectionRange(count, count+lines[i].length);
					}
					else if(document.getElementById(elmid).createTextRange)
					{
						range=document.getElementById(elmid).createTextRange();
						range.collapse(true);
						range.moveStart("character", count);
						range.moveEnd("character", count+lines[i].length);
						range.select();
					}
				}
			}')));

			$options['collapse_phpinit'] = empty($context['pmx']['phpInit']['havecont']);

			echo '
								<div class="cat_bar catbg_grid">
									<h4 class="catbg catbg_grid">
										<span style="float:right;display:block;margin-top:-2px;">
											<img onclick="php_syntax(\''. $context['pmx']['phpShow']['id'] .'\',\''. str_replace('/', '|', str_replace($boardurl, '', $context['pmx_imageurl'])) .'\')" style="padding:3px 5px 3px 10px;cursor:pointer;" title="'. $txt['pmx_check_phpsyntax'] .'" alt="Syntax check" src="'. $context['pmx_imageurl'] .'syntaxcheck.png" class="pmxright" />
										</span>
										<span class="cat_left_title">'. $txt['pmx_edit_content'] .'
										<span id="upshrinkPHPinitCont"'. (empty($options['collapse_phpinit']) ? '' : ' style="display:none;"') .'>'. $txt['pmx_edit_content_show'] .'</span></span>
									</h4>
								</div>
								<div id="check_'. $context['pmx']['phpShow']['id'] .'" class="info_frame" style="line-height:1.4em;margin:1px 0;">
										<img onclick="Hide_SyntaxCheck(this.parentNode)" style="padding-left:10px;cursor:pointer;" alt="close" src="'. $context['pmx_imageurl'] .'cross.png" class="pmxright" />
								</div>

								<textarea name="'. $context['pmx']['phpShow']['id'] .'" id="'. $context['pmx']['phpShow']['id'] .'" style="display:block;resize:vertical;width:'. $context['pmx']['phpShow']['width'] .';height:'. $context['pmx']['phpShow']['height'] .';">'. $context['pmx']['phpShow']['value'] .'</textarea>

								<div class="plainbox info_text" style="margin-top:5px;margin-right:0px;padding:5px 0 7px 0;display:block;">
									<div class="normaltext" style="margin:0 10px;">
									'.(empty($context['pmx']['phpInit']['havecont']) ? '<span style="margin-top:2px;margin-right:-4px;" id="upshrinkPHPshowImg" class="floatright '.
											(empty($options['collapse_visual']) ? 'toggle_up" align="bottom"' : 'toggle_down" align="bottom"') .' title="'.
											(empty($options['collapse_visual']) ? $txt['pmx_collapse'] : $txt['pmx_expand']) . $txt['pmx_php_partblock'] .'">
										</span>' : '') .'
										<span>'. $txt['pmx_php_partblock_note'] .'
											<img class="info_toggle" onclick=\'Toggle_help("pmxPHPH01")\' src="'. $context['pmx_imageurl'] .'information.png" alt="*" title="'. $txt['pmx_information_icon'] .'" style="vertical-align: -3px;" />
										</span>
									</div>
									<div id="pmxPHPH01" style="display:none; margin:4px 10px 0;">'. $txt['pmx_php_partblock_help'] .'</div>
								</div>

								<div id="upshrinkPHPshowCont"' .(empty($options['collapse_phpinit']) ? '' : ' style="margin-top:5px;display:none;"') .'>
									<div class="cat_bar catbg_grid">
										<h4 class="catbg catbg_grid">
											<span style="float:right;display:block;margin-top:-2px;">
												<img onclick="php_syntax(\''. $context['pmx']['phpInit']['id'] .'\')" style="padding:3px 5px 3px 10px;cursor:pointer;" title="'. $txt['pmx_check_phpsyntax'] .'" alt="Syntax check" src="'. $context['pmx_imageurl'] .'syntaxcheck.png" class="pmxright" />
											</span>
											<span class="cat_left_title">'. $txt['pmx_edit_content'] . $txt['pmx_edit_content_init'] .'</span>
										</h4>
									</div>
									<div id="check_'. $context['pmx']['phpInit']['id'] .'" class="info_frame" style="line-height:1.4em;margin:1px 0;">
										<img onclick="Hide_SyntaxCheck(this.parentNode)" style="padding-left:10px;cursor:pointer;" alt="close" src="'. $context['pmx_imageurl'] .'cross.png" class="pmxright" />
									</div>

									<textarea name="'. $context['pmx']['phpInit']['id'] .'" id="'. $context['pmx']['phpInit']['id'] .'" style="display:block;width:'. $context['pmx']['phpInit']['width'] .';height:'. $context['pmx']['phpInit']['height'] .';">'. $context['pmx']['phpInit']['value'] .'</textarea>
								</div>';

			if(empty($context['pmx']['phpInit']['havecont']))
				addInlineJavascript("\t". str_replace("\n", "\n\t", PortaMx_compressJS('
						var upshrinkPHPshow = new pmxc_Toggle({
							bToggleEnabled: true,
							bCurrentlyCollapsed: '. (empty($options['collapse_phpinit']) ? 'false' : 'true') .',
							aSwappableContainers: [
								\'upshrinkPHPshowCont\',
								\'upshrinkPHPinitCont\'
							],
							aSwapImages: [
								{
									sId: \'upshrinkPHPshowImg\',
									altCollapsed: '. JavaScriptEscape($txt['pmx_expand'] . $txt['pmx_php_partblock']) .',
									altExpanded: '. JavaScriptEscape($txt['pmx_collapse'] . $txt['pmx_php_partblock']) .'
								}
							],
							oCookieOptions: {
									bUseCookie: false
								}
						});')), true);
		}
		else
			echo '
									<div class="cat_bar catbg_grid">
										<h4 class="catbg catbg_grid"><span class="cat_left_title">'. $txt['pmx_edit_content'] .'</span></h4>
									</div>
									<textarea name="'. $context['pmx']['script']['id'] .'" id="'. $context['pmx']['script']['id'] .'" style="display:block;width:'. $context['pmx']['script']['width'] .';height:'. $context['pmx']['script']['height'] .';">'. $context['pmx']['script']['value'] .'</textarea>';

		echo '
								</td>
							</tr>
							<tr>
								<td width="50%" style="padding:4px;">
									<div style="min-height:182px;">
										<input type="hidden" name="config[settings]" value="" />';

			// show the settings area
			echo '
										<div class="cat_bar catbg_grid grid_padd">
											<h4 class="catbg catbg_grid"><span class="cat_left_title">'. $txt['pmx_articles_types'][$this->cfg['ctype']] .' '. $txt['pmx_article_settings_title'] .'</span></h4>
										</div>
										<div>';

		if($this->cfg['ctype'] == 'html')
			echo '
											<div class="adm_check">
												<span class="adm_w80">'. $txt['pmx_html_teaser'] .'
													<img class="info_toggle" onclick=\'Show_help("pmxHTMLH01")\' src="'. $context['pmx_imageurl'] .'information.png" alt="*" title="'. $txt['pmx_information_icon'] .'" />
												</span>
												<input type="hidden" name="config[settings][teaser]" value="0" />
												<div><input class="input_check" type="checkbox" name="config[settings][teaser]" value="1"' .(isset($this->cfg['config']['settings']['teaser']) && !empty($this->cfg['config']['settings']['teaser']) ? ' checked="checked"' : ''). ' /></div>
											</div>
											<div id="pmxHTMLH01" class="info_frame" style="margin-top:4px;">'. str_replace('@@', '<img src="'. $context['pmx_imageurl'] .'pgbreak.png" alt="*" style="vertical-align:-5px;"/>', $txt['pmx_html_teasehelp']) .'</div>';

		elseif($this->cfg['ctype'] != 'php')
			echo '
											<div class="adm_check">
												<span class="adm_w80">'. sprintf($txt['pmx_article_teaser'], $txt['pmx_teasemode'][intval(!empty($context['pmx']['settings']['teasermode']))]) .'
													<img class="info_toggle" onclick=\'Show_help("pmxHTMLH02")\' src="'. $context['pmx_imageurl'] .'information.png" alt="*" title="'. $txt['pmx_information_icon'] .'" />
												</span>
												<div><input type="text" size="5" name="config[settings][teaser]" value="'. (isset($this->cfg['config']['settings']['teaser']) ? $this->cfg['config']['settings']['teaser'] : '') .'" /></div>
											</div>
											<div id="pmxHTMLH02" class="info_frame" style="margin-top:4px;">'. $txt['pmx_article_teasehelp'] .'</div>';

		echo '
											<div class="adm_check">
												<span class="adm_w80">'. $txt['pmx_content_print'] .'</span>
												<input type="hidden" name="config[settings][printing]" value="0" />
												<div><input class="input_check" type="checkbox" name="config[settings][printing]" value="1"' .(!empty($this->cfg['config']['settings']['printing']) ? ' checked="checked"' : ''). ' /></div>
											</div>

											<div class="adm_check">
												<span class="adm_w80">'. $txt['pmx_article_footer'] .'
													<img class="info_toggle" onclick=\'Show_help("pmxARTH01")\' src="'. $context['pmx_imageurl'] .'information.png" alt="*" title="'. $txt['pmx_information_icon'] .'" />
												</span>
												<input type="hidden" name="config[settings][showfooter]" value="0" />
												<div><input class="input_check" type="checkbox" name="config[settings][showfooter]" value="1"' .(isset($this->cfg['config']['settings']['showfooter']) && !empty($this->cfg['config']['settings']['showfooter']) ? ' checked="checked"' : ''). ' /></div>
											</div>
											<div id="pmxARTH01" class="info_frame" style="margin-top:4px;">'. $txt['pmx_article_footerhelp'] .'</div>
											<input type="hidden" name="config[show_sitemap]" value="0" />';

			if($this->cfg['ctype'] != 'php')
				echo '
											<div class="adm_check">
												<span class="adm_w80">'. $txt['pmx_articles_disableHSimage'] .'</span>
												<input type="hidden" name="config[settings][disableHSimg]" value="0" />
												<div><input class="input_check" type="checkbox" name="config[settings][disableHSimg]" value="1"' .(isset($this->cfg['config']['settings']['disableHSimg']) && !empty($this->cfg['config']['settings']['disableHSimg']) ? ' checked="checked"' : '').(!empty($context['pmx']['settings']['disableHS']) ? ' disabled="disabled"' : '') .' /></div>
											</div>';

			echo '
										</div>
									</div>';

		// the group access
		echo '
									<div class="cat_bar catbg_grid grid_padd grid_top">
										<h4 class="catbg catbg_grid">
											<img class="grid_click_image pmxleft" onclick=\'Show_help("pmxBH03")\' src="'. $context['pmx_imageurl'] .'information.png" alt="*" title="'. $txt['pmx_information_icon'] .'" />
											<span class="cat_msg_title">'. $txt['pmx_article_groups'] .'</span>
										</h4>
									</div>
									<select id="pmxgroups" onchange="changed(\'pmxgroups\');" style="width:83%;" name="acsgrp[]" multiple="multiple" size="5">';

		if(!empty($this->cfg['acsgrp']))
			list($grpacs, $denyacs) = Pmx_StrToArray($this->cfg['acsgrp'], ',', '=');
		else
			$grpacs = $denyacs = array();

		foreach($this->smf_groups as $grp)
			echo '
										<option value="'. $grp['id'] .'='. intval(!in_array($grp['id'], $denyacs)) .'"'. (in_array($grp['id'], $grpacs) ? ' selected="selected"' : '') .'>'. (in_array($grp['id'], $denyacs) ? '^' : '') . $grp['name'] .'</option>';

		echo '
									</select>
									<div id="pmxBH03" class="info_frame">'. $txt['pmx_article_groupshelp'] .'</div>
									<script type="text/javascript">
										var pmxgroups = new MultiSelect("pmxgroups");
									</script>';

		// article moderate
		if(!isset($this->cfg['config']['can_moderate']))
			$this->cfg['config']['can_moderate'] = 1;

		if(allowPmx('pmx_articles, pmx_create', true))
			echo '
									<input type="hidden" name="config[can_moderate]" value="'. $this->cfg['config']['can_moderate'] .'" />';

		if(allowPmx('pmx_admin, pmx_articles, pmx_create'))
			echo '
									<div class="cat_bar catbg_grid grid_padd grid_top">
										<h4 class="catbg catbg_grid">
											<span class="cat_msg_title">'. $txt['pmx_article_moderate_title'] .'</span>
										</h4>
									</div>';

		if(allowPmx('pmx_admin'))
				echo '
									<div class="adm_check">
										<span class="adm_w80">'. $txt['pmx_article_moderate'] .'
										</span>
										<input type="hidden" name="config[can_moderate]" value="0" />
										<div><input class="input_check" type="checkbox" name="config[can_moderate]" value="1"' .(!empty($this->cfg['config']['can_moderate']) ? ' checked="checked"' : ''). ' /></div>
									</div>';

		echo '
									<input type="hidden" name="config[check_ecl]" value="0" />
									<input type="hidden" name="config[check_eclbots]" value="0" />';

		if(allowPmx('pmx_admin, pmx_articles, pmx_create') && !empty($modSettings['ecl_enabled']))
			echo '
									<div class="adm_check">
										<span class="adm_w80">'. $txt['pmx_check_artelcmode'] .'
										 <img class="info_toggle" onclick=\'Show_help("pmxArteclHelp")\' src="'. $context['pmx_imageurl'] .'information.png" alt="*" title="'. $txt['pmx_information_icon'] .'" />
										</span>
										<div><input class="input_check" type="checkbox" name="config[check_ecl]" value="1"' .(!empty($this->cfg['config']['check_ecl']) ? ' checked="checked"' : ''). ' onclick="showeclbots(this)" /></div>
									</div>
									<div id="pmxArteclHelp" class="info_frame" style="margin-top:0px;">'. $txt['pmx_art_eclcheckhelp'] .'</div>
									<div id="eclextend" style="display:'. (!empty($this->cfg['config']['check_ecl']) ? 'block' : 'none') .'">
									<div class="adm_check">
										<span class="adm_w80">'. $txt['pmx_check_artelcbots'] .'
										 <img class="info_toggle" onclick=\'Show_help("pmxeclHelpbots")\' src="'. $context['pmx_imageurl'] .'information.png" alt="*" title="'. $txt['pmx_information_icon'] .'" />
										</span>
										<div><input id="eclextendinp" class="input_check" type="checkbox" name="config[check_eclbots]" value="1"' .(!empty($this->cfg['config']['check_eclbots']) ? ' checked="checked"' : ''). ' /></div>
									</div>
									<div id="pmxeclHelpbots" class="info_frame" style="margin-top:0;">'. $txt['pmx_art_eclcheckbotshelp'] .'</div>
									<script type="text/javascript">
										function showeclbots(elm) {if(elm.checked == true) document.getElementById("eclextend").style.display = "block"; else {document.getElementById("eclextend").style.display = "none"; document.getElementById("eclextendinp").checked = false;}}
									</script>
								</td>';

		// the visual options
		echo '
								<td width="50%" id="set_col" style="padding:4px;">
									<div class="cat_bar catbg_grid grid_padd">
										<h4 class="catbg catbg_grid"><span class="cat_left_title">'. $txt['pmx_edit_visuals'] .'</span></h4>
									</div>
									<div style="float:left; height:30px; width:177px;">'. $txt['pmx_edit_cancollapse'] .'</div>
									<input style="padding-left:141px;" type="hidden" name="config[collapse]" value="0" />
									<input class="input_check" id="collapse" type="checkbox" name="config[collapse]" value="1"'. ($this->cfg['config']['visuals']['header'] == 'none' ? ' disabled="disabled"' : ($this->cfg['config']['collapse'] == 1 ? ' checked="checked"' : '')) .' />
									<div style="clear:both;" /></div>
									<div style="float:left; height:30px; width:180px;">'. $txt['pmx_edit_collapse_state'] .'</div>
									<select style="width:46%;" size="1" name="config[collapse_state]">';

		foreach($txt['pmx_collapse_mode'] as $key => $text)
			echo '
										<option value="'. $key .'"'. (isset($this->cfg['config']['collapse_state']) && $this->cfg['config']['collapse_state'] == $key ? ' selected="selected"' : '') .'>'. $text .'</option>';
		echo '
									</select>
									<br style="clear:both;" />
									<div style="float:left; height:30px; width:180px;">'. $txt['pmx_edit_overflow'] .'</div>
									<select style="width:46%;" size="1" id="mxhgt" name="config[overflow]" onchange="checkMaxHeight(this);">';

		foreach($txt['pmx_overflow_actions'] as $key => $text)
			echo '
										<option value="'. $key .'"'. (isset($this->cfg['config']['overflow']) && $this->cfg['config']['overflow'] == $key ? ' selected="selected"' : '') .'>'. $text .'</option>';
		echo '
									</select>
									<br style="clear:both;" />
									<div style="float:left; min-height:30px; width:99%;">
										<div style="float:left; min-height:30px; width:180px;">'. $txt['pmx_edit_height'] .'</div>
										<div style="float:left; max-width:46%">
											<input onkeyup="check_numeric(this)" id="maxheight" type="text" style="width:20%" name="config[maxheight]" value="'. (isset($this->cfg['config']['maxheight']) ? $this->cfg['config']['maxheight'] : '') .'"'. (!isset($this->cfg['config']['overflow']) || empty($this->cfg['config']['overflow']) ? ' disabled="disabled"' : '') .' /><span class="smalltext">'. $txt['pmx_pixel'] .'</span><span style="display:inline-block; width:3px;"></span>
											<select id="maxheight_sel" style="float:right;width:52%;margin-right:-1%;" size="1" name="config[height]">';

		foreach($txt['pmx_edit_height_mode'] as $key => $text)
			echo '
												<option value="'. $key .'"'. (isset($this->cfg['config']['height']) && $this->cfg['config']['height'] == $key ? ' selected="selected"' : '') .'>'. $text .'</option>';
		echo '
											</select>
										</div>
									</div>
									<br style="clear:both;" />
									<script type="text/javascript">
										checkMaxHeight(document.getElementById("mxhgt"));
									</script>

									<div style="float:left; height:30px; width:180px;">'. $txt['pmx_edit_innerpad'] .'</div>
									<input onkeyup="check_numeric(this, \',\')" type="text" size="4" name="config[innerpad]" value="'. (isset($this->cfg['config']['innerpad']) ? $this->cfg['config']['innerpad'] : '4') .'" /><span class="smalltext">'. $txt['pmx_pixel'] .' (xy/y,x)</span>
									<br style="clear:both;" />';

		// CSS class settings
		echo '
									<div class="cat_bar catbg_grid grid_padd">
										<h4 class="catbg catbg_grid grid_botpad">
											<div style="float:left;width:177px;"><span class="cat_left_title">'. $txt['pmx_edit_usedclass_type'] .'</span></div>
											<span class="cat_left_title">'. $txt['pmx_edit_usedclass_style'] .'</span>
										</h4>
									</div>
									<div style="margin:0px 2px;">';

		// write out the classes
		foreach($this->usedClass as $ucltyp => $ucldata)
		{
			echo '
										<div style="float:left; width:180px; height:30px; padding-top:2px;">'. $ucltyp .'</div>
										<select'. ($ucltyp == 'frame' || $ucltyp == 'postframe' ? ' id="pmx_'. $ucltyp .'" ' : ' ') .'style="width:46%;" name="config[visuals]['. $ucltyp .']" onchange="checkCollapse(this)">';

			foreach($ucldata as $cname => $class)
					echo '
											<option value="'. $class .'"'. (!empty($this->cfg['config']['visuals'][$ucltyp]) ? ($this->cfg['config']['visuals'][$ucltyp] == $class ? ' selected="selected"' : '') : (substr($cname,0,1) == '+' ? ' selected="selected"' : '')) .'>'. substr($cname, 1) .'</option>';
			echo '
										</select>
										<br style="clear:both;" />';

		}

		echo '
									</div>
									<div class="cat_bar catbg_grid grid_padd">
										<h4 class="catbg catbg_grid"><span class="cat_left_title" style="margin-left:-3px;">'. $txt['pmx_edit_canhavecssfile'] .'</span></h4>
									</div>
									<div style="float:left; margin:0px 2px; width:176px;">'. $txt['pmx_edit_cssfilename'] .'</div>
									<select id="sel.css.file" style="width:46%;margin-bottom:2px;" name="config[cssfile]" onchange="pmxChangeCSS(this)">
										<option value="">'. $txt['pmx_default_none'] .'</option>';

		// custon css files exist ?
		if(!empty($this->custom_css))
		{
			// write out custom mpt/css definitions
			foreach($this->custom_css as $custcss)
			{
				if(is_array($custcss))
					echo '
										<option value="'. $custcss['file'] .'"'. ($this->cfg['config']['cssfile'] == $custcss['file'] ? ' selected="selected"' : '') .'>'. $custcss['file'] .'</option>';
			}
			echo '
									</select>
									<div style="clear:both; height:2px;"></div>';

			// write out all class definitions (hidden)
			foreach($this->custom_css as $custcss)
			{
				if(is_array($custcss))
				{
					echo '
									<div id="'. $custcss['file'] .'" style="display:none;">';

					foreach($custcss['class'] as $key => $val)
					{
						if(in_array($key, array_keys($this->usedClass)))
							echo '
										<div style="float:left; width:180px; padding:0 2px;">'. $key .'</div>'. (empty($val) ? sprintf($txt['pmx_edit_nocss_class'], $settings['theme_id']) : $val) .'<br />';
					}

					echo '
									</div>';
				}
			}
			echo '
									<script type="text/javascript">
										var elm = document.getElementById("sel.css.file");
										var fname = elm.options[elm.selectedIndex].value;
										if(document.getElementById(fname))
											document.getElementById(fname).style.display = "";
										function pmxChangeCSS(elm)
										{
											for(i=0; i<elm.length; i++)
											{
												if(document.getElementById(elm.options[i].value))
													document.getElementById(elm.options[i].value).style.display = "none";
											}
											var fname = elm.options[elm.selectedIndex].value;
											if(document.getElementById(fname))
												document.getElementById(fname).style.display = "";
										}
									</script>';
		}
		else
			echo '
									</select>
									<div style="clear:both; height:6px;"></div>';

									echo '
								</td>
							</tr>
							<tr>
								<td colspan="2" style="text-align:center;padding:4px 4px 0 4px;"><hr class="pmx_hr" />
									<div style="height:10px;"></div>
									<input class="button_submit" type="button" style="margin-right:10px;" value="'. $txt['pmx_save_exit'] .'" onclick="FormFuncCheck(\'save_edit\', \'1\')" />
									<input class="button_submit" type="button" style="margin-right:10px;" value="'. $txt['pmx_save_cont'] .'" onclick="FormFuncCheck(\'save_edit_continue\', \'1\')" />
									<input class="button_submit" type="button" style="margin-right:10px;" value="'. $txt['pmx_cancel'] .'" onclick="FormFunc(\'cancel_edit\', \'1\')" />
								</td>
							</tr>
						</table>
						</div>
					</td>
				</tr>';
	}
}
?>