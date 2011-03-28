<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Delete Controller
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 01.12.10
     * @time 20:56
     */

    self::Fn('Delete', function ($Call)
    {
        Data::Delete(
                array(
                     'Point'=> $Call['Entity'],
                     'Where'=>
                        array(
                            'ID' => $Call['ID'])));

        $Call['Items'] = array();
        Code::On('Entity.Delete.Object.Deleted', $Call);
        return $Call;
    });
