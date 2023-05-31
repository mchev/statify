<?php

namespace App\Enums;

enum WebsiteDefaultViewEnum:string
{
    case Today = 'Today';
    case Last24Hours = 'Last 24 Hours';
    case Yesterday = 'Yesterday';
    case Last7Days = 'Last 7 Days';
}
