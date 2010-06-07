<?php 

function F_Semantags_Process ($Data)
{
    $Pockets = array();
    $Semantags = array('Irony');

    // FIXME No parsing at forms
    if (preg_match_all('@\[(.*)\](.*)\[/(.*)\]@SsUu', $Data, $Pockets) and Application::$Plugin != 'Update')
        foreach ($Pockets[0] as $IX => $Match)
            {
                $Tag = ucfirst($Pockets[1][$IX]);
                if (in_array($Tag, $Semantags))
                    $Data = str_replace ($Match, '<span class="Semantic '.$Tag.'" title="<l>Semantag:'.$Tag.':Hint</l>">'.$Pockets[2][$IX].'</span>',$Data);
                else
                    $Data = str_replace ($Match, $Pockets[2][$IX],$Data);
            }

    return $Data;
} 
