<!DOCTYPE html>
<html lang="nl" data-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>RijVaardig Academy - Afrekenen</title>
  <link href="https://cdn.jsdelivr.net/npm/daisyui@latest/dist/full.css" rel="stylesheet" type="text/css" />
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css">
  <link rel="stylesheet" href="assets/css/index.css">
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/pages.css">
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
      <div class="container mx-auto py-16 px-4">
        <div class="max-w-3xl mx-auto">
          <h1 class="text-4xl font-bold text-center mb-12 animate-fadeInUp">Rond uw bestelling af</h1>
          
          <div class="w-full bg-base-200 rounded-full h-2.5 mb-8">
            <div class="bg-primary h-2.5 rounded-full transition-all duration-500" style="width: 0%" id="checkoutProgress"></div>
          </div>
          
          <div class="card bg-base-100 shadow-xl mb-8 checkout-card">
            <div class="card-body">
              <h2 class="text-2xl font-semibold mb-4">Gekozen pakket: <span id="packageName">Laden...</span></h2>
              <p class="text-xl mb-4">Prijs: <span id="packagePrice">Laden...</span></p>
              
              <form class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div class="form-control">
                    <label class="label">
                      <span class="label-text">Voornaam</span>
                    </label>
                    <input type="text" class="input input-bordered hover-grow" required />
                  </div>
                  
                  <div class="form-control">
                    <label class="label">
                      <span class="label-text">Achternaam</span>
                    </label>
                    <input type="text" class="input input-bordered hover-grow" required />
                  </div>
                </div>

                <div class="form-control">
                  <label class="label">
                    <span class="label-text">E-mailadres</span>
                  </label>
                  <input type="email" class="input input-bordered hover-grow" required />
                </div>

                <div class="form-control">
                  <label class="label">
                    <span class="label-text">Telefoonnummer</span>
                  </label>
                  <input type="tel" class="input input-bordered hover-grow" required />
                </div>

                <div class="divider">Betaalgegevens</div>

                <div class="form-control">
                  <label class="label">
                    <span class="label-text">Betaalmethode</span>
                  </label>
                  <select class="select select-bordered" id="paymentMethod" onchange="togglePaymentFields()">
                    <option value="ideal">iDEAL</option>
                    <option value="credit">Creditcard</option>
                  </select>
                </div>

                <div id="idealFields">
                  <div class="form-control">
                    <label class="label">
                      <span class="label-text">Kies uw bank</span>
                    </label>
                    <select class="select select-bordered">
                      <option>ING</option>
                      <option>ABN AMRO</option>
                      <option>Rabobank</option>
                      <option>SNS Bank</option>
                      <option>ASN Bank</option>
                      <option>Bunq</option>
                      <option>Knab</option>
                    </select>
                  </div>
                </div>

                <div id="creditFields" class="hidden">
                  <div class="form-control">
                    <label class="label">
                      <span class="label-text">Kaartnummer</span>
                    </label>
                    <input type="text" class="input input-bordered hover-grow" placeholder="1234 5678 9012 3456" />
                  </div>

                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-control">
                      <label class="label">
                        <span class="label-text">Vervaldatum</span>
                      </label>
                      <input type="text" class="input input-bordered hover-grow" placeholder="MM/JJ" />
                    </div>
                    
                    <div class="form-control">
                      <label class="label">
                        <span class="label-text">CVV</span>
                      </label>
                      <input type="text" class="input input-bordered hover-grow" placeholder="123" />
                    </div>
                  </div>
                </div>

                <div class="form-control mt-6">
                  <button type="submit" class="btn btn-primary">Afronden</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>

      <!-- Footer -->
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

  <script>
    // Get package details from URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    const packageName = urlParams.get('package');
    const packagePrice = urlParams.get('price');

    // Update the page with package details
    document.getElementById('packageName').textContent = packageName || 'Pakket niet geselecteerd';
    document.getElementById('packagePrice').textContent = packagePrice || 'Prijs niet beschikbaar';
  </script>

  <style>
    .checkout-card {
      animation: slideInAndFade 0.8s ease-out;
    }
    
    @keyframes slideInAndFade {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    
    body {
      animation: fadeIn 0.5s ease-in-out;
    }
    
    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }
  </style>

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

    // Progress bar handling
    const formInputs = document.querySelectorAll('form input, form textarea');
    const progressBar = document.getElementById('checkoutProgress');

    formInputs.forEach(input => {
      input.addEventListener('input', updateProgress);
    });

    function updateProgress() {
      const totalFields = formInputs.length;
      const filledFields = Array.from(formInputs).filter(input => input.value.trim() !== '').length;
      const progress = (filledFields / totalFields) * 100;
      progressBar.style.width = `${progress}%`;
    }

    // Payment method toggle
    function togglePaymentFields() {
      const method = document.getElementById('paymentMethod').value;
      const idealFields = document.getElementById('idealFields');
      const creditFields = document.getElementById('creditFields');
      
      if (method === 'ideal') {
        idealFields.classList.remove('hidden');
        creditFields.classList.add('hidden');
      } else {
        idealFields.classList.add('hidden');
        creditFields.classList.remove('hidden');
      }
    }
  </script>

  <script src="assets/js/main.js"></script>
</body>
</html> 