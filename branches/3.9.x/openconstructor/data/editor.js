junk=true;
var rf=new Function('','return false');
function getRange(){return theHTML.document.selection.createRange();}
function excmd(cmd){getRange().execCommand(cmd);this.blur();theHTML.focus();}
function intoTags(tag){
	s=getRange();
	try{s.parentElement();
	}catch(RuntimeException){return false}
	if(s.parentElement().tagName!=tag)
	{if(s.text&&!theHTML.source)s.pasteHTML('<'+tag+'>'+s.text+'</'+tag+'>');}
	else {s.parentElement().removeNode();}
	theHTML.focus();
}
function editStyle(){
	try{tag=(getRange()).parentElement();
	}catch(RuntimeException){return false;}
	//if(tag.tagName=='BODY') return false;
	var d=new Date();
	style=new Array(false,tag.className,tag.style.cssText);
	while(!style[0])
		style=mopen('/openconstructor/editstyle.php?tag='+tag.tagName+'&class='+style[1]+'&style='+style[2]+'&j='+Math.ceil(d.getTime()/1000),570,400);
	if(style.length>1){tag.className=style[1];tag.style.cssText=style[2];}
	theHTML.focus();
}
function wxyopen(uri, x, y, id){
	if(!id)
		id = '_blank';
	if(id.substr(0,1) != '_')
		if(window.top._childs && window.top._childs[id])
			return window.top._childs[id].focus();
	if(!window.top._childs)
		window.top._childs = new Array();
	window.top._childs[id] = window.open(uri,'nw',"resizable=yes, scrollbars=yes, status=yes, height="+y+", width="+x);
	 
}
function insertImage()
{
	var d=new Date();
	image=mopen('/openconstructor/getimage/index.php?skin='+skin+'&j='+Math.ceil(d.getTime()/1000),600,500);
	if(image)
		getRange().execCommand('InsertImage',false,image);
}
function mopen(uri,w,h,args)
{
	return window.showModalDialog(uri,window.self,"center=yes;help=no;resizable=yes;status=no;"+(args?args:"")+"dialogWidth:"+w+"px; dialogHeight:"+h+"px");
}
//function mopen(uri,w,h){return window.showModalDialog(uri,window.self,"center=yes;help=no;resizable=yes;status=no;dialogWidth:"+w+"px; dialogHeight:"+h+"px");}
function editsource()
{
	if(!theHTML.source){
		theHTML.document.body.oldfont=theHTML.document.body.style.fontFamily;
		theHTML.document.body.oldsize=theHTML.document.body.style.fontSize;
		theHTML.document.body.style.fontFamily='Courier New';
		theHTML.document.body.style.fontSize='13px';
		theHTML.document.body.innerText=theHTML.document.body.innerHTML;
		theHTML.document.body.oldBorder=theHTML.document.body.style.borderColor;
		theHTML.document.body.style.borderColor='red';
	}else{
		theHTML.document.body.style.fontFamily=theHTML.document.body.oldfont;
		theHTML.document.body.style.fontSize=theHTML.document.body.oldsize;
		theHTML.document.body.innerHTML=theHTML.document.body.innerText;
		theHTML.document.body.style.borderColor=theHTML.document.body.oldBorder;
	}
	theHTML.source=!theHTML.source;
}
var pi=new Array();
pi[1]=new Image;
pi[1].src='/openconstructor/i/default/e/save_.gif';
pi[2]=new Image;
pi[2].src='/openconstructor/i/default/e/border.gif';
function disableButton(b,img,src){
	if(!src){
		if((b.dLevel&&1)==(img&&1)) return;
		if(img)
			_disableButton(b,false);
		else
			_enableButton(b);
		return;
	}
	if((img&&1)==(src.disb&&1)) return;
	if(!img){
		_enableButton(b);
		if(src) src.disb=false;
	}
	else {
		_disableButton(b,!src.disb);
		src.disb=true;
	}
}
function _disableButton(but,f){
	if(!but.parentElement.href) return false;
	if(!but.dimg) but.dimg=but.src.substr(0,but.src.length-4)+'_'+but.src.substr(but.src.length-4);
	if(!but.dLevel) but.dLevel=0;
	if(f||but.dLevel<1) but.dLevel++;
	if(but.dLevel>1) return;
	but.dsrc=but.src;p=but.parentElement;p.donclick=p.onclick;p.className='disabled';
	but.src=but.dimg;p.onclick=rf;
}
function _enableButton(but){
	if(!but.dLevel||!but.parentElement.href) return false;
	but.dLevel--;
	if(but.dLevel>0) return;
	but.src=but.dsrc;p=but.parentElement;p.onclick=p.donclick;p.className='tool1';
}
function editProps(){
	try{tag=(getRange()).parentElement();
	}catch(RuntimeException){return false}
	//if(tag.tagName=='BODY') return false;
	var d=new Date();
	window.curTag=tag;
	props=mopen('/openconstructor/editprops.php?tag='+tag.tagName+'&j='+Math.ceil(d.getTime()/1000),570,420);
	theHTML.focus();
}
function removeCSS()
{
	body=theHTML.document.body;
	objCount=body.all.length;
	for(i=0;i<objCount;i++)
	{
		obj=body.all(i);
		if(obj.style.cssText) obj.style.cssText=null;
		obj.removeAttribute('className',0);
	}
}
function repairHRefs(objWin)
{
	if(host.indexOf('www.')==0)
		host=host.substr(4);
	a=objWin.document.links;
	for(i=0;i<a.length;i++){
		el=a.item(i);
		el.href=removeHost(el.href.toLowerCase());
	}
	a=objWin.document.images;
	for(i=0;i<a.length;i++){
		el=a.item(i);
		el.src=removeHost(el.src.toLowerCase());
	}
}
function removeHost(uri)
{
	if(uri.indexOf('http://'+host)==0||uri.indexOf('http://www.'+host)==0)
		return uri.substr(uri.indexOf('/',7));
	return uri;
}
function editTable(){
	tag=(getRange()).parentElement();
	while(tag.tagName!='TABLE'&&tag.tagName!='BODY') tag=tag.parentElement;
	if(tag.tagName=='BODY') return false;
	var d=new Date();
	window.curTblOrig=tag;
	window.curTbl=tag.cloneNode(true);
	if(mopen('/openconstructor/data/editor/table.php?prototype=&j='+Math.ceil(d.getTime()/1000),700,520)){
		tbl=tag.nextSibling;
		tag.removeNode(true);
		if(tbl.parentElement.tagName!='DIV'&&tbl.parentElement.style.width!='100%'){
			d=tbl.applyElement(theHTML.document.createElement('div'),"outside");
			d.style.width="100%";
		}
	}
	window.curTbl.removeNode(true);
	theHTML.focus();
}
function insertHR()
{
	s=getRange();
	if(!s.text&&!theHTML.source)
		s.pasteHTML('<img src="/openconstructor/i/1x1.gif" hspace=0 vspace=0 class="hline">');
	theHTML.focus();
}
