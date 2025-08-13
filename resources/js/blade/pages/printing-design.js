import 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js'
import 'https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js'
import 'https://unpkg.com/imagesloaded@4/imagesloaded.pkgd.min.js'

$(document).ready(function() {
    var $grid = $(".grid-masonry").masonry({
        itemSelector: ".grid-masonry-item",
        columnWidth: ".grid-masonry-item",
        columnWidth: 90,
        gutter: 20,
    });

    $grid.imagesLoaded().progress(function() {
        $grid.masonry("layout");
    });
});
