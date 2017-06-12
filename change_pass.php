<?php
require_once 'classes/User.php';
require_once 'inc/header.php';
Session::checkSession();
?>


<?php
if (isset($_GET['id'])) {
    $user_id = (int)$_GET['id'];
    $sesId = Session::get("id");
    if ($user_id != $sesId) {
        header("Location: index.php");
    }
}
$user = new User();


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_pass'])) {
    $updatePass = $user->updatePassword($id, $_POST);
}

?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h2> Смена пароля <span class="pull-right"><a href="profile.php?id=<?=$user_id; ?>" class="btn btn-primary">Назад</a></span></h2>
    </div>

    <div class="panel-body">
        <div class="" style="max-width: 600px; margin: 0 auto;">
            <?php
            if (isset($updatePass)) {
                echo $updatePass;
            }
            ?>

            <form action="" method="post">
                <div class="form-group">
                    <label for="old_pass">Старый пароль:</label>
                    <input type="password" id="old_pass" name="old_pass" class="form-control">
                </div>
                <div class="form-group">
                    <label for="password">Новый пароль:</label>
                    <input type="password" id="password" name="password" class="form-control">
                </div>
                <button class="btn btn-success" type="submit" name="update_pass">Обновить</button>
            </form>
        </div>
    </div>
</div>
<?php require_once 'inc/footer.php'; ?>
