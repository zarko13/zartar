<?php

return [
    'exact_threshold' => env('ZARTAR_SIMILARITY_EXACT_THRESHOLD', 90),
    'did_you_mean_threshold' =>  env('ZARTAR_DID_YOU_MEAN_THRESHOLD', 50),
];
