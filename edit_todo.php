<?php 


    $row = "";
    $error = "";
    if(isset($_GET["error"])){
        if($_GET["error"] == "noinput"){
        $error = "Opps! Please fill the input box.";
        }else if ($_GET['error'] == "inputlessthanthreecharacters"){
            $error = "Opps! Todo must be greater than 2 characters";
        }
        else{
            // do this id no error exist
            $error = "";
        }
    }

    require_once("db_connection.php");
    if($connection->connect_error) {
        die("Connection Error ".$connection->connect_error);
        header("location: index.php");
        exit();
    } 
    if(isset($_POST['update_todo'])) {
        $id = $_GET['id'];
        $todo = $_POST["todo"];
        if(empty($todo) && $todo != "0"){
            header("location: edit_todo.php?id=$id&error=noinput");
            exit();
        }
        else if(strlen($todo) < 3){
            header("location: edit_todo.php?id=$id&error=inputlessthanthreecharacters");
            exit();
        }
        $stmt = $connection->prepare("UPDATE todos SET todo=? WHERE id=?");
        $stmt->bind_param("si", $todo, $id);
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
    <title>Edit Todo</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <div class="todo_container">
        <a href="index.php">Back</a>
        <h1>Are you sure you want to edit?</h1>
        <p class="error"><?php echo $error ?></p>
        <form action="edit_todo.php?id=<?php echo $id ?>" method="post">
            <input type="text" placeholder="enter todo" name="todo" value="<?php echo $row; ?>">
            <input type="submit" value="save changes" name="update_todo">
        </form>
    </div>
</body>
</html>