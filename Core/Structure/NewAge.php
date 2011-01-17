<?php

    /**
     * @author BreathLess
     * @date 08.01.11
     * @time 1:39
     */

    final class Structure // implements ArrayAccess, Countable, Iterator
    {
        protected $_Scheme;
        protected $_Data;

        public static function Make($Scheme)
        {
            return new Structure($Scheme);
        }

        protected function __construct($Scheme)
        {
            $this->_Scheme = $Scheme;
        }

        public function __get($Key)
        {
            $Meta = array('Restricted');
            
            if (isset($this->_Scheme['Nodes'][$Key]))
            {
                $Data = $this->_Data[$Key];

                foreach($Meta as $cMeta)
                {
                    $Data = Code::Run(
                        array(
                            'N'      => 'Structure.Meta.'.$cMeta,
                            'F'      => 'Get',
                            'Scheme' => $this->_Scheme,
                            'Data'   => $this->_Data,
                            'Key'    => $Key,
                            'Value'  => $Data
                        )
                    );
                }

                return $Data;
            }
        }

        public function __set($Key, $Value)
        {
            if (isset($this->_Scheme['Nodes'][$Key]))
            {
                $this->_Data[$Key] = $Value;
            }
        }
    }
