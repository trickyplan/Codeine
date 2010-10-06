<?php

    function F_Image_Parse($Args)
    {
        $ProducedName = Code::E('Process/Hash', 'Get', $Args);

        $Args = json_decode($Args, true);

        $SourceWidth = 0;
        $SourceHeight = 0;

        if (($ImageFN = Server::Locate('Data', $Args['Image'])) == false)
            $ImageFN = Server::Locate('Images', 'NoPhoto.jpg');

        list($SourceWidth, $SourceHeight) = getimagesize($ImageFN);

        switch ($Args['Type'])
        {
            case 'Thumb':
                $Ratio = $Args['Size'] / $SourceWidth; // Коофициент сжатия

                $DestHeight = floor($SourceHeight * $Ratio);
                $DestWidth = $Args['Size'];

                $SourceX = 0;
                $SourceY = 0;

                $DestX = 0;
                $DestY = 0;
                
            break;

            case 'Clip':

                $DestHeight = $Args['Size'];
                $DestWidth = $Args['Size'];

                if ($SourceWidth > $SourceHeight)
                {
                    $SourceY = floor(($SourceWidth-$SourceHeight)/2);
                    $SourceX = 0;
                    $Q = $SourceHeight-$SourceY;
                }
                else
                {
                    $SourceX = floor(($SourceHeight-$SourceWidth)/2);
                    $SourceY = 0;
                    $Q = $SourceWidth-$SourceX;
                }

                $DestX = 0;
                $DestY = 0;

            break;
        }

        $DIM = imagecreatetruecolor($DestWidth, $DestHeight);

        $PI = pathinfo($ImageFN);

        if ($PI['extension'] == 'jpeg')
            $PI['extension'] = 'jpg';

        $ProducedName = Server::Path('Temp').'Image/'.$ProducedName.'.'.$PI['extension'];
        
        switch ($PI['extension'])
        {
            case 'jpg':
                $SIM = imagecreatefromjpeg($ImageFN);
                imagecopyresampled($DIM, $SIM, $DestX,$DestY,$SourceX,$SourceY, $DestWidth, $DestHeight, $SourceWidth, $SourceHeight);
                imagejpeg($DIM, Root.$ProducedName, 90);
                break;

            case 'png':
                $SIM = imagecreatefrompng($ImageFN);
                imagealphablending($SIM, false);
                imagealphablending($DIM, false);

                imagesavealpha($SIM, true);
                imagesavealpha($DIM, true);

                imagecopyresampled($DIM, $SIM, $DestX,$DestY,$SourceX,$SourceY, $DestWidth, $DestHeight, $SourceWidth, $SourceHeight);
                imagepng($DIM, Root.$ProducedName);
                break;

            case 'gif':
                $SIM = imagecreatefromgif($ImageFN);
                imagecopyresampled($DIM, $SIM, $DestX,$DestY,$SourceX,$SourceY, $DestWidth, $DestHeight, $SourceWidth, $SourceHeight);
                imagegif($DIM, Root.$ProducedName);
            break;
        }

        if (!isset($Args['Layout']))
            $Args['Layout'] = 'Default';
        
        $TRs = array('<src/>'=>$ProducedName);

        foreach ($Args as $Key => $Value)
            $TRs['<'.$Key.'/>'] = $Value;

        return View::Replace('Images/'.$Args['Layout'], $TRs);
    }