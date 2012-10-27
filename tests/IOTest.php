<?php

    class IOTest extends PHPUnit_Framework_TestCase
    {
        protected $IDs;

        public function setUp()
        {
            for ($a = 1; $a < 10000; $a++)
                $this->IDs[uniqid()] = rand();
        }

        public function testWrite()
        {
            foreach ($this->IDs as $ID => $Data)
                F::Run('IO', 'Write',
                [
                    'Storage' => 'Redis',
                    'Where' => $ID,
                    'Data' => $Data
                ]);
        }

        public function testRead()
        {
            foreach ($this->IDs as $ID)
                F::Run('IO', 'Read',
                [
                    'Storage' => 'Redis',
                    'Where' => $ID
                ]);

        }

        public function testRandomRead()
        {
            for ($a = 1; $a < 10000; $a++)
                F::Run('IO', 'Read',
                [
                    'Storage' => 'Redis',
                    'Where' => $this->IDs[array_rand($this->IDs)]
                ]);

        }
    }