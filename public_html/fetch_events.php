<?php
$googleSheetsApiUrl = 'https://docs.google.com/spreadsheets/d/e/2PACX-1vScets2UoJCsuJFST-6DfRczo9791ofvlOJv2zsPFCDedtQQs6yo9mZ8hLc6YLkoEJJQNc6G1pCuCky/pub?output=csv';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $googleSheetsApiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$output = curl_exec($ch);
if (curl_errno($ch)) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to fetch data from Google Sheets: ' . curl_error($ch)]);
    exit;
}
curl_close($ch);
header('Content-Type: text/csv');
echo $output;
?>
