/**
 *	@author Sanjar Akhmedov (eSector solutions)
 *	@since 20.10.2004
 */


var widgetUtils = new WidgetUtils();


/**	Creates calendar style date chooser
 *	
 *	@param Element	input		<input type="text"/> which helds date value
 *	@param Element	button		element which activates calendar, usually <input type="button"/>
 *	@param Integer	from		starting year for calendar
 *	@param Integer	to			ending year
 *	@param String	className	CSS class of DIV which will contain created calendar
 *	@param String	language	interface language
 */

function CalendarWidget(input, button, from, to, className, language) {
	this.table = this.createTable(className);
	this.today = new Date();
	this.button = button;
	this.target = input;
	this.text = null;
	this.from = from ? from : today.getUTCFullYear();
	this.to = to ? to : this.from;
	this.setContent(language ? language : '');
	this.hidden = true;
	this.regexp = [/^\s*(\d{1,2})\s+([a-zа-я?]+)\s+(\d{4})\s*/i,/^\s*(\d{1,2})\s*[\/\.]\s*(\d{1,2})\s*[\/\.]\s*(\d{4})\s*/i];
	this.setDate();
	this.setTargetValue();
	var self = this;
	button.onclick = function() {self.pickUp();};
	if(input.form) {
		self.onsubmit = input.form.onsubmit;
		input.form.onsubmit= function() {
			self.setDate();
			self.setTargetValue(true);
			if(self.onsubmit) {
				this.onsubmit = self.onsubmit;
				return this.onsubmit();
			}
			return true;
		}
	}
	this.docOnclick = new CaptureEvents("onclick,onkeypress", [this.table, this.button], function() {self.hide();});
	this.target.getValue = function() {return self.getValue(true);};
	this.instances[this.instances.length] = this;
}

CalendarWidget.prototype.instances = new Array();

CalendarWidget.prototype.setDate = function() {
	var month = this.today.getDate();
	this.year = this.today.getUTCFullYear();
	for(var i=0, m;i<this.regexp.length;i++){
		m = this.regexp[i].exec(this.target.value);
		if(m && m.length == 4) {
			this.day = m[1];
			month = m[2];
			this.year = m[3];
			break;
		}
	}
	
	this.year = this.year < this.from ? this.from : this.year;
	this.year = this.year > this.to ? this.to : this.year;
	if(!isNaN(parseInt(new Number(month)))){
		month = parseInt(new Number(month));
		this.month = month >0 && month <13 ? month - 1 : this.today.getMonth();
	}
	else {
		month = month.toString().toLowerCase();
		for(i=0; i<12; i++)
			if(month == this.text.month[i].toLowerCase()) {
				this.month = i;
				break;
			}
	}
		
	var length = new Date(this.year,this.month,40);
	length = 40 - length.getDate();
	this.day = this.day >0 && this.day<=length ? this.day : this.today.getDate();
}

