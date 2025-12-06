<?php

require_once '../includes/operation.php';
$getAllBorrowers=display('borrower', $conn);
if($_SERVER['REQUEST_METHOD']=='POST'){
   if(isset($_POST['method']) && $_POST['method']=='updateBorrower'){
    $borrower_id=$_POST['borrower_id'];
    $data=[
        'first_name'=>$_POST['first_name'],
        'last_name'=>$_POST['last_name'],
        'borrowertype_id'=>$_POST['type_id'],
        'contact_info'=>$_POST['contact_info']
        
    ];
    
    $updateBorrower=update('borrower',$data,'borrower_id',$borrower_id,$conn);
    if($updateBorrower){
        header("Location: ../views/index.php#section-borrower");
        exit();
    }
     
    
    else{
        echo "Error updating borrower.";
        header("Location: ../views/index.php#section-borrower");
        exit();
    }
   }
else if(isset($_POST['method']) && $_POST['method']=='deleteBorrower'){
    // ($table, $field, $id, $conn)
    $borrower_id=$_POST['borrower_id'];
    $deleteBorrower=delete('borrower','borrower_id',$borrower_id,$conn);
    if($deleteBorrower){
        header("Location: ../views/index.php#section-borrowers");
        exit();
    }
    else{
        echo "Error deleting borrower.";
    }  }
    else if(isset($_POST['method']) && $_POST['method']=='insertBorrower'){
        $data=[
            'first_name'=>$_POST['first_name'],
            'last_name'=>$_POST['last_name'],
            'borrowertype_id'=>$_POST['type_id'],
            'contact_info'=>$_POST['contact_info']
            
        ];
        
        $insertBorrower=insert('borrower',$data,$conn);
        if($insertBorrower){
            header("Location: ../views/index.php#section-borrowers");
            exit();
        }
        else{
            echo "Error inserting borrower.";
            header("Location: ../views/index.php#section-borrowers");
            exit();
        }
    }


}
?>