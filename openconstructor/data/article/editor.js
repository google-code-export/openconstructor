var menu=new Array, head=new Array, cont=new Array;
var curIndex=0, count=0;
var pages=-1;

function load(_pages)
{
	for(i=0;i<_pages+1;i++){
		addPage(document.all("theader"+i),document.all("thtml"+i));
		updateMenuItem(i);
	}
	disableButton(btn_rmvpage,true);
	setView(curIndex);
}

function saveState()
{
	if(curIndex == 0) {
		f.intro.value = content.document.body.innerHTML;
	}
	head[curIndex].value=document.all('hview').innerHTML;
	cont[curIndex].value=content.document.body.innerHTML;
}

function createPage(pageNum)
{
	addPage(newTextarea(),newTextarea());
	moveTo(pages,pageNum);
	switchTo(pageNum);
}

function addPage(th,tc)
{
	pages++;
	menu[pages]=pages>0?newItem(th.value?th.value:(pageText+pages)):newHead(th.value?th.value:headText);
	menu[pages].menuIndex=pages;
	head[pages]=th;
	disableButton(btn_saveart,!head[pages].value.match(gre),head[pages]);
	cont[pages]=tc;
	actionCheck();
}

function newTextarea()
{
	text=document.createElement('<textarea>');
	text.style.display='none';
	f.appendChild(text);
	return text;
}

function newItem(text)
{
	li=document.createElement('<li>');
	li.innerHTML='<input type="checkbox" name="ch" onclick="itemChecked(this.parentElement,this.checked)"><a href="#" onclick="switchTo(this.parentElement.menuIndex);return false" name="txt"></a>';
	setItemText(li,text);
	olMenu.appendChild(li);
	return li;
}

function newHead(text)
{
	h=document.createElement('<div>');
	h.innerHTML='<a href="#" onclick="switchTo(this.parentElement.menuIndex);return false" name="txt"></a>';
	h.title=headText;
	h.style.padding='11 0 0 0';
	setItemText(h,text);
	olMenu.parentElement.insertBefore(h,olMenu);
	return h;
}

function switchTo(pageNum)
{
	pageNum=parseInt(pageNum);
	if(content.source) {theHTML=content;editsource();}
	saveState();
	updateMenuItem(curIndex);
	setView(pageNum)
}

function setView(index)
{
	if(index!=0){
		document.all('acl').style.display='none';
		document.all('pge').style.display='block';
		document.all('content').style.display='inline';
		document.all('datetable').style.display='inline';
		document.all('editbar').style.display='inline';
	}else{
		document.all('acl').style.display='block';
		document.all('pge').style.display='none';
		document.all('content').style.display='inline';
		document.all('datetable').style.display='inline';
		document.all('editbar').style.display='inline';
	}
	fn=document.all('hview').onpropertychange;
	document.all('hview').onpropertychange=null;
	document.all('hview').inner=head[index];
	document.all('hview').innerHTML=head[index].value;
	document.all('hview').onpropertychange=fn;
	if(index != 0)
		content.document.body.innerHTML=cont[index].value;
	else
		content.document.body.innerHTML=f.intro.value;

	menu[curIndex].className='def';
	menu[index].className='sel';
	curIndex=index;
}

function moveItems(delta)
{
	from=1;to=pages;
	if(delta>0){from=pages;to=1;}
	for(var i=from;i!=to-delta;i-=delta)
		if((i+delta)>0&&(i+delta<=pages)&&(menu[i].isChecked)&&(!menu[i+delta].isChecked))
			moveTo(i,i+delta);
}

function moveTo(from,to)
{
	if(from==to) return;

	step=to>from?1:-1;

	for(var i=from+step;i!=to+step;i+=step){

		if(from==curIndex)
			curIndex=i;
		else if(i==curIndex)
			curIndex=from;

		if(step>0){
			temp=menu[i].cloneNode(true);
			menu[i].removeNode(true);
			menu[i]=temp;
			olMenu.insertBefore(menu[i],menu[from]);
		}else{
			temp=menu[from].cloneNode(true);
			menu[from].removeNode(true);
			menu[from]=temp;
			olMenu.insertBefore(menu[from],menu[i]);
		}
		temp=menu[from];
		menu[from]=menu[i];
		menu[i]=temp;
		menu[from].menuIndex=from;
		menu[i].menuIndex=i;
		if(getItemText(menu[from])==pageText+i)
			setItemText(menu[from],pageText+from);
		if(getItemText(menu[i])==pageText+from)
			setItemText(menu[i],pageText+i);
		if(menu[i].isChecked)
			menu[i].all('ch').checked=true;

		temp=head[i];
		head[i]=head[from];
		head[from]=temp;

		temp=cont[i];
		cont[i]=cont[from];
		cont[from]=temp;

		from=i;
	}
}

function updateMenuItem(index)
{
	text=index>0?pageText+index:headText;
	if(head[index].value.match(gre))
		text=head[index].value;
	setItemText(menu[index],text);
}
function setItemText(li,text)
{
	li.all('txt').innerText=text;;
}

function getItemText(li)
{
	return li.all('txt').innerText;
}

function itemChecked(li,state)
{
	li.isChecked=state;
	if(state)
		count++;
	else
		count--;
	actionCheck();
}

function removePages()
{
	if((pages>1)&&!mopen("../../confirm.php?q=" + encodeURIComponent(REMOVE_SELECTED_PAGES_Q) + "&skin="+skin,350,150)) return;
	for(var i=1;i<=pages;i++)
		if(menu[i].isChecked)
			if(removePage(i)) {i--;count--;}
	actionCheck();
}

function removePage(index)
{
	menu[index].isChecked=false;
	if(pages<2) {return false;}
	if(index<=curIndex)
	{
		switchTo(index-1);
	}
	disableButton(document.all('btn_saveart'),false,head[index]);
	moveTo(index,pages);
	menu[pages].removeNode(true);
	head[pages].removeNode(true);
	cont[pages].removeNode(true);
	pages--;
	return true;
}

function actionCheck()
{
	if(count>0&&pages>=2)
	{
		disableButton(btn_rmvpage,false);
		disableButton(btn_moveup,false);
		disableButton(btn_movedown,false);
	} else {
		disableButton(btn_rmvpage,true);
		disableButton(btn_moveup,true);
		disableButton(btn_movedown,true);
	}
}

function switchpanel()
{
	if(view.style.display!='none')
		view.style.display='none';
	else
		view.style.display='inline';
	setCookie('panelstate',view.style.display);
}

function setCookie(argName,argValue){
	document.cookie=argName+'='+argValue;
}
function chkPnl(){
	if(view.style.display=='none')
		pnl.className="pnlright";
	else
		pnl.className="pnlleft";
}