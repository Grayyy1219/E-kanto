<?php

include('connect_db.php');

header('Content-Type: application/json'); // Set the response content type

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve data sent from the client-side
    $customerId = $_POST["customerId"];
    $isChecked = $_POST["isChecked"];
    $action = $_POST["action"];

    // Perform database update based on the checkbox state and action
    try {
        // Example: Update a 'blocked' column in the customer_info table
        $blockedValue = ($isChecked === "1" || $isChecked === "true" || $isChecked === true) ? 1 : 0;

        // Update the 'blocked' column based on the action
        if ($action === "block") {
            $sql = "UPDATE customer_info SET blocked = :blockedValue WHERE customer_id = :customerId";
        } elseif ($action === "unblock") {
            $sql = "UPDATE customer_info SET blocked = 0 WHERE customer_id = :customerId";
        } else {
            throw new Exception("Invalid action");
        }

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":customerId", $customerId, PDO::PARAM_INT);

        // Bind the blockedValue parameter only for block action
        if ($action === "block") {
            $stmt->bindParam(":blockedValue", $blockedValue, PDO::PARAM_INT);
        }

        $pdo->beginTransaction();
        $stmt->execute();
        $pdo->commit();

        // Database update successful
        echo json_encode(["status" => "success"]);
    } catch (PDOException $e) {
        // Database update failed
        $pdo->rollBack();
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    } catch (Exception $e) {
        // Invalid action
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
} else {
    // Invalid request method
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}

?>
