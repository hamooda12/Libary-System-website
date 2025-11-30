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
else if(isset($_POST['method']) && $_POST['method']=='deleteBook'){
    // ($table, $field, $id, $conn)
    $book_id=$_POST['book_id'];
    $deleteBook=delete('book','book_id',$book_id,$conn);
    if($deleteBook){
        header("Location: ../views/index.php#section-books");
        exit();
    }
    else{
        echo "Error deleting book.";
    }  }


}
?>