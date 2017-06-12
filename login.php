<?php require_once 'inc/header.php'; ?>
<?php require_once 'classes/User.php';
Session::checkLogin();
?>

<?php
$user = new User();
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $userLogin = $user->userLogin($_POST);
}
?>

<div class="panel panel-default">
    <div class="panel-heading">
        <h2>Логин пользователя</h2>
    </div>
    <div class="panel-body">
        <div class="" style="max-width: 600px; margin: 0 auto;">

            <?php
            if (isset($userLogin)) {
                echo $userLogin;
            }
            ?>
            <form action="" method="post" class="">
                <div class="form-group">
                    <label for="email">Адрес электронной почты</label>
                    <input type="text" id="email" name="email" class="form-control">
                </div>
                <div class="form-group">
                    <label for="password">Пароль</label>
                    <input type="password" id="password" name="password" class="form-control">
                </div>
                <button class="btn btn-success" type="submit" name="login">Войти</button>
            </form>
        </div>
    </div>
</div>
<?php require_once 'inc/footer.php'; ?>
