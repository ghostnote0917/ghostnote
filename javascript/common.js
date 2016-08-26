function $() {
    var a = [];
    for (var b = 0; b < arguments.length; b++) {
        if (typeof arguments[b] == "string") {
            a[a.length] = document.getElementById(arguments[b]);
        } else {
            a[a.length] = arguments[b];
        }
    }
    return a[1] ? a : a[0];
}
function show(id) {
	var e = $(id);
	if (e != null) {
		e.style.display = "block";
	}
}
function hide(id) {
	var e = $(id);
	if (e != null) {
		e.style.display = "none";
	}
}
function _addEvent(element, eventName, func) {
        if(element.attachEvent) {
                element.attachEvent("on" + eventName, func);
        } else if (element.addEventListener) {
                element.addEventListener(eventName, func, false);
        }
}
function _addInputEvent(elementId, setToFocusFunc, unsetToFocusFunc){
        var element = $(elementId);
        _addEvent(element, "focus", setToFocusFunc);
        _addEvent(element, "blur", unsetToFocusFunc);
}
function addInputEvent(inputId, borderId) {
        _addInputEvent(inputId, function(){borderOn(inputId,borderId);}, function(){borderOff(inputId,borderId);});
}
function addDeleteButtonEvent(inputId, buttonId) {
	var input = $(inputId);
	if (input == null) {
		return;
	}
	var button = $(buttonId);

	var buttonEnableEvent = function() {
		if(input.value != "") {
			button.style.display = "block";
		} else {
			button.style.display = "none";
		}
	};

	var buttonClickEvent = function() {
		input.value = "";
		buttonEnableEvent();
	};

	_addEvent(input, "input", buttonEnableEvent);
	_addEvent(input, "focus", buttonEnableEvent);
	_addEvent(input, "blur", buttonEnableEvent);
	_addEvent(button, "touchstart", buttonClickEvent);

	buttonEnableEvent();
}
function borderOn(inputId,id) {
	alert('hi');
        var e = $(id);
        if (e.className.indexOf(" focus") == -1) {
                e.className = e.className + " focus";
        }
        try{
        hide('label_'+id);
        }catch(e){}
}
function borderOff(inputId,id) {
        var e = $(id);
        var f = $(inputId);
        e.className = e.className.replace(" focus", "");
        try{
        if (f.value.length==0){
                show('label_'+id);
        }
        }catch(e){}
}
function borderOn(inputId,id) {
	var e = $(id);
	if (e.className.indexOf(" focus") == -1) {
		e.className = e.className + " focus";
	}
	try{
	hide('label_'+id);
	}catch(e){}
}
function borderOff(inputId,id) {
	var e = $(id);
	var f = $(inputId);
	e.className = e.className.replace(" focus", "");
	try{
	if (f.value.length==0){
		show('label_'+id);
	}
	}catch(e){}
}
function confirmSubmit() {
	var id = $("id");
	var pw = $("pw");
	var encpw = $("encpw");
	
	//if(id.value == "" && encpw.value == "") {
	if(id.value == "") {
		show("err_empty_id");
		hide("err_empty_pw");
		hide("err_common");
		id.focus();
		return false;
	//} else if(pw.value == "" && encpw.value == "") {
	} else if(pw.value == "") {
		hide("err_empty_id");
		show("err_empty_pw");
		hide("err_common");
		pw.focus();
		return false;
	}
	return true;
}