/**
 * PortaMx Forum
 * @package PortaMx
 * @author PortaMx
 * @copyright 2017 PortaMx
 *
 * file Portal.js
 * Common Javascript functions
 *
 * @version 1.0 RC3
 */

// Print the block content
function PmxPrintPage(pdir, cid, chars, cttl, lbhelp, lblabel)
{
	var content = document.getElementById('print'+ cid).innerHTML;
	content = content.replace(/<br>/g, '<br />');
	content = content.replace(/<hr>/g, '<hr />');
	content = content.replace(/<img([^>]*)>/g, '<img$1 />');
	var pmxprint = window.open(window.location.href, 'printer', '');
	pmxprint.document.open();
	pmxprint.document.write('<!DOCTYPE html>');
	pmxprint.document.write('<html dir="'+ pdir +'">');
	pmxprint.document.write('<head><meta charset="'+ chars +'">');
	pmxprint.document.write('<meta name="viewport" content="width=device-width, initial-scale=1">');
	pmxprint.document.write('<title>Print of "'+ cttl +'"</title>');
	pmxprint.document.write('<link rel="stylesheet" type="text/css" href="'+ pmx_default_theme_url +'/Portal/SysCss/portal_print.css" />');
	pmxprint.document.write('<link rel="stylesheet" type="text/css" href="'+ pmx_default_theme_url +'/Portal/SysCss/portal.css" />');
	pmxprint.document.write('<link rel="stylesheet" type="text/css" href="'+ pmx_default_theme_url +'/css/lightbox.css" />');
	pmxprint.document.write('<script>var mobile_device=false;var Lightbox_help="'+ lbhelp +'";var Lightbox_label="'+ lblabel +'";</script>');
	pmxprint.document.write('<script src="'+ pmx_default_theme_url +'/scripts/jquery-2.2.4.min.js"></script>');
	pmxprint.document.write('<script src="'+ pmx_default_theme_url +'/scripts/lightbox.js"></script>');
	pmxprint.document.write('</head>');
	pmxprint.document.write('<body class="pmx_printbody"><div style="text-align:center;font-size:1.2em;font-weight:bold;">'+ cttl +'</div><hr />'+ content);
	pmxprint.document.write('</body></html>');
	pmxprint.document.close();
}

// Submit a static block
function pmx_StaticBlockSub(id, elm, pValue, uid)
{
	var sUrl = decodeURI(elm.href);
	elm.href = 'javascript:void(0)';
	sUrl = sUrl.substr(pValue.length -1);
	pmxCookie('set', 'pmx_YOfs', pmxWinGetTop(uid, 'StaticBlock'));
	document.getElementById(id).value = sUrl;
	document.getElementById(id +'_form').submit();
}

// Set data using AJAX POST Request.
function pmxXMLpost(sUrl, sData)
{
	var sResult = '';
	$.ajax({type: 'POST', async:false, url:sUrl, data:sData, success:function(data){sResult = data;}});
	return sResult;
}

// Submit language
function pmxSetlang(elm, id)
{
	if(id != '_ecl')
		pmxWinGetTop(id, 'SetLang');

	document.getElementById('pmxlangval'+ id).value = elm.options[elm.selectedIndex].value;
	document.getElementById('pmxlangchg'+ id).submit();
}

// expand / collapse a teased html page
var HTMLpagetop;
function ShowHTML(pageid)
{
	var shortid = 'short_'+ pageid;
	var fullid = 'full_'+ pageid;
	if(document.getElementById(fullid).style.display == 'none')
	{
		document.getElementById('href_'+ shortid).href = 'javascript:void(0)';
		HTMLpagetop = pmxWinGetTop();
		$(document.getElementById(fullid)).slideDown(400);
		$(document.getElementById(shortid)).hide(400)
	}
	else
	{
		document.getElementById('href_'+ fullid).href = 'javascript:void(0)';
		pmx_RestoreScrollTop(HTMLpagetop);
		$(document.getElementById(shortid)).slideDown(400);
		$(document.getElementById(fullid)).hide(400)
		HTMLpagetop = '';
	}
}

// expand / collapse a message attaches
function ShowMsgAtt(elm, sID)
{
	var cstat = document.getElementById(sID).style.display;
	if(cstat == 'none')
	{
		$(document.getElementById(sID)).slideDown(0, function(){portamx_EqualHeight(0);});
		elm.style.display = 'none';
		do elm = elm.nextSibling; while(elm.tagName != 'A');
		elm.style.display = '';
	}
	else
	{
		$(document.getElementById(sID)).slideUp(0, function(){portamx_EqualHeight(0);});
		elm.style.display = 'none';
		do elm = elm.previousSibling; while(elm.tagName != 'A');
		elm.style.display = '';
	}
}

