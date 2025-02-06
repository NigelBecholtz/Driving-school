<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>DriveRight Academy - Home</title>
  <link href="https://cdn.jsdelivr.net/npm/daisyui@latest/dist/full.css" rel="stylesheet" type="text/css" />
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css">
  <link rel="stylesheet" href="assets/css/style.css">
  <style>
    @keyframes fadeInUp {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    @keyframes slideInLeft {
      from { opacity: 0; transform: translateX(-30px); }
      to { opacity: 1; transform: translateX(0); }
    }
    @keyframes slideInRight {
      from { opacity: 0; transform: translateX(30px); }
      to { opacity: 1; transform: translateX(0); }
    }
    @keyframes scaleIn {
      from { transform: scale(0.9); opacity: 0; }
      to { transform: scale(1); opacity: 1; }
    }
    
    .animate-fadeInUp { animation: fadeInUp 0.8s ease-out; }
    .animate-slideInLeft { animation: slideInLeft 0.8s ease-out; }
    .animate-slideInRight { animation: slideInRight 0.8s ease-out; }
    .animate-scaleIn { animation: scaleIn 0.8s ease-out; }
    
    /* Hover animations */
    .hover-lift { transition: transform 0.2s ease; }
    .hover-lift:hover { transform: translateY(-5px); }
    
    .hover-grow { transition: transform 0.2s ease; }
    .hover-grow:hover { transform: scale(1.02); }
    
    .menu a {
      position: relative;
      transition: color 0.3s ease;
    }
    
    .menu a::after {
      content: '';
      position: absolute;
      width: 0;
      height: 2px;
      bottom: 0;
      left: 0;
      background-color: currentColor;
      transition: width 0.3s ease;
    }
    
    .menu a:hover::after {
      width: 100%;
    }

    .typing-text {
      border-right: 3px solid;
      animation: typing 3.5s steps(30, end), blink .75s step-end infinite;
      white-space: nowrap;
      overflow: hidden;
    }

    @keyframes typing {
      from { width: 0 }
      to { width: 100% }
    }

    @keyframes blink {
      from, to { border-color: transparent }
      50% { border-color: currentColor }
    }
  </style>
</head>
<body>
  <div class="drawer">
    <input id="my-drawer-3" type="checkbox" class="drawer-toggle" /> 
    <div class="drawer-content flex flex-col">
      <!-- Navbar -->
      <div class="w-full navbar bg-base-300 sticky top-0 z-50">
        <div class="flex-none lg:hidden">
          <label for="my-drawer-3" aria-label="open sidebar" class="btn btn-square btn-ghost">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="inline-block w-6 h-6 stroke-current"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
          </label>
        </div> 
        <div class="flex-1 px-2 mx-2">RijVaardig Academy</div>
        <div class="flex-none hidden lg:block">
          <ul class="menu menu-horizontal">
            <li><a href="index.php">Home</a></li>
            <li>
              <details>
                <summary>Diensten</summary>
                <ul class="p-2 bg-base-100">
                    <li><a href="packages.php">Pakketten</a></li>
                    <li><a href="diensten/rijlessen.php">Rijlessen</a></li>
                    <li><a href="diensten/theorie-examen.php">Theorie Examen</a></li>
                    <li><a href="diensten/praktijkexamen.php">Praktijk Examen</a></li>
                    <li><a href="diensten/opfriscursus.php">Opfriscursus</a></li>
                </ul>
              </details>
            </li>
            <li><a href="about.php">Over Ons</a></li>
            <li><a href="contact.php">Contact</a></li>
          </ul>
        </div>
        <div class="flex-none hidden lg:block ml-4">
          <label class="swap swap-rotate">
            <input type="checkbox" class="theme-controller" value="dark" />
            <svg class="swap-on fill-current w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M5.64,17l-.71.71a1,1,0,0,0,0,1.41,1,1,0,0,0,1.41,0l.71-.71A1,1,0,0,0,5.64,17ZM5,12a1,1,0,0,0-1-1H3a1,1,0,0,0,0,2H4A1,1,0,0,0,5,12Zm7-7a1,1,0,0,0,1-1V3a1,1,0,0,0-2,0V4A1,1,0,0,0,12,5ZM5.64,7.05a1,1,0,0,0,.7.29,1,1,0,0,0,.71-.29,1,1,0,0,0,0-1.41l-.71-.71A1,1,0,0,0,4.93,6.34Zm12,.29a1,1,0,0,0,.7-.29l.71-.71a1,1,0,1,0-1.41-1.41L17,5.64a1,1,0,0,0,0,1.41A1,1,0,0,0,17.66,7.34ZM21,11H20a1,1,0,0,0,0,2h1a1,1,0,0,0,0-2Zm-9,8a1,1,0,0,0-1,1v1a1,1,0,0,0,2,0V20A1,1,0,0,0,12,19ZM18.36,17A1,1,0,0,0,17,18.36l.71.71a1,1,0,0,0,1.41,0,1,1,0,0,0,0-1.41ZM12,6.5A5.5,5.5,0,1,0,17.5,12,5.51,5.51,0,0,0,12,6.5Zm0,9A3.5,3.5,0,1,1,15.5,12,3.5,3.5,0,0,1,12,15.5Z"/></svg>
            <svg class="swap-off fill-current w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M21.64,13a1,1,0,0,0-1.05-.14,8.05,8.05,0,0,1-3.37.73A8.15,8.15,0,0,1,9.08,5.49a8.59,8.59,0,0,1,.25-2A1,1,0,0,0,8,2.36,10.14,10.14,0,1,0,22,14.05,1,1,0,0,0,21.64,13Zm-9.5,6.69A8.14,8.14,0,0,1,7.08,5.22v.27A10.15,10.15,0,0,0,17.22,15.63a9.79,9.79,0,0,0,2.1-.22A8.11,8.11,0,0,1,12.14,19.73Z"/></svg>
          </label>
        </div>
      </div>
      <!-- Page content here -->
      <div class="hero min-h-screen" style="background-image: url(https://images.unsplash.com/photo-1449965408869-eaa3f722e40d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80);">
        <div class="hero-overlay bg-opacity-60"></div>
        <div class="hero-content text-center text-neutral-content">
          <div class="max-w-md">
            <h1 class="mb-5 text-5xl font-bold animate-fadeInUp">
              Begin Jouw 
              <span class="typing-text">Rijvaardigheid</span>
            </h1>
            <p class="mb-5 animate-fadeInUp" style="animation-delay: 0.3s;">
              Word een zelfverzekerde bestuurder met onze ervaren instructeurs en op maat gemaakte pakketten.
            </p>
            <a href="packages.php" class="btn btn-primary animate-pulse">Bekijk Pakketten</a>
          </div>
        </div>
      </div>

      <div class="container mx-auto py-16 px-4">
        <h2 class="text-3xl font-bold text-center mb-12">Waarom Kiezen voor RijVaardig Academy?</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div class="card bg-base-100 shadow-xl">
            <div class="card-body">
              <h2 class="card-title"><i class="fas fa-user-tie mr-2"></i>Expert Instructeurs</h2>
              <p>Onze instructeurs zijn hoogopgeleid en ervaren, zodat je de beste training krijgt.</p>
            </div>
          </div>
          <div class="card bg-base-100 shadow-xl">
            <div class="card-body">
              <h2 class="card-title"><i class="fas fa-car mr-2"></i>Modern Wagenpark</h2>
              <p>Leer rijden in onze moderne, goed onderhouden voertuigen met de nieuwste veiligheidsvoorzieningen.</p>
            </div>
          </div>
          <div class="card bg-base-100 shadow-xl">
            <div class="card-body">
              <h2 class="card-title"><i class="fas fa-graduation-cap mr-2"></i>Complete Opleiding</h2>
              <p>Van theorie tot praktijk, onze opleidingen bevatten alles wat je nodig hebt om een veilige bestuurder te worden.</p>
            </div>
          </div>
        </div>
      </div>

        <footer class="footer p-10 bg-neutral text-neutral-content">
            <nav>
                <header class="footer-title">Diensten</header>
                <a href="diensten/rijlessen.php" class="link link-hover">Rijlessen</a>
                <a href="diensten/theorie-examen.php" class="link link-hover">Theorie-examen</a>
                <a href="diensten/praktijkexamen.php" class="link link-hover">Praktijkexamen</a>
                <a href="diensten/opfriscursus.php" class="link link-hover">Opfriscursus</a>
            </nav>
            <nav>
                <header class="footer-title">Bedrijf</header>
                <a href="about.php" class="link link-hover">Over ons</a>
                <a href="contact.php" class="link link-hover">Contact</a>
                <a href="bedrijf/vacatures.php" class="link link-hover">Vacatures</a>
            </nav>
            <nav>
                <header class="footer-title">Juridisch</header>
                <a href="juridisch/gebruiksvoorwaarden.php" class="link link-hover">Gebruiksvoorwaarden</a>
                <a href="juridisch/privacybeleid.php" class="link link-hover">Privacybeleid</a>
                <a href="juridisch/cookiebeleid.php" class="link link-hover">Cookiebeleid</a>
            </nav>
        </footer>
    </div> 
    <div class="drawer-side">
      <label for="my-drawer-3" aria-label="close sidebar" class="drawer-overlay"></label> 
      <ul class="menu p-4 w-80 min-h-full bg-base-200">
        <li><a href="index.php" class="active">Home</a></li>
        <li><a href="packages.php">Pakketten</a></li>
        <li><a href="about.php">Over Ons</a></li>
        <li><a href="contact.php">Contact</a></li>
      </ul>
    </div>
  </div>
  <button onclick="scrollToTop()" id="scrollTopBtn" class="btn btn-circle btn-primary fixed bottom-8 right-8 opacity-0 transition-opacity duration-300">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
    </svg>
  </button>
  <script src="assets/js/main.js"></script>
</body>
</html>
