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

  // Contact page: show an error message, and preselect the topic from the link.
  var params = new URLSearchParams(window.location.search);
  var error = document.getElementById('form-error');
  if (error && params.get('error')) {
    error.hidden = false;
  }
  var topic = document.getElementById('topic');
  if (topic && params.get('topic')) {
    topic.value = params.get('topic');
  }
});
