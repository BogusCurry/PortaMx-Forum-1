/**
 * PortaMx Forum
 * @package PortaMx
 * @author PortaMx
 * @copyright 2017 PortaMx
 *
 * file PortalFader.js
 * Javascript functions for Opac Fader
 *
 * @version 1.0 RC3
 */

function PmxOpacFader(aOptions)
{
	this.opt = aOptions;
	var elm = document.getElementById(this.opt.fadeContId);
	elm.innerHTML = this.opt.fadeData[this.opt.fadeCsr];
	this.FadeUp(90);
}

PmxOpacFader.prototype.FadeUp = function(start)
{
	if(start == null)
		this.FadeChangeData();
	this.FadeOpacity(start ? start : 0, 100, this.opt.fadeUptime[this.opt.fadeCsr]);
	setTimeout(this.opt.fadeName + '.FadeDown();', this.opt.fadeUptime[this.opt.fadeCsr] + this.opt.fadeHoldtime[this.opt.fadeCsr]);
}

PmxOpacFader.prototype.FadeDown = function()
{
	this.FadeOpacity(100, 0, this.opt.fadeDowntime[this.opt.fadeCsr]);
	setTimeout(this.opt.fadeName + '.FadeUp();', this.opt.fadeDowntime[this.opt.fadeCsr] + this.opt.fadeChangetime);
}

PmxOpacFader.prototype.FadeOpacity = function(opacStart, opacEnd, millisec)
{
	//speed for each frame
	var speed = Math.round(millisec / 100);
	var timer = 0;

	if(opacStart > opacEnd)
	{
		for(var iOpac = opacStart; iOpac >= opacEnd; iOpac--)
		{
			setTimeout(this.opt.fadeName + '.FadeChangeOpac(' + iOpac + ');', timer * speed);
			timer++;
		}
	}
	else if(opacStart < opacEnd)
	{
		for(var iOpac = opacStart; iOpac <= opacEnd; iOpac++)
		{
			setTimeout(this.opt.fadeName + '.FadeChangeOpac(' + iOpac + ');', timer * speed);
			timer++;
		}
	}
}

PmxOpacFader.prototype.FadeChangeOpac = function(iOpac)
{
	var elm = document.getElementById(this.opt.fadeContId).style;
	if(is_ie)
	{
		elm.zoom = 1;
		elm.opacity = (iOpac / 100);
		elm.filter = 'Alpha(Opacity=' + iOpac + ')';
		elm.filter = 'progid:DXImageTransform.Microsoft.Alpha(Opacity=' + iOpac + ')';
	}
	else
	{
		elm.opacity = (iOpac / 100);
		elm.MozOpacity = (iOpac / 100);
		elm.KhtmlOpacity = (iOpac / 100);
	}
}

PmxOpacFader.prototype.FadeChangeData = function()
{
	this.opt.fadeCsr++;
	if(this.opt.fadeCsr >= this.opt.fadeData.length)
		this.opt.fadeCsr = 0;
	var elm = document.getElementById(this.opt.fadeContId);
	elm.innerHTML = this.opt.fadeData[this.opt.fadeCsr];
	pmxCookie('set', this.opt.fadeName, this.opt.fadeCsr);
}
/* EOF */