/**
 *	@author Sanjar Akhmedov (eSector solutions)
 *	@since 20.10.2004
 */

var wchome = '/openconstructor';
var agt=navigator.userAgent.toLowerCase();
var isIE=(agt.indexOf("msie")!=-1)&&!(agt.indexOf("opera")!=-1)&&!(agt.indexOf("mac")!=-1);
var isMoz=(agt.indexOf("gecko")!=-1)&&!(agt.indexOf("safari")!=-1);
var main = null;

initMain();

function newXmlHttp() {
	var result = null;
	if(isIE) {
		result = new ActiveXObject("Microsoft.XMLHTTP");
	} else if(isMoz) {
		result = new XMLHttpRequest();
	}
	return result;
}

function initMain() {
	main = window;
	try {
		while(main.opener && !main.opener.closed && (main.opener != main) && main.opener.setChild)
			main = main.opener;
		if(!main.top.closed);
			main = main.top;
	} catch(e){
	}
}

function setChild(id, win) {
	if(!window._childs)
		window._childs = new Array();
	window._childs[id] = win;
}

function ImagePreloader() {
	this.images=new Array();
}

ImagePreloader.prototype.add = function add(uri) {
	this.images[this.images.length] = new Image;
	this.images[this.images.length - 1].src = uri;
}

function pushMethod(object,method) {
	var pushed = 0;
	while(object["pushed_"+pushed]) pushed++;
	pushed="pushed_"+pushed;
	object[pushed]=method;
	return pushed;
}

function getTop(el) {
	if(!el || el.tagName == 'BODY')
		return 0;
	else
		return el.offsetTop + getTop(el.offsetParent, true);
}

function getLeft(el, inner) {
	if(!el || el.tagName == 'BODY')
		return 0;
	else 
		return el.offsetLeft + getLeft(el.offsetParent, true);
}

function setButtonState(btn, state){
	var tag = btn.tagName.toLowerCase();
	switch(tag){
		case 'input':
			setPlainButtonState(btn, state);
		break;
		case 'a':
			setHrefButtonState(btn, state);
		break;
		case 'img':
			setImgButtonState(btn, state);
		break;
	}
}

function setPlainButtonState(btn, enable) {
	if(enable) {
		btn.disabled = false;
	} else {
		btn.disabled = true;
	}
}

function setHrefButtonState(btn, enable) {
	var img = btn.firstChild;
	if(enable) {
		btn.style.cursor = "pointer";
		img.style.filter = "";
	} else {
		btn.style.cursor = "default";
		img.style.filter = "progId:DXImageTransform.Microsoft.BasicImage(GrayScale=1, Opacity=0.65)";
	}
}

function setImgButtonState(btn, enable) {
	if(enable) {
		btn.style.cursor = "pointer";
		btn.style.filter = "";
	} else {
		btn.style.cursor = "default";
		btn.style.filter = "progId:DXImageTransform.Microsoft.BasicImage(GrayScale=1, Opacity=0.3)";
	}
}

function openWindow(uri, x, y, id){
	if(!id)
		id = getIdByUri(uri);
	if(id.substr(0,1) != '_') {
		id = location.host.replace(/[^\w0-9_\$]/g, "_") + "_" + id;
		try {
			main._childs[id].focus();
			return;
		} catch(e) {}
	}
	var height = y > 0 ? ", height=" + y : "";
	var width = x > 0 ? ", width=" + x : "";
	var win = window.open(uri, id, "resizable=yes, scrollbars=yes, status=yes" + width + height);
	try {
		main.setChild(id, win);
	} catch(e) {
		initMain();
		main.setChild(id, win);
	}
}

function openModal(uri, width, height, args) {
	var wArgs = "center=yes; help=no; resizable=yes; status=no;"
		+ (args ? args : "")
		+ (width ? "dialogWidth:" + width + "px;" : "")
		+ (height ? "dialogHeight:" + height + "px;" :"")
	;
	return window.showModalDialog(uri, window.self, wArgs);
}

function setCookie(name, value, path){
	document.cookie = name + '=' + value + '; path=' + path;
}

function addListener(el, event, listener) {
	if(el[event]) {
		var old = el[event];
		el[event] = function() {
			old();
			listener();
		}
	} else
		el[event] = listener;
}

