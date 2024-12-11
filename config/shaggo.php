<?php

/**
 * Shaggo Payment Provider Configuration
 *
 * This file defines the configuration options for interacting with the Shaggo Payments API.
 *
 * - base_url: The base URL of the Shaggo Payments API endpoint.
 * - api_key: The API key for your Shaggo Payments account.
 */

return [
    'base_url' => env('SHAGGO_BASE_URL', ''),
    'api_key' => env('SHAGGO_API_KEY', ''),
];