CalendarWidget.prototype.createTable = function(className) {
	var self = this;
	var table = document.createElement('table');
	var thead = document.createElement('thead');
	var tbody = document.createElement('tbody');
	var tfoot = document.createElement('tfoot');
	var tr = document.createElement('tr');
	var td = document.createElement('td');
	var div = document.createElement('div');

	thead.appendChild(tr.cloneNode(true));
	thead.firstChild.appendChild(td.cloneNode(true));
	thead.firstChild.firstChild.colSpan=7;
	tfoot.appendChild(thead.firstChild.cloneNode(true));
	thead.firstChild.firstChild.appendChild(div.cloneNode(true));
	thead.firstChild.firstChild.appendChild(div.cloneNode(true));
	thead.firstChild.firstChild.appendChild(div.cloneNode(true));
	thead.firstChild.className = "header";

	thead.appendChild(tr.cloneNode(true));
	thead.lastChild.className = "week";
	for(var i=0;i<7;i++)
		thead.lastChild.appendChild(td.cloneNode(true));
	thead.lastChild.lastChild.className = "end";
	
	for(i=0;i<6;i++){
		tbody.appendChild(tr.cloneNode(true));
		for(var j=0, tdb=null;j<7;j++){
			tdb = td.cloneNode(true);
			tdb.onclick = function(){
				if(!isNaN(parseInt(this.innerHTML)))
					self.selectDay(parseInt(this.innerHTML));
			}
			tdb.ondblclick = function(){
				if(!isNaN(parseInt(this.innerHTML))){
					self.selectDay(parseInt(this.innerHTML));
					self.hide();
				}
			}
			tdb.onmouseover = function(){
				if(this.className!="hover" && !isNaN(parseInt(this.innerHTML))){
					this.oldClass = this.className;
					this.className = "hover";
				}
			}
			tdb.onmouseout = function(){
				if(!isNaN(parseInt(this.innerHTML)) && this.className=="hover")
					this.className = this.oldClass;
			}
			tbody.lastChild.appendChild(tdb);
		}
		tbody.lastChild.lastChild.className = "end";
	}
		
	table.appendChild(thead);
	table.appendChild(tbody);
	table.appendChild(tfoot);
	
	div.style.position = "absolute";
	div.style.display = "none";
	div.appendChild(table);
	document.body.appendChild(div);
	div.className = className;
	table.cellPadding = 0;
	table.cellSpacing = 0;
	table.onselectstart = function() {return false;}

	//table.onblur = function() {self.hide();};
	return table;
}

CalendarWidget.prototype.setContent = function(language) {
	var text = this["languagePack"+language.toUpperCase()], self=this;
	if(!text)
		text = this.languagePack;
	
	var mon = document.createElement('SELECT');
	mon.size=1;
	mon.onchange = function(){self.setMonth(this.selectedIndex);};

	for(var i=0, option=null; i<12; i++){
		option = new Option(i);
		option.innerHTML = text.month[i];
		mon.appendChild(option);
	}
	
	var year = document.createElement('SELECT');
	year.size=1;
	year.onchange = function(){self.setYear(this.options[this.selectedIndex].value);};

	for(i=this.from; i<=this.to; i++){
		option = new Option(i);
		option.innerHTML = i;
		option.value = i;
		year.appendChild(option);
	}
	
	this.table.firstChild.firstChild.firstChild.firstChild.className = "previous";
	this.table.firstChild.firstChild.firstChild.firstChild.innerHTML = "<a href='javascript:void(0);'>"+text.previous+"</a>";
	this.table.firstChild.firstChild.firstChild.firstChild.firstChild.onclick = function(){self.setMonth(self.month-1);};
	this.table.firstChild.firstChild.firstChild.childNodes[1].className = "month";
	this.table.firstChild.firstChild.firstChild.childNodes[1].appendChild(mon);
	this.table.firstChild.firstChild.firstChild.childNodes[1].appendChild(year);
	this.table.firstChild.firstChild.firstChild.childNodes[2].className = "next";
	this.table.firstChild.firstChild.firstChild.childNodes[2].innerHTML = "<a href='javascript:void(0);'>"+text.next+"</a>";
	this.table.firstChild.firstChild.firstChild.childNodes[2].firstChild.onclick = function(){self.setMonth(self.month+1);};
	this.table.lastChild.firstChild.firstChild.innerHTML = "<INPUT type='button' value='"+text.close+"'/>";
	this.table.lastChild.firstChild.firstChild.firstChild.onclick = function(){self.hide();};
	
	for(i=0;i<7;i++)
		this.table.firstChild.lastChild.childNodes[i].innerHTML = text.week[i];
	this.text = text;
}

CalendarWidget.prototype.pickUp = function() {
	if(!this.hidden)
		return this.hide();
	this.setDate();
	this.repaint();
	this.table.parentNode.style.display="";
	this.table.parentNode.style.left = getLeft(this.button) - this.table.parentNode.offsetWidth + this.button.offsetWidth;
	this.table.parentNode.style.top = getTop(this.button) + this.button.offsetHeight;// + this;
	this.hidden = false;
	this.docOnclick.enable();
}

