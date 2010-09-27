<?php

function F_Clip_Parse($Pockets)
{
    
    $Args = json_decode($Pockets, true);
    $SourceWidth = 0;
    $SourceHeight = 0;
    $DestWidth    = 0;
    $DestHeight   = 0;

    $Image = $Args['Image'];
    
    if ($Image == 'Data/***')
        $Image = 'Images/Private.jpg';
    
    if ('' == $Image or is_dir(Root.$Image) or !file_exists(Root.$Image))
    {
        $Image = 'Images/NoPhoto.jpg';
        $Args['Mode'] = '';
    }

    if (!isset($Args['Align']))
        $Args['Align'] = '';
    else
        $Args['Align'] = 'align='.$Args['Align'];

    if (!isset($Args['Class']))
        $Args['Class'] = '';

    $Size = $Args['Size'];

    if (!is_dir(Root.Temp.'Thumbs/'.$Size))
        mkdir(Root.Temp.'Thumbs/'.$Size, 0777);

    $FullName = Root.$Image;
    $ThumbName = Root.Temp.'Thumbs/'.$Size.'/clip_'.basename($Image);
    $ThumbURL = Temp.'Thumbs/'.$Size.'/clip_'.basename($Image);
    
    if (!file_exists($ThumbName))
    {
            list ($SourceWidth, $SourceHeight) = getimagesize($FullName);
            $CPX = 0;
            $CPY = 0;
            $Q = $SourceWidth;

            if ($SourceWidth > $SourceHeight)
            {
                $CPY = floor(($SourceWidth-$SourceHeight)/2);
                $CPX = 0;
                $Q = $SourceHeight-$CPY;
            }
            else
            {
                $CPX = floor(($SourceHeight-$SourceWidth)/2);
                $CPY = 0;
                $Q = $SourceWidth-$CPX;
            }

                    $DIM = imagecreatetruecolor($Size, $Size);

                    $Extension = explode('.', $FullName);
                    $Extension = $Extension[count($Extension) - 1];

                    if ($Extension == 'jpeg')
                        $Extension = 'jpg';

                    switch ($Extension)
                    {
                        case 'jpg':
                            $SIM = imagecreatefromjpeg($FullName);
                            imagecopyresampled($DIM, $SIM, 0,0, $CPX, $CPY, $Size, $Size, $Q, $Q);
                            imagejpeg($DIM, $ThumbName, 100);
                            break;

                        case 'png':
                            $SIM = imagecreatefrompng($FullName);
                            imagealphablending($SIM, false);
                            imagealphablending($DIM, false);

                            imagesavealpha($SIM, true);
                            imagesavealpha($DIM, true);
 
                            imagecopyresampled($DIM, $SIM, 0,0, $CPX,$CPY, $Size, $Size, $Q, $Q);
                            imagepng($DIM, $ThumbName);
                            break;

                        case 'gif':
                            $SIM = imagecreatefromgif($FullName);
                            imagecopyresampled($DIM, $SIM, 0,0, $CPX,$CPY, $Size, $Size, $Q, $Q);
                            imagegif($DIM, $ThumbName);
                        break;
                    }

                    $HTML = '<img alt="" src="/'.$ThumbURL.'" class="'.$Args['Class'].'" />';

    }
    else
    {
        list ($DestWidth, $DestHeight) = getimagesize($ThumbName);
        $HTML = '<img alt="" width="'.$DestWidth.'" height="'.$DestHeight.'" src="/'.$ThumbURL.'" class="'.$Args['Class'].'" '.$Args['Align'].' />';
    }

    switch ($Args['Mode'])
    {
        case 'LightBox':
            Page::JSFile('~jQuery/Plugins/ShadowBox.js');
            Page::CSSFile('CSS/ShadowBox.css');
            Page::JS('
                Shadowbox.init({
                handleOversize: "resize",
                modal: true
            });');
            $HTML = '<a rel="lightbox" class="nou" href="'.$Image.'">'.$HTML.'</a>';
        break;

        case 'Zoom':
            Page::JSFile('jQuery/Plugins/Zoom.js');
            Page::CSSFile('CSS/jqzoom.css');
            Page::JS('$(".zoom").jqzoom();');
            $HTML = '<a class="nou zoom" title="Зум" href="'.$Image.'">'.$HTML.'</a>';
        break;

        case 'ZoomBox':
            Page::JSFile('jQuery/Plugins/ShadowBox.js');
            Page::JSFile('jQuery/Plugins/Zoom.js');
            Page::CSSFile('CSS/ShadowBox.css');
            Page::CSSFile('CSS/jqzoom.css');
            Page::JS('$(".zoom").jqzoom();');
            Page::JS('
                Shadowbox.init({
                handleOversize: "resize",
                modal: true
            });');
            $HTML = '<a rel="lightbox" class="nou zoom" title="Зум" href="'.$Image.'">'.$HTML.'</a>';
        break;

        case 'Text':
            $HTML = '/'.$ThumbURL;
        break;
    }

    return $HTML;
}