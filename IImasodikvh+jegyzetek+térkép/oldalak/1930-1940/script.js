function toggleJegyzet() {
    const jegyzet = document.getElementById('jegyzet');
    const overlay = document.getElementById('overlay');
    jegyzet.classList.toggle('active');
    
    // Overlay megjelenítése/elrejtése
    if (jegyzet.classList.contains('active')) {
        overlay.style.display = 'block';
    } else {
        overlay.style.display = 'none';
    }
}

function saveNotes() {
    const text = document.getElementById('jegyzetSzoveg').value;
    const blob = new Blob([text], { type: 'text/plain' });
    const a = document.createElement('a');
    a.href = URL.createObjectURL(blob);
    a.download = 'jegyzet.txt';
    a.click();
}

function sendNotes() {
    const text = document.getElementById('jegyzetSzoveg').value;
    // Itt implementálhatod az elküldés logikáját, pl. email vagy szerver API hívás
    alert('Jegyzet elküldve:\n' + text);
}