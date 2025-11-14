<?php
require 'config.php';
?>
<!DOCTYPE html>
<html lang="sq">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>ASA Education</title>

  <!-- Fonts & Favicon -->
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Poppins&display=swap" rel="stylesheet">
  <link rel="icon" type="image/png" href="images/logo.png">

  <!-- CSS -->
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <!-- LOADER -->
  <div id="loader">
    <div class="spinner"></div>
    <p>Duke u hapur...</p>
  </div>

  <!-- NAVBAR -->
  <nav class="navbar">
    <div class="logo">
      <img src="images/logo.png" alt="ASA Logo" />
    </div>

    <!-- Hamburger Menu -->
    <div id="menu-toggle" class="menu-toggle">
      <span class="bar"></span>
      <span class="bar"></span>
      <span class="bar"></span>
    </div>

    <!-- Navigation Links -->
    <ul id="nav-links" class="nav-links">
      <li><a href="#home" class="active">Kreu</a></li>
      <li><a href="#about">Rreth Nesh</a></li>
      <li><a href="#services">Çfarë Ofrojmë</a></li>
      <li><a href="#courses">Kurset</a></li>
      <li><a href="institution.html">Institucioni</a></li>
      <li><a href="#contact">Kontakti</a></li>

      <!-- DYNAMIC LOGIN NAVBAR -->
      <?php if (isset($_SESSION['user_id'])): ?>

      <li class="user-dropdown">
          <span class="user-name">
              Përshëndetje, <?= htmlspecialchars($_SESSION['full_name']); ?> ▼
          </span>

          <ul class="dropdown-menu">
              <li><a href="dashboard.php">Paneli</a></li>
              <li><a href="dashboard.php">Profili</a></li>

              <?php if ($_SESSION['is_admin'] == 1): ?>
                  <li><a href="admin.php">Admin Panel</a></li>
              <?php endif; ?>

              <li><a class="logout-btn" href="logout.php">Dil</a></li>
          </ul>
      </li>

      <?php else: ?>

      <li><a href="#" onclick="openLogin()">Kyçu</a></li>
      <li><a href="#" onclick="openRegister()">Regjistrohu</a></li>

      <?php endif; ?>
    </ul>
  </nav>

  <!-- HERO -->
  <section id="home" class="hero">
    <div class="content">
      <h1>ASA Education</h1>
      <p>Frymëzojmë Krijimtarinë, Promovojmë Talente të Reja.</p>
      <div class="buttons">
        <button class="btn btn-red" id="goToCoursesBtn">Shiko Kurset</button>
        <button class="btn btn-white" onclick="scrollToSection('about')">Më shumë rreth nesh</button>
      </div>
    </div>
  </section>

  <!-- ABOUT -->
  <section id="about">
    <div class="section-container">
      <h1>Rreth ASA Education</h1>
      <p>
        ASA Education është një qendër trajnimi që frymëzon kreativitetin dhe mbështet talentet e reja.
      </p>
    </div>
  </section>

  <!-- SERVICES -->
  <section id="services">
    <div class="section-container">
      <h1>Çfarë Ofrojmë</h1>
      <ul>
        <li>Kurse programimi (HTML, CSS, JavaScript, Python, Java)</li>
        <li>Trajnime për dizajn grafik dhe marketing dixhital</li>
        <li>Mentorim profesional</li>
        <li>Certifikata ndërkombëtare</li>
      </ul>
    </div>
  </section>

  <!-- COURSES -->
  <section id="courses">
    <div class="courses-header">
      <h1>Zbulo talentin tënd krijues!</h1>
      <p>Regjistrimet janë të hapura.</p>
    </div>

    <div class="courses-container">

      <div class="course-card" onclick="selectCourse('Artistët e Vegjël', this)">
        <img src="images/foto1.png" alt="Artistët e Vegjël" />
        <h2>Artistët e Vegjël</h2>
        <p>(4–8 vjeç)</p>
        <p><strong>60 min × 2 herë në javë</strong></p>
      </div>

      <div class="course-card" onclick="selectCourse('Talente të Reja', this)">
        <img src="images/foto2.png" alt="Talente të Reja" />
        <h2>Talente të Reja</h2>
        <p>(8–13 vjeç)</p>
        <p><strong>120 min × 1 herë në javë</strong></p>
      </div>

      <div class="course-card" onclick="selectCourse('Artistët & Arkitektët e Rinj',this)">
        <img src="images/foto3.png" alt="Artistët & Arkitektët e Rinj" />
        <h2>Artistët & Arkitektët e Rinj</h2>
        <p>(13+ vjeç)</p>
        <p><strong>180 min × 1 herë në javë</strong></p>
      </div>

      <div class="course-card" onclick="selectCourse('Grafik Dizajn',this)">
        <img src="images/foto4.png" alt="Grafik Dizajn" />
        <h2>Grafik Dizajn</h2>
        <p>(13+ vjeç)</p>
        <p><strong>90 min × 2 herë në javë</strong></p>
      </div>

      <div class="course-card" onclick="selectCourse('Seanca Individuale',this)">
        <img src="images/foto5.png" alt="Seanca Individuale" />
        <h2>Seanca Individuale</h2>
        <p><strong>Sipas kërkesës</strong></p>
      </div>

    </div>

    <div class="register-btn">
      <a onclick="registerForCourse()" class="btn btn-red big-btn" style="cursor:pointer;">
          🎨 Regjistrohu Tani
      </a>
    </div>
  </section>

  <script>
  function registerForCourse() {
      const loggedIn = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;

      if (loggedIn) {
          window.location.href = "dashboard.php#addCourse";
      } else {
          openLogin();
      }
  }
  </script>

  <!-- CONTACT -->
  <section id="contact">
    <div class="section-container">
      <h1>Na Kontaktoni</h1>
      <p>📍 Grand Gallery, Yzberisht, Tiranë</p>
      <p>📧 qendra.asa@gmail.com</p>
      <p>📞 +355 69 45 49 045</p>
    </div>
  </section>

  <!-- LOGIN POPUP -->
  <div id="loginModal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeLogin()">&times;</span>

      <h2>Kyçu</h2>

      <form action="login.php" method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Fjalëkalimi" required>
        <button type="submit" class="btn btn-red" style="width:100%;">Kyçu</button>
      </form>

      <p style="margin-top:10px;">
        Nuk ke llogari? <a href="#" onclick="switchToRegister()">Regjistrohu këtu</a>
      </p>
    </div>
  </div>
<!-- REGISTER POPUP -->
<div id="registerModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeRegister()">&times;</span>

    <h2>Regjistrohu</h2>

    <form action="register.php" method="POST">

      <!-- HIDDEN INPUT REQUIRED -->
      <input type="hidden" name="selected_course" id="selected_course">

      <input type="text" name="full_name" placeholder="Emri i plotë" required>
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Fjalëkalimi" required>
      <input type="password" name="confirm_password" placeholder="Përsërit fjalëkalimin" required>

      <button type="submit" class="btn btn-red" style="width:100%;">Regjistrohu</button>
    </form>

    <p style="margin-top:10px;">
      Ke llogari? <a href="#" onclick="switchToLogin()">Kyçu këtu</a>
    </p>
  </div>
</div>


  <footer>
    <p>© 2025 ASA Education – Të gjitha të drejtat e rezervuara</p>
  </footer>

 <script>
  window.isLoggedIn = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;
</script>

<script src="script.js"></script>
</body>
</html>