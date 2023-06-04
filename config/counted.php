<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Ad Blocker Prevention - Script Name
    |--------------------------------------------------------------------------
    |
    | This option allows you to specify the custom name for your script file
    | that will be used in the GET request. By default, the name is set
    | to 'script'. Changing this value can help prevent ad blockers from
    | detecting and blocking the script.
    |
    | Example: If you set the value to 'myscustomscriptname', the GET request
    | URL would be https://website.com/myscustomscriptname.js.
    |
    | Note: It is important to keep in mind that determined ad blockers might
    | still be able to detect the script even with a custom name. This option
    | should be used as a preventive measure but cannot guarantee 100% success.
    |
    */

    'script_name' => env('COUNTED_SCRIPT_NAME', 'script'),

];
