var ctrl = true;

function pageClicked() {
	this.sel.style.visibility = this.checked ? "visible" : "";
	this.sel.disabled = !this.checked;
	if(ctrl && event.ctrlKey) {
		ctrl = false;
		var tr = this.parentNode.parentNode.nextSibling;
		while(tr && tr.tagName == "TR") {
			var ch = tr.childNodes[1].childNodes[0];
			if(ch.l > this.l) {
				if(ch.checked != this.checked)
					ch.click();
				tr = tr.nextSibling;
			} else
				tr = null;
		}
		ctrl = true;
	}
}

function selectBlock(blockName) {
	var selects = document.getElementById("sitemap").getElementsByTagName("select");
	for(var i = 0; i < selects.length; i++) 
		if(!selects[i].disabled) {
			var s = selects[i];
			for(var j = 0; j < s.options.length; j++)
				if(s.options[j].innerHTML == blockName) {
					s.options[j].selected = true;
					break;
				}
		}
}

function initUses() {
	var pages = document.getElementById("pages").getElementsByTagName("LI");
	var table = document.getElementById("sitemap");
	var tbody = table.childNodes[1];
	var trow = tbody.childNodes[0];
	for(var i = 0, id = null; i < pages.length; i++) 
		if(pages[i].i) {
			var row = trow.cloneNode(true), td1 = row.childNodes[0], td2 = row.childNodes[1], sel = td1.childNodes[0];
			el = pages[i];
			id = parseInt(el.i); aId = Math.abs(id);
			td2.innerHTML = "<input type='checkbox' name='page[]' value='" + aId + "' id='ex" + aId + "' align='absmiddle'> <label for='ex" + aId + "'>" + el.innerHTML + "</label>";
			row.className = "r" + i % 2;
			td2.style.paddingLeft = el.l * 20 + "px";
			tbody.appendChild(row);
			
			sel.name = "block[" + aId + "]";
			
			if(el.b && el.b.substr(0, 1) == "@")
				for(var j = 0; j < sel.options.length; j++)
					if(sel.options[j].value == el.b) {
						sel.options[j].selected = true;
						break;
					}
			for(var j = 0, b = blocks[el.a]; j < b.length; j++) {
				var opt = new Option();
				if(b[j].substr(0, 1) == "*") {
					opt.innerHTML = b[j].substr(1);
					opt.className = "stub";
				} else
					opt.innerHTML = b[j];
				opt.value = opt.innerHTML;
				if(id < 0 && opt.value == el.b)
					opt.selected = true;
				sel.appendChild(opt);
			}
			
			var ch = document.getElementById("ex" + aId);
			ch.sel = sel;
			ch.l = el.l;
			ch.onclick = pageClicked;
			if(id < 0)
				ch.click();
		}
	trow.removeNode(true);
	document.getElementById("pages").removeNode(true); 
}