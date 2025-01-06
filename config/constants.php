<?php
$SITE_URL = env('APP_URL');
return [
    'SITE_NAME'=> '4UV',
    'SITE_URL'=>  $SITE_URL,
    'ASSET_URL'=> $SITE_URL.'/',
    'ADMIN_PREFIX'=> 'backend',
    'ADMIN_URL' => $SITE_URL.'/backend'.'/'
    
];

define('ADMIN_URL', $SITE_URL.'/backend'.'/');
