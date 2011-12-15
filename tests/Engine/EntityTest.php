<?php

    class PositiveEntityTest extends PHPUnit_Framework_TestCase
    {
        public function testCreate()
        {
            F::Run('Engine.Entity', 'Create',
                array(
                     'Entity' => 'Test',
                     'Data' =>
                         array(
                             'ID'  => 'Test',
                             'Key' => 'Value'
                         )
                ));
        }

        public function testReadByID ()
        {
            F::Run ('Engine.Entity', 'Read',
                    array(
                         'Entity' => 'Test',
                         'Where' =>
                            array('ID' => 'Test')
                    ));
        }

        public function testReadByKeyEqual ()
        {
            F::Run ('Engine.Entity', 'Read',
                    array(
                         'Entity'  => 'Test',
                         'Where' =>
                            array('ID' => 'Test')
                    ));
        }

        public function testUpdate ()
        {
            F::Run ('Engine.Entity', 'Update',
                    array(
                         'Entity' => 'Test',
                         'Where' => array('ID' => 'Test'),
                         'Data' =>
                             array(
                                 'Key' => 'Value2'
                             )
                    ));
        }

        public function testDelete ()
        {
            F::Run ('Engine.Entity', 'Delete',
                    array(
                         'Entity' => 'Test',
                         'Where' => array('ID'   => 'Test'),
                         'Data' =>
                         array(
                             'Key' => 'Value'
                         )
                    ));
        }
    }
