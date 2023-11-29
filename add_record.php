<?php
// Read existing records from the database
$databaseFile = 'db/database.json';
$records = json_decode(file_get_contents($databaseFile), true);

// Get the data from the form
$language = $_POST['language'];
$quote = $_POST['quote'];
$author = $_POST['author'];
$source = $_POST['source'];
$verified = $_POST['verified'];
$date = date('Y-m-d');

// Generate a new auto-increment ID
$id = 1;
if (!empty($records)) {
    $lastRecord = end($records);
    $id = $lastRecord['id'] + 1;
}

// Create a new record
$newRecord = [
    'id' => $id,
    'lang' => $language,
    'quote' => $quote,
    'author' => $author,
    'source' => $source,
    'is_verified' => $verified,
    'added_by' => 'admin',
    'date' => $date
];

// Add the new record to the records array
$records[] = $newRecord;

// Save the updated records back to the database file
file_put_contents($databaseFile, json_encode($records, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT));

// Return a success message
echo 'Record added successfully!';
?>
