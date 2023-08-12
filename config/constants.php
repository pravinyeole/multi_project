<?php

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

return [
    'SUPER_ADMIN_DOC_LINK'  => '',
    'DOC_LINK'              => '',
    'APP_ENV'               => '',
    'WEB_URL'               => '',
    'ADMIN_EMAIL'           => '',
    'APP_VERSION'           => '',
    'WEB_VERSION'           => '',
    'PORTAL_VERSION'        => '',
    'DashboardMsg'          => "How's business for Flat Our of Heels?",

    // Common
    'CURRENTEPOCH'          => round(microtime(true)*1000),
    'S3_URL'                => '',
    'IS_EMAIL_ACTIVE'       => '',
    'FILE_EXTENSION'        => '',
    'FILE_SIZE_MB'          => '',

    // Credits
    'MINIMUM_CREDIT'        => 0,
    'MAXIMUM_CREDIT'        => 250,

    'INSURANCE_AGENCY_ID'   =>  '',
    'USER_TYPE'             =>  '',
];
