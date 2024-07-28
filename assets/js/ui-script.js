document.addEventListener('DOMContentLoaded', (event) => {
  const popup = document.querySelector('.popup-menu');
  const li = document.querySelector('li[onclick="navbarMenuToggle()"]');

  // Toggle the popup display
  window.navbarMenuToggle = function() {
    popup.style.display = popup.style.display === 'none' ? 'block' : 'none';
  }

  // Hide the popup when clicking outside
  window.onclick = function(event) {
    if (!li.contains(event.target) && popup.style.display === 'block') {
      popup.style.display = 'none';
    }
  }
});