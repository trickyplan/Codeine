<?php

    class FCode implements ArrayAccess
    {
        private $Name;

        public function __construct($Name = null)
        {
            return $this->Name = $Name;
        }

        public function __call($Name, $Args)
        {
            echo $this->Name.' '.$Name.' with ';
            print_r($Args);
            $this->Name = '';
        }

        public function __get($Name)
        {
            $this->Name .= '/'.$Name;
            return $this;
        }

        public function offsetGet($Name)
        {
            echo '<'.$Name.'>';
            return $this;
        }

        public function offsetSet($Key, $Value) {}
        public function offsetExists($Key) {}
        public function offsetUnset($Key) {}
    }

    $FCode = new FCode('');
    $FCode->process->hashing['sha1']->get(5);
    $FCode->data->mounter->read(5);