<?php
// Google Sheets API URL
$googleSheetsApiUrl = 'https://docs.google.com/spreadsheets/d/e/2PACX-1vScets2UoJCsuJFST-6DfRczo9791ofvlOJv2zsPFCDedtQQs6yo9mZ8hLc6YLkoEJJQNc6G1pCuCky/pub?output=csv';

// Initialize a cURL session
$ch = curl_init();

// Set the URL
curl_setopt($ch, CURLOPT_URL, $googleSheetsApiUrl);

// Return the transfer as a string
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

// Follow redirects
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

// Execute the cURL session
$output = curl_exec($ch);

// Check for cURL errors
if (curl_errno($ch)) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to fetch data from Google Sheets: ' . curl_error($ch)]);
    exit;
}

// Close the cURL session
curl_close($ch);

// Set the appropriate header for CSV response
header('Content-Type: text/csv');

// Output the result
echo $output;