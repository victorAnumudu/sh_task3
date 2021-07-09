<?php 


    $row = "";

    require_once("db_connection.php");
    if($connection->connect_error) {
        die("Connection Error ".$connection->connect_error);
        header("location: index.php");
        exit();
    } 
    if(isset($_POST['delete_todo'])) {
        $id = $_GET['id'];
        $stmt = $connection->prepare("DELETE FROM todos WHERE id=? LIMIT 1");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        header("location: index.php");
        
    }
    else if(isset($_GET['id'])){
        $id = $_GET['id'];
    
        $stmt = $connection->prepare("SELECT * FROM todos WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        // no record found
        if($result->num_rows <= 0) {
            header("location: index.php?error=norecordfound");
            exit();
        } 
        // record found
        else {
            $row = $result->fetch_assoc()['todo'];
        }
    }
    else {
        header("location: index.php");
    }
        
      
    $stmt->close();
    $connection->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>delete Todo</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <div class="todo_container">
        <a href="index.php">Back</a>
        <h1>Are you sure you want to delete?</h1>
               
        <form action="delete.php?id=<?php echo $id ?>" method="post">
            <input type="text" placeholder="enter todo" disabled name="todo" value="<?php echo $row; ?>">
            <input type="submit" value="delete Todo" name="delete_todo">
        </form>
    </div>
</body>
</html>