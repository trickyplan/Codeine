<?php

  function F_Form_Parse($Args)
  {
      $Args = json_decode($Args);

      $Output = '';
      $Facets = array();

      if (!isset($Args->URL))
            $Args->URL = Application::$Call;

      if (isset($Args->Object))
      {
          $Object = new Object($Args->Object);
          $Args->Data = $Object->Data();
          $Facets = $Object->Get('Facet', false, false);
      }

      if (!is_array($Args->Model))
      {
          $FormName = $Args->Model;
          $Args->Model = Data::Read('Model',
               array('I'=>$Args->Model, 'Facets'=>$Facets));
      }
      else
          $FormName = $Args->Name;

      foreach($Args->Model->Nodes as $Name => $Field)
      {
            if (isset($Field->Protected) and $Field->Protected == 'True')
                continue;

            // Default Editor
            if (!isset($Field->Editor))
                $Field->Editor = 'Textfield';

            // Default Set
            if (!isset($Field->Set))
                $Field->Set = 'General';

            if (!isset($Field->Required))
                $Field->Required = 'false';
            else
                $Field->Required = 'true';
            
            // Generating Field ID
            $FID = str_replace(':','_', $Name);

            // Required fields has own layouts
            
            $Element = array('Form/Elements/'.$Field->Editor, 'Form/Elements/Default');

            // Populating form with data
            if (isset($Args->Data[$Name]))
                {
                    if (count($Args->Data[$Name])== 1)
                        $Value = $Args->Data[$Name][0];
                    else
                        $Value = $Args->Data[$Name];
                }
            else
                $Value = null;

            $Variants = array();

            if (isset($Field->Lookup))
                {
                    $Lookup = Code::E('Data/Lookup', 'Lookup', $Field->Lookup->Data, $Field->Lookup->Type);
                    if (is_array($Lookup))
                    {
                        if (isset($Field->I18N) and $Field->I18N == 'True')
                            foreach($Lookup as $cLookup)
                                $Variants[$cLookup] = '<l>'.$FormName.':'.$Name.':'.$cLookup.'</l>';
                        else
                            foreach($Lookup as $cLookup)
                                $Variants[$cLookup] = $cLookup;
                    }
                }

            $Out[$Field->Set][$Name] =
                    View::Replace (
                        $Element,
                        array ('<fid/>' => $FID,
                               '<required/>' => (string)$Field->Required,
                               '<editor/>'=> Code::E ('Output/Elements/Form','Render',
                                        array ( 'id'       => $FID,
                                                'node'     => $Field,
                                                'name'     => $Name,
                                                'value'    => $Value,
                                                'variants' => $Variants), $Field->Editor),
                               '<label/>' => $FormName.':'.$Name));
      }

      if (count($Out)>1)
          foreach ($Out as $Title => $Set)
          {
              $Output.= View::Replace ('Form/Set/Default',
                      array(
                            '<formname/>' => $FormName,
                            '<title/>' => $Title,
                            '<content/>' => implode('',$Set)));
          }
      else
        foreach ($Out as $Title => $Set)
          {
              $Output = View::Replace ('Form/Set/One',
                      array(
                            '<formname/>' => $FormName,
                            '<title/>' => $Title,
                            '<content/>' => implode('',$Set)));
          }
      
      return View::Replace ('Form/Body/Default',
                  array(
                      '<url/>' => $Args->URL,
                      '<id/>'=>'FRM'.uniqid(),
                      '<content/>' => $Output));
      return $Output ;
  }