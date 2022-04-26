<?php

Class Book
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
        $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }

    public function add($data)
    {

        $reserved = array();
        foreach ($data['rooms'] as $room) {
            $date_start = $room['dateStart'];
            $date_end = $room['dateEnd'];
            $reserv = $this->isReserved($date_start, $date_end);
            if ($reserv)
                $reserved[] = $reserv;
        }

        if (count($reserved)) {
            return $reserved;
        }


        $user = $data['user'];

        $sth = $this->conn->prepare("INSERT INTO `book` SET `name` = :name,`user_id` = :user_id");
        $sth->execute(array(
            'name' => $user['name'],
            'user_id' => $user['id']
        ));


        $book_id = $this->conn->lastInsertId();

        foreach ($data['rooms'] as $room) {
            $sth = $this->conn->prepare("INSERT INTO `room` SET `book_id` = :book_id, `date_start` = :date_start,  `date_end` = :date_end,  `type` = :type");

            $sth->execute(array(
                'book_id' => $book_id,
                'date_start' => $room['dateStart'],
                'date_end' => $room['dateEnd'],
                'type' => $room['roomType']
            ));
        }
        return $book_id;
    }

    public function get($id)
    {
        $sth = $this->conn->prepare("SELECT * FROM `book` WHERE `id` = :id");
        $sth->execute(array(
            'id' => $id,
        ));
        $book = $sth->fetch(PDO::FETCH_ASSOC);

        $sth = $this->conn->prepare("SELECT r.id, r.date_start, r.date_end, r.type FROM `room` r WHERE `book_id` = :id");
        $sth->execute(array(
            'id' => $id,
        ));
        $rooms = $sth->fetchAll(PDO::FETCH_ASSOC);

        if (count($book)) {
            return array(
                'book' => $book,
                'rooms' => $rooms
            );
        }
        return false;

    }

    public function getList($offset = 0, $limit = 10)
    {
        $sth = $this->conn->prepare("SELECT * FROM `book` LIMIT :limit OFFSET :offset");
        $sth->execute(array(
            'limit' => $limit,
            'offset' => $offset,
        ));
        $books = $sth->fetchAll(PDO::FETCH_ASSOC);


        if (count($books)) {
            return $books;
        }
        return false;
    }

    public function getListCount()
    {
        $sth = $this->conn->prepare("SELECT COUNT(*) qty FROM `book`");
        $sth->execute(array());
        $qty = $sth->fetch(PDO::FETCH_ASSOC);


        if ($qty['qty']) {
            return $qty['qty'];
        }
        return false;
    }

    public function del($id, $user_id)
    {
        $sth = $this->conn->prepare("DELETE FROM `book` WHERE `id` = :id AND `user_id` = :user_id");
        $sth->execute(array(
            'id' => $id,
            'user_id' => $user_id
        ));
        $result = $sth->rowCount();

        if ($result) {
            $sth = $this->conn->prepare("DELETE FROM `room` WHERE `book_id` = :book_id");
            $sth->execute(array(
                'book_id' => $id
            ));
        }

        return $result;
    }

    public function isReserved($date_start, $date_end)
    {
        $sth = $this->conn->prepare("SELECT * FROM `room` r 
                                        LEFT JOIN `book` b ON r.book_id = b.id
                                        WHERE 
                                        b.active = 1 
                                        AND r.date_start>= :date_start 
                                        AND r.date_end<= :date_end");
        $sth->execute(array(
            'date_start' => $date_start,
            'date_end' => $date_end

        ));
        $reserved = $sth->fetchAll(PDO::FETCH_ASSOC);

        if (count($reserved)) {
            return $reserved;
        }
        return false;
    }
}