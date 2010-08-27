<?php

    function F_Default_Install($Args)
    {
        $App = new Object('_Application', $Args[2]);
        $Output = 'Not executed:'.print_r($Args, true);

        if (count($Args)==3)
            {
                $App->Set('Installed', 'True');
                $App->Add('Inherit', 'CRUD');
                $Output = $Args[2].' installed';
            }

        if (count($Args)==5)
            {
                switch($Args[3])
                {
                    case 'Self':
                        if (file_exists(Engine.Apps.$Args[2].'/'.$Args[4].'.php'))
                        {
                            $App->Add('Plugin:Installed', $Args[4]);
                            $Output = $Args[2].' self plugin '.$Args[4].' installed';
                        }
                        else
                            $Output = 'Self plugin '.$Args[4].' not exist';
                    break;
                    case 'Shared':
                        if (file_exists(Engine.Apps.'_Shared/'.$Args[4].'.php'))
                        {
                            $App->Add('Plugin:Shared', $Args[4]);
                            $Output = $Args[2].' shared plugin '.$Args[4].' installed';
                        }
                        else
                            $Output = 'Shared plugin '.$Args[4].' not exist';
                    break;
                }
            }

        

        $App->Save();
        return $Output;
    }

    function F_Default_Remove($Args)
    {
        $App = new Object('_Application', $Args[2]);
        $Output = 'Not executed:'.print_r($Args, true);

        if (count($Args)==3)
            {
                $App->Del('Installed', 'True');
                $Output = $Args[2].' removed';
            }

        if (count($Args)==5)
            {
                switch($Args[3])
                {
                    case 'Self':
                        if (file_exists(Engine.Apps.$Args[2].'/'.$Args[4].'.php'))
                        {
                            $App->Del('Plugin:Installed', $Args[4]);
                            $Output = $Args[2].' self plugin '.$Args[4].' removed';
                        }
                        else
                            $Output = 'Self plugin '.$Args[4].' not exist';
                    break;
                    case 'Shared':
                        if (file_exists(Engine.Apps.'_Shared/'.$Args[4].'.php'))
                        {
                            $App->Del('Plugin:Shared', $Args[4]);
                            $Output = $Args[2].' shared plugin '.$Args[4].' removed';
                        }
                        else
                            $Output = 'Shared plugin '.$Args[4].' not exist';
                    break;
                }
            }



        $App->Save();
        return $Output;
    }