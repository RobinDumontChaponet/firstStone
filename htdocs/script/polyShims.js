// JavaScript Document


// ECMA-262, Edition 5, 15.4.4.18
// Référence: http://es5.github.io/#x15.4.4.18
if (!Array.prototype.forEach) {

  Array.prototype.forEach = function(callback, thisArg) {

    var T, k;

    if (this == null) {
      throw new TypeError(' this vaut null ou n est pas défini');
    }

    // 1. Soit O le résultat de l'appel à ToObject
    //    auquel on a passé |this| en argument.
    var O = Object(this);

    // 2. Soit lenValue le résultat de l'appel de la méthode
    //    interne Get sur O avec l'argument "length".
    // 3. Soit len la valeur ToUint32(lenValue).
    var len = O.length >>> 0;

    // 4. Si IsCallable(callback) est false, on lève une TypeError.
    // Voir : http://es5.github.com/#x9.11
    if (typeof callback !== "function") {
      throw new TypeError(callback + ' n est pas une fonction');
    }

    // 5. Si thisArg a été fourni, soit T ce thisArg ;
    //    sinon soit T égal à undefined.
    if (arguments.length > 1) {
      T = thisArg;
    }

    // 6. Soit k égal à 0
    k = 0;

    // 7. On répète tant que k < len
    while (k < len) {

      var kValue;

      // a. Soit Pk égal ToString(k).
      //   (implicite pour l'opérande gauche de in)
      // b. Soit kPresent le résultat de l'appel de la
      //    méthode interne HasProperty de O avec l'argument Pk.
      //    Cette étape peut être combinée avec c
      // c. Si kPresent vaut true, alors
      if (k in O) {

        // i. Soit kValue le résultat de l'appel de la
        //    méthode interne Get de O avec l'argument Pk.
        kValue = O[k];

        // ii. On appelle la méthode interne Call de callback
        //     avec T comme valeur this et la liste des arguments
        //     qui contient kValue, k, et O.
        callback.call(T, kValue, k, O);
      }
      // d. On augmente k de 1.
      k++;
    }
    // 8. on renvoie undefined
  };
}


/*
 * classList.js: Cross-browser full element.classList implementation.
 * 2014-01-31
 *
 * By Eli Grey, http://eligrey.com
 * Public Domain.
 * NO WARRANTY EXPRESSED OR IMPLIED. USE AT YOUR OWN RISK.
 */
