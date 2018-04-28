<?php

/**
 * Контроллер UserController
 */
class UserController
{

    /**
     * Action для страницы "Вход на сайт"
     */
    public function actionLogin()
    {
        // Переменные для формы
        $username = false;
        $password = false;
        
        // Обработка формы
        if (isset($_POST['submit'])) {
            // Если форма отправлена 
            // Получаем данные из формы
            $username = $_POST['username'];
            $password = $_POST['password'];

            // Флаг ошибок
            $errors = false;


            // Проверяем существует ли пользователь
            $userId = User::checkUserData($username, $password);

            if ($userId == false) {
                // Если данные неправильные - показываем ошибку
                $errors[] = 'Неправильные данные для входа на сайт';
            } else {
                // Если данные правильные, запоминаем пользователя (сессия)
                User::auth($userId);

                // Перенаправляем пользователя в закрытую часть - кабинет 
                header("Location: /cabinet");
            }
        }

        // Подключаем вид
        require_once(ROOT . '/views/user/login.php');
        return true;
    }

    /**
     * Удаляем данные о пользователе из сессии
     */
    public function actionLogout()
    {
        // Стартуем сессию
        //session_start();
        
        // Удаляем информацию о пользователе из сессии
        unset($_SESSION["username"]);
        
        // Перенаправляем пользователя на главную страницу
        header("Location: /");
    }

}
