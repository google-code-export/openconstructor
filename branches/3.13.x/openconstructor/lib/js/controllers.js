/**
 *	@author Sanjar Akhmedov (eSector solutions)
 *	@since 20.10.2004
 */


function IsReadyController(button, error){
	this.isInitialised=false;
	this.isReady=true;
	this.infoNode=error;
	this.targets=new Array(); // Validators
	this.dependents=new Array();
	this.addDependent(button);
}

IsReadyController.prototype.init = function() {
	this.isInitialised=true;
	this.updateState();
}

IsReadyController.prototype.addDependent = function(button) {
	this.dependents[this.dependents.length]=button;
	this.setButtonState(button, this.isReady);
	if(isMoz){
		var old = pushMethod(button, button.onclick), self = this;
		button.onclick= function(){
			self.updateState(true);
			if(!self.isReady)
				return false;
			if(button[old])
				return button[old]();
			return true;
		}
	}
}

IsReadyController.prototype.addTarget = function(target) {
	if(!target.targetData) // passed target wasn't Validator
		return;
	this.targets[this.targets.length]=target;
	
	var self = this;
	target.addListener(function(){self.updateState()});
	
}

IsReadyController.prototype.updateState = function() {
	var newState=true;
	for(var i=0;i<this.targets.length;i++)
		if(this.targets[i]&&!this.targets[i].isValid(true)){
			newState=false;
			break;
		}
	if(newState!=this.isReady){
		for(i=0;i<this.dependents.length;i++)
			this.setButtonState(this.dependents[i],newState);
		this.showInfo(newState);
	}
	this.isReady=newState;
}

IsReadyController.prototype.setButtonState = function(button,enable) {
	if(enable) {
		button.disabled=false;
	} else {
		button.disabled=true;
	}
}

IsReadyController.prototype.showInfo = function(isReady) {
	// TODO: as in Validators
	if(this.infoNode)
		this.infoNode.style.display = isReady ? 'none' : 'inline';
}




function AltElementsController() {
	this.elements = new Array();
	this.names = new Array();
	this.activeindex = -1;
}

AltElementsController.prototype.add = function(element, name) {
	if(this.elements.length < 1 && element.parentNode)
		element.parentNode.style.height = 0;
	this.elements[this.elements.length] = element;
	this.names[this.names.length] = name;
	this.setParentHeight();
	if(this.activeindex < 0)
		this.switchTo(this.elements.length - 1);
	else
		this.hide(this.elements[this.elements.length - 1]);
}

AltElementsController.prototype.remove = function(index) {
	return;
	if(this.elements[index])
		this.elements.splice(index,1);
}

AltElementsController.prototype.switchTo = function(index) {
	if(!this.elements[index]) return;
	this.setParentHeight();
	if(this.elements[this.activeindex])
		this.hide(this.elements[this.activeindex]);
	this.activeindex = index;
	this.show(this.elements[index]);
}

AltElementsController.prototype.show = function(element) {
	element.style.display = "";
}

AltElementsController.prototype.hide = function(element) {
	element.style.display = "none";
}

AltElementsController.prototype.setParentHeight = function() {
	var maxHeight = parseInt(this.elements[0].parentNode.style.height);
	for(var i = 0; i<this.elements.length; i++)
		if(this.elements[i].offsetHeight > maxHeight)
			maxHeight = this.elements[i].offsetHeight;
	this.elements[0].parentNode.style.height = maxHeight;
}



function AltWYSIWYGsController() {}

AltWYSIWYGsController.prototype = new AltElementsController();

AltWYSIWYGsController.prototype.show = function(wysiwyg) {
	wysiwyg.iframe.style.display = "";
	try{
		wysiwyg.bindedDocument.designMode = "on";
	}
	catch(e){}
	wysiwyg.controller.bind(wysiwyg);
}

AltWYSIWYGsController.prototype.hide = function(wysiwyg) {
	wysiwyg.iframe.style.display = "none";
	wysiwyg.controller.bind(null);
}

AltWYSIWYGsController.prototype.setParentHeight = function() {}





function DocumentsController() {
	this.inputs = new Array();
	this.headers = new Array();
	this.names = new Array();
	this.widget = null;
}

DocumentsController.prototype.bind = function(widget) {
	this.widget = widget;
}

DocumentsController.prototype.add = function(input, name) {
	this.inputs[this.inputs.length] = input;
	this.headers[this.headers.length] = input.getAttribute("header");
	this.names[this.names.length] = name;
}

DocumentsController.prototype.editEnabled = function(index) {
	return this.inputs[index].value > 0;
}

DocumentsController.prototype.createEnabled = function(index) {
	return !(this.inputs[index].value > 0) && this.inputs[index].getAttribute("hybrid") > 0;
}

DocumentsController.prototype.removeEnabled = function(index) {
	return (this.inputs[index].value > 0);// && (this.inputs[index].getAttribute("isown") != 1);
}

