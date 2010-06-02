<?php

include Engine.Classes.'ReCAPTCHA.php';

function F_ReCAPTCHA_Generate($Args)
{
    return recaptcha_get_html('6LcCHwoAAAAAANpeHBFbmDnDHW0YwtTmLO3KAh9n');
}


function F_ReCAPTCHA_Check($Args)
{
    $resp = recaptcha_check_answer ('6LcCHwoAAAAAAJCfId6d5Fb2ZaQdgHdM2OEmN7o_',
                                    _IP,
                                    $_POST["recaptcha_challenge_field"],
                                    $_POST["recaptcha_response_field"]);

    return $resp->is_valid;
}
