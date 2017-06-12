<?php
require_once 'classes/User.php';
require_once 'inc/header.php';
Session::checkSession();

$login_msg = Session::get("login_msg");
if (isset($login_msg)) {
    echo $login_msg;
}
Session::set("login_msg", null);

?>

<div class="panel panel-default">
    <div class="panel-heading">
        <h2>Список пользователей <span class="pull-right">Привет!<strong>
            <?php
            $username = Session::get("username");
            if (isset($username)) {
                echo $username;
            }
            ?>
            </strong></span></h2>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <tr>
                    <th width="20%">№</th>
                    <th width="20%">Имя</th>
                    <th width="20%">Логин</th>
                    <th width="20%">Email</th>
                    <th width="20%">Действия</th>
                </tr>
                <?php
                $user = new User();
                $user_data = $user->getUserData();
                if ($user_data) {
                    $i = 0;
                    foreach ($user_data as $data) {
                        $i++;
                        ?>
                        <tr>
                            <td><?= $i; ?></td>
                            <td><?= $data['name'] ?></td>
                            <td><?= $data['username'] ?></td>
                            <td><?= $data['email'] ?></td>
                            <td>
                                <a href="profile.php?id=<?= $data['id'] ?>" class="btn btn-primary">Посмотреть</a>
                            </td>
                        </tr>
                    <?php }
                } else { ?>
                    <tr>
                        <td colspan="5"><h2>Нет данных</h2></td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</div>
<?php require_once 'inc/footer.php'; ?>
