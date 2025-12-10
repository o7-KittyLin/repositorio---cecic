// CARUSEL + EFECTOS
document.addEventListener('DOMContentLoaded', () => {

  // ---------- DROPDOWN NAVBAR ----------
  const dropdowns = document.querySelectorAll('.dropdown');
  dropdowns.forEach(drop => {
    const toggle = drop.querySelector('.dropdown-toggle-custom');
    if (!toggle) return;

    toggle.addEventListener('click', (e) => {
      e.preventDefault();
      e.stopPropagation();
      // cerrar otros
      dropdowns.forEach(d => { if (d !== drop) d.classList.remove('open'); });
      drop.classList.toggle('open');
    });
  });

  document.addEventListener('click', () => dropdowns.forEach(d => d.classList.remove('open')));

  // ---------- SCROLL SUAVE UNIVERSAL ----------
  document.querySelectorAll('a[href^="#"]').forEach(a => {
    a.addEventListener('click', function(e){
      const href = this.getAttribute('href');
      if (!href || href === '#' || href.startsWith('javascript')) return;
      const target = document.querySelector(href);
      if (!target) return;
      e.preventDefault();
      const offset = -20;
      const top = target.getBoundingClientRect().top + window.pageYOffset + offset;
      window.scrollTo({ top, behavior: 'smooth' });
    });
  });

  // ---------- CARRUSEL LUGARES ----------
  (function(){
    const slides = document.querySelectorAll('.slide');
    const prev = document.querySelector('.prev');
    const next = document.querySelector('.next');
    const container = document.querySelector('.imagenes-lugar');
    if (!container || slides.length === 0 || !prev || !next) return;

    let idx = 0;
    function update(){ container.style.transform = `translateX(-${idx * 100}%)`; }
    prev.addEventListener('click', () => { idx = (idx - 1 + slides.length) % slides.length; update(); });
    next.addEventListener('click', () => { idx = (idx + 1) % slides.length; update(); });
    update();
  })();

  // ---------- CARRUSEL ALIADOS ----------
  (function(){
    const slides = document.querySelectorAll('.slide-aliado');
    const prev = document.querySelector('.prev-aliado');
    const next = document.querySelector('.next-aliado');
    const container = document.querySelector('.imagenes-aliados');
    if (!container || slides.length === 0 || !prev || !next) return;

    let idx = 0;
    function update(){ container.style.transform = `translateX(-${idx * 100}%)`; }
    prev.addEventListener('click', () => { idx = (idx - 1 + slides.length) % slides.length; update(); });
    next.addEventListener('click', () => { idx = (idx + 1) % slides.length; update(); });
    update();
  })();

  // ---------- POLÍTICAS: tilt 3D ----------
  (function(){
    const cards = document.querySelectorAll('.politica-box');
    cards.forEach(card => {
      card.addEventListener('mousemove', e => {
        const r = card.getBoundingClientRect();
        const x = e.clientX - r.left;
        const y = e.clientY - r.top;
        const rx = ((y - r.height/2) / r.height) * 10;
        const ry = ((x - r.width/2) / r.width) * 10;
        card.style.transform = `rotateX(${-rx}deg) rotateY(${ry}deg)`;
      });
      card.addEventListener('mouseleave', () => { card.style.transform = 'rotateX(0) rotateY(0)'; });
    });
  })();

  // ---------- FORM POST (archivo) ----------
  // No hay formulario #upload-form en welcome.blade.php; dejo el ejemplo comentado.
  /*
  (function(){
    const uploadForm = document.getElementById('upload-form');
    const fileInput = document.getElementById('file-input');
    if (!uploadForm || !fileInput) return;
    uploadForm.addEventListener('submit', function(e){
      e.preventDefault();
      const file = fileInput.files[0];
      if (!file) return alert('Por favor selecciona un archivo para subir.');
      const formData = new FormData();
      formData.append('file', file);
      formData.append('descripcion', 'Archivo de prueba');
      fetch('https://api.miservidor.com/subir-archivo', { method: 'POST', body: formData })
        .then(r => r.json()).then(d => alert('Archivo subido correctamente.')).catch(err => alert('Hubo un error al subir el archivo.'));
    });
  })();
  */

  // ---------- Small safety: ensure cacao-float elements get visible (in case CSS animation had delays) ----------
  (function(){
    const floats = document.querySelectorAll('.cacao-float');
    floats.forEach(el => {
      // ensure background-image exists (inline style set in blade)
      const bg = el.style.backgroundImage;
      if (!bg || bg === 'none') {
        // fallback to default relative path (if needed)
        el.style.backgroundImage = "url('/img/cacao-blanco.png')";
      }
      // Force opacity to 1 when animation starts (some browsers respect keyframes but keep initial opacity)
      el.addEventListener('animationiteration', () => { el.style.opacity = ''; });
      // set initial opacity to allow animation fade-in
      setTimeout(() => { el.style.opacity = ''; }, 50);
    });
  })();

});
