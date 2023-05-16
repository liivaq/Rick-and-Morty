<?php

return [
    ['GET', '/', ['App\Controllers\CharacterController', 'characters']],
    ['GET', '/search', ['App\Controllers\CharacterController', 'search']],
    ['GET', '/characters[/{page}]', ['App\Controllers\CharacterController', 'characters']],
    ['GET', '/episodes', ['App\Controllers\EpisodeController', 'allEpisodes']],
    ['GET', '/locations[/{page}]', ['App\Controllers\LocationController', 'locations']],
    ['GET', '/character[/{page}]', ['App\Controllers\CharacterController', 'singleCharacter']],
    ['GET', '/episode[/{page}]', ['App\Controllers\EpisodeController', 'singleEpisode']],
    ['GET', '/location[/{page}]', ['App\Controllers\LocationController', 'singleLocation']]
];
