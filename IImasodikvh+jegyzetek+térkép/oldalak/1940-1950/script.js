var map = L.map('map').setView([20, 0], 2); // Globális nézet, hogy minden csata látható legyen

// OpenStreetMap réteg hozzáadása
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
}).addTo(map);

// Csaták markerei
var polandInvasion = L.marker([52.2297, 21.0122]).bindPopup(
    "<b>Lengyelország inváziója</b><br>" +
    "Dátum: 1939. szeptember 1. – 1939. október 6.<br>" +
    "Harcoló felek: Németország és Szovjetunió vs. Lengyelország<br>" +
    "Fontos infó: A második világháború kitörésének kezdete."
);

var winterWar = L.marker([64.1466, 27.0060]).bindPopup(
    "<b>Téli háború</b><br>" +
    "Dátum: 1939. november 30. – 1940. március 13.<br>" +
    "Harcoló felek: Szovjetunió vs. Finnország<br>" +
    "Fontos infó: Finnország hősies ellenállása a szovjet invázióval szemben."
);

var london = L.marker([51.5074, -0.1278]).bindPopup(
    "<b>Londini légicsata</b><br>" +
    "Dátum: 1940. július 10. – 1940. október 31.<br>" +
    "Harcoló felek: Nagy-Britannia vs. Németország<br>" +
    "Fontos infó: Megakadályozta Nagy-Britannia német megszállását."
);

var franceInvasion = L.marker([48.8566, 2.3522]).bindPopup(
    "<b>Franciaország inváziója</b><br>" +
    "Dátum: 1940. május 10. – 1940. június 25.<br>" +
    "Harcoló felek: Németország vs. Franciaország és Szövetségesek<br>" +
    "Fontos infó: Franciaország gyors bukása, a nyugati front összeomlása."
);

var narvik = L.marker([68.4385, 17.4273]).bindPopup(
    "<b>Narvik-i csata</b><br>" +
    "Dátum: 1940. április 9. – 1940. június 8.<br>" +
    "Harcoló felek: Németország vs. Norvégia és Szövetségesek<br>" +
    "Fontos infó: Stratégiai harc az észak-európai vasércért."
);

var pearlHarbor = L.marker([21.3445, -157.9749]).bindPopup(
    "<b>Pearl Harbor-i támadás</b><br>" +
    "Dátum: 1941. december 7.<br>" +
    "Harcoló felek: Japán vs. Egyesült Államok<br>" +
    "Fontos infó: Az USA belépése a háborúba."
);

var moscow = L.marker([55.7512, 37.6173]).bindPopup(
    "<b>Moszkvai csata</b><br>" +
    "Dátum: 1941. október 2. – 1942. január 7.<br>" +
    "Harcoló felek: Németország vs. Szovjetunió<br>" +
    "Fontos infó: A német előrenyomulás első jelentős megállítása a keleti fronton."
);

var leningrad = L.marker([59.9343, 30.3351]).bindPopup(
    "<b>Leningrádi ostrom</b><br>" +
    "Dátum: 1941. szeptember 8. – 1944. január 27.<br>" +
    "Harcoló felek: Németország vs. Szovjetunió<br>" +
    "Fontos infó: A történelem egyik leghosszabb és legpusztítóbb ostroma."
);

var crete = L.marker([35.5138, 24.0180]).bindPopup(
    "<b>Krétai csata</b><br>" +
    "Dátum: 1941. május 20. – 1941. június 1.<br>" +
    "Harcoló felek: Németország vs. Görögország és Szövetségesek<br>" +
    "Fontos infó: A történelem első nagy ejtőernyős hadművelete."
);

var barbarossa = L.marker([54.5260, 25.2551]).bindPopup(
    "<b>Barbarossa hadművelet</b><br>" +
    "Dátum: 1941. június 22. – 1941. december (folytatólagos)<br>" +
    "Harcoló felek: Németország és szövetségesei vs. Szovjetunió<br>" +
    "Fontos infó: A keleti front megnyitása, a legnagyobb szárazföldi invázió kezdete."
);

