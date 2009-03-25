try {
	tmpimg=new Image;tmpimg.src=imghome+'/tbar/moveobject.gif';
	tmpimg1=new Image;tmpimg1.src=imghome+'/tbar/publish.gif';
	tmpimg2=new Image;tmpimg2.src=imghome+'/tbar/unpublish.gif';
	tmpimg3=new Image;tmpimg3.src=imghome+'/tbar/remove.gif';
} catch(e) {}
var lastChecked = single = 0;

Array.prototype.find = function(searchStr) {
	var returnArray = false;
	for (i=0; i<this.length; i++) {
		if (typeof(searchStr) == 'function') {
			if (searchStr.test(this[i])) {
				if (!returnArray) { returnArray = [] }
				returnArray.push(i);
			}
		} else {
			if (this[i]===searchStr) {
				if (!returnArray) { returnArray = [] }
				returnArray.push(i);
			}
		}
	}
	return returnArray;
}

function nodeChecked(img) {
	var state = img.attr('state');
	var cont = img.parent();
	if(state != 0)
		setNodeState(img.attr('index'), 0);
	else
		setNodeState(img.attr('index'), 1);
}
function setNodeState(index, state) {
	var img = $('.multiple img[index='+index+']');
	if(img.attr('state') != state) {
		img.attr('state', state);
		highlightNode(index);
	}
}
function highlightNode(i) {
	var img = $('.multiple img[index='+i+']');
	if(!img) return;
	img.removeClass();
	img.addClass('checkbox'+img.attr('state'));
}
function applyNodeFilter() {
	var selected = getSelectedNodes();
	if(!selected.length)
		selected[0] = -1;
	window.location.href = window.location.pathname + "?node=" + selected.join(',');
}
function getSelectedNodes() {
	selected = new Array();
	var imgs = $('.multiple img');
	for(i=0,j=0;i<imgs.length;i++){
		if(imgs[i].getAttribute('state')!="0"){
			selected[j] = imgs[i].getAttribute('index');
			j++;
		}
	}
	
	return selected;
}
function remove()
{
	if(ch_doc == 0) {
		if(curnode == 1) return;
		if(mopen(wchome + "/confirm.php?q=" + encodeURIComponent(REMOVE_NODE_Q) + "&skin=" + skin,350,170))
			if(mopen(wchome+"/confirm.php?q=" + encodeURIComponent("<span style='color:red'><b>" + SURE_REMOVE_NODE_Q + "</b></span>") + "&skin="+skin,350,190))
				$('form[name=f_remove]').submit();
	} else {
//		$('form[name=f_doc]').attr('action', wchome + '/data/hybrid/i_hybrid.php');
		if(mopen(wchome+"/confirm.php?q=" + encodeURIComponent(REMOVE_SELECTED_DOCUMENTS_Q) + "&skin="+skin,350,170))
			$('form[name=f_doc]').submit();
	}
}
function publish_docs(status)
{
	if(!(ch_doc>0)) return;
//	$('form[name=f_doc]').attr('action',wchome + '/data/hybrid/i_hybrid.php');
	if(status){
		if(mopen(wchome+"/confirm.php?q=" + encodeURIComponent(PUBLISH_SELECTED_DOCUMENTS_Q) + "&skin="+skin,350,170))
			$('form[name=f_doc] input[name=action]').attr('value','publish_documents');
		else return;
	}else{
		if(mopen(wchome+"/confirm.php?q=" + encodeURIComponent(UNPUBLISH_SELECTED_DOCUMENTS_Q) + "&skin="+skin,350,170))
			$('form[name=f_doc] input[name=action]').attr('value','unpublish_documents');
		else return;
	}
	$('form[name=f_doc]').submit();
}
function chk(obj){
	chk_(obj)
	if(ch_doc < 1){
		disableButton('btn_publish',imghome+'/tbar/publish_.gif');
		disableButton('btn_unpublish',imghome+'/tbar/unpublish_.gif');
		if(!rootNode)
			disableButton('btn_editsec', imghome+'/tbar/editsec_.gif');
		if(curTab == 'browse')
			disableButton('btn_remove', imghome + '/tbar/remove_.gif');
	} else {
		disableButton('btn_publish', false);
		disableButton('btn_unpublish', false);
		disableButton('btn_editsec', false);
		if(curTab == 'browse')
			disableButton('btn_remove', false);
	}
}
function moveUp() {
	$('form[name=f_remove] input[name=action]').attr('value','move_up');
	$('form[name=f_remove]').submit();
}
function moveDown() {
	$('form[name=f_remove] input[name=action]').attr('value','move_down');
	$('form[name=f_remove]').submit();
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