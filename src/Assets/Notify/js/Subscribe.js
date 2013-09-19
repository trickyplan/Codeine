var notify =
{
    subscribe: function ()
    {
        $.ajax(
        {
                url: '/notify/subscribe',
                type: 'post',
                dataType: 'JSON',
                success: function(json)
                {
                    if (json.length > 0)
                    {
                        for (var i = 0; i< json.length; i++)
                        {
                            $.pnotify({
                                title: json[i].Title,
                                text: json[i].Text,
                                type: json[i].Type,
                                width: '25%'
                            });
                        }
                    }
                }
            }
        );
    }
}

$(document).ready(function() {
    notify.subscribe();
    setInterval("notify.subscribe()", 10000);
})