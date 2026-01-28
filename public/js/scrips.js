document.addEventListener("DOMContentLoaded", function() {
    const track = document.getElementById('carousel-track');
    const slides = Array.from(track.children);
    let index = 0;

    function updateCarousel() {
        // Quitamos la clase active de todas las fotos
        slides.forEach(slide => slide.classList.remove('active'));

        // Añadimos active a la foto actual
        slides[index].classList.add('active');

        // Calculamos cuánto desplazar para que la 'active' esté en el centro
        // El cálculo considera el ancho de la slide y el contenedor
        const containerWidth = document.querySelector('.carousel-container').offsetWidth;
        const slideWidth = slides[index].offsetWidth;
        
        // Posición necesaria para centrar la imagen actual
        const centerOffset = (containerWidth / 2) - (slideWidth / 2);
        const position = -index * (slideWidth + 20) + centerOffset;

        track.style.transform = `translateX(${position}px)`;
    }

    function nextSlide() {
        index++;
        if (index >= slides.length) {
            index = 0;
        }
        updateCarousel();
    }

    // Ejecutar cada 5 segundos
    setInterval(nextSlide, 10000);

    // Ejecutar al cargar para posicionar la primera
    window.addEventListener('resize', updateCarousel); // Ajusta si cambian el tamaño de ventana
    updateCarousel();
});