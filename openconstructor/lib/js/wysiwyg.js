/**
 *	@author Sanjar Akhmedov (eSector solutions)
 *	@since 20.10.2004
 */


var isWYSIWYGSupported = isIE || isMoz;


/**	Creates Mozilla and IE 5+ compatible What You See Is What You Get HTML editor
 *	
 *	@param Element	frame				IFRAME element which will become editor. It's src property must be empty.
 *	@param Element	textcontrol			<TEXTAREA> which contains HTML content to be edited
 *	@param Object	wysiwygcontroller	controller which will manipulate editor
 *	@param String	style				style definitins to inject
 *	
 *	BUGS:
 *	-	Mozillada AltWYSIWYDsController bilan ishlamedi. Agar IFRAME.style.display = "none" qilsa, mozilla birnarsalar deb yig'ledi.
 *		Qisqasi, to'g'irlaganga malades... Balki, IFRAMEni boshqa DIVga tiqib ko'rsa, ishlab ketar. Sinab ko'rgani xozir vaqtim yo'q.
 */

function WYSIWYGWidget(textcontrol, frame, wysiwygcontroller, style, srcEditor)
{
	if(!isWYSIWYGSupported) {alert("Your browser is not supported by WYSIWYG Editor");return false;}
	style = style ? style : '';
	
	this.iframe = frame;
	this.bindedDocument = frame.contentWindow.document;
	this.bindedText = this.bindedDocument.bindedText = textcontrol;
	this.bindedText.bindedDocument = this.bindedDocument;
	this.controller = wysiwygcontroller;
	this.srcEditor = srcEditor ? srcEditor : null;
	this.sourceMode = false;
	
	this.bindedDocument.designMode = "on";
	var self = this, doc = this.bindedDocument, text = this.bindedText, control = this.controller;
	control.bind(self);
	
	if(text.form){
		self.onsubmit = text.form.onsubmit;
		text.form.onsubmit = function(){
			if(self.sourceMode) {
				self.controller.bind(self);
				self.controller.editSource();
			}
			self.removeLocalhost();
			text.value = self.getContent();
			doc.designMode = "off";
			if(self.onsubmit) {
				this.onsubmit = self.onsubmit;
				return this.onsubmit();
			}
			return true;
		};
	}
	
	if(isIE){
		doc.onreadystatechange = function(){
			doc.onreadystatechange = null;
			self.setContent(textcontrol.value);
			doc.body.onfocus = function(){control.bind(self);};
			doc.body.onblur = function(){self.bindedText.value = self.getContent();};
	
			doc.lastChild.firstChild.appendChild(doc.createElement('<style>'));
			doc.styleSheets[0].cssText = style;
		}
		this.bindedText.onfocus = function() {control.bind(self);}
		if(this.srcEditor)
			this.srcEditor.onfocus = function() {control.bind(self);}
	}

	if(isMoz){
		self.setContent(textcontrol.value);
		doc.addEventListener('focus', function(){control.bind(self);}, false);
		doc.addEventListener('blur', function(){self.bindedText.value = self.getContent();}, false);

		doc.lastChild.firstChild.innerHTML += '<style>' + style + '</style>';
		this.bindedText.addEventListener('focus', function(){control.bind(self);}, false);
		if(this.srcEditor)
			this.srcEditor.addEventListener('focus', function(){control.bind(self);}, false);
	}
}

WYSIWYGWidget.prototype.removeLocalhost = function() {
	var localhost = window.location.hostname.indexOf('www.') == 0 ? window.location.hostname.substr(4) : window.location.hostname;
	var a = this.bindedDocument.links, href1, href2, el;
	for(var i = 0; i < a.length; i++) {
		el = a[i];
		href1 = el.href.toLowerCase();
		href2 = this.removeHost(href1, localhost);
		if(href1 != href2)
			el.href = href2;
	}
	var img = this.bindedDocument.images;
	for(var i = 0; i < img.length; i++) {
		el = img[i];
		el.src = this.removeHost(el.src, localhost);
	}
}
WYSIWYGWidget.prototype.removeHost = function(uri, host) {
	var u = uri.toLowerCase();
	if(u.indexOf("http://" + host) == 0 || u.indexOf("http://www." + host) == 0)
		return uri.substr(uri.indexOf('/', 7));
	return uri;
}

