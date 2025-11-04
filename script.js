document.addEventListener('DOMContentLoaded', function() {
    const bouton = document.getElementById('clickMe');
    if (bouton) {
        bouton.addEventListener('click', function() {
            alert('Merci d\'avoir cliqué !');
        });
    }
});