CalendarWidget.prototype.hide = function() {
	this.hidden = true;
	this.table.parentNode.style.display = "none";
	this.docOnclick.disable();
}

CalendarWidget.prototype.repaint = function() {
	this.table.firstChild.firstChild.firstChild.childNodes[1].firstChild.selectedIndex= this.month;
	this.table.firstChild.firstChild.firstChild.childNodes[1].lastChild.selectedIndex= this.year - this.from;
	var first = new Date(this.year,this.month,1);
	var length = new Date(this.year,this.month,40);
	length = 40 - length.getDate();
	var ended = false;
	for(var i=0, td=null, d=0; i<6; i++)
		for(var j=0; j<7; j++){
			td = this.table.childNodes[1].childNodes[i].childNodes[j];
			if((i==0 && j<first.getUTCDay())||ended){
				td.innerHTML = "&nbsp;";
				td.id = null;
				td.className = null;;
			} else {
				td.innerHTML = ++d;
				if(d==this.day)
					td.id = "selected";
				else
					td.id='';
				if(d == this.today.getDate() && this.month == this.today.getMonth() && this.year == this.today.getYear())
					td.className = "today";
				else if (j==6)
					td.className = "end";
				else
					td.className = null;
				ended = d >= length;
			}
		}
}

CalendarWidget.prototype.setYear = function(year) {
	if(year>=this.from && year<=this.to)
		this.year = year;
	this.setTargetValue();
	this.repaint();
}

CalendarWidget.prototype.setMonth = function(month) {
	if(month>11 && this.year<this.to){
		this.month = 0;
		this.year++;
	}
	else
	if(month<0 && this.year>this.from){
		this.month = 11;
		this.year--;
	}
	else
	if(month>=0 && month<=11)
		this.month = month;
	this.setTargetValue();
	this.repaint();
}

CalendarWidget.prototype.selectDay = function(day) {
	this.day = day;
	this.setTargetValue();
	this.repaint();
}

CalendarWidget.prototype.setTargetValue = function(prepareToSubmit) {
	if(!prepareToSubmit)
		prepareToSubmit = false;
	this.target.value = this.getValue(prepareToSubmit);
}

CalendarWidget.prototype.getValue = function(originalFormat) {
	var value = null, newValue = this.target.value;
	if(originalFormat)
		value = this.day + " " + this.languagePack.month[this.month] + " " + this.year + " ";
	else
		value = this.day + " " + this.text.month[this.month] + " " + this.year + " ";
	for(var i=0;i<this.regexp.length;i++){
		newValue = this.target.value.replace(this.regexp[i], value);
		if(newValue != this.target.value)
			break;
	}
	return newValue;
}

CalendarWidget.prototype.languagePack = {
	month: ["January","February","March","April","May","June","July","August","September","October","November","December"],
	week: ["Mo","Tu","We","Th","Fr","Sa","Su"],
	previous: "Prev",
	next: "Next",
	close: "Close"
}

CalendarWidget.prototype.languagePackRUS = {
	month: ["Январь","Февраль","Март","Апрель","Май","Июнь","Июль","Август","Сентябрь","Октябрь","Ноябрь","Декабрь"],
	week: ["Пн","Вт","Ср","Чт","Пт","Сб","Вк"],
	previous: "<<",
	next: ">>",
	close: "Закрыть"
}


/**	Extended Drop Down box (<select>)
 *
 *	@param Element	select		<SELECT> which needs to be decorated
 *	@param Element	view		Element which will show selected items
 *	@param Element	button		Element which will activate dropdown, if null then @param view will be used as button
 *	@param String	divclass	ClassName of DIV that will contain dropdown
 *	@param String	style		CSS style definitions to be injected to dropdown
 *	@param Integer	width		Width. You can also pass String as width. Examples: 10; 10px; 10%;
 *	@param Integer	height		Height. Has same behaviour as Width.
 */

