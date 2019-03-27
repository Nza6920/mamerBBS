<?php

return [
    'encoding'      => 'UTF-8',
    'finalize'      => true,
    'cachePath'     => storage_path('app/purifier'),
    'cacheFileMode' => 0755,
    'settings'      => [
        'user_topic_body' => [
            'HTML.Doctype'             => 'XHTML 1.0 Transitional',
            'HTML.Allowed'             => 'div[style],b,strong,i,em,a[href|title|target],ul,ol,ol[start],li,p[style],br,span[style],img[width|height|alt|src],pre[style|class],hr,code,h2,h3,h4,h5,h6,blockquote,del,table,thead,tbody,tr,th,td,u,strike,*[style|class]',
            'CSS.AllowedProperties'    => 'font,font-size,font-weight,font-style,margin,width,height,font-family,text-decoration,padding-left,color,background-color,text-align',
            'Attr.AllowedFrameTargets' => ['_blank','_self'],
            'AutoFormat.AutoParagraph' => true,
            'AutoFormat.RemoveEmpty'   => true,
        ],
    ],
];
