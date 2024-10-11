// Evento mousedown para el efecto ripple
document.querySelectorAll('html').forEach(item => {
    item.addEventListener('click', function(e) {
        let ripple = document.createElement('span');
        ripple.classList.add('ripple-effect');
        ripple.style.left = `${e.clientX - e.target.offsetLeft}px`;
        ripple.style.top = `${e.clientY - e.target.offsetTop}px`;
        this.appendChild(ripple);
        
        // Eliminar el ripple después de 500ms
        setTimeout(() => {
            ripple.remove();
        }, 500); 
    });
});
