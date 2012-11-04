<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */


    setFn('Create', function ($Call)
    {
        $Call['Image'] =
            [
                'Object' => imagecreatetruecolor($Call['Width'], $Call['Height']),
                'Width'  => $Call['Width'],
                'Height' => $Call['Height'],
                'Type' => $Call['Type']
            ];

        return $Call['Image'];
    });

    setFn('Load', function ($Call)
    {
        list ($Call['Image']['Widht'], $Call['Image']['Height'], $Call['Image']['Type']) = getimagesize($Call['ID']);

        switch ($Call['Image']['Type'])
        {
            case IMAGETYPE_GIF:
                $Call['Image']['Object'] = imagecreatefromgif($Call['ID']);
                $Call['Image']['Type'] = 'GIF';
            break;

        	case IMAGETYPE_JPEG:
                $Call['Image']['Object'] = imagecreatefromjpeg($Call['ID']);
                $Call['Image']['Type'] = 'JPEG';
            break;

        	case IMAGETYPE_PNG:
                $Call['Image']['Object'] = imagecreatefrompng($Call['ID']);
                $Call['Image']['Type'] = 'PNG';
            break;

        	case IMAGETYPE_SWF:
                // TODO SWF Support
            break;

        	case IMAGETYPE_PSD:
                // TODO PSD Support
            break;

        	case IMAGETYPE_BMP:
                // TODO BMP Support
            break;

        	case IMAGETYPE_TIFF_II:
                // TODO TIFF Support
            break;

        	case IMAGETYPE_TIFF_MM:

            break;

        	case IMAGETYPE_JPC:
                // TODO JPC Support
            break;

        	case IMAGETYPE_JP2:
                // TODO JP2 Support
            break;

        	case IMAGETYPE_JPX:
                // TODO JPX Support
            break;

        	case IMAGETYPE_JB2:
                // TODO JB2 Support
            break;

        	case IMAGETYPE_SWC:
                // TODO SWC Support
            break;

        	case IMAGETYPE_IFF:
                // TODO IFF Support
            break;

        	case IMAGETYPE_WBMP:
                $Call['Image']['Object'] = imagecreatefromwbmp($Call['ID']);
                $Call['Image']['Type'] = 'WBMP';
            break;

        	case IMAGETYPE_XBM:
                $Call['Image']['Object'] = imagecreatefromxbm($Call['ID']);
                $Call['Image']['Type'] = 'XBM';
            break;

            case IMAGETYPE_ICO:
                // TODO ICO Support
            break;
        }

        // TODO WEBP Support
        // TODO TGA Support

        return $Call['Image'];
    });

    setFn('Save', function ($Call)
    {
        // TODO Realize "Save" function
        switch ($Call['Image']['Type'])
        {
            case 'GIF':
                imagegif($Call['Image']['Object'], $Call['ID']);
            break;

        	case 'JPEG':
                imagejpeg($Call['Image']['Object'], $Call['ID'], $Call['JPEG']['Quality']);
            break;

        	case 'PNG':
                imagepng($Call['Image']['Object'], $Call['ID'], $Call['JPEG']['Quality']);
            break;

            case 'SWF':
                // TODO SWF Support
            break;

            case 'PSD':
                // TODO PSD Support
            break;

            case 'BMP':
                // TODO BMP Support
            break;

        	case 'TIFF_II':
                // TODO TIFF Support
            break;

        	case 'TIFF_MM':

            break;

        	case 'JPC':
                // TODO JPC Support
            break;

        	case 'JP2':
                // TODO JP2 Support
            break;

        	case 'JPX':
                // TODO JPX Support
            break;

        	case 'JB2':
                // TODO JB2 Support
            break;

        	case 'SWC':
                // TODO SWC Support
            break;

        	case 'IFF':
                // TODO IFF Support
            break;

        	case 'WBMP':
                imagewbmp($Call['Image']['Object'], $Call['ID']);
            break;

        	case 'XBM':
                imagexbm($Call['Image']['Object'], $Call['ID']);
            break;

            case 'ICO':
                // TODO ICO Support
            break;
        }

        // TODO WEBP Support
        // TODO TGA Support

        return $Call;
    });