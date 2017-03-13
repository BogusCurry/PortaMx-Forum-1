<?php
// Version: 1.0 RC1; AdminSettings

/**
 * PortaMx Forum
 * @package PortaMx
 * @author PortaMx
 * @copyright 2017 PortaMx
 *
 * Language file AdminSettings.english
 */

// global
$txt['pmx_global_settings'] = 'Global settings';
$txt['pmx_settings_panelpadding'] = 'Padding between Portal elements:';
$txt['pmx_settings_panelpadding_help'] = 'Space between the panels and the Forum area, and clearance between the individual portal elements.';
$txt['pmx_settings_paneloverflow'] = 'Action on Panel height overflow:';
$txt['pmx_settings_download'] = 'Show download button on Menubar:';
$txt['pmx_settings_download_action'] = 'Action name for download button:';
$txt['pmx_settings_download_acs'] = 'Membergroups they can see the download button:';
$txt['pmx_settings_other_actions'] = 'Request names they handled as Forum request:';
$txt['pmx_settings_enable_xbars'] = 'Panels to collapse/expand with <b>xBars</b>:';
$txt['pmx_settings_all_toggle'] = 'Toggle all on/off';
$txt['pmx_settings_enable_xbarkeys'] = 'Enable panel <b>xBarKeys</b>:';
$txt['pmx_settings_xbar_topoffset'] = 'Offset from top for <b>Head Panel, Top Panel</b> xBars:';
$txt['pmx_settings_xbar_botoffset'] = 'Offset from bottom for <b>Bottom Panel, Foot Panel</b> xBars:';
$txt['pmx_frontpage_settings'] = 'Frontpage settings';
$txt['pmx_settings_frontpage_none'] = 'No Frontpage, go directly to Forum:';
$txt['pmx_settings_frontpage_centered'] = 'Show the Frontpage:';
$txt['pmx_settings_pages_hidefront'] = 'Hide the Frontpage blocks on Pages, Categories or Articles:';
$txt['pmx_settings_index_front'] = 'Enable the Frontpage indexing for spider:';
$txt['pmx_settings_restoretop'] = 'Set or Restore the browser vertical page position:';
$txt['pmx_settings_restorespeed'] = 'Restore animate time:';
$txt['pmx_settings_loadinactive'] = 'Load not active blocks on Blocks Manager overview:';
$txt['pmx_settings_colminwidth'] = 'Minimal Frontpage area width to show two column blocks as defined:';
$txt['pmx_settings_collapse_visibility'] = 'Collapse the <b>Dynamic visibility</b> panel:';
$txt['pmx_settings_enable_xbars'] = 'Panels to collapse/expand with <b>xBars</b>:';
$txt['pmx_settings_postcountacs'] = 'Use Post count based groups for access settings:';

// panels
$txt['pmx_panel_settings'] = 'Panel settings';
$txt['pmx_settings_panelset'] = 'Settings';
$txt['pmx_settings_panelhead'] = 'Head panel';
$txt['pmx_settings_panelleft'] = 'Left panel';
$txt['pmx_settings_panelright'] = 'Right panel';
$txt['pmx_settings_paneltop'] = 'Top panel';
$txt['pmx_settings_panelbottom'] = 'Bottom panel';
$txt['pmx_settings_panelfoot'] = 'Foot panel';
$txt['pmx_settings_panelhidetitle'] = 'Hide the panel on section:';
$txt['pmx_settings_panel_customhide'] = 'Hide the panel on action:';
$txt['pmx_settings_panel_collapse'] = 'Disable panel collapse:';
$txt['pmx_settings_panelwidth'] = 'Width of panel:';
$txt['pmx_settings_panelheight'] = 'Max height of panel:';
$txt['pmx_pixel'] = 'Pixel';

$txt['pmx_hw_pixel'] = array(
	'head' => 'Pixel or leave blank',
	'top' => 'Pixel or leave blank',
	'bottom' => 'Pixel or leave blank',
	'foot' => 'Pixel or leave blank',
	'left' => 'Pixel',
	'right' => 'Pixel'
);
$txt['pmx_settings_hidehelp'] = 'To hide the panel, select or unselect one or more options by hold down the <b>Ctrl Key</b> and <b>click</b> on the items.<br />
	To toggle between <b>Show and Hide</b>, hold down the <b>Ctrl Key</b> and take a <b>double click</b> (IE needs three clicks!) on the item.
	If a item set to <b>Hide</b> the symbol <b>^</b> is shown at the front.<br />
	<b>Select example</b>: On "Admin" the panel is hidden only on <i>Admin</i>, on "^Admin" the panel is always hidden, but not on <i>Admin</i>';

