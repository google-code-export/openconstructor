var docsHref = null;
function disableToolbarButtons() {
	disableButton(btn_activate, wchome + '/i/default/e/activate_.gif');
	disableButton(btn_deactivate, wchome + '/i/default/e/deactivate_.gif');
	disableButton(btn_remove, wchome + '/i/default/e/remove_.gif');
}
function enableToolbarButtons() {
	disableButton(btn_activate, false);
	disableButton(btn_deactivate, false);
	disableButton(btn_remove, false);
	var docs = document.getElementById('iframe.votes').contentWindow;
	if(!docs.___unloadAttached) {
		docs.attachEvent('onunload', function() {disableToolbarButtons();});
		docs.___unloadAttached = true;
	}
}
function removeVotes() {
	if(checkedDocs > 0) {
		var docs = document.getElementById('iframe.votes').contentWindow;
		if(docs.mopen(wchome + "/confirm.php?q=" + encodeURIComponent(REMOVE_SELECTED_VOTES_Q) + "&skin=" + skin, 350, 170)) {
			docs.f_doc.target = "_parent";
			docs.f_doc.submit();
		}
	}
}
function setVotesState(state) {
	if(checkedDocs > 0) {
		var docs = document.getElementById('iframe.votes').contentWindow;
		var message = state ? ACTIVATE_SELECTED_VOTES_Q : DEACTIVATE_SELECTED_VOTES_Q;
		if(docs.mopen(wchome + "/confirm.php?q=" + encodeURIComponent(message) + "&skin=" + skin, 350, 170)) {
			docs.f_doc.action.value = state ? 'activate_vote' : 'deactivate_vote';
			docs.f_doc.target = "_parent";
			docs.f_doc.submit();
		}
	}
}
function chk(obj, count) {
	checkedDocs = count;
	if(count > 0) {
		enableToolbarButtons();
	} else {
		disableToolbarButtons();
	}
}
function showOnlyFakeVotes(onlyFake) {
	displayFilterButton("div.allVotes", onlyFake);
	displayFilterButton("div.fakeVotes", !onlyFake);
	setActiveFilter("fs.fake");
	reloadVotes(null, onlyFake ? 1 : 0, null, null);
}
function displayFilterButton(id, display) {
	var	btn = document.getElementById(id);
	btn.style.display =  display ? "" : "none";
}
function search() {
	var  txt = document.getElementById("txt.search");
	setActiveFilter("fs.search");
	reloadVotes(txt.value, null, null, null);
}
function filterByDate() {
	var from = document.getElementById('txt.from');
	var to = document.getElementById('txt.to');
	setActiveFilter("fs.date");
	reloadVotes(null, null, from.getValue(), to.getValue());
}
function setActiveFilter(id) {
	if(this.activeFilter)
		this.activeFilter.className = null;
	var f = document.getElementById(id);
	if(f)
		f.className = "inUse";
	this.activeFilter = f;
}
function reloadVotes(search, fake, from, to) {
	var href = docsHref;
	if(search) {
		href += "&keyword=" + escape(search);
		displayFilterButton("div.allVotes", true);
	} else if(fake) {
		href += "&onlyFake=1";
	} else if(from && to) {
		href += "&from=" + escape(from) + "&to=" + escape(to);
		displayFilterButton("div.allVotes", true);
	} else {
		setActiveFilter(null);
		displayFilterButton("div.allVotes", false);
	}
	displayFilterButton("div.fakeVotes", !fake);
	var docs = document.getElementById('iframe.votes').contentWindow;
	if(docs.location.href != href)
		docs.location.href = href;
}
function toggleSetRatingCtrls(show) {
	document.getElementById('tr.setRating').style.display = show ? 'block' : 'none';
	document.getElementById('a.setRating').style.display = show ? 'none' : 'inline-block';
	if(show)
		document.getElementById('txt.rating').focus();
}
function toggleRightPanel(show) {
	var a = document.getElementById('a.rightPanel');
	var td = document.getElementById('rightPanel');
	var shown = show != undefined ? !show : td.style.display != 'none';
	a.className = shown ? 'pnlleft' : 'pnlright';
	td.style.display = shown ? 'none' : '';
}
function setRating() {
	var f = document.forms['f'];
	var rating = parseInt(f.rating.value);
	if(rating >= minRating && rating <= maxRating) {
		return true;
	}
	alert(RATING_MUST_BE_VALID_W);
	f.rating.focus();
	return false;
}