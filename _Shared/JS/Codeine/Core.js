$.Latency = 10000;
$.StLatency = 10000;
$.LiveMode = [];
$.LiveInterval = [];
$.ErrorState = false;

function Do (div, url2)
{
    var Dv = $('#'+div);
    $.ajax({url: url2, success: function (data)
        {
            if (Dv.html() != data)
                Dv.html(data);
        }});
}

function DoPost (div, url, data)
{
    $.Latency = $.StLatency;
    var Div = $('#'+div);
    Div.addClass("AjaxActive");
    Div.load(url, data, function (){Div.removeClass("AjaxActive");})
}

function Get (url)
{
    $.Latency = $.StLatency;
    $.ajax({
        url: url,
        disableCaching: true
    })
}

function blGet (url)
{
    $.Latency = $.StLatency;
    $.ajax({
        url: url,
        disableCaching: true,
        async: false
    })
}

function GetAndGo (url, go)
{
    $.Latency = $.StLatency;
    $.ajax({
        url: url,
        disableCaching: true,
        success:function ()
            {
                Workspace(go);
            }
    })
}

function Refresh (id)
{
    Do(id, $('#'+id).attr('exec'));
}

function Location (url)
{
    Do('Main', url);
}

function Live (id)
{
    if ($.LiveMode[id] != undefined)
        $.LiveMode[id] = !$.LiveMode[id];
    else
        $.LiveMode[id] = true;

    if ($.LiveMode[id])
        $.LiveInterval[id] = setInterval('Refresh("'+id+'");', $.Latency);
    else
        clearInterval($.LiveInterval[id]);
    
}

function E (div, url, data)
{
    $.Latency = $.StLatency;
    $.ajax({
        url: url,
        type: 'post',
        data: data,
        success:
            function (data)
            {
                if (div)
                    $('#'+div).html(data);
            }
    })
}

function Rate (Type, Name, Rater, Direction)
{
    E('Rater_'+Rater+'_'+Type+'_'+Name, '/jsonp/'+Type+'/Rating/'+Name, {'Direction':Direction, 'Rater':Rater});
}

function Workspace (url)
{
    $.Latency = $.StLatency;
    $.WS = $('#Content').addClass("AjaxActive").html();
    $('#Content').load(url).removeClass("AjaxActive");
}

function ContextHelper()
{
    $.ContextHelper = !$.ContextHelper;
}

function Back()
{
    window.back();
    Refresh('Content');
}

function Return()
{
    Refresh('Content');
}

function AddBookmark (Type, Name)
{
    E(false, '/ajax/'+Type+'/Bookmark/'+Name);
}

$(document).ready(function()
{
    $('#Preload').fadeOut('fast');
    $.WS = $('#Content').html();

    $('.ajaxLink').live('click', function()
    {
        Workspace($(this).attr('href'));
        return false;
    });

    $('.Action').live('click', function()
    {
        if ($.ContextHelper)
            {
                $.ajax({
                    url: '/json/Help/Show/'+$(this).attr('topic'),
                    type: 'get',
                    success: function (data)
                    {
                        $.jGrowl(data);
                    }
                });

            }
        else
            eval($(this).attr('action'));
    });
    
    $('nrl').live('click',function() {
		var Text = prompt('Введите ваш вариант перевода для этой фразы','');
		var Token = $(this).attr('token');
		var Th = this;
		if (Text != null)
		{
			$.ajax({
				type: 'POST',
				url: '/ajax/_Language/Submit',
				data: {Token: Token, Text: Text},
				success: function (data)
				{
					$(Th).html(data);
				}
			});
		}
    });

   // $('[title]').tooltip({tip:'#Tip', effect:'fade'});

    $('.ajax').live('submit', function() {
            $(this).ajaxSubmit({
            clearForm: true,
            method: 'post',
            success: function (data) {
                if (data == 'Success')
                    Return();
            }
        });
        return false;
    });

    $('.wsform').live('submit', function() {
            $(this).ajaxSubmit({
            clearForm: true,
            method: 'post',
            success: function (data)
            {
                $.Latency = $.StLatency;
                window.location = data;
            }
        });
        return false;
    });

    $(document).ajaxStart(function () {
        $('#Loader').fadeIn();
    });

    $(document).ajaxStop(function () {
        $('#Loader').fadeOut();
    });

    $(document).ajaxSuccess(function()
    {
        if ($.ErrorState)
            {
                $.jGrowl('Связь с сервером восстановлена!', {header: 'Хорошая новость',theme: 'Panel_Good', sticky: true});
                $.ErrorState = false;
            }
    })
    $(document).ajaxError(function () {
        $.jGrowl('Связь с сервером потеряна!', {header: 'Ошибка',theme: 'Panel_Error', sticky: true});
        $.ErrorState = true;
    });

    setInterval('$.Latency = $.Latency + 50;', 2500);
});