// manager control
$txt['pmx_global_program'] = 'Manager control settings';
$txt['pmx_settings_quickedit'] = 'Show a <b>quick edit link</b> on the block titlebar:';
$txt['pmx_settings_adminpages'] = 'Panels on which a Portal Moderator has access:';
$txt['pmx_settings_article_on_page'] = 'Number of Articles on the Manager overview page:';
$txt['pmx_settings_enable_promote'] = 'Enable the Promote messages feature:';
$txt['pmx_settings_promote_messages'] = 'Currently promoted messages:';

// access settings
$txt['pmx_access_settings'] = 'Access settings';
$txt['pmx_access_promote'] = 'Membergroups they can promote posts:';
$txt['pmx_access_articlecreate'] = 'Membergroups they can create articles:';
$txt['pmx_access_articlemoderator'] = 'Membergroups they can create, moderate and approve articles:';
$txt['pmx_access_blocksmoderator'] = 'Membergroups they can moderate blocks in enabled panels:';
$txt['pmx_access_pmxadmin'] = 'Membergroups they can Administrate the entire Portal:';

// help;
$txt['pmx_settings_index_front_help'] = 'If checked, the Frontpage content can be indexed by spiders like google.';
$txt['pmx_settings_restoretop_help'] = 'The browser vertically page position is set or restored on many request like change the page, category, article and on all Admin actions.';
$txt['pmx_settings_restorespeed_help'] = 'Here you can set the animate time (milliseconds) to restore the top position. Note that this not works on all Browser, so it\'s better to leave this empty!';
$txt['pmx_settings_restorespeed_time'] = ' milliseconds';
$txt['pmx_settings_loadinactive_help'] = 'If enabled, not active blocks on the <b>top, head, left, right, bottom and foot</b> panel are loaded but not shown on the blocks manager overview.
	So you can see the result immediate if you enable not active blocks.<br />
	If <b>not checked</b>, you must reload the page to see the result after enable not active blocks.';
$txt['pmx_settings_colminwidth_help'] = 'Enter the minimum width of the <b>frontpage area</b> to show two-column frontpage blocks (like boardnews, promoted posts) also on <b>mobile devices</b> as two-column blocks.
	If the width of the frontpage area less than the specified value, two-column blocks are displayed as one-column blocks.
	A value of <b>620</b> is a good choice to display these on smaller devices as single-column blocks. Enter <b>0</b> or leave this empty to disable this feature.<br>
	Note that <b>Caching</b> must be activated, if you have the <b>ECL</b> function enabled else <b>this function don\'t work!</b>';
$txt['pmx_access_promote_help'] = 'Members in the selected groups can promote posts in the forum.<br />
	<b>Granted rights:</b> <i>Add and remove promote to posts</i>';
$txt['pmx_access_articlecreate_help'] = 'Members in the selected groups can create articles, edit or delete his own articles.
	Articles the created by this membergroups must be approved by a Article Moderator or Administrator.<br />
	<b>Granted rights:</b> <i>Create article, edit, clone, delete, activate/deactivate own articles</i>';
$txt['pmx_access_articlemoderator_help'] = 'Members in the selected groups can create, edit, delete and approve articles they enabled for <b>Moderate Article</b>.
	This is always given, if a article created by the Article create groups.<br />
	<b>Granted rights:</b> <i>Create article, edit, clone, delete, activate/deactivate, approve/unapprove</i>';
$txt['pmx_access_blocksmoderator_help'] = 'Members in the selected groups can edit blocks they enabled for <b>Moderate Blocks</b>.
	The access to the blocks is limited by the enabled panels (see Manager settings).<br />
	<b>Granted rights:</b> <i>Edit the content, access, title, css settings, activate/deactivate</i>';
$txt['pmx_access_pmxadmin_help'] = 'Members in the selected groups have <b>full access</b> to all parts of the entire Portal.
	The Members have the same rights as a Forum Admin, but limited to the Portal. <b>Handle this with care !</b>';
$txt['pmx_frontpage_help'] = 'Select the Frontpage, which you use.<br />
	Note, that the full size Frontpage normally have <b>no</b> Menubar, but you can enable a small Menubar.<br />
	Single pages are always displayed, even if the Frontpage set to "no Frontpage".<br />
	If you need a additional CSS for the full size Frontpage, create a CSS file (<b>frontpage.css</b>) and save it to the directory of the theme.';
