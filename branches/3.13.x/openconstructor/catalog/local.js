try {
	tmpimg=new Image;tmpimg.src=imghome+'/tool/moveobject.gif';
	tmpimg1=new Image;tmpimg1.src=imghome+'/tool/publish.gif';
	tmpimg2=new Image;tmpimg2.src=imghome+'/tool/unpublish.gif';
	tmpimg3=new Image;tmpimg3.src=imghome+'/tool/remove.gif';
} catch(e) {}
var lastChecked = single = 0;
function nodeChecked(img) {
	var index = img.getAttribute('index');
	if(tree[index].state != 0)
		setNodeState(index, 0);
	else {
		setNodeState(index, 1);
		if(document.getElementById("tn" + tree[index].id) && document.getElementById("tn" + tree[index].id).className == 'cc')
			node(tree[index].id);
	}
}
function setNodeState(index, state) {
	if(tree[index] && tree[index].state != state) {
		tree[index].state = state;
		highlightNode(index);
	}
}
function highlightNode(i) {
	if(!tree[i]) return;
	var img = document.getElementById('mst'+tree[i].id), el = img.parentNode.nextSibling;
	if(i != tree.root && !tree[i].isRoot)
		img.className = 'checkbox' + tree[i].state;
	el.style.backgroundColor = tree[i].state == 1 ? "ECE9D8" : "";
}
function applyNodeFilter() {
	var selected = getSelectedNodes();
	if(!selected.length)
		selected[0] = -1;
	window.location.href = window.location.pathname + "?node=" + selected.join(',');
}
function getSelectedNodes() {
	var selected = new Array(), level = 0;
	for(var i = 1; i < tree.length; i++)
		if(tree[i] && tree[i].state == 1 && !tree[i].isRoot)
			selected[selected.length] = tree[i].id;
	return selected;
}
function remove()
{
	if(ch_doc == 0) {
		if(curnode == 1) return;
		if(mopen(wchome + "/confirm.php?q=" + encodeURIComponent(REMOVE_NODE_Q) + "&skin=" + skin,350,170))
			if(mopen(wchome+"/confirm.php?q=" + encodeURIComponent("<span style='color:red'><b>" + SURE_REMOVE_NODE_Q + "</b></span>") + "&skin="+skin,350,190))
				f_remove.submit();
	} else {
		f_doc.attributes['action'].nodeValue = wchome + '/data/hybrid/i_hybrid.php';
		if(mopen(wchome+"/confirm.php?q=" + encodeURIComponent(REMOVE_SELECTED_DOCUMENTS_Q) + "&skin="+skin,350,170))
			f_doc.submit();
	}
}
function publish_docs(status)
{
	if(!(ch_doc>0)) return;
	f_doc.attributes['action'].nodeValue = wchome + '/data/hybrid/i_hybrid.php';
	if(status){
		if(mopen(wchome+"/confirm.php?q=" + encodeURIComponent(PUBLISH_SELECTED_DOCUMENTS_Q) + "&skin="+skin,350,170))
			f_doc.all('action').value='publish_documents';
		else return;
	}else{
		if(mopen(wchome+"/confirm.php?q=" + encodeURIComponent(UNPUBLISH_SELECTED_DOCUMENTS_Q) + "&skin="+skin,350,170))
			f_doc.all('action').value='unpublish_documents';
		else return;
	}
	f_doc.submit();
}
function chk(obj){
	chk_(obj)
	if(ch_doc < 1){
		disableButton(btn_publish,imghome+'/tool/publish_.gif');
		disableButton(btn_unpublish,imghome+'/tool/unpublish_.gif');
		if(!rootNode)
			disableButton(btn_editsec, imghome+'/tool/editsec_.gif');
		if(curTab == 'browse')
			disableButton(btn_remove, imghome + '/tool/remove_.gif');
	} else {
		disableButton(btn_publish, false);
		disableButton(btn_unpublish, false);
		disableButton(btn_editsec, false);
		if(curTab == 'browse')
			disableButton(btn_remove, false);
	}
}
function moveUp() {
	f_remove.all('action').value='move_up';
	f_remove.submit();
}
function moveDown() {
	f_remove.all('action').value='move_down';
	f_remove.submit();
}
function create_record()
{
	if(curDS) {
		wxyopen(wchome+'/data/hybrid/edit.php?' + preset + '&id=new&ds_id=' + curDS, 788, 520);
	}
}
function edit_security() {
	if(ch_doc == 0) {
		if(curnode)
			wxyopen(wchome+'/security/edittree.php?id=' + rootNode, 500, 420);
	} else {
		wxyopen(wchome+'/security/editdoc.php?ds_id=' + curDS + '&id=' + getSelectedDocs(), 500, 200);
	}
}