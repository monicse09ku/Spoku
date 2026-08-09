<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Complaint publication threshold
    |--------------------------------------------------------------------------
    |
    | A complaint is published when this many approvers have approved it.
    | Example: 7 out of 10 approvers.
    |
    */
    'approval_threshold' => (int) env('SPOKU_APPROVAL_THRESHOLD', 7),

    'approval_pool_size' => (int) env('SPOKU_APPROVAL_POOL_SIZE', 10),
];
