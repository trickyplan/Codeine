<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description: Simple HTML Renderer
     * @package Codeine
     * @version 8.x
     */

    setFn('Render', function ($Call)
    {
        $Call = F::Hook('beforeRSSPipeline', $Call);

        $XML = new XMLWriter();

        $XML->openMemory();
        $XML->startDocument('1.0', 'UTF-8');
        $XML->setIndent(true);

        $XML->startElement('rss');

        $XML->startAttribute('version');
            $XML->text('2.0');
        $XML->endAttribute();

        $XML->startElement('channel');

        $XML->writeElement('title', $Call['Project']['Title']); // FIXME
        $XML->writeElement('link', $Call['HTTP']['Proto'].$Call['HTTP']['Host'].$Call['Link']);
        if (isset($Call['Page']['Description']))
            $XML->writeElement('description', $Call['Page']['Description']);
        else
            $XML->writeElement('description', '');
        $XML->writeElement('generator', 'Codeine '.$Call['Version']['Codeine']['Major']);
        $XML->writeElement('pubDate', date(DATE_RSS, time()));
        $XML->writeElement('lastBuildDate', date(DATE_RSS, time()));

        if (!isset($Call['Slug']))
            $Call['Slug'] = strtolower($Call['Entity']);

        foreach ($Call['Output']['Content'] as $Element)
            if ($Element['Type'] == 'Template' && isset($Element['Data']))
            {
                $XML->startElement('item');

                    $XML->startElement('title');
                        $XML->text($Element['Data']['Title']);
                    $XML->endElement(); // title

                    $XML->startElement('pubDate');
                        $XML->text(date(DATE_RSS, $Element['Data']['Created']));
                    $XML->endElement(); // description

                    if (isset($Element['Data']['Description']))
                    {
                        $XML->startElement('description');
                            $XML->writeCdata($Element['Data']['Description']);
                        $XML->endElement(); // description
                    }

                    $XML->startElement('link');
                    if (isset($Element['Data']['Slug']))
                        $XML->text($Call['HTTP']['Proto'].$Call['HTTP']['Host']
                            .'/'.$Call['Slug']['Entity'].'/'.$Element['Data']['Slug'].'?Channel=RSS'); // FIXME It's shit!
                    else
                        $XML->text($Call['HTTP']['Proto'].$Call['HTTP']['Host']
                            .'/'.$Call['Slug']['Entity'].'/'.$Element['Data']['ID'].'?Channel=RSS'); // FIXME It's double shit!

                    $XML->endElement(); // title

                $XML->endElement(); // item
            }

        $XML->endElement(); // Channel
        $XML->endElement(); // rss
        $XML->endDocument();

        $Call['Output'] = $XML->outputMemory(true);

        $Call = F::Hook('afterRSSPipeline', $Call);

        return $Call;
    });
