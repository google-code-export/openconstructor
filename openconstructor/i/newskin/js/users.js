function ep(dsId, profileId) {
	if(dsId > 0 && profileId > 0)
		wxyopen(wchome + '/data/hybrid/edit.php?ds_id=' + dsId + '&id=' + profileId, 788, 520);
}
function remove()
{
	if(ch_doc>0)
	{
		if(mopen(wchome + "/confirm.php?q=" + encodeURIComponent(REMOVE_SELECTED_USERS_Q) + "&skin=" + skin, 350, 170))
			$("form[name='f_doc']").submit();
	} else {
		if(curnode==0) return;
		if(mopen(wchome+"/confirm.php?q=" + encodeURIComponent(REMOVE_USERGROUP_Q) + "&skin=" + skin, 350, 170))
			if(mopen(wchome+"/confirm.php?q=" + encodeURIComponent("<span style='color:red'><b>" + SURE_REMOVE_USERGROUP_Q + "</b></span>") + "&skin=" + skin, 350, 190))
				$("form[name='f_remove']").submit();
	}
}
function chk(obj)
{
	chk_(obj);
	if(ch_doc < 1) {		disableButton("btn_removemember",imghome+'/tbar/removemember_.gif');
		disableButton("btn_enableuser",imghome+'/tbar/enableuser_.gif');
		disableButton("btn_disableuser",imghome+'/tbar/disableuser_.gif');
		if(builtIn)
			disableButton("btn_remove",imghome+'/tbar/remove_.gif');
	} else {
		disableButton("btn_remove",false);
		disableButton("btn_removemember",false);
		disableButton("btn_enableuser",false);
		disableButton("btn_disableuser",false);
	}
}
function edit_security() {
	if(ch_doc == 0) {
		if(!curnode) return;
		wxyopen(wchome+'/security/editgroup.php?id=' + curnode, 500, 540);
	} else {
		wxyopen(wchome+'/security/edituser.php?id=' + getSelectedDocs(), 530 + (ch_doc > 1 ? 70 : 0), 530);
	}
}
function removeMembers() {
	if(ch_doc > 0) {
		if(mopen(wchome + "/confirm.php?q=" + encodeURIComponent(REMOVE_SELECTED_MEMBERS_Q) + "&skin=" + skin, 350, 200)) {
			$("form[name='f_doc'] input[name='action']").val('remove_member');
			$("form[name='f_doc']").submit();
		}
	}
}
function addmember() {
	var users = mopen(wchome + "/users/selectuser.php?type=multiple", 440, 520, "scroll=no;");
	if(users.length > 0) {
		var f = document.createElement("FORM");
		f.style.display = "none";
		f.action = wchome + "/users/i_users.php";
		f.method = "post";
		document.body.appendChild(f);
		f.action.value = 'add_member';
		var tpl = document.createElement("input"), action = tpl.cloneNode(true), group = tpl.cloneNode(true);
		action.type = "hidden"; action.name = 'action'; action.value = 'add_member';
		f.appendChild(action);
		group.type = "hidden"; group.name = 'group_id'; group.value = curnode;
		f.appendChild(group);
		tpl.name = 'ids[]';
		for(var i=0, usr = null; i < users.length; i++) {
			usr = tpl.cloneNode(true);
			usr.value = users[i][0];
			f.appendChild(usr);
		}
		f.submit();
	}
}
function setUserState(state) {
	if(!(ch_doc>0)) return;
	if(state){
		if(mopen(wchome + "/confirm.php?q=" + encodeURIComponent(ENABLE_SELECTED_USERS_Q) + "&skin=" + skin, 350, 170)){
			$("form[name='f_doc'] input[name='action']").val('enable_users');
		}
		else return;
	}else{
		if(mopen(wchome + "/confirm.php?q=" + encodeURIComponent(DISABLE_SELECTED_USERS_Q) + "&skin=" + skin, 350, 170)){
			$("form[name='f_doc'] input[name='action']").val('disable_users');
		}
		else return;
	}
	$("form[name='f_doc']").submit();
}
