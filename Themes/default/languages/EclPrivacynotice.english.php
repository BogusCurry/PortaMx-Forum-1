<?php
// Version: 1.0 RC1; EclPrivacynotice

/**
	Additional informations for this file format:
	We have 3 tokens, they replaced at run time:
	@site@   - replace with the Forum name
	@host@   - replaced with the Domain name
	@cookie@ - replaced with the cookie you have setup
*/

/* Header text */
$txt['ecl_header'] = '
	<p style="text-align:center;"><strong>Privacy Notice for "@site@"</strong></p><br />
	To comply with <a href="http://ec.europa.eu/ipg/basics/legal/cookies/index_en.htm" target="_blank" class="ecl_link">European Union law</a>,
	we are required to inform users accessing "@host@" from within the EU about the cookies that this site uses and the information they contain
	and also provide them with the means to "opt-in" - in other words, permit the site to set cookies.
	Cookies are small files that are stored by your browser and all browsers have an option whereby you can inspect
	the content of these files and delete them if you wish.<br /><br />
	The following table details the name of each cookie, where it comes from and what we know about the information
	that cookie stores:<br /><br />';

/*
	All cookie informations
	if you have more cookies, add them at the end with the same format
*/
$txt['ecl_headrows'] = array(
	array(
		'<div><b>Cookie</b></div>',
		'<div><b>Origin</b></div>',
		'<div><b>Persistency</b></div>',
		'<div><b>Information and Usage</b></div>',
	),
	array(
		'eclauth',
		'@host@',
		'Expires after 30 days',
		'This cookie contains the text "LiPF_cookies_authorised".
			Without this cookie, the Website software is prevented from setting other cookies.',
	),
	array(
		'@cookie@',
		'@host@',
		'Expires according to user-chosen session duration',
		'If you log-in as a member of this site, this cookie contains your user name, an encrypted hash of
			your password and the time you logged-in. It is used by the Website software to ensure that features such as indicating
			new Forum and Private messages are indicated to you.',
	),
	array(
		'PHPSESSID',
		'@host@',
		'Current session only',
		'This cookie contains a unique Session Identification value. It is set for both members and
			non-members (guests) and it is essential for the site software to work completely. This cookie is not persistent
			and should be automatically removed when you close the browser window.',
	),
	array(
		'pmx_cbtstat{ID}',
		'@host@',
		'Current session only',
		'These cookies are set to records the expand/collapse state for the CBT Navigator block content.',
	),
	array(
		'pmx_poll{ID}',
		'@host@',
		'Current session only',
		'These cookies are set to records the id for the current poll in a multiple Poll block.',
	),
	array(
		'pmx_upshr{NAME}',
		'@host@',
		'Current session only',
		'These cookies are set to records your display preferences for the site\'s Portal page if a panel
			or individual block is collapsed or expanded.',
	),
	array(
		'pmx_{fadername}',
		'@host@',
		'Current session only',
		'These cookies are set to records the state for the Opac-Fader block.',
	),
	array(
		'pmx_shout{ID}',
		'@host@',
		'Current session only',
		'These cookies are set to records the current state of the Shout box block.',
	),
	array(
		'pmx_YOfs, pmx_YOfs2',
		'@host@',
		'Page load time',
		'These cookies will probably never see you.
			It\'s set on portal Action such as a click on page numbers or on Admin action.
			The cookies are evaluated when loading the desired page and then deleted.
			It\'s used to set or restore the vertical screen position.',
	),
	array(
		'screen',
		'@host@',
		'Current session only',
		'This cookie contains the orientation and width of the frontpage area and is only used on Mobile Devices.',
	),
	array(
		'upshrinkIC',
		'@host@',
		'Current session only',
		'This cookie contains the collapse/expand state of the @host@ - Info Center and is set only for non-members (guests).',
	),
);

/* footer header */
$txt['ecl_footertop'] = '
	<span><strong>Notes:</strong></span><br />';

/* footer informations */
$txt['ecl_footrows'] = array(
/* remove the comment if you use Google Adsense
	'We use Google AdSense, therefore cookies are set by Google, to analyze visits to our website and personalize content and ads.
	Informations on the use of our website are anonymous distributed to our partner for social media, to adapt advertising and analysis.',
*/
	'If you are accessing this site using someone else\'s computer, please ask the owner\'s permission before
		accepting cookies.',

	'Your browser provides you with the ability to inspect all cookies stored on your PC. In addition your browser
		is responsible for removing "current session only" cookies and those that have expired; if your browser is
		not doing this, you should report the matter to your browser\'s authors.',

	'We regret and apologies for any inconvenience this causes to members and guests who are accessing our web site
		from outside the European Union. It is currently not possible to interrogate your browser and obtain geographic
		location information in order to decide whether or not to prompt you to accept cookies.',
);

/* last line for ecl privacy */
$txt['ecl_footer'] = '
	<br>For further and fuller information about cookies and their use, please visit
		<a target="_blank" class="ecl_link" href="http://www.allaboutcookies.org">All About Cookies</a>';
?>