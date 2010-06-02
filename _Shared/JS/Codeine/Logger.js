function Logger(Log, URI)
{
	var Logging = [];
	Logging[URI] = $.evalJSON(Log);
	var DID = 'DL'+Math.round(Math.random()*1000);
	var LogStr = '<div id="'+DID+'">';
	for (Row in Logging[URI])
		LogStr += "<div class='Logger Logger_"+Logging[URI][Row].T+"'> "+Logging[URI][Row].S+" > "+Logging[URI][Row].E+": "+Logging[URI][Row].M+"</div>";
	LogStr += "</div>";
	
	$('body').append(LogStr);
	$("#Logger").tabs('add', '#'+DID, URI);
}

function ToggleLogger()
{
	$('#Logger').slideToggle('fast');
}

$(document).ready(function () {
    $(document).keydown(function(e) {
        if (e.keyCode == 192)
            ToggleLogger();
    })
})