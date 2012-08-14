<?php

    /* Codeine
     * @author BreathLess
     * @description: 
     * @package Codeine
     * @version 7.6.2
     * @date 13.08.11
     * @time 22:37
     */

    self::setFn ('Write', function ($Call)
    {
        if(!empty($Call['Value']['name']))
        {
            $Call['Name'] = F::Live($Call['Naming'], $Call);

            if (isset($Call['MIME']))
                if (!in_array($Call['Value']['type'],$Call['MIME']))
                    return null;

            if (move_uploaded_file($Call['Value']['tmp_name'], Root . '/' . $Call['Directory'] . '/' . $Call['Scope'] . '/' .$Call['Name']))
                return $Call['Name'];
            else
            {
                d(__FILE__, __LINE__, $Call);
                d(__FILE__, __LINE__, Root . '/' . $Call['Directory'] . '/' . $Call['Scope'] . '/' .$Call['Name']);
                die('Uploading failed');
            }
        }
        else
            return null;

    });

    self::setFn('Read', function ($Call)
    {
        return file_get_contents(Root . '/' . $Call['Directory'] . '/' . $Call['Scope'] . '/' . $Call['Where']['ID']);
    });