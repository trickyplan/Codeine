<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $Call['Value'] = $_FILES['file'];

        $Call['ID'] = F::Run('Security.UID', 'Get', $Call);
        $Call['Name'] = F::Live($Call['Naming'], $Call);

        if (move_uploaded_file($_FILES['file']['tmp_name'], Root.$Call['Uploader']['Directory'].'/misc/'.$Call['Name']))
            $Call['Output']['filelink'] = '/uploads/misc/'.$Call['Name'];

        return $Call;
    });