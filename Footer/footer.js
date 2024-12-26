document.addEventListener('DOMContentLoaded', function () {
    var currentYear = new Date().getFullYear();
    document.getElementById('currentYear').textContent = currentYear;
});
//automatically updates an HTML element with the current year once the page has fully loaded.