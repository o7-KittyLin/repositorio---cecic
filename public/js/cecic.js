// CARUSEL + EFECTOS
document.addEventListener("DOMContentLoaded", () => {
  // =======================
  // LUGARES
  // =======================
  const slidesLugares = document.querySelectorAll(".slide");
  const prevBtnLugares = document.querySelector(".prev");
  const nextBtnLugares = document.querySelector(".next");
  const containerLugares = document.querySelector(".imagenes-lugar");

  let indexLugares = 0;

  function updateSlideLugares() {
    if (containerLugares) {
      containerLugares.style.transform = `translateX(-${indexLugares * 100}%)`;
    }
  }

  if (slidesLugares.length > 0 && prevBtnLugares && nextBtnLugares && containerLugares) {
    prevBtnLugares.addEventListener("click", () => {
      indexLugares = (indexLugares - 1 + slidesLugares.length) % slidesLugares.length;
      updateSlideLugares();
    });

    nextBtnLugares.addEventListener("click", () => {
      indexLugares = (indexLugares + 1) % slidesLugares.length;
      updateSlideLugares();
    });

    updateSlideLugares();
  }

  // =======================
  // ALIADOS
  // =======================
  const slidesAliados = document.querySelectorAll(".slide-aliado");
  const prevBtnAliados = document.querySelector(".prev-aliado");
  const nextBtnAliados = document.querySelector(".next-aliado");
  const containerAliados = document.querySelector(".imagenes-aliados");

  let indexAliados = 0;

  function updateSlideAliados() {
    if (containerAliados) {
      containerAliados.style.transform = `translateX(-${indexAliados * 100}%)`;
    }
  }

  if (slidesAliados.length > 0 && prevBtnAliados && nextBtnAliados && containerAliados) {
    prevBtnAliados.addEventListener("click", () => {
      indexAliados = (indexAliados - 1 + slidesAliados.length) % slidesAliados.length;
      updateSlideAliados();
    });

    nextBtnAliados.addEventListener("click", () => {
      indexAliados = (indexAliados + 1) % slidesAliados.length;
      updateSlideAliados();
    });

    updateSlideAliados();
  }

  // =======================
  // EJEMPLO FETCH (PokeAPI)
  // =======================
  const idtosearch = 1;
  fetch(`https://pokeapi.co/api/v2/egg-group/${idtosearch}/`)
    .then(response => {
      if (!response.ok) {
        throw new Error('Error en la respuesta de la API');
      }
      return response.json();
    })
    .then(data => {
      console.log("Datos de la API (ejemplo):");
      console.log(data);
    })
    .catch(error => {
      console.error('Hubo un problema con la petición fetch:', error);
    });

  // =======================
  // POLÍTICAS - efecto 3D Tilt
  // =======================
  const politicas = document.querySelectorAll('.politica-box');

  politicas.forEach(card => {
    card.addEventListener('mousemove', (e) => {
      const rect = card.getBoundingClientRect();
      const x = e.clientX - rect.left;
      const y = e.clientY - rect.top;
      const centerX = rect.width / 2;
      const centerY = rect.height / 2;
      const rotateX = ((y - centerY) / centerY) * 10;
      const rotateY = ((x - centerX) / centerX) * 10;

      card.style.transform = `rotateX(${-rotateX}deg) rotateY(${rotateY}deg)`;
    });

    card.addEventListener('mouseleave', () => {
      card.style.transform = 'rotateX(0) rotateY(0)';
    });
  });

  // =======================
  // EJEMPLO tipo POST (protegido)
  // =======================
  const uploadForm = document.getElementById('upload-form');
  const fileInput = document.getElementById('file-input');

  if (uploadForm && fileInput) {
    uploadForm.addEventListener('submit', function (event) {
      event.preventDefault();
      const file = fileInput.files[0];

      if (!file) {
        alert("Por favor selecciona un archivo para subir.");
        return;
      }

      const formData = new FormData();
      formData.append('file', file);
      formData.append('descripcion', 'Archivo de prueba');

      fetch('https://api.miservidor.com/subir-archivo', {
        method: 'POST',
        body: formData,
      })
        .then(response => response.json())
        .then(data => {
          console.log('Archivo subido exitosamente:', data);
          alert('Archivo subido correctamente.');
        })
        .catch(error => {
          console.error('Error al subir el archivo:', error);
          alert('Hubo un error al subir el archivo.');
        });
    });
  }

  // =======================
  // SCROLL SUAVE -> "LUGARES"
  // =======================
  const enlaceLugares = document.querySelector('a[href="#lugares"]');
  const seccionLugares = document.querySelector('#lugares');

  if (enlaceLugares && seccionLugares) {
    enlaceLugares.addEventListener('click', function (e) {
      e.preventDefault();
      const offset = -20;
      const targetPosition = seccionLugares.getBoundingClientRect().top + window.pageYOffset + offset;

      window.scrollTo({
        top: targetPosition,
        behavior: 'smooth'
      });
    });
  }
});
