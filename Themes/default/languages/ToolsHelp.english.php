<?php
// Version: 1.0 RC1; ToolsHelp

global $helptxt;

// Admin help messages
$helptxt['dont_use_lightbox'] = 'If <b>enabled</b>, images and attaches in messages can be displayed enlarged.<br>
If you have more than one image in a message, it will be shown like a gallery.<br>You can also disable any image or attach by adding a <b>expand=off</b> to the IMG or ATTACH bbc code, like [img expand=off] or [attach expand=off]';
$helptxt['enable_quick_reply'] = 'This setting allows all users to using the Quick Reply box on the message index page.';
$helptxt['add_favicon_to_links'] = 'This settings add a favicon (if the site have one) to each link with the class "bbc_link".';

$helptxt['ecl_enabled'] = 'This make your PortaMx Forum compatible with the <b>EU Cookie Law</b>.<br>
If enabled, any visitor (except spider) must accept the storage of cookies before he can browse the forum.<br>
More information you find on <a href="http://ec.europa.eu/ipg/basics/legal/cookies/index_en.htm" target="_blank">European Commission</a>';
$helptxt['ecl_nomodal'] = 'Normaly the Forum are not accessible until ECL is accepted.<br>
If you enable the <b>none modal mode</b>, the site is accessible and a Vistor can simple browse the forum.
 <b>Note, that is this case any additional modification or adsense content can store cookies!</b>';
$helptxt['ecl_nomodal_mobile'] = 'On Mobile devices the ECL mode is normaly switched to <b>modal mode</b>. Here you can disable this, so the <b>none modal mode</b> is used.';
$helptxt['ecl_topofs'] = 'Here you can set the top position for the ECL overlay.';

$helptxt['portal_enabled'] = 'This settings will enable the integrated <b>Portal System</b>.<br>
The Portal System expand your forum with many functions like a Frontpage, Panels on left, right, top and bottom and a featured category/article system.';

$helptxt['disclaimer_link'] = 'Here you can setup a <b>link</b> to a file or action, to show a Disclaimer for your Forum.
 You can use a html page or a Portal Block for this. The link contains the users language and is inserted in the footer after the copyright.
 Note, that you NOT use the forum url here, these is automatically added. Also you have to create a Disclaimer for any language you use in your forum.<br>
Examples for a link: <b>mypages/disclaimer.html</b> or <b>?spage=pagename</b>. The first example use the link <b>forumurl/mypages/disclaimer_language.html</b>,
 the second example use the link <b>forumurl/index.php?spage=pagename_language</b> (this is a Portal singe Page). Note, that you have to create a Disclaimer for any language you use in your Forum.';
?>