<?php
/**
 * Class tasks  модель для работы с задачами
 */

class tasks
{
    const SHOW_BY_DEFAULT = 3;

    public static function getTasksList($page = 1, $sort)
    {
        $page = intval($page);
        $offset = ($page - 1) * self::SHOW_BY_DEFAULT;
        $sort = $sort;
        // Соединение с БД
        $db = Db::getConnection();

        // Запрос к БД
        $sql = 'SELECT * FROM zadachi ORDER BY '.$sort.' LIMIT '.self::SHOW_BY_DEFAULT.' OFFSET '.$offset

        ;

       // echo $sql; die;
        $result = $db->prepare($sql);
        // Указываем, что хотим получить данные в виде массива
        $result->setFetchMode(PDO::FETCH_ASSOC);

        // Выполнение коменды
        $result->execute();

        $taskList = array();
        $i = 0;
        while ($row = $result->fetch()) {
            $taskList[$i]['id'] = $row['id'];
            $taskList[$i]['username'] = $row['username'];
            $taskList[$i]['email'] = $row['email'];
            $taskList[$i]['text'] = $row['text'];
            $taskList[$i]['status'] = $row['status'];
            $taskList[$i]['image'] = $row['image'];
            $i++;
        }
        return $taskList;
    }

    public static function addTask($username, $email, $text, $uploaddir)
    {
        // Соединение с БД
        $db = Db::getConnection();

        // Запрос к БД
        $sql = 'INSERT INTO zadachi (username, email, text, image) VALUES (:username, :email, :text, :image)';
        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':username', $username,  PDO::PARAM_STR);
        $result->bindParam(':email', $email,  PDO::PARAM_STR);
        $result->bindParam(':text', $text,  PDO::PARAM_STR);
        $result->bindParam(':image', $uploaddir,  PDO::PARAM_STR);
        return $result->execute();

    }

    public static function updateTask($username, $email, $text, $status, $id)
    {
        // Соединение с БД
        $db = Db::getConnection();

        // Запрос к БД
        $sql = 'UPDATE zadachi SET username=:username, email=:email, text=:text, status=:status WHERE id=:id';
        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id,  PDO::PARAM_INT);
        $result->bindParam(':username', $username,  PDO::PARAM_STR);
        $result->bindParam(':email', $email,  PDO::PARAM_STR);
        $result->bindParam(':text', $text,  PDO::PARAM_STR);
        $result->bindParam(':status', $status,  PDO::PARAM_STR);

        return $result->execute();

    }

    public static function getTaskByID($id)
    {
        // Соединение с БД
        $db = Db::getConnection();

        // Текст запроса к БД
        $sql = 'SELECT * FROM zadachi WHERE id = :id';

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);

        // Указываем, что хотим получить данные в виде массива
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetch();
    }

    public static function getTotalTasks()
    {
        // Соединение с БД
        $db = Db::getConnection();
        $sql = 'SELECT COUNT(id) AS count FROM zadachi';
        $result = $db->prepare($sql);
        $result -> setFetchMode(PDO::FETCH_ASSOC);
        $row = $result -> fetch();
        return $row['count'];
    }

}