$(document).ready(function() {
    loadPage('home');

    $('nav a').on('click', function(e) {
        e.preventDefault();
        const page = $(this).data('page');
        loadPage(page);
    });


    function loadPage(page) {
        const path = 'pages/' + page + '.html';
        $('#content').load(path, function(response, status, xhr) {
            if (status === "error") {
                var msg = "Ошибка: " + xhr.status + " " + xhr.statusText;
                $('#content').html(msg);
            }
        });
    }
});