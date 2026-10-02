/**
 * فيك تحدي - Fik Tahadi | Official Landing Page Scripts
 * Interactive Particles, Ambient Animation & UX Interactions
 */

document.addEventListener('DOMContentLoaded', () => {
  initParticleBackground();
  initTiltEffect();
  initNotifyForm();
  initStoreButtons();
  
  const yearElem = document.getElementById('year');
  if (yearElem) {
    yearElem.textContent = new Date().getFullYear();
  }
});

/* Particle Canvas Engine */
function initParticleBackground() {
  const canvas = document.getElementById('particles-canvas');
  if (!canvas) return;

  const ctx = canvas.getContext('2d');
  let width = (canvas.width = window.innerWidth);
  let height = (canvas.height = window.innerHeight);

  const particles = [];
  const particleCount = Math.min(Math.floor(window.innerWidth / 18), 65);

  const colors = [
    'rgba(56, 189, 248, ',  // Sky Blue
    'rgba(255, 152, 0, ',   // Orange
    'rgba(255, 183, 3, ',   // Yellow Gold
    'rgba(99, 102, 241, '   // Indigo
  ];

  class Particle {
    constructor() {
      this.reset(true);
    }

    reset(initial = false) {
      this.x = Math.random() * width;
      this.y = initial ? Math.random() * height : height + 10;
      this.size = Math.random() * 2.8 + 1;
      this.speedY = Math.random() * 0.7 + 0.3;
      this.speedX = (Math.random() - 0.5) * 0.5;
      this.colorBase = colors[Math.floor(Math.random() * colors.length)];
      this.opacity = Math.random() * 0.6 + 0.2;
      this.pulseSpeed = Math.random() * 0.02 + 0.01;
      this.pulseVal = Math.random() * Math.PI;
    }

    update() {
      this.y -= this.speedY;
      this.x += this.speedX;
      this.pulseVal += this.pulseSpeed;

      const currentOpacity = this.opacity * (0.6 + 0.4 * Math.sin(this.pulseVal));

      if (this.y < -10 || this.x < -10 || this.x > width + 10) {
        this.reset(false);
      }

      return currentOpacity;
    }

    draw(opacity) {
      ctx.beginPath();
      ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
      ctx.fillStyle = `${this.colorBase}${opacity})`;
      ctx.shadowBlur = 10;
      ctx.shadowColor = `${this.colorBase}0.8)`;
      ctx.fill();
      ctx.shadowBlur = 0;
    }
  }

  // Create initial particles
  for (let i = 0; i < particleCount; i++) {
    particles.push(new Particle());
  }

  function animate() {
    ctx.clearRect(0, 0, width, height);

    particles.forEach(p => {
      const op = p.update();
      p.draw(op);
    });

    requestAnimationFrame(animate);
  }

  animate();

  window.addEventListener('resize', () => {
    width = canvas.width = window.innerWidth;
    height = canvas.height = window.innerHeight;
  });
}

/* 3D Tilt Effect on interactive cards */
function initTiltEffect() {
  const cards = document.querySelectorAll('.feature-box, .notify-card, .store-btn-card');

  cards.forEach(card => {
    card.addEventListener('mousemove', e => {
      const rect = card.getBoundingClientRect();
      const x = e.clientX - rect.left - rect.width / 2;
      const y = e.clientY - rect.top - rect.height / 2;

      const rotateX = (-y / rect.height) * 10;
      const rotateY = (x / rect.width) * 10;

      card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateY(-4px)`;
    });

    card.addEventListener('mouseleave', () => {
      card.style.transform = '';
    });
  });
}

/* Notify Form Submission Handler */
function initNotifyForm() {
  const form = document.getElementById('notifyForm');
  const input = document.getElementById('notifyEmail');

  if (!form || !input) return;

  form.addEventListener('submit', e => {
    e.preventDefault();
    const email = input.value.trim();

    if (!email || !isValidEmail(email)) {
      showToast('الرجاء إدخال بريد إلكتروني صحيح ✉️', 'warning');
      input.focus();
      return;
    }

    // Save to localStorage or mock trigger
    try {
      const subscribers = JSON.parse(localStorage.getItem('fik_tahadi_subscribers') || '[]');
      if (!subscribers.includes(email)) {
        subscribers.push(email);
        localStorage.setItem('fik_tahadi_subscribers', JSON.stringify(subscribers));
      }
    } catch (err) {
      console.log('Subscribed:', email);
    }

    showToast('رائع! 🎉 تم تسجيل بريدك وسنرسل لك إشعاراً فور الإطلاق!', 'success');
    input.value = '';
  });
}

function isValidEmail(email) {
  return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

/* Store Buttons Feedback */
function initStoreButtons() {
  // Store buttons have direct href links to App Store and Google Play
}

/* Toast Notification Utility */
let toastTimeout;
function showToast(message, type = 'info') {
  let toast = document.getElementById('globalToast');

  if (!toast) {
    toast = document.createElement('div');
    toast.id = 'globalToast';
    toast.className = 'toast-alert';
    document.body.appendChild(toast);
  }

  const icons = {
    success: '🎉',
    warning: '⚠️',
    info: '✨'
  };

  toast.innerHTML = `<span class="toast-icon">${icons[type] || '✨'}</span> <span>${message}</span>`;
  toast.classList.add('show');

  clearTimeout(toastTimeout);
  toastTimeout = setTimeout(() => {
    toast.classList.remove('show');
  }, 4000);
}
