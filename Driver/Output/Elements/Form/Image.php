<?php

function F_Image_Render($Args)
{
    if (is_file(Root.Data.$Args['value']))
        $EF = '<vxthumb>{"Image":"'.Data.$Args['value'].'","Size":"50","Mode":"LightBox"}</vxthumb>';
    else
        $EF = '';

    return '<div class="line"><input name="'.$Args['name'].'" type="file" /></div><div class="Spaced">'.$EF.'</div>';
    
}