<?php

    function F_FirePHP_Output($Messages)
    {
        //if (!headers_sent())
        {
            include Engine.Classes.'FirePHPCore/FirePHP.class.php';
            $FP = FirePHP::getInstance(true);

            foreach($Messages as $AppID => $AppMessages)
            {
                $FP->group($AppID);
                foreach($AppMessages as $Message)
                {
                        switch($Message[1])
                        {
                            case 0:   $FP->info ($Message[2], $Message[0]); break;
                            case 1:   $FP->error($Message[2], $Message[0]); break;
                            case 2:   $FP->warn ($Message[2], $Message[0]); break;
                            case 3:   $FP->warn ($Message[2], $Message[0]); break;
                            case 4:   $FP->info ($Message[2], $Message[0]); break;
                            case 5:   $FP->info ($Message[2], $Message[0]); break;
                            case 6:   $FP->info ($Message[2], $Message[0]); break;
                            case 7:   $FP->info ($Message[2], $Message[0]); break;
                            case 8:   $FP->info ($Message[2], $Message[0]); break;
                            default:  $FP->info ($Message[2], $Message[0]); break;
                        }
                }
                $FP->groupEnd();
            }
        }
        
        return null;
    }