WYSIWYGWidget.prototype.getContent = function() {
	return this.bindedDocument.body.innerHTML;
}

WYSIWYGWidget.prototype.setContent = function(content) {
	this.bindedDocument.body.innerHTML = content;
}


function AbstractWYSIWYGController() {
	this.editor = null;
}

AbstractWYSIWYGController.prototype.bind = function(wysiwygwidget) {
	this.editor = wysiwygwidget;
	/*
	try {
		this.editor.bindedDocument.body.focus();
	} catch(e){}
	*/
}

AbstractWYSIWYGController.prototype.cut = function() {
	this.exec('cut',null,null);
}
AbstractWYSIWYGController.prototype.copy = function() {
	this.exec('copy',null,null);
}
AbstractWYSIWYGController.prototype.paste = function() {
	this.exec('paste',null,null);
}
AbstractWYSIWYGController.prototype.bold = function() {
	this.exec('bold',null,null);
}
AbstractWYSIWYGController.prototype.italic = function() {
	this.exec('italic',null,null);
}
AbstractWYSIWYGController.prototype.underline = function() {
	this.exec('underline',null,null);
}
AbstractWYSIWYGController.prototype.removeFormat = function() {
	this.exec('removeformat',null,null);
}
AbstractWYSIWYGController.prototype.justifyLeft = function() {
	this.exec('justifyleft',null,null);
}
AbstractWYSIWYGController.prototype.justifyCenter = function() {
	this.exec('justifycenter',null,null);
}
AbstractWYSIWYGController.prototype.justifyRight = function() {
	this.exec('justifyright',null,null);
}
AbstractWYSIWYGController.prototype.indent = function() {
	this.exec('indent',null,null);
}
AbstractWYSIWYGController.prototype.outdent = function() {
	this.exec('outdent',null,null);
}
AbstractWYSIWYGController.prototype.orderedList = function(){
	this.exec('insertorderedlist',null,null);
}
AbstractWYSIWYGController.prototype.unorderedList = function(){
	this.exec('insertunorderedlist',null,null);
}
AbstractWYSIWYGController.prototype.exec = function(command, arg1, arg2){
	try{
		var range = this.getRange();
		var tag = range.parentElement();
	} catch(e) {
		return;
	}
	if(!this.editor.bindedDocument.body.contains(tag))
		return;
	range.execCommand(command, arg1, arg2);
	this.editor.bindedDocument.body.focus();
}
AbstractWYSIWYGController.prototype.selectStyle = function(sel){
	try{
		var tag = (this.getRange()).parentElement();
	} catch(e) {
		return;
	}
	if(!this.editor.bindedDocument.body.contains(tag))
		sel.selectedIndex = 0;
	if(sel.selectedIndex == 0)
		return;
	var className = sel.options[sel.selectedIndex].value, id = sel.options[sel.selectedIndex].eId;
	if(className)
		tag.className = className;
	else
		tag.removeAttribute('className', 0);
	if(id)
		tag.id = id;
	else
		tag.removeAttribute('id');
}
AbstractWYSIWYGController.prototype.prepareStyles = function(sel){
	try{
		var tag = (this.getRange()).parentElement();
	} catch(e) {
		return;
	}
	if(!this.editor.bindedDocument.body.contains(tag)) {
		sel.selectedIndex = 0;
		return;
	}
	if(sel.tag != tag.tagName) {
			while(sel.options.length > 2)
				sel.removeChild(sel.options[sel.options.length - 1]);
			sel.options[1].innerHTML = tag.tagName;
			var regex = this.getSelectorRegexp(tag);
			var rules = this.getAllRules(this.editor.bindedDocument);
			for(var i = 0, m = null, opt = null; i<rules.length; i++) {
				m = rules[i].selectorText.match(regex);
				if(m && m.length == 3 && (m[1] || m[2])) {
					opt = new Option();
					opt.innerHTML = rules[i].selectorText;
					opt.value = m[1];
					opt.eId = m[2];
					sel.appendChild(opt);
				}
			}
			sel.style.width = 'auto';
			sel.tag = tag.tagName;
	}
	for(var i = 1; i < sel.options.length; i++)
		if(sel.options[i].value == tag.className) {
			if((sel.options.eId && sel.options.eId == tag.id) || !sel.options.eId) {
				sel.options.selectedIndex = i;
				break;
			}
		}
	if(i == sel.options.length)
		sel.options.selectedIndex = 0;
}
AbstractWYSIWYGController.prototype.editSource = function(){
	if(this.editor.sourceMode) {
		if(this.editor.srcEditor) {
			this.editor.setContent(this.editor.srcEditor.getSource());
			this.editor.srcEditor.style.display = "none";
			this.editor.iframe.style.display = "";
			this.editor.bindedDocument.body.focus();
		} else {
			this.editor.setContent(this.editor.bindedText.value);
			this.editor.bindedText.style.display = "none";
			this.editor.iframe.style.display = "";
			this.editor.bindedDocument.body.focus();
		}
	} else {
		if(this.editor.srcEditor) {
			this.editor.srcEditor.setSource(this.editor.getContent());
			this.editor.srcEditor.style.display = "";
			this.editor.srcEditor.focus();
		} else {
			this.editor.bindedText.value = this.editor.getContent();
			this.editor.bindedText.style.display = "";
			this.editor.bindedText.focus();
		}
		this.editor.iframe.style.display = "none";
	}
	this.editor.sourceMode = !this.editor.sourceMode;
}
AbstractWYSIWYGController.prototype.editTag = function(){
	dump(this.editor.bindedDocument);
	alert('NOT_IMPLEMETED');
}
AbstractWYSIWYGController.prototype.editTable = function(){
	alert('NOT_IMPLEMETED');
}
AbstractWYSIWYGController.prototype.getSelectorRegexp = function(tag){
	var name = tag.tagName.toLowerCase();
	return new RegExp("(?:^\\s*|\\s+)(?:(?:"+name+")?(?:\\.(\\w+))?(?:#(\\w+))?)(?::\\w+)?\\s*$","i");
}
AbstractWYSIWYGController.prototype.getAllRules = function(doc){
	var rules = new Array();
	for(var i=0; i<doc.styleSheets.length; i++)
		this.getSheetRules(doc.styleSheets[i], rules);
	return rules;
}
AbstractWYSIWYGController.prototype.getSheetRules = function(sheet, rules){
	for(var i=0; i<sheet.rules.length; i++)
		rules[rules.length] = sheet.rules[i];
	for(var i=0; i<sheet.imports.length; i++)
		this.getSheetRules(sheet.imports[i], rules);
}
AbstractWYSIWYGController.prototype.removeCSS = function(){
	try{
		var tag = (this.getRange()).parentElement();
	} catch(e) {
		return;
	}
	if(!this.editor.bindedDocument.body.contains(tag))
		return;
	this.removeElementStyles(tag);
}
AbstractWYSIWYGController.prototype.removeElementStyles = function(tag){
	if(!tag.tagName)
		return;
	if(tag.style.cssText)
		tag.style.cssText = null;
	tag.removeAttribute('className', 0);
	for(var i = 0; i < tag.childNodes.length; i++) {
		el = tag.childNodes[i];
		if(el.hasChildNodes())
			this.removeElementStyles(el);
		else if(el.tagName) {
			if(el.style.cssText)
				el.style.cssText = null;
			el.removeAttribute('className', 0);
		}
	}
}

