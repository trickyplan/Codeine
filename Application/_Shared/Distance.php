<?php

  if (Client::$Level == 2)
    {
        $Object = new Object(self::$Name, self::$ID);

        if (($Object != Client::$Face))
                {
                    $lat2 = Client::$Face->Get("Geo:Latitude");
                    $lon2 = Client::$Face->Get("Geo:Longitude");

                    $lat1 = $Object->Get("Geo:Latitude");
                    $lon1 = $Object->Get("Geo:Longitude");

                    // krumo ($lon1, $lon2, $lat1, $lat2);
                    if (in_array(null, array($lat2,$lat1,$lon2,$lon1)))
                            $Output = '<l>App:Shared:Distance:Dunno</l>';
                    else
                        {
                            $Distance = (6378*3.1415926*sqrt(($lat2-$lat1)*($lat2-$lat1) + cos($lat2/57.29578)*cos($lat1/57.29578)*($lon2-$lon1)*($lon2-$lon1))/180);
                            $KMs = round($Distance).' км';
                            
                            if ($Distance>1000) $KMs = round($Distance/1000, 2).' тыс. км.';
                            if ($Distance<=1000) $KMs = round($Distance, 1).' км.';
                            if ($Distance<1) $KMs = round($Distance*1000).' м';

                            $Hours = '';

                            if ($Distance == 0)
                                $Output = '<l>App:Shared:Distance:Here</l>';
                            else
                                {
                                    $Transports = array('Walk'=>5, 'Bus'=>50, 'Train'=>40, 'Car'=>75, 'Fly'=> 500);

                                    foreach ($Transports as $Method => $Speed)
                                        {
                                            $Mins = ($Distance/($Speed/60));
                                                if ($Mins >= 60)
                                                    $Hours = ceil($Mins/60).' ч. ';
                                                else
                                                    $Hours = '';

                                            $TRs['<'.$Method.'/>'] = $Hours.($Mins % 60).' мин.';
                                        }

                                    $TRs['<KM/>']   = $KMs;
                                    $Output = Page::Replace('Objects/_Shared/Distance', $TRs);
                                }
                        }
                } else $Output = '<l>App:Shared:Distance:Here</l>';
    Page::Add($Output);
    }
    else
        Page::Add('Авторизуйтесь!');