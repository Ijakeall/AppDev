<?php
session_start();

if (!isset($_SESSION["todoList"])) {
    $_SESSION["todoList"] = new SplStack();
}

function appendData($task, $dueDate, $list)
{
    $list->push(["task" => $task, "dueDate" => $dueDate]);
    return $list;
}

function deleteData($toDelete, $list)
{
    $tempList = new SplStack();
    while (!$list->isEmpty()) {
        $task = $list->pop();
        if ($task["task"] !== $toDelete) {
            $tempList->push($task);
        }
    }
    return $tempList;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST["task"])) {
        $task = $_POST["task"];
        $dueDate = $_POST["due_date"];

        if (!empty($task) && !empty($dueDate)) {
            $_SESSION["todoList"] = appendData($task, $dueDate, $_SESSION["todoList"]);
        } else {
            echo '<script>alert("Error: Task and Due Date are required")</script>';
        }
    } else {
        echo '<script>alert("Error: There is no data to add to the list")</script>';
    }
}

if (isset($_GET['task'])) {
    $_SESSION["todoList"] = deleteData($_GET['task'], $_SESSION["todoList"]);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enhanced To-Do List</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center">Enhanced To-Do List</h1>
        <div class="card">
            <div class="card-header">Add a new task</div>
            <div class="card-body">
                <form method="post" action="">
                    <div class="form-group">
                        <label for="task">Task</label>
                        <input type="text" class="form-control" name="task" id="task" placeholder="Enter your task here" required>
                    </div>
                    <div class="form-group">
                        <label for="due_date">Due Date</label>
                        <input type="text" class="form-control datepicker" name="due_date" id="due_date" placeholder="Select due date" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Task</button>
                </form>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">Tasks</div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Task</th>
                            <th>Due Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!$_SESSION["todoList"]->isEmpty()) {
                            foreach ($_SESSION["todoList"] as $task) {
                                echo '<tr>';
                                echo '<td>' . $task["task"] . '</td>';
                                echo '<td>' . $task["dueDate"] . '</td>';
                                echo '<td><a href="index.php?task=' . urlencode($task["task"]) . '" class="btn btn-danger">Delete</a></td>';
                                echo '</tr>';
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                todayHighlight: true,
                autoclose: true
            });
        });
    </script>
</body>

</html>