/*global self, document, DOMException */
/*! @source http://purl.eligrey.com/github/classList.js/blob/master/classList.js*/
if ("document" in self && !("classList" in document.createElement("_"))) {
(function (view) {
"use strict";
if (!('Element' in view)) return;
var classListProp = "classList"
	, protoProp = "prototype"
	, elemCtrProto = view.Element[protoProp]
	, objCtr = Object
	, strTrim = String[protoProp].trim || function () {
		return this.replace(/^\s+|\s+$/g, "");
	}, arrIndexOf = Array[protoProp].indexOf || function (item) {
		var i = 0, len = this.length;
		for (; i < len; i++) {
			if (i in this && this[i] === item) {
				return i;
			}
		}
		return -1;
	}
	// Vendors: please allow content code to instantiate DOMExceptions
	, DOMEx = function (type, message) {
		this.name = type;
		this.code = DOMException[type];
		this.message = message;
	}, checkTokenAndGetIndex = function (classList, token) {
		if (token === "") {
			throw new DOMEx(
				  "SYNTAX_ERR"
				, "An invalid or illegal string was specified"
			);
		}
		if (/\s/.test(token)) {
			throw new DOMEx(
				  "INVALID_CHARACTER_ERR", "String contains an invalid character"
			);
		}
		return arrIndexOf.call(classList, token);
	}, ClassList = function (elem) {
		var trimmedClasses = strTrim.call(elem.getAttribute("class") || "")
			, classes = trimmedClasses ? trimmedClasses.split(/\s+/) : []
			, i = 0
			, len = classes.length;
		for (; i < len; i++) {
			this.push(classes[i]);
		}
		this._updateClassName = function () {
			elem.setAttribute("class", this.toString());
		};
	}, classListProto = ClassList[protoProp] = []
	, classListGetter = function () {
		return new ClassList(this);
	};
// Most DOMException implementations don't allow calling DOMException's toString()
// on non-DOMExceptions. Error's toString() is sufficient here.
DOMEx[protoProp] = Error[protoProp];
classListProto.item = function (i) {
	return this[i] || null;
};
classListProto.contains = function (token) {
	token += "";
	return checkTokenAndGetIndex(this, token) !== -1;
};
classListProto.add = function () {
	var tokens = arguments
		, i = 0
		, l = tokens.length
		, token
		, updated = false;
	do {
		token = tokens[i] + "";
		if (checkTokenAndGetIndex(this, token) === -1) {
			this.push(token);
			updated = true;
		}
	}
	while (++i < l);
	if (updated) {
		this._updateClassName();
	}
};
classListProto.remove = function () {
	var tokens = arguments
		, i = 0
		, l = tokens.length
		, token
		, updated = false;
	do {
		token = tokens[i] + "";
		var index = checkTokenAndGetIndex(this, token);
		if (index !== -1) {
			this.splice(index, 1);
			updated = true;
		}
	}
	while (++i < l);
	if (updated) {
		this._updateClassName();
	}
};
classListProto.toggle = function (token, force) {
	token += "";
	var result = this.contains(token)
		, method = result ?
			force !== true && "remove"
		:
			force !== false && "add";

	if (method) {
		this[method](token);
	}
	return !result;
};
classListProto.toString = function () {
	return this.join(" ");
};
if (objCtr.defineProperty) {
	var classListPropDesc = {
		  get: classListGetter
		, enumerable: true
		, configurable: true
	};
	try {
		objCtr.defineProperty(elemCtrProto, classListProp, classListPropDesc);
	} catch (ex) { // IE 8 doesn't support enumerable:true
		if (ex.number === -0x7FF5EC54) {
			classListPropDesc.enumerable = false;
			objCtr.defineProperty(elemCtrProto, classListProp, classListPropDesc);
		}
	}
} else if (objCtr[protoProp].__defineGetter__) {
	elemCtrProto.__defineGetter__(classListProp, classListGetter);
}
}(self));
}

if(document.getElementsByClassName==undefined){
	document.getElementsByClassName = function(className, parentElement) {
	if (Prototype.BrowserFeatures.XPath) {
		var q = ".//*[contains(concat(' ', @class, ' '), ' " + className + " ')]";
		return document._getElementsByXPath(q, parentElement);
	} else {
		var children = ($(parentElement) || document.body).getElementsByTagName('*');
		var elements = [], child;
		for (var i = 0, length = children.length; i < length; i++) {
			child = children[i];
			if (Element.hasClassName(child, className))
				elements.push(Element.extend(child));
			}
			return elements;
		}
	};
}

function getXMLHttpRequest() {
	if (window.ActiveXObject) {
        try {
            xhr = new ActiveXObject("Microsoft.XMLHTTP");
        } catch (e) {
            xhr = new ActiveXObject("Msxml2.XMLHTTP");
        }
    } else if (window.XMLHttpRequest) {
		return new XMLHttpRequest();
	}
	try {
		return new ActiveXObject('MSXML2.XMLHTTP.6.0');
	} catch (e) {
		try {
			return new ActiveXObject('MSXML2.XMLHTTP.3.0');
		} catch (e) {
			return null;
		}
	}
}