DocumentsController.prototype.selectEnabled = function(index) {
	return this.inputs[index].getAttribute("isown") != 1;
}

DocumentsController.prototype.updateDocument = function(index, value, header) {
	this.inputs[index].value = value;
	this.headers[index] = header;
	if(this.widget)
		this.widget.refresh();
}

DocumentsController.prototype.editDocument = function(index) {
	if(!this.editEnabled(index))
		return false;
	var id = this.inputs[index].value;
	var dsId = this.inputs[index].getAttribute("fromds");
	var url = wchome + "/data/" + this.inputs[index].getAttribute("doctype") + "/edit.php?id=" + id + "&ds_id=" + dsId;
	openWindow(url, 800, 500);
}

DocumentsController.prototype.createDocument = function(index) {
	if(!this.createEnabled(index))
		return false;
	var callback = this.createCallback(index);
	var url = wchome + "/data/" + this.inputs[index].getAttribute("doctype") + "/edit.php?id=new&ds_id=" + this.inputs[index].getAttribute("fromds") + "&hybridid=" + this.inputs[index].getAttribute("hybrid") + "&fieldid=" + this.inputs[index].getAttribute("fieldid") + "&callback=" + callback;
	openWindow(url, 800, 500);
}

DocumentsController.prototype.removeDocument = function(index) {
	if(!this.removeEnabled(index))
		return false;
	this.inputs[index].value = "-1";
	this.headers[index] = "";
	if(this.widget)
		this.widget.refresh();
}

DocumentsController.prototype.selectDocument = function(index) {
	if(!this.selectEnabled(index))
		return;
	var input = this.inputs[index];
	var doc = openModal("selectdocument.php?doctype=" + input.getAttribute("doctype") + "&type=single&ds_id=" + input.getAttribute("fromds"), 440, 500, "scroll=no;");
	if(doc.length != 1)
		return;
	this.updateDocument(index, doc[0][0], doc[0][1]);
}

DocumentsController.prototype.createCallback = function(index) {
	var prefix = "callback", ct = 0;
	while(window[prefix + ct])
		ct++;
	var name = prefix + ct, self = this, fieldid = this.inputs[index].getAttribute("fieldid");
	window[name] = function(id, header, field) {
		if(field != fieldid) return;
		self.updateDocument(index, id, header);
	}
	return name;
}


function ArrayController(select) {
	this.select = select;
	this.widget = null;
	select.multiple = true;
	for(var i = 0; i<select.options.length; i++)
		select.options[i].selected = true;
}

ArrayController.prototype.bind = function(widget) {
	this.widget = widget;
}

ArrayController.prototype.addEnabled = function() {
	return this.select.getAttribute("isown") != 1;
}

ArrayController.prototype.createEnabled = function() {
	return this.select.getAttribute("hybrid") > 0;
}

ArrayController.prototype.add = function() {
	if(!this.addEnabled())
		return;
	var docs = openModal(wchome + "/data/hybrid/selectdocument.php?doctype=" + this.select.getAttribute("doctype") + "&type=multiple&ds_id=" + this.select.getAttribute("fromds"), 440, 500, "scroll=no;");
	if(docs.length < 1)
		return;
	for(var i=0; i<docs.length; i++)
		this.addItem(docs[i][0], docs[i][1]);
}

ArrayController.prototype.addItem = function(id, header) {
	var oldlength = this.select.options.length;
	var index = this.getIndex(id);
	this.select.options[index].innerHTML = header;
	this.select.options[index].setAttribute("selected", true);
	if(!this.widget)
		return;
	if(index == oldlength)
		this.widget.addDocument(index);
	else
		this.widget.restoreDocument(index);
}

ArrayController.prototype.getIndex = function(value) {
	for(var i=0; i<this.select.options.length; i++)
		if(this.select.options[i].value == value)
			return i;
	var o = new Option(value);
	o.value = value;
	this.select.appendChild(o);
	return this.select.options.length - 1;
}

ArrayController.prototype.createAndAdd = function() {
	if(!this.createEnabled())
		return;
	var callback = this.createCallback();
	var url = wchome + "/data/" + this.select.getAttribute("doctype") + "/edit.php?id=new&ds_id=" + this.select.getAttribute("fromds") + "&hybridid=" + this.select.getAttribute("hybrid") + "&fieldid=" + this.select.getAttribute("fieldid") + "&callback=" + callback;
	openWindow(url, 800, 500);
}

ArrayController.prototype.remove = function(index) {
	this.select.options[index].selected = false;
}

ArrayController.prototype.createCallback = function() {
	var prefix = "callback", ct = 0;
	while(window[prefix + ct])
		ct++;
	var name = prefix + ct, self = this, fieldid = this.select.getAttribute("fieldid");
	window[name] = function(id, header, field) {
		if(field != fieldid) return;
		self.addItem(id, header);
	}
	return name;
}




