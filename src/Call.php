<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.4.5
     */

    class Call implements ArrayAccess
    {
        protected $Data;

        public function __construct($Data)
        {
            $this->Data = $Data;
            return $this;
        }

        public function offsetExists ($offset)
        {
            return isset($this->Data[$offset]);
        }

        public function offsetGet ($offset)
        {
            if (is_array($this->Data[$offset]))
                $this->Data[$offset] = new Call($this->Data[$offset]);

            return $this->Data[$offset];
        }


        public function offsetSet ($offset, $value)
        {
            $this->Data[$offset] = $value;
        }


        public function offsetUnset ($offset)
        {
            unset($this->Data[$offset]);
        }

    }