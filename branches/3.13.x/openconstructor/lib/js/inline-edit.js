/**
 *	@author Sanjar Akhmedov (eSector solutions)
 *	@since 20.12.2005
 */

function registerWCIBlock(block, visible, cSrc, cacheId, cacheLifetime) {
	if(!isIE)
		return;
	var div = document.createElement("DIV");
	div.className = "wciBlock";
	div.style.display = visible ? "" : "none";
	block.visible = visible ? true : false;
	if(block.childNodes.length)
		block.insertBefore(div, block.childNodes[0]);
	else
		block.appendChild(div);
	div.innerHTML = "<span>" + BTN_WCI_EDIT_BLOCK + "</span>";
	var menu = new WCIBlockEditMenu(block.objName, block.childNodes[0]);
	var docHandler, objHandler;
	if(block.objDocId) {
		if(block.objDsId) {
			docHandler = function() {
				openWindow(wchome + '/data/' + block.objDsType+ '/edit.php?ds_id=' + block.objDsId + '&id=' + block.objDocId, 820);
				menu.hide();
			}
		} else {
			docHandler = function() {
				openWindow(wchome + '/data/editdoc.php?type=' + block.objDsType+ '&id=' + block.objDocId, 820);
				menu.hide();
			}
		}
		menu.addItem(BTN_WCI_EDIT_DOC, docHandler);
	}
	objHandler = function() {
		openWindow(wchome + '/objects/edit.php?id=' + block.objId, 660);
		menu.hide();
	}
	div.onclick = docHandler ? docHandler : objHandler;
	menu.addItem(BTN_WCI_EDIT_OBJ, objHandler);
	menu.addItem(BTN_WCI_EDIT_TPL, function() {
		openWindow(wchome + '/templates/edit.php?id=' + block.objTpl, 760);
		menu.hide();
	});
	if(block.objDsId)
		menu.addItem(BTN_WCI_OPEN_DS, function() {
			window.open(wchome + '/data/?node=' + block.objDsId);
			menu.hide();
		});
	document.attachEvent("onkeydown", function() {
		if(event.ctrlKey) {
			if(!block.visible) {
				block.childNodes[0].style.display = "block";
				block.visible = true;
				setCookie('wciVisible', 1, '/');
			} else {
				block.childNodes[0].style.display = "none";
				block.visible = false;
				menu.hide();
				setCookie('wciVisible', 0, '/');
			}
		}
	});
	div.title = H_WCI_BLOCK_ID + ": " + block.blockId +
		"\n" + H_WCI_OBJ_TYPE + ": " + block.objType +
		"\n" + H_WCI_OBJ_NAME + ": " + block.objName +
		"\n" + H_WCI_CONTENT_SRC + ": " + (cSrc == "D/" ? H_WCI_CSRC_DYN : (cSrc == "C/" ? H_WCI_CSRC_CACHE : H_WCI_CSRC_NEW_CACHE))
	;
	if(cacheId)
		div.title +=
			"\n" + H_WCI_CACHE_ID + ": " + cacheId +
			"\n" + H_WCI_CACHE_LIFETIME + ": " + cacheLifetime
	;
	div.onmouseover = function() {
		window.status = docHandler ? BTN_WCI_EDIT_DOC + " [ " + block.objDsType + " ]" : BTN_WCI_EDIT_OBJ + " [ " + block.objType + " ]";
	}
	div.onmouseout = function() {
		window.status = window.defaultStatus;
	}
}

function WCIBlockEditMenu(title, button) {
	var self = this;
	var div = document.createElement("DIV");
	this.el = div;
	this.visible = false;
	this.button = button;
	this.button.oncontextmenu = function() {self.show(); return false;}
	div.className = "wciToolbar";
	div.style.display = "none";
	div.innerHTML = "<h4>" + title + "</h4><ul></ul>";
	this.items = div.childNodes[1];
	window.attachEvent("onload", function() {
		self.ce = new CaptureEvents('onclick,oncontextmenu', [button, self.el], function() {self.hide();});
		document.body.appendChild(self.el);
	});
}

WCIBlockEditMenu.prototype = {
	addItem:
		function(title, handler) {
			var li = document.createElement('LI');
			this.items.appendChild(li);
			if(handler) {
				li.innerHTML = "<a href='javascript: //'>" + title + "</a>";
				li.onclick = handler;
			} else {
				li.innerHTML = title;
				li.className = "dis";
			}
		},
	
	show :
		function() {
			if(this.visible) {
				this.hide();
				return;
			}
			this.visible = true;
			this.ce.enable();
			this.el.style.left = getLeft(this.button) + "px";
			this.el.style.top = getTop(this.button) + this.button.offsetHeight + "px";
			this.el.style.display = "block";
		},
	
	hide :
		function() {
			this.visible = false;
			this.ce.disable();
			this.el.style.display = "none";
		}
}
