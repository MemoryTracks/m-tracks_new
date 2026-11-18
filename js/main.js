// Memory Tracks site: small helpers shared by every page.

document.addEventListener('DOMContentLoaded', function () {
  // Mobile menu: open and close the navigation on small screens.
  var toggle = document.querySelector('.nav-toggle');
  var nav = document.getElementById('site-nav');
  if (toggle && nav) {
    toggle.addEventListener('click', function () {
      var open = nav.classList.toggle('is-open');
      toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
  }

  // Keep the footer year current.
  var year = document.getElementById('year');
  if (year) {
    year.textContent = new Date().getFullYear();
  }
});
