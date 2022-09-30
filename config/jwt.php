<?php

return [

    'secret_key' => env('JWT_SECRET_KEY'),

    'algorithm' => env('JWT_ALGORITHM') ?? 'HS256'

];

