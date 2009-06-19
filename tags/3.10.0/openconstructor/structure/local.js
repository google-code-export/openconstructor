// Structure
function blockChanged(objId) {
	var ch = document.getElementById("block" + objId);
	var img = document.getElementById("img" + objId);
	var isStub = isBlockStub(ch.old);
	for(var i = 0, s = null, o = null; i < blocks.length; i++) {
		s = document.getElementById("block" + blocks[i]);
		if(s != ch) {
			if(ch.old != '+' && ch.old != 'PRE' && ch.old != 'POST' && ch.old.substr(0, 1) != '@') {
				o = new Option();
				o.value = ch.old;
				if(isStub)
					o.className = "stub";
				var j = 0;
				for(j = 0; j < s.options.length; j++) {
					if(s.options[j].value == '+' || s.options[j].value == 'PRE' || s.options[j].value.substr(0, 1) == '@')
						continue;
					if(s.options[j].value > o.value || s.options[j].value == 'POST') {
						s.insertBefore(o, s.options[j]);
						break;
					}
				}
				if(j == s.options.length)
					s.appendChild(o);
				o.innerHTML = o.value;
			}
			if(ch.options[ch.selectedIndex].value != '+'
				&& ch.options[ch.selectedIndex].value != 'PRE'
				&& ch.options[ch.selectedIndex].value != 'POST'
				&& ch.options[ch.selectedIndex].value.substr(0, 1) != '@'
				) {
				for(var j = 0; j < s.options.length; j++)
					if(s.options[j].value == ch.options[ch.selectedIndex].value)
						s.options[j].removeNode(true);
			}
		}
	}
	ch.old = ch.options[ch.selectedIndex].value;
	changeBlockDecorator(document.getElementById("img" + objId), ch.old);
}
function changeBlockDecorator(img, value) {
	var dec = 'play', isStub = false;
	if(value == '+')
		dec = 'stop';
	else
		isStub = isBlockStub(value);
	img.src = img.src.substr(0, img.src.lastIndexOf("/") + 1) + dec + '.gif';
	img.style.filter = isStub ? 'alpha(opacity=50)' : null;
}
function isBlockStub(block) {
	for(var i = 0; i < stubs.length; i++)
		if(stubs[i] == block)
			return true;
	return false;
}
function chk(obj) {
	chk_(obj);
	if(!isVersion)
		return;
	if(ch_doc < 1)
		disableButton(btn_remove, imghome + '/tool/remove_.gif');
	else
		disableButton(btn_remove,false);
}
function remove(id) {
	if(ch_doc == 0) {
//		if(isVersion||id==0){
		if(id == 1) {
			alert(YOU_CANNOT_REMOVE_SITEROOT_W);
			return;
		}
		if(mopen(wchome + "/confirm.php?q=" + encodeURIComponent(REMOVE_PAGE_Q) + "&skin=" + skin, 350, 170))
			if(mopen(wchome + "/confirm.php?q=" + encodeURIComponent("<span style='color:red'><b>" + SURE_REMOVE_PAGE_Q + "</b></span>") + "&skin=" + skin, 350, 170)) {
				if(!(id == undefined))
					f_remove.page_id = id;
				f_remove.submit();
			}
	}
	else if(mopen(wchome + "/confirm.php?q=" + encodeURIComponent(EXCLUDE_SELECTED_OBJECTS_Q) + "&skin=" + skin, 350, 170)) {
		f_doc.action.value = 'remove_objects';
		f_doc.submit();
	}
}
function context(a){
	var e = a.href.lastIndexOf("node=");
	if(0 && (e+5 < a.href.length) && (!isNaN(parseInt(a.href.substr(e+5))))){
		return false;
	}
	return true;
}
function edit_security() {
	if(ch_doc == 0) {
		wxyopen(wchome+'/security/editpage.php?id=' + curnode, 530, 560);
	} else {
		wxyopen(wchome+'/security/editobj.php?id=' + getSelectedDocs(), 500 + (ch_doc > 1 ? 70 : 0), 460);
	}
}
function moveUp() {
	f_doc.all('action').value='move_up';
	f_doc.submit();
}
function moveDown() {
	f_doc.all('action').value='move_down';
	f_doc.submit();
}
function setPageState(state) {
	f_doc.all('action').value = state ? 'publish_page' : 'unpublish_page';
	f_doc.submit();
}
function republishSubTree() {
	f_doc.all('action').value = 'republish_page';
	f_doc.submit();
}
function editTpl(tplId) {
	wxyopen(wchome + '/templates/editpage.php?dstype=site&id=' + tplId, 760);
}