<?php 

    $todos = [];
    
    if(isset($_GET['error'])){
        // do this if error exist
        if($_GET['error'] == "servererror"){
            $error = "Opps! unable to proceed.";
        }else if ($_GET['error'] == "noinput"){
            $error = "Please fill out the input box";
        }else if ($_GET['error'] == "servererror"){
            $error = "error inserting todo";
        }else if ($_GET['error'] == "inputlessthanthreecharacters"){
            $error = "Opps! Todo must be greater than 2 characters";
        }
        else{
            // do this id no error exist
            $error = "";
        }

    }else{
    // do this id no error exist
    $error = "";
    }

    require_once("db_connection.php");
    //if connection to database failed
    if($connection->connect_error) {
        die("Connection Error ".$conn->connect_error);
        header("location: index.php?error=servererror");
        exit();
    }

    $stmt = $connection->prepare("SELECT * FROM todos");
    // $stmt->bind_param("s", $firstName);
    $stmt->execute();
    $result = $stmt->get_result();
   
    if($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $todos[] = $row;
        }
    } else {
        $todos = [];
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
    <title>Todo</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <div class="todo_container">
        <h1>Todo App</h1>
        <form action="insert_todo.php" method="post">
            <input type="text" placeholder="enter todo" name="todo">
            <input type="submit" value="Insert Todo" name="submit_todo">
        </form>
        <div>
            <p class="error"><?php echo $error ?></p>
            <h2 class="heading"><?php echo count($todos)>0 ? "todo list" : "" ?></h2>
            
            <table>
            <?php 
                    for($i = 0; $i < count($todos); $i++) {
                ?>
                <tr>
                    <td><?php echo $i+1 ?></td>
                    <td><?php echo $todos[$i]["todo"] ?></td>
                    <td><a href="edit_todo.php?id=<?php echo $todos[$i]["id"] ?>">edit todo</a></td>
                    <td><a href="delete.php?id=<?php echo $todos[$i]["id"] ?>" title="delete todo">delete todo</a></td>
                </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</body>
</html>