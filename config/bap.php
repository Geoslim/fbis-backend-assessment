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
    'base_url' => env('BAP_BASE_URL', ''),
    'api_key' => env('BAP_API_KEY', ''),
];
