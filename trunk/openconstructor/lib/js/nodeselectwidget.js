/**
 *	@author Sanjar Akhmedov (eSector solutions)
 *	@since 20.10.2004
 */

function Tree() {
	this.node = new Array();
	this.root = null;
}

Tree.prototype = {
	getNode :
		function(id) {
			for(var i = 0; i < this.node.length; i++)
				if(this.node[i].id == id)
					return this.node[i];
			return null;
		},

	importXml :
		function(xml) {
			var root = null;
			try {
				root = xml.getElementsByTagName("tree")[0];
			} catch(e) {
				try {
					root = xml.getElementsByTagName("node")[0]
				} catch (e) {}
			}
			if(root == null)
				return;
			this.node[0] = new Node(this);
			this.node[0].fromTag(root);
			this.root = this.node[0];
			this.root.level = 0;
			this.importChildren(this.root, root);
		},

	importChildren :
		function(node, tag) {
			if(tag.childNodes.length)
				for(var i = 0, t = null, n = null; i < tag.childNodes.length; i++) {
					t = tag.childNodes[i];
					if(t.tagName == "tree" || t.tagName == "node") {
						n = new Node(this);
						n.fromTag(t);
						n.parent = node.index;
						n.level = node.level + 1;
						this.node[n.index] = n;
						this.importChildren(n, t);
						node.child[node.child.length] = n;
					}
				}
		}
}

function Node(tree) {
	this.tree = tree;
	this.id = null;
	this.name = null;
	this.index = -1;
	this.next = -1;
	this.parent = -1;
	this.level = 0;
	this.state = 0;
	this.child = new Array();
}

Node.prototype = {
	fromTag :
		function(tag) {
			this.id = tag.getAttribute("id");
			this.name = tag.getAttribute("name");
			this.index = tag.getAttribute("i");
			this.next = tag.getAttribute("n");
		},

	toString :
		function() {
			return this.name + " [id = " + this.id + "]";
		}
}

function NodeSelectWidget(select, output, button, width, height, closeText, loadingText) {
	var self = this;
	this.treeId = parseInt(select.getAttribute("tree"));
	this.tree = null;
	this.treeElement = null;
	this.control = null;
	this.setDrawer(new DefaultTreeDrawer());
	this.docOnclick = null;
	this.select = select;
	this.multiple = select.type == "select-multiple";
	this.viewStrategy = null;
	this.width = width ? width : "310px";
	this.height = height ? height : "350px";
	button.onclick = function() {
		self.show();
	}
	this.button = button;
	this.output = output;
	this.setViewStrategy(
		function(sel) {
			return sel.join("; ");
		}
	);
	this.closeText = closeText ? closeText : "Close";
	this.loadingText = loadingText ? loadingText : "Loading...";
	this.lastSelected = null;
	this.url = "/data/hybrid/gettree.php?id=";
}

