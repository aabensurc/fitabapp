self.addEventListener('install', event => {
  event.waitUntil(
      caches.open('pwa-cache-v1').then(cache => {
          return cache.addAll([
              'index.html',
              'ejercicios.html',
              'detalle_ejer.html',
              'busqueda_ejercicio.html',
              'css/styles.css',
              'js/app.js',
              'manifest.json',
              'sounds/timesup.mp3',
              'fonts/Gilroy-Bold.ttf',
              'fonts/Gilroy-Heavy.ttf',  
              'fonts/Gilroy-Light.ttf',  
              'fonts/Gilroy-Medium.ttf',  
              'fonts/Gilroy-Regular.ttf',    
              'img/logo.png', 
              'img/icon1.png',      // Icono para el manifest
              'img/icon2.png',      // Otro icono
              'img/screenshot1.png', // Captura de pantalla
              'img/screenshot2.png'  // Otra captura de pantalla
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

// Evento de activación para limpiar cachés antiguos
self.addEventListener('activate', event => {
  const cacheWhitelist = ['pwa-cache-v1']; // Ajusta el nombre de la nueva versión de caché

  event.waitUntil(
      caches.keys().then(cacheNames => {
          return Promise.all(
              cacheNames.map(cacheName => {
                  if (!cacheWhitelist.includes(cacheName)) {
                      return caches.delete(cacheName);
                  }
              })
          );
      })
  );
});
