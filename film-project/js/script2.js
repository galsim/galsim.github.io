'use strict'
const searchForm = document.querySelector('#search-form');
const movie = document.querySelector('#movies');

function apiSearch(event) {
    event.preventDefault();
    const searchText = document.querySelector('.form-control').value;
    const server = 'https://api.themoviedb.org/3/search/multi?api_key=7a1832779ca83fdfa3d9e7d2d5402531&language=ru&query=' + searchText;
    movie.innerHTML = 'Загрузка';
    fetch(server)
        .then(function(value) {
            return value.json();
        })
        .then(function(output) {
            let inner = '';
            output.results.forEach(function (item) {
                let nameItem = item.name || item.title,
                    filmPoster = item.poster_path,
                    filmDescription = item.overview;
                inner += '<div class="col-4">' + '<img class="img-fluid" src="https://image.tmdb.org/t/p/w500' + filmPoster + '"/>' + '<p class="h3">' + nameItem + '</p>' + '<p class="text-muted">' + filmDescription + '</p>' + '</div>';
            });
            movie.innerHTML = inner;


        })
        .catch(function(reason) {
            movie.innerHTML = 'Упс, что-то пошло не так!';
            console.log('error: ' + request.status);
        });
};

searchForm.addEventListener('submit', apiSearch);