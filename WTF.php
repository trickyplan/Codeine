<?php

    /**
     * @author BreathLess
     * @date 11.11.10
     * @time 0:37
     */
    
    class WTF extends Exception
    {
        public function Log()
        {
            //Log::Error($this->getMessage());
        }

        public function Panic()
        {
            die ('Kernel panic:  '.$this->getMessage());
        }
    }