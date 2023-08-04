# Danh sách các API cần làm

## API phục vụ Rocket Bot

### Trả về danh sách món ăn

Để người dùng chọn trong ngày hoặc/và đặt mặc định

**Request: GET {{url}}/v1/list-menu?user=namng**

- user: ID người dùng trong hệ thống Rocket

**Expected Response:**

```javascript
{
    "status": "success",
    "data": [
        {
            "id": 4,
            "title": "Cơm Chay",
            "description": "Cho ít nước thịt",
            "status": 1,
            "datecreate": "2022-06-21 17:22:28",
            "datemodified": "2022-06-21 17:22:28",
            "isdelete": 0
        },
        {
            "id": 3,
            "title": "Bun Nem nướng",
            "description": "Nước nhiều, thịt nhiều, không hành....",
            "status": 1,
            "datecreate": "2022-06-21 17:19:52",
            "datemodified": "2022-06-28 10:50:24",
            "isdelete": 0
        },
        {
            "id": 2,
            "title": "món3",
            "description": "Nhiều bún, không hành",
            "status": 1,
            "datecreate": "2022-06-21 14:40:33",
            "datemodified": "2022-06-28 14:06:44",
            "isdelete": 0
        },
        {
            "id": 1,
            "title": "Cơm bò",
            "description": "2 món mặn, 2 món rau",
            "status": 1,
            "datecreate": "2022-06-21 14:40:01",
            "datemodified": "2022-06-28 09:41:25",
            "isdelete": 0
        }
    ],
    "total": 4,
    "time": "2022-06-28 14:44:14",
    "user": {
        "fullname": null,
        "email": "namng@hivetech.vn",
        "mobile": null,
        "islunch": true,//Cấu hình có ăn hay không
        "default": {
            "id": 3,
            "title": "Bun Nem nướng",
            "description": "Nước nhiều, thịt nhiều, không hành....",
            "status": 1,
            "datecreate": "2022-06-21 17:19:52",
            "datemodified": "2022-06-28 10:50:24",
            "isdelete": 0
        },
        "today": {
            "id": 1021,
            "username": "namng",
            "date": "2022-06-28",
            "menu": "Bun Nem nướng",
            "price": 30000,
            "store_id": 2,
            "menu_id": 3,
            "office_id": 1,
            "booked": 1,
            "ate": 1,
            "datecreate": "2022-06-28 07:39:58",
            "datemodified": "2022-06-28 14:39:58",
            "isdelete": 0
        }
    }
}
```

- id: ID của món ăn trong hệ thống
- name: Tên món ăn
- default: true/false Thể hiện người dùng có đặt món này làm mặc định hay không
- today: true/false Thể hiện món này sẽ được phục vụ ngày hôm nay hay không (Có thể thông qua việc họ đặt mặc định hoặc chọn món trong ngày)

### Đăng ký hoặc Hủy cấu hình ăn trưa hàng ngày (mặc định)

Giúp người dùng đăng ký với hệ thống là sẽ không ăn trưa hoặc có ăn trưa

**POST {{url}}v1/lunch-registry**

- user: ID người dùng trong hệ thống Rocket

Body

```js
{
    "user":"namng",
    "menu_id":2,//Optional
    "select":"on"
}
```
- user: ID người dùng trong hệ thống Rocket
- menu_id: ID món ăn được chọn
- select: on, off (Bật tắt đăng ký ăn trưa hàng ngày)

**Success response format**

Example

```js
{
    "status": "success",
    "action": "choice",
    "data": {
        "id": 3,
        "title": "cơm",
        "description": "cơm"
    }
}
```

### Hủy ăn trưa ngày hôm nay

Giúp người dùng thông báo sẽ không ăn trưa cho ngày hiện tại

**POST {{url}}v1/lunch-today**



Body

```js
{
    "user":"namng"
}
```
- user: ID người dùng trong hệ thống Rocket

**Success response format**

Example

```js
{
    "status": "success",
    "action": "destroy"
}
```

### Chọn món ăn cho hôm nay

**POST {{url}}v1/lunch-today**



Body

```js
{
    "user":"namng",
    "menu_id":2,
    "default":true//Optional
}
```
- user: ID người dùng trong hệ thống Rocket
- menu_id: ID món ăn được chọn
- default: đưa thành món mặc định của người dùng hàng ngày

**Success response format**

Example

```js
{
    "status": "success",
    "action": "choice",
    "data": {
        "id": 3,
        "title": "cơm",
        "description": "cơm"
    }
}
```
**Error response format**

Example

```js
{
    "success":"error",
    "message": "Expired time for order and edit lunch"
}
```

### Lấy bảng tổng hợp món ăn đã đặt trong ngày

API này trả về bảng tổng hợp các món ăn được các thành viên chọn (bao gồm cả mặc định) cho bên Back-Office biết từ đối thông báo cho các nhà cung cấp.

**GET {{url}}v1/report-today**

Response

```javascript
{
    "status": "success",
    "total": 2,
    "time": "2022-07-06 16:01:32",
      "thisday": [
        {
            "total": 124,
            "price": "3812001"
        }
    ],
    "thisweek": [
        {
            "total": 225,
            "price": "7131801"
        }
    ],
}
```