$txt['pmx_settings_adminpageshelp'] = 'Members in the <b>PortaMx Moderator group</b> can change the settings on the Blocks Manager overview and edit the content of the blocks they enabled for moderate.<br />
	<b>Handle this option with care!</b>';
$txt['pmx_settings_xbars_help'] = 'Select the panels, they you can collapse or expand with the xBars.
	<b>xBars</b> are narrow strips on the left, right, up and down edge of the browser area, they shown once you move the mouse over the area.
	The xBars also work with mobile devices.';
$txt['pmx_settings_collapse_vishelp'] = 'The panel is used in Block settings. You can collapse that initially, but it\'s shown always if the Block have dynamic visibility options.';
$txt['pmx_settings_xbarkeys_help'] = 'If checked, you can collapse/expand the panels with the <b>Alt</b> key and a <b>Numpad</b> key (<b>4=left, 6=right, 9=head, 8=top, 2=bottom, 3=foot</b>). Note that the <b>xBarKeys</b> are disabled if the editor loaded.';
$txt['pmx_settings_blockcachestatshelp'] = 'If enabled the pmx-cache status is shown in the footer above the page load.';
$txt['pmx_settings_hidecopyrighthelp'] = 'Enter the codekey as you received. If the key valid for your domain and not expired, the PortaMx copyright is not shown.
	Please use copy and paste (the key is longer as the inputfield) to put in the correct code.';
$txt['pmx_settings_panel_custhelp'] = 'Here you can enter any other actions.
	For <b>Single pages, Articles and Categories</b> we use a prefix (<b>p:</b> for Single pages, <b>a:</b> for Articles and <b>c:</b> for Categories).
	Enter the prefix before the page, article or category name, as example <b>p:mypage</b>.
	You can use names with the wildcards <b>*</b> and <b>?</b>. In this case the panel is invisible, whose name matched.
	Furthermore you can also use subaction, these starts alway with a ampersand (<b>&amp;</b>) like <b>&amp;subactionname=value</b>.
	For more detailed informations about the customer actions read our documentation.';
$txt['pmx_settings_downloadhelp'] = 'If checked, a <b>Download</b> button is shown next to the <b>Communuty</b> button.';
$txt['pmx_settings_dl_actionhelp'] = 'Define the action which the download button to be assigned.<br />
	You can use any name with the character (<b>a-z, A-Z, 0-9, -, _, .</b>).<br />For Single pages, Articles and Categories you have to add a prefix before the name
	(<b>p:</b> for Single pages, <b>a:</b> for Articles and <b>c:</b> for Categories) as example <b>p:download</b>';
$txt['pmx_settings_other_actionshelp'] = 'Enter one or more request names (separated by comma) they are handled as Forum requests.
	You can enter <b>name=value</b> pairs like <b>project=1</b> for the Project tool.';
$txt['pmx_settings_quickedithelp'] = 'You can enable a direct link to the Manager <b>edit function</b>.
	The links is associated to the <b>title</b> and is active only for Admins and Portal Admins.';
$txt['pmx_settings_pages_help'] = 'Enter names for Singe Pages, Categories and Articles (separated by comma), for which you will hide the Frontpage blocks.
	Use the prefix <b>p:</b> for Single pages, <b>a:</b> for Articles and <b>c:</b> for Categories.
	You can use names with the wildcards <b>*</b> and <b>?</b><br />
	Leave this empty, if you want to place Frontpage block individually with the block settings.';
$txt['pmx_settings_article_on_pagehelp'] = 'Enter the number of Articles you will see in the Article Manager overview page';
$txt['pmx_settings_postcountacshelp'] = 'Use the Forum Post count based groups for the block access, additional to the Regular groups.';
$txt['pmx_settings_teasermode'] = array(
	0 => 'Choose the counting method for the Post teaser:',
	1 => 'Count words',
	2 => 'Count characters'
);
$txt['pmx_settings_pmxteasecnthelp'] = 'In different blocks a <i>Post teaser</i> is used.
	Here you can set, as the teaser is supposed to work.
	For languages that do not use spaces between words, the setting, <b>Count characters</b> is suggest.';
$txt['pmx_settings_promote_messages_help'] = 'You see all promoted message id\'s and you can add or remove message id\'s. Note that each id is separated by a comma.';
$txt['pmx_settings_enable_promote_help'] = 'If checked the Promote function is enabled and you see a <b>Promote message</b> link belove each message. If the message already promoted, the link is show as <b>Clear Promote</b>.';
?>