var greeceInvasion = L.marker([37.9838, 23.7275]).bindPopup(
    "<b>Görögország inváziója</b><br>" +
    "Dátum: 1941. április 6. – 1941. április 30.<br>" +
    "Harcoló felek: Németország és Olaszország vs. Görögország és Szövetségesek<br>" +
    "Fontos infó: A Balkán elfoglalásának része, Kréta inváziója előtt."
);

var atlanticCharter = L.marker([47.6361, -52.7126]).bindPopup(
    "<b>Atlanti Charta találkozó</b><br>" +
    "Dátum: 1941. augusztus 9. – 1941. augusztus 12.<br>" +
    "Résztvevők: Roosevelt (USA) és Churchill (UK)<br>" +
    "Fontos infó: A szövetséges célok alapjainak lefektetése."
);

var stalingrad = L.marker([48.7080, 44.1271]).bindPopup(
    "<b>Sztálingrádi csata</b><br>" +
    "Dátum: 1942. augusztus 23. – 1943. február 2.<br>" +
    "Harcoló felek: Németország vs. Szovjetunió<br>" +
    "Fontos infó: Fordulópont a keleti fronton."
);

var midway = L.marker([28.2101, -177.3768]).bindPopup(
    "<b>Midway-i csata</b><br>" +
    "Dátum: 1942. június 4. – 1942. június 7.<br>" +
    "Harcoló felek: Egyesült Államok vs. Japán<br>" +
    "Fontos infó: Döntő fordulat a csendes-óceáni hadszíntéren."
);

var elAlamein = L.marker([30.8373, 28.9470]).bindPopup(
    "<b>Második El Alamein-i csata</b><br>" +
    "Dátum: 1942. október 23. – 1942. november 11.<br>" +
    "Harcoló felek: Szövetségesek vs. Németország és Olaszország<br>" +
    "Fontos infó: Megállította a tengelyhatalmak előrenyomulását Észak-Afrikában."
);

var guadalcanal = L.marker([-9.4456, 160.0186]).bindPopup(
    "<b>Guadalkanali csata</b><br>" +
    "Dátum: 1942. augusztus 7. – 1943. február 9.<br>" +
    "Harcoló felek: Egyesült Államok vs. Japán<br>" +
    "Fontos infó: Az első nagy szövetséges offenzíva a csendes-óceáni hadszíntéren."
);

var coralSea = L.marker([-15.0000, 155.0000]).bindPopup(
    "<b>Korall-tengeri csata</b><br>" +
    "Dátum: 1942. május 4. – 1942. május 8.<br>" +
    "Harcoló felek: Egyesült Államok és Ausztrália vs. Japán<br>" +
    "Fontos infó: Az első csata, ahol repülőgép-hordozók közvetlenül csaptak össze."
);

var kursk = L.marker([51.7300, 36.1933]).bindPopup(
    "<b>Kurszki csata</b><br>" +
    "Dátum: 1943. július 5. – 1943. augusztus 23.<br>" +
    "Harcoló felek: Németország vs. Szovjetunió<br>" +
    "Fontos infó: A történelem legnagyobb páncélos ütközete, német offenzíva kudarca."
);

var tarawa = L.marker([1.4278, 172.9764]).bindPopup(
    "<b>Tarawa-i csata</b><br>" +
    "Dátum: 1943. november 20. – 1943. november 23.<br>" +
    "Harcoló felek: Egyesült Államok vs. Japán<br>" +
    "Fontos infó: Véres ütközet, a szövetségesek első nagy szigethódítása a Csendes-óceánon."
);

var tehran = L.marker([35.6892, 51.3890]).bindPopup(
    "<b>Teheráni konferencia</b><br>" +
    "Dátum: 1943. november 28. – 1943. december 1.<br>" +
    "Résztvevők: Sztálin (Szovjetunió), Roosevelt (USA), Churchill (UK)<br>" +
    "Fontos infó: A második front megnyitásának tervezése."
);

