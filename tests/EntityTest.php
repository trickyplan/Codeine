<?php

    class EntityTest extends PHPUnit_Framework_TestCase
    {
        public function testCreate()
        {
            F::Run('Entity', 'Create', array(
                                            'Entity' => 'Test',
                                            'Data' =>
                                                array(
                                                    'Key' => 'Value'
                                                )
                                       ));
        }
    }
