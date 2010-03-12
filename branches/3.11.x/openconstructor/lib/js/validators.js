/**
 *	@author Sanjar Akhmedov (eSector solutions)
 *	@since 20.10.2004
 */


function Validator() {
	this.oldState=null;
	this.infoNode=null;
	this.targetData=null;
	this.nextValidator=null;
	this.listeners=new Array();
}

/**
 * BUGS:
 *	-	@param target and @param error must not be same elements
 */

Validator.prototype.bind = function(target, error) {
	if(error)
		this.infoNode=error;

	if(target.targetData) {
		// passed target was Validator
		target.nextValidator=this; // chain this validotor
		this.targetData=target.targetData;
		return;
	}
	
	this.targetData=target;
	
	var self = this, notifyer;
	
	if(target.onpropertychange){
		var old = pushMethod(target,target.onpropertychange);
		notifyer = function() {
			self.targetChanged();
			target[old]();
		}
	} else
		notifyer = function() {
			self.targetChanged();
		}
	
	if(isIE) {
		target.onpropertychange = notifyer;
		return;
	}
	
	if(target.tagName.toUpperCase()=='INPUT' && target.type.toLowerCase()=='hidden')
		target.setValue = function(value) {
			target.value=value;
			notifyer();
		};
	else if(target.tagName.toUpperCase()=='SELECT')
		target.addEventListener("change",notifyer,false);
	else
		target.addEventListener("input",notifyer,false);
}

Validator.prototype.isValidNext = function() {
	if(!this.nextValidator)
		return true;
	return this.nextValidator.isValid();
}

Validator.prototype.addListener = function(func) {
	this.listeners[this.listeners.length]=func;
}

Validator.prototype.getTargetValue = function() {
	// TODO: checkboxes and selects can have multiple values
	switch(this.targetData.nodeName.toUpperCase()){
		case 'TEXTAREA':
		case 'BUTTON':
			return this.targetData.value;
		break;
		
		case 'SELECT':
			return this.targetData.selectedIndex >= 0 ? this.targetData.options.item(this.targetData.selectedIndex).value : '';
		break;
		
		case 'INPUT':
		break;
		
		default:
		return '';
	}
	
	switch(this.targetData.type.toLowerCase()){
		case 'checkbox':
			return this.targetData.checked ? this.targetData.value : '';
		break;
		
		default:
			return this.targetData.value;
		return this.targetData.value;
	}
}

Validator.prototype.targetChanged = function(quiet) {
	var stateChanged = this.oldState != this.isValid(true);
	this.oldState = stateChanged != this.oldState;
	if(stateChanged){
		this.isValid(quiet);
		for(var i=0;i<this.listeners.length;i++)
			this.listeners[i]();
	}
}

Validator.prototype.showInfo = function(isValid) {
	if(this.infoNode) {
		if(this.infoNode.getAttribute("validclass") == undefined)
			this.infoNode.setAttribute("validclass", this.infoNode.className);
		this.infoNode.className = this.infoNode.getAttribute(isValid ? 'validclass' : 'errorclass');
	}
}




function NumberValidator(target, error, minbound, maxbound) {
	this.minbound=-Number.MAX_VALUE;
	this.maxbound=Number.MAX_VALUE;
	this.bind(target, error);
	if(minbound != undefined)
		this.minbound=minbound;
	if(maxbound != undefined)
		this.maxbound=maxbound;
	this.oldState = this.isValid(true);
}

NumberValidator.prototype = new Validator();

NumberValidator.prototype.isValid = function(quiet) {
	var result = this.getTargetValue();
	result = new Number(result);
	result = !isNaN(result) && (result>=this.minbound) && (result<=this.maxbound);
	
	if(!quiet)
		this.showInfo(result);
	
	return result ? this.isValidNext() : false;
}




function RegExpValidator(target, error, regexp) {
	this.bind(target, error);
	if(!(regexp instanceof RegExp))
		this.regexp = new RegExp(regexp);
	else
		this.regexp = regexp;
	this.oldState = this.isValid(true);
}

RegExpValidator.prototype = new Validator();

RegExpValidator.prototype.isValid = function(quiet) {
	var result = this.getTargetValue().search(this.regexp) != -1;
	if(!quiet)
		this.showInfo(result);
	
	return result ? this.isValidNext() : false;
}



// TODO: DateTime
// TODO: Date
// TODO: Time
// TODO: NotEmpty
// TODO: Length
// TODO: Email
