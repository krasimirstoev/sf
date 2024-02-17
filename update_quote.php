<?php
// Read the JSON file
$data = file_get_contents('db/database.json');
$quotes = json_decode($data, true);

// Get the quote ID and updated values from the POST data
$id = $_POST['id'];
$updatedValues = $_POST['values'];

// Find the quote with the matching ID
$quoteIndex = array_search($id, array_column($quotes, 'id'));

if ($quoteIndex !== false) {
  // Update the fields of the quote with the updated values
  foreach ($updatedValues as $field => $value) {
    $quotes[$quoteIndex][$field] = $value;
  }

  // Save the updated quotes to the JSON file
  file_put_contents('db/database.json', json_encode($quotes, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT));

  // Send a success response
  echo json_encode(['message' => 'Quote updated successfully']);
} else {
  // Send an error response
  http_response_code(404);
  echo json_encode(['error' => 'Quote not found']);
}
