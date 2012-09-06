<?php

    class CodeineTest extends PHPUnit_Framework_TestCase
    {
        protected $_Path;

        public function setUp ()
        {
            $this->_Path = Codeine . '/Options/';
        }

        public function testAll()
        {
            $Directory = new RecursiveDirectoryIterator($this->_Path);
            $Iterator  = new RecursiveIteratorIterator($Directory);
            $Regex     = new RegexIterator($Iterator, '/^.+\.test.json$/i', RecursiveRegexIterator::GET_MATCH);

            $szPath = strlen($this->_Path);
            $List = array();

            foreach ($Regex as $File)
                $List[] = mb_substr ($File[0], $szPath, strlen ($File[0]) - $szPath - 5);

            foreach ($List as $Service)
                $this->ServiceTest($Service);

            return $List;
        }

        protected function ServiceTest ($Service)
        {
            $Tests = F::findFiles('Options/'.$Service.'.json');

            if (is_array($Tests))
                foreach ($Tests as $Test)
                {
                    $Test = json_decode(file_get_contents($Test),true);
                    foreach ($Test['Suites'] as $Suite)
                        foreach ($Suite as $Case)
                            $this->assertEquals(F::Run($Test['Service'], $Case['Method'], $Case['Call']), $Case['Result']);
                }

            return true;
        }
    }
