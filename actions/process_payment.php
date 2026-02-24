<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require '../config/db.php';

// ... zbytek kódu

if (!isset($_SESSION['user_id'])) {
    die("Musíte být přihlášeni.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $plan_name = $_POST['plan_name'];
    $plan_price = (float) $_POST['plan_price'];
    $plan_days = (int) $_POST['plan_days']; // Na kolik dní se to pronajímá

    try {
        // Zabezpečíme to transakcí, aby se neudělalo jen půlka věcí
        $pdo->beginTransaction();

        // 1. Najdeme první DOSTUPNOU konzoli (uzamkneme si ji přes FOR UPDATE, aby ji ve stejný moment nekoupil někdo jiný)
        $stmt_konzole = $pdo->prepare("SELECT id_konzole FROM KONZOLE WHERE stav = 'DOSTUPNA' LIMIT 1 FOR UPDATE");
        $stmt_konzole->execute();
        $konzole = $stmt_konzole->fetch();

        if (!$konzole) {
            throw new Exception("Omlouváme se, aktuálně nejsou k dispozici žádné volné konzole.");
        }
        $id_konzole = $konzole['id_konzole'];

        // 2. Pro zjednodušení si vytvoříme/najdeme PREDPLATNE pro tento plán
        $stmt_predplatne = $pdo->prepare("SELECT id_predplatne FROM PREDPLATNE WHERE nazev = ? LIMIT 1");
        $stmt_predplatne->execute([$plan_name]);
        $predplatne = $stmt_predplatne->fetch();

        if ($predplatne) {
            $id_predplatne = $predplatne['id_predplatne'];
        } else {
            // Pokud takové předplatné ještě v DB neexistuje, vytvoříme ho
            $stmt_new_plan = $pdo->prepare("INSERT INTO PREDPLATNE (nazev, cena, delka_planu) VALUES (?, ?, ?)");
            $stmt_new_plan->execute([$plan_name, $plan_price, $plan_days]);
            $id_predplatne = $pdo->lastInsertId();
        }

        // 3. Vytvoříme záznam o pronájmu
        // datum_konce_plan vypočítáme podle počtu dnů plánu
        $datum_zacatku = date('Y-m-d H:i:s');
        $datum_konce_plan = date('Y-m-d H:i:s', strtotime("+$plan_days days"));

        $stmt_pronajem = $pdo->prepare("
            INSERT INTO PRONAJMY 
            (datum_zacatku, datum_konce_plan, celkova_cena, stav_pronajmu, UZIVATELE_id_osoba, KONZOLE_id_konzole, PREDPLATNE_id_predplatne) 
            VALUES (?, ?, ?, 'AKTIVNI', ?, ?, ?)
        ");
        $stmt_pronajem->execute([$datum_zacatku, $datum_konce_plan, $plan_price, $user_id, $id_konzole, $id_predplatne]);
        $id_pronajem = $pdo->lastInsertId();

        // 4. Zapíšeme falešnou platbu do tabulky PLATBY
        $stmt_platba = $pdo->prepare("INSERT INTO PLATBY (datum, castka, metoda, PRONAJMY_id_pronajem) VALUES (?, ?, 'Karta (Falešná)', ?)");
        $stmt_platba->execute([$datum_zacatku, $plan_price, $id_pronajem]);

        // 5. Změníme stav konzole na OBSAZENA
        $stmt_update_konzole = $pdo->prepare("UPDATE KONZOLE SET stav = 'OBSAZENA' WHERE id_konzole = ?");
        $stmt_update_konzole->execute([$id_konzole]);

        // Vše proběhlo v pořádku, uložíme to do DB
        $pdo->commit();

        // Přesměrujeme na dashboard, kde uživatel rovnou uvidí svou novou konzoli
        header("Location: ../dashboard.php?success=1");
        exit;

    } catch (Exception $e) {
        $pdo->rollBack();
        die("Chyba platby: " . $e->getMessage());
    }
}
?>