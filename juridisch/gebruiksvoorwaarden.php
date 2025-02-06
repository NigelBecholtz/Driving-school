<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>RijVaardig Academy - Gebruiksvoorwaarden</title>
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


      <!-- Hero Section -->
      <div class="hero min-h-[40vh] bg-base-200">
        <div class="hero-content text-center">
          <div class="max-w-md animate-fadeInUp">
            <h1 class="text-5xl font-bold mb-4">Gebruiksvoorwaarden</h1>
            <p class="mb-6">Duidelijke afspraken voor een zorgeloze rijervaring</p>
            <div class="flex gap-4 justify-center">
              <button class="btn btn-primary" onclick="document.getElementById('voorwaarden').scrollIntoView({behavior: 'smooth'})">
                Lees voorwaarden
              </button>
              <a href="/contact.php" class="btn btn-outline">Contact</a>
            </div>
          </div>
        </div>
      </div>

      <!-- Main Content -->
      <div class="container mx-auto py-16 px-4" id="voorwaarden">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
          <!-- Sidebar Navigation -->
          <div class="lg:col-span-1">
            <div class="sticky top-24">
              <div class="card bg-base-100 shadow-xl">
                <div class="card-body p-4">
                  <h3 class="font-bold text-lg mb-4">Inhoud</h3>
                  <ul class="menu bg-base-200 rounded-box">
                    <li><a href="#algemeen">Algemeen</a></li>
                    <li><a href="#dienstverlening">Dienstverlening</a></li>
                    <li><a href="#inschrijving">Inschrijving & Betaling</a></li>
                    <li><a href="#lessen">Lessen & Examens</a></li>
                    <li><a href="#aansprakelijkheid">Aansprakelijkheid</a></li>
                    <li><a href="#privacy">Privacy & Gegevens</a></li>
                  </ul>
                </div>
              </div>
            </div>
          </div>

          <!-- Content Sections -->
          <div class="lg:col-span-3 space-y-8">
            <div id="algemeen" class="card bg-base-100 shadow-xl animate-fadeInUp">
              <div class="card-body">
                <h2 class="card-title text-2xl">
                  <i class="fas fa-book mr-2"></i>
                  1. Algemeen
                </h2>
                <p>Deze gebruiksvoorwaarden zijn van toepassing op alle diensten van RijVaardig Academy. Door gebruik te maken van onze diensten gaat u akkoord met deze voorwaarden.</p>
                <ul class="list-disc list-inside mt-4 space-y-2">
                  <li>Geldig vanaf 1 januari 2024</li>
                  <li>Van toepassing op alle vestigingen</li>
                  <li>Wijzigingen voorbehouden</li>
                </ul>
              </div>
            </div>

            <div id="dienstverlening" class="card bg-base-100 shadow-xl animate-fadeInUp">
              <div class="card-body">
                <h2 class="card-title text-2xl">
                  <i class="fas fa-car mr-2"></i>
                  2. Dienstverlening
                </h2>
                <p>RijVaardig Academy biedt professionele rijopleidingen aan voor verschillende rijbewijscategorieën:</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                  <div class="bg-base-200 p-4 rounded-lg">
                    <h3 class="font-bold mb-2">Auto (B)</h3>
                    <ul class="list-disc list-inside">
                      <li>Praktijklessen</li>
                      <li>Theoriecursussen</li>
                      <li>Examenbegeleiding</li>
                    </ul>
                  </div>
                  <div class="bg-base-200 p-4 rounded-lg">
                    <h3 class="font-bold mb-2">Motor (A)</h3>
                    <ul class="list-disc list-inside">
                      <li>AVB training</li>
                      <li>AVD lessen</li>
                      <li>Theoriecursussen</li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>

            <div id="inschrijving" class="card bg-base-100 shadow-xl animate-fadeInUp">
              <div class="card-body">
                <h2 class="card-title text-2xl">
                  <i class="fas fa-file-signature mr-2"></i>
                  3. Inschrijving en Betaling
                </h2>
                <div class="space-y-4">
                  <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    <span>Betalingen dienen vooraf te worden voldaan voor aanvang van de lessen.</span>
                  </div>
                  <h3 class="font-bold">Betalingsmogelijkheden:</h3>
                  <ul class="list-disc list-inside space-y-2">
                    <li>iDEAL</li>
                    <li>Bankoverschrijving</li>
                    <li>Pinbetaling op locatie</li>
                    <li>Termijnbetaling in overleg</li>
                  </ul>
                </div>
              </div>
            </div>

            <div id="lessen" class="card bg-base-100 shadow-xl animate-fadeInUp">
              <div class="card-body">
                <h2 class="card-title text-2xl">
                  <i class="fas fa-chalkboard-teacher mr-2"></i>
                  4. Lessen en Examens
                </h2>
                <div class="alert alert-warning mb-4">
                  <i class="fas fa-exclamation-triangle"></i>
                  <span>Afmelden kan kosteloos tot 24 uur voor aanvang van de les.</span>
                </div>
                <div class="space-y-4">
                  <p>Bij niet tijdig afmelden worden de volgende kosten in rekening gebracht:</p>
                  <ul class="list-disc list-inside space-y-2">
                    <li>Praktijkles: 100% van het lestarief</li>
                    <li>Theorieles: 50% van het lestarief</li>
                    <li>Examen: Volledige examenkosten</li>
                  </ul>
                </div>
              </div>
            </div>

            <div id="aansprakelijkheid" class="card bg-base-100 shadow-xl animate-fadeInUp">
              <div class="card-body">
                <h2 class="card-title text-2xl">
                  <i class="fas fa-shield-alt mr-2"></i>
                  5. Aansprakelijkheid
                </h2>
                <p>RijVaardig Academy is volledig verzekerd voor alle lessen en examens. Onze aansprakelijkheid is echter beperkt tot:</p>
                <ul class="list-disc list-inside space-y-2 mt-4">
                  <li>Directe schade tijdens rijlessen</li>
                  <li>Schade door aantoonbare nalatigheid</li>
                  <li>Maximaal het factuurbedrag van de betreffende dienst</li>
                </ul>
              </div>
            </div>

            <div id="privacy" class="card bg-base-100 shadow-xl animate-fadeInUp">
              <div class="card-body">
                <h2 class="card-title text-2xl">
                  <i class="fas fa-user-shield mr-2"></i>
                  6. Privacy & Gegevens
                </h2>
                <p>Wij gaan zorgvuldig om met uw persoonsgegevens volgens onze privacyverklaring.</p>
                <div class="flex gap-4 mt-4">
                  <a href="/juridisch/privacybeleid.php" class="btn btn-outline btn-sm">
                    <i class="fas fa-external-link-alt mr-2"></i>
                    Privacybeleid
                  </a>
                  <a href="/juridisch/cookiebeleid.php" class="btn btn-outline btn-sm">
                    <i class="fas fa-cookie-bite mr-2"></i>
                    Cookiebeleid
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