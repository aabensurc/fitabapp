self.addEventListener('install', event => {
    event.waitUntil(
      caches.open('pwa-cache-v1').then(cache => {
        return cache.addAll([
          'index.html',
          'ejercicios.html',
          'detalle_ejer.html',
          'css/styles.css',
          'js/app.js',
          'json/data.json',
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
  
