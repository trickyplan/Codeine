<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
     
     setFn('Do', function ($Call)
     {
         $SearchOptions = F::loadOptions('Search');
         $ElasticOptions = F::loadOptions('Search.Provider.Elastic');
         $Hosts = $ElasticOptions['Elastic Search']['Options']['hosts'];

         if (isset($SearchOptions['Providers']))
             foreach ($Hosts as $Host)
             {
                 $Table = [
                     ['Host', $Host]
                 ];

                 $Stats = F::Run('IO', 'Read',
                     [
                         'Storage' => 'Web',
                         'Format' => 'Formats.JSON',
                         'Where'   => $Host.'/project/_stats'
                     ]);

                 if ($Stats[0] === null)
                     $Table[] =
                         ['Status', 'Offline'];
                 else
                 {
                     $Table[] = ['Status', 'Online'];

                     $Table[] = ['Total', $Stats[0]['_all']['total']['docs']['count']];

                     foreach ($SearchOptions['Providers'] as $Mount)
                         if ($Mount['Service'] == 'Search.Provider.Elastic')
                         {
                             $Result = F::Run('IO', 'Read',
                                 [
                                     'Storage' => 'Web',
                                     'Format' => 'Formats.JSON',
                                     'Where'   => $Host.'/project/_search/?type='.$Mount['Call']['Type'].'&search_type=count'
                                 ]);

                             $Table[] = [
                                 '<l>'.$Mount['Call']['Scope'].'.Control:Title</l>',
                                 F::Run('Formats.Number.French', 'Do',
                                     [
                                         'Value' => $Result[0]['hits']['total']
                                     ])];
                         }
                 }


                 $Call['Output']['Content'][] =
                     [
                         'Type'  => 'Table',
                         'Value' => $Table
                     ];
             }

         return $Call;
     });

    setFn('Menu', function ($Call)
    {
        $ElasticOptions = F::loadOptions('Search.Provider.Elastic');
        $Hosts = $ElasticOptions['Elastic Search']['Options']['hosts'];

        $OnlineHosts = 0;

        foreach ($Hosts as $Host)
        {
            $Stats = F::Run('IO', 'Read',
                [
                    'Storage' => 'Web',
                    'Format' => 'Formats.JSON',
                    'Where'   => $Host.'/project/_stats'
                ]);

            if ($Stats[0] === null)
                ;
            else
                $OnlineHosts++;
        }

        $Call['Count'] = $OnlineHosts.'/'.count($Hosts);

        return $Call;
    });