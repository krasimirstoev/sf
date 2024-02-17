<?php
// Read the JSON file
$data = file_get_contents('db/database.json');
$quotes = json_decode($data, true);

// Get the quote ID from the POST data
$id = $_POST['id'];

// Find the quote with the matching ID
$quoteIndex = array_search($id, array_column($quotes, 'id'));

if ($quoteIndex !== false) {
  // Ensure $quoteIndex is an integer
  if (!is_int($quoteIndex)) {

    // Optionally, you can handle the case where $quoteIndex is not an integer
    // For example, log an error, throw an exception, or convert it to an integer

    $quoteIndex = (int)$quoteIndex; // Convert $quoteIndex to an integer
  }

  array_splice($quotes, $quoteIndex, 1);

  // Save the updated quotes to the JSON file
  file_put_contents('db/database.json', json_encode($quotes, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT));

  // Send a success response
  echo json_encode(['message' => 'Quote deleted successfully']);
} else {
  // Send an error response
  http_response_code(404);
  echo json_encode(['error' => 'Quote not found']);
}
