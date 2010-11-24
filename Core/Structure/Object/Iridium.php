<?php

    /**
     * @author BreathLess
     * @date 15.11.10
     * @time 23:50
     */

    class Structure_Object implements ArrayAccess, Iterator, SplObserver
    {
        private $_Type;
        private $_Name;
        private $_Data;

        public function Where($Key, $Is, $Value = null)
        {
            return $this->_Name = Code::Run(array(
                               'F'     => 'Data/Map/Where',
                               'D'    => Core::$Conf['Data']['Points'][$this->_Type]['Map'],
                               'Key'   => $Key,
                               'Is'    => $Is,
                               'Value' => $Value,
                               'Mode'  => 'Once'
                            ));
        }


        public function Load($Name = null)
        {
            if ($Name !== null)
                $this->_Name = $Name;

            $this->_Data = Code::Run(array(
                               'F'    => 'Data/Map/Load',
                               'D'    => Core::$Conf['Data']['Points'][$this->_Type]['Map'],
                               'Name' => $this->_Name
                            ));

            // TODO Inheritance
            // TODO Entaglement
        }

        public function Add($Key, $Value)
        {

        }

        public function Del()
        {

        }

        public function Set()
        {

        }

        public function Get()
        {

        }

        /**
         * (PHP 5 >= 5.1.0)<br/>
         * Receive update from subject
         * @link http://php.net/manual/en/splobserver.update.php
         * @param SplSubject $subject <p>
         * The SplSubject notifying the observer of an update.
         * </p>
         * @return void
         */
        public function update($subject)
        {
            // TODO: Implement update() method.
        }

        /**
         * (PHP 5 >= 5.1.0)<br/>
         * Rewind the Iterator to the first element
         * @link http://php.net/manual/en/iterator.rewind.php
         * @return void Any returned value is ignored.
         */
        public function rewind()
        {
            // TODO: Implement rewind() method.
        }

        /**
         * (PHP 5 >= 5.1.0)<br/>
         * Checks if current position is valid
         * @link http://php.net/manual/en/iterator.valid.php
         * @return boolean The return value will be casted to boolean and then evaluated.
         * Returns true on success or false on failure.
         */
        public function valid()
        {
            // TODO: Implement valid() method.
        }

        /**
         * (PHP 5 >= 5.1.0)<br/>
         * Return the key of the current element
         * @link http://php.net/manual/en/iterator.key.php
         * @return scalar scalar on success, integer
         * 0 on failure.
         */
        public function key()
        {
            // TODO: Implement key() method.
        }

        /**
         * (PHP 5 >= 5.1.0)<br/>
         * Move forward to next element
         * @link http://php.net/manual/en/iterator.next.php
         * @return void Any returned value is ignored.
         */
        public function next()
        {
            // TODO: Implement next() method.
        }

        /**
         * (PHP 5 >= 5.1.0)<br/>
         * Return the current element
         * @link http://php.net/manual/en/iterator.current.php
         * @return mixed Can return any type.
         */
        public function current()
        {
            // TODO: Implement current() method.
        }

        /**
         * (PHP 5 >= 5.1.0)<br/>
         * Offset to unset
         * @link http://php.net/manual/en/arrayaccess.offsetunset.php
         * @param mixed $offset <p>
         * The offset to unset.
         * </p>
         * @return void
         */
        public function offsetUnset($offset)
        {
            // TODO: Implement offsetUnset() method.
        }

        /**
         * (PHP 5 >= 5.1.0)<br/>
         * Offset to set
         * @link http://php.net/manual/en/arrayaccess.offsetset.php
         * @param mixed $offset <p>
         * The offset to assign the value to.
         * </p>
         * @param mixed $value <p>
         * The value to set.
         * </p>
         * @return void
         */
        public function offsetSet($offset, $value)
        {
            // TODO: Implement offsetSet() method.
        }

        /**
         * (PHP 5 >= 5.1.0)<br/>
         * Offset to retrieve
         * @link http://php.net/manual/en/arrayaccess.offsetget.php
         * @param mixed $offset <p>
         * The offset to retrieve.
         * </p>
         * @return mixed Can return all value types.
         */
        public function offsetGet($offset)
        {
            // TODO: Implement offsetGet() method.
        }

        /**
         * (PHP 5 >= 5.1.0)<br/>
         * Whether a offset exists
         * @link http://php.net/manual/en/arrayaccess.offsetexists.php
         * @param mixed $offset <p>
         * An offset to check for.
         * </p>
         * @return boolean Returns true on success or false on failure.
         * </p>
         * <p>
         * The return value will be casted to boolean if non-boolean was returned.
         */
        public function offsetExists($offset)
        {
            // TODO: Implement offsetExists() method.
        }
                
    }