function getIdByUri(uri) {
	var docId = parseInt(uri.replace(/^.*(?:\?|&)id=(\d+)(?:&.*$|$)/gi,"$1"));
	var dsId = parseInt(uri.replace(/^.*(?:\?|&)ds_id=(\d+)(?:&.*$|$)/gi,"$1"));
	if(!isNaN(docId) && !isNaN(dsId))
		return "doc_" + dsId + "_" + docId;
	return '_blank';
}

function dump(obj, ret){
	var result = "", s= "" ;
	for(prop in obj){
		try {
			s=obj[prop];
		} catch(RuntimeException){
			s="Permission Denied";
		}
		result+=prop+" : "+s+(ret?"\n":"\n");
	}
	if(ret)
		return result;
	else
		showinnewwindow(result);
}

function showinnewwindow(text){
	dumpwin=window.open('','',"resizable=yes, scrollbars=yes, status=yes, height=, width=");
	dumpwin.document.writeln('<html><head><title>Opening...</title></head><body style="padding:0;margin:0;"><textarea style="width:100%;height:100%;" wrap="off">'+text.replace(/<|>/g,function(m,o,s){return m=='<'?'&lt;':'&gt;';})+'</textarea></body></html>');
	dumpwin.document.close();
}



function CaptureEvents(events, exclude, func) {
	this.instances[this.instances.length] = this;
	this.events = events.split(",");
	for(var i = 0; i < this.events.length; i++)
		if(this.events[i].indexOf("on") == 0) {
			if(isMoz)
				this.events[i] = this.events[i].substr(2);
		} else if(isIE)
			this.events[i] = "on" + this.events[i];
	this.exclude = exclude instanceof Array ? exclude : new Array(exclude);
	this.func = func;
	this.handler = null;
}

CaptureEvents.prototype = {
	instances: new Array(),
	
	enable :
		function() {
			if(this.handler == null)
				this.createHandler();
			for(var i = 0; i < this.events.length; i++)
				if(isIE)
					document.attachEvent(this.events[i], this.handler);
				else if(isMoz)
					document.addEventListener(this.events[i], this.handler, false);
		},
	
	disable :
		function() {
			for(var i = 0; i < this.events.length; i++)
				if(isIE)
					document.detachEvent(this.events[i], this.handler);
				else if(isMoz)
					document.removeEventListener(this.events[i], this.handler, false);
		},
	
	createHandler :
		function() {
			if(this.handler == null) {
				var self = this;
				var func = function(e) {
					var target = isIE ? e.srcElement : e.target;
					while(target != document) {
						for(var i = 0; i < self.exclude.length; i++)
							if(target == self.exclude[i])
								return;
						target = target.parentNode;
					}
					self.func();
				}
				this.handler = isIE ? function() {func(event);} : func;
			}
		}
}




function KeyBinder(target, win) {
	this.target = target;
	this.func = new Array();
	this.func[0] = new Array();
	this.func[1] = new Array();
	this.func[2] = new Array();
	this.func[3] = new Array();
	this.addTarget(target, win);
}

KeyBinder.prototype.addTarget = function(target, win) {
	var self = this;
	if(!win)
		win = window;
	if(isIE)
		target.onkeydown = function() {
			if(self.func[t = win.event.ctrlKey << 1 | win.event.altKey][win.event.keyCode]){
				self.func[t][win.event.keyCode]();
				return false;
			}
		}
	else
		target.onkeydown = function(event) {
			if(self.func[t = event.ctrlKey << 1 | event.altKey][event.keyCode]){
				self.func[t][event.keyCode]();
				return false;
			}
		}
}

KeyBinder.prototype.addShortcut = function(shortcut, func) {
	var keys = shortcut.toUpperCase().split('+');
	var ctrl = alt = false;
	for(var i=0; i<keys.length; i++)
		if(keys[i] == "CTRL")
			ctrl = true;
		else if(keys[i] == "ALT")
			alt = true;
		else
			key = keys[i].charCodeAt(0);
	this.func[ctrl << 1 | alt][key] = func;
}

function $(id) {
	return document.getElementById(id);
}