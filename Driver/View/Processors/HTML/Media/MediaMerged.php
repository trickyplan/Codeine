<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Media Tag
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 23.11.10
     * @time 20:39
     */

    self::Fn('Process', function ($Call)
    {
        if (preg_match_all('@<media>(.*)</media>@SsUu',$Call['Input'], $Pockets))
        {
            $CSSOutput = '';
            $CSSFiles = array();

            $JSOutput = '';
            $JSFiles = array();

            foreach($Pockets[1] as $IX => $Match)
            {
                if (mb_substr($Match, mb_strlen($Match)-4) == '.css')
                {
                    if (mb_strpos($Match, '::'))
                    {
                        list ($Point, $ID) = explode('::', $Match);

                        $CSSFiles[$ID] = $ID.Data::Version($Point.'::'.$ID);
                    }
                }

                if (mb_substr($Match, mb_strlen($Match)-3) == '.js')
                {
                    if (mb_strpos($Match, '::'))
                    {
                        list ($Point, $ID) = explode('::', $Match);
                        $JSFiles[$ID] = $ID.Data::Version($Point.'::'.$ID);
                    }
                }
            }

            $CSSFile = Data::Path('Temp').'CSS/'.sha1(implode('', $CSSFiles)).'.css';

            if (true or !file_exists(Root.$CSSFile))
            {
                foreach($CSSFiles as $ID => $File)
                    $CSSOutput.= Data::Read($Point.'::'.$ID);

                Data::Create(
                    array(
                         'Point' => 'TempCSS',
                         'ID'    => $CSSFile,
                         'Body'  => $CSSOutput
                    )
                );
            }

            $JSFile = Data::Path('Temp').'JS/'.sha1(implode('', $JSFiles)).'.js';

            if (!file_exists(Root.$JSFile))
            {
                foreach($JSFiles as $ID => $File)
                    $JSOutput.= Data::Read($Point.'::'.$ID);

                Data::Create(
                    array(
                         'Point' => 'TempJS',
                         'ID'    => $JSFile,
                         'Body'  => $JSOutput
                    )
                );
            }

            $Call['Input'] =
                    str_replace(
                        '<mediacss/>',
                        '<link href="/'.$CSSFile.'" rel="stylesheet" />',
                        $Call['Input']);
            
            $Call['Input'] =
                    str_replace(
                        '<mediajs/>',
                        '<script type="text/javascript" src="/'.$JSFile.'"></script>',
                        $Call['Input']);

            $Call['Input'] = str_replace($Pockets[0], '', $Call['Input']);
        }

        return $Call['Input'];
    });
