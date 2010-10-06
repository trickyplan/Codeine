<?php

function F_Image_Render($Args)
{
    if (is_file(Root.Data.$Args['value']))
        $EF = '<vximage>{"Type":"Thumb","Image":"'.$Args['value'].'","Size":"50","Layout":"LightBox"}</vximage>';
    else
        $EF = '';

    return '<div class="line"><input name="'.$Args['name'].'" type="file" /></div><div class="Spaced">'.$EF.'</div>';
    
}