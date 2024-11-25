// to get current year
function getYear() {
    var currentDate = new Date();
    var currentYear = currentDate.getFullYear();
    //document.querySelector("#displayYear").innerHTML = currentYear;
}

getYear();

//service section owl carousel
$(".product_owl-carousel").owlCarousel({
    autoplay: true,
    loop: true,
    margin: 20,
    autoHeight: true,
    nav: true,
    responsive: {
        0: {
            items: 1
        },
        576: {
            items: 2
        },
        768: {
            items: 2
        },
        991: {
            items: 3
        },
    },

});

//  owl carousel script
$(".client_owl-carousel").owlCarousel({
    loop: true,
    margin: 20,
    nav: true,
    autoplay: true,
    navText: ['<i class="fa fa-long-arrow-left" aria-hidden="true"></i>', '<i class="fa fa-long-arrow-right" aria-hidden="true"></i>'],
    autoplayHoverPause: true,
    responsive: {
        0: {
            items: 1
        },
        1000: {
            items: 2
        }
    }
});

//    end owl carousel script 


/** google_map js **/

function myMap() {
    var mapProp = {
        center: new google.maps.LatLng(-0.044600, -51.159471),
        zoom: 18,
    };
    var map = new google.maps.Map(document.getElementById("googleMap"), mapProp);
}