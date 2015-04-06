<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
      /*  if (isset($Call['Request']['Fields']))
            $Call['Fields'] = $Call['Request']['Fields'];

        $Count = F::Run('Entity', 'Count', $Call);

        $Pages = $Count / $Call['CSV']['Pagesize'];

        for ($PageNumber = 0; $PageNumber < $Pages; $PageNumber++)
        {
            $Elements = F::Run('Entity', 'Read', $Call);

        }

        $Call['View']['Renderer'] =
            [
                'Service' =>  'View.CSV',
                'Method' =>  'Render'
            ];

        foreach ($Elements as $Element)
            $Call['Output']['Content'][] =
                $Element;*/

        return $Call;
    });