/* Placeholders.js v3.0.2 */
(function(t){"use strict";function e(t,e,r){return t.addEventListener?t.addEventListener(e,r,!1):t.attachEvent?t.attachEvent("on"+e,r):void 0}function r(t,e){var r,n;for(r=0,n=t.length;n>r;r++)if(t[r]===e)return!0;return!1}function n(t,e){var r;t.createTextRange?(r=t.createTextRange(),r.move("character",e),r.select()):t.selectionStart&&(t.focus(),t.setSelectionRange(e,e))}function a(t,e){try{return t.type=e,!0}catch(r){return!1}}t.Placeholders={Utils:{addEventListener:e,inArray:r,moveCaret:n,changeType:a}}})(this),function(t){"use strict";function e(){}function r(){try{return document.activeElement}catch(t){}}function n(t,e){var r,n,a=!!e&&t.value!==e,u=t.value===t.getAttribute(V);return(a||u)&&"true"===t.getAttribute(D)?(t.removeAttribute(D),t.value=t.value.replace(t.getAttribute(V),""),t.className=t.className.replace(R,""),n=t.getAttribute(F),parseInt(n,10)>=0&&(t.setAttribute("maxLength",n),t.removeAttribute(F)),r=t.getAttribute(P),r&&(t.type=r),!0):!1}function a(t){var e,r,n=t.getAttribute(V);return""===t.value&&n?(t.setAttribute(D,"true"),t.value=n,t.className+=" "+I,r=t.getAttribute(F),r||(t.setAttribute(F,t.maxLength),t.removeAttribute("maxLength")),e=t.getAttribute(P),e?t.type="text":"password"===t.type&&M.changeType(t,"text")&&t.setAttribute(P,"password"),!0):!1}function u(t,e){var r,n,a,u,i,l,o;if(t&&t.getAttribute(V))e(t);else for(a=t?t.getElementsByTagName("input"):b,u=t?t.getElementsByTagName("textarea"):f,r=a?a.length:0,n=u?u.length:0,o=0,l=r+n;l>o;o++)i=r>o?a[o]:u[o-r],e(i)}function i(t){u(t,n)}function l(t){u(t,a)}function o(t){return function(){m&&t.value===t.getAttribute(V)&&"true"===t.getAttribute(D)?M.moveCaret(t,0):n(t)}}function c(t){return function(){a(t)}}function s(t){return function(e){return A=t.value,"true"===t.getAttribute(D)&&A===t.getAttribute(V)&&M.inArray(C,e.keyCode)?(e.preventDefault&&e.preventDefault(),!1):void 0}}function d(t){return function(){n(t,A),""===t.value&&(t.blur(),M.moveCaret(t,0))}}function g(t){return function(){t===r()&&t.value===t.getAttribute(V)&&"true"===t.getAttribute(D)&&M.moveCaret(t,0)}}function v(t){return function(){i(t)}}function p(t){t.form&&(T=t.form,"string"==typeof T&&(T=document.getElementById(T)),T.getAttribute(U)||(M.addEventListener(T,"submit",v(T)),T.setAttribute(U,"true"))),M.addEventListener(t,"focus",o(t)),M.addEventListener(t,"blur",c(t)),m&&(M.addEventListener(t,"keydown",s(t)),M.addEventListener(t,"keyup",d(t)),M.addEventListener(t,"click",g(t))),t.setAttribute(j,"true"),t.setAttribute(V,x),(m||t!==r())&&a(t)}var b,f,m,h,A,y,E,x,L,T,N,S,w,B=["text","search","url","tel","email","password","number","textarea"],C=[27,33,34,35,36,37,38,39,40,8,46],k="#ccc",I="placeholdersjs",R=RegExp("(?:^|\\s)"+I+"(?!\\S)"),V="data-placeholder-value",D="data-placeholder-active",P="data-placeholder-type",U="data-placeholder-submit",j="data-placeholder-bound",q="data-placeholder-focus",z="data-placeholder-live",F="data-placeholder-maxlength",G=document.createElement("input"),H=document.getElementsByTagName("head")[0],J=document.documentElement,K=t.Placeholders,M=K.Utils;if(K.nativeSupport=void 0!==G.placeholder,!K.nativeSupport){for(b=document.getElementsByTagName("input"),f=document.getElementsByTagName("textarea"),m="false"===J.getAttribute(q),h="false"!==J.getAttribute(z),y=document.createElement("style"),y.type="text/css",E=document.createTextNode("."+I+" { color:"+k+"; }"),y.styleSheet?y.styleSheet.cssText=E.nodeValue:y.appendChild(E),H.insertBefore(y,H.firstChild),w=0,S=b.length+f.length;S>w;w++)N=b.length>w?b[w]:f[w-b.length],x=N.attributes.placeholder,x&&(x=x.nodeValue,x&&M.inArray(B,N.type)&&p(N));L=setInterval(function(){for(w=0,S=b.length+f.length;S>w;w++)N=b.length>w?b[w]:f[w-b.length],x=N.attributes.placeholder,x?(x=x.nodeValue,x&&M.inArray(B,N.type)&&(N.getAttribute(j)||p(N),(x!==N.getAttribute(V)||"password"===N.type&&!N.getAttribute(P))&&("password"===N.type&&!N.getAttribute(P)&&M.changeType(N,"text")&&N.setAttribute(P,"password"),N.value===N.getAttribute(V)&&(N.value=x),N.setAttribute(V,x)))):N.getAttribute(D)&&(n(N),N.removeAttribute(V));h||clearInterval(L)},100)}M.addEventListener(t,"beforeunload",function(){K.disable()}),K.disable=K.nativeSupport?e:i,K.enable=K.nativeSupport?e:l}(this);
// Multiple file selector by Stickman -- http://www.the-stickman.com
// with thanks to: [for Safari fixes] Luis Torrefranca -- http://www.law.pitt.edu and Shawn Parker & John Pennypacker -- http://www.fuzzycoconut.com [for duplicate name bug] 'neal'
function MultiSelector( list_target, max, inputname ){this.inputname=inputname; this.list_target = list_target;this.count = 0;this.id = 0;if( max ){this.max = max;} else {this.max = -1;};this.addElement = function( element ){if( element.tagName == 'INPUT' && element.type == 'file' ){if(this.inputname!='')element.name=this.inputname; else element.name = 'file_' + this.id++;element.multi_selector = this;element.onchange = function(){ if(onchange)onchange(this);var new_element = document.createElement( 'input' );new_element.type = 'file';new_element.id = 'file';this.parentNode.insertBefore( new_element, this );this.multi_selector.addElement( new_element );this.multi_selector.addListRow( this );this.id = '';};if( this.max != -1 && this.count >= this.max ){element.disabled = true;};this.count++;this.current_element = element;} else {alert( 'Error: not a file input element' );};};this.addListRow = function( element ){var new_row = document.createElement( 'div' );var new_row_button = document.createElement( 'input' );new_row_button.type = 'button';new_row_button.value = "";new_row.element = element;new_row_button.onclick=function(){this.parentNode.element.parentNode.removeChild( this.parentNode.element );this.parentNode.parentNode.removeChild( this.parentNode );this.parentNode.element.multi_selector.count--;this.parentNode.element.multi_selector.current_element.disabled = false;return false;}; if(element.value.lastIndexOf("/")!=-1)new_row.innerHTML=element.value.substring(element.value.lastIndexOf("/")+1);else if(element.value.lastIndexOf("\\")!=-1) new_row.innerHTML=element.value.substring(element.value.lastIndexOf("\\")+1);else new_row.innerHTML=element.value;new_row.insertBefore(new_row_button,new_row.childNodes[0]);this.list_target.appendChild( new_row );};};

