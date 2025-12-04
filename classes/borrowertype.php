<?php
require_once '../includes/operation.php';
$getAllBorrowerTypes = display('borrowertype', $conn);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['method']) && $_POST['method'] == 'insertBorrowerType') {
        $data = [
            'type_name' => $_POST['type_name'] ?? '',
            'description' => $_POST['description'] ?? ''
        ];
        
        $insertBorrowerType = insert('borrowertype', $data, $conn);
        if ($insertBorrowerType) {
            header("Location: ../views/index.php#section-borrowertypes");
            exit();
        } else {
            echo "Error inserting borrower type.";
            header("Location: ../views/index.php#section-borrowertypes");
            exit();
        }
    } else if (isset($_POST['method']) && $_POST['method'] == 'updateBorrowerType') {
        $type_id = $_POST['type_id'];
        $data = [
            'type_name' => $_POST['type_name'] ?? '',
            'description' => $_POST['description'] ?? ''
        ];
        
        $updateBorrowerType = update('borrowertype', $data, 'type_id', $type_id, $conn);
        if ($updateBorrowerType) {
            header("Location: ../views/index.php#section-borrowertypes");
            exit();
        } else {
            echo "Error updating borrower type.";
            header("Location: ../views/index.php#section-borrowertypes");
            exit();
        }
    } else if (isset($_POST['method']) && $_POST['method'] == 'deleteBorrowerType') {
        $type_id = $_POST['type_id'];
        $deleteBorrowerType = delete('borrowertype', 'type_id', $type_id, $conn);
        if ($deleteBorrowerType) {
            header("Location: ../views/index.php#section-borrowertypes");
            exit();
        } else {
            echo "Error deleting borrower type.";
        }
    }
}
?>

