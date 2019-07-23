<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    function array_slice_recursive($Array, $Length)
        {
            if (is_array($Array))
            {
                $Array = array_slice($Array, 0, $Length);
                foreach ($Array as &$Item)
                    $Item = array_slice_recursive($Item, $Length);
            }

            return $Array;
        }

    setFn('Do', function ($Call)
    {
        if ($Length = F::Dot($Call, 'Test.Case.Result.Slice'))
        {
            $Cut = F::Dot($Call, 'Test.Case.Result.Actual');

            if (is_array($Cut))
                $Cut = array_slice_recursive($Cut, $Length);

            $Call = F::Dot($Call, 'Test.Case.Result.Actual', $Cut);
        }

        return $Call;
    });