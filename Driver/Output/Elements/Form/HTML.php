<?php

function F_HTML_Render($Args)
{   
    $ID = uniqid('T');

    Page::JSFile('JS/TinyMCE/jscripts/tinymce/jquery.tinymce.js');
    
    Page::JS(
        "
            $('#".$ID."').tinymce(
                {
                    script_url : '/JS/TinyMCE/jscripts/tinymce/tiny_mce.js',
                    theme: 'advanced',
                    cleanup: false,
                    verify_html : false,
                    convert_urls : false,
                    extended_valid_elements : 'object[width|height|param|embed],param[name|value],embed[src|type|width|height]',
                    width: '100%',
                    height: 400,
                    ".'
plugins : "safari,spellchecker,table,save,media,contextmenu,paste",
theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,formatselect,fontsizeselect",
theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,link,unlink,anchor,image,code",
theme_advanced_buttons3 : "tablecontrols,|,media,spellchecker,|,blockquote,insertimage",
theme_advanced_toolbar_location : "top",
theme_advanced_toolbar_align : "left",
theme_advanced_statusbar_location : "bottom",
theme_advanced_resizing : true
'."});
");
    return '<br/>
        <span class="Button" onclick="$(\'#'.$ID.'\').tinymce().remove()">Отключить WYSIWYG</span> <br/>
<textarea cols=40 rows=20 id="'.$ID.'" name="'.$Args['name'].'">'.$Args['value'].'</textarea>';
}