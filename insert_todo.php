<?php 

    // if the submit todo btn is clicked
    if(isset($_POST["submit_todo"])){
        $my_todo = $_POST["todo"];
        // if the todo input is empty
        if(empty($my_todo) && $my_todo != "0"){
            header("location: index.php?error=noinput");
            exit();
        }
        else if(strlen($my_todo) < 3){
            header("location: index.php?error=inputlessthanthreecharacters");
            exit();
        }
        else{
            require_once("db_connection.php");
            //if connection to database failed
            if($connection->connect_error) {
                die("Connection Error ".$connection->connect_error);
                header("location: index.php?error=servererror");
                exit();
            }

            $stmt = $connection->prepare("INSERT INTO todos (todo) VALUES (?)");
            $stmt->bind_param("s", $my_todo);
            $stmt->execute();

            $stmt->close();
            $connection->close();

            header("location: index.php?error=success");
            exit();
        }

    }else{
        // if submit todo btn id not clicked
        header("location: index.php");
        exit();
    }

?>