<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>DriveRight Academy - Vacatures</title>
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
                  <li><a href="../diensten/rijlessen.php">Rijlessen</a></li>
                  <li><a href="../diensten/theorie-examen.php">Theorie Examen</a></li>
                  <li><a href="../diensten/opfriscursus.php">Opfriscursus</a></li>
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

      <!-- Page content -->
      <div class="container mx-auto py-16 px-4">
        <h1 class="text-4xl font-bold text-center mb-8 animate-fadeInUp">Vacatures</h1>
        <p class="text-center mb-12 max-w-2xl mx-auto animate-fadeInUp">
          Word onderdeel van ons team! Bij RijVaardig Academy zijn we altijd op zoek naar getalenteerde professionals die onze passie voor rijonderwijs delen.
        </p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
          <!-- Rijinstructeur Vacature -->
          <div class="card bg-base-100 shadow-xl hover-grow">
            <div class="card-body">
              <h2 class="card-title"><i class="fas fa-car-side mr-2"></i>Rijinstructeur</h2>
              <p class="mb-4">We zoeken ervaren rijinstructeurs die onze leerlingen kunnen begeleiden naar hun rijbewijs.</p>
              <ul class="list-disc list-inside mb-4">
                <li>Minimaal 3 jaar ervaring als rijinstructeur</li>
                <li>WRM-certificaat</li>
                <li>Uitstekende communicatieve vaardigheden</li>
                <li>Flexibele werkuren</li>
              </ul>
              <div class="card-actions justify-end">
                <button class="btn btn-primary" onclick="document.getElementById('rijinstructeur-modal').showModal()">Solliciteer Nu</button>
              </div>
            </div>
          </div>

          <!-- Theorie Docent Vacature -->
          <div class="card bg-base-100 shadow-xl hover-grow">
            <div class="card-body">
              <h2 class="card-title"><i class="fas fa-chalkboard-teacher mr-2"></i>Theorie Docent</h2>
              <p class="mb-4">Voor onze theorielessen zoeken we enthousiaste docenten die complexe verkeersregels helder kunnen uitleggen.</p>
              <ul class="list-disc list-inside mb-4">
                <li>Relevante onderwijservaring</li>
                <li>Grondige kennis van verkeersregels</li>
                <li>Ervaring met klassikaal lesgeven</li>
                <li>Part-time beschikbaarheid</li>
              </ul>
              <div class="card-actions justify-end">
                <button class="btn btn-primary" onclick="document.getElementById('theorie-docent-modal').showModal()">Solliciteer Nu</button>
              </div>
            </div>
          </div>
        </div>

        <!-- Contact sectie -->
        <div class="mt-16 text-center">
          <h2 class="text-2xl font-bold mb-4">Geen passende vacature?</h2>
          <p class="mb-6">
            Stuur ons een open sollicitatie! We zijn altijd geïnteresseerd in getalenteerde mensen.
          </p>
          <a href="../contact.php" class="btn btn-outline">Neem Contact Op</a>
        </div>
      </div>

      <!-- Footer -->
      <footer class="footer p-10 bg-neutral text-neutral-content">
        <nav>
          <header class="footer-title">Diensten</header> 
          <a href="../diensten/rijlessen.php" class="link link-hover">Rijlessen</a>
          <a href="../diensten/theorie-examen.php" class="link link-hover">Theorie-examen</a>
          <a href="../diensten/praktijkexamen.php" class="link link-hover">Praktijkexamen</a>
          <a href="../diensten/opfriscursus.php" class="link link-hover">Opfriscursus</a>
        </nav> 
        <nav>
          <header class="footer-title">Bedrijf</header> 
          <a href="../about.php" class="link link-hover">Over ons</a>
          <a href="../contact.php" class="link link-hover">Contact</a>
          <a href="./vacatures.php" class="link link-hover">Vacatures</a>
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

  <!-- Sollicitatieformulier Modal voor Rijinstructeur -->
  <dialog id="rijinstructeur-modal" class="modal">
    <div class="modal-box w-11/12 max-w-2xl">
      <form method="dialog">
        <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
      </form>
      <h3 class="font-bold text-lg mb-4">Solliciteer als Rijinstructeur</h3>
      <form class="space-y-4" id="rijinstructeur-form" onsubmit="handleSubmit(event, 'rijinstructeur')">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="form-control">
            <label class="label">
              <span class="label-text">Voornaam</span>
            </label>
            <input type="text" required class="input input-bordered" name="voornaam" />
          </div>
          <div class="form-control">
            <label class="label">
              <span class="label-text">Achternaam</span>
            </label>
            <input type="text" required class="input input-bordered" name="achternaam" />
          </div>
        </div>
        <div class="form-control">
          <label class="label">
            <span class="label-text">E-mail</span>
          </label>
          <input type="email" required class="input input-bordered" name="email" />
        </div>
        <div class="form-control">
          <label class="label">
            <span class="label-text">Telefoonnummer</span>
          </label>
          <input type="tel" required class="input input-bordered" name="telefoon" />
        </div>
        <div class="form-control">
          <label class="label">
            <span class="label-text">WRM-certificaatnummer</span>
          </label>
          <input type="text" required class="input input-bordered" name="wrm" />
        </div>
        <div class="form-control">
          <label class="label">
            <span class="label-text">Motivatie</span>
          </label>
          <textarea class="textarea textarea-bordered h-24" required name="motivatie"></textarea>
        </div>
        <div class="form-control">
          <label class="label">
            <span class="label-text">CV uploaden</span>
          </label>
          <input type="file" required class="file-input file-input-bordered w-full" name="cv" accept=".pdf,.doc,.docx" />
        </div>
        <div class="modal-action">
          <button type="submit" class="btn btn-primary">Verstuur Sollicitatie</button>
        </div>
      </form>
    </div>
  </dialog>

  <!-- Sollicitatieformulier Modal voor Theorie Docent -->
  <dialog id="theorie-docent-modal" class="modal">
    <div class="modal-box w-11/12 max-w-2xl">
      <form method="dialog">
        <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
      </form>
      <h3 class="font-bold text-lg mb-4">Solliciteer als Theorie Docent</h3>
      <form class="space-y-4" id="theorie-docent-form" onsubmit="handleSubmit(event, 'theorie-docent')">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="form-control">
            <label class="label">
              <span class="label-text">Voornaam</span>
            </label>
            <input type="text" required class="input input-bordered" name="voornaam" />
          </div>
          <div class="form-control">
            <label class="label">
              <span class="label-text">Achternaam</span>
            </label>
            <input type="text" required class="input input-bordered" name="achternaam" />
          </div>
        </div>
        <div class="form-control">
          <label class="label">
            <span class="label-text">E-mail</span>
          </label>
          <input type="email" required class="input input-bordered" name="email" />
        </div>
        <div class="form-control">
          <label class="label">
            <span class="label-text">Telefoonnummer</span>
          </label>
          <input type="tel" required class="input input-bordered" name="telefoon" />
        </div>
        <div class="form-control">
          <label class="label">
            <span class="label-text">Beschikbaarheid per week</span>
          </label>
          <select class="select select-bordered" required name="beschikbaarheid">
            <option value="">Selecteer beschikbaarheid</option>
            <option value="8-16">8-16 uur</option>
            <option value="16-24">16-24 uur</option>
            <option value="24-32">24-32 uur</option>
            <option value="32-40">32-40 uur</option>
          </select>
        </div>
        <div class="form-control">
          <label class="label">
            <span class="label-text">Motivatie</span>
          </label>
          <textarea class="textarea textarea-bordered h-24" required name="motivatie"></textarea>
        </div>
        <div class="form-control">
          <label class="label">
            <span class="label-text">CV uploaden</span>
          </label>
          <input type="file" required class="file-input file-input-bordered w-full" name="cv" accept=".pdf,.doc,.docx" />
        </div>
        <div class="modal-action">
          <button type="submit" class="btn btn-primary">Verstuur Sollicitatie</button>
        </div>
      </form>
    </div>
  </dialog>

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

    // Formulier handling toevoegen
    function handleSubmit(event, type) {
      event.preventDefault();
      const form = event.target;
      const formData = new FormData(form);

      // Hier zou je normaal gesproken de data naar je backend sturen
      // Voor nu tonen we een succesbericht
      const modal = document.getElementById(`${type}-modal`);
      modal.close();

      // Toon succesbericht
      const alert = document.createElement('div');
      alert.className = 'alert alert-success fixed top-4 right-4 w-96 z-50';
      alert.innerHTML = `
        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        <span>Bedankt voor je sollicitatie! We nemen zo spoedig mogelijk contact met je op.</span>
      `;
      document.body.appendChild(alert);

      // Verwijder het succesbericht na 5 seconden
      setTimeout(() => {
        alert.remove();
      }, 5000);

      // Reset het formulier
      form.reset();
    }
  </script>
</body>
</html>
