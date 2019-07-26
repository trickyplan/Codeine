<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn ('Do', function ($Call)
    {
        F::Log('Mail Interface Started', LOG_NOTICE);

        $Call = F::Hook('beforeInterfaceRun', $Call);

        $f = fopen('php://stdin', 'r');
        $Mail = stream_get_contents($f);

        /*
         *  From root@rig.bergstein.ru  Thu Jul 25 20:28:02 2019
            Return-Path: <root@rig.bergstein.ru>
            X-Original-To: cptmarten@localhost
            Delivered-To: cptmarten@localhost
            Received: by prime.network.trickyplan.com (Postfix, from userid 0)
                    id C48963E0B13; Thu, 25 Jul 2019 20:28:02 +0300 (MSK)
            Subject: Terminal Email Send
            Message-Id: <20190725172802.C48963E0B13@prime.network.trickyplan.com>
            Date: Thu, 25 Jul 2019 20:28:02 +0300 (MSK)
            From: root@rig.bergstein.ru (root)

            Email Content line 1
            Email Content line 2

         */
        F::Log($Mail,LOG_WARNING);

        F::Log('Mail Interface Finished', LOG_NOTICE);

        return $Call;
    });