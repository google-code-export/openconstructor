function tblimport()
{
	t=tbl;
	t.deleteTHead();
	t2=null;i=0;
	while(i>=0&&i<content.document.all.length){
		if(content.document.all(i).tagName=='TABLE'){
			t2=content.document.all(i);
			i=-2;
		}
		i++;
	}
	if(i>=0) return;t=tbl;
	t.style.cssText=null;
	if(t.className) t.removeAttribute('className',0);
	for(i=0;i<t.all.length;i++){
		o=t.all(i);
		if(o.tagName=='p'||o.tagName=='SPAN') {o.removeNode();i-=2};
		o.style.cssText=null;
		if(o.className) o.removeAttribute('className',0);
	}
	tblcopy(t,t2);
}
function tblcopy(t1,t2)
{
	var i,j,k,a=Array(t1.rows.length);
	tb=t2.tBodies(0);
	createarr(a,Math.min(t1.rows(0).cells.length,tb.rows(0).cells.length),t1.rows.length);
	for(i=0;i<t1.rows.length;i++){
		if(i>=tb.rows.length)
			tb.appendChild(tb.rows(i-1).cloneNode(true));
	}
	formarr(a,t1,tb);
	for(i=0;i<a.length;i++)
		rowcopy(t1.rows(i),tb.rows(i),a[i]);
}
function createarr(a,x,y){
	for(var i=0;i<y;i++)
	{
		a[i]=Array(x);
		for(var j=0;j<x;j++)
			a[i][j]=true;
	}
}
function formarr(a,t1,t2){
	for(var r=0;r<a.length;r++)
		for(var c=0,vc=0;c<a[r].length;c++,vc++){
			if(!a[r][c]){
				vc--;
				continue;
			}
			rs=cs=0;prr=1;
			if(vc>0)
				prr=t1.rows(r).cells(vc-1).rowSpan;

			for(var k=0;k<t1.rows(r).cells(vc).rowSpan-prr;k++)
				if(r+k+1<a.length) t2.rows(r+k+1).cells(vc).removeNode(true);
			rs=t1.rows(r).cells(vc).rowSpan-prr;
			if(rs<0) rs=0;
			t2.rows(r).cells(vc).rowSpan+=rs;
			if(r>0){
				cs=t1.rows(r).cells(vc).colSpan-t1.rows(r-1).cells(st(a[r-1],vc)).colSpan;
				if(cs==0) cs=t2.rows(r-1).cells(vc).colSpan-t2.rows(r).cells(vc).colSpan;
				t2.rows(r).cells(vc).colSpan+=cs;
					for(var k=0;k<cs;k++)
						if(t2.rows(r).cells(vc+1)) {
							if(t2.rows(r).cells(vc+1).colSpan>1){
								for(rss=0;rss<=rs;rss++) t2.rows(r+rss).cells(vc+1).colSpan-=1;
								cs++;
							}
							else
								for(rss=0;rss<=rs;rss++) t2.rows(r+rss).cells(vc+1).removeNode(true);
						}
					if(cs<0) cs=0;
			}
			spanarr(a,r,c,rs,cs);
		}
}
function spanarr(a,y,x,h,w){
	for(var i=y;i<=y+h;i++)
		for(var j=x;j<=x+w;j++)
			a[i][j]=false;
	a[y][x]=true;
}
function st(a,pos){
	stt=-1;var i=0;
	while(i<a.length&&stt<pos){
		if(a[i]) stt++;
		i++;
	}
	return stt;
}
function rowcopy(r1,r2,ar){
	for(var c=0,vc=0;c<ar.length;c++,vc++){
		if(!ar[c]){
			vc--;
			continue;
		}
		r2.cells(vc).innerHTML=r1.cells(vc).innerHTML;
	}
}
function tblinsert(){
	dialogArguments[0].parent.curTblOrig.insertAdjacentHTML('afterEnd',content.document.body.innerHTML);
	window.returnValue=true;
}
function loadprot(prot){
	theHTML.location = 'tpl.php?id=' + prot;
	btnimp.disabled=true;
	window.inter=window.setInterval(setcontent,250);
}
