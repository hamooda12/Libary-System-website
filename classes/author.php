<?php

require_once '../includes/operation.php';
$getAllAuthors=display('author', $conn);
if($_SERVER['REQUEST_METHOD']=='POST'){
   if(isset($_POST['method']) && $_POST['method']=='updateAuthor'){
    $author_id=$_POST['author_id'];
    $data=[
        'first_name'=>$_POST['first_name'],
        'last_name'=>$_POST['last_name'],
        'country'=>$_POST['country'],
        'bio'=>$_POST['bio']
        
    ];
    
    $updateAuthor=update('author',$data,'author_id',$author_id,$conn);
    if($updateAuthor){
        header("Location: ../views/index.php#section-authors");
        exit();
    }
     
    
    else{
        echo "Error updating author.";
        header("Location: ../views/index.php#section-authors");
        exit();
    }
   }
else if(isset($_POST['method']) && $_POST['method']=='deleteAuthor'){
    // ($table, $field, $id, $conn)
    $author_id=$_POST['author_id'];
    $deleteAuthor=delete('author','author_id',$author_id,$conn);
    if($deleteAuthor){
        header("Location: ../LibarySystem/login.php#section-authors");
        exit();
    }
    else{
        echo "Error deleting author.";
    }  }
    else if(isset($_POST['method']) && $_POST['method']=='insertAuthor'){
        $data=[
            'first_name'=>$_POST['first_name'],
            'last_name'=>$_POST['last_name'],
            'country'=>$_POST['country'],
            'bio'=>$_POST['bio']
            
        ];
        
        $insertAuthor=insert('author',$data,$conn);
        if($insertAuthor){
            header("Location: ../views/index.php#section-authors");
            exit();
        }
        else{
            echo "Error inserting author.";
            header("Location: ../views/index.php#section-authors");
            exit();
        }
    }


}
?>