// Templates
function chk(obj){
	chk_(obj);
	if(ch_doc < 1) {
		disableButton("btn_remove",imghome+'/tbar/remove_.gif');
		disableButton("btn_editsec",imghome+'/tbar/editsec_.gif');
	} else {
		disableButton("btn_remove",false);
		disableButton("btn_editsec",false);
	}
}
function remove()
{
	if(ch_doc<1) return;
	if(mopen(wchome + "/confirm.php?q=" + encodeURIComponent(REMOVE_SELECTED_TEMPLATES_Q) + "&skin=" + skin, 350, 170))
		$("form[name='f_doc']").submit();
}
function createtpl()
{
	wxyopen(wchome+"/templates/edit"+(curnode=='page'?'page':'')+".php?id=new&dstype="+nodetype+"&type="+curnode,670,420);
}
function edit_security() {
	if(ch_doc > 0) {
		wxyopen(wchome+'/security/edittpl.php?id=' + getSelectedDocs(), 500 + (ch_doc > 1 ? 70 : 0), 420);
	}
}
function viewDefaultTpl(type) {
	wxyopen(wchome + '/templates/viewtpl.php?type=' + type, 778, 600);
}