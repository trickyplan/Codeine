<?php

    function F_Default_New ($Args)
    {
        $Object = new Object($Args[2], $Args[3]);
        $Output = 'Ничего не сделано.';
        
        if ($Object->Add('CreatedOn',time()))
            $Output = 'Объект создан.';
        else
            $Output = 'Объект не создан.';

        $Object->Save();
        return $Output;
    }

    function F_Default_Erase ($Args)
    {
        $Object = new Object($Args[2], $Args[3]);
        $Output = 'Ничего не сделано.';

        if ($Object->Erase())
            $Output = 'Объект уничтожен.';
        else
            $Output = 'Объект не уничтожен.';

        $Object->Save();
        return $Output;
    }

    function F_Default_Add ($Args)
    {
      $Object = new Object($Args[2]);
        if ($Object->Query($Args[3]))
            {
                $Args[5] = implode(' ', array_slice($Args, 5));

                if ($Object->Add($Args[4],$Args[5]))
                    $Output = 'Граф создан.';
                else
                    $Output = 'Граф не создан.';
                $Object->Save();
            }
        else
            $Output = 'Объект не найден';
        return $Output;
    }

    function F_Default_Del ($Args)
    {
      $Object = new Object($Args[2]);
        if ($Object->Query($Args[3]))
            {
                $Args[5] = implode(' ', array_slice($Args, 5));

                if ($Object->Del($Args[4],$Args[5]))
                    $Output = 'Граф удалён.';
                else
                    $Output = 'Граф не удалён.';
                $Object->Save();
            }
        else
            $Output = 'Объект не найден';
        return $Output;
    }

    function F_Default_Set ($Args)
    {
        $Object = new Object($Args[2]);
        if ($Object->Query($Args[3]))
            {
                $Args[5] = implode(' ', array_slice($Args, 5));

                if ($Object->Set($Args[4],$Args[5]))
                    $Output = 'Граф изменён.';
                else
                    $Output = 'Граф не изменён.';
                $Object->Save();
            }
        else
            $Output = 'Объект не найден';

        return $Output;
    }