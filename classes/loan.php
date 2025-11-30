<?php

require_once '../includes/operation.php';
$getAllLoans=display('loan', $conn);
if($_SERVER['REQUEST_METHOD']=='POST'){
   if(isset($_POST['method']) && $_POST['method']=='updateLoan'){
    $loan_id=$_POST['loan_id'];
    $data=[
        'book_id'=>$_POST['book_id'],
        'period_id'=>$_POST['period_id'],
        'loan_date'=>$_POST['loan_date'],
        'due_date'=>$_POST['due_date'],
        'return_date'=>$_POST['return_date']
        
    ];
   
    
    $updateLoan=update('loan',$data,'loan_id',$loan_id,$conn);
    if($updateLoan){
        header("Location: ../views/index.php#section-loan");
        exit();
    }
     
    
    else{
        echo "Error updating loan.";
        header("Location: ../views/index.php#section-loan");
        exit();
    }
   }
else if(isset($_POST['method']) && $_POST['method']=='deleteLoan'){
    // ($table, $field, $id, $conn)
    $loan_id=$_POST['loan_id'];
    $deleteLoan=delete('loan','loan_id',$loan_id,$conn);
    if($deleteLoan){
        header("Location: ../views/index.php#section-loan");
        exit();
    }
    else{
        echo "Error deleting loan.";
    }  }


}
?>