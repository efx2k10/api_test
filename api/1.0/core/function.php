<?php
function add($book, $data)
{
    if (isset($data['rooms']) && count($data['rooms']) > 0 && $data['user']) {

        $book_id = $book->add($data);

        if (is_array($book_id)) {
            header('HTTP/1.0 200 OK');
            echo json_encode(array(
                'reserved' => $book_id,
                'status' => 'error',
                'text' => 'На  данное время уже зарезервированно'
            ));
            return 0;
        }

        if ($book_id) {
            header('HTTP/1.0 201 Created');
            echo json_encode(array(
                'book_id' => $book_id,
                'status' => 'success',
                'text' => 'Успешное бронирование'
            ));
        }


    } else {
        header('HTTP/1.0 200 OK');
        echo json_encode(array(
            'status' => 'error',
            'text' => 'Нет данных для отправки'
        ));
    }
}

function get($book, $book_id)
{
    $data = $book->get($book_id);

    if ($data) {
        header('HTTP/1.0 200 OK');
        echo json_encode(array(
            'book_id' => $data['book']['id'],
            'name' => $data['book']['name'],
            'user_id' => $data['book']['user_id'],
            'active' => $data['book']['active'],
            'c_date' => $data['book']['c_date'],
            'up_date' => $data['book']['up_date'],
            'rooms' => $data['rooms'],
            'status' => 'success',
            'text' => 'Данные получены'
        ));
    } else {
        header('HTTP/1.0 200 OK');
        echo json_encode(array(
            'status' => 'error',
            'text' => 'Нет данных'
        ));
    }

}

function getList($book, $offset, $limit)
{

    $data = $book->getList($offset, $limit);

    if ($data) {
        header('HTTP/1.0 200 OK');
        echo json_encode(array(
            'books' => $data,
            'count' => $book->getListCount(),
            'status' => 'success',
            'text' => 'Данные получены'
        ));
    } else {
        header('HTTP/1.0 200 OK');
        echo json_encode(array(
            'status' => 'error',
            'text' => 'Нет данных'
        ));
    }
}

function del($book, $book_id, $user_id)
{
    $data = $book->del($book_id, $user_id);
    if ($data) {
        header('HTTP/1.0 200 OK');
        echo json_encode(array(
            'status' => 'success',
            'text' => 'Данные удалены'
        ));
    } else {
        header('HTTP/1.0 200 OK');
        echo json_encode(array(
            'status' => 'error',
            'text' => 'Нет данных для удаления'
        ));
    }

}
