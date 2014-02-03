<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Write', function ($Call)
    {
        $Call['Value'] = $_FILES['file'];

        $Call['ID'] = F::Run('Security.UID', 'Get', $Call);
        $Call['Data'] = file_get_contents($_FILES['file']['tmp_name']);
        $Call['Name'] = F::Live($Call['Naming'], $Call);

        F::Run('IO', 'Write', $Call,
            [
                 'Storage' => 'Upload',
                 'Scope'   => 'WYSIWYG',
                 'Where'   => $Call['Name']
            ]);

        $Call['Output']['Content']['filelink'] = '/uploads/'.$Call['Name']; // FIXME Scope support

        return $Call;
    });

    setFn('Read', function ($Call)
    {
        $Call['Output']['Content'] = F::Run('IO', 'Read', $Call,
            [
                 'Storage' => 'Upload',
                 'Scope'   => 'WYSIWYG'
            ]);

        if (empty($Call['Output']['Content']))
            $Call = F::Hook('NotFound', $Call);

        return $Call;
    });