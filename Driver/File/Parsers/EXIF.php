<?php

  function F_EXIF_Parse($Filename)
  {
    $AllowedTags = array('ExposureTime','ISOSpeedRatings','DateTimeOriginal','Model','FNumber','FocalLength');
    $EXIF = exif_read_data($Filename);

    if (is_array($EXIF))
        foreach($AllowedTags as $Key)
            if (isset($EXIF[$Key]))
                {
                    if (mb_strpos($EXIF[$Key], '/'))
                        eval('$Value = '.$EXIF[$Key].';');
                    $Result[$Key] = $Value;
                }
    else
        $Result = array();
                
    return $Result;
  }