NodeSelectWidget.prototype = {
	loadTree :
		function() {
			var self = this;
			var req = newXmlHttp();
			this.control.childNodes[0].innerHTML = "<div class='load'>" + this.loadingText + "</div>";
			req.open("GET", document.location.protocol + "//" + document.location.host + wchome + this.url + self.treeId, true);
			req.onreadystatechange = function() {
				if(req.readyState == 4) {
					if(req.status == 200) {
						self.control.childNodes[0].innerHTML = "";
						self.tree = new Tree();
						self.tree.importXml(req.responseXML);
						if(self.tree.node.length > 0) {
							self.init();
							return;
						}
					}
					self.control.childNodes[0].innerHTML = "<div class='load'>Cannot load tree[id = " + self.treeId + "], status = " + req.status + "</div>";
				}
			}
			req.send(null);
		},

	init :
		function() {
			if(this.control == null) {
				var div = document.createElement("div"), self = this;
				document.body.appendChild(div);
				div.innerHTML = "<div class='ns'></div><input type='button' value='" + this.closeText + "' class='close'>";
				div.className = "ctl";
				div.style.position = "absolute";
				div.style.display = "none";
				div.style.width = this.width;
				div.childNodes[0].style.width = this.width;
				div.childNodes[0].style.height = this.height;
				div.childNodes[1].onclick = function() {
					self.hide();
				}
				this.docOnclick = new CaptureEvents("onclick,onkeypress", [div, this.button], function() {self.hide();});
				this.control = div;
			}
			if(this.tree == null) {
				this.loadTree();
				return;
			}
			if(this.tree.node.length == 0) {
				return;
			}
			if(this.treeElement == null) {
				this.drawControl();
				var self = this, tpl = function(i) {
					var o = self.select.options[i];
					var node = self.tree.getNode(o.value);
					if(node != null) {
						self.setNodeState(node, true);
						var parent = node.parent;
						while(parent > 0) {
							var n = self.tree.node[parent];
							self.drawer.setSubTreeState(n, true)
							parent = n.parent;
						}
					}
				}
				if(this.multiple) {
					for(var i = 0; i < this.select.options.length; i++)
						if(this.select.options[i].selected)
							tpl(i);
				} else if(this.select.selectedIndex != -1)
					tpl(this.select.selectedIndex);
			}
		},

	show :
		function() {
			this.init();
			if(this.control.style.display == "") {
				this.hide();
				return;
			}
			this.control.style.left = getLeft(this.button) + "px";
			this.control.style.top = getTop(this.button) + this.button.offsetHeight + "px";
			this.control.style.display = "";
			this.docOnclick.enable();
		},

	hide :
		function() {
			this.docOnclick.disable();
			this.control.style.display = "none";
			this.updateSelect();
			this.displaySelected();
		},

	updateSelect :
		function() {
			while(this.select.options.length > 0)
				this.select.removeChild(this.select.options[0]);
			for(var i = 0; i < this.tree.node.length; i++)
				if(this.tree.node[i].state > 0) {
					var opt = document.createElement('option'), node = this.tree.node[i];
					this.select.appendChild(opt);
					opt.value = node.id;
					opt.innerHTML = node.name;
					opt.selected = true;
					if(this.select.type != "select-multiple")
						return;
				}
		},

	displaySelected :
		function() {
			if(!this.initialOutput)
				this.initialOutput = this.output.innerHTML;
			var sel = new Array(), html = this.initialOutput;
			if(this.select.type == "select-multiple")
				for(var i = 0, o = null; i < this.select.options.length; i++) {
					o = this.select.options[i];
					if(o.selected)
						sel[sel.length] = o.innerHTML;
				}
			else if(this.select.selectedIndex != -1)
				sel[sel.length] = this.select.options[this.select.selectedIndex].innerHTML;
			if(sel.length > 0)
				html = this.viewStrategy(sel);
			this.output.innerHTML = html;
		},

	setNodeState :
		function(node, state) {
			if(!this.multiple && this.lastSelected) {
				this.lastSelected.state = false;
				this.drawer.repaint(this.lastSelected);
			}
			node.state = state;
			this.drawer.repaint(node);
			this.lastSelected = node;
		},

	setViewStrategy :
		function(strategy) {
			this.viewStrategy = strategy;
			this.displaySelected();
		},

	setDrawer :
		function(drawer) {
			this.drawer = drawer;
			this.drawer.widget = this;
		},

	drawControl :
		function() {
			this.treeElement = this.drawer.drawRoot(this.tree.root, this.control.childNodes[0])
			this.drawChildNodes(this.tree.root, this.treeElement);
		},

	drawChildNodes :
		function(node, el) {
			for(var i = 0, n = null; i < node.child.length; i++) {
				n = node.child[i];
				if(n.child.length)
					this.drawChildNodes(n, this.drawer.drawLeafNode(n, el));
				else
					this.drawer.drawNode(n, el);
			}
		}
}


