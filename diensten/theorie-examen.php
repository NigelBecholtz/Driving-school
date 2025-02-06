<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>RijVaardig Academy - Theorie-examen</title>
  <link href="https://cdn.jsdelivr.net/npm/daisyui@latest/dist/full.css" rel="stylesheet" type="text/css" />
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css">
  <link rel="stylesheet" href="../styles/index.css">
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
                  <li><a href="./rijlessen.php">Rijlessen</a></li>
                  <li><a href="./theorie-examen.php" class="active">Theorie Examen</a></li>
                  <li><a href="./praktijkexamen.php">Praktijk Examen</a></li>
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
      <div class="hero min-h-[60vh]" style="background-image: url(https://images.unsplash.com/photo-1434030216411-0b793f4b4173?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80);">
        <div class="hero-overlay bg-opacity-60"></div>
        <div class="hero-content text-center text-neutral-content">
          <div class="max-w-md">
            <h1 class="mb-5 text-5xl font-bold animate-fadeInUp">Theorie-examen Training</h1>
            <p class="mb-5 animate-fadeInUp" style="animation-delay: 0.3s;">
              Bereid je optimaal voor op je CBR theorie-examen
            </p>
          </div>
        </div>
      </div>

      <!-- Main Content -->
      <div class="container mx-auto py-16 px-4">
        <!-- Category Tabs -->
        <div class="tabs tabs-boxed justify-center mb-8">
          <a class="tab tab-active" onclick="showCategory('auto')">Auto Theorie</a> 
          <a class="tab" onclick="showCategory('motor')">Motor Theorie</a>
        </div>

        <!-- Auto Content -->
        <div id="auto-content">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-16">
            <div class="prose max-w-none animate-fadeInUp">
              <h2 class="text-3xl font-bold mb-6">Auto Theorie-examen Voorbereiding</h2>
              <p class="mb-4">Bij RijVaardig Academy bieden we een uitgebreid theorieprogramma dat je optimaal voorbereidt op het CBR auto theorie-examen. Ons programma is ontwikkeld door ervaren instructeurs en heeft een bewezen slagingspercentage van 85%.</p>
              <div class="card bg-base-100 shadow-xl p-6 mt-8">
                <h3 class="text-xl font-bold mb-4">Ons programma omvat:</h3>
                <ul class="list-disc list-inside space-y-2">
                  <li>Uitgebreid digitaal lesmateriaal</li>
                  <li>Onbeperkt toegang tot online oefenexamens</li>
                  <li>Persoonlijke begeleiding van ervaren instructeurs</li>
                  <li>Examengarantie bij volledige afronding</li>
                  <li>Gratis herkansing bij niet slagen</li>
                </ul>
              </div>
            </div>

            <div class="animate-fadeInUp" style="animation-delay: 0.2s">
              <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                  <h2 class="card-title text-2xl mb-6">Theoriepakketten</h2>
                  <div class="space-y-6">
                    <div class="p-4 bg-base-200 rounded-lg hover-lift">
                      <div class="flex justify-between items-center mb-2">
                        <span class="font-bold">Online Zelfstudie</span>
                        <span class="badge badge-primary badge-lg">€75</span>
                      </div>
                      <p class="text-sm opacity-75">Perfect voor zelfstandig leren</p>
                    </div>
                    <div class="p-4 bg-base-200 rounded-lg hover-lift">
                      <div class="flex justify-between items-center mb-2">
                        <span class="font-bold">Klassikale Training</span>
                        <span class="badge badge-primary badge-lg">€125</span>
                      </div>
                      <p class="text-sm opacity-75">Inclusief 2 klassikale lesdagen</p>
                    </div>
                    <div class="p-4 bg-base-200 rounded-lg hover-lift">
                      <div class="flex justify-between items-center mb-2">
                        <span class="font-bold">Complete Pakket</span>
                        <span class="badge badge-primary badge-lg">€175</span>
                      </div>
                      <p class="text-sm opacity-75">Combinatie van online en klassikaal</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Motor Content -->
        <div id="motor-content" class="hidden">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-16">
            <div class="prose max-w-none animate-fadeInUp">
              <h2 class="text-3xl font-bold mb-6">Motor Theorie-examen Voorbereiding</h2>
              <p class="mb-4">Ons motortheorie-programma bereidt je grondig voor op het CBR motortheorie-examen. Met speciale aandacht voor motorspecifieke verkeerssituaties en risicoperceptie.</p>
              <div class="card bg-base-100 shadow-xl p-6 mt-8">
                <h3 class="text-xl font-bold mb-4">Specifieke aandachtspunten:</h3>
                <ul class="list-disc list-inside space-y-2">
                  <li>Motorspecifieke verkeerssituaties</li>
                  <li>Risicoperceptie voor motorrijders</li>
                  <li>Technische aspecten van motorfietsen</li>
                  <li>Beschermende kleding en uitrusting</li>
                  <li>Motorspecifieke verkeersregels</li>
                </ul>
              </div>
            </div>

            <div class="animate-fadeInUp" style="animation-delay: 0.2s">
              <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                  <h2 class="card-title text-2xl mb-6">Motor Theoriepakketten</h2>
                  <div class="space-y-6">
                    <div class="p-4 bg-base-200 rounded-lg hover-lift">
                      <div class="flex justify-between items-center mb-2">
                        <span class="font-bold">Online Zelfstudie Motor</span>
                        <span class="badge badge-primary badge-lg">€85</span>
                      </div>
                      <p class="text-sm opacity-75">Inclusief motorspecifieke oefenexamens</p>
                    </div>
                    <div class="p-4 bg-base-200 rounded-lg hover-lift">
                      <div class="flex justify-between items-center mb-2">
                        <span class="font-bold">Klassikale Motor Training</span>
                        <span class="badge badge-primary badge-lg">€135</span>
                      </div>
                      <p class="text-sm opacity-75">Inclusief 2 klassikale motortheorie dagen</p>
                    </div>
                    <div class="p-4 bg-base-200 rounded-lg hover-lift">
                      <div class="flex justify-between items-center mb-2">
                        <span class="font-bold">Complete Motor Pakket</span>
                        <span class="badge badge-primary badge-lg">€185</span>
                      </div>
                      <p class="text-sm opacity-75">Online + klassikaal met extra praktijkvoorbeelden</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- FAQ Section -->
        <div class="bg-base-200 p-8 rounded-lg animate-fadeInUp" style="animation-delay: 0.4s">
          <h2 class="text-3xl font-bold mb-8 text-center">Veelgestelde Vragen</h2>
          <div class="space-y-4 max-w-3xl mx-auto">
            <div class="collapse collapse-plus bg-base-100">
              <input type="radio" name="my-accordion-3" checked="checked" /> 
              <div class="collapse-title text-xl font-medium">
                Wat zijn de verschillen tussen auto- en motortheorie?
              </div>
              <div class="collapse-content"> 
                <p>Het motortheorie-examen bevat extra onderdelen specifiek gericht op motorrijden, zoals balans, motorspecifieke verkeerssituaties en beschermende uitrusting. De basisprincipes van verkeersregels zijn hetzelfde.</p>
              </div>
            </div>
            <div class="collapse collapse-plus bg-base-100">
              <input type="radio" name="my-accordion-3" /> 
              <div class="collapse-title text-xl font-medium">
                Hoe lang duurt de voorbereiding?
              </div>
              <div class="collapse-content"> 
                <p>De gemiddelde voorbereidingstijd is 2-3 weken bij regelmatige studie. Dit verschilt per persoon en hangt af van je studiemethode en voorkennis.</p>
              </div>
            </div>
            <div class="collapse collapse-plus bg-base-100">
              <input type="radio" name="my-accordion-3" /> 
              <div class="collapse-title text-xl font-medium">
                Wat is het slagingspercentage?
              </div>
              <div class="collapse-content"> 
                <p>85% van onze cursisten slaagt bij de eerste poging. Bij het complete pakket bieden we een gratis herkansing aan als je niet slaagt.</p>
              </div>
            </div>
            <div class="collapse collapse-plus bg-base-100">
              <input type="radio" name="my-accordion-3" /> 
              <div class="collapse-title text-xl font-medium">
                Kan ik direct beginnen?
              </div>
              <div class="collapse-content"> 
                <p>Ja, na aanmelding krijg je direct toegang tot het online leerplatform. Klassikale lessen worden ingepland in overleg.</p>
              </div>
            </div>
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

    <!-- Drawer Side -->
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

  <!-- Scroll to top button -->
  <button onclick="scrollToTop()" id="scrollTopBtn" class="btn btn-circle btn-primary fixed bottom-8 right-8 opacity-0 transition-opacity duration-300">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
    </svg>
  </button>

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

    // Scroll to top functionality
    window.onscroll = function() {
      const btn = document.getElementById('scrollTopBtn');
      if (document.body.scrollTop > 200 || document.documentElement.scrollTop > 200) {
        btn.classList.remove('opacity-0');
      } else {
        btn.classList.add('opacity-0');
      }
    };

    function scrollToTop() {
      window.scrollTo({
        top: 0,
        behavior: 'smooth'
      });
    }

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