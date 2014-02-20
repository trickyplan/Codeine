$('input,textarea').change(
    function (el)
    {
      $.ajax('?Prepare',
      {
            method: 'post',
            type: 'json',
            data: $(this).parents('form').serialize(),
            success: function (data)
            {
                $.each (data, function (index, row)
                {
                    var key, key2, key3; // PIECE OF SHIT!!!!
                    for (key in row)
                    {
                        if (row[key] instanceof Object)
                            for (key2 in row[key])
                            {
                                if (row[key][key2] instanceof Object)
                                    for (key3 in row[key][key2])
                                        $('[name="Data['+index+']['+key+']['+key2+']['+key3+']"]').val(row[key][key2][key3]);

                                $('[name="Data['+index+']['+key+']['+key2+']"]').val(row[key][key2]);
                            }
                        else
                            $('[name="Data['+index+']['+key+']"]').val(row[key]);
                    }
                });
            }
      })
    }
);