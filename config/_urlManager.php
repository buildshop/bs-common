<?php

return array(
    'urlFormat' => 'path',
    'class' => 'app.managers.CManagerUrl',
    'showScriptName' => false,
    'useStrictParsing' => true,
    'rules' => array(
        '/' => 'main/index/index',
        'ajax/<action:[.\w]+>' => 'main/ajax/<action>', // dotted for actions widget.<name>
        'ajax/<action:[.\w]>/*' => 'main/ajax/<action>',
        'admin/auth' => 'admin/auth',
        'admin/auth/logout' => 'admin/auth/logout',
        'admin/dekstop/<action:\w+>' => 'admin/dekstop/<action>',
        'admin/dekstop/<action:\w+>/*' => 'admin/dekstop/<action>',
        'admin/<module:\w+>' => '<module>/admin/default',
        'admin/<module:\w+>/<controller:\w+>' => '<module>/admin/<controller>',
        'admin/<module:\w+>/<controller:\w+>/<action:\w+>' => '<module>/admin/<controller>/<action>',
        'admin/<module:\w+>/<controller:\w+>/<action:\w+>/*' => '<module>/admin/<controller>/<action>',
        '<module:\w+>/<controller:\w+>' => '<module>/<controller>',
        '<module:\w+>/<controller:\w+>/<action:\w+>' => '<module>/<controller>/<action>',
        'admin' => 'admin/default/index',
        'admin/gii' => 'gii',
        'admin/gii/<controller:\w+>' => 'gii/<controller>',
        'admin/gii/<controller:\w+>/<action:\w+>' => 'gii/<controller>/<action>',
    ),
);