function SelectWidget(select, view, button, closeText, divclass, style, width, height) {
//	alert(select.defaultSelected);
	var self = this;
	this.view = view;
	this.select = select;
	this.hidden = true;
	this.div = null;
	this.list = null;
	this.chooseText = null;
	this.createList(closeText, divclass, style, width, height);
	this.button = button ? button : view;
	this.button.onclick = function() {self.show();}
	this.displaySelected(true);
	this.ce = new CaptureEvents("onclick,onkeypress", [this.div, this.view], function() {self.hide();});
}

SelectWidget.prototype.createList = function(closeText, divclass, styledef, width, height) {
	var div = document.createElement("DIV"), self = this;
	var frame = document.createElement("IFRAME");
	var close = document.createElement("A");
	document.body.appendChild(div);
	div.appendChild(frame);
	div.appendChild(close);
	frame.contentWindow.document.writeln("<HTML><HEAD><TITLE>SelectWidget</TITLE></HEAD><BODY class='iframe'><UL class='select'></UL></BODY></HTML>");
	frame.contentWindow.document.close();
	this.div = div;
	this.list = frame.contentWindow.document.body.firstChild;
	var style = frame.contentWindow.document.createElement("STYLE");
	frame.contentWindow.document.lastChild.firstChild.appendChild(style);
	if(isMoz)
		frame.contentWindow.document.styleSheets[0].insertRule(styledef,0);
	else
		frame.contentWindow.document.styleSheets[0].cssText = styledef;
	frame.frameBorder = 0;
	frame.style.width = width;
	frame.style.height = height;
	close.innerHTML = closeText;
	close.href = "javascript:void(0);";
	close.onclick = function(){self.hide();}
	div.className = divclass;
	div.style.position = "absolute";
	div.style.display = "none";
	div.style.width = width;
//	div.style.height = height;
	this.initializeList();
}

SelectWidget.prototype.initializeList = function() {
	this.list.onselectstart = function() {return false;}
	for(var i=0, li = null, input = null; i<this.select.options.length; i++){
		li=this.list.ownerDocument.createElement("LI");
		li.innerHTML = "<INPUT type='" + (this.select.multiple ? "checkbox" : "radio") + "'name='i'>" + this.select.options[i].innerHTML;
		li.className = this.select.options[i].selected ? "checked" : "";
		li.input = li.firstChild;
		li.index = i;
		this.list.appendChild(li);
		li.input.checked = this.select.options[i].selected;
		this.registerItemHandlers(li);
	}
}

SelectWidget.prototype.registerItemHandlers = function(li){
	var self = this;
	li.onclick = function(){
		if(self.select.options[this.index].selected)
			self.itemUnchecked(this);
		else
			self.itemChecked(this);
	}
	li.ondblclick = function(){
		self.hide();
	}
	li.onmouseover = function(){
		self.itemHovered(this);
	}
	li.onmouseout = function(){
		self.itemUnhovered(this);
	}
}

SelectWidget.prototype.show = function(){
	if(!this.hidden){
		this.hide();
		return;
	}
	this.synchronize();
	this.hidden = false;
	this.div.style.left = getLeft(this.button);
	this.div.style.top = getTop(this.button) + this.button.offsetHeight;
	this.div.style.display="";
	this.ce.enable();
}

SelectWidget.prototype.hide = function(){
	this.hidden = true;
	this.div.style.display = "none";
	this.displaySelected();
	this.button.focus();
	this.ce.disable();
}

SelectWidget.prototype.displaySelected = function(firstTime){
	if(!this.chooseText)
		this.chooseText = this.view.innerHTML;
	for(var i = 0, v = new Array(), j = 0; i < this.select.options.length; i++)
		if((!firstTime && this.select.options[i].selected) || (firstTime && this.select.options[i].defaultSelected))
			v[j++] = this.select.options[i].innerHTML;
	this.view.innerHTML = v.join("; ");
	if(this.view.innerHTML == "")
		this.view.innerHTML = this.chooseText;
}

