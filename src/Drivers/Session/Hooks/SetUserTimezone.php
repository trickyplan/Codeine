<?php

    setFn('LoadUser.After', function ($Call) {
        $UserTZ = F::Dot($Call, 'Session.User.Timezone.Identifier');
        if (empty($UserTZ)) {
        } else {
            date_default_timezone_set($UserTZ);
        }

        return $Call;
    });
