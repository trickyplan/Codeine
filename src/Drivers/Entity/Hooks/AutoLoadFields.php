<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 8.x
     */

    setFn('beforeEntityRead', function ($Call) {
        if (isset($Call['Fields'])) {
        } else {
            foreach ($Call['Nodes'] as $Name => $Node) {
                $Add = true;

                if (isset($Node['Auto']) && $Node['Auto'] == false) {
                    $Add = false;
                }

                if (isset($Call['Load']['Fields']) && in_array($Name, $Call['Load']['Fields'])) {
                    $Add = true;
                }

                if ($Add) {
                    $Call['Fields'][$Name] = $Name;
                }
            }
        }


        return $Call;
    });