SelectWidget.prototype.synchronize = function(){
	// Not implemented yet
	// TODO: Agar <select> script tomonidan runtimeda o'zgartirilsa chora ko'rish k-k... Xali beri k-k emas
}

SelectWidget.prototype.itemChecked = function(li){
	if(!this.select.multiple)
		this.itemUnchecked(this.list.childNodes[this.select.selectedIndex],true);
	this.select.options[li.index].selected = li.input.checked = true;
	li.className = "checked";
}

SelectWidget.prototype.itemUnchecked = function(li, force){
	if(!force && !this.select.multiple && li.index == this.select.selectedIndex) return;
	this.select.options[li.index].selected = li.input.checked = false;
	li.className = "";
}

SelectWidget.prototype.itemHovered = function(li){
	li.id = "hover";
}

SelectWidget.prototype.itemUnhovered = function(li){
	li.id = "";
}




function TabbedWidget(altcontroller, where, divclass) {
	this.tabs = null;
	this.dropDown = null;
	this.alt = altcontroller;
	this.activeindex = -1;
	this.shown = this.alt.elements.length;
	this.createElements(where, divclass);
	this.update();
	if(this.alt.activeindex >=0)
		this.activateTab(this.alt.activeindex);
}

TabbedWidget.prototype.createElements = function(where, divclass) {
	this.tabs = document.createElement("UL");
	this.dropDown = document.createElement("UL");
	var div = document.createElement("DIV"), self = this;
	for(var i = 0, li = null, now = null; i < this.alt.elements.length; i++){
		now = new Date();
		li = document.createElement("LI");
		li.innerHTML = this.alt.names[i];
		li.visited = now.getTime() - i*10;
		this.tabs.appendChild(li);
		this.registerTabItemHandlers(i);
	}
	for(i = 0; i < this.alt.elements.length; i++){
		li = document.createElement("LI");
		li.innerHTML = this.alt.names[i];
		li.style.display = "none";
		this.dropDown.appendChild(li);
		this.registerDropDownItemHandlers(i);
	}
	li = document.createElement("LI");
	li.innerHTML = "More...";
	li.className = "dropdown";
	li.onclick = function() {self.dropDownClicked();}
	this.tabs.appendChild(li);
	document.body.appendChild(div);
	div.appendChild(this.dropDown);
	div.style.position = "absolute";
	div.className = divclass;
	this.hideDropDown();
	where.appendChild(this.tabs);
	self.onresize = window.onresize;
	window.onresize = function(){
		self.update();
		if(self.onresize)
			self.onresize();
	}
}

TabbedWidget.prototype.dropDownClicked = function() {
	var div = this.dropDown.parentNode;
	if(div.style.display != "none") {
		this.hideDropDown();
		return;
	}
	div.style.display = "";
	div.style.left = getLeft(this.tabs.lastChild) + this.tabs.lastChild.offsetWidth - div.offsetWidth;
	div.style.top = getTop(this.tabs.lastChild) + this.tabs.lastChild.offsetHeight;
}

TabbedWidget.prototype.hideDropDown = function() {
	this.dropDown.parentNode.style.display = "none";
}

TabbedWidget.prototype.registerTabItemHandlers = function(index) {
	var self = this;
	this.tabs.childNodes[index].onclick = function(){
		self.activateTab(index);
	}
}

TabbedWidget.prototype.registerDropDownItemHandlers = function(index) {
	var self = this;
	this.dropDown.childNodes[index].onclick = function(){
		self.showTab(index);
		self.activateTab(index);
		self.hideDropDown();
		self.update();
		this.className = "";
	}
	this.dropDown.childNodes[index].onmouseover = function(){
		this.className = "hover";
	}
	this.dropDown.childNodes[index].onmouseout = function(){
		this.className = "";
	}
}

