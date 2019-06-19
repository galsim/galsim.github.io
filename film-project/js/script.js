const searchForm = document.querySelector('#search-form');
const movie = document.querySelector('#movies');

function apiSearch(event) {
    event.preventDefault();

    const searchText = document.querySelector('.form-control').value;
    const server = 'https://api.themoviedb.org/3/search/multi?api_key=7a1832779ca83fdfa3d9e7d2d5402531&language=ru&query=' + searchText;

    requestApi(server);

};

searchForm.addEventListener('submit', apiSearch);

function requestApi(url) {
    const request = new XMLHttpRequest();
    request.open('GET', url);
    request.send();
    request.addEventListener('readystatechange', function() {
        if (request.readyState !== 4) {
            return;
        }

        if (request.status !== 200) {
            console.log('error: ' + request.status);
            return;
        };

        const output = JSON.parse(request.responseText);
        console.log(output.results);
        let inner = '';

        output.results.forEach(function(item) {
            let nameItem = item.name || item.title,
            filmPoster = item.poster_path,
            filmDescription = item.overview;
            inner += '<div class="col-4">' + '<img class="img-fluid" src="https://image.tmdb.org/t/p/w500' + filmPoster + '"/>' + '<p class="h3">' + nameItem + '</p>' + '<p class="text-muted">' + filmDescription + '</p>' + '</div>';
        });

        movie.innerHTML = inner;
    });

}
