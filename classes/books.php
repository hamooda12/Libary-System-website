<?php

require_once '../includes/operation.php';
$getAllBooks=display('book', $conn);
if($_SERVER['REQUEST_METHOD']=='POST'){
   if(isset($_POST['method']) && $_POST['method']=='updateBook'){
    $book_id=$_POST['book_id'];
    $data=[
        'title'=>$_POST['title'],
        'book_type'=>$_POST['type'],
        'publisher_id'=>$_POST['publisher_id'],
        'category'=>$_POST['category'],
        'original_price'=>$_POST['price'],
        'available'=>$_POST['available']
        
    ];
    if (!helperUpdate($data['publisher_id'],'publisher',$conn,'publisher_id')){
       
        header("Location: ../views/index.php?publisher_error=1#section-books");
        exit();

    }
    else{
    $updateBook=update('book',$data,'book_id',$book_id,$conn);
    if($updateBook){
        header("Location: ../views/index.php#section-books");
        exit();
    }
     
    
    else{
        echo "Error updating book.";
        header("Location: ../views/index.php#section-books");
        exit();
    }
   }}
else if(isset($_POST['method']) && $_POST['method']=='deleteBook') {

    $book_id = $_POST['book_id'];

    // 1) Get all tables that reference 'book.book_id'
    $sql = "SELECT TABLE_NAME, COLUMN_NAME 
            FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
            WHERE REFERENCED_TABLE_NAME = 'book'
            AND REFERENCED_COLUMN_NAME = 'book_id'
            AND TABLE_SCHEMA = ?";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $db);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $blockingTables = [];

   
    while($row = mysqli_fetch_assoc($result)) {
        $table = $row['TABLE_NAME'];
        $column = $row['COLUMN_NAME'];

        $checkSql = "SELECT COUNT(*) AS cnt FROM `$table` WHERE `$column` = ?";
        $checkStmt = mysqli_prepare($conn, $checkSql);
        mysqli_stmt_bind_param($checkStmt, "i", $book_id);
        mysqli_stmt_execute($checkStmt);
        $checkResult = mysqli_stmt_get_result($checkStmt);
        $countRow = mysqli_fetch_assoc($checkResult);

        if ($countRow['cnt'] > 0) {
           
            $blockingTables[] = "$table.$column";
        }
    }

    
    if (!empty($blockingTables)) {
        $fkString = implode(", ", $blockingTables);
        header("Location: ../views/index.php?foreign_key_error=$fkString#section-books");
        exit();
    }


    $deleted = delete('book', 'book_id', $book_id, $conn);

    if ($deleted) {
        header("Location: ../views/index.php#section-books");
        exit();
    } else {
        header("Location: ../views/index.php?delete_error=failed#section-books");
        exit();
    }
}

    
     
    else if(isset($_POST['method']) && $_POST['method']=='insertBook'){
        $data=[
            'title'=>$_POST['title'],
            'book_type'=>$_POST['book_type'],
            'publisher_id'=>$_POST['publisher_id'],
            'category'=>$_POST['category'],
            'original_price'=>$_POST['original_price'],
            'available'=>$_POST['available']
            
        ];
        if (!helperUpdate($data['publisher_id'],'publisher',$conn,'publisher_id')){
       
            header("Location: ../views/index.php?publisher_error=1#section-books");
            exit();
    
        }
        else{
        $insertBook=insert('book',$data,$conn);
        if($insertBook){
            header("Location: ../views/index.php#section-books");
            exit();
        }
         
        
        else{
            echo "Error inserting book.";
            header("Location: ../views/index.php#section-books");
            exit();
        }
       }
    }
}


?>