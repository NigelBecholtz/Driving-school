<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>RijVaardig Academy - Privacybeleid</title>
  <link href="https://cdn.jsdelivr.net/npm/daisyui@latest/dist/full.css" rel="stylesheet" type="text/css" />
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css">
  <style>
    .animate-fadeInUp { animation: fadeInUp 0.8s ease-out; }
    @keyframes fadeInUp {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>
  <div class="drawer">
    <input id="my-drawer-3" type="checkbox" class="drawer-toggle" /> 
    <div class="drawer-content flex flex-col">
      <!-- Navbar (behouden zoals in andere pagina's) -->
      <div class="container mx-auto py-16 px-4">        

      <!-- Hero Section -->
      <div class="hero min-h-[40vh] bg-base-200">
        <div class="hero-content text-center">
          <div class="max-w-md animate-fadeInUp">
            <h1 class="text-5xl font-bold mb-4">Privacybeleid</h1>
            <p class="mb-6">Wij beschermen uw privacy en persoonlijke gegevens</p>
            <div class="flex gap-4 justify-center">
              <button class="btn btn-primary" onclick="document.getElementById('privacy-content').scrollIntoView({behavior: 'smooth'})">
                Lees privacybeleid
              </button>
              <a href="/contact.php" class="btn btn-outline">Contact</a>
            </div>
          </div>
        </div>
      </div>

      <!-- Main Content -->
      <div class="container mx-auto py-16 px-4" id="privacy-content">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
          <!-- Sidebar Navigation -->
          <div class="lg:col-span-1">
            <div class="sticky top-24">
              <div class="card bg-base-100 shadow-xl">
                <div class="card-body p-4">
                  <h3 class="font-bold text-lg mb-4">Inhoud</h3>
                  <ul class="menu bg-base-200 rounded-box">
                    <li><a href="#gegevensbescherming">Gegevensbescherming</a></li>
                    <li><a href="#verzameling">Gegevensverzameling</a></li>
                    <li><a href="#gebruik">Gebruik van Gegevens</a></li>
                    <li><a href="#bewaartermijn">Bewaartermijn</a></li>
                    <li><a href="#rechten">Uw Rechten</a></li>
                    <li><a href="#contact">Contact</a></li>
                  </ul>
                </div>
              </div>
            </div>
          </div>

          <!-- Content Sections -->
          <div class="lg:col-span-3 space-y-8">
            <div id="gegevensbescherming" class="card bg-base-100 shadow-xl animate-fadeInUp">
              <div class="card-body">
                <h2 class="card-title text-2xl">
                  <i class="fas fa-shield-alt mr-2"></i>
                  Gegevensbescherming
                </h2>
                <p>RijVaardig Academy respecteert uw privacy en zorgt voor de bescherming van uw persoonsgegevens. Wij houden ons aan de Algemene Verordening Gegevensbescherming (AVG).</p>
                <div class="alert alert-info mt-4">
                  <i class="fas fa-info-circle"></i>
                  <span>Laatste update: Januari 2024</span>
                </div>
              </div>
            </div>

            <div id="verzameling" class="card bg-base-100 shadow-xl animate-fadeInUp">
              <div class="card-body">
                <h2 class="card-title text-2xl">
                  <i class="fas fa-database mr-2"></i>
                  Welke gegevens verzamelen wij?
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                  <div class="bg-base-200 p-4 rounded-lg">
                    <h3 class="font-bold mb-2">Persoonlijke Gegevens</h3>
                    <ul class="list-disc list-inside space-y-2">
                      <li>Naam en adres</li>
                      <li>E-mailadres</li>
                      <li>Telefoonnummer</li>
                      <li>Geboortedatum</li>
                    </ul>
                  </div>
                  <div class="bg-base-200 p-4 rounded-lg">
                    <h3 class="font-bold mb-2">Rijgerelateerde Gegevens</h3>
                    <ul class="list-disc list-inside space-y-2">
                      <li>Rijbewijsnummer</li>
                      <li>Leerlingendossier</li>
                      <li>Examenresultaten</li>
                      <li>Voortgangsgegevens</li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>

            <div id="gebruik" class="card bg-base-100 shadow-xl animate-fadeInUp">
              <div class="card-body">
                <h2 class="card-title text-2xl">
                  <i class="fas fa-tasks mr-2"></i>
                  Gebruik van Gegevens
                </h2>
                <p>Wij gebruiken uw gegevens voor de volgende doeleinden:</p>
                <div class="space-y-4 mt-4">
                  <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <span>Uitvoering van de rijopleiding</span>
                  </div>
                  <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <span>Administratieve verwerking</span>
                  </div>
                  <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <span>Communicatie over lessen en examens</span>
                  </div>
                </div>
              </div>
            </div>

            <div id="bewaartermijn" class="card bg-base-100 shadow-xl animate-fadeInUp">
              <div class="card-body">
                <h2 class="card-title text-2xl">
                  <i class="fas fa-clock mr-2"></i>
                  Bewaartermijn
                </h2>
                <p>Wij hanteren de volgende bewaartermijnen:</p>
                <div class="overflow-x-auto mt-4">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>Type Gegevens</th>
                        <th>Bewaartermijn</th>
                        <th>Reden</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>Persoonsgegevens</td>
                        <td>7 jaar</td>
                        <td>Wettelijke verplichting</td>
                      </tr>
                      <tr>
                        <td>Leerlingendossier</td>
                        <td>2 jaar</td>
                        <td>Administratieve doeleinden</td>
                      </tr>
                      <tr>
                        <td>Financiële gegevens</td>
                        <td>7 jaar</td>
                        <td>Belastingwetgeving</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            <div id="rechten" class="card bg-base-100 shadow-xl animate-fadeInUp">
              <div class="card-body">
                <h2 class="card-title text-2xl">
                  <i class="fas fa-user-shield mr-2"></i>
                  Uw Rechten
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                  <div class="bg-base-200 p-4 rounded-lg">
                    <h3 class="font-bold mb-2">Inzagerecht</h3>
                    <p>U heeft recht op inzage in uw persoonsgegevens en kunt een kopie ontvangen.</p>
                  </div>
                  <div class="bg-base-200 p-4 rounded-lg">
                    <h3 class="font-bold mb-2">Correctierecht</h3>
                    <p>U kunt onjuiste gegevens laten corrigeren of aanvullen.</p>
                  </div>
                  <div class="bg-base-200 p-4 rounded-lg">
                    <h3 class="font-bold mb-2">Verwijderingsrecht</h3>
                    <p>U kunt verzoeken om verwijdering van uw gegevens.</p>
                  </div>
                  <div class="bg-base-200 p-4 rounded-lg">
                    <h3 class="font-bold mb-2">Bezwaarrecht</h3>
                    <p>U kunt bezwaar maken tegen de verwerking van uw gegevens.</p>
                  </div>
                </div>
              </div>
            </div>

            <div id="contact" class="card bg-base-100 shadow-xl animate-fadeInUp">
              <div class="card-body">
                <h2 class="card-title text-2xl">
                  <i class="fas fa-envelope mr-2"></i>
                  Contact
                </h2>
                <p>Heeft u vragen over ons privacybeleid? Neem dan contact met ons op:</p>
                <div class="flex gap-4 mt-4">
                  <a href="/contact.php" class="btn btn-primary">
                    <i class="fas fa-paper-plane mr-2"></i>
                    Contact Opnemen
                  </a>
                  <a href="/juridisch/cookiebeleid.php" class="btn btn-outline">
                    <i class="fas fa-cookie-bite mr-2"></i>
                    Cookiebeleid
                  </a>
                  <a href="/juridisch/gebruiksvoorwaarden.php" class="btn btn-outline">
                    <i class="fas fa-file-alt mr-2"></i>
                    Gebruiksvoorwaarden
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Footer (behouden zoals in andere pagina's) -->
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
  
  </script>
</body>
</html> 