<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Generate.Manifest', function ($Call)
    {
        $Call = F::Dot($Call, 'View.Renderer', ['Service' => 'View.JSON', 'Method' => 'Render']);
        // $Call = F::CopyDot($Call, 'Version.Project.Minor','Integration.Google.Chrome.Extension.Manifest.Template.version');
        $Call = F::CopyDot($Call, 'Project.Title','Integration.Google.Chrome.Extension.Manifest.Template.name');
        $Call = F::CopyDot($Call, 'Project.Title','Integration.Google.Chrome.Extension.Manifest.Template.browser_action.default_title');
        $Call = F::CopyDot($Call, 'Project.Description.Short','Integration.Google.Chrome.Extension.Manifest.Template.description');
        
        $Call = F::CopyDot($Call, 'Integration.Google.Chrome.Extension.Manifest.Template', 'Output.Content');
        return $Call;
    });
    
    setFn('Generate.Icon', function ($Call)
    {
        $Call = F::Dot($Call, 'View.Renderer', ['Service' => 'View.File', 'Method' => 'Render']);
        $Call = F::CopyDot($Call, 'Integration.Google.Chrome.Extension.Icon', 'Output.Content');
        return $Call;
    });
    
    setFn('Generate.PopupHTML', function ($Call)
    {
        $Call = F::Dot($Call, 'View.Renderer', ['Service' => 'View.Plaintext', 'Method' => 'Render']);
        $Call['Output']['Content'][] = F::Run('View', 'Load', $Call,
            [
                'Scope' => 'Code.Integration.Google.Chrome.Extension',
                'ID'    => 'Popup'
            ]);
        
        return $Call;
    });
    
    setFn('Generate.PopupJS', function ($Call)
    {
        $Call = F::Dot($Call, 'View.Renderer', ['Service' => 'View.RAW', 'Method' => 'Render']);
        $Call['Output']['Content'] = F::Run('IO', 'Read',
                            [
                                'Storage' => 'JS',
                                'Scope'   => 'Code/Integration/Google/Chrome/Extension',
                                'Where'   => 'Popup',
                                'IO One'  => true
                            ]);
        
        return $Call;
    });