TabbedWidget.prototype.hideLastTab = function() {
	var index = -1;
	for(var i = 0; i < this.alt.elements.length; i++)
		if(index >=0 && this.tabs.childNodes[i].style.display != "none" && this.tabs.childNodes[i].visited < this.tabs.childNodes[index].visited)
			index = i;
		else if(index < 0 && this.tabs.childNodes[i].style.display != "none")
			index = i;
	this.hideTab(index);
}

TabbedWidget.prototype.hideTab = function(index) {
	this.showDropDownItem(index);
	this.deactivateTab(index);
	this.tabs.childNodes[index].style.display = "none";
	this.shown--;
}

TabbedWidget.prototype.showTab = function(index) {
	this.tabs.childNodes[index].style.display = "";
	this.hideDropDownItem(index);
	this.shown++;
}

TabbedWidget.prototype.activateTab = function(index) {
	if(this.activeindex == index) return;
	if(this.activeindex >= 0)
		this.deactivateTab(this.activeindex);
	this.hideDropDown();
	var now = new Date();
	this.tabs.childNodes[index].visited = now.getTime();
	this.tabs.childNodes[index].className = "active";
	this.alt.switchTo(index);
	this.activeindex = index;
	this.update();
}

TabbedWidget.prototype.deactivateTab = function(index) {
	this.tabs.childNodes[index].className = "";
}

TabbedWidget.prototype.showDropDownItem = function(index) {
	this.dropDown.childNodes[index].style.display = "";
}

TabbedWidget.prototype.hideDropDownItem = function(index) {
	this.dropDown.childNodes[index].style.display = "none";
}

TabbedWidget.prototype.update = function() {
	while(this.mustHideTab())
		this.hideLastTab();
	while(this.tryToAddTab())
		;
	this.tabs.lastChild.style.visibility = this.shown < this.alt.elements.length ? "" : "hidden";
}

TabbedWidget.prototype.mustHideTab = function() {
	if(this.shown == 1) return false;
	var firstshown = 0, lastshown = this.alt.elements.length - 1;
	while(!(this.tabs.childNodes[firstshown].style.display != "none") && firstshown < this.alt.elements.length)
		firstshown++;
	if(!(this.tabs.childNodes[firstshown].style.display != "none"))
		return false;
	if(this.tabs.lastChild.offsetTop >= (this.tabs.childNodes[firstshown].offsetTop + this.tabs.childNodes[firstshown].offsetHeight/2))
		return true;
	while(lastshown >= 0 && !(this.tabs.childNodes[lastshown].style.display != "none"))
		lastshown--;
	if(lastshown < 0)
		return false;
	if((this.tabs.childNodes[lastshown].offsetLeft + this.tabs.childNodes[lastshown].offsetWidth) +10 >= this.tabs.lastChild.offsetLeft)
		return true;
	return false;
}

TabbedWidget.prototype.tryToAddTab = function() {
	var index = -1, firstshown = 0;
	for(var i = 0; i < this.alt.elements.length; i++)
		if(index >=0 && !(this.tabs.childNodes[i].style.display != "none") && this.tabs.childNodes[i].visited > this.tabs.childNodes[index].visited)
			index = i;
		else if(index < 0 && !(this.tabs.childNodes[i].style.display != "none"))
			index = i;
	while(!(this.tabs.childNodes[firstshown].style.display != "none") && firstshown < this.alt.elements.length)
		firstshown++;
	if(!(this.tabs.childNodes[firstshown].style.display != "none") || index<0)
		return false;
	this.showTab(index);
	if(this.tabs.lastChild.offsetTop >= (this.tabs.childNodes[firstshown].offsetTop + this.tabs.childNodes[firstshown].offsetHeight/2)){
		this.hideTab(index);
		return false;
	}
	if((this.tabs.childNodes[index].offsetLeft + this.tabs.childNodes[index].offsetWidth) +15 >= this.tabs.lastChild.offsetLeft){
		this.hideTab(index);
		return false;
	}
	return true;
}




function DocumentsWidget(controller, where, headers) {
	this.control = controller;
	this.control.bind(this);
	this.view = null;
	this.createView(where, headers);
	this.refresh();
}

