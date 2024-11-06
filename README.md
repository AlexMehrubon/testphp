
Установка
1. Скопировать себе репозиторий
2. Прописать команду в корне проекта: docker compose up
3. В новом окне терминала прописать: docker compose exec php bash после чего прописать: composer install 
4. Подключиться к текущей бд через phpMyAdmin или PhpStorm с такими данными:
```php
PORT: 33064
DATABASE: album_db
USER: album
PASSWORD: pass
```
![image](https://github.com/user-attachments/assets/1d2dcf57-9eff-44f4-84ef-ac18ba8699ba)

5. После чего выполните SQL запросы:
```sql
create table users
(
    id       int auto_increment
        primary key,
    name     varchar(26) not null,
    password varchar(26) not null
);

create table albums
(
    id          int auto_increment
        primary key,
    user_id     int          not null,
    title       varchar(256) not null,
    description varchar(256) not null,
    constraint user_id
        foreign key (user_id) references users (id)
            on update cascade on delete cascade
);

create table comments
(
    id           int auto_increment
        primary key,
    text         text     not null,
    user_id      int      not null,
    created_time datetime not null,
    constraint fk_user_id
        foreign key (user_id) references users (id)
);

create table images
(
    id          int auto_increment
        primary key,
    title       varchar(256) not null,
    description varchar(256) not null,
    file_path   text         not null,
    album_id    int          not null,
    constraint images_albums_id_fk
        foreign key (album_id) references albums (id)
            on delete cascade
);

create table photos
(
    id         int auto_increment
        primary key,
    photo_path text not null,
    album_id   int  null,
    constraint album_id
        foreign key (album_id) references albums (id)
);
```
![image](https://github.com/user-attachments/assets/e9f24420-2b68-462e-954d-a0957683f672)


FrontEnd работает по адресу http://localhost:8001
</br>
BackEnd работает по адресу http://localhost:8000
