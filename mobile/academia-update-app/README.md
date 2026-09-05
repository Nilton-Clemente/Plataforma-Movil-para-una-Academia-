# Academia Update Cordova

App movil Cordova para consumir el backend Laravel de Academia Update.

## Configuracion

1. Publica el backend Laravel en HTTPS.
2. Cambia `baseApiUrl` en `www/js/app.js` o guardalo en `localStorage`:

   ```js
   localStorage.setItem('baseApiUrl', 'https://tu-dominio.com/api');
   ```

3. Instala dependencias y plataforma Android:

   ```powershell
   npm.cmd install
   cordova.cmd platform add android
   ```

4. Verifica requisitos y compila:

   ```powershell
   cordova.cmd requirements android
   cordova.cmd build android
   ```

El APK debug queda en `platforms/android/app/build/outputs/apk/debug/app-debug.apk`.

## Requisitos Android

Cordova necesita Android SDK completo, `avdmanager` disponible en PATH, JDK compatible y Gradle/Android Studio instalado.
