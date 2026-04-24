<?php

return [
    'allowed_extensions' => explode(',', env('ALLOWED_EXTENSIONS', 'jpg,jpeg,png,pdf,doc,docx,xls,xlsx,zip')),
    'max_size' => env('UPLOAD_MAX_SIZE', 10485760),
    'max_files_per_upload' => env('MAX_FILES_PER_UPLOAD', 10),
    'versions_to_keep' => env('FILE_VERSIONS_TO_KEEP', 10),
    'share_expiry_days' => env('FILE_SHARE_EXPIRY_DAYS', 30),
];