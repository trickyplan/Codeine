<?php

class Collection
{
    public $_Items = array();
    public $Names = array();
    public $Length = 0;
    public $Loaded = false;
    public $Queried = false;
    public $LastQuery = '';
    private $__Keys;
    private $__Current;

    public function __construct ($Point, $Selector = null, $AutoLoad = false)
        {
            $this->Point = $Point;
            $this->Scope = $Point;

            $this->ORM = Data::GetORM($this->Point);

            if (null !== $Selector)
                $this->Query ($Selector);

            if ($AutoLoad)
                $this->Load();

            return $this;
        }

    public function Names ($IDs = null)
    {
        if (null !== $IDs)
            $this->Names = $IDs;

        return $this->Names;
    }

    public function Query ($Selector, $Mode = 'New')
    {
             $IDs = array();
             
             if (!empty ($Selector))
                    {
                        if ($Selector == '@My')
                            $Selector = '=Owner='.Client::$UID;
                        
                        if (mb_strpos($Selector, ';') !== false)
                            {
                                $Selectors = explode(';',$Selector);
                                $this->Query($Selectors[0], 'New');
                                unset($Selectors[0]);
                                foreach($Selectors as $Selector)
                                    $this->Query($Selector, 'Intersect');
                            }
                        elseif (mb_strpos($Selector, '&') !== false)
                            {
                                $Selectors = explode('&',$Selector);
                                    foreach($Selectors as $Selector)
                                        $this->Query($Selector, 'Merge');
                            }
                        else
                        {
                            $QUID = $this->Scope.':'.$Selector;

                            if (null === ($IDs = Data::PoolGet($QUID)))
                                {
                                    $IDs = Code::E('Data/Mappers', 'Query',
                                    array('Scope'=> $this->Scope,
                                          'Point'=>$this->Point,
                                          'Selector'=>$Selector), $this->ORM);
                                    
                                    Data::PoolPut($QUID, $IDs);
                                }                          


                            if (null === $IDs)
                                $IDs = array();
                            
                            switch ($Mode)
                                {
                                    case 'New':         $this->Names($IDs);  break;
                                    case 'Intersect':   $this->Names(array_intersect ($IDs, $this->Names));break;
                                    case 'Merge':       $this->Names(array_merge ($IDs, $this->Names)); break;
                                    case 'Substract':   $this->Names(array_diff($this->Names, $IDs));break;
                                }
                            $this->LastQuery = $Selector;
                            $this->Length = sizeof($this->Names);
                        }

                        $this->Queried = true;
                    }
                else
                    return Log::Error ('Object: Пустой селектор');

            return $this;
        }

    public function Intersect ($Selector)
    {
        return $this->Query($Selector, 'Intersect');
    }

    public function Merge ($Selector)
    {
        return $this->Query($Selector, 'Merge');
    }

    public function Substract ($Selector)
    {
        return $this->Query($Selector, 'Substract');
    }


    public function Load ($Names = null)
    {
        if (null !== $Names)
            $this->Names($Names);

        $this->_Items = array();

        if (!is_array($this->Names))
            $this->Names = array();
        
        if ($this->LastQuery == '@All')
            $Names = array('@All');
        else
            $Names = array_merge($this->Names, array('Default'));

        $Data = Code::E('Data/Mappers', 'Load', array('Scope'=> $this->Scope,'Point'=>$this->Point, 'Name'=> $Names), $this->ORM);

        if (isset($Data['Default']))
            $DefaultData = $Data['Default'];
        else
            $DefaultData = array();
        
        if (is_array($Data))
            {
                $IC = 0;
                foreach($this->Names as $ID)
                {
                    if (null === $DefaultData)
                        $DataF = $Data[$ID];
                    else
                    {
                        $DataF = $DefaultData;
                        if (isset($Data[$ID]) and is_array($Data[$ID]))
                            foreach($Data[$ID] as $Key => $Value)
                                $DataF[$Key] = $Value;
                    }

                    if (isset($Data[$ID]) and is_array($Data[$ID]))
                    {
                        $this->_Items[$IC] = new Object($this->Scope);
                        $this->_Items[$IC]->Name($ID);
                        $this->_Items[$IC]->Data ($DataF);
                        $this->_Items[$IC]->Loaded = true;
                        $IC++;
                    }                    
                }
                $this->Loaded = true;
                $this->__Keys = $this->Names;
                $this->Length = $IC;

                return $this;
            }
        else
            return null;
    }

    public function ValuesOf($Key)
    {
        $Values = array();
        foreach($this->_Items as $Name => $Item)
            $Values[$Name] = $Item->Get($Key);

        return $Values;
    }

    public function VariantsOf($Key)
    {
        $Output = array();
        if (!empty($this->_Items))
            foreach($this->_Items as $Item)
            {
                $Variants = $Item->Get($Key, false);
                    if (is_array($Variants))
                        foreach($Variants as $Variant)
                            $Output[$Variant] = $Variant;
            }
        else
            $Output = Code::E('Data/Mappers', 'Variants', array('Scope'=> $this->Scope,'Point'=>$this->Point, 'Key'=> $Key), $this->ORM);
        
        return $Output;
    }

    public function Sort($Key, $Direction, $Dry = false)
    {
        return $this->Names(Code::E('Data/Mappers', 'Sort',
                array('Scope'=> $this->Scope,
                      'Point'=>$this->Point,
                      'Name'=> $this->Names,
                      'Key'=>$Key,
                      'Direction'=>$Direction),
                $this->ORM));
    }

    public function Slice($From, $Length = 0)
    {
        if (is_array($this->_Items))
            $this->_Items = array_slice($this->_Items, $From, $Length);
        if (is_array($this->Names))
            $this->Names = array_slice($this->Names, $From, $Length);

        return true;
    }

  public function ID($ID)
  {
      foreach($this->_Items as $Item)
        if ($Item->Name == $ID)
            return $Item;
      
      return null;
  }

  public function Page($PageNumber, $PageSize = null)
  {
      if (is_array($this->Names))
      {
          $PageSize = Application::GetTune('PageSize',10);
          $PageCount = ceil($this->Length / $PageSize);
          if ($PageNumber>0 and $PageNumber<=$PageCount)
              $this->Names = array_slice($this->Names, ($PageNumber-1)*$PageSize, $PageSize);
          else
              return Log::Warning('Page Number out of bounds');
      }
      else
          return null;
  }

  public function offsetSet($Key, $Value) {
        $this->_Items[$Key] = $Value;
      }
  public function offsetUnset($key) {
        unset($this->_Items[$Key]);
      }
  public function offsetGet($key) {
        return $this->_Items[$Key];
        }
  public function offsetExists($key) {
        return isset($this->_Items[$Key]);
      }
  
  public function count() {
    return $this->Length;
  }
  
  public function rewind() {
    $this->__Current = 0;
  }

  public function valid() {
    return $this->__Current < sizeof($this->_Items);
  }

  public function key() {
    return $this->_Items[$this->__Current]->Name;
  }

  public function next() {
    $this->__Current++;
  }

  public function current() {
    return $this->_Items[$this->__Current];
  }

}
