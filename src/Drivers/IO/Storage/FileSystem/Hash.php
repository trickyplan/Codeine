<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Do', function ($Call)
    {
        if (isset($Call['IO']['FileSystem']['Hashing']['Enabled']) && $Call['IO']['FileSystem']['Hashing']['Enabled'])
        {
            $Call['Hash'] = [];

            if (isset($Call['Where']['ID']) && is_array($Call['Where']['ID']))
                foreach ($Call['Where']['ID'] as &$ID)
                {
                    $Hash = sha1($ID);
    
                    for ($IX = 0; $IX < $Call['IO']['FileSystem']['Hashing']['Levels']; $IX++)
                        $Call['Hash'][] = mb_substr($Hash, $IX, $Call['IO']['FileSystem']['Hashing']['Size']);
    
                    $ID = implode(DS, $Call['Hash']).DS.$ID;
                }
        }

        return $Call;
    });