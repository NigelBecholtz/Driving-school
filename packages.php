<?php
session_start();
// Include config file
require_once "includes/config.php";

// Fetch active packages
$sql = "SELECT * FROM packages WHERE active = 1 ORDER BY type, price ASC";
$packages = $pdo->query($sql)->fetchAll();

// Groepeer pakketten per type
$auto_packages = array_filter($packages, function($package) {
    return $package['type'] === 'auto';
});
$motor_packages = array_filter($packages, function($package) {
    return $package['type'] === 'motor';
});
?>

<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pakketten - RijVaardig Academy</title>
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

      <!-- Hero Section -->
      <div class="hero min-h-[40vh] bg-base-200">
        <div class="hero-content text-center">
          <div class="max-w-2xl">
            <h1 class="text-5xl font-bold">Onze Pakketten</h1>
            <p class="py-6">Kies het pakket dat het beste bij jou past. Al onze pakketten worden verzorgd door ervaren instructeurs en zijn inclusief persoonlijke begeleiding.</p>
          </div>
        </div>
      </div>

      <!-- Page content -->
      <div class="container mx-auto px-4 py-16">
        <!-- Auto pakketten -->
        <h2 class="text-3xl font-bold mb-8">Autopakketten</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
            <?php foreach($auto_packages as $package): ?>
                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body">
                        <h2 class="card-title"><?php echo htmlspecialchars($package['name']); ?></h2>
                        <div class="text-3xl font-bold text-primary my-4">
                            €<?php echo number_format($package['price'], 2); ?>
                        </div>
                        <div class="space-y-2 mb-4">
                            <div class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span><?php echo $package['lessons']; ?> lessen van <?php echo $package['duration_minutes']; ?> minuten</span>
                            </div>
                            <?php 
                            $description_points = explode("\n", $package['description']);
                            foreach($description_points as $point):
                                if(trim($point)):
                            ?>
                                <div class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    <span><?php echo htmlspecialchars(trim($point, "- \t\n\r\0\x0B")); ?></span>
                                </div>
                            <?php 
                                endif;
                            endforeach; 
                            ?>
                        </div>
                        <div class="card-actions justify-end">
                            <?php if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true): ?>
                                <form method="POST" action="order-package.php">
                                    <input type="hidden" name="package_id" value="<?php echo $package['id']; ?>">
                                    <button type="submit" name="order_package" class="btn btn-primary">Pakket Bestellen</button>
                                </form>
                            <?php else: ?>
                                <a href="register.php?redirect=packages.php&package_id=<?php echo $package['id']; ?>" class="btn btn-primary">Account Aanmaken om te Bestellen</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Motor pakketten -->
        <h2 class="text-3xl font-bold mb-8">Motorpakketten</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach($motor_packages as $package): ?>
                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body">
                        <h2 class="card-title"><?php echo htmlspecialchars($package['name']); ?></h2>
                        <div class="text-3xl font-bold text-primary my-4">
                            €<?php echo number_format($package['price'], 2); ?>
                        </div>
                        <div class="space-y-2 mb-4">
                            <div class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span><?php echo $package['lessons']; ?> lessen van <?php echo $package['duration_minutes']; ?> minuten</span>
                            </div>
                            <?php 
                            $description_points = explode("\n", $package['description']);
                            foreach($description_points as $point):
                                if(trim($point)):
                            ?>
                                <div class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    <span><?php echo htmlspecialchars(trim($point, "- \t\n\r\0\x0B")); ?></span>
                                </div>
                            <?php 
                                endif;
                            endforeach; 
                            ?>
                        </div>
                        <div class="card-actions justify-end">
                            <?php if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true): ?>
                                <form method="POST" action="order-package.php">
                                    <input type="hidden" name="package_id" value="<?php echo $package['id']; ?>">
                                    <button type="submit" name="order_package" class="btn btn-primary">Pakket Bestellen</button>
                                </form>
                            <?php else: ?>
                                <a href="register.php?redirect=packages.php&package_id=<?php echo $package['id']; ?>" class="btn btn-primary">Account Aanmaken om te Bestellen</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
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
        <li><a href="/packages.php" class="active">Pakketten</a></li>
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

  <script src="assets/js/main.js"></script>
</body>
</html>
