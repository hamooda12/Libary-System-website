<?php
require_once '../includes/operation.php';
$getAllLoanPeriods = display('loanperiod', $conn);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['method']) && $_POST['method'] == 'insertLoanPeriod') {
        $data = [
            'period_name' => $_POST['period_name'] ?? '',
            'days' => $_POST['days'] ?? 0,
            'description' => $_POST['description'] ?? ''
        ];
        
        $insertLoanPeriod = insert('loanperiod', $data, $conn);
        if ($insertLoanPeriod) {
            header("Location: ../views/index.php#section-loanperiods");
            exit();
        } else {
            echo "Error inserting loan period.";
            header("Location: ../views/index.php#section-loanperiods");
            exit();
        }
    } else if (isset($_POST['method']) && $_POST['method'] == 'updateLoanPeriod') {
        $period_id = $_POST['period_id'];
        $data = [
            'period_name' => $_POST['period_name'] ?? '',
            'days' => $_POST['days'] ?? 0,
            'description' => $_POST['description'] ?? ''
        ];
        
        $updateLoanPeriod = update('loanperiod', $data, 'period_id', $period_id, $conn);
        if ($updateLoanPeriod) {
            header("Location: ../views/index.php#section-loanperiods");
            exit();
        } else {
            echo "Error updating loan period.";
            header("Location: ../views/index.php#section-loanperiods");
            exit();
        }
    } else if (isset($_POST['method']) && $_POST['method'] == 'deleteLoanPeriod') {
        $period_id = $_POST['period_id'];
        $deleteLoanPeriod = delete('loanperiod', 'period_id', $period_id, $conn);
        if ($deleteLoanPeriod) {
            header("Location: ../views/index.php#section-loanperiods");
            exit();
        } else {
            echo "Error deleting loan period.";
        }
    }
}
?>

