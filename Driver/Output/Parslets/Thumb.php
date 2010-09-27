<?php

function F_Thumb_Parse($Pockets)
{

    $Args = json_decode($Pockets, true);
    $SourceWidth = 0;
    $SourceHeight = 0;

    $Image = $Args['Image'];

    if ($Image == 'Data/***')
        $Image = 'Images/Private.jpg';

    if ('' == $Image or is_dir($Image) or !file_exists(Root.$Image))
    {
        Log::Warning ('Image '.$Image.' not found');
        $Image = 'Images/NoPhoto.jpg';
        $Args['Mode'] = '';
    }

    $Size = $Args['Size'];

    $FullName = Root.$Image;
    $ThumbName = Root.Temp.'Thumbs/'.$Size.'/'.basename($Image);
    $ThumbURL = Temp.'Thumbs/'.$Size.'/'.basename($Image);

    if (!isset($Args['Align']))
        $Args['Align'] = '';
    else
        $Args['Align'] = 'align='.$Args['Align'];

    if (!isset($Args['Class']))
        $Args['Class'] = '';

    if (!is_dir(Root.Temp.'Thumbs/'.$Size))
        mkdir(Root.Temp.'Thumbs/'.$Size, 0777);

    if (!file_exists($ThumbName))
    {
            list ($SourceWidth, $SourceHeight) = getimagesize($FullName);

            if ($SourceWidth == 0) return '';
                $Ratio = $Size / $SourceWidth; // Коофициент сжатия

                $DestHeight = floor($SourceHeight * $Ratio);
                $DestWidth = $Size;

            if ($Ratio < 1)
                {
                    $DIM = imagecreatetruecolor($DestWidth, $DestHeight);

                    $Extension = explode('.', $FullName);
                    $Extension = $Extension[count($Extension) - 1];

                    if ($Extension == 'jpeg')
                        $Extension = 'jpg';

                    switch ($Extension)
                    {
                        case 'jpg':
                            $SIM = imagecreatefromjpeg($FullName);
                            imagecopyresampled($DIM, $SIM, 0,0,0,0, $DestWidth, $DestHeight, $SourceWidth, $SourceHeight);
                            imagejpeg($DIM, $ThumbName, 90);
                            break;

                        case 'png':
                            $SIM = imagecreatefrompng($FullName);
                            imagealphablending($SIM, false);
                            imagealphablending($DIM, false);

                            imagesavealpha($SIM, true);
                            imagesavealpha($DIM, true);

                            imagecopyresampled($DIM, $SIM, 0,0,0,0, $DestWidth, $DestHeight, $SourceWidth, $SourceHeight);
                            imagepng($DIM, $ThumbName);
                            break;

                        case 'gif':
                            $SIM = imagecreatefromgif($FullName);
                            imagecopyresampled($DIM, $SIM, 0,0,0,0, $DestWidth, $DestHeight, $SourceWidth, $SourceHeight);
                            imagegif($DIM, $ThumbName);
                        break;
                    }

                    $HTML = '<img alt="" src="/'.$ThumbURL.'" class="'.$Args['Class'].'" />';
                }
              else
                  $HTML = '<img alt="" src="/'.$Image.'" class="'.$Args['Class'].'" />';
    }
    else
        $HTML = '<img alt="" src="/'.$ThumbURL.'" class="'.$Args['Class'].'" '.$Args['Align'].' />';

    if (!isset($Args['Mode']))
        $Args['Mode'] = 'None';

    switch ($Args['Mode'])
    {
        case 'LightBox':
            Page::JSFile('jQuery/Plugins/ShadowBox.js');
            Page::CSSFile('CSS/ShadowBox.css');
            Page::JS('Shadowbox.init({handleOversize: "resize",modal: true});');
            $HTML = '<a rel="lightbox" class="nou" href="/'.$Image.'">'.$HTML.'</a>';
        break;

        case 'Zoom':
            Page::JSFile('jQuery/Plugins/Zoom.js');
            Page::CSSFile('CSS/jqzoom.css');
            Page::JS('$(".zoom").jqzoom();');
            $HTML = '<a class="nou zoom" title="Зум" href="/'.$Image.'">'.$HTML.'</a>';
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
            $HTML = '<a rel="lightbox" class="nou zoom" title="Зум" href="/'.$Image.'">'.$HTML.'</a>';
        break;

        case 'Text':
            $HTML = '/'.$ThumbURL;
        break;
    }

    return $HTML;
}