if(!Array.isArray) {
	Array.isArray = function(arg) {
		return Object.prototype.toString.call(arg) === '[object Array]';
	};
}

//addEventListener polyfill 1.0 / Eirik Backer / MIT Licence
(function(win, doc){
	if(win.addEventListener)return;		//No need to polyfill

	function docHijack(p){var old = doc[p];doc[p] = function(v){return addListen(old(v))}}
	function addEvent(on, fn, self){
		return (self = this).attachEvent('on' + on, function(e){
			e = e || win.event;
			e.preventDefault  = e.preventDefault  || function(){e.returnValue = false}
			e.stopPropagation = e.stopPropagation || function(){e.cancelBubble = true}
			fn.call(self, e);
		});
	}
	function addListen(obj, i){
		if(i = obj.length)while(i--)obj[i].addEventListener = addEvent;
		else obj.addEventListener = addEvent;
		return obj;
	}

	addListen([doc, win]);
	if('Element' in win)win.Element.prototype.addEventListener = addEvent;			//IE8
	else{		//IE < 8
		doc.attachEvent('onreadystatechange', function(){addListen(doc.all)});		//Make sure we also init at domReady
		docHijack('getElementsByTagName');
		docHijack('getElementById');
		docHijack('createElement');
		addListen(doc.all);
	}
})(window, document);

if(document.getElementsByClassName==undefined){
	document.getElementsByClassName = function(className, parentElement) {
	if (Prototype.BrowserFeatures.XPath) {
		var q = ".//*[contains(concat(' ', @class, ' '), ' " + className + " ')]";
		return document._getElementsByXPath(q, parentElement);
	} else {
		var children = ($(parentElement) || document.body).getElementsByTagName('*');
		var elements = [], child;
		for (var i = 0, length = children.length; i < length; i++) {
			child = children[i];
			if (Element.hasClassName(child, className))
				elements.push(Element.extend(child));
			}
			return elements;
		}
	};
}
