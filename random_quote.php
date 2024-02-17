<?php

// Function to get a random quote from the JSON file
function getRandomQuote() {
    $quotesJson = file_get_contents('db/database.json'); // Adjust the filename if needed
    $quotes = json_decode($quotesJson, true);

    if (json_last_error() === JSON_ERROR_NONE && !empty($quotes)) {
        $randomIndex = array_rand($quotes);
        return $quotes[$randomIndex]['quote'];
    } else {
        return "Error: Unable to retrieve a random quote.";
    }
}

// Function to set a cookie with the quote for 10 minutes
function setQuoteCookie($quote) {
    setcookie('random_quote', $quote, time() + 24 * 60 * 60, '/');
}

// Function to get the quote from the cookie
function getQuoteFromCookie() {
    return isset($_COOKIE['random_quote']) ? $_COOKIE['random_quote'] : null;
}

// Check if a quote is already set in the cookie
$existingQuote = getQuoteFromCookie();

// If no quote is set, get a random quote, set it in the cookie, and return it
if (!$existingQuote) {
    $randomQuote = getRandomQuote();
    setQuoteCookie($randomQuote);
    $existingQuote = $randomQuote;
}

// Generate an HTML response
$htmlResponse = '<div style="padding: 20px; border: 1px solid #ccc; margin: 20px;">';
$htmlResponse .= '<h3>Random Quote</h3>';
$htmlResponse .= '<p>' . $existingQuote . '</p>';
$htmlResponse .= '</div>';

// Set the response content type to HTML
header('Content-Type: text/html');

// Echo the HTML response
echo $htmlResponse;
