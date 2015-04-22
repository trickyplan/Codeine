<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        if (is_dir($Call['Task']['ID']))
            F::Log('Directory already exists', LOG_WARNING);
        else
        {
            F::Log('Trying to make directory…', LOG_INFO);

            if (mkdir($Call['Task']['ID']))
            {
                F::Log('Directory ' . $Call['Task']['ID'] . ' created', LOG_INFO);
                chdir($Call['Task']['ID']);

                foreach ($Call['Project']['Create']['Directory']['Paths'] as $Path)
                    if (mkdir($Path, 0777, true))
                        F::Log('Subdirectory ' . $Path . ' created', LOG_INFO);
                    else
                        F::Log('Subdirectory ' . $Path . ' failed to created', LOG_ERR);
            }
            else
            {
                F::Log('Directory ' . $Call['Task']['ID'] . ' failed to created', LOG_ERR);
                return null;
            }
        }

        return $Call;
    });