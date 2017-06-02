// UTILs - TRANSIT.js - r-dc_ 2010

/*
var Observable = function() {
    this.subscribers = [];
}

Observable.prototype = {
	subscribe: function(callback) {
		if(!~this.subscribers.indexOf(callback))
			this.subscribers.push(callback);
	},
	unsubscribe: function(callback) {
		var i = 0,
			len = this.subscribers.length;

		// Iterate through the array and if the callback is
		// found, remove it.
		for (; i < len; i++) {
			if (this.subscribers[i] === callback) {
				this.subscribers.splice(i, 1);
				// Once we've found it, we don't need to
				// continue, so just return.
				return;
			}
		}
	},
	publish: function(data) {
		var i = 0,
			len = this.subscribers.length;

		// Iterate over the subscribers array and call each of
		// the callback functions.
		for (; i < len; i++) {
			this.subscribers[i](data);
		}
	}
};
*/


function getCaretPosition(areaElement) {
	if (areaElement.selectionStart)
		return areaElement.selectionStart;
	else if (document.selection) {
		areaElement.focus();

		var range = document.selection.createRange();
		if (range === null)
			return 0;

		var textRange = areaElement.createTextRange(),
		duplicate = textRange.duplicate();
		textRange.moveToBookmark(range.getBookmark());
		duplicate.setEndPoint('EndToStart', textRange);

		return duplicate.text.length;
	}
	return 0;
}

//https://remysharp.com/2010/07/21/throttling-function-calls#comment-1737112654
function throttle(func, ms){
	var timeout, last = 0;
	return function() {
		var a = arguments,
			t = this,
			now = +(new Date()),
			exe = function() {
				last = now;
				func.apply(t,a);
			}
		clearTimeout(timeout);
		if(now >= last + ms)
			exe();
		else
			timeout = setTimeout(exe, ms);
	}
}

var COR = function() {
    this.nextChain = null;
}
COR.prototype.chainExecuter = function(data) {
	if(!this.chainExecute(data) && this.nextChain)
		this.nextChain.chainExecuter(data);
}

COR.prototype.setNextChain = function(nextChain) {
	this.nextChain = nextChain;
}


