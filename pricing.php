<?php
session_start();
require 'config/db.php';

$prihlaseny_uzivatel = null;
$zobrazene_jmeno = '';

if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT jmeno, email FROM OSOBY WHERE id_osoba = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $prihlaseny_uzivatel = $stmt->fetch();

    if ($prihlaseny_uzivatel) {
        if ($prihlaseny_uzivatel['jmeno'] === 'Nezadáno') {
            $zobrazene_jmeno = explode('@', $prihlaseny_uzivatel['email'])[0];
        } else {
            $zobrazene_jmeno = htmlspecialchars($prihlaseny_uzivatel['jmeno']);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="cs">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ConsoleCloud - Pricing</title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/responsive.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Protest+Guerrilla&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <link rel="icon" href="img/logo.svg">
    <style>
        /* Styl pro falešnou platební bránu */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(5px);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .payment-modal {
            background: #111;
            border: 1px solid var(--clr-primary);
            border-radius: 12px;
            padding: 32px;
            width: 100%;
            max-width: 400px;
            color: white;
            box-shadow: 0 10px 40px rgba(142, 22, 22, 0.3);
        }

        .payment-modal h3 {
            margin-bottom: 24px;
            font-size: 24px;
            text-align: center;
        }

        .payment-input {
            width: 100%;
            padding: 12px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            color: white;
            margin-bottom: 16px;
            font-family: "Roboto", sans-serif;
        }

        .payment-row {
            display: flex;
            gap: 16px;
        }

        .close-modal {
            text-align: center;
            margin-top: 16px;
            color: #858891;
            cursor: pointer;
            font-size: 14px;
            transition: color 0.3s;
        }

        .close-modal:hover {
            color: white;
        }
    </style>
</head>

<body>
    <div class="background-container">
        <div class="background-hero-pattern"></div>
        <header class="header-container" id="header">
            <div class="header container">
                <div class="header-wrapper">
                    <a href="index.php" class="header-logo-wrapper">
                        <svg xmlns="http://www.w3.org/2000/svg" width="38" height="42" viewBox="0 0 48 32" fill="none">
                            <path
                                d="M40.9687 1.96116C41.0396 2.01977 41.1104 2.07838 41.1833 2.13877C45.8416 6.06864 47.3231 13.9292 47.9062 19.6799C47.9152 19.7649 47.9241 19.8499 47.9332 19.9375C48.0174 20.8591 48.0174 21.7809 48.0174 22.7055C48.0176 22.9827 48.0193 23.26 48.0211 23.5372C48.0282 25.931 47.8136 28.4633 46.2107 30.3773C45.2214 31.3565 44.182 31.6146 42.8371 31.6157C40.4746 31.5798 38.2852 30.1719 36.4687 28.7737C36.4059 28.7259 36.3431 28.6782 36.2783 28.629C35.3998 27.9611 34.5543 27.2801 33.75 26.5237C31.7633 24.7052 29.7348 23.7351 27.0937 23.2424C27.0283 23.23 26.9628 23.2175 26.8954 23.2047C25.0118 22.8663 22.7847 22.8876 20.9062 23.2424C20.8289 23.2565 20.7515 23.2707 20.6717 23.2852C18.0375 23.7815 16.1078 24.8524 14.1621 26.6643C13.6148 27.1734 13.035 27.6329 12.4418 28.0871C12.2898 28.2046 12.1397 28.3246 11.9897 28.4448C10.0357 29.9971 7.61446 31.5951 5.04492 31.6096C4.96099 31.6115 4.87706 31.6135 4.79058 31.6155C3.6589 31.622 2.71679 31.2405 1.87463 30.4787C1.70237 30.2938 1.55353 30.1041 1.40625 29.8987C1.32413 29.7855 1.32413 29.7855 1.24035 29.6701C0.858962 29.0852 0.636248 28.491 0.439449 27.8244C0.416721 27.7491 0.393994 27.6738 0.370578 27.5962C-0.0300161 26.1409 -0.0352431 24.6175 -0.0293015 23.1194C-0.0291306 23.0294 -0.0289597 22.9394 -0.0287837 22.8466C-0.0238865 21.3064 0.0591521 19.7997 0.281245 18.2737C0.30463 18.1077 0.30463 18.1077 0.328487 17.9383C0.846043 14.3447 1.75809 10.8332 3.1875 7.49241C3.21987 7.41617 3.25224 7.33993 3.28559 7.26137C3.74007 6.20425 4.30976 5.24722 4.96875 4.30491C5.00716 4.24982 5.04558 4.19472 5.08515 4.13796C6.02099 2.81568 7.12137 1.73073 8.53125 0.929908C8.5982 0.889907 8.66515 0.849905 8.73413 0.808692C10.8235 -0.351568 13.5204 -0.0294139 15.75 0.461158C15.8417 0.481249 15.9333 0.50134 16.0278 0.52204C16.6316 0.656115 17.2336 0.797387 17.8354 0.940207C21.763 1.95944 21.763 1.95944 25.778 1.89963C25.9247 1.87503 25.9247 1.87503 26.0745 1.84992C27.0721 1.67659 28.0522 1.44252 29.0342 1.19746C29.5442 1.07043 30.0549 0.946603 30.5658 0.822974C30.6644 0.799094 30.7631 0.775213 30.8647 0.750609C31.6147 0.570081 32.3655 0.401895 33.123 0.25608C33.2549 0.230259 33.2549 0.230259 33.3894 0.203917C36.119 -0.297848 38.8483 0.0675986 40.9687 1.96116ZM8.01892 4.24595C3.49094 8.94137 2.1742 17.2831 2.22326 23.5524C2.2667 26.3386 2.2667 26.3386 3.51965 28.7407C4.06003 29.2213 4.58242 29.2902 5.28186 29.2776C7.40437 29.0601 9.31179 27.5633 10.9169 26.2658C11.048 26.1603 11.1806 26.0566 11.3133 25.9531C11.745 25.6158 12.1551 25.2614 12.5566 24.8889C13.072 24.4107 13.6016 23.9563 14.1562 23.5237C14.2289 23.467 14.3015 23.4103 14.3763 23.3519C17.8753 20.7642 22.8165 20.3331 27 20.8987C29.4452 21.2878 31.8146 22.0164 33.75 23.6174C33.8059 23.6634 33.8618 23.7095 33.9194 23.7569C34.0751 23.8867 34.2292 24.0182 34.3828 24.1506C34.4384 24.1983 34.494 24.2459 34.5512 24.295C34.9355 24.6267 35.3079 24.9691 35.6777 25.3166C36.2547 25.8563 36.8641 26.337 37.5 26.8049C37.5513 26.8433 37.6026 26.8817 37.6555 26.9213C39.4135 28.2356 41.3969 29.5779 43.6875 29.3362C44.3118 29.1134 44.666 28.6654 44.974 28.0954C47.2574 23.1515 45.22 15.2957 43.5 10.3987C42.4338 7.52119 40.9486 4.26983 38.0109 2.89793C37.1476 2.54822 36.3283 2.41002 35.4023 2.40061C35.3197 2.39789 35.237 2.39517 35.1519 2.39237C33.4265 2.37701 31.6967 2.87646 30.0356 3.29274C25.2965 4.47523 21.7728 4.22993 17.0586 3.07957C13.8246 2.29181 10.6939 1.74051 8.01892 4.24595Z"
                                fill="white" />
                        </svg>
                        <h1 class="header-logo-text">Console<span style="color: var(--clr-primary);">Cloud</span></h1>
                    </a>
                    <ul class="header-links-wrapper">
                        <li class="header-link"><a href="index.php">Home</a></li>
                        <li class="header-link"><a href="pricing.php">Pricing</a></li>
                        <li class="header-link"><a href="contact.php">Contact</a></li>
                        <?php if ($prihlaseny_uzivatel): ?>
                            <li class="header-link"><a href="dashboard.php">Dashboard</a></li>
                        <?php endif; ?>
                    </ul>
                    <div class="header-actions-wrapper">
                        <?php if ($prihlaseny_uzivatel): ?>
                            <a href="dashboard.php"
                                style="color: var(--clr-primary); font-weight: 500; margin-right: 15px; text-decoration: none;">
                                Hello, <?= $zobrazene_jmeno ?>!
                            </a>
                            <a href="auth/logout.php" class="btn btn-secondary">Log out</a>
                        <?php else: ?>
                            <a href="login.php" class="btn btn-secondary">Log in</a>
                            <a href="register.php" class="btn btn-primary">Get Started</a>
                        <?php endif; ?>
                    </div>
                    <button class="burger-menu-btn" id="burgerBtn" aria-label="Toggle menu">
                        <span></span><span></span><span></span>
                    </button>
                </div>
            </div>

            <div class="mobile-menu" id="mobileMenu">
                <nav class="mobile-nav-links">
                    <a href="index.php" class="mobile-link">Home</a>
                    <a href="pricing.php" class="mobile-link">Pricing</a>
                    <a href="contact.php" class="mobile-link">Contact</a>
                </nav>
                <div class="mobile-actions">
                    <?php if ($prihlaseny_uzivatel): ?>
                        <a href="dashboard.php" class="btn btn-primary"
                            style="display: flex; width: 100%; justify-content: center; background: transparent; border: 1px solid var(--clr-primary);">Dashboard</a>
                        <a href="auth/logout.php" class="btn btn-secondary"
                            style="display: flex; width: 100%; justify-content: center;">Log out</a>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-secondary"
                            style="display: flex; width: 100%; justify-content: center;">Log in</a>
                        <a href="register.php" class="btn btn-primary"
                            style="display: flex; width: 100%; justify-content: center;">Get Started</a>
                    <?php endif; ?>
                </div>
            </div>
        </header>

        <div class="content">
            <section class="container pricing-container" style="margin-top: 64px;">
                <div class="info-box">Pricing</div>
                <h2 class="hero-text">Pricing made <span class="hero-gradient">easy</span></h2>
                <div class="pricing-cards-wrapper">
                    <div class="pricing-card">
                        <h4 class="pricing-card-title">By an hour</h4>
                        <div class="pricing-card-price">
                            <span class="price">$0.23</span>
                            <span class="price-period">per hour</span>
                        </div>
                        <ul class="pricing-card-features">
                            <li>Pay by an hour</li>
                            <li>Cancel after 24h</li>
                        </ul>
                        <?php if ($prihlaseny_uzivatel): ?>
                            <button class="btn-primary" style="width: 100%; justify-content: center;"
                                onclick="openPayment('Hourly', 0.23, 1)">Start renting</button>
                        <?php else: ?>
                            <a href="login.php" class="btn-primary"
                                style="display: flex; width: 100%; justify-content: center;">Log in to rent</a>
                        <?php endif; ?>
                    </div>

                    <div class="pricing-card">
                        <h4 class="pricing-card-title">Weekly</h4>
                        <div class="pricing-card-price">
                            <span class="price">$0.19</span>
                            <span class="price-period">per hour</span>
                        </div>
                        <ul class="pricing-card-features">
                            <li>Subscribe for a week</li>
                            <li>Pay in advance ($31.92)</li>
                        </ul>
                        <?php if ($prihlaseny_uzivatel): ?>
                            <button class="btn-primary" style="width: 100%; justify-content: center;"
                                onclick="openPayment('Weekly', 31.92, 7)">Start renting</button>
                        <?php else: ?>
                            <a href="login.php" class="btn-primary"
                                style="display: flex; width: 100%; justify-content: center;">Log in to rent</a>
                        <?php endif; ?>
                    </div>

                    <div class="pricing-card">
                        <h4 class="pricing-card-title">Monthly</h4>
                        <div class="pricing-card-price">
                            <span class="price">$0.16</span>
                            <span class="price-period">per hour</span>
                        </div>
                        <ul class="pricing-card-features">
                            <li>Subscribe for a month</li>
                            <li>Pay in advance ($115.20)</li>
                        </ul>
                        <?php if ($prihlaseny_uzivatel): ?>
                            <button class="btn-primary" style="width: 100%; justify-content: center;"
                                onclick="openPayment('Monthly', 115.20, 30)">Start renting</button>
                        <?php else: ?>
                            <a href="login.php" class="btn-primary"
                                style="display: flex; width: 100%; justify-content: center;">Log in to rent</a>
                        <?php endif; ?>
                    </div>

                    <div class="pricing-card">
                        <h4 class="pricing-card-title">Enterprise/custom</h4>
                        <div class="pricing-card-price">
                            <span class="price">Let's talk</span>
                            <span class="price-period">custom pricing</span>
                        </div>
                        <ul class="pricing-card-features">
                            <li>Dedicated hardware</li>
                            <li>24/7 Support</li>
                        </ul>
                        <a href="contact.php" class="btn-primary"
                            style="display: flex; width: 100%; justify-content: center;">Contact sales</a>
                    </div>
                </div>
            </section>
            <footer class="footer-section">
                <div class="container">
                    <div class="footer-content">
                        <div class="footer-col brand-col">
                            <a href="" class="header-logo-wrapper">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="34" viewBox="0 0 48 32"
                                    fill="none">
                                    <path
                                        d="M40.9687 1.96116C41.0396 2.01977 41.1104 2.07838 41.1833 2.13877C45.8416 6.06864 47.3231 13.9292 47.9062 19.6799C47.9152 19.7649 47.9241 19.8499 47.9332 19.9375C48.0174 20.8591 48.0174 21.7809 48.0174 22.7055C48.0176 22.9827 48.0193 23.26 48.0211 23.5372C48.0282 25.931 47.8136 28.4633 46.2107 30.3773C45.2214 31.3565 44.182 31.6146 42.8371 31.6157C40.4746 31.5798 38.2852 30.1719 36.4687 28.7737C36.4059 28.7259 36.3431 28.6782 36.2783 28.629C35.3998 27.9611 34.5543 27.2801 33.75 26.5237C31.7633 24.7052 29.7348 23.7351 27.0937 23.2424C27.0283 23.23 26.9628 23.2175 26.8954 23.2047C25.0118 22.8663 22.7847 22.8876 20.9062 23.2424C20.8289 23.2565 20.7515 23.2707 20.6717 23.2852C18.0375 23.7815 16.1078 24.8524 14.1621 26.6643C13.6148 27.1734 13.035 27.6329 12.4418 28.0871C12.2898 28.2046 12.1397 28.3246 11.9897 28.4448C10.0357 29.9971 7.61446 31.5951 5.04492 31.6096C4.96099 31.6115 4.87706 31.6135 4.79058 31.6155C3.6589 31.622 2.71679 31.2405 1.87463 30.4787C1.70237 30.2938 1.55353 30.1041 1.40625 29.8987C1.32413 29.7855 1.32413 29.7855 1.24035 29.6701C0.858962 29.0852 0.636248 28.491 0.439449 27.8244C0.416721 27.7491 0.393994 27.6738 0.370578 27.5962C-0.0300161 26.1409 -0.0352431 24.6175 -0.0293015 23.1194C-0.0291306 23.0294 -0.0289597 22.9394 -0.0287837 22.8466C-0.0238865 21.3064 0.0591521 19.7997 0.281245 18.2737C0.30463 18.1077 0.30463 18.1077 0.328487 17.9383C0.846043 14.3447 1.75809 10.8332 3.1875 7.49241C3.21987 7.41617 3.25224 7.33993 3.28559 7.26137C3.74007 6.20425 4.30976 5.24722 4.96875 4.30491C5.00716 4.24982 5.04558 4.19472 5.08515 4.13796C6.02099 2.81568 7.12137 1.73073 8.53125 0.929908C8.5982 0.889907 8.66515 0.849905 8.73413 0.808692C10.8235 -0.351568 13.5204 -0.0294139 15.75 0.461158C15.8417 0.481249 15.9333 0.50134 16.0278 0.52204C16.6316 0.656115 17.2336 0.797387 17.8354 0.940207C21.763 1.95944 21.763 1.95944 25.778 1.89963C25.9247 1.87503 25.9247 1.87503 26.0745 1.84992C27.0721 1.67659 28.0522 1.44252 29.0342 1.19746C29.5442 1.07043 30.0549 0.946603 30.5658 0.822974C30.6644 0.799094 30.7631 0.775213 30.8647 0.750609C31.6147 0.570081 32.3655 0.401895 33.123 0.25608C33.2549 0.230259 33.2549 0.230259 33.3894 0.203917C36.119 -0.297848 38.8483 0.0675986 40.9687 1.96116ZM8.01892 4.24595C3.49094 8.94137 2.1742 17.2831 2.22326 23.5524C2.2667 26.3386 2.2667 26.3386 3.51965 28.7407C4.06003 29.2213 4.58242 29.2902 5.28186 29.2776C7.40437 29.0601 9.31179 27.5633 10.9169 26.2658C11.048 26.1603 11.1806 26.0566 11.3133 25.9531C11.745 25.6158 12.1551 25.2614 12.5566 24.8889C13.072 24.4107 13.6016 23.9563 14.1562 23.5237C14.2289 23.467 14.3015 23.4103 14.3763 23.3519C17.8753 20.7642 22.8165 20.3331 27 20.8987C29.4452 21.2878 31.8146 22.0164 33.75 23.6174C33.8059 23.6634 33.8618 23.7095 33.9194 23.7569C34.0751 23.8867 34.2292 24.0182 34.3828 24.1506C34.4384 24.1983 34.494 24.2459 34.5512 24.295C34.9355 24.6267 35.3079 24.9691 35.6777 25.3166C36.2547 25.8563 36.8641 26.337 37.5 26.8049C37.5513 26.8433 37.6026 26.8817 37.6555 26.9213C39.4135 28.2356 41.3969 29.5779 43.6875 29.3362C44.3118 29.1134 44.666 28.6654 44.974 28.0954C47.2574 23.1515 45.22 15.2957 43.5 10.3987C42.4338 7.52119 40.9486 4.26983 38.0109 2.89793C37.1476 2.54822 36.3283 2.41002 35.4023 2.40061C35.3197 2.39789 35.237 2.39517 35.1519 2.39237C33.4265 2.37701 31.6967 2.87646 30.0356 3.29274C25.2965 4.47523 21.7728 4.22993 17.0586 3.07957C13.8246 2.29181 10.6939 1.74051 8.01892 4.24595Z"
                                        fill="white" />
                                </svg>
                                <span class="header-logo-text">
                                    Console<span style="color: var(--clr-primary);">Cloud</span>
                                </span>
                            </a>
                            <p class="footer-desc">
                                High-performance cloud gaming available instantly on any device.
                            </p>
                        </div>

                        <div class="footer-col">
                            <h4 class="footer-title">Platform</h4>
                            <ul class="footer-links">
                                <li><a href="#">How it works</a></li>
                                <li><a href="#">Pricing</a></li>
                                <li><a href="#">Games</a></li>
                                <li><a href="#">Server Status</a></li>
                            </ul>
                        </div>

                        <div class="footer-col">
                            <h4 class="footer-title">Company</h4>
                            <ul class="footer-links">
                                <li><a href="#">Contact</a></li>
                                <li><a href="#">Blog</a></li>
                                <li><a href="#">Careers</a></li>
                            </ul>
                        </div>

                        <div class="footer-col">
                            <h4 class="footer-title">Legal</h4>
                            <ul class="footer-links">
                                <li><a href="#">Privacy Policy</a></li>
                                <li><a href="#">Terms of Service</a></li>
                                <li><a href="#">Cookie Policy</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="footer-bottom">
                        <p>&copy; 2026 ConsoleCloud. All rights reserved.</p>
                        <div class="social-links">
                            <a href="#" aria-label="Instagram">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                                    <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                                    <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                                </svg>
                            </a>
                            <a href="#" aria-label="Twitter">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path
                                        d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z">
                                    </path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <div class="modal-overlay" id="paymentModal">
        <div class="payment-modal">
            <h3>Secure Checkout</h3>
            <p style="text-align: center; color: #858891; margin-bottom: 24px;">Selected Plan: <strong
                    id="planNameDisplay" style="color: white;"></strong> - <strong id="planPriceDisplay"
                    style="color: var(--clr-primary);"></strong></p>

            <form action="actions/process_payment.php" method="POST">
                <input type="hidden" name="plan_name" id="planNameInput">
                <input type="hidden" name="plan_price" id="planPriceInput">
                <input type="hidden" name="plan_days" id="planDaysInput">

                <input type="text" class="payment-input" placeholder="Cardholder Name" required value="John Doe">
                <input type="text" class="payment-input" placeholder="Card Number (Fake)" required
                    value="4532 1123 8976 5432" maxlength="19">
                <div class="payment-row">
                    <input type="text" class="payment-input" placeholder="MM/YY" required value="12/26" maxlength="5">
                    <input type="text" class="payment-input" placeholder="CVC" required value="123" maxlength="3">
                </div>

                <button type="submit" class="btn-primary"
                    style="width: 100%; justify-content: center; margin-top: 8px;">
                    Pay & Rent Console
                </button>
            </form>
            <div class="close-modal" onclick="closePayment()">Cancel</div>
        </div>
    </div>

    <script src="main.js"></script>
    <script>
        function openPayment(planName, price, days) {
            document.getElementById('paymentModal').style.display = 'flex';
            document.getElementById('planNameDisplay').innerText = planName;
            document.getElementById('planPriceDisplay').innerText = '$' + price;

            document.getElementById('planNameInput').value = planName;
            document.getElementById('planPriceInput').value = price;
            document.getElementById('planDaysInput').value = days;
        }

        function closePayment() {
            document.getElementById('paymentModal').style.display = 'none';
        }
    </script>
</body>

</html>