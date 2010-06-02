<?php

/*
 * Palladium ORM Object
 */

class Object
    {
        public  $Point;

        public  $Scope;
        public  $Name;
        public  $Time;
        public  $Plane;
        public  $Universe;

        public  $ORM;
        
        private $Privacy;
        private $_Model;
        private $_ModelLoaded;
        public  $Loaded;

        private $DML = array ();

        private $Data;
        private $InheritData;
        private $SelfData;

        private $Keys;

        public function __toString()
        {
            return $this->Scope.OBJSEP.$this->Name;
        }

        public function __construct ($Point = null, $Name = null, $ORM = null)
        {
            if (null === $Point or $Point == '_')
                $Point = Application::$Name;

            $this->Scope = $this->Point;

            if (null !== $Name)
                {
                    $this->Name($Name);
                    $this->Point = $Point;
                }
            elseif ($ORM == null)
            {
                if (mb_strpos($Point, OBJSEP) !== false)
                    list($this->Point, $this->Name) = explode(OBJSEP, $Point);
                else
                    $this->Point = $Point;
            }

            $this->Scope = $this->Point;

            if (null === $ORM)
                $this->ORM = Data::GetORM($this->Point);
            else
                $this->ORM = $ORM;

            if (null !== $Name)
                $this->Load($Name);

            return $this;
        }

        public function Mode ($Mode = 'Single')
        {
            // TODO Fluent Structures! Epic Task!
            if (null !== $Mode)
                {
                    if (in_array ($Mode, array ('Single', 'List', 'Tree','Cycle', 'BTree','RBTree','Heap')))
                        {
                            $this->Mode = $Mode;
                            $Mode = 'To'.$Mode;
                            $this->$$Mode();
                        }
                }
            else
                return $this->Mode;
        }  // Хамелеон

        public function Scope ($Scope = null)
        {
            if (null !== $Scope)
                $this->Scope = $Scope;
            else
                return $this->Scope;
        }  // Хамелеон

        public function Name ($Name = null)
        {
            if ($Name !== null)
                $this->Name = $Name;
            else
                return $this->Name;
        }  // Хамелеон

        public function Data($Data = null)
        {
            if (!$this->Loaded && null === $Data)
                $this->Load();

            if (null === $Data)
                return $this->Data;
            else
                $this->Data = $Data;
        }

        public function FlattenData()
        {
            if (!$this->Loaded && null === $Data)
                $this->Load();

            $Data = array();

            foreach($this->Data as $Key => $Value)
                $Data[$Key] = $Value[0];

            return $Data;
        }

        private function iDML ($Method, $Key, $Value = null, $Old = null)
        {
            $this->DML[$Method][] = array ('Key' => $Key,
                                  'Value' => $Value,
                                  'Old'=> $Old
                                  );
        }

        private function _Privacy()
        {
            //$this->LoadModel();
            return ;
            if((string) $this->_Model['Privacy'] == 'true')
            {
                $Grant = new Object('Grant');
                $Grant->Query('=To='.Client::$Face);
                $Grant->Query('=Face='.$this->Name, 'Intersect');
                $Grant->Load();

                $Allowed = $Grant->Get($this->Scope, false);

                if (null === $Allowed)
                    $Allowed = array();

                foreach($this->_Model as $Node)
                    if (isset($Node->isPrivate))
                        {
                            if (!in_array($Node->Name, $Allowed))
                                $this->Data[$Node->Name] = array('***');
                        }
            }
        }

        public function Load ($Name = null, $Inheritated = true)
        {
            Timing::Go ($this->Scope.':'.$Name.':Load');

            $Result = null;

            if (Access::Check($this, 'Load'))
            {
                $this->Data = array();
                $this->SelfData = array();
                $this->InheritData = array();

                if ((null === $Name) && (empty($this->Name)))
                        return false;

                if (null !== $Name)
                    {
                        $this->Name = $Name;
                        $Inheritated = true;
                    }

                if (null !== ($PooledData = Data::PoolGet($this->Scope.'::'.$this->Name)))
                    {
                        list($this->Data, $this->SelfData,$this->InheritData) = $PooledData;
                        Timing::Stop ($this->ORM.':Load');
                        Timing::Stop ($this->Scope.':'.$Name.':Load');
                        return ($this->Loaded = true);
                    }

                $DSCID = 'Default:'.$this->Scope;

                if (null === ($Default = Data::PoolGet($DSCID)))
                {
                    $Data = Code::E('Data/Mappers', 'Load', array('Scope'=> $this->Scope,'Point'=>$this->Point, 'Name'=> array($this->Name, 'Default')), $this->ORM);

                    if ($Data !== null)
                    {
                        if (!isset($Data['Default']))
                            $Default = array();
                        else
                            $Default = $Data['Default'];

                        Data::PoolPut($DSCID, $Default);

                        $Data = $Data[$this->Name];
                    }
                    else
                        return null;
                }
                else
                    $Data = Code::E('Data/Mappers', 'Load', array('Scope'=> $this->Scope,'Point'=>$this->Point, 'Name'=> $this->Name), $this->ORM);

                if (!empty($Data))
                {
                    $InheritatedData = array ();
                    $this->Data = $Default;

                        if ($Inheritated && isset ($Data['Inherit']))
                        {
                            if (is_array ($Data['Inherit']))
                                {
                                    $InheritatedData = Code::E('Data/Mappers', 'Load', array('Scope'=>$this->Scope,'Point'=>$this->Point, 'Name'=>$Data['Inherit']), $this->ORM);

                                    if (is_array($InheritatedData))
                                        foreach($InheritatedData as $InData)
                                            if (is_array($InData))
                                                foreach($InData as $Key => $Value)
                                                    $this->InheritData[$Key] = $Value;
                                }

                            if (is_array($this->InheritData))
                                foreach($this->InheritData as $Key => $Value)
                                    $this->Data[$Key] = $Value;
                        }


                        if (is_array($Data))
                        {
                            foreach($Data as $Key => $Value)
                                {
                                    $this->Data[$Key] = $Value;
                                    $this->SelfData[$Key] = $Value;
                                }
                        }

                        $this->Loaded = true;
                        $this->Keys = array_keys ($this->Data);
                        $Result = true;
                    }

                Data::PoolPut($this->Scope.':'.$this->Name, array(&$this->Data, &$this->SelfData, &$this->InheritData));
                // Data::CachePut('VOCache', $this->Scope.':'.$this->Name, $this->Data);

                // $this->_Privacy();
            }

            Timing::Stop ($this->Scope.':'.$Name.':Load');

            return $Result;
        }

        public function Save ()
        {
            if (Access::Check($this, 'Save'))
            {
                if (!$this->Loaded)
                    $this->Load();

                if (!empty($this->DML) and $this->Check($this->Data))
                {
                    $Result = Code::E('Data/Mappers', 'Save', array('Scope'=> $this->Scope,
                                                    'Point' => $this->Point,
                                                    'Name' => $this->Name,
                                                    'Data' => $this->Data,
                                                    'DML' => $this->DML
                                                    )
                                                ,$this->ORM);
                }
                else
                    $Result = Log::Warning('Бессмысленный вызов ORM Save '.$this->Point.' > '.$this->Name);

                $this->DML = array();
            }
            else
                 $Result = null;

            return $Result;
        }

        public function Get ($Key, $Ordinary = true, $ForceArray = true, $Index = 0)
        {
            if (!$this->Loaded)
                $this->Load();

            $Data = null;

            if (is_array($Key))
            {
                $Data = array();
                foreach ($Key as $cKey)
                    $Data[$cKey] = $this->Get($cKey, $Ordinary, $ForceArray, $Index);
            }
            else
                {
                    if (isset ($this->Data[$Key]))
                        {
                            if (isset($this->Privacy[$Key]) && !$this->Privacy[$Key])
                                    $Data = 'Private';

                            if ($Ordinary)
                            {
                                if (isset ($this->Data[$Key][$Index]))
                                    $Data = $this->Data[$Key][$Index];
                                else
                                    $Data = null;
                            }
                            else
                            {
                                if (!$ForceArray&&sizeof ($this->Data[$Key]) == 1)
                                    $Data = $this->Data[$Key][0];
                                else
                                    $Data = $this->Data[$Key];
                            }
                        }
                }

            return $Data;
        }

        public function GetOr ($Keys, $Default = null)
        {
            foreach ($Keys as $Key)
                if (null !== ($R = $this->Get($Key)))
                    return $R;
            return $Default;
        }

        public function GetByMask ($Pattern, $Ordinary = true, $ForceArray = true, $Index = 0)
        {
            if (!$this->Loaded)
                $this->Load ();

            $Data = array ();
            foreach($this->Keys as $Key)
                if (mb_ereg($Pattern.'(.*)',$Key))
                    $Data[$Key] = $this->Get($Key, $Ordinary, $ForceArray, $Index);

            return $Data;
        }

        public function GetKeyOf ($Value, $Ordinary = true, $ForceArray = true)
        {
            if (!$this->Loaded)
                $this->Load();

            $Keys = array ();

            foreach($this->Data as $Key => $kData)
                if (array_search ($Value, $kData) !== false)
                    {
                        if (mb_substr($Key, 0,1) != '_')
                            $Keys[] = $Key;
                    }

            if ((!$ForceArray && sizeof ($Keys) == 1) or ($Ordinary))
                $Keys = $Keys[0];

            if (empty ($Keys) and !$ForceArray)
                $Keys = null;

            return $Keys;
        }

        public function Query ($Selector, $CheckID = null)
        {
            $IDs = null;
            if (mb_strpos($Selector, '::'))
                list($this->Scope, $Selector) = explode('::', $Selector);
            if (!empty ($Selector))
                    {
                        if (mb_strpos($Selector, ';'))
                                {
                                    $Selectors = explode(';',$Selector);
                                    foreach($Selectors as $Selector)
                                        $this->Query($Selector, 'Intersect');
                                }
                        else
                        {
                            $QUID = $this->Scope.':'.$Selector;
                            if (null === ($IDs = Data::PoolGet($QUID)))
                                {
                                    $IDs = Code::E('Data/Mappers', 'Query', array('Scope'=> $this->Scope,'Point'=>$this->Point, 'Selector'=>$Selector), $this->ORM);
                                        Data::PoolPut($QUID, $IDs);
                                }
                        }
                    }
                else
                    return Log::Error ('Object: Пустой селектор');

            if ($CheckID !== null)
                if (in_array ($CheckID, $IDs))
                    return true;
                else return false;

            if (sizeof ($IDs)>1)
                Log::Warning ('Селектор в Object получил не единственный объект');
            elseif (sizeof ($IDs) == 0 or null == $IDs)
                return null;

            $this->Load (min ($IDs));
            return true;
        }

        public function Add ($Key, $Value = null, $AllowDuplicates = false)
        {
            if (empty ($Key))
                return Log::Error ('Object:Add Не указан ключ');

            if (!$this->Loaded)
                $this->Load();
            
            if (null !== $Value)
            {
                if (is_array ($Value))
                    foreach($Value as $cValue)
                        $this->Add ($Key, $cValue, $AllowDuplicates);
                else
                {
                    if (isset ($this->SelfData[$Key]))
                        {
                            if (!$AllowDuplicates && in_array ($Value, $this->SelfData[$Key]))
                                return Log::Warning ('Object:Add Дублирующаяся нода '.$Key.' не добавлена');
                        }
                    $this->Data[$Key][] = $Value;
                    $this->iDML('Add', $Key, $Value);
                    return true;
                }
            }
            else
            {
                if (is_array($Key))
                    foreach($Key as $K => $V)
                        $this->Add($K, $V);
                else
                    return Log::Error ('Object:Add Не указано значение для '.$Key);
            }

        }

        public function Del ($Key, $Value = null)
        {
            if (!empty ($Key))
            {
                if (!$this->Loaded)
                    $this->Load();
                
                if (is_array ($Key))
                    foreach($Key as $cKey)
                        $this->Del ($cKey, $Value);
                else
                {
                    if (isset ($this->Data[$Key]))
                        {
                            if (null === $Value)
                                {
                                    unset ($this->Data[$Key]);
                                    unset ($this->SelfData[$Key]);

                                    $this->iDML('Del', $Key);
                                }
                            else
                                {
                                    $Ix = array_search ($Value, $this->SelfData[$Key]);
                                    if ($Ix === false)
                                        return Log::Warning ('Object:Del Не найдено удаляемое значение');

                                    unset ($this->Data[$Key][$Ix]);
                                    unset ($this->SelfData[$Key][$Ix]);
                                    $this->iDML('Del', $Key, null, $Value);
                                }
                        }
                    else
                        return Log::Warning ('Object:Del Несуществующая нода '.$Key.' не удалена');
                }
            }
            else
                return Log::Error ('Object:Del Не указан ключ');

            return true;
        }

        public function Set ($Key, $Value = null, $Old = null)
        {
            if (is_array($Key) && (null === $Value))
                foreach ($Key as $K => $V)
                    $this->Set($K, $V);

            if (!$this->Loaded)
                $this->Load();

            if (!empty ($Key))
            {
                    if (is_array($Value))
                    {
                        $this->Del($Key);
                        $this->Add($Key, $Value);
                    }
                    else
                    {
                        if (isset ($this->SelfData[$Key]))
                            {
                                if (null === $Old)
                                    {
                                        $this->Data[$Key] = array ($Value);
                                        $this->SelfData[$Key] = array ($Value);
                                        $this->iDML('Set', $Key, $Value);
                                        return true;
                                    }
                                else
                                    {
                                        $Ix = array_search ($Old, $this->SelfData[$Key]);
                                        if ($Ix === false)
                                            return Log::Error ('Object:Set Не найдено изменяемое значение');

                                        $Ix = array_search ($Value, $this->SelfData[$Key]);
                                        if ($Ix === false)
                                            return Log::Error ('Object:Set Найден дубликат значения');

                                        $this->Data[$Key][$Ix] = $Value;
                                        $this->SelfData[$Key][$Ix] = $Value;
                                        $this->iDML('Set', $Key, $Value, $Old);
                                        return true;
                                    }
                            }
                        else
                            {
                                if ($this->Add ($Key, $Value))
                                    return Log::Info ('Object:Set Несуществующая нода создана');
                                else
                                    return Log::Warning ('Object:Set Несуществующая нода не создана');
                            }
                    }
            }
            else
                return Log::Error ('Object:Set Не указан ключ');

            return true;
        }

        public function Toggle ($Key, $Value)
        {

            if (!isset($this->Data[$Key]))
                    $this->Data[$Key] = array();

            if (!in_array($Value, $this->Data[$Key]))
                {
                    $this->Add($Key, $Value);
                    return true;
                }
            else
                {
                    $this->Del($Key, $Value);
                    return false;
                }
        }

        public function Node ($Key, $Value = null)
        {
            if (null !== $Value)
            {
                if ($Value === false)
                    $this->Del ($Key);
                else
                    $this->Sel ($Key, $Value);
            }
            else
                return $this->Get($Key);
        }// Хамелеон

        public function GetValues () {}

        public function Inc ($Key, $Inc =1)
        {
            $Value = $this->Get($Key);

            if ($Value === null)
                $NV = $Inc;
            else
                $NV = $Value+$Inc;

            $this->Set($Key, $NV);
        }

        public function Process (){}
        public function Erase ()
        {
            Code::E('Data/Mappers', 'Erase', array('Scope'=> $this->Scope,'Point'=>$this->Point, 'Name'=> $this->Name), $this->ORM);
            if (file_exists(Root.Data.$this->Scope.'/'.$this->Name) and is_dir(Root.Data.$this->Scope.'/'.$this->Name))
                rmdir(Root.Data.$this->Scope.'/'.$this->Name);
            return true;
        }
        public function Reset (){}
        public function Merge () {}
        public function Split () {}

        public function Sort () {}
        public function Convert () {}


        public function Time (){}// Хамелеон

        public function MoveToTime ($Time){}// Переносит весь объект во времени
        public function CopyToTime ($Time){} // Копирует весь объект во времени

        public function GetFarFuture (){}
        public function GetOldPast (){}

        public function AllTimes(){}

        public function TimeCollapse(){}

        // Методы 5D

        public function Plane (){} // Хамелеон

        public function MoveToPlane ($Time){} // Переносит весь объект в плоскость
        public function CopyToPlane ($Time){} // Копирует весь объект в плоскость

        public function AllPlanes(){}

        public function CollapsePlanes(){}

        // Методы 6D

        public function Universe (){} // Хамелеон

        public function MoveToUniverse ($Time){} // Переносит весь объект во вселенную
        public function CopyToUniverse ($Time){} // Копирует весь объект во вселенную

        public function AllUniverses(){}

        public function CollapseUniverses(){}

        public function AllValues()
        {

        }
        public function AllKeys()
        {
            if (!$this->Loaded)
                $this->Load();

            if (!empty($this->Data))
                return array_keys($this->Data);
            else
                return array();
        }
        public function AllIDs(){}

        // Проверки существования

        public function isExistID ($ID = false){}
        public function isExistKey ($Key){}
        public function isExistValue ($Value){}
        public function isExistNode ($Key){}

        // Связывание

        public function AddEntangle ($ID){}
        public function DelEntangle ($ID){}
        public function GetEntangled ($ID){}
        public function AddMirror ($ID){}
        public function DelMirror ($ID){}


        private function _LoadModel ($Name = null)
        {
            if (null === $Name)
                $Name = $this->Scope;
            
            if (!$this->_ModelLoaded)
            {
                if (Data::Exist ('Model','{"I":"'.$Name.'"}'))
                {
                    $this->_Model = Data::Read ('Model','{"I":"'.$Name.'"}');
                    if (isset($this->_Model->Facets))
                        foreach($this->_Model->Facets as $Facet)
                        {
                            $Facet = Data::Read ('Model','{"I":"Facets/'.$Facet.'"}');
                            $this->_Model->Nodes = (object) array_merge((array)$this->_Model->Nodes, (array)$Facet->Nodes);
                        }
                    $this->_ModelLoaded = true;
                }
                else
                    return false;
            }
            
        return true;
      }

      public function Check ($Data = null, $Purpose = 'Create')
      {
          $Errors = array();

          $this->_LoadModel();

          if (null === $Data)
              $Data = $this->Data;

          $UniqueTesting = new Collection($this->Scope);

          if (isset($Data['I']))
              if ((array_intersect($Data['I'], $UniqueTesting->VariantsOf('I'))) !== array())
                    $Errors[$Name] = 'Unique';

          if (isset($this->_Model->Nodes ))
          {
            foreach($this->_Model->Nodes as $Name => $Node)
            {
                if (isset($Node->WriteOnce) and ($Node->WriteOnce == 'True') and isset($Data[$Name]) and $Purpose != 'Create')
                    $Errors[$Name][] = 'WriteOnce';

                if (isset($Node->Required) and  ($Node->Required == 'True') and empty($Data[$Name]))
                    $Errors[$Name][] = 'Required';
                else
                {
                   if (isset($Node->Multiple) and ($Node->Multiple != 'True') and sizeof($Data[$Name])>1)
                         $Errors[$Name][] = 'Multiple';

                   if (isset($Data[$Name]) and is_array($Data[$Name]))
                       foreach($Data[$Name] as $Value)
                            if (true !== ($ErMsg = Code::E ('Data/Types','Validate', array ('Key'=>$Name, 'Value'=> $Value, 'Node' => $Node), $Node->Type)))
                                $Errors[$Name][] = $ErMsg;

                   if (empty($Errors[$Name]))
                   {
                        if (isset($Node->Unique) and $Node->Unique == 'True')
                                if ((array_intersect($Data[$Name], $UniqueTesting->VariantsOf($Name))) !== array())
                                    $Errors[$Name][] = 'Unique';
                   }
                }
            }
          }
         if (empty($Errors))
             $Errors = true;
         
         return $Errors;
        }

        public function Model ($Name = null)
        {
            if (null !== $Name)
                self::_LoadModel($Name);
            else
                self::_LoadModel($this->Scope);

            return $this->_Model;
        }

        public function Create ($Data)
        {
            if (($Check = $this->Check($Data)) !== true)
                return $Check;

            if (!$this->Loaded)
                $this->Load();
            
            if ($this->_LoadModel())
            {

                if (isset($this->_Model->Options->UID))
                    $UIDD = $this->_Model->Options->UID;
                else
                    $UIDD = 'Default';

                if (isset($Data['I']))
                    $this->Name($Data['I']);
                else
                    $this->Name = Code::E('Generator/UniqueID','Generate',$this->Scope, $UIDD);

                if (isset($this->_Model->Options->Folder))
                    mkdir(Root.Data.$this->Scope.'/'.$this->Name, 0777, true);

                foreach($this->_Model->Nodes as $Name => $Node)
                {
                    if (isset($Data[$Name]) && !empty($Data[$Name]))
                        {
                            if (!is_array($Data[$Name]))
                                $Data[$Name] = array($Data[$Name]);

                                foreach($Data[$Name] as $K => $Value)
                                    {
                                        $Data[$Name][$K] = Code::E('Data/Types', 'Input',
                                            array('Key'   => $Name,
                                                  'Value' => $Value,
                                                  'Name'  => $this->Name,
                                                  'Scope'=> $this->Scope), $Node->Type);

                                        if (is_array($Data[$Name][$K]['Aux']))
                                            {
                                                foreach($Data[$Name][$K]['Aux'] as $AuxK => $AuxV)
                                                    $this->Add($Name.':'.$AuxK, $AuxV);

                                                $Data[$Name][$K] = $Data[$Name][$K]['Value'];
                                            }
                                    }
                            $this->Add($Name, $Data[$Name]);
                        }
                }

                $this->Add('Owner',Client::$UID);

                if (Client::$Level == 2)
                    $this->Add('Face' , (string) Client::$Face);

                if (!isset($Data['CreatedOn']))
                    $Data['CreatedOn'] = time();
                
                $this->Set('CreatedOn', $Data['CreatedOn']);

                $this->Save();
            }
            else
                throw new WTF('Model not found', 500);
            
            return true;
        }

        public function Update ($Data)
        {
            if (!Client::$Authorized)
                return false;

            if (!$this->Loaded)
                $this->Load();
            
            if (true !== ($Errors = $this->Check($Data)))
                return $Errors;

            $this->_LoadModel($this->Scope);

            foreach($this->_Model->Nodes as $Name => $Node)
            {
                if (isset($Data[$Name]))
                    {
                        if (isset($Data[$Name]) && !empty($Data[$Name]) and $Data[$Name] != $this->Data[$Name][0])
                            {
                                if (!is_array($Data[$Name]))
                                    $Data[$Name] = array($Data[$Name]);

                                    foreach($Data[$Name] as $K => $V)
                                        $Data[$Name][$K] = Code::E('Data/Types', 'Input',
                                            array('Key'=>$Name,'Value' => $V,'Name' => $this->Name, 'Scope'=> $this->Scope), $Node->Type);
                                    
                                if ($this->Set($Name, $Data[$Name]) and isset($Node->onChange))
                                {
                                    if ($Node->I18N)
                                        Event::Queue (array('Priority'=>96, 'Signal'=>'NodeUpdated', 'Subject'=> $Name, 'Argument'=>'<l>'.$Name.':'.$Data[$Name][0].'</l>'));
                                    else
                                        Event::Queue (array('Priority'=>96, 'Signal'=>'NodeUpdated', 'Subject'=> $Name, 'Argument'=>$Data[$Name][0]));
                                }
                                
                                if (isset($Node->Verificable))
                                    $this->Del('Verified:'.$Name);
                            }
                    }
            }
            $this->Set('ModifiedOn', time());
            $this->Save();
            return true;
        }
}