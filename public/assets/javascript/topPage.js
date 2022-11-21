document.addEventListener('DOMContentLoaded', function () {
    window.onscroll = function (ev) {
        document.getElementById("cRetour").className = (window.pageYOffset > 300) ? "cVisible" : "cInvisible";
    };
});