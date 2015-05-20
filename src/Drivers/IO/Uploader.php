<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Write', function ($Call)
    {
        $Call['Value'] = $_FILES['file'];

        $Call['ID'] = F::Run('Security.UID', 'Get', $Call);
        $Call['Data'] = file_get_contents($_FILES['file']['tmp_name']);
        $Call['Name'] = F::Live($Call['Naming'], $Call);

        F::Run('IO', 'Write', $Call,
            [
                 'Storage' => 'Uploader',
                 'Scope'   => 'wysiwyg',
                 'Where'   => $Call['Name']
            ]);

        $Call['Output']['Content']['filelink'] = '/uploads/wysiwyg/'.$Call['Name']; // FIXME Scope support

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