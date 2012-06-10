<?php

    class RedisTest extends PHPUnit_Framework_TestCase
    {
        protected $_Data;

        public function __construct()
        {
            $this->_Data = array('Num' => 3.14, 'String' => 'String', 'Array' => array(0, '1', true), 'Bool' => false);
        }

        public function testCreateAndRead()
        {
            $this->assertEquals($this->_Data, F::Run('IO', 'Write', array(
                'Storage' => 'Redis',
                'ID' => 'Test',
                'Data' => $this->_Data
            )));

            $this->assertEquals(array($this->_Data), F::Run('IO', 'Read', array(
                'Storage' => 'Redis',
                'Where' => 'Test'
            )));
        }

        public function testUpdate()
        {

        }

        public function testDelete()
        {

        }
    }
