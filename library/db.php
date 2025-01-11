<?php 
try {
    // Establishing a PDO connection to the MySQL database
    $conn = new PDO("mysql:host=localhost;dbname=bibliotheque", 'root', '');

} catch(PDOException $e) {
    // Catching the exception and printing the error message
    echo "Error: " . $e->getMessage();
    die();
}
?>
