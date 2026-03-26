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

    // ---------- MAPA INTERACTIVO COLOMBIA ----------
  (function(){
    const mapa = document.querySelector('.mapa-svg');
    if (!mapa) return;

    const titulo = document.getElementById('mapaTitulo');
    const lugar = document.getElementById('mapaLugar');
    const descripcion = document.getElementById('mapaDescripcion');
    const img = document.getElementById('mapaImg');

    if (!titulo || !lugar || !descripcion || !img) return;

    const departamentos = {
      COAMA: { nombre:"Amazonas", region:"Región Amazónica", desc:"Alta biodiversidad y ecosistemas estratégicos.", img:"/img/logos/Logo.png" },
      COANT: { nombre:"Antioquia", region:"Región Andina", desc:"Centro clave de producción e investigación.", img:"/img/logos/Logo.png" },
      COARA: { nombre:"Arauca", region:"Orinoquía", desc:"Zona agropecuaria y fronteriza.", img:"/img/logos/Logo.png" },
      COATL: { nombre:"Atlántico", region:"Región Caribe", desc:"Desarrollo logístico y académico.", img:"/img/logos/Logo.png" },
      COBOL: { nombre:"Bolívar", region:"Región Caribe", desc:"Territorio histórico y productivo.", img:"/img/logos/Logo.png" },
      COBOY: { nombre:"Boyacá", region:"Región Andina", desc:"Base rural y académica sólida.", img:"/img/logos/Logo.png" },
      COCAL: { nombre:"Caldas", region:"Región Andina", desc:"Innovación agrícola.", img:"/img/logos/Logo.png" },
      COCAQ: { nombre:"Caquetá", region:"Región Amazónica", desc:"Biodiversidad y producción emergente.", img:"/img/logos/Logo.png" },
      COCAS: { nombre:"Casanare", region:"Orinoquía", desc:"Producción agroindustrial.", img:"/img/logos/Logo.png" },
      COCAU: { nombre:"Cauca", region:"Región Pacífica", desc:"Riqueza cultural y agrícola.", img:"/img/logos/Logo.png" },
      COCES: { nombre:"Cesar", region:"Región Caribe", desc:"Desarrollo rural y productivo.", img:"/img/logos/Logo.png" },
      COCHO: { nombre:"Chocó", region:"Región Pacífica", desc:"Alta biodiversidad y comunidades ancestrales.", img:"/img/logos/Logo.png" },
      COCOR: { nombre:"Córdoba", region:"Región Caribe", desc:"Producción agropecuaria.", img:"/img/logos/Logo.png" },
      COCUN: { nombre:"Cundinamarca", region:"Región Andina", desc:"Centro institucional y científico.", img:"/img/logos/Logo.png" },
      CODC:  { nombre:"Bogotá D.C.", region:"Capital", desc:"Centro administrativo y académico.", img:"/img/logos/Logo.png" },
      COGUA: { nombre:"Guainía", region:"Región Amazónica", desc:"Territorio natural y cultural.", img:"/img/logos/Logo.png" },
      COGUV: { nombre:"Guaviare", region:"Región Amazónica", desc:"Zona de transición ecológica.", img:"/img/logos/Logo.png" },
      COHUI: { nombre:"Huila", region:"Región Andina", desc:"Producción agrícola diversificada.", img:"/img/logos/Logo.png" },
      COLAG: { nombre:"La Guajira", region:"Región Caribe", desc:"Saberes ancestrales y clima extremo.", img:"/img/logos/Logo.png" },
      COMAG: { nombre:"Magdalena", region:"Región Caribe", desc:"Zona agrícola y turística.", img:"/img/logos/Logo.png" },
      COMET: { nombre:"Meta", region:"Orinoquía", desc:"Producción ganadera y agrícola.", img:"/img/logos/Logo.png" },
      CONAR: { nombre:"Nariño", region:"Región Pacífica", desc:"Diversidad climática y agrícola.", img:"/img/logos/Logo.png" },
      CONSA: { nombre:"Norte de Santander", region:"Región Andina", desc:"Zona fronteriza productiva.", img:"/img/logos/Logo.png" },
      COPUT: { nombre:"Putumayo", region:"Región Amazónica", desc:"Territorio biodiverso.", img:"/img/logos/Logo.png" },
      COQUI: { nombre:"Quindío", region:"Región Andina", desc:"Innovación agrícola.", img:"/img/logos/Logo.png" },
      CORIS: { nombre:"Risaralda", region:"Región Andina", desc:"Investigación y desarrollo rural.", img:"/img/logos/Logo.png" },
      COSAN: { nombre:"Santander", region:"Región Andina", desc:"Innovación productiva.", img:"/img/logos/Logo.png" },
      COSAP: { nombre:"San Andrés y Providencia", region:"Caribe Insular", desc:"Biodiversidad marina y cultura raizal.", img:"/img/logos/Logo.png" },
      COSUC: { nombre:"Sucre", region:"Región Caribe", desc:"Producción agropecuaria.", img:"/img/logos/Logo.png" },
      COTOL: { nombre:"Tolima", region:"Región Andina", desc:"Investigación agrícola.", img:"/img/logos/Logo.png" },
      COVAC: { nombre:"Valle del Cauca", region:"Región Pacífica", desc:"Centro industrial y agrícola.", img:"/img/logos/Logo.png" },
      COVAU: { nombre:"Vaupés", region:"Región Amazónica", desc:"Territorio indígena.", img:"/img/logos/Logo.png" },
      COVID: { nombre:"Vichada", region:"Orinoquía", desc:"Expansión agrícola sostenible.", img:"/img/logos/Logo.png" }
    };


    mapa.querySelectorAll('path').forEach(path => {
      path.addEventListener('mouseenter', () => {
        const d = departamentos[path.id];
        if (!d) return;

        titulo.textContent = d.nombre;
        lugar.textContent = d.region;
        descripcion.textContent = d.desc;
        img.src = d.img;
      });
    });

  })();


});
