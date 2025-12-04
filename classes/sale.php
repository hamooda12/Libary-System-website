<?php
require_once '../includes/operation.php';
$getAllSales=display('sale', $conn);
if($_SERVER['REQUEST_METHOD']=='POST'){
    if(isset($_POST['method']) && $_POST['method']=='insertSale'){
          $data=[
                'book_id'=>$_POST['book_id'],
                'borrower_id'=>$_POST['borrower_id'],
                'sale_date'=>$_POST['sale_date'],
                'sale_price'=>$_POST['sale_price']
                
          ];
          
          $insertSale=insert('sale',$data,$conn);
          if($insertSale){
                header("Location: ../views/index.php#section-sales");
                exit();
          }
          else{
                echo "Error inserting sale.";
                header("Location: ../views/index.php#section-sales");
                exit();
          }
     }
     else if(isset($_POST['method']) && $_POST['method']=='deleteSale'){
          // ($table, $field, $id, $conn)
          $sale_id=$_POST['sale_id'];
          $deleteSale=delete('sale','sale_id',$sale_id,$conn);
          if($deleteSale){
                header("Location: ../views/index.php#section-sales");
                exit();
          }
          else{
                echo "Error deleting sale.";
          }  
     }
     else if(isset($_POST['method']) && $_POST['method']=='updateSale'){
          $sale_id=$_POST['sale_id'];
          $data=[
                'book_id'=>$_POST['book_id'],
                'borrower_id'=>$_POST['borrower_id'],
                'sale_date'=>$_POST['sale_date'],
                'sale_price'=>$_POST['sale_price']
                
          ];
          
          $updateSale=update('sale',$data,'sale_id',$sale_id,$conn);
          if($updateSale){
                header("Location: ../views/index.php#section-sales");
                exit();
          }
          
          
          else{
                echo "Error updating sale.";
                header("Location: ../views/index.php#section-sales");
                exit();
          }
     }
}
?>