DocumentsWidget.prototype.createView = function(where, headers) {
	var table = document.createElement("TABLE");
	var tbody = document.createElement("TBODY");
	var thead = document.createElement("THEAD");
	var tr = document.createElement("TR");
	var self = this;
	thead.appendChild(tr.cloneNode());
	for(var i = 0, td = null; i<5; i++) {
		td = document.createElement("TD");
		td.className = "c" + (i + 1);
		tr.appendChild(td.cloneNode());
		td.innerHTML = headers[i];
		thead.firstChild.appendChild(td);
	}
	
	for(i = 0, r = null; i<this.control.names.length; i++) {
		r = tr.cloneNode(true);
		r.firstChild.innerHTML = this.control.names[i];
		r.firstChild.noWrap = true;
		r.childNodes[1].innerHTML = '<a href="#"></a>';
		r.childNodes[1].firstChild.onclick = function(){self.control.editDocument(this.index);return false;}
		r.childNodes[2].innerHTML = '<img src="/openconstructor/i/default/e/h/new.gif" alt="' + headers[2]+ '" width="24" height="24">';
		r.childNodes[2].firstChild.onclick = function(){self.control.createDocument(this.index);}
		r.childNodes[3].innerHTML = '<img src="/openconstructor/i/default/e/h/remove.gif" alt="' + headers[3]+ '" width="24" height="24">';
		r.childNodes[3].firstChild.onclick = function(){self.control.removeDocument(this.index);}
		r.childNodes[4].innerHTML = '<img src="/openconstructor/i/default/e/h/select.gif" alt="' + headers[4]+ '" width="24" height="24">';
		r.childNodes[4].firstChild.onclick = function(){self.control.selectDocument(this.index);}
		r.childNodes[1].firstChild.index = r.childNodes[2].firstChild.index = r.childNodes[3].firstChild.index = r.childNodes[4].firstChild.index = i;
		r.className = 'r' + (i % 2);
		tbody.appendChild(r);
	}
	
	table.cellSpacing = 0;
	table.appendChild(thead);
	table.appendChild(tbody);
	this.view = table;
	where.appendChild(this.view);
}

DocumentsWidget.prototype.refresh = function() {
	for(var i = 0, r = null; i<this.control.names.length; i++) {
		r = this.view.lastChild.childNodes[i];
		r.childNodes[1].firstChild.innerHTML = this.control.headers[i];
		setButtonState(r.childNodes[2].firstChild, this.control.createEnabled(i));
		setButtonState(r.childNodes[3].firstChild, this.control.removeEnabled(i));
		setButtonState(r.childNodes[4].firstChild, this.control.selectEnabled(i));
	}
}





function ArrayWidget(controller, where, headers) {
	this.control = controller;
	controller.bind(this);
	this.docs = null;
	this.toolbar = null;
	this.selected = 0;
	this.createView(where, headers);
	this.initialize();
}

ArrayWidget.prototype.createView = function(where, headers) {
	var table = document.createElement("TABLE");
	var tbody = document.createElement("TBODY");
	var div = document.createElement("DIV");
	var a = document.createElement("A");
	var self = this;
	
	div.appendChild(a.cloneNode());
	div.lastChild.innerHTML = "<img src='" + wchome +"/i/default/e/h/new.gif' width='24' height='24'>";
	div.lastChild.href = div.lastChild.title = headers[0];
	div.lastChild.onclick = function() {self.control.createAndAdd();return false;}
	
	div.appendChild(a.cloneNode());
	div.lastChild.innerHTML = "<img src='" + wchome +"/i/default/e/h/add.gif' width='24' height='24'>";
	div.lastChild.href = div.lastChild.title = headers[1];
	div.lastChild.onclick = function() {self.control.add();return false;}
	
	div.appendChild(a.cloneNode());
	div.lastChild.innerHTML = "<img src='" + wchome +"/i/default/e/h/remove.gif' width='24' height='24'>";
	div.lastChild.href = div.lastChild.title = headers[2];
	div.lastChild.onclick = function() {self.removeSelected();return false;}
	
	table.cellPadding = 0;
	table.cellSpacing = 0;
	table.appendChild(tbody);
	this.toolbar = div;
	this.docs = tbody;
	where.appendChild(div);
	where.appendChild(table);
}

