<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../core/database.php';
include_once '../core/book.php';
include_once '../core/function.php';

$database = new Database();
$db = $database->getConnection();
$book = new Book($db);

$method = $_SERVER['REQUEST_METHOD'];

$data = json_decode(file_get_contents('php://input'), true);

if ($method == 'POST') {
    add($book, $data);
}
elseif ($method == 'GET') {


    if (isset($data['book_id'])) {
        $book_id = (int)$data['book_id'];
        get($book, $book_id);

    } elseif (isset($data['offset']) && isset($data['limit'])) {
        $offset = (int)$data['offset'];
        $limit = (int)$data['limit'];
        getList($book, $offset, $limit);

    } else {
        header('HTTP/1.0 200 OK');
        echo json_encode(array(
            'status' => 'error',
            'text' => 'Нет данных'
        ));
    }


}
elseif ($method == 'DELETE') {
    if (isset($data['book_id']) && isset($data['user_id'])) {
        $book_id = (int)$data['book_id'];
        $user_id = (int)$data['user_id'];
        del($book, $book_id, $user_id);
    }
}
else {
    header('HTTP/1.0 405 Method Not Allowed');
    echo json_encode(array(
        'status' => 'error',
        'text' => 'Неправильный метод'
    ));
}

