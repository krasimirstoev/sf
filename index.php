<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Quotes Database</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css">
  <style>
    .editable {
      cursor: pointer;
    }
    .editable:hover {
      background-color: #f9f9f9;
    }
  </style>
</head>
<body>
  <div class="container mt-4">

<?php
// Read the contents of the JSON file
$quotes = json_decode(file_get_contents('db/database.json'), true);

// Get a random quote from the array
$randomIndex = array_rand($quotes);
$randomQuote = $quotes[$randomIndex];

// Generate HTML for the random quote
$html = '<div>';
$html .= '<p><i> ' . $randomQuote['quote'] . '</i></p>';
$html .= '<p><strong>Author:</strong> ' . $randomQuote['author'] . '</p>';
$html .= '<p><strong>Source:</strong> ' . $randomQuote['source'] . '</p>';
$html .= '</div>';

// Output the HTML answer
echo $html;
?>

    <h2>Quotes</h2>
    <table class="table table-striped">
      <thead>
        <tr>
          <th>ID</th>
          <th>Language</th>
          <th>Quote</th>
          <th>Author</th>
          <th>Source</th>
          <th>Verified</th>
          <th>Added By</th>
          <th>Date</th>
          <th>Grand Edit</th>
          <th>Grand Delete</th>
        </tr>
      </thead>
      <tbody id="quote-table-body"></tbody>
    </table>
    <nav>
      <ul class="pagination" id="pagination"></ul>
    </nav>
  </div>

