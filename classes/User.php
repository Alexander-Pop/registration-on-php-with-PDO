<?php
require_once "Session.php";
require_once "Database.php";

/*
 * preg_match — Выполняет проверку на соответствие регулярному выражению
 * filter_var — Фильтрует переменную с помощью определенного фильтра
 * */

/**
 * Created class User
 */
class User
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }


    // Метод регистрации нового пользователя
    public function userRegistration($data)
    {
        $name = $data['name'];
        $username = $data['username'];
        $email = $data['email'];
        $password = $data['password'];

        $chk_email = $this->emailCheck($email);


        // Проверка на заполнение всех полей
        if ($name == '' or $username == '' or $email == '' or $password == '') {
            $msg = "<div class='alert alert-danger'><strong>Ошибка !</strong> Поле не должно быть пустым !</div>";
            return $msg;
        }

        // Проверка на Имя пользователя
        if (strlen($username) < 4) {
            $msg = "<div class='alert alert-danger'><strong>Ошибка !</strong> Слишком короткое имя пользователя !</div>";
            return $msg;
        } elseif (preg_match('/[^a-z0-9_-]+/i', $username)) {
            $msg = "<div class='alert alert-danger'><strong>Ошибка !</strong> Имя пользователя должно содержать только буквенно-цифровые символы, дефисы и знаки подчеркивания !</div>";
            return $msg;
        }

        // Проверка на Email
        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            $msg = "<div class='alert alert-danger'><strong>Ошибка !</strong> Недействительный адрес электронной почты!</div>";
            return $msg;
        }
        if ($chk_email == true) {
            $msg = "<div class='alert alert-danger'><strong>Ошибка !</strong> Адрес электронной почты уже существует!</div>";
            return $msg;
        }

        $password = md5($data['password']);

        $sql = "INSERT INTO tbl_user (name, username, email, password) VALUES (:name, :username, :email, :password)";
        $query = $this->db->pdo->prepare($sql);
        $query->bindValue(':name', $name);
        $query->bindValue(':username', $username);
        $query->bindValue(':email', $email);
        $query->bindValue(':password', $password);

        $result = $query->execute();

        if ($result) {
            $msg = "<div class='alert alert-success'><strong>Успешно!</strong> Спасибо, вы были зарегистрированы!</div>";
            return $msg;
        } else {
            $msg = "<div class='alert alert-danger'><strong>Ошибка!</strong>Извините, возникла проблема с вставкой ваших данных!</div>"; // Sorry, there has been problem inserting your details
            return $msg;
        }

    }

    // Метод проверки существования email
    public function emailCheck($email)
    {
        $sql = "SELECT email FROM tbl_user WHERE email = :email";
        $query = $this->db->pdo->prepare($sql);
        $query->bindValue(':email', $email);
        $query->execute();

        if ($query->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }


    public function getLoginUser($email, $password)
    {
        $sql = "SELECT * FROM tbl_user WHERE email = :email AND password = :password LIMIT 1";
        $query = $this->db->pdo->prepare($sql);
        $query->bindValue(':email', $email);
        $query->bindValue(':password', $password);
        $query->execute();

        $result = $query->fetch(PDO::FETCH_OBJ);
        return $result;
    }


    // Метод Авторизации пользователя
    public function userLogin($data)
    {
        $email = $data['email'];

        $password = md5($data['password']);
        $chk_email = $this->emailCheck($email);

        // Проверка на заполнение всех полей
        if ($email == '' or $password == '') {
            $msg = "<div class='alert alert-danger'><strong>Ошибка !</strong> Поле не должно быть пустым !</div>";
            return $msg;
        }

        // Проверка на Email
        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            $msg = "<div class='alert alert-danger'><strong>Ошибка !</strong> Недействительный адрес электронной почты!</div>";
            return $msg;
        }
        if ($chk_email == false) {
            $msg = "<div class='alert alert-danger'><strong>Ошибка !</strong> Адрес электронной почты не существует!</div>";
            return $msg;
        }

        $result = $this->getLoginUser($email, $password);
        if ($result) {
            Session::init();
            Session::set("login", true);
            Session::set("id", $result->id);
            Session::set("name", $result->name);
            Session::set("username", $result->username);
            Session::set("login_msg", "<div class='alert alert-success'><strong>Успешно!</strong> Вы вошли в систему!</div>");
            header("Location: index.php");
        } else {
            $msg = "<div class='alert alert-danger'><strong>Ошибка !</strong> Данные не найдены!</div>";
            return $msg;
        }

    }

    // Метод получения данныйх пользователя
    public function getUserData()
    {
        $sql = "SELECT * FROM tbl_user ORDER BY id DESC";
        $query = $this->db->pdo->prepare($sql);
        $query->execute();
        $result = $query->fetchAll();
        return $result;
    }


    public function getUserById($id)
    {
        $sql = "SELECT * FROM tbl_user WHERE id = :id LIMIT 1";
        $query = $this->db->pdo->prepare($sql);
        $query->bindValue(':id', $id);
        $query->execute();

        $result = $query->fetch(PDO::FETCH_OBJ);
        return $result;
    }


    public function updateUserData($id, $data)
    {
        $name = $data['name'];
        $username = $data['username'];
        $email = $data['email'];

        // Проверка на заполнение всех полей
        if ($name == '' or $username == '' or $email == '') {
            $msg = "<div class='alert alert-danger'><strong>Ошибка !</strong> Поле не должно быть пустым !</div>";
            return $msg;
        }

        $sql = "UPDATE tbl_user set
            name     = :name,
            username = :username,
            email    = :email
            WHERE id = :id";


        $query = $this->db->pdo->prepare($sql);

        $query->bindValue(':name', $name);
        $query->bindValue(':username', $username);
        $query->bindValue(':email', $email);
        $query->bindValue(':id', $id);

        $result = $query->execute();

        if ($result) {
            $msg = "<div class='alert alert-success'><strong>Успешно!</strong> Спасибо, Ваши данные были обновлены!</div>";
            return $msg;
        } else {
            $msg = "<div class='alert alert-danger'><strong>Ошибка!</strong>Ваши данные не обновильсь!</div>"; // Sorry, there has been problem inserting your details
            return $msg;
        }
    }

    private function checkPassword($id, $old_pass)
    {
        $password = md5($old_pass);
        $sql = "SELECT password FROM tbl_user WHERE id = :id AND password = :password";
        $query = $this->db->pdo->prepare($sql);
        $query->bindValue(':id', $id);
        $query->bindValue(':password', $password);
        $query->execute();
        if ($query->rowCount() > 0) {
            return true;
        } else {
            return false;
        }

    }


    public function updatePassword($id, $data)
    {
        $old_pass = $data['old_pass'];
        $new_pass = $data['password'];

        $check_pass = $this->checkPassword($id, $old_pass);

        if ($old_pass == "" or $new_pass == "") {
            $msg = "<div class='alert alert-danger'><strong><strong>Ошибка !</strong> Поле не должно быть пустым !</div>";
            return $msg;
        }
        if ($check_pass == false) {
            $msg = "<div class='alert alert-danger'><strong><strong>Ошибка !</strong>Старый пароль не существует!</div>";
            return $msg;
        }

        if (strlen($new_pass) < 6) {
            $msg = "<div class='alert alert-danger'><strong><strong>Ошибка !</strong>Ваш пароль слишком короткий!</div>";
            return $msg;
        }

        $password = md5($new_pass);
        $sql = "UPDATE tbl_user set password = :password WHERE id = :id";


        $query = $this->db->pdo->prepare($sql);

        $query->bindValue(':password', $password);
        $query->bindValue(':id', $id);

        $result = $query->execute();

        if ($result) {
            $msg = "<div class='alert alert-success'><strong>Успешно!</strong> Спасибо, Ваш пароль был обновлен!</div>";
            return $msg;
        } else {
            $msg = "<div class='alert alert-danger'><strong>Ошибка!</strong>Ваш пароль не обновился!</div>"; // Sorry, there has been problem inserting your details
            return $msg;
        }

    }
}