// useful Poop Templating Solution (PTS for short)
// because I wanted to try it and did not have time to redo it afterwhile.
HtmlTemplate = function (template) {
	this.template = template;
}
HtmlTemplate.prototype.compile = function () {
//	console.log('compiling template');
	var template = this.template;
	template = template.replace(/'/g, '&quot;');
	template = template.replace(/{{\s?(\w+)\s?}}/igm, '\'+data.$1+\'').replace(/(?:\r\n|\r|\n)/g, '\\n');

	this.template = new Function('data', '"use strict"; return \''+ template +'\'');
}
HtmlTemplate.prototype.execute = function (data) {
	if(Object.prototype.toString.call(this.template) != '[object Function]')
		this.compile();

	return this.template(data);
}

function getPosTop(el) {
	var offset = 0;
	while(el) {
		offset += el.offsetTop;
		el = el.offsetParent;
	}
	return offset;
}

function isVisible(elt) {
	if (!elt)
		return false;

	var posTop = getPosTop(elt)+10;
	var posBottom = posTop + elt.offsetHeight;
	var visibleTop = (document.body.scrollTop?document.body.scrollTop:document.body.scrollTop);
	var visibleBottom = visibleTop + document.body.offsetHeight/*-(document.body.offsetHeight/2)*/;
	return ((posBottom >= visibleTop) && (posTop <= visibleBottom));
}

function getAnimationState (el) {
	return el.style.webkitAnimationPlayState || el.style.mozAnimationPlayState || el.style.msAnimationPlayState || el.style.oAnimationPlayState || el.style.animationPlayState;
}

function setAnimationState (el, state) {
	el.style.webkitAnimationPlayState=state;
	el.style.mozAnimationPlayState   =state;
	el.style.msAnimationPlayState    =state;
	el.style.oAnimationPlayState     =state;
	el.style.animationPlayState      =state;
}

function toggleAnimation (el) {
	if (getAnimationState(el)=='paused')
		setAnimationState (el, 'running');
	else
		setAnimationState (el, 'paused');
}

function setAnimation (el, param) {
	el.style.webkitAnimation=param;
	el.style.mozAnimation   =param;
	el.style.msAnimation    =param;
	el.style.oAnimation     =param;
	el.style.animation      =param;
}

// Prevents event bubble up or any usage after this is called.
eventCancel = function (e) {
	if (!e)
		if (window.event) e = window.event;
		else return;
	if (e.cancelBubble !== null) e.cancelBubble = true;
	if (e.stopPropagation) e.stopPropagation();
	if (e.preventDefault) e.preventDefault();
	if (window.event) e.returnValue = false;
	if (e.cancel !== null) e.cancel = true;
}

//http://stackoverflow.com/a/10997390
function updateURLParameter(url, param, paramVal)
{
    var TheAnchor = null;
    var newAdditionalURL = "";
    var tempArray = url.split("?");
    var baseURL = tempArray[0];
    var additionalURL = tempArray[1];
    var temp = "";

    if (additionalURL)
    {
        var tmpAnchor = additionalURL.split("#");
        var TheParams = tmpAnchor[0];
            TheAnchor = tmpAnchor[1];
        if(TheAnchor)
            additionalURL = TheParams;

        tempArray = additionalURL.split("&");

        for (var i=0; i<tempArray.length; i++)
        {
            if(tempArray[i].split('=')[0] != param)
            {
                newAdditionalURL += temp + tempArray[i];
                temp = "&";
            }
        }
    }
    else
    {
        var tmpAnchor = baseURL.split("#");
        var TheParams = tmpAnchor[0];
            TheAnchor  = tmpAnchor[1];

        if(TheParams)
            baseURL = TheParams;
    }

    if(TheAnchor)
        paramVal += "#" + TheAnchor;

    var rows_txt = temp + "" + param + "=" + paramVal;
    return baseURL + "?" + newAdditionalURL + rows_txt;
}

//http://stackoverflow.com/a/1917916
function insertParam(key, value) {
	var key = escape(key),
		value = escape(value),
		kvp = document.location.search.substr(1).split('&');

	if (kvp === '')
		document.location.search = '?' + key + '=' + value;
	else {
        var i = kvp.length,
			x;

        while (i--) {
			x = kvp[i].split('=');

			if (x[0] == key) {
				x[1] = value;
				kvp[i] = x.join('=');
				break;
			}
		}

		if (i < 0)
			kvp[kvp.length] = [key, value].join('=');

		//this will reload the page, it's likely better to store this until finished
		document.location.search = kvp.join('&');
	}
}

function utf8_encode(s) {
  return unescape(encodeURIComponent(s));
}

function utf8_decode(s) {
  return decodeURIComponent(escape(s));
}

function form2args (elements) {
	var args = '';
	for(var i=0, l=elements.length; i<l; i++) {
		var el=elements[i];
		if (el.tagName.toLowerCase() == 'select')
			args+= '&'+el.name+'=' + el.options[el.selectedIndex].value;
		else if (el.tagName.toLowerCase()=='input' && el.type=='checkbox')
			args+= '&'+el.name+'=' + ((el.checked)?el.value:false);
		else if(el.tagName.toLowerCase()=='textarea' || (el.tagName.toLowerCase()=='input' && el.type!='file' && el.type!='submit' && el.type!='button'))
			args+= '&'+el.name+'=' + el.value;
	}
	return '?'+args.substring(1);
}

function getUrlId() {
	var url = window.location.pathname;
	return url.substring(url.lastIndexOf('/')+1);
}

function goTo (url, hash) {
	if(hash && !url)
		window.location.hash=hash;
	else
		window.location.href=url+((hash)?'#'+hash:'');
}

function Timer(callback, delay) {
    var timerId, start, remaining = delay;

    this.pause = function() {
        window.clearTimeout(timerId);
        remaining -= new Date() - start;
    };

    this.resume = function() {
        start = new Date();
        timerId = window.setTimeout(callback, remaining);
    };

    this.resume();
}

function Note ( msg, duration, specialClass ) {
	specialClass = typeof specialClass !== 'undefined' ? specialClass : '';
	var t=this;
	t.el = document.createElement('p');
	t.el.className = 'note '+specialClass;
	t.el.innerHTML = msg;
	if(!isNaN(duration)) {
		var timer = new Timer(function(){t.destroy()}, duration);
		t.el.addEventListener("mouseover", function(){timer.pause()}, false);
		t.el.addEventListener("mouseout", function(){timer.resume()}, false);
	}
	document.body.appendChild( t.el );
}
Note.prototype.destroy = function () {
	document.body.removeChild(this.el)
};