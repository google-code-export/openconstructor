try {
	tmpimg=new Image;tmpimg.src=imghome+'/tbar/moveobject.gif';
	tmpimg1=new Image;tmpimg1.src=imghome+'/tbar/publish.gif';
	tmpimg2=new Image;tmpimg2.src=imghome+'/tbar/unpublish.gif';
	tmpimg3=new Image;tmpimg3.src=imghome+'/tbar/remove.gif';
} catch(RuntimeException) {
}
function remove()
{
	if(ch_doc==0)
	{
		if(isInternal) return;
		if(!curnode) return;
		if(mopen(wchome + "/confirm.php?q=" + encodeURIComponent(REMOVE_DS_Q) + "&skin=" + skin, 350, 170))
			if(mopen(wchome + "/confirm.php?q=" + encodeURIComponent("<span style='color:red'><b>" + SURE_REMOVE_DS_Q + "</b></span>") + "&skin=" + skin, 350, 170)){
				if(isLocked && !(isLocked && mopen(wchome + "/confirm.php?q=" + encodeURIComponent("<span style='color:red'><b>" + SURE_REMOVE_LOCKED_DS_Q + "</b></span>") + "&skin=" + skin, 190)))
					return;
				$("form[name='f_remove']").submit();;
			}
	}else{
		if(mopen(wchome+"/confirm.php?q=" + encodeURIComponent(REMOVE_SELECTED_DOCUMENTS_Q) + "&skin=" + skin,350,170))
			$("form[name='f_doc']").submit();
	}
}
function move_docs()
{
	if(!(ch_doc>0)) return;
	var d=new Date();
	result=mopen(wchome+'/data/move_doc.php?ds_type='+nodetype+'&ds_id='+curnode+"&j="+Math.ceil(d.getTime()/1000),450,270);
	if(!result) return;
	$("form[name='f_doc'] input[name='action']").attr('value', 'move_documents');
	$("form[name='f_doc'] input[name='dest_ds_id']").attr('value', result);
	$("form[name='f_doc']").submit();
}
function publish_docs(status)
{
	if(!(ch_doc>0)) return;
	if(status){
		if(mopen(wchome + "/confirm.php?q=" + encodeURIComponent(PUBLISH_SELECTED_DOCUMENTS_Q) + "&skin=" + skin, 350, 170))
			$("form[name='f_doc'] input[name='action']").attr('value', 'publish_documents');
		else return;
	}else{
		if(mopen(wchome + "/confirm.php?q=" + encodeURIComponent(UNPUBLISH_SELECTED_DOCUMENTS_Q) + "&skin=" + skin, 350, 170))
			$("form[name='f_doc'] input[name='action']").attr('value', 'unpublish_documents');
		else return;
	}
	$("form[name='f_doc']").submit();
}
function chk(obj){
	chk_(obj)
	if(ch_doc<1){
		disableButton("btn_moverecord",imghome+'/tbar/moverecord_.gif');
		disableButton("btn_publish",imghome+'/tbar/publish_.gif');
		disableButton("btn_unpublish",imghome+'/tbar/unpublish_.gif');
		if(isInternal)
			disableButton("btn_remove",imghome+'/tbar/remove_.gif');
	} else {
		disableButton("btn_moverecord",false);
		disableButton("btn_publish",false);
		disableButton("btn_unpublish",false);
		if(isInternal)
			disableButton("btn_remove",false);
	}
}
function reply(id){
	try{
	m=event.srcElement;
	if(m.m) return;
	a=document.all('r_'+id).cells(1).childNodes(0);
	event.srcElement.href+='?subject=RE: '+a.innerText;
	m.m=true;
	}catch(RuntimeException){return false;}
}
function create_record()
{
	if(!curnode) return;
	wxyopen(wchome+'/data/'+nodetype+'/edit.php?id=new&ds_id='+curnode,788,520);
}
function create_alias()
{
	if(!curnode) return;
	documents = mopen(wchome+'/data/create_alias.php?nodetype='+nodetype+'&ds_id='+curnode + '&type=multiple', 440, 520, "scroll=no;");
	if(documents.length > 0) {
		var f = document.createElement("FORM");
		f.style.display = "none";
		f.action = wchome + "/data/"+nodetype+"/i_"+nodetype+".php";
		f.method = "post";
		document.body.appendChild(f);
		f.action.value = 'create_alias';
		var tpl = document.createElement("input"), action = tpl.cloneNode(), ds_id = tpl.cloneNode();
		action.type = "hidden"; action.name = 'action'; action.value = 'create_alias';
		f.appendChild(action);
		ds_id.type = "hidden"; ds_id.name = 'ds_id'; ds_id.value = curnode;
		f.appendChild(ds_id);
		tpl.name = 'ids[]';
		for(var i=0, doc = null; i < documents.length; i++) {
			doc = tpl.cloneNode();
			doc.value = documents[i][0];
			f.appendChild(doc);
		}
		f.appendChild(tpl);
		f.submit();
	}
	
}
function edit_security() {
	if(ch_doc == 0) {
		if(!curnode) return;
		wxyopen(wchome+'/security/editds.php?id=' + curnode, 500, 520);
	} else {
		wxyopen(wchome+'/security/editdoc.php?ds_id=' + curnode + '&id=' + getSelectedDocs(), 500, 200);
	}
}
function ddChoose(id){
	if($('a.drop').attr('id') != id){
		def = $('a.drop');
		cur = $('a#'+id);
		def.attr({
			href: cur.attr('href'),
			id: cur.attr('id')
		});
		def.find('img:first').attr({
			src: cur.find('img').attr('src'),
			title: cur.find('img').attr('title'),
			alt: cur.find('img').attr('alt'),
			id: cur.find('img').attr('id')
		});
		$.cookie('def_bs', id);
	}
}