function DefaultTreeDrawer() {
	this.widget = null;
}

DefaultTreeDrawer.prototype = {
	drawRoot :
		function(node, el) {
			el.innerHTML = "<table class='tree' cellspacing='0' cellpadding='0'><tbody><tr>" +
				"<td colspan='4' class='head'>" + node.name + "</td>" +
				"</tr></tbody></table>";
			return el.childNodes[0].childNodes[0];
		},

	drawLeafNode :
		function(node, el) {
			this.drawNode(node, el);
			var tr = document.createElement('tr');
			var td = document.createElement('td');
			td.style.cssText = "padding:0;border:none;";
			td.colSpan = 4;
			tr.appendChild(td);
			tr.style.display = "none";
			tr.setAttribute("id", "t" + node.id);
			el.appendChild(tr);
			td.innerHTML = "<table class='tree' cellspacing='0' cellpadding='0'><tbody></tbody></table>";
			return td.childNodes[0].childNodes[0];
		},

	drawNode :
		function(node, el) {
			var self = this;
			var tr = document.createElement('tr');
			var tdc = document.createElement('td'), tdp = tdc.cloneNode(true), tdf = tdc.cloneNode(true), tdn = tdc.cloneNode(true);
			tr.appendChild(tdc);
			tr.appendChild(tdp);
			tr.appendChild(tdf);
			tr.appendChild(tdn);
			el.appendChild(tr);
			tr.setAttribute("id", "n" + node.id);
			tdc.className = "ch";
			tdc.innerHTML = this.widget.multiple ? "<input type='checkbox'>" : "<input type='radio' name='_ns" + node.tree.root.id + "' class='r'>";
			tdc.childNodes[0].onclick = function() {
				self.widget.setNodeState(node, this.checked);
			}
			if(node.child.length) {
				tdp.innerHTML = "<a href='#'><img src='" + wchome + "/i/default/widg/plus.gif' style='margin: 0 5px 0 5px'><img src='" + wchome + "/i/default/widg/minus.gif' style='margin: 0 5px 0 5px'></a>";
				var a = tdp.childNodes[0];
				a.setAttribute("id", "s" + node.id);
				a.onclick = function() {
					self.signClicked(this, node);
					return false;
				}
				a.childNodes[1].style.display = "none";
				tdp.style.cssText = "padding-left:" + (node.level * 15 - 19) + "px";
			} else {
				tdp.innerHTML = "&nbsp;";
				tdp.style.cssText = "padding-left:" + (node.level * 15) + "px";
			}
			tdf.innerHTML = "<img src='" + wchome + "/i/default/widg/f.gif' style='margin: 0 3px 0 2px'>";
			tdn.noWrap = "yes";
			tdn.innerHTML = "<a href='#" + node.id + "'>" + node.name + "</a>";
			tdn.childNodes[0].onclick = function() {
				tdc.childNodes[0].click();
				if(node.child.length > 0 && tdc.childNodes[0].checked)
					self.setSubTreeState(node, true);
				return false;
			}
			tdn.className = "n";
		},

	repaint :
		function(node) {
			var tr = document.getElementById("n" + node.id);
			tr.className = node.state ? "selected" : "";
			tr.childNodes[0].childNodes[0].checked = node.state;
		},

	setSubTreeState :
		function(node, state) {
			var a = document.getElementById("s" + node.id);
			var tr = document.getElementById("t" + node.id);
			if(state) {
				a.childNodes[0].style.display = "none";
				a.childNodes[1].style.display = "";
			} else {
				a.childNodes[0].style.display = "";
				a.childNodes[1].style.display = "none";
			}
			tr.style.display = a.childNodes[1].style.display;
		},

	signClicked :
		function(a, node) {
			var opened = a.childNodes[0].style.display == "none";
			this.setSubTreeState(node, !opened);
		}
}