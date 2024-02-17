<?php
// Read the JSON file
$data = file_get_contents('db/database.json');
$quotes = json_decode($data, true);

// Get the current page number and limit from the query parameters
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$limit = isset($_GET['limit']) ? $_GET['limit'] : 5;

// Calculate the starting index and the ending index for the quotes to be fetched
$startIndex = ($page - 1) * $limit;
$endIndex = $startIndex + $limit - 1;
$totalQuotes = count($quotes);
$totalPages = ceil($totalQuotes / $limit);

// Fetch the quotes based on the calculated indices
$quotesPerPage = array_slice($quotes, $startIndex, $limit);

// Prepare the response data
$response = [
  'quotes' => $quotesPerPage,
  'totalPages' => $totalPages,
  'currentPage' => $page
];

// Send the response as JSON
header('Content-Type: application/json');
echo json_encode($response, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
