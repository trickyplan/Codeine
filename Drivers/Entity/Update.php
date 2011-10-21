<?php

    /* Codeine
     * @author BreathLess
     * @description Create Action
     * @package Codeine
     * @version 6.0
     */

     self::Fn('Do', function ($Call)
     {
         if (isset($Call['Value']['IMDF']))
         {
             $ID = F::Run($Call, array('_F' => 'Submit'));
             header ('Location: /Show/'.$Call['Scope'].'/'.$ID); // FIXME
         }

         $Model = F::Run($Call,
             array(
                 '_N' => 'Engine.Object',
                 '_F' => 'Model.Load'
             ));

         $Object = F::Run(
                      array(
                      'Object' => array('Load', $Call['Scope']),
                      'ID' => $Call['ID'])
                  );

         $Object = $Object[$Call['ID']];

         $Widgets = array(
             array(
                 'Place' => 'Content',
                 'Type'  => 'Element',
                 'Widget' => 'Form.Panel',
                 'Action' => '',
                 'ID' => 'CreateForm',
                 'Value' => '<place>Create.Form</place>'
             )
         ); // FIXME Dirty Container Hack


         foreach($Model['Nodes'] as $Name => $Node)
         {
             if ($Node['Tags'] == 'Update' || in_array('Update', $Node['Tags']))
             {
                 $Editor = isset($Node['Editor'])? $Node['Editor']: 'Textfield';
                 $Widgets[] =
                     array(
                         'Place' => 'Create.Form',
                         'Type' => 'Element',
                         'Widget' => 'Form.'.$Editor,
                         'ID' => strtr($Name, '.','_'),
                         'Class' => array('Textfield',$Name),
                         'Name' => $Name,
                         'Value' => $Object[$Name]
                     );
             }
         }

         $Widgets[] = array(
                          'Place' => 'Sidebar',
                          'Type'  => 'Sidebar.Button',
                          'Value' => 'Ready',
                          'Action' => 'javascript:$(\'#CreateForm\').submit()',
                          'Anchor' => '/join',
                          'Subtext' => 'Create.'.$Call['Scope'].'.Ready.Subtext'
                  );

         $Widgets[] = array(
                          'Place' => 'Sidebar',
                          'Type'  => 'Sidebar.Button',
                          'Value' => 'Reset',
                          'Action' => '/',
                          'Anchor' => '/',
                          'Subtext' => 'Create.'.$Call['Scope'].'.Reset.Subtext'
                  );

         $Widgets[] =
                      array(
                          'Place' => 'Create.Form',
                          'Type' => 'Element',
                          'Widget' => 'Form.Hidden',
                          'Name' => 'IMDF',
                          'Value' =>  uniqid()
                      ); // FIXME Imitodefence

         return $Widgets;
     });

    self::Fn('Submit', function ($Call)
    {
        $ID = uniqid();
        
        if (isset($Call['Hooks']['after'.$Call['_F']]))
            foreach ($Call['Hooks']['after'.$Call['_F']] as $Hook)
                $Call = F::Run(F::Merge($Hook, array('Value' => $Call)), F::Kernel);

        F::Run(
            array(
                'Object' => array('Update', $Call['Scope']),
                'ID' => $ID,
                'Value' => $Call['Value'])
        );

        return $ID;
    });