<button id="refresh-quotes-btn">Refresh Quotes</button>


  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script>
    $(document).ready(function() {
      var currentPage = 1;
      var quotesPerPage = 5;

      // Load quotes for the specified page
      function loadQuotes(page) {
        $.ajax({
          url: 'get_quotes.php',
          method: 'GET',
          dataType: 'json',
          data: { page: page, limit: quotesPerPage },
          success: function(response) {
            console.log(response);

            // Clear the existing table body
            $('#quote-table-body').empty();

            // Populate the table with the retrieved quotes
            $.each(response.quotes, function(index, quote) {
              var row = `<tr data-id="${quote.id}">
                          <td class="editable" data-field="id">${quote.id}</td>
                          <td class="editable" data-field="lang">${quote.lang}</td>
                          <td class="editable" data-field="quote">${quote.quote}</td>
                          <td class="editable" data-field="author">${quote.author}</td>
                          <td class="editable" data-field="source">${quote.source}</td>
                          <td class="editable" data-field="is_verified">${quote.is_verified}</td>
                          <td class="editable" data-field="added_by">${quote.added_by}</td>
                          <td class="editable" data-field="date">${quote.date}</td>
                          <td><button class="btn btn-sm btn-primary edit-quote" data-id="${quote.id}">Edit</button></td>
                          <td><button class="btn btn-sm btn-danger delete-quote" data-id="${quote.id}">Delete</button></td>
                        </tr>`;
              $('#quote-table-body').append(row);
            });

            // Update pagination links
            updatePagination(response.totalPages, response.currentPage);
          },
          error: function(error) {
            console.error(error);
          }
        });
      }

      // Update pagination links
      function updatePagination(totalPages, currentPage) {
        var pagination = $('#pagination');
        pagination.empty();

        for (var i = 1; i <= totalPages; i++) {
          var linkClass = i === currentPage ? 'page-item active' : 'page-item';
          var link = `<li class="${linkClass}"><a class="page-link" href="#">${i}</a></li>`;
          pagination.append(link);
        }
      }

      // Handle pagination link click
      $(document).on('click', '.page-link', function(e) {
        e.preventDefault();
        var page = $(this).text();
        currentPage = parseInt(page);
        loadQuotes(currentPage);
      });

// Handle editing of any field
$(document).on('click', '.editable', function() {
  var currentCell = $(this);
  var fieldValue = currentCell.text();

  // Create an input element with the current cell value
  var input = $('<input>')
    .val(fieldValue)
    .addClass('form-control')
    .addClass('editable-input');

  // Replace the cell with the input element
  currentCell.html(input);

  // Set the focus on the input element
  input.focus();
});

// Handle edit button click
$(document).on('click', '.edit-quote', function() {
  var quoteId = $(this).data('id');
  var quoteRow = $(this).closest('tr');
  var updatedValues = {};

  // Iterate through each editable cell in the row and retrieve the updated values
  quoteRow.find('.editable-input').each(function() {
    var field = $(this).closest('td').data('field');
    var updatedValue = $(this).val();
    updatedValues[field] = updatedValue;
  });

  // Send AJAX request to update the fields in the database
  $.ajax({
    url: 'update_quote.php',
    method: 'POST',
    data: { id: quoteId, values: updatedValues },
    success: function(response) {
      console.log(response);

      // Make cells non-editable again
      quoteRow.find('.editable-input').each(function() {
        var field = $(this).closest('td').data('field');
        var updatedValue = updatedValues[field];
        $(this).replaceWith('<span class="editable">' + updatedValue + '</span>');
      });
    },
    error: function(error) {
      console.error(error);
      alert('An error occurred while updating the quote.');
    }
  });
});

// Handle hitting Enter key to save changes
$(document).on('keydown', '.editable-input', function(event) {
  if (event.which === 13) {
    var quoteRow = $(this).closest('tr');
    quoteRow.find('.edit-quote').trigger('click');
  } else if (event.which === 27) {
    var currentCell = $(this).closest('td');
    var fieldValue = $(this).val();
    currentCell.html('<span class="editable">' + fieldValue + '</span>');
  }
});

      // Handle delete button click
      $(document).on('click', '.delete-quote', function() {
        if (confirm('Are you sure you want to delete this quote?')) {
          var quoteId = $(this).data('id');
          var quoteRow = $(this).closest('tr');

          $.ajax({
            url: 'delete_quote.php',
            method: 'POST',
            data: { id: quoteId },
            success: function(response) {
              console.log(response);
              quoteRow.fadeOut(300, function() {
                $(this).remove();
                alert('Quote deleted successfully!');
              });
            },
            error: function(error) {
              console.error(error);
              alert('An error occurred while deleting the quote.');
            }
          });
        }
      });

      // Load initial quotes on page load
      loadQuotes(currentPage);
    });
 </script>

  <div class="container mt-4">
    <h2>Add New Record</h2>
    <form id="addRecordForm">
      <div class="mb-3">
        <label for="language" class="form-label">Language</label>
        <select class="form-select" id="language" name="language">
          <option value="en">English</option>
          <option value="bg">Bulgarian</option>
        </select>
      </div>
      <div class="mb-3">
        <label for="quote" class="form-label">Quote</label>
        <textarea class="form-control" id="quote" name="quote" rows="3" required></textarea>
      </div>
      <div class="mb-3">
        <label for="author" class="form-label">Author</label>
        <input type="text" class="form-control" id="author" name="author" required>
      </div>
      <div class="mb-3">
        <label for="source" class="form-label">Source</label>
        <input type="text" class="form-control" id="source" name="source" required>
      </div>
      <div class="mb-3">
        <label for="verified" class="form-label">Verified</label>
        <select class="form-select" id="verified" name="verified">
          <option value="yes">Yes</option>
          <option value="no">No</option>
        </select>
      </div>
      <button type="submit" class="btn btn-primary">Add Record</button>
    </form>
  </div>

  <!-- Bootstrap 5 JS and jQuery -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <script>
    $(document).ready(function() {
      // Handle form submission
      $('#addRecordForm').submit(function(event) {
        event.preventDefault();

        // Get form data
        var formData = {
          language: $('#language').val(),
          quote: $('#quote').val(),
          author: $('#author').val(),
          source: $('#source').val(),
          verified: $('#verified').val(),
          date: new Date().toISOString().split('T')[0] // Get current date in Y-m-d format
        };

        // Send AJAX request to add the new record
        $.ajax({
          url: 'add_record.php',
          method: 'POST',
          data: formData,
          success: function(response) {
            console.log(response);
            alert('Record added successfully!');
            $('#addRecordForm')[0].reset();
          },
          error: function(error) {
            console.error(error);
            alert('An error occurred while adding the record.');
          }
        });
      });
    });
  </script>
</body>
</html>