function IEWYSIWYGController() {}

IEWYSIWYGController.prototype = new AbstractWYSIWYGController();

IEWYSIWYGController.prototype.getRange = function(){
	return this.editor.bindedDocument.selection.createRange();
}

IEWYSIWYGController.prototype.createLink = function(){
	this.exec('createlink',true);
}

IEWYSIWYGController.prototype.insertImage = function(){
	try{
		var tag = (this.getRange()).parentElement();
	} catch(e) {
		return;
	}
	if(!this.editor.bindedDocument.body.contains(tag))
		return;
	var image = openModal(wchome + "/data/hybrid/selectimage.php", 440, 500, "scroll=no;");
	if(image)
		this.getRange().execCommand('InsertImage', false, image);
}
IEWYSIWYGController.prototype.editStyle = function(btn){
	try{
		var tag = (this.getRange()).parentElement();
	} catch(e) {
		return;
	}
	if(!this.editor.bindedDocument.body.contains(tag))
		return;
	var oldBg = tag.style.backgroundColor, oldColor = tag.style.color;
	var txt = this.getTextControl(btn);
	txt.value = tag.style.cssText ? tag.style.cssText : "";
	tag.style.backgroundColor = '#0A246A';
	tag.style.color = '#fff';
	txt.ondeactivate = function() {
		txt.style.display = "none";
		tag.style.backgroundColor = oldBg;
		tag.style.color = oldColor;
		tag.style.cssText = txt.value;
	}
	txt.setActive();
}
IEWYSIWYGController.prototype.editTable = function(){
	try{
		var tag = (this.getRange()).parentElement();
	} catch(e) {
		return;
	}
	if(this.editor.bindedDocument.body.contains(tag)) {
		while(tag.tagName != "TABLE" && tag.tagName != "BODY" && tag.parentNode)
			tag = tag.parentNode;
		if(tag.tagName == "TABLE") {
			// this is for compatibility
			window.curTblOrig = tag;
			window.curTbl = tag.cloneNode(true);
			if(openModal(wchome + "/data/editor/table.php?j=" + Math.ceil((new Date()).getTime() / 1000), 700, 520)) {
				var tbl = tag.nextSibling;
				tag.removeNode(true);
				if(tbl.parentElement.tagName != 'DIV' && tbl.parentElement.style.width != '100%') {
					var div = tbl.applyElement(this.editor.bindedDocument.createElement('DIV'), "outside");
					div.style.width = "100%";
				}
			}
			window.curTbl.removeNode(true);
			window.curTbl = null;
			window.curTblOrig = null;
		}
	}
}
IEWYSIWYGController.prototype.getTextControl = function(btn){
	if(!this.styleInput) {
		var txt = document.createElement('TEXTAREA'), self = this;
		document.body.appendChild(txt);
		txt.style.position = 'absolute';
		txt.onkeypress = function() {
			if(event.keyCode == 10) {
				txt.blur();
			}
		}
		this.styleInput = txt;
	}
	this.styleInput.style.display = "";
	this.styleInput.style.width = '250px';
	this.styleInput.style.height = '150px';
	this.styleInput.style.top = getTop(btn) + btn.offsetHeight;
	this.styleInput.style.left = getLeft(btn) - this.styleInput.offsetWidth + btn.offsetWidth;
	return this.styleInput;
}

function MozillaWYSIWYGController() {}

MozillaWYSIWYGController.prototype = new AbstractWYSIWYGController();

MozillaWYSIWYGController.prototype.getRange = function(){
	return this.editor.bindedDocument;
}

MozillaWYSIWYGController.prototype.createLink = function(){
	this.exec('createlink',false,prompt("URL:","http://"));
}

MozillaWYSIWYGController.prototype.insertImage = function(){
	this.exec('insertimage',false,prompt("URL:","http://"));
}

function WYSIWYGController() {}

WYSIWYGController.prototype = isIE ? new IEWYSIWYGController() : new MozillaWYSIWYGController();
