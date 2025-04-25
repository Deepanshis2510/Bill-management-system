<?php
// fetch_party_details.php
include '../config/db_connection.php'; // Include your PDO connection

if (isset($_GET['id'])) {
    $partyId = intval($_GET['id']); // Get the party ID from the request and sanitize it

    try {
        // Prepare and execute the query using PDO
        $sql = "SELECT gst_no, pan_no FROM master_data WHERE id = :partyId";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':partyId', $partyId, PDO::PARAM_INT);
        $stmt->execute();
        
        $partyDetails = $stmt->fetch(PDO::FETCH_ASSOC); // Fetch the details as an associative array

        if ($partyDetails) {
            // Return the party details as JSON
            echo json_encode($partyDetails);
        } else {
            echo json_encode([]); // Return an empty JSON if no matching record is found
        }
    } catch (PDOException $e) {
        // Handle any error that occurred during the query execution
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Invalid or missing party ID']); // Return an error message if no ID is set
}
?>
