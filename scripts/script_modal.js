const planetImages = {
  Mercurio: [
    "assets/img/Mercurio_hotel1.jpeg",
    "assets/img/Mercurio_hotel2.jpeg"
  ],
  Venus: [
    "assets/img/Venus_hotel1.jpeg",
    "assets/img/Venus_hotel2.jpeg"
  ],
  Tierra: [
    "assets/img/Tierra_hotel1.jpeg",
    "assets/img/Tierra_hotel2.jpeg"
  ],
  Marte: [
    "assets/img/Marte_hotel1.jpeg",
    "assets/img/Marte_hotel2.jpeg"
  ],
  Jupiter: [
    "assets/img/Jupiter_hotel1.jpeg",
    "assets/img/Jupiter_hotel2.jpeg"
  ],
  Saturno: [
    "assets/img/Saturno_hotel1.jpeg",
    "assets/img/Saturno_hotel2.jpeg"
  ]
};

let images = [];
let currentIndex = 0;

function openModal(planetName) {
  images = planetImages[planetName] || [];
  currentIndex = 0;

  if (images.length > 0) {
    document.getElementById("sliderImage").src = images[currentIndex];
    document.getElementById("imageModal").style.display = "block";
  } else {
    alert("No hay imágenes disponibles para " + planetName);
  }
}

function closeModal() {
  document.getElementById("imageModal").style.display = "none";
}

function changeSlide(direction) {
  if (images.length === 0) return;

  currentIndex += direction;
  if (currentIndex < 0) currentIndex = images.length - 1;
  if (currentIndex >= images.length) currentIndex = 0;

  document.getElementById("sliderImage").src = images[currentIndex];
}
