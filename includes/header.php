<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<div class="navbar bg-base-300">
    <div class="flex-1">
        <a href="index.php" class="btn btn-ghost normal-case text-xl">RijVaardig Academy</a>
    </div>
    <div class="flex-none">
        <ul class="menu menu-horizontal px-1">
            <li><a href="index.php">Home</a></li>
            <?php if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true): ?>
                <li><a href="dashboard.php">Mijn Account</a></li>
                <li><a href="logout.php">Uitloggen</a></li>
            <?php else: ?>
                <li><a href="login.php">Login</a></li>
                <li><a href="register.php">Registreren</a></li>
            <?php endif; ?>
            <?php if($_SESSION["role"] === "instructor"): ?>
                <li><a href="my-students.php">Mijn Studenten</a></li>
                <li><a href="instructor-schedule.php">Mijn Rooster</a></li>
                <li><a href="manage-lessons.php">Lessen Beheren</a></li>
                <li><a href="manage-availability.php">Beschikbaarheid</a></li>
            <?php endif; ?>
            <?php if($_SESSION["role"] === "admin"): ?>
                <li><a href="admin-dashboard.php">Dashboard</a></li>
                <li><a href="manage-progress-items.php">Voortgangspunten</a></li>
            <?php endif; ?>
        </ul>
    </div>
</div> 