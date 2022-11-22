document.addEventListener('DOMContentLoaded', function () {
    window.onscroll = function (ev) {
        document.getElementById("cBack").className = (window.pageYOffset > 300) ? "cVisible" : "cInvisible";
    };
});