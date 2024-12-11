<?php

/**
 * BAP (Biller Aggregation Portal) Provider Configuration
 *
 * This file defines the configuration options for interacting with the BAP Payments API.
 *
 * - base_url: The base URL of the BAP Payments API endpoint.
 * - api_key: The API key for your BAP Payments account.
 */

return [
    'base_url' => env('BAP_BASE_URL', 'https://api.staging.baxibap.com'),
    'api_key' => env('BAP_API_KEY', 'T7Wi2Q7tHFkq6sxU5WaUSBIGg3ynb96Qi74RnAeY6ys='),
];
