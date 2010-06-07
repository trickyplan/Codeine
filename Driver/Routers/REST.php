<?php

function F_REST_Route($Call)
{
    if (mb_substr($Call,0,1) != '/')
       return null;
    
    $Routed = array();
    Log::Important($Call);
    
    if (mb_strpos($Call,'?') !== false)
        {
            list($Call, $Query) = explode ('?', $Call);
            mb_parse_str($Query, $Routed);
        }

    $Call = explode ('/', mb_substr($Call,1));

    if (is_array($Call) and sizeof($Call) == 2)
    {
        list ($Routed['Name'], $Routed['ID']) = $Call;
        $Routed['Interface'] = 'web';

        switch (Server::$REST)
        {
            case 'get':
                if ((mb_substr($Routed['Name'], mb_strlen($Routed['Name'])-1) == 's'))
                {
                    if ($Routed['Name'] == 'News')
                    {
                        if ($Routed['ID'] == '@All')
                            $Routed['Plugin'] = 'List';
                        else
                            $Routed['Plugin'] = 'Show';
                    }
                    else
                    {
                        $Routed['Name'] = mb_substr($Routed['Name'],0,mb_strlen($Routed['Name'])-1);
                        $Routed['Plugin'] = 'List';
                    }
                }
                else
                    $Routed['Plugin'] = 'Show';

                if (in_array($Routed['ID'], array('Create')))
                    {
                        $Routed['Plugin'] = $Routed['ID'];
                        unset($Routed['ID']);
                    }
            break;

            case 'put': $Routed['Plugin'] = 'Create'; break;
            case 'post': $Routed['Plugin'] = 'Update'; break;
            case 'delete': $Routed['Plugin'] = 'Delete'; break;
        }
    }
    else
        $Routed = null;

    return $Routed;
}