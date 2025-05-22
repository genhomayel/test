document.addEventListener('DOMContentLoaded', function() {
    // Curseur personnalisé (bureau uniquement)
    if (window.innerWidth > 768) {
        const cursor = document.querySelector('.custom-cursor');
        
        document.addEventListener('mousemove', (e) => {
            cursor.style.left = e.clientX + 'px';
            cursor.style.top = e.clientY + 'px';
        });
        
        document.addEventListener('mousedown', () => {
            cursor.style.transform = 'translate(-50%, -50%) scale(0.7)';
        });
        
        document.addEventListener('mouseup', () => {
            cursor.style.transform = 'translate(-50%, -50%) scale(1)';
        });
        
        document.querySelectorAll('a, button, .card').forEach(item => {
            item.addEventListener('mouseenter', () => {
                cursor.style.transform = 'translate(-50%, -50%) scale(1.5)';
                cursor.style.mixBlendMode = 'difference';
            });
            
            item.addEventListener('mouseleave', () => {
                cursor.style.transform = 'translate(-50%, -50%) scale(1)';
                cursor.style.mixBlendMode = 'difference';
            });
        });
    }
});
