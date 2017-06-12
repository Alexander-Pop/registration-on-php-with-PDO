<?php
require_once 'classes/User.php';
require_once 'inc/header.php';
Session::checkSession();
?>
<?php
if (isset($_GET['id'])) {
    $user_id = (int)$_GET['id'];
}
$user = new User();


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $updateUser = $user->updateUserData($user_id, $_POST);
}

?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h2> Профиль пользователя<span class="pull-right"><a href="index.php" class="btn btn-primary">Назад</a></span>
        </h2>
    </div>

    <div class="panel-body">
        <div class="" style="max-width: 600px; margin: 0 auto;">
            <?php
            if (isset($updateUser)) {
                echo $updateUser;
            }
            ?>



            <?php
            $user_data = $user->getUserById($user_id);
            if ($user_data) {
                ?>
                <form action="" method="post">
                    <div class="form-group">
                        <label for="name">Ваше Имя:</label>
                        <input type="text" id="name" name="name" class="form-control" value="<?= $user_data->name; ?>">
                    </div>
                    <div class="form-group">
                        <label for="username">Ваш логин:</label>
                        <input type="text" id="username" name="username" class="form-control"
                               value="<?= $user_data->username; ?>">
                    </div>

                    <div class="form-group">
                        <label for="email">Адрес электронной почты</label>
                        <input type="text" id="email" name="email" class="form-control"
                               value="<?= $user_data->email; ?>">
                    </div>
                    <?php
                    $sesId = Session::get("id");
                    if ($user_id == $sesId) {
                        ?>
                        <button class="btn btn-success" type="submit" name="update">Обновить</button>
                        <a href="change_pass.php?id=<?= $user_id; ?>" class="btn btn-info">Изменить пароль</a>
                    <?php } ?>
                </form>
            <?php } ?>
        </div>
    </div>
</div>
<?php require_once 'inc/footer.php'; ?>