// Get window top postion
var currentTop;
function pmxWinGetTop(uid, sSend)
{
	if(uid == 'adm')
	{
		var elemRect = $('#portal_main').offset();
		currentTop = parseInt(elemRect.top) -6;
	}
	else
		currentTop = $(window).scrollTop();

	if(uid && sSend)
		pmxCookie('set', 'pmx_YOfs', currentTop);

	return currentTop;
}

// Resize(Rotate) event on mobile devices
function eResizeFunc()
{
	portamx_onload()
}

// Pmx onLoad fuction
function portamx_onload()
{
	// OnLoad forum functions
	sysOnLoad();
	if(reloadCalled == true)
		return;

	// restore top position
	currentTop = pmxCookie('get', 'pmx_YOfs', '', 'set');
	if(currentTop.indexOf('#') == 0)
	{
		cTop = currentTop.substr(1);
		temp = $('#'+ cTop).offset(); 
		currentTop = parseInt(temp.top) -2;
	}
	else
		currentTop = parseInt(currentTop);

	if(pmx_onForum)
		currentTop = '';

	if(mobile_device == true)
		setTimeout(portamx_EqualHeight,50);
	else
		setTimeout(portamx_EqualHeight, 150);
}

// set div's to equal height
function portamx_EqualHeight(skipsetTop)
{
	if(!pmx_onForum)
	{
		var rightRows = [];
		var leftRows = [];
		rightRows = $("div[class='pmxEQHR']").each(function(){rightRows += $(this);});
		if(rightRows.length > 0)
		{
			leftRows = $("div[class='pmxEQHL']").each(function(){leftRows += $(this);});
			for(var i = 0; i < rightRows.length; i++)
			{
				rightRows[i].style.minHeight = null;
				leftRows[i].style.minHeight = null;
				if(rightRows[i].clientHeight > leftRows[i].clientHeight)
					rightRows[i].style.minHeight = leftRows[i].style.minHeight = rightRows[i].clientHeight +'px';
				else
					leftRows[i].style.minHeight = rightRows[i].style.minHeight = leftRows[i].clientHeight +'px';
			}
		}
	}

	if(skipsetTop !== null && skipsetTop !== 0 && !skipsetTop && !pmx_onForum)
		window.setTimeout('pmx_RestoreScrollTop()', 150);
}

function pmx_RestoreScrollTop(toppos)
{
	if(pmx_restore_top)
	{
		if(!isNaN(toppos))
			$('html,body').scrollTop(toppos);
		else if(!isNaN(currentTop))
			$('html,body').scrollTop(currentTop);
	}
}

// xbarkey events
var xBarInAdmin = (window.location.href.indexOf('action=portal') > 0 || window.location.href.indexOf('action=admin') > 0);
function xBarKeys(Events)
{
	if(pmx_onedit)
		return;

	if(pmx_xBarKeys)
	{
		if(!Events)
			var Events = window.event;
		if(Events.altKey)
		{
			if(Events.which)
				xKey = Events.which;
			else
			{
				if(Events.keyCode)
					xKey = Events.keyCode;
			}
			Events = null;
			switch(xKey)
			{
				case 105:
					if(pmx_inAdmin && pmx_blockOnOff_enabled)
					{
						var curTop = $(window).scrollTop();
						var oldHight = document.getElementById('pmx_head_panel').scrollHeight;
						document.getElementById('pmx_head_panel').style.display = (document.getElementById('pmx_head_panel').style.display == 'block' ? 'none' : 'block');
 						AdjustTop('head', curTop, oldHight);
					}
					else
						headPanel.toggle();
					return false;

				case 104:
					if(pmx_inAdmin && pmx_blockOnOff_enabled)
					{
						var curTop = $(window).scrollTop();
						var oldHight = document.getElementById('pmx_top_panel').scrollHeight;
						document.getElementById('pmx_top_panel').style.display = (document.getElementById('pmx_top_panel').style.display == 'block' ? 'none' : 'block');
						AdjustTop('top', curTop, oldHight);
					}
					else
						topPanel.toggle();
					return false;

				case 100:
					leftPanel.toggle();
					return false;

				case 102:
					rightPanel.toggle();
					return false;

				case 98:
					bottomPanel.toggle();
					return false;

				case 99:
					footPanel.toggle();
					return false;
			}
		}
	}
}

function AdjustTop(side, curTop, oldHight)
{
	var delta = oldHight - document.getElementById('pmx_' + side + '_panel').scrollHeight;
	$('body, html').scrollTop(delta >= 0 ? curTop - delta : curTop + Math.abs(delta));
}
/* eof */