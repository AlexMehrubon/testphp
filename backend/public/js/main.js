$(document).ready(function() {
    // Функция для загрузки страницы
    function loadPage(url) {
        $.ajax({
            url: url,
            method: 'GET',
            success: function(response) {
                $('#page-content').html(response);
            },
            error: function(error) {
                console.error('Error loading page:', error);
            }
        });
    }

    // Функция для получения списка пользователей
    function fetchUsers() {
        $.ajax({
            url: '/api/users',
            method: 'GET',
            success: function(response) {
                displayUsers(response.users);
            },
            error: function(error) {
                console.error('Error fetching users:', error);
            }
        });
    }

    // Функция для отображения пользователей
    function displayUsers(users) {
        var $usersList = $('#users-list');
        $usersList.empty();

        users.forEach(function(user) {
            $usersList.append('<li>' + user + '</li>');
        });
    }

    // Обработка кликов по ссылкам
    $(document).on('click', 'a', function(event) {
        event.preventDefault();
        var url = $(this).attr('href');
        if (url.startsWith('/api')) {
            fetchUsers();
        } else {
            loadPage(url);
        }
    });

    // Загрузка домашней страницы по умолчанию
    loadPage('/pages/home.html');
});