ArrayWidget.prototype.initialize = function() {
	for(var i = 0; i < this.control.select.options.length; i++)
		this.addDocument(i);
	setButtonState(this.toolbar.childNodes[0], this.control.createEnabled());
	setButtonState(this.toolbar.childNodes[1], this.control.addEnabled());
	setButtonState(this.toolbar.childNodes[2], this.selected > 0);
}

ArrayWidget.prototype.addDocument = function(index) {
	var tr = document.createElement("TR");
	var td = document.createElement("TD");
	var self = this;
	tr.appendChild(td);
	tr.appendChild(td.cloneNode());
	tr.className = "r" + (index % 2);
	
	td.className = "input";
	td.innerHTML = "<input type='checkbox' name='ch." + this.control.select.id + "'>";
	td.firstChild.onclick = function() { self.selected += this.checked ? 1 : -1; self.refreshToolbar();}
	
	td = tr.lastChild;
	td.className = "header";
	td.innerHTML = "<a href='" + this.getDocumentURI(index) + "'>" + this.control.select.options[index].innerHTML + "</a>";
	td.firstChild.onclick = function() {openWindow(this.href, 800, 500); return false;}
	
	this.docs.appendChild(tr);
	this.refresh();
}

ArrayWidget.prototype.getDocumentURI = function(index) {
	var select = this.control.select;
	return wchome + "/data/" + select.getAttribute("doctype") + "/edit.php?id=" + select.options[index].value + "&ds_id=" + select.getAttribute("fromds");
}

ArrayWidget.prototype.removeSelected = function() {
	for(var i = 0; i<this.docs.childNodes.length; i++)
		if(this.docs.childNodes[i].style.display != "none" && this.docs.childNodes[i].firstChild.firstChild.checked) {
			this.control.remove(i);
			this.docs.childNodes[i].style.display = "none";
		}
	this.selected = 0;
	this.refreshToolbar();
	this.refresh();
}

ArrayWidget.prototype.restoreDocument = function(index) {
	this.docs.childNodes[index].firstChild.firstChild.checked = false;
	this.docs.childNodes[index].style.display = "";
	this.refresh();
}

ArrayWidget.prototype.refreshToolbar = function() {
	setButtonState(this.toolbar.childNodes[2], this.selected > 0);
}

ArrayWidget.prototype.refresh = function() {
	var index = 0;
	for(var i = 0; i<this.docs.childNodes.length; i++)
		if(this.docs.childNodes[i].style.display != "none")
			this.docs.childNodes[i].className = "r" + (index++ % 2);
}


function WidgetUtils() {}

WidgetUtils.prototype.createTableFromSelect = function(select, header) {
	var table = document.createElement("TABLE");
	var tbody = document.createElement("TBODY");
	var tr = document.createElement("TR");
	var td = document.createElement("TD");

	tr.appendChild(td.cloneNode());
	tr.appendChild(td.cloneNode());
	
	for(var i = 0, r = null; i < select.options.length; i++){
		r = tr.cloneNode(true);
		r.firstChild.innerHTML = "<INPUT type='" + (select.multiple ? "checkbox" : "radio") + "' name='i'>";
		r.firstChild.firstChild.index = i;
		r.firstChild.firstChild.onclick = select.multiple ? function() {select.options[this.index].selected = this.checked;}
			: function() {select.selectedIndex = this.index;}
		r.lastChild.innerHTML = header ? header(i) : select.options[i].innerHTML;
		r.firstChild.className = "input";
		r.lastChild.className = "doc";
		r.className = 'r' + (i % 2);
		tbody.appendChild(r);
	}
	
	table.appendChild(tbody);
	
	return table;
}