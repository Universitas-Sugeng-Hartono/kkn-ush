<script type="module">
  // Import the functions you need from the SDKs you need
  import { initializeApp } from "https://www.gstatic.com/firebasejs/11.10.0/firebase-app.js";
  import { getAnalytics } from "https://www.gstatic.com/firebasejs/11.10.0/firebase-analytics.js";
  // TODO: Add SDKs for Firebase products that you want to use
  // https://firebase.google.com/docs/web/setup#available-libraries

  // Your web app's Firebase configuration
  // For Firebase JS SDK v7.20.0 and later, measurementId is optional
  const firebaseConfig = {
    apiKey: "AIzaSyDIv3SBPrlGWx2wtwEH5e9cy3yzRcfvtRk",
    authDomain: "kknush-491cb.firebaseapp.com",
    projectId: "kknush-491cb",
    storageBucket: "kknush-491cb.firebasestorage.app",
    messagingSenderId: "385105197059",
    appId: "1:385105197059:web:48b89ee347261c04157636",
    measurementId: "G-XD3VC9QPSL"
  };

  // Initialize Firebase
  const app = initializeApp(firebaseConfig);
  const analytics = getAnalytics(app);
</script>

sekarang buat halaman Dosen berikut

http://localhost:8000/dashboard
http://localhost:8000/students
http://localhost:8000/grades
http://localhost:8000/dpl/monitoring
http://localhost:8000/dpl/monitoring/map

bisa responsive di layar HP tanpa merubah tampilan dari ukuran web