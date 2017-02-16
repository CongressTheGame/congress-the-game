//ALL OF THE POPUPS ON THE LOGIN PAGE//////////////////////
//SETTING UP OUR POPUP
//0 means disabled; 1 means enabled;
var popupRegisterStatus = 0;
var popupLoginStatus = 0;
var popupOverviewStatus = 0;

//loading popup with jQuery magic!
function loadRegisterPopup(){
	//loads popup only if it is disabled
	if(popupRegisterStatus==0){
		$("#backgroundPopup").css({
			"opacity": "0.7"
		});
		$("#backgroundPopup").fadeIn("slow");
		$("#popupRegister").fadeIn("slow");
		popupRegisterStatus = 1;
	}
}

function loadLoginPopup(){
	//loads popup only if it is disabled
	if(popupLoginStatus==0){
		$("#backgroundPopup").css({
			"opacity": "0.7"
		});
		$("#backgroundPopup").fadeIn("slow");
		$("#popupLogin").fadeIn("slow");
		popupLoginStatus = 1;
	}
}
function loadOverviewPopup(){
	//loads popup only if it is disabled
	if(popupOverviewStatus==0){
		$("#backgroundPopup").css({
			"opacity": "0.7"
		});
		$("#backgroundPopup").fadeIn("slow");
		$("#popupOverview").fadeIn("slow");
		popupOverviewStatus = 1;
	}
}

//disabling popup with jQuery magic!
function disableRegisterPopup(){
	//disables popup only if it is enabled
	if(popupRegisterStatus==1){
		$("#backgroundPopup").fadeOut("slow");
		$("#popupRegister").fadeOut("slow");
		popupRegisterStatus = 0;
	}
}
function disableLoginPopup(){
	//disables popup only if it is enabled
	if(popupLoginStatus==1){
		$("#backgroundPopup").fadeOut("slow");
		$("#popupLogin").fadeOut("slow");
		popupLoginStatus = 0;
	}
}
function disableOverviewPopup(){
	//disables popup only if it is enabled
	if(popupOverviewStatus==1){
		$("#backgroundPopup").fadeOut("slow");
		$("#popupOverview").fadeOut("slow");
		popupOverviewStatus = 0;
	}
}
//centering popup
function centerRegisterPopup(){
	//request data for centering
	var windowWidth = document.documentElement.clientWidth;
	var windowHeight = document.documentElement.clientHeight;
	var popupHeight = $("#popupRegister").height();
	var popupWidth = $("#popupRegister").width();
	//centering
	$("#popupRegister").css({
		"position": "absolute",
		"top": windowHeight/2-popupHeight/2,
		"left": windowWidth/2-popupWidth/2
	});
	//only need force for IE6
	
	$("#backgroundPopup").css({
		"height": windowHeight
	});
	
}

function centerLoginPopup(){
	//request data for centering
	var windowWidth = document.documentElement.clientWidth;
	var windowHeight = document.documentElement.clientHeight;
	var popupHeight = $("#popupLogin").height();
	var popupWidth = $("#popupLogin").width();
	//centering
	$("#popupLogin").css({
		"position": "absolute",
		"top": windowHeight/2-popupHeight/2,
		"left": windowWidth/2-popupWidth/2
	});
	//only need force for IE6
	
	$("#backgroundPopup").css({
		"height": windowHeight
	});
	
}

function centerOverviewPopup(){
	//request data for centering
	var windowWidth = document.documentElement.clientWidth;
	var windowHeight = document.documentElement.clientHeight;
	var popupHeight = $("#popupOverview").height();
	var popupWidth = $("#popupOverview").width();
	//centering
	$("#popupOverview").css({
		"position": "absolute",
		"top": windowHeight/2-popupHeight/2,
		"left": windowWidth/2-popupWidth/2
	});
	//only need force for IE6
	
	$("#backgroundPopup").css({
		"height": windowHeight
	});
	
}

//CONTROLLING EVENTS IN jQuery
$(document).ready(function(){
	//REGISTER
	//LOADING POPUP
	//Click the button event!
	$(".register_button").click(function(){
		//centering with css
		centerRegisterPopup();
		//load popup
		loadRegisterPopup();
	});
				
	//CLOSING POPUP
	//Click the x event!
	$("#popupRegisterClose").click(function(){
		disableRegisterPopup();
	});
	//Click out event!
	$("#backgroundPopup").click(function(){
		disableRegisterPopup();
	});
	//Press Escape event!
	$(document).keypress(function(e){
		if(e.keyCode==27 && popupRegisterStatus==1){
			disableRegisterPopup();
		}
	});
	
	//LOGIN
	//LOADING POPUP
	//Click the button event!
	$(".login_button").click(function(){
		//centering with css
		centerLoginPopup();
		//load popup
		loadLoginPopup();
	});
				
	//CLOSING POPUP
	//Click the x event!
	$("#popupLoginClose").click(function(){
		disableLoginPopup();
	});
	//Click out event!
	$("#backgroundPopup").click(function(){
		disableLoginPopup();
	});
	//Press Escape event!
	$(document).keypress(function(e){
		if(e.keyCode==27 && popupLoginStatus==1){
			disableLoginPopup();
		}
	});
	
	//OVERVIEW
	//LOADING POPUP
	//Click the button event!
	$(".overview_button").click(function(){
		//centering with css
		centerOverviewPopup();
		//load popup
		loadOverviewPopup();
	});
				
	//CLOSING POPUP
	//Click the x event!
	$("#popupOverviewClose").click(function(){
		disableOverviewPopup();
	});
	//Click out event!
	$("#backgroundPopup").click(function(){
		disableOverviewPopup();
	});
	//Press Escape event!
	$(document).keypress(function(e){
		if(e.keyCode==27 && popupOverviewStatus==1){
			disableOverviewPopup();
		}
	});
});

/***************************/
//@Author: Adrian "yEnS" Mato Gondelle
//@website: www.yensdesign.com
//@email: yensamg@gmail.com
//@license: Feel free to use it, but keep this credits please!					
/***************************/

//FORM STYLING//
$('.formField').focusin(function(){
	$(this).css('border-color','#003','border','bold');
}).focusout(function(){
	$(this).css('border-color','');
});
