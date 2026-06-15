document.addEventListener('DOMContentLoaded', (event) => {
  const popup = document.querySelector('.popup-menu');
  const li = document.querySelector('li[onclick="navbarMenuToggle()"]');

  // Toggle the popup display
  window.navbarMenuToggle = function() {
    if (popup) {
      popup.style.display = popup.style.display === 'none' ? 'block' : 'none';
    }
  }

  // Hide the popup when clicking outside
  window.onclick = function(event) {
    if (li && popup && !li.contains(event.target) && popup.style.display === 'block') {
      popup.style.display = 'none';
    }
  }

  // Password toggle
  document.querySelectorAll('[id^="togglePassword"]').forEach(btn => {
    btn.addEventListener('click', function() {
        const input = this.closest('.input-group').querySelector('input');
        const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
        input.setAttribute('type', type);
        this.querySelector('i').classList.toggle('bi-eye');
        this.querySelector('i').classList.toggle('bi-eye-slash');
    });
  });
});