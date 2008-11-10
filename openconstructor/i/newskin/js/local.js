// Structure
function blockChanged(objId) {
	var ch = document.getElementById("block" + objId);
	var img = document.getElementById("img" + objId);
	var isStub = isBlockStub(ch.old);
	for(var i = 0, s = null, o = null; i < blocks.length; i++) {
		s = document.getElementById("block" + blocks[i]);
		if(s != ch) {
			if(ch.getAttribute('old') != '+' && ch.getAttribute('old') != 'PRE' && ch.getAttribute('old') != 'POST' && ch.getAttribute('old').substr(0, 1) != '@') {
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
				for(var j = 0; j < s.options.length; j++){
					if(s.options[j].value == ch.options[ch.selectedIndex].value)						s.removeChild(s.options[j]);//.removeNode(true);
						}
			}
		}
	}
	ch.setAttribute('old', ch.options[ch.selectedIndex].value);
	changeBlockDecorator(objId, ch.getAttribute('old'));
}
function changeBlockDecorator(img_id, value) {
	var dec = 'play', isStub = false;
	var img = document.getElementById("img"+img_id)
	if(value == '+')
		dec = 'stop';
	else
		isStub = isBlockStub(value);
	img.src = img.src.substr(0, img.src.lastIndexOf("/") + 1) + dec + '.gif';
	if(isStub)
		$("#img"+img_id).animate({opacity: '0.5'});
}
function isBlockStub(block) {
	for(var i = 0; i < stubs.length; i++)
		if(stubs[i] == block)
			return true;
	return false;
}

function chk(obj){
	chk_(obj);
	if(!isVersion) return;
	if(ch_doc < 1)
		disableButton("btn_remove",imghome + '/tbar/remove_.gif');
	else
		disableButton("btn_remove",false);
}

function context(a){
	var e = a.href.lastIndexOf("node=");
	if(0 && (e+5 < a.href.length) && (!isNaN(parseInt(a.href.substr(e+5))))){
		return false;
	}
	return true;
}

function editTpl(tplId) {
	wxyopen(wchome + '/templates/editpage.php?dstype=site&id=' + tplId, 760);
}

function save() {
	$("form[name='f_doc'] input[name='tplId']").val($("#tplId").val());
	$("form[name='f_doc']").submit();
}

function addObject(node) {
	wxyopen(wchome+"/structure/add_object.php?node="+node,700,450);
}

function createPage(node) {
	wxyopen(wchome+"/structure/create_page.php?node="+node,700,370);
}

function movePage(node) {
	wxyopen(wchome+"/structure/move_page.php?node="+node,700,320);
}

function moveUp() {
	$("form[name='f_doc'] input[name='action']").val('move_up');
	$("form[name='f_doc']").submit();
}

function moveDown() {
	$("form[name='f_doc'] input[name='action']").val('move_down');
	$("form[name='f_doc']").submit();
}

function setPageState(state) {
	var val = 'unpublish_page';
	if(state)
		val = 'publish_page';
	$("form[name='f_doc'] input[name='action']").val(val);
	$("form[name='f_doc']").submit();
}

function republishSubTree() {
	$("form[name='f_doc'] input[name='action']").val('republish_page');
	$("form[name='f_doc']").submit();
}

function editPage(node) {
	wxyopen(wchome+"/structure/edit_page.php?node="+node,650);
}

function edit_security(node) {
	if(ch_doc == 0) {
		wxyopen(wchome+'/security/editpage.php?id=' + node, 530, 560);
	} else {
		wxyopen(wchome+'/security/editobj.php?id=' + getSelectedDocs(), 500 + (ch_doc > 1 ? 70 : 0), 460);
	}
}

function remove(id) {
	if(ch_doc == 0) {
		if(id == 1) {
			alert(YOU_CANNOT_REMOVE_SITEROOT_W);
			return;
		}
		if(mopen(wchome + "/confirm.php?q=" + encodeURIComponent(REMOVE_PAGE_Q) + "&skin=" + skin, 350, 170))
			if(mopen(wchome + "/confirm.php?q=" + encodeURIComponent("<span style='color:red'><b>" + SURE_REMOVE_PAGE_Q + "</b></span>") + "&skin=" + skin, 350, 170)) {
				if(!(id == undefined))
					$("input[name='page_id']").val(id);
				$("form[name='f_remove']").submit();
			}
	}
	else if(mopen(wchome + "/confirm.php?q=" + encodeURIComponent(EXCLUDE_SELECTED_OBJECTS_Q) + "&skin=" + skin, 350, 170)) {
		$("form[name='f_doc'] input[name='action']").val('remove_objects');
		$("form[name='f_doc']").submit();
	}
}
