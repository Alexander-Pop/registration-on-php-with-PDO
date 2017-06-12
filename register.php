<?php require_once 'inc/header.php'; ?>
<?php require_once 'classes/User.php'; ?>


<?php
$user = new User();
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])){
    $userReg = $user->userRegistration($_POST);
}
?>


<div class="panel panel-default">
    <div class="panel-heading">
        <h2>Регистрация пользователя</h2>
    </div>
    <div class="panel-body">
        <div class="" style="max-width: 600px; margin: 0 auto;">

            <?php
                if (isset($userReg)){
                    echo $userReg;
                }
            ?>

            <form action="" method="post">
                <div class="form-group">
                    <label for="name">Ваше имя:</label>
                    <input type="text" id="name" name="name" class="form-control">
                </div>

                <div class="form-group">
                    <label for="username">Ваш логин:</label>
                    <input type="text" id="username" name="username" class="form-control">
                </div>

                <div class="form-group">
                    <label for="email">Адрес электронной почты</label>
                    <input type="text" id="email" name="email" class="form-control">
                </div>

                <div class="form-group">
                    <label for="password">Пароль</label>
                    <input type="password" id="password" name="password" class="form-control">
                </div>
                <button class="btn btn-success" type="submit" name="register">Регистрация</button>
            </form>
        </div>
    </div>
</div>
<?php require_once 'inc/footer.php'; ?>
