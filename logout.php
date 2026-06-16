<?php
// Pokrećemo sesiju kako bismo je mogli uništiti
session_start();
// Brišemo sve sesijske varijable (npr. $_SESSION['user'], $_SESSION['cart'])
session_unset();
// Uništavamo sesiju i briše cookie
session_destroy();
// Vraćamo korisnika na početnu stranicu
header('Location: index.php');
exit;