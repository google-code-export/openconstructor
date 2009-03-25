try {
	var pri=new Array();
	pri[1] = new Image; pri[1].src = imghome + '/t/f.gif';
	pri[2] = new Image; pri[2].src = imghome + '/t/f_.gif';
	pri[3] = new Image; pri[3].src = imghome + '/t/minus.gif';
	pri[4] = new Image; pri[4].src = imghome + '/t/plus.gif';
	pri[5] = new Image; pri[5].src = imghome + '/tbar/border.gif';
	if(imghome.indexOf('/metallic/') != -1) {
		pri[6] = new Image; pri[6].src = imghome + '/vbkmrk_state_none.gif';;
		pri[7] = new Image; pri[7].src = imghome + '/vbkmrk_state_inline.gif';
	} else if(imghome.indexOf('/classic/') != -1) {
		pri[6] = new Image; pri[6].src = imghome + '/vbkmrk.gif';
		pri[7] = new Image; pri[7].src = imghome + '/vbkmrk_.gif';
	}
	pri[8] = new Image; pri[8].src = imghome + '/tbar/border_.gif';
} catch(RuntimeException) {
}
var ch_doc=0;
var curdd=null;
function node(id) {
	var cell = document.getElementById("tn" + id);
	var folder = cell.nextSibling.firstChild.firstChild.firstChild.firstChild;
	var children = cell.nextSibling.firstChild.lastChild;
	if(cell.className == "co") {
		cell.className = "cc";
		folder.className = "fc";
		children.className = "closed";
	} else {
		cell.className = "co";
		folder.className = "fo";
		children.className = "";
	}
}
function swapSrc(img){
	if(img.noswap) return;
	if(!img.ssrc){
		img.ssrc=img.src.substr(0,img.src.length-4);
		if(img.ssrc.charAt(img.ssrc.length-1)=='_')
			img.ssrc=img.ssrc.substr(0,img.ssrc.length-1)+'.gif';
		else
			img.ssrc=img.ssrc+'_.gif';
	}
	t=img.src;
	img.src=img.ssrc;
	img.ssrc=t;
}
function switch_user() {
	user = window.showModalDialog(wchome + '/switchuser.php','',"center=yes;help=no;resizable=no;status=no;dialogHeight:200px;dialogWidth:290px");
	if(user) {
		var f = document.createElement("FORM");
		f.style.display = "none";
		f.action = wchome + "/i_login.php";
		f.method = "post";
		var login = document.createElement("input"), pwd = login.cloneNode(), next = login.cloneNode();
		login.type = next.type = "hidden";
		login.name = 'login';
		login.value = user[0];
		pwd.type = "password";
		pwd.name = 'password';
		pwd.value = user[1];
		next.name = 'next';
		next.value = location.pathname + location.search;
		document.body.appendChild(f);
		f.appendChild(login);
		f.appendChild(pwd);
		f.appendChild(next);
		f.submit();
	}
}
function switchpanel(uri)
{
	if(view.style.display!='none')
		view.style.display='none';
	else
		view.style.display='inline';
	setCookie('panelstate',view.style.display,uri);
}
function metallic_swp(uri){
	switchpanel(uri);
	document.getElementById('bk_arrow').src=imghome+'/vbkmrk_state_'+view.style.display+'.gif';
}
function setCookie(argName,argValue,argPath){
	document.cookie=argName+'='+argValue+'; path='+argPath;
}
function mopen(uri,w,h,args)
{
	return window.showModalDialog(uri,window.self,"center=yes;help=no;resizable=yes;status=no;"+(args?args:"")+"dialogWidth:"+w+"px; dialogHeight:"+h+"px");
}
function wxyopen(uri,x,y)
{
	window.open(uri,'',"resizable=yes, scrollbars=yes, status=yes" + (y > 0 ? ", height=" + y : "") + (x > 0 ? ", width=" + x : ""));
}
function disableButton(elm, img) {
	var b = document.getElementById(elm);
	if(!b.parentNode.href) return false;
	if(img && !b.dbtn) {
		b.dbtn = true;
		b.dsrc = b.src;
		p = b.parentNode;
		p.donclick = p.onclick;
		p.dclassName = p.className;
		p.className = 'disabled';
		b.src = img;
		p.onclick = function() {return false;};
	} else if(!img && b.dbtn) {
		b.dbtn = false;
		b.src = b.dsrc;
		p = b.parentNode;
		p.onclick = p.donclick;
		p.className = p.dclassName;
	}
}


