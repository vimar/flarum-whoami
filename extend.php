<?php

namespace Vimar\FlarumWhoAmI;

use Flarum\Extend;

return [
    (new Extend\Routes('api'))
    ->get('/whoami', 'whoami', Api\Controllers\WhoamiController::class)
];

