var ctrl = true;

function pageClicked(e) {	var id = this.value;
	var elm = $("select[name='block[" + id + "]']").get(0);
	elm.style.visibility = this.checked ? "visible" : "";
	elm.disabled = !this.checked;
	var evt = e || window.event;
	if(ctrl && evt.ctrlKey) {
		ctrl = false;
		var tr = this.parentNode.parentNode.nextSibling;
		while(tr && tr.tagName == "TR") {
			var ch = tr.getElementsByTagName("input")[0];
			if(map[ch.value][1] > map[id][1]) {
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
				if(s.options[j].innerHTML == blockName) {					s.options[j].selected = true;
					break;
				}
		}
}


function initUses() {
	var table = document.getElementById("sitemap");
	var tbody = table.getElementsByTagName("tbody")[0];
	var trow = tbody.getElementsByTagName("tr")[0];
	var i = 0;
	for(var id in map){
		var row = trow.cloneNode(true), cells = row.getElementsByTagName("td"), td1 = cells[0], td2 = cells[1];
		var sel = td1.getElementsByTagName("select")[0];
		var block = blocks[map[id][3]];
		td2.innerHTML = "<input type='checkbox' name='page[]' value='" + id + "' id='ex" + id + "'> <label for='ex" + id + "'>" + map[id][4] + "</label>";
		row.className = "r" + i % 2;
		td2.style.paddingLeft = map[id][1] * 20 + "px";
		tbody.appendChild(row);

		sel.name = "block[" + id + "]";

		if(map[id][2] && map[id][2].substr(0, 1) == "@")
			for(var j = 0; j < sel.options.length; j++)
				if(sel.options[j].value == map[id][2]) {
					sel.options[j].selected = true;
					break;
				}

		for(var j = 0; j < block.length; j++) {
			var opt = new Option();
			if(block[j].substr(0, 1) == "*") {
				opt.innerHTML = block[j].substr(1);
				opt.className = "stub";
			} else
				opt.innerHTML = block[j];
			opt.value = opt.innerHTML;
			if(map[id][0] == 0 && opt.value == map[id][2])
				opt.selected = true;
			sel.appendChild(opt);
		}

		var ch = document.getElementById("ex" + id);
		ch.onclick = pageClicked;
		if(map[id][0] == 0)
			ch.click();
		i++;
	}
	tbody.removeChild(trow);
}