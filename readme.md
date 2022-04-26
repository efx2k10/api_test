Запись данных в базу POST
```json
{
    "rooms": [
        {
            "roomType": 0,
            "dateStart": "2022-06-25T00:00:00.000Z",
            "dateEnd": "2022-06-25T01:00:00.000Z"
        },
        {
            "roomType": 1,
            "dateStart": "2022-06-25T00:00:00.000Z",
            "dateEnd": "2022-06-25T01:00:00.000Z"
        }
    ],
    "user": {
        "id": 55,
        "name": "Test user"
    }
}
```
Получить одну запись по id GET
```json
{
"book_id":1
}
```
Получить записи с пагинацией GET
```json
{
"offset":0,
"limit":2
}
```
Удалить запись DELETE
```json
{
"user_id":55,
"book_id":2
}
```