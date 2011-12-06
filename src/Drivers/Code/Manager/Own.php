<?php

   /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

    self::setFn ('Scan', function ($Call)
       {
          $Drivers = F::Run (
              array(
                   '_N' => 'Code.Source.Enumerate.Driver',
                   '_F' => 'ListAll'
              )
          );
           d(__FILE__, __LINE__, $Drivers);
          $Output = array();

          foreach ($Drivers as $Driver)
          {
              $Definition = F::Run (array('_N' => 'Code.Manager.Package','_F' => 'Definition',
                                  'Value' => $Driver));

              if (!empty($Definition))
                $Output[sha1(serialize($Definition))] = $Definition;
          }

          return $Output;
       });

    self::setFn ('Construct', function ($Call)
    {
        $Packages = F::Run (array('_N' => 'Code.Manager.Own','_F' => 'Scan'));

        file_put_contents(Root.'/Data/Packages.json', json_encode($Packages));

        $Call['Widgets'] = array();

        return $Call;
    });