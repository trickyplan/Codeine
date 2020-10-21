<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 2020.x.x
     */

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    setFn('Open', function ($Call)
    {
        return new PHPMailer(true);
    });

    setFn('Write', function ($Call)
    {
        $Result = false;

        if (isset($Call['Link']))
            ;
        else
            $Call = F::Apply(null, 'Open', $Call);

        try
        {
            //Server settings
            $Call['Link']->isSMTP();                                            // Send using SMTP
            $Call['Link']->SMTPDebug = self::$_Verbose['Administrator'] - 4;
            $Call['Link']->Debugoutput = function ($Message, $Level) {
                F::Log($Message, $Level+4, ['Administrator', 'Mail', 'PHPMailer']);
            };
            $Call['Link']->Host       = F::Dot($Call, 'Mail.Host');       // Set the SMTP server to send through
            $Call['Link']->Port       = F::Dot($Call, 'Mail.Port');         // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

            F::Log('Host:Port is *'.F::Dot($Call, 'Mail.Host').':'.F::Dot($Call, 'Mail.Port').'*', LOG_INFO);
            if (F::Dot($Call, 'Mail.SMTPAuth.Enabled'))
            {
                $Call['Link']->SMTPAuth   = true;     // Enable SMTP authentication
                $Call['Link']->Username   = F::Dot($Call, 'Mail.Username');     // SMTP username
                $Call['Link']->Password   = F::Dot($Call, 'Mail.Password');     // SMTP password
            }

            if (F::Dot($Call, 'Mail.Secure.Enabled'))
                $Call['Link']->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            else
            {
                $Call['Link']->SMTPSecure = false;
                $Call['Link']->SMTPAutoTLS = false;
            }

            //Recipients
            $Call['Link']->setFrom(F::Dot($Call, 'Mail.From').'@'.$Call['HTTP']['Host'], $Call['Project']['Title'] ?? 'Codeine');
            $Call['Link']->addAddress(F::Dot($Call, 'Scope'));            // Name is optional

            foreach ($Call['Mail']['Headers'] as $Key => $Value)
            {
                $Call['Link']->addCustomHeader($Key, $Value);
            }

            // $Call['Link']->addReplyTo('info@example.com', 'Information');
            // $Call['Link']->addCC('cc@example.com');
            // $Call['Link']->addBCC('bcc@example.com');

            // Attachments
            // $Call['Link']->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            // $Call['Link']->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

            // Content
            $Call['Link']->isHTML(true);                                  // Set email format to HTML
            $Call['Link']->Subject = F::Dot($Call, 'Where.ID');

            if (F::Dot($Call, 'Mail.HTML.Enabled'))
                $Call['Link']->Body    = $Call['Data'];
            else
                $Call['Link']->AltBody = strip_tags($Call['Data']);

            $Result = $Call['Link']->send();
        }
        catch (Exception $e)
        {
            F::Log('Message could not be sent. PHP Mailer Error: '.$Call['Link']->ErrorInfo, LOG_ERR, ['Administrator', 'Mail']);
        }

        return $Result;
    });
