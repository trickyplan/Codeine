<?php

class Form
{
    private static $_Model;
    private static $_Name;

    public static function Model ($Model = null)
    {
        if (null === $Model)
            return self::$_Model;
        else
            self::$_Model = $Model;
    }

    public static function Load ($Name)
    {
        self::$_Name = $Name;
        $Object = new Object($Name);
        return self::$_Model = $Object->Model();
    }

    public static function Render ($Layout = 'Form/Default', $Data = array(), $URL = null)
    {
        if ($URL === null)
            $URL = Application::$Call;

        $Out  = array ();
        $FormOutput = '';

        foreach(self::$_Model->Nodes as $Name => $Field)
        {
            if (isset($Field->Protected) and $Field->Protected == 'True')
                continue;

            if (!isset($Field->Editor))
                $Field->Editor = 'Textfield';
            
            if (!isset($Field->Set))
                $Field->Set = '';

            $FID = str_replace(':','_', $Name);
            $Variants = array();

            if (isset($Field->Required) and $Field->Required == 'True') 
                $Element = array('Elements/'.$Field->Editor.'_Required', 'Elements/Default_Required');
            else
                $Element = array('Elements/'.$Field->Editor, 'Elements/Default');

            if (isset($Data[$Name]))
                {
                    if (sizeof($Data[$Name])== 1)
                        $Value = $Data[$Name][0];
                    else
                        $Value = $Data[$Name];
                }
            else
                $Value = null;

            if (isset($Field->Lookup))
            {
                $Lookup = Code::E('Data/Lookup', 'Lookup', $Field->Lookup->Data, $Field->Lookup->Type);
                if (isset($Field->I18N) and $Field->I18N == 'True')
                    foreach($Lookup as $cLookup)
                        $Variants[$cLookup] = '<l>'.self::$_Name.':'.$Name.':'.$cLookup.'</l>';
                else
                    foreach($Lookup as $cLookup)
                        $Variants[$cLookup] = $cLookup;
            }

            if (isset($Field->Multiple) and $Field->Multiple == 'True')
                $FName = $Name.'[]';
            else
                $FName = $Name;

            if (!isset($Field->Set))
                $Field->Set = 'General';
            
            $Out[$Field->Set][$Name] =
                Page::Replace (
                    $Element,
                    array ('<fid/>' => $FID,
                           '<editor/>'=> Code::E ('Output/Elements/Form','Render',
                                    array ( 'id'       => $FID,
                                            'name'     => $FName,
                                            'node'     => $Field,
                                            'value'    => $Value,
                                            'variants' => $Variants), $Field->Editor),
                           '<label/>' => self::$_Name.':'.$Name));
        }

        $Tabs = array();
        foreach($Out as $Set => $Fields)
        {
            $Tabs[] = '<span class="Form_Tab Tab" id="'.$Set.'"><l>'.$Set.'</l></span>';
            $FormOutput.= Page::Replace ('Form/Default_Set', array('<set/>' => $Set, '<content/>' =>implode ('<br /> ', $Fields)));
        }
        
        $Tabs = implode('',$Tabs);
        return Page::Replace($Layout, array('<tabs/>'=>$Tabs, '<url/>' => $URL, '<id/>'=>'FRM'.uniqid(), '<content/>' => $FormOutput));
    }
}