/*
$(document).ready(function(){
	$("input#checkall").click(function(){		var elm = $(this);
		var all_elm = $("input[name='ids[]']");
		if(!all_elm) return;
		count = 0;
		all_elm.each(function(){			if(elm.attr('checked')){				count++;
				this.checked = true;
				$(this).parent().parent().addClass("brown");
			}
			else{
				this.checked = false;
				$(this).parent().parent().removeClass("brown");
			}
		});
		//count = all_elm.get().length;
		alert(count);
		$(this).get(0).blur();
	});

	$("input[name='ids[]']").click(function(){
		if($(this).attr('checked'))
			$(this).parent().parent().addClass("brown");
		else{			$("input#checkall").attr('checked',false);
			$(this).parent().parent().removeClass("brown");
		}
		$(this).get(0).blur();
	});
});*/

function chk_(obj){
	if(obj.checked){
		ch_doc++;
		$("#r_"+obj.value).addClass("brown");
	}
	else{
		ch_doc--;
		$("input#checkall").attr('checked',false);
		$("#r_"+obj.value).removeClass("brown");
	}
}
function doall(){
	var elm = $("input#checkall");
	var all_elm = $("input[name='ids[]']");
	if(!all_elm) return;
	all_elm.each(function(){
		if(elm.attr('checked')){
			if (!this.checked){				this.checked = true;
				chk(this);
			}
		}
		else{
			if (this.checked){
				this.checked = false;
				chk(this);
			}
		}
	});
}

function getSelectedDocs() {
	var result = new Array(), docs = $("form[name='f_doc'] input[name='ids[]']"), l = 0;
	if(!docs) return;
	if(docs.length>0)
		for(i=0; i < docs.length; i++){
			if(docs[i].checked)
				result[l++] = docs[i].value;
	} else if(docs.checked)
		result[l++] = docs.value;
	return result;
}
function viewSource(){
//	alert(document.location.href);
	if(!document.getElementById('docSrc')){
		sSrc=document.childNodes(0).innerHTML;
		document.childNodes(0).childNodes(1).innerHTML='<textarea id="docSrc" style="width:100%;height:100%" wrap="off"></textarea>';
	} else return;
	document.getElementById('docSrc').innerText=sSrc;
	document.getElementById('docSrc').scrollIntoView();
	document.getElementById('docSrc').focus();
}
function aboutOpenConstructor(){
	mopen(wchome+'/about.php?j='+Math.ceil((new Date).getTime()/1000),490,560,"scroll=no;");
}
function techSupport(){
	wxyopen(wchome+'/support.php',440,400);
}
function dropdown(dd){
	if(curdd) {
		dd_hide(curdd);
		return;
	}
	dd_show(dd);
}
function dd_hide(dd){
	if(!dd) return;
	dd.style.display='none';
	curdd=null;
}
function dd_show(dd){
	dd.style.display='inline';
	curdd=dd;
}
function dd_choose(ddid){
	var a=event.srcElement;
	if(a.tagName!='A')
		a=a.parentNode;
	if(a.tagName!='A')
		return false;
	var img=a.childNodes(0);
	window.setTimeout('dd_setdef("'+ddid+'","'+img.name+'")',10);
	dd_hide(curdd);
	return false;
}
function dd_setdef(ddid,rep){
	def=document.getElementById('btn_def_'+ddid);
	rep=document.getElementById(rep);
	a=rep.parentNode;
	def.src=rep.src;
	def.alt=rep.alt;
	def.aliasof='r_'+rep.name;
	def.parentNode.href=a.href
	setCookie('def_'+ddid,rep.name.substr(4),cururi);
}
function dd_i(){
	el=event.srcElement;
	if(curdd){
		for(var i=0;i<6;i++)
			if(el==null||el.tagName=='BODY')
				break;
			else if(el==curdd||el.id==curdd.id+'_')
				return;
			else
				el=el.parentNode;
		dd_hide(curdd);
	}
}
function setVar(queryString, name, value) {
	var result = "?";
	if(queryString) {
		var f = queryString.substr(1).split('&');
		for(var i = 0; i < f.length; i++) {
			result += f[i].indexOf(name + "=") == 0 ? name + "=" + value : f[i];
			result += i + 1 == f.length ? '' : '&';
		}
	} else
		result += name + '=' + value;
	return result;
}

function context(a){
	return true;
}
function dump(obj,ret){
	var result="",s="";
	for(prop in obj){
		try {
			s=obj[prop];
		} catch(RuntimeException){
			s="Permission Denied";
		}
		result += prop + " : " + s + (ret ? "\t" : "\n" );
	}
	if(ret)
		return result;
	showinnewwindow(result);
}
function showinnewwindow(text){
	dumpwin=window.open('','',"resizable=yes, scrollbars=yes, status=yes, height=, width=");
	dumpwin.document.writeln('<html><head><title>Dumping...</title></head><body><pre style="padding:20px;"></pre></body></html>');
	dumpwin.document.close();
	dumpwin.document.body.firstChild.innerText = text;
}
