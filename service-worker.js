self.addEventListener('install', event => {
  event.waitUntil(
      caches.open('pwa-cache-v1').then(cache => {
          return cache.addAll([
              // Archivos raíz
              'index.html',
              'ejercicios.html',
              'detalle_ejer.html',
              'busqueda_ejercicio.html',
              'calculadora1rm.html',
              'contacto.html',
              'crear_ejercicio.html',
              'eliminar_ejercicio.html',
              'login.html',
              'manifest.json',
              'nutricion.html',
              'recuento_cargas.html',
              'registro_entrenamientos.html',
              'rutina_detalle.html',
              'rutina_hoy.html',
              'rutinas.html',
              'seguimiento_ejer.html',
              'service-worker.js',
              'sounds/timesup.mp3',
              
              // CSS
              'css/styles.css',

              // Fuentes
              'fonts/Gilroy-Bold.ttf',
              'fonts/Gilroy-Heavy.ttf',
              'fonts/Gilroy-Light.ttf',
              'fonts/Gilroy-Medium.ttf',
              'fonts/Gilroy-Regular.ttf',

              // Imágenes
              'img/favicon.ico',
              'img/foto.jpg',
              'img/icon1.png',
              'img/icon1_blanco.png',
              'img/icon2.png',
              'img/logo.png',
              'img/screenshot1.png',
              'img/screenshot2.png',
              'img/whatsapp_icon.png',
              
              // JavaScript
              'js/app.js',
              'js/auth.js',

              // PHP (Si necesitas que sean accesibles, pero normalmente no se cachean)
              'php/actualizar_ejercicio.php',
              'php/crear_ejercicio.php',
              'php/eliminar_ejercicio.php',
              'php/login_controller.php',
              'php/obtener_ejercicio.php',
              'php/obtener_ejercicios.php',

              // Videos
              'videos/ejercicios/EXTENSION_DE_TRICEPS_CON_SOGA_EN_POLEA.webm',
              'videos/ejercicios/curl_femoral_acostado.webm',
              'videos/ejercicios/jalon_polea_prono.webm',
              'videos/ejercicios/jalon_polea_supino.webm',
              'videos/ejercicios/leg-extension.webm',
              'videos/ejercicios/peck_deck.webm',
              'videos/ejercicios/sentadilla-bulgara.webm'
          ]);
      })
  );
});

self.addEventListener('fetch', event => {
  event.respondWith(
      caches.match(event.request).then(response => {
          return response || fetch(event.request);
      })
  );
});


self.addEventListener('push', function(event) {
    const options = {
        body: event.data ? event.data.text() : '¡Tienes una nueva notificación!',
        icon: 'img/favicon.ico', // Cambia esto por la ruta de tu ícono
        badge: 'img/favicon.ico' // Opcional: ícono que aparece en la notificación
    };

    event.waitUntil(
        self.registration.showNotification('FITABAPP', options)
    );
});

