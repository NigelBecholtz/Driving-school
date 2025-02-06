<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>DriveRight Academy - Praktijkexamen</title>
  <link href="https://cdn.jsdelivr.net/npm/daisyui@latest/dist/full.css" rel="stylesheet" type="text/css" />
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css">
  <style>
    .animate-fadeInUp { animation: fadeInUp 0.8s ease-out; }
    @keyframes fadeInUp {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    
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
            <li><a href="../index.php">Home</a></li>
            <li>
              <details>
                <summary>Diensten</summary>
                <ul class="p-2 bg-base-100">
                  <li><a href="../packages.php">Pakketten</a></li>
                  <li><a href="./diensten/rijlessen.php">Rijlessen</a></li>
                  <li><a href="./diensten/theorie-examen.php">Theorie Examen</a></li>
                  <li><a href="./praktijkexamen.php" class="active">Praktijk Examen</a></li>
                  <li><a href="./opfriscursus.php">Opfriscursus</a></li>
                </ul>
              </details>
            </li>
            <li><a href="../about.php">Over Ons</a></li>
            <li><a href="../contact.php">Contact</a></li>
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

      <!-- Hero Section -->
      <div class="hero min-h-[60vh]" style="background-image: url(https://images.unsplash.com/photo-1600880292203-757bb62b4baf?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80);">
        <div class="hero-overlay bg-opacity-60"></div>
        <div class="hero-content text-center text-neutral-content">
          <div class="max-w-md">
            <h1 class="mb-5 text-5xl font-bold animate-fadeInUp">Praktijkexamen</h1>
            <p class="mb-5 animate-fadeInUp">Bereid je optimaal voor op je praktijkexamen met onze professionele begeleiding.</p>
            <button class="btn btn-primary" onclick="document.getElementById('examen-info').scrollIntoView({behavior: 'smooth'})">Lees Meer</button>
          </div>
        </div>
      </div>

      <!-- Main Content -->
      <div class="container mx-auto py-16 px-4" id="examen-info">
        <!-- Category Tabs -->
        <div class="tabs tabs-boxed justify-center mb-8">
          <a class="tab tab-active" onclick="showCategory('auto')">Auto Examen</a> 
          <a class="tab" onclick="showCategory('motor')">Motor Examen</a>
        </div>

        <!-- Auto Examen Content -->
        <div id="auto-content">
          <!-- Informatie Sectie -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-16">
            <div class="space-y-6">
              <h2 class="text-3xl font-bold mb-6">Het Praktijkexamen</h2>
              <p>Het praktijkexamen is de laatste stap naar je rijbewijs. Tijdens het examen laat je zien dat je:</p>
              <ul class="list-disc list-inside space-y-2">
                <li>Veilig en zelfstandig kunt rijden</li>
                <li>De verkeersregels kent en toepast</li>
                <li>Rekening houdt met andere weggebruikers</li>
                <li>Milieubewust rijdt</li>
                <li>Bijzondere verrichtingen beheerst</li>
              </ul>
            </div>
            <div class="card bg-base-200 shadow-xl">
              <div class="card-body">
                <h3 class="card-title">Praktische Informatie</h3>
                <ul class="space-y-2">
                  <li><i class="fas fa-clock mr-2"></i>Duur: 55 minuten</li>
                  <li><i class="fas fa-car mr-2"></i>Eigen lesauto beschikbaar</li>
                  <li><i class="fas fa-map-marker-alt mr-2"></i>Start vanaf CBR locatie</li>
                  <li><i class="fas fa-id-card mr-2"></i>Legitimatie verplicht</li>
                  <li><i class="fas fa-file-medical mr-2"></i>Gezondheidsverklaring nodig</li>
                </ul>
              </div>
            </div>
          </div>

          <!-- Voorbereiding Stappen -->
          <h2 class="text-3xl font-bold mb-8 text-center">Onze Voorbereiding</h2>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
            <div class="card bg-base-100 shadow-xl hover-grow">
              <div class="card-body">
                <h3 class="card-title"><i class="fas fa-check-circle text-success mr-2"></i>Proefexamen</h3>
                <p>Ervaar een realistische simulatie van het praktijkexamen om goed voorbereid te zijn.</p>
              </div>
            </div>
            <div class="card bg-base-100 shadow-xl hover-grow">
              <div class="card-body">
                <h3 class="card-title"><i class="fas fa-route text-primary mr-2"></i>Examenroutes</h3>
                <p>Oefen met de meest voorkomende examenroutes in jouw regio.</p>
              </div>
            </div>
            <div class="card bg-base-100 shadow-xl hover-grow">
              <div class="card-body">
                <h3 class="card-title"><i class="fas fa-comments text-info mr-2"></i>Examenbegeleiding</h3>
                <p>Persoonlijke tips en mentale voorbereiding voor het examen.</p>
              </div>
            </div>
          </div>

          <!-- Tarieven -->
          <div class="card bg-base-100 shadow-xl mb-16">
            <div class="card-body">
              <h2 class="card-title text-2xl mb-6">Tarieven Praktijkexamen</h2>
              <div class="overflow-x-auto">
                <table class="table">
                  <thead>
                    <tr>
                      <th>Dienst</th>
                      <th>Duur</th>
                      <th>Prijs</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>Praktijkexamen + Auto</td>
                      <td>55 min</td>
                      <td>€295,-</td>
                    </tr>
                    <tr>
                      <td>Proefexamen</td>
                      <td>60 min</td>
                      <td>€75,-</td>
                    </tr>
                    <tr>
                      <td>Herexamen + Auto</td>
                      <td>55 min</td>
                      <td>€285,-</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- Call-to-Action -->
          <div class="text-center">
            <h2 class="text-2xl font-bold mb-4">Klaar voor je Praktijkexamen?</h2>
            <p class="mb-6">Neem contact met ons op om je examen in te plannen of voor meer informatie.</p>
            <a href="/contact.php" class="btn btn-primary">Contact Opnemen</a>
          </div>
        </div>

        <!-- Motor Examen Content -->
        <div id="motor-content" class="hidden">
          <!-- Informatie Sectie -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-16">
            <div class="space-y-6">
              <h2 class="text-3xl font-bold mb-6">Het Motor Praktijkexamen</h2>
              <p>Het motorexamen bestaat uit twee delen:</p>
              <ul class="list-disc list-inside space-y-2">
                <li>Voertuigbeheersing (AVB)</li>
                <li>Verkeersdeelname (AVD)</li>
              </ul>
              <p class="mt-4">Tijdens het examen toon je aan dat je:</p>
              <ul class="list-disc list-inside space-y-2">
                <li>De motor volledig beheerst</li>
                <li>Veilig aan het verkeer kunt deelnemen</li>
                <li>Risico's kunt inschatten</li>
                <li>Defensief rijgedrag vertoont</li>
                <li>De verkeersregels correct toepast</li>
              </ul>
            </div>
            <div class="card bg-base-200 shadow-xl">
              <div class="card-body">
                <h3 class="card-title">Praktische Informatie</h3>
                <ul class="space-y-2">
                  <li><i class="fas fa-clock mr-2"></i>AVB: 30 minuten</li>
                  <li><i class="fas fa-clock mr-2"></i>AVD: 55 minuten</li>
                  <li><i class="fas fa-motorcycle mr-2"></i>Eigen motorfiets mogelijk</li>
                  <li><i class="fas fa-map-marker-alt mr-2"></i>Start vanaf CBR locatie</li>
                  <li><i class="fas fa-id-card mr-2"></i>Legitimatie verplicht</li>
                  <li><i class="fas fa-file-medical mr-2"></i>Gezondheidsverklaring nodig</li>
                </ul>
              </div>
            </div>
          </div>

          <!-- Voorbereiding Stappen -->
          <h2 class="text-3xl font-bold mb-8 text-center">Onze Voorbereiding</h2>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
            <div class="card bg-base-100 shadow-xl hover-grow">
              <div class="card-body">
                <h3 class="card-title"><i class="fas fa-check-circle text-success mr-2"></i>AVB Training</h3>
                <p>Intensieve training voor de bijzondere verrichtingen op het AVB terrein.</p>
              </div>
            </div>
            <div class="card bg-base-100 shadow-xl hover-grow">
              <div class="card-body">
                <h3 class="card-title"><i class="fas fa-route text-primary mr-2"></i>AVD Voorbereiding</h3>
                <p>Praktijktraining in het verkeer met focus op verkeersinzicht en defensief rijden.</p>
              </div>
            </div>
            <div class="card bg-base-100 shadow-xl hover-grow">
              <div class="card-body">
                <h3 class="card-title"><i class="fas fa-comments text-info mr-2"></i>Examensimulatie</h3>
                <p>Complete simulatie van beide examens voor optimale voorbereiding.</p>
              </div>
            </div>
          </div>

          <!-- Tarieven -->
          <div class="card bg-base-100 shadow-xl mb-16">
            <div class="card-body">
              <h2 class="card-title text-2xl mb-6">Tarieven Motor Praktijkexamen</h2>
              <div class="overflow-x-auto">
                <table class="table">
                  <thead>
                    <tr>
                      <th>Dienst</th>
                      <th>Duur</th>
                      <th>Prijs</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>AVB Examen</td>
                      <td>30 min</td>
                      <td>€235,-</td>
                    </tr>
                    <tr>
                      <td>AVD Examen</td>
                      <td>55 min</td>
                      <td>€285,-</td>
                    </tr>
                    <tr>
                      <td>Proefexamen AVB</td>
                      <td>30 min</td>
                      <td>€65,-</td>
                    </tr>
                    <tr>
                      <td>Proefexamen AVD</td>
                      <td>55 min</td>
                      <td>€85,-</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- Call-to-Action -->
          <div class="text-center">
            <h2 class="text-2xl font-bold mb-4">Klaar voor je Motor Praktijkexamen?</h2>
            <p class="mb-6">Neem contact met ons op om je examen in te plannen of voor meer informatie.</p>
            <a href="/contact.php" class="btn btn-primary">Contact Opnemen</a>
          </div>
        </div>
      </div>

      <!-- Footer -->
      <footer class="footer p-10 bg-neutral text-neutral-content">
        <nav>
          <header class="footer-title">Diensten</header>
          <a href="rijlessen.php" class="link link-hover">Rijlessen</a>
          <a href="theorie-examen.php" class="link link-hover">Theorie-examen</a>
          <a href="praktijkexamen.php" class="link link-hover">Praktijkexamen</a>
          <a href="opfriscursus.php" class="link link-hover">Opfriscursus</a>
        </nav>
        <nav>
          <header class="footer-title">Bedrijf</header>
          <a href="../about.php" class="link link-hover">Over ons</a>
          <a href="../contact.php" class="link link-hover">Contact</a>
          <a href="../bedrijf/vacatures.php" class="link link-hover">Vacatures</a>
        </nav>
        <nav>
          <header class="footer-title">Juridisch</header>
          <a href="../juridisch/gebruiksvoorwaarden.php" class="link link-hover">Gebruiksvoorwaarden</a>
          <a href="../juridisch/privacybeleid.php" class="link link-hover">Privacybeleid</a>
          <a href="../juridisch/cookiebeleid.php" class="link link-hover">Cookiebeleid</a>
        </nav>
      </footer>
    </div>

    <!-- Mobile menu -->
    <div class="drawer-side">
      <label for="my-drawer-3" aria-label="close sidebar" class="drawer-overlay"></label> 
      <ul class="menu p-4 w-80 min-h-full bg-base-200">
        <li><a href="/index.php">Home</a></li>
        <li><a href="/packages.php">Pakketten</a></li>
        <li><a href="/about.php">Over Ons</a></li>
        <li><a href="/contact.php">Contact</a></li>
      </ul>
    </div>
  </div>

  <script>
    // Theme handling
    const themeController = document.querySelector('.theme-controller');
    
    const getCurrentTheme = () => {
      return localStorage.getItem('theme') || 'dark';
    }
    
    const setTheme = (theme) => {
      document.documentElement.setAttribute('data-theme', theme);
      localStorage.setItem('theme', theme);
      themeController.checked = theme === 'dark';
    }
    
    setTheme(getCurrentTheme());
    
    themeController.addEventListener('change', (e) => {
      setTheme(e.target.checked ? 'dark' : 'light');
    });

    // Category switching functionality
    function showCategory(category) {
      const autoContent = document.getElementById('auto-content');
      const motorContent = document.getElementById('motor-content');
      const tabs = document.querySelectorAll('.tab');
      
      if (category === 'auto') {
        autoContent.classList.remove('hidden');
        motorContent.classList.add('hidden');
        tabs[0].classList.add('tab-active');
        tabs[1].classList.remove('tab-active');
      } else {
        autoContent.classList.add('hidden');
        motorContent.classList.remove('hidden');
        tabs[0].classList.remove('tab-active');
        tabs[1].classList.add('tab-active');
      }
    }
  </script>
</body>
</html>
