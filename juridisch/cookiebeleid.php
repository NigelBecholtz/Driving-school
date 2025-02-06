<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>RijVaardig Academy - Cookiebeleid</title>
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
  </style>
</head>
<body>
  <!-- Add standard navbar -->
  <div class="drawer">
    <input id="my-drawer-3" type="checkbox" class="drawer-toggle" /> 
    <div class="drawer-content flex flex-col">
      <!-- Navbar -->
      <div class="w-full navbar bg-base-300 sticky top-0 z-50">
        <!-- ... same navbar as other pages ... -->
      </div>
      
      <!-- Hero Section -->
      <div class="hero min-h-[40vh] bg-base-200">
        <div class="hero-content text-center">
          <div class="max-w-md animate-fadeInUp">
            <h1 class="text-5xl font-bold mb-4">Cookiebeleid</h1>
            <p class="mb-6">Transparant over uw privacy en cookiegebruik</p>
            <button class="btn btn-primary" onclick="document.getElementById('cookie-settings').scrollIntoView({behavior: 'smooth'})">
              Cookie-instellingen aanpassen
            </button>
          </div>
        </div>
      </div>

      <!-- Main Content -->
      <div class="container mx-auto py-16 px-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          <!-- Informatie Sectie -->
          <div class="lg:col-span-2 space-y-8 animate-fadeInUp">
            <div class="card bg-base-100 shadow-xl">
              <div class="card-body">
                <h2 class="card-title text-2xl mb-4">
                  <i class="fas fa-cookie-bite mr-2"></i>
                  Wat zijn cookies?
                </h2>
                <p>Cookies zijn kleine tekstbestanden die bij het bezoeken van websites op uw computer kunnen worden bewaard. Ze helpen ons om uw ervaring op onze website te verbeteren en te personaliseren.</p>
              </div>
            </div>

            <div class="card bg-base-100 shadow-xl">
              <div class="card-body">
                <h2 class="card-title text-2xl mb-4">
                  <i class="fas fa-list-check mr-2"></i>
                  Onze cookies
                </h2>
                <div class="space-y-4">
                  <div class="p-4 bg-base-200 rounded-lg">
                    <h3 class="font-bold mb-2">Noodzakelijke cookies</h3>
                    <p>Deze cookies zijn essentieel voor het functioneren van de website. Ze kunnen niet worden uitgeschakeld.</p>
                  </div>
                  <div class="p-4 bg-base-200 rounded-lg">
                    <h3 class="font-bold mb-2">Analytische cookies</h3>
                    <p>Helpen ons te begrijpen hoe bezoekers onze website gebruiken, via Google Analytics.</p>
                  </div>
                  <div class="p-4 bg-base-200 rounded-lg">
                    <h3 class="font-bold mb-2">Functionele cookies</h3>
                    <p>Onthouden uw voorkeuren zoals taal, thema en inloggegevens.</p>
                  </div>
                </div>
              </div>
            </div>

            <div class="card bg-base-100 shadow-xl">
              <div class="card-body">
                <h2 class="card-title text-2xl mb-4">
                  <i class="fas fa-shield-halved mr-2"></i>
                  Uw privacy
                </h2>
                <ul class="space-y-2">
                  <li><i class="fas fa-check text-success mr-2"></i>Wij respecteren uw privacy</li>
                  <li><i class="fas fa-check text-success mr-2"></i>Geen verkoop van persoonlijke gegevens</li>
                  <li><i class="fas fa-check text-success mr-2"></i>Veilige verwerking volgens AVG/GDPR</li>
                  <li><i class="fas fa-check text-success mr-2"></i>Controle over uw gegevens</li>
                </ul>
              </div>
            </div>
          </div>

          <!-- Cookie Instellingen Sidebar -->
          <div class="lg:col-span-1" id="cookie-settings">
            <div class="card bg-base-100 shadow-xl sticky top-24">
              <div class="card-body">
                <h2 class="card-title text-2xl mb-6">
                  <i class="fas fa-sliders mr-2"></i>
                  Cookie-instellingen
                </h2>
                <div class="space-y-4">
                  <div class="form-control">
                    <label class="label cursor-pointer">
                      <span class="label-text font-medium">Noodzakelijke cookies</span> 
                      <input type="checkbox" checked="checked" disabled class="toggle toggle-primary" />
                    </label>
                    <p class="text-sm opacity-75 mt-1">Altijd actief voor basisfunctionaliteit</p>
                  </div>
                  <div class="form-control">
                    <label class="label cursor-pointer">
                      <span class="label-text font-medium">Analytische cookies</span> 
                      <input type="checkbox" class="toggle toggle-primary" id="analytics-cookies" />
                    </label>
                    <p class="text-sm opacity-75 mt-1">Help ons de website te verbeteren</p>
                  </div>
                  <div class="form-control">
                    <label class="label cursor-pointer">
                      <span class="label-text font-medium">Functionele cookies</span> 
                      <input type="checkbox" class="toggle toggle-primary" id="functional-cookies" />
                    </label>
                    <p class="text-sm opacity-75 mt-1">Voor een gepersonaliseerde ervaring</p>
                  </div>
                  <button class="btn btn-primary w-full mt-6" onclick="saveCookiePreferences()">
                    Voorkeuren opslaan
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Add standard footer -->
      <footer class="footer p-10 bg-neutral text-neutral-content">
        <!-- ... same footer as index.php ... -->
      </footer>
    </div>
  </div>

  <script>
    // Theme handling (zoals in andere pagina's)
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

    // Cookie preferences handling
    function saveCookiePreferences() {
      const analytics = document.getElementById('analytics-cookies').checked;
      const functional = document.getElementById('functional-cookies').checked;
      
      // Sla voorkeuren op in localStorage
      localStorage.setItem('cookie-preferences', JSON.stringify({
        analytics,
        functional
      }));

      // Toon succesbericht
      const alert = document.createElement('div');
      alert.className = 'alert alert-success fixed top-4 right-4 w-96 z-50';
      alert.innerHTML = `
        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        <span>Uw cookie-voorkeuren zijn opgeslagen!</span>
      `;
      document.body.appendChild(alert);

      // Verwijder het bericht na 3 seconden
      setTimeout(() => {
        alert.remove();
      }, 3000);
    }

    // Laad opgeslagen voorkeuren
    window.addEventListener('load', () => {
      const preferences = JSON.parse(localStorage.getItem('cookie-preferences') || '{}');
      if (preferences.analytics) document.getElementById('analytics-cookies').checked = true;
      if (preferences.functional) document.getElementById('functional-cookies').checked = true;
    });
  </script>
</body>
</html> 