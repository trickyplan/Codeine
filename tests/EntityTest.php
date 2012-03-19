<?php

    class EntityTest extends PHPUnit_Framework_TestCase
    {
        public function testUpdate()
        {
            F::Run('Entity', 'Update', array(
                                            'Entity' => 'Test',
                                            'Data' =>
                                                array(
                                                    'Key' => 'Value'
                                                ),
                                            'Where' => 'ID'
                                       ));
        }
    }
