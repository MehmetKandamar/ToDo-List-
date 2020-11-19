<?php
// Hata değişkenini başlat
$errors = "";

// Database bağlantısı
$db = mysqli_connect("localhost", "root", "", "todo");

// Görev tanımlandamadan kaydetmeye çalışırsa hata mesajı
if (isset($_POST['submit'])) {
    if (empty($_POST['task'])) {
        $errors = "Before clicking the submit button add your task please!";
    }else{
        $task = $_POST['task'];
        $sql = "INSERT INTO tasks (task) VALUES ('$task')";
        mysqli_query($db, $sql);
        header('location: index.php');
    }
    }
// Görevi silme kısmı
if (isset($_GET['del_task'])) {
    $id = $_GET['del_task'];

    mysqli_query($db, "DELETE FROM tasks WHERE id=".$id);
    header('location: index.php');
    $count = $db->query('SELECT COUNT(tasks.id) from tasks')->fetch_all(PDO::FETCH_ASSOC);

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Mehmet's First Demo</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<div class="heading">
    <h1 style="font-style: 'Arial';">My ToDo List</h1>
</div>
<form method="post" action="index.php" class="input_form">
    <label>
        <input type="text" name="task" class="task_input">
    </label>
    <button type="submit" name="submit" id="add_btn" class="add_btn">Add Task</button>
</form>
    <table>
        <thead>
        <tr>
            <th style="text-align: left">Tasks <?php
                $count = mysqli_query($db, "SELECT COUNT(id) as total from tasks ");?>
                <?php foreach ($count as $total):?>
                (<?php echo $total['total']?>)
                <?php endforeach;?>
            </th>
            <th style="width: 60px;">Action</th>
        </tr>
        </thead>

        <tbody>
        <?php
        // Sayfaya tekrar girilirse veya yenilenirse tüm görevler devam
        $tasks = mysqli_query($db, "SELECT * FROM tasks");

        $i = 1; while ($row = mysqli_fetch_array($tasks)) { ?>
            <tr>
                <td class="task"> <?php echo $row['task']; ?> </td>
                <td class="delete">
                    <a href="index.php?del_task=<?php echo $row['id'] ?>"> <button type="submit" name="submit" id="add_btn" class="add_btn">delete</button></a>
                </td>
            </tr>
            <?php $i++; } ?>
        </tbody>
    </table>
<?php if (isset($errors)) { ?>
    <p><?php echo $errors; ?></p>
<?php } ?>
</body>
</html>