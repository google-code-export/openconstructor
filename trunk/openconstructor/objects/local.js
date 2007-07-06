// Objects
function chk(obj)
{
	chk_(obj);
	if(ch_doc < 1) {
		disableButton(btn_remove,imghome+'/tool/remove_.gif');
		disableButton(btn_editsec,imghome+'/tool/editsec_.gif');
	} else {
		disableButton(btn_remove,false);
		disableButton(btn_editsec,false);
	}
}
function remove()
{
	if(ch_doc<1) return;
	if(mopen(wchome + "/confirm.php?q=" + encodeURIComponent(REMOVE_SELECTED_OBJECTS_Q) + "&skin=" + skin, 350, 170))
		f_doc.submit();
}
function createobject()
{
	wxyopen(wchome+"/objects/createobject.php?ds_type="+nodetype+"&obj_type="+curnode,670,400);
}
function edit_security() {
	if(ch_doc > 0) {
		wxyopen(wchome+'/security/editobj.php?id=' + getSelectedDocs(), 500 + (ch_doc > 1 ? 70 : 0), 450);
	}
}
