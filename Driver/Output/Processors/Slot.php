<?php

    function F_Slot_Process ($Data)
    {
        /*while (mb_ereg('<slot>([[:alnum:]]*)<\/slot>', $Data, $Pockets))
            if (isset(View::$Slots[$Pockets[1]]))
                {
                    if (is_array(View::$Slots[$Pockets[1]]))
                        View::$Slots[$Pockets[1]] = implode(' / ',View::$Slots[$Pockets[1]]);
                    $Data = str_replace ($Pockets[0], View::$Slots[$Pockets[1]], $Data);
                }
            else
                $Data = str_replace ($Pockets[0], '', $Data);
*/
        $TRs = array('<ip/>'=>_IP,
                     '<interface/>'=>Application::$Interface,
                     '<language/>'=>Client::$Language,
                     '<app/>'=>Application::$Name,
                     '<plugin/>'=>Application::$Plugin,
                     '<id/>'=>Application::$ID,
                     '<mode/>'=>Application::$Mode,
                     '<aspect/>'=>Application::$Aspect,
                     '<call/>' => Application::$Call,
                     '<uid/>'  => Client::$UID
                     );

        if (Client::$Level == 2)
            {
                $TRs['<facetype/>'] = Client::$Face->Scope();
                $TRs['<facename/>'] = Client::$Face->Name();
            }

        return strtr($Data, $TRs);
    }
