<?php

include_once Engine.'Package/ReCAPTCHA/ReCAPTCHA.php';

function F_ReCAPTCHA_Generate($Args)
{
    return recaptcha_get_html(Core::$Conf['Keys']['ReCAPTCHA']['Public']);
}


function F_ReCAPTCHA_Check($Args)
{
    $resp = recaptcha_check_answer (Core::$Conf['Keys']['ReCAPTCHA']['Private'],
                                    _IP,
                                    Server::Arg('recaptcha_challenge_field'),
                                    Server::Arg('recaptcha_response_field'));

    return $resp->is_valid;
}
