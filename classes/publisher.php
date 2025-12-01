<?php
require_once '../includes/operation.php';
$getAllPublishers = display('publisher', $conn);
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['method']) && $_POST['method'] == 'insertPublisher') {
        $data = [
            'name' => $_POST['name'],
            'city' => $_POST['city'],
            'country' => $_POST['country'],
            'contact_info' => $_POST['contact_info']
        ];

        $insertPublisher = insert('publisher', $data, $conn);
        if ($insertPublisher) {
            header("Location: ../views/index.php#section-publishers");
            exit();
        } else {
            echo "Error inserting publisher.";
            header("Location: ../views/index.php#section-publishers");
            exit();
        }
    }else if (isset($_POST['method']) && $_POST['method'] == 'deletePublisher') {
        // ($table, $field, $id, $conn)
        $publisher_id = $_POST['publisher_id'];
        $deletePublisher = delete('publisher', 'publisher_id', $publisher_id, $conn);
        if ($deletePublisher) {
            header("Location: ../views/index.php#section-publishers");
            exit();
        } else {
            echo "Error deleting publisher.";
        }
    }
    else if (isset($_POST['method']) && $_POST['method'] == 'updatePublisher') {
        $publisher_id = $_POST['publisher_id'];
        $data = [
            'name' => $_POST['name'],
            'city' => $_POST['city'],
            'country' => $_POST['country'],
            'contact_info' => $_POST['contact_info']
        ];

        $updatePublisher = update('publisher', $data, 'publisher_id', $publisher_id, $conn);
        if ($updatePublisher) {
            header("Location: ../views/index.php#section-publishers");
            exit();
        } else {
            echo "Error updating publisher.";
            header("Location: ../views/index.php#section-publishers");
            exit();
        }
    }
}
?>