var normandia = L.marker([49.1829, -0.3707]).bindPopup(
    "<b>Normandiai partraszállás</b><br>" +
    "Dátum: 1944. június 6.<br>" +
    "Harcoló felek: Szövetségesek vs. Németország<br>" +
    "Fontos infó: Nyugat-Európa felszabadításának kezdete."
);

var monteCassino = L.marker([41.4901, 13.8136]).bindPopup(
    "<b>Monte Cassino-i csata</b><br>" +
    "Dátum: 1944. január 17. – 1944. május 18.<br>" +
    "Harcoló felek: Szövetségesek vs. Németország<br>" +
    "Fontos infó: Az olaszországi hadjárat kulcsfontosságú ütközete."
);

var leyteGulf = L.marker([10.3667, 125.0333]).bindPopup(
    "<b>Leyte-öböl csatája</b><br>" +
    "Dátum: 1944. október 23. – 1944. október 26.<br>" +
    "Harcoló felek: Egyesült Államok és Ausztrália vs. Japán<br>" +
    "Fontos infó: A történelem legnagyobb tengeri csatája, Japán flottájának végső veresége."
);

var berlin = L.marker([52.5200, 13.4050]).bindPopup(
    "<b>Berlin ostroma</b><br>" +
    "Dátum: 1945. április 16. – 1945. május 2.<br>" +
    "Harcoló felek: Szovjetunió vs. Németország<br>" +
    "Fontos infó: A náci Németország bukásának végső csatája."
);

var iwoJima = L.marker([24.7514, 141.3229]).bindPopup(
    "<b>Iwo Jima-i csata</b><br>" +
    "Dátum: 1945. február 19. – 1945. március 26.<br>" +
    "Harcoló felek: Egyesült Államok vs. Japán<br>" +
    "Fontos infó: Ikonikus csata, a híres zászlófelvonás helyszíne."
);

var okinawa = L.marker([26.5013, 127.9455]).bindPopup(
    "<b>Okinawai csata</b><br>" +
    "Dátum: 1945. április 1. – 1945. június 22.<br>" +
    "Harcoló felek: Egyesült Államok vs. Japán<br>" +
    "Fontos infó: A legnagyobb partraszállás a csendes-óceáni hadszíntéren."
);

var yalta = L.marker([44.4978, 34.1663]).bindPopup(
    "<b>Jaltai konferencia</b><br>" +
    "Dátum: 1945. február 4. – 1945. február 11.<br>" +
    "Résztvevők: Sztálin (Szovjetunió), Roosevelt (USA), Churchill (UK)<br>" +
    "Fontos infó: A háború utáni Európa felosztásának tervezése."
);

var potsdam = L.marker([52.3906, 13.0645]).bindPopup(
    "<b>Potsdami konferencia</b><br>" +
    "Dátum: 1945. július 17. – 1945. augusztus 2.<br>" +
    "Résztvevők: Sztálin (Szovjetunió), Truman (USA), Churchill/Attlee (UK)<br>" +
    "Fontos infó: Németország sorsának eldöntése és a japán kapituláció előkészítése."
);

var hiroshima = L.marker([34.3853, 132.4553]).bindPopup(
    "<b>Hirosimai atombomba</b><br>" +
    "Dátum: 1945. augusztus 6.<br>" +
    "Harcoló felek: Egyesült Államok vs. Japán<br>" +
    "Fontos infó: Az első atomfegyver használata háborúban."
);

var nagasaki = L.marker([32.7503, 129.8779]).bindPopup(
    "<b>Nagaszaki atombomba</b><br>" +
    "Dátum: 1945. augusztus 9.<br>" +
    "Harcoló felek: Egyesült Államok vs. Japán<br>" +
    "Fontos infó: A második és utolsó atomfegyver bevetése a háborúban."
);

// Időcsúszka frissítése
function updateMap(year) {
    document.getElementById('year').innerText = year;

    if (year == 1939) {
        polandInvasion.addTo(map);
    } else {
        map.removeLayer(polandInvasion);
    }

    if (year >= 1939 && year <= 1940) {
        winterWar.addTo(map);
    } else {
        map.removeLayer(winterWar);
    }

    if (year == 1940) {
        london.addTo(map);
    } else {
        map.removeLayer(london);
    }

    if (year == 1940) {
        franceInvasion.addTo(map);
    } else {
        map.removeLayer(franceInvasion);
    }

    if (year == 1940) {
        narvik.addTo(map);
    } else {
        map.removeLayer(narvik);
    }

    if (year == 1941) {
        pearlHarbor.addTo(map);
    } else {
        map.removeLayer(pearlHarbor);
    }

    if (year >= 1941 && year <= 1942) {
        moscow.addTo(map);
    } else {
        map.removeLayer(moscow);
    }

    if (year >= 1941 && year <= 1944) {
        leningrad.addTo(map);
    } else {
        map.removeLayer(leningrad);
    }

    if (year == 1941) {
        crete.addTo(map);
    } else {
        map.removeLayer(crete);
    }

    if (year == 1941) {
        barbarossa.addTo(map);
    } else {
        map.removeLayer(barbarossa);
    }

    if (year == 1941) {
        greeceInvasion.addTo(map);
    } else {
        map.removeLayer(greeceInvasion);
    }

    if (year == 1941) {
        atlanticCharter.addTo(map);
    } else {
        map.removeLayer(atlanticCharter);
    }

    if (year >= 1942 && year <= 1943) {
        stalingrad.addTo(map);
    } else {
        map.removeLayer(stalingrad);
    }

    if (year == 1942) {
        midway.addTo(map);
    } else {
        map.removeLayer(midway);
    }

    if (year == 1942) {
        elAlamein.addTo(map);
    } else {
        map.removeLayer(elAlamein);
    }

    if (year >= 1942 && year <= 1943) {
        guadalcanal.addTo(map);
    } else {
        map.removeLayer(guadalcanal);
    }

    if (year == 1942) {
        coralSea.addTo(map);
    } else {
        map.removeLayer(coralSea);
    }

    if (year == 1943) {
        kursk.addTo(map);
    } else {
        map.removeLayer(kursk);
    }

    if (year == 1943) {
        tarawa.addTo(map);
    } else {
        map.removeLayer(tarawa);
    }

    if (year == 1943) {
        tehran.addTo(map);
    } else {
        map.removeLayer(tehran);
    }

    if (year == 1944) {
        normandia.addTo(map);
    } else {
        map.removeLayer(normandia);
    }

    if (year == 1944) {
        monteCassino.addTo(map);
    } else {
        map.removeLayer(monteCassino);
    }

    if (year == 1944) {
        leyteGulf.addTo(map);
    } else {
        map.removeLayer(leyteGulf);
    }

    if (year == 1945) {
        berlin.addTo(map);
    } else {
        map.removeLayer(berlin);
    }

    if (year == 1945) {
        iwoJima.addTo(map);
    } else {
        map.removeLayer(iwoJima);
    }

    if (year == 1945) {
        okinawa.addTo(map);
    } else {
        map.removeLayer(okinawa);
    }

    if (year == 1945) {
        yalta.addTo(map);
    } else {
        map.removeLayer(yalta);
    }

    if (year == 1945) {
        potsdam.addTo(map);
    } else {
        map.removeLayer(potsdam);
    }

    if (year == 1945) {
        hiroshima.addTo(map);
    } else {
        map.removeLayer(hiroshima);
    }

    if (year == 1945) {
        nagasaki.addTo(map);
    } else {
        map.removeLayer(nagasaki);
    }
}

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

updateMap(1939);