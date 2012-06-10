<?php

    class EntityTest extends PHPUnit_Framework_TestCase
    {
        protected $_Data = array(

                                    'String' => 'Test',
                                    'Integer' => 8,
                                    'Text' => 'Test text',
                                    'Boolean' => true
                                );

        public function __construct()
        {
            $this->_ID = rand();
        }

        public function testCRUD ()
        {
            $this->assertEquals($this->_Data, F::Run('Entity', 'Create', array(
                                            'Entity' => 'Test',
                                            'ID' => $this->_ID,
                                            'Data' => $this->_Data
                                       )));

            $this->assertEquals($this->_Data, F::Run('Entity', 'Read', array(
                                            'Entity' => 'Test',
                                            'Where' => $this->_ID
                                       )));
        }
    }
