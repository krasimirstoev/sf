SF - Simple Fortune

SF (Simple Fortune) is a lightweight PHP application that provides a simple and quick solution for managing and displaying fortunes. Built with simplicity in mind, SF uses a JSON file as its database, making it easy to set up and get started without the need for a traditional database system.

Features

    Fortune Display: Quickly view and share fortunes through a straightforward web interface.
    JSON Database: SF leverages a JSON file for storing fortunes, making it easy to manage and customize.

Why SF?

    Ease of Use: No database setup required; clone the project, and you're ready to go.
    Speed: SF is designed for quick fortune retrieval, thanks to its use of a JSON database.

Getting Started
Prerequisites

    PHP installed on your server (Tested with PHP 7.x).

Installation

    Clone the repository:

    git clone git@github.com:krasimirstoev/sf.git

    Navigate to the project folder in your web server's directory.

    Open the application in your web browser.

Usage

    Access the index.php page to view and share fortunes.
    Customize fortunes directly in the db/database.json file or with the web interface. If you have to edit some fortune, click on the text in "Quote" column, change the text and then click "Edit" button. 
    Deleting the fortunes is as easy as editing - just press "Delete" button for the corresponding fortune.

    Feel free to fork and customize this project, because I don't have any free time for development. Having the database.json, you are able to write a simple API for this project and serve random fortune for your website. This project is also great if you have a coffee bar and want to reduce plastic usage and change the traditional plastic fortune with digital one.

Verification
    SF has a verification feature in mind. If you have a source for every quote, you are able to mark it as "Verified". Will be amazing if you use only verified quotes in your project. <3

License

This project is licensed under the Creative Commons Zero license - see the LICENSE.md file for details.
