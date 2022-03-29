<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description: Simple HTML Renderer
     * @package Codeine
     * @version 8.x
     */

    setFn('Render', function ($Call) {
        if ($Call['Output']['Content'] === null) {
            $Call = F::Dot($Call, 'HTTP.Headers.Content-Type:', null);
            $Call = F::Dot($Call, 'HTTP.Headers.Content-Length:', 0);
        } else {
            $finfo = new finfo(FILEINFO_MIME);

            if (F::Dot($Call, 'HTTP.Headers.Content-Type:') === null) {
                $Call = F::Dot($Call, 'HTTP.Headers.Content-Type:', $finfo->file($Call['Output']['Content']));
            }

            $Call = F::Dot($Call, 'HTTP.Headers.Content-Length:', filesize($Call['Output']['Content']));
            $Call = F::Dot(
                $Call,
                'HTTP.Headers.Last-Modified:',
                gmdate('D, d M Y H:i:s T', filemtime($Call['Output']['Content']))
            );
            $Call['Output'] = file_get_contents($Call['Output']['Content']);
        }
        return $Call;
    });
