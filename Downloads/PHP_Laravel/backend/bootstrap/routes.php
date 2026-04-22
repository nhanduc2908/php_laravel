<?php

/*
|--------------------------------------------------------------------------
| Load The Application Routes
|--------------------------------------------------------------------------
|
| This file is responsible for loading all of the application's route files.
| It will include the web, api, and console routes automatically.
|
*/

// Load web routes (for browser)
require base_path('routes/web.php');

// Load API routes (for RESTful API)
require base_path('routes/api.php');

// Load console routes (for Artisan commands)
require base_path('routes/console.php');

// Load WebSocket channels (for broadcasting)
if (file_exists(base_path('routes/channels.php'))) {
    require base_path('routes/channels.php');
}