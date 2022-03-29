<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 2019.x
     */

    use BaconQrCode\Renderer\ImageRenderer;
    use BaconQrCode\Renderer\Image\SvgImageBackEnd;
    use BaconQrCode\Renderer\RendererStyle\RendererStyle;
    use BaconQrCode\Writer;

    setFn('Encode', function ($Call) {
        $Renderer = new ImageRenderer
        (
            new RendererStyle($Call['QR']['Size']),
            new SvgImageBackEnd()
        );
        $Writer = new Writer($Renderer);

        $Call['Output']['Content'] = $Writer->writeString($Call['QR']['Data']);
        $Call['HTTP']['Headers']['Content-Type:'] = 'image/svg+xml';

        return $Call;
    });