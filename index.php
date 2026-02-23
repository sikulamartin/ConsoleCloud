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
    <title>ConsoleCloud</title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/responsive.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Protest+Guerrilla&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <link rel="icon" href="img/logo.svg">
    <script src="https://unpkg.com/@lottiefiles/dotlottie-wc@0.8.5/dist/dotlottie-wc.js" type="module"></script>
</head>

<body>
    <div class="background-container">
        <div class="background-hero-pattern">
        </div>
        <header class="header-container" id="header">
            <div class="header container">
                <div class="header-wrapper">
                    <a href="index.php" class="header-logo-wrapper">
                        <svg xmlns="http://www.w3.org/2000/svg" width="38" height="42" viewBox="0 0 48 32" fill="none">
                            <path
                                d="M40.9687 1.96116C41.0396 2.01977 41.1104 2.07838 41.1833 2.13877C45.8416 6.06864 47.3231 13.9292 47.9062 19.6799C47.9152 19.7649 47.9241 19.8499 47.9332 19.9375C48.0174 20.8591 48.0174 21.7809 48.0174 22.7055C48.0176 22.9827 48.0193 23.26 48.0211 23.5372C48.0282 25.931 47.8136 28.4633 46.2107 30.3773C45.2214 31.3565 44.182 31.6146 42.8371 31.6157C40.4746 31.5798 38.2852 30.1719 36.4687 28.7737C36.4059 28.7259 36.3431 28.6782 36.2783 28.629C35.3998 27.9611 34.5543 27.2801 33.75 26.5237C31.7633 24.7052 29.7348 23.7351 27.0937 23.2424C27.0283 23.23 26.9628 23.2175 26.8954 23.2047C25.0118 22.8663 22.7847 22.8876 20.9062 23.2424C20.8289 23.2565 20.7515 23.2707 20.6717 23.2852C18.0375 23.7815 16.1078 24.8524 14.1621 26.6643C13.6148 27.1734 13.035 27.6329 12.4418 28.0871C12.2898 28.2046 12.1397 28.3246 11.9897 28.4448C10.0357 29.9971 7.61446 31.5951 5.04492 31.6096C4.96099 31.6115 4.87706 31.6135 4.79058 31.6155C3.6589 31.622 2.71679 31.2405 1.87463 30.4787C1.70237 30.2938 1.55353 30.1041 1.40625 29.8987C1.32413 29.7855 1.32413 29.7855 1.24035 29.6701C0.858962 29.0852 0.636248 28.491 0.439449 27.8244C0.416721 27.7491 0.393994 27.6738 0.370578 27.5962C-0.0300161 26.1409 -0.0352431 24.6175 -0.0293015 23.1194C-0.0291306 23.0294 -0.0289597 22.9394 -0.0287837 22.8466C-0.0238865 21.3064 0.0591521 19.7997 0.281245 18.2737C0.30463 18.1077 0.30463 18.1077 0.328487 17.9383C0.846043 14.3447 1.75809 10.8332 3.1875 7.49241C3.21987 7.41617 3.25224 7.33993 3.28559 7.26137C3.74007 6.20425 4.30976 5.24722 4.96875 4.30491C5.00716 4.24982 5.04558 4.19472 5.08515 4.13796C6.02099 2.81568 7.12137 1.73073 8.53125 0.929908C8.5982 0.889907 8.66515 0.849905 8.73413 0.808692C10.8235 -0.351568 13.5204 -0.0294139 15.75 0.461158C15.8417 0.481249 15.9333 0.50134 16.0278 0.52204C16.6316 0.656115 17.2336 0.797387 17.8354 0.940207C21.763 1.95944 21.763 1.95944 25.778 1.89963C25.9247 1.87503 25.9247 1.87503 26.0745 1.84992C27.0721 1.67659 28.0522 1.44252 29.0342 1.19746C29.5442 1.07043 30.0549 0.946603 30.5658 0.822974C30.6644 0.799094 30.7631 0.775213 30.8647 0.750609C31.6147 0.570081 32.3655 0.401895 33.123 0.25608C33.2549 0.230259 33.2549 0.230259 33.3894 0.203917C36.119 -0.297848 38.8483 0.0675986 40.9687 1.96116ZM8.01892 4.24595C3.49094 8.94137 2.1742 17.2831 2.22326 23.5524C2.2667 26.3386 2.2667 26.3386 3.51965 28.7407C4.06003 29.2213 4.58242 29.2902 5.28186 29.2776C7.40437 29.0601 9.31179 27.5633 10.9169 26.2658C11.048 26.1603 11.1806 26.0566 11.3133 25.9531C11.745 25.6158 12.1551 25.2614 12.5566 24.8889C13.072 24.4107 13.6016 23.9563 14.1562 23.5237C14.2289 23.467 14.3015 23.4103 14.3763 23.3519C17.8753 20.7642 22.8165 20.3331 27 20.8987C29.4452 21.2878 31.8146 22.0164 33.75 23.6174C33.8059 23.6634 33.8618 23.7095 33.9194 23.7569C34.0751 23.8867 34.2292 24.0182 34.3828 24.1506C34.4384 24.1983 34.494 24.2459 34.5512 24.295C34.9355 24.6267 35.3079 24.9691 35.6777 25.3166C36.2547 25.8563 36.8641 26.337 37.5 26.8049C37.5513 26.8433 37.6026 26.8817 37.6555 26.9213C39.4135 28.2356 41.3969 29.5779 43.6875 29.3362C44.3118 29.1134 44.666 28.6654 44.974 28.0954C47.2574 23.1515 45.22 15.2957 43.5 10.3987C42.4338 7.52119 40.9486 4.26983 38.0109 2.89793C37.1476 2.54822 36.3283 2.41002 35.4023 2.40061C35.3197 2.39789 35.237 2.39517 35.1519 2.39237C33.4265 2.37701 31.6967 2.87646 30.0356 3.29274C25.2965 4.47523 21.7728 4.22993 17.0586 3.07957C13.8246 2.29181 10.6939 1.74051 8.01892 4.24595Z"
                                fill="white" />
                        </svg>
                        <h1 class="header-logo-text">
                            Console<span style="color: var(--clr-primary);">Cloud</span>
                        </h1>
                    </a>
                    <ul class="header-links-wrapper">
                        <li class="header-link"><a href="pricing.php">Pricing</a></li>
                        <li class="header-link"><a href="contact.php">Contact</a></li>
                        <?php if ($prihlaseny_uzivatel): ?>
                            <li class="header-link"><a href="dashboard.php">Dashboard</a></li>
                        <?php endif; ?>
                    </ul>
                    <div class="header-actions-wrapper">
                        <?php if ($prihlaseny_uzivatel): ?>
    <a href="profile.php" style="color: white; font-weight: 500; margin-right: 15px; text-decoration: none; transition: color 0.3s ease;" onmouseover="this.style.color='var(--clr-primary)'" onmouseout="this.style.color='white'">
        Hello, <?= $zobrazene_jmeno ?>!
    </a>
    <a href="auth/logout.php" class="btn btn-secondary">Log out</a>
<?php else: ?>
    <a href="login.php" class="btn btn-secondary">Log in</a>
    <a href="register.php" class="btn btn-primary">
        Get Started
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
            <path d="M1 7.72705H15" stroke="white" stroke-linecap="round" stroke-linejoin="round" />
            <path d="M9.27246 2L14.9997 7.72727L9.27246 13.4545" stroke="white" stroke-width="1.27273" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
    </a>
<?php endif; ?>
                    </div>


                    <button class="burger-menu-btn" id="burgerBtn" aria-label="Toggle menu">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                </div>
            </div>

            <div class="mobile-menu" id="mobileMenu">
                <nav class="mobile-nav-links">
                    <a href="pricing.php" class="mobile-link">Pricing</a>
                    <a href="contact.php" class="mobile-link">Contact</a>
                    <?php if ($prihlaseny_uzivatel): ?>
                            <a href="dashboard.php" class="mobile-link">Dashboard</a>
                        <?php endif; ?>
                </nav>
                <div class="mobile-actions">
                    <?php if ($prihlaseny_uzivatel): ?>
    <a href="dashboard.php" class="btn btn-primary" style="display: flex; width: 100%; justify-content: center; background: transparent; border: 1px solid var(--clr-primary);">
        Dashboard (<?= $zobrazene_jmeno ?>)
    </a>
    <a href="auth/logout.php" class="btn btn-secondary" style="display: flex; width: 100%; justify-content: center;">Log out</a>
<?php else: ?>
    <a href="login.php" class="btn btn-secondary" style="display: flex; width: 100%; justify-content: center;">Log in</a>
    <a href="register.php" class="btn btn-primary" style="display: flex; width: 100%; justify-content: center;">Get Started</a>
<?php endif; ?>
                </div>
            </div>
        </header>
        <div class="content">
            <!--<dotlottie-wc src="https://lottie.host/3d890076-bf21-428d-8df5-4b778136b06d/k5dyiTIPV8.lottie"
                style="width: 750px;height: 750px" autoplay loop></dotlottie-wc>-->
            <section class="hero-container">
                <div class="info-box">
                    Low console availability
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M7.6013 1.2C4.06596 1.2 1.2 4.06596 1.2 7.6013C1.2 11.1367 4.06596 14.0026 7.6013 14.0026C11.1367 14.0026 14.0026 11.1367 14.0026 7.6013C14.0026 4.06596 11.1367 1.2 7.6013 1.2ZM0 7.6013C0 3.40322 3.40322 0 7.6013 0C11.7994 0 15.2026 3.40322 15.2026 7.6013C15.2026 11.7994 11.7994 15.2026 7.6013 15.2026C3.40322 15.2026 0 11.7994 0 7.6013ZM6.41406 7.01992C6.41406 6.68855 6.68269 6.41992 7.01406 6.41992H7.5975C7.92887 6.41992 8.1975 6.68855 8.1975 7.01992V10.5043C8.52122 10.513 8.78094 10.7782 8.78094 11.1041C8.78094 11.4354 8.51232 11.7041 8.18094 11.7041H7.5975C7.26613 11.7041 6.9975 11.4354 6.9975 11.1041V7.6197C6.67379 7.61092 6.41406 7.34575 6.41406 7.01992ZM8.33235 4.39274C8.33235 4.87608 7.94053 5.2679 7.45719 5.2679C6.97385 5.2679 6.58203 4.87608 6.58203 4.39274C6.58203 3.9094 6.97385 3.51758 7.45719 3.51758C7.94053 3.51758 8.33235 3.9094 8.33235 4.39274Z"
                            fill="#B7B7B7" />
                    </svg>
                </div>
                <h2 class="hero-text">
                    PLAY <span class="hero-gradient">EXCLUSIVE</span></br>games in minutes
                </h2>
                <p class="hero-subtext">
                    Your favorite games, ready to play in minutes — from timeless classics to exclusive</br>new
                    releases,
                    all available instantly on our high-performance servers.
                </p>
                <button class="btn-primary">
                    View pricing plans <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M10.3069 10.3415C10.1531 10.4953 10.1531 10.7439 10.3069 10.8977L12.6668 13.2576C12.7435 13.3343 12.8442 13.3728 12.9449 13.3728C13.0456 13.3728 13.1463 13.3343 13.223 13.2576C13.3767 13.1038 13.3767 12.8552 13.223 12.7014L10.8631 10.3415C10.7093 10.1877 10.4607 10.1877 10.3069 10.3415ZM9.28978 11.9148C9.13599 11.761 8.88741 11.761 8.73363 11.9148C8.57984 12.0686 8.57984 12.3171 8.73363 12.4709L11.0935 14.8308C11.1702 14.9075 11.2709 14.9461 11.3716 14.9461C11.4723 14.9461 11.573 14.9075 11.6497 14.8308C11.8035 14.6771 11.8035 14.4285 11.6497 14.2747L9.28978 11.9148ZM7.71651 13.4881C7.56272 13.3343 7.31414 13.3343 7.16035 13.4881C7.00657 13.6418 7.00657 13.8904 7.16035 14.0442L9.52026 16.4041C9.59696 16.4808 9.69765 16.5194 9.79834 16.5194C9.89903 16.5194 9.99972 16.4808 10.0764 16.4041C10.2302 16.2503 10.2302 16.0017 10.0764 15.848L7.71651 13.4881ZM15.142 12.5862L10.9783 8.42251L5.24136 14.1594L9.40502 18.3231L15.142 12.5862ZM11.2564 7.58828L15.9762 12.3081C16.13 12.4619 16.13 12.7105 15.9762 12.8643L9.6831 19.1573C9.6064 19.234 9.50571 19.2726 9.40502 19.2726C9.30433 19.2726 9.20364 19.234 9.12694 19.1573L4.40713 14.4375C4.25334 14.2837 4.25334 14.0352 4.40713 13.8814L10.7002 7.58828C10.854 7.4345 11.1026 7.4345 11.2564 7.58828ZM20.4081 14.3856C20.2201 14.2751 19.9794 14.3384 19.8696 14.526L15.3614 22.2154C15.0358 22.779 14.3124 22.9729 13.7665 22.6583L12.8882 22.0891C12.7065 21.9707 12.4627 22.0231 12.3447 22.2052C12.2263 22.3877 12.2782 22.6311 12.4607 22.7491L13.3559 23.3293C13.6647 23.5074 14.0025 23.592 14.336 23.592C15.0161 23.592 15.6781 23.24 16.0411 22.6111L20.5481 14.9237C20.6579 14.7364 20.5953 14.4957 20.4081 14.3856ZM23.5645 1.96659V8.25968C23.5645 9.4766 23.1774 10.3832 22.2693 11.291L10.5885 22.9883C10.2168 23.3599 9.72282 23.5641 9.19735 23.5641C8.67188 23.5641 8.17826 23.3599 7.80736 22.9883L0.576211 15.7575C0.204525 15.3858 0 14.8918 0 14.3663C0 13.8409 0.204525 13.3472 0.576211 12.976L10.7923 2.77368C8.12084 2.1664 6.463 2.12549 5.97765 2.26748C6.18257 2.47712 6.82092 2.92235 8.36155 3.52964C8.56371 3.60948 8.66283 3.8376 8.58338 4.03977C8.50354 4.24193 8.27502 4.33987 8.07325 4.2616C4.88422 3.00456 5.07066 2.3076 5.14106 2.04525C5.28698 1.49854 6.06614 1.31644 7.66223 1.45606C8.96214 1.56973 10.64 1.89029 12.3868 2.35794C14.1335 2.82599 15.7461 3.38725 16.9284 3.93908C18.3817 4.61677 18.9642 5.16309 18.8175 5.71019C18.6759 6.23802 17.94 6.30253 17.6981 6.32377C17.6867 6.32495 17.6749 6.32534 17.6635 6.32534C17.4617 6.32534 17.2903 6.17077 17.2722 5.96624C17.2529 5.74992 17.413 5.55916 17.6297 5.54028C17.7972 5.52572 17.9086 5.50291 17.9805 5.48246C17.8122 5.30665 17.3862 5.0325 16.705 4.70605C16.5622 4.94676 16.4847 5.22326 16.4847 5.50645C16.4847 6.37411 17.1904 7.07972 18.058 7.07972C18.9257 7.07972 19.6313 6.37411 19.6313 5.50645C19.6313 4.63879 18.9257 3.93318 18.058 3.93318C17.8405 3.93318 17.6647 3.75737 17.6647 3.53986C17.6647 3.32236 17.8405 3.14654 18.058 3.14654C19.3595 3.14654 20.4179 4.20496 20.4179 5.50645C20.4179 6.80794 19.3595 7.86636 18.058 7.86636C16.7565 7.86636 15.6981 6.80794 15.6981 5.50645C15.6981 5.11195 15.8019 4.72768 15.9872 4.38471C15.046 3.98942 13.7834 3.54694 12.1834 3.11783C12.0147 3.0726 11.8522 3.0313 11.6898 2.99L1.13236 13.5325C0.909351 13.7551 0.786636 14.0517 0.786636 14.3663C0.786636 14.6818 0.909351 14.9779 1.13236 15.201L8.36351 22.4321C8.80914 22.8777 9.58634 22.8777 10.032 22.4321L21.7127 10.7348C22.4793 9.96864 22.7778 9.27444 22.7778 8.25968V1.96659C22.7778 1.31604 22.2484 0.786636 21.5979 0.786636H15.3048C14.29 0.786636 13.5958 1.08516 12.8296 1.85135C12.6759 2.00514 12.4273 2.00514 12.2735 1.85135C12.1197 1.69756 12.1197 1.44898 12.2735 1.2952C13.1813 0.387025 14.0879 0 15.3048 0H21.5979C22.6823 0 23.5645 0.882212 23.5645 1.96659Z"
                            fill="white" />
                    </svg>
                </button>
                <div class="hero-btn-subinfo-wrapper">
                    <div class="hero-btn-subinfo">
                        <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 19 19" fill="none">
                            <path
                                d="M8.1225 9.39635C8.1225 10.2176 8.78887 10.8836 9.60975 10.8836C10.1865 10.8836 10.6807 10.5521 10.9275 10.0725L10.9316 10.0762L13.6466 5.17798L8.88037 8.10073L8.88487 8.10523C8.43225 8.3606 8.1225 8.83985 8.1225 9.39635Z"
                                fill="#858891" />
                            <path
                                d="M15.6015 2.30962C14.0775 0.977625 12.1084 0.127125 9.8584 0V2.26012C11.7334 2.37937 12.8381 2.97487 13.9474 3.90412L15.6015 2.30962Z"
                                fill="#858891" />
                            <path
                                d="M16.6631 8.69175H18.9206C18.7691 6.44175 17.889 4.593 16.5225 3.078L14.9254 4.66538C15.8876 5.76788 16.5206 7.19175 16.6631 8.69175Z"
                                fill="#858891" />
                            <path
                                d="M16.6736 9.81687C16.4348 13.5669 13.3279 16.541 9.53288 16.541C5.58225 16.541 2.25 13.337 2.25 9.38674C2.25 5.62624 5.35838 2.54936 8.73338 2.26061V0.000488281C3.85838 0.292613 0 4.39436 0 9.39649C0 14.5876 4.2825 18.791 9.47363 18.791C14.5106 18.791 18.6949 14.6919 18.9353 9.81687H16.6736Z"
                                fill="#858891" />
                        </svg>
                        No high-end pc needed
                    </div>
                    <div class="hero-btn-subinfo">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <g clip-path="url(#clip0_19_55)">
                                <path
                                    d="M9.66874 14.55C9.68983 14.5709 9.71285 14.5897 9.73749 14.6063L9.77499 14.6266C9.80315 14.6446 9.8344 14.6573 9.86718 14.6641L9.90624 14.675C9.96608 14.6868 10.0277 14.6868 10.0875 14.675L10.1281 14.6625L10.175 14.6484C10.1911 14.641 10.2067 14.6327 10.2219 14.6234L10.2547 14.6047C10.2806 14.5873 10.3046 14.5674 10.3266 14.5453L14.5312 10.3453C14.5837 10.3045 14.6269 10.253 14.6579 10.1942C14.6889 10.1355 14.7071 10.0708 14.7112 10.0044C14.7153 9.93812 14.7053 9.87167 14.6818 9.80951C14.6583 9.74735 14.6218 9.69091 14.5748 9.64392C14.5278 9.59692 14.4714 9.56046 14.4092 9.53695C14.3471 9.51343 14.2806 9.5034 14.2143 9.50752C14.148 9.51165 14.0833 9.52982 14.0245 9.56085C13.9657 9.59188 13.9142 9.63505 13.8734 9.6875L10.4687 13.0875V0.46875C10.4687 0.34443 10.4194 0.225201 10.3314 0.137294C10.2435 0.049386 10.1243 0 9.99999 0C9.87567 0 9.75644 0.049386 9.66854 0.137294C9.58063 0.225201 9.53124 0.34443 9.53124 0.46875V13.0875L6.12655 9.6875C6.03637 9.61733 5.92366 9.58251 5.80961 9.5896C5.69556 9.59668 5.58803 9.64519 5.50723 9.72599C5.42643 9.80679 5.37792 9.91432 5.37084 10.0284C5.36375 10.1424 5.39857 10.2551 5.46874 10.3453L9.66874 14.55Z"
                                    fill="#858891" />
                                <path
                                    d="M19.5312 13.75C19.4069 13.75 19.2877 13.7994 19.1998 13.8873C19.1119 13.9752 19.0625 14.0944 19.0625 14.2188V17.6562C19.0625 18.0292 18.9143 18.3869 18.6506 18.6506C18.3869 18.9143 18.0292 19.0625 17.6562 19.0625H2.34375C1.97079 19.0625 1.6131 18.9143 1.34938 18.6506C1.08566 18.3869 0.9375 18.0292 0.9375 17.6562V14.2188C0.9375 14.0944 0.888114 13.9752 0.800206 13.8873C0.712299 13.7994 0.59307 13.75 0.46875 13.75C0.34443 13.75 0.225201 13.7994 0.137294 13.8873C0.049386 13.9752 0 14.0944 0 14.2188L0 17.6562C0 18.2779 0.24693 18.874 0.686468 19.3135C1.12601 19.7531 1.72215 20 2.34375 20H17.6562C18.2779 20 18.874 19.7531 19.3135 19.3135C19.7531 18.874 20 18.2779 20 17.6562V14.2188C20 14.0944 19.9506 13.9752 19.8627 13.8873C19.7748 13.7994 19.6556 13.75 19.5312 13.75Z"
                                    fill="#858891" />
                            </g>
                            <defs>
                                <clipPath id="clip0_19_55">
                                    <rect width="20" height="20" fill="white" />
                                </clipPath>
                            </defs>
                        </svg>
                        Up to 1TB of storage
                    </div>
                </div>
                <img class="hero-img" src="img/macbook-frame.png">
            </section>
            <section class="container">
                <div class="info-box">Some of our best games!</div>
                <div class="marquee">
                    <div class="marquee-content">
                        <img src="img/image 6.png" alt="Game 2">
                        <img src="img/image 7.png" alt="Game 3">
                        <img src="img/image 8.png" alt="Game 4">
                        <img src="img/image 9.png" alt="Game 5">
                        <img src="img/image 10.png" alt="Game 6">
                        <img src="img/image 11.png" alt="Game 7">
                        <img src="img/image 12.png" alt="Game 8">
                        <img src="img/image 6.png" alt="Game 2">
                        <img src="img/image 7.png" alt="Game 3">
                        <img src="img/image 8.png" alt="Game 4">
                        <img src="img/image 9.png" alt="Game 5">
                        <img src="img/image 10.png" alt="Game 6">
                        <img src="img/image 11.png" alt="Game 7">
                        <img src="img/image 12.png" alt="Game 8">
                    </div>
                </div>
            </section>
            <section class="container how-it-works-section">
                <img src="img/image (8).jpg" width="100%" height="auto" class="how-bg-img">
                <div class="how-it-works-contnt">
                    <div class="info-box">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <path d="M13.6569 2.34315L2.34315 13.6569" stroke="white" stroke-width="1.5"
                                stroke-linecap="round" />
                            <path d="M8 1.33333L8 14.6667" stroke="white" stroke-width="1.5" stroke-linecap="round" />
                        </svg>
                        How it works
                    </div>
                    <h2 class="hero-text" style="text-align: center;">
                        Your <span class="hero-gradient">cloud</span></br> gaming platform
                    </h2>
                    <p class="hero-subtext" style="text-align: center;">
                        Step into a world of exclusives made for true gamers. From groundbreaking indie</br>masterpieces
                        to AAA blockbusters you can't play anywhere else — this is your gateway</br>to experiences that
                        define what gaming should feel like.
                    </p>
                    <div class="how-steps-wrapper">
                        <div class="how-step">
                            <div class="how-step-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <rect x="3" y="3" width="18" height="18" rx="2" stroke="white" stroke-width="1.5" />
                                    <path d="M8 12H16M12 8V16" stroke="white" stroke-width="1.5"
                                        stroke-linecap="round" />
                                </svg>
                            </div>
                            <h3 class="how-step-title">Purchase</h3>
                            <p class="how-step-text">Select your plan, choose a server and rent your console</p>
                        </div>
                        <div class="how-step">
                            <div class="how-step-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <rect x="3" y="3" width="8" height="8" rx="1" stroke="white" stroke-width="1.5" />
                                    <rect x="13" y="3" width="8" height="8" rx="1" stroke="white" stroke-width="1.5" />
                                    <rect x="3" y="13" width="8" height="8" rx="1" stroke="white" stroke-width="1.5" />
                                    <rect x="13" y="13" width="8" height="8" rx="1" stroke="white" stroke-width="1.5" />
                                </svg>
                            </div>
                            <h3 class="how-step-title">Connect</h3>
                            <p class="how-step-text">Connect to your console through the dashboard</p>
                        </div>
                        <div class="how-step">
                            <div class="how-step-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <path d="M8 16L12 12L8 8M16 8L12 12L16 16" stroke="white" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <circle cx="12" cy="12" r="9" stroke="white" stroke-width="1.5" />
                                </svg>
                            </div>
                            <h3 class="how-step-title">Play</h3>
                            <p class="how-step-text">Enjoy your exclusive gaming experience</p>
                        </div>
                    </div>
                </div>
            </section>
            <section class="container pricing-container">
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
                            <li>cancel after 24h</li>
                        </ul>
                        <button class="btn-primary ">Start renting</button>
                        <a href="#" class="pricing-card-link">See available servers</a>
                    </div>

                    <div class="pricing-card">
                        <h4 class="pricing-card-title">Weekly</h4>
                        <div class="pricing-card-price">
                            <span class="price">$0.19</span>
                            <span class="price-period">per hour</span>
                        </div>
                        <ul class="pricing-card-features">
                            <li>Subscribe for a week</li>
                            <li>pay in advance</li>
                        </ul>
                        <button class="btn-primary ">Start renting</button>
                        <a href="#" class="pricing-card-link">See available servers</a>
                    </div>

                    <div class="pricing-card">
                        <h4 class="pricing-card-title">Monthly</h4>
                        <div class="pricing-card-price">
                            <span class="price">$0.16</span>
                            <span class="price-period">per hour</span>
                        </div>
                        <ul class="pricing-card-features">
                            <li>Subscribe for a month</li>
                            <li>pay in advance</li>
                        </ul>
                        <button class="btn-primary ">Start renting</button>
                        <a href="#" class="pricing-card-link">See available servers</a>
                    </div>

                    <div class="pricing-card">
                        <h4 class="pricing-card-title">Enterprise/custom</h4>
                        <div class="pricing-card-price">
                            <span class="price">Lets talk</span>
                            <span class="price-period">custom pricing</span>
                        </div>
                        <ul class="pricing-card-features">
                            <li>Pay by an hour</li>
                            <li>cancel after 24h</li>
                        </ul>
                        <button class="btn-primary ">Contact sales</button>
                        <a href="#" class="pricing-card-link">See available servers</a>
                    </div>
                </div>
            </section>
            <section class="faq-section container">
                <div class="faq-container">
                    <div class="faq-left">
                        <h2 class="faq-title">Frequently asked questions</h2>
                    </div>

                    <div class="faq-right">
                        <div class="faq-list">
                            <div class="faq-item">
                                <button class="faq-question">
                                    <span>How do i get started with console cloud ?</span>
                                    <svg class="faq-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                        <line x1="12" y1="5" x2="12" y2="19"></line>
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                    </svg>
                                </button>
                                <div class="faq-answer">
                                    <p>Getting started is simple! Choose your preferred plan, select a server location
                                        closest to you, and complete the rental process. Once confirmed, you'll receive
                                        access to your dashboard where you can connect to your console and start playing
                                        immediately.</p>
                                </div>
                            </div>

                            <div class="faq-item">
                                <button class="faq-question">
                                    <span>Can i run console cloud on my device</span>
                                    <svg class="faq-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                        <line x1="12" y1="5" x2="12" y2="19"></line>
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                    </svg>
                                </button>
                                <div class="faq-answer">
                                    <p>Console Cloud works on most modern devices including Windows, Mac, iOS, and
                                        Android. All you need is a stable internet connection and a compatible web
                                        browser. For the best experience, we recommend a minimum 25 Mbps connection.</p>
                                </div>
                            </div>

                            <div class="faq-item">
                                <button class="faq-question">
                                    <span>How does the pricing work, can i cancel if it doesnt work ?</span>
                                    <svg class="faq-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                        <line x1="12" y1="5" x2="12" y2="19"></line>
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                    </svg>
                                </button>
                                <div class="faq-answer">
                                    <p>We offer flexible monthly and annual plans with no long-term commitments. You can
                                        cancel anytime from your dashboard. If you're not satisfied within the first 7
                                        days, we offer a full refund, no questions asked.</p>
                                </div>
                            </div>

                            <div class="faq-item">
                                <button class="faq-question">
                                    <span>Which games will i be able to run on console cloud ?</span>
                                    <svg class="faq-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                        <line x1="12" y1="5" x2="12" y2="19"></line>
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                    </svg>
                                </button>
                                <div class="faq-answer">
                                    <p>You'll have access to the full library of console games, including exclusive
                                        titles, AAA blockbusters, and indie favorites. Our platform supports all games
                                        available on the console you rent, with no restrictions on game selection.</p>
                                </div>
                            </div>

                            <div class="faq-item">
                                <button class="faq-question">
                                    <span>What performance will i get with console cloud</span>
                                    <svg class="faq-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                        <line x1="12" y1="5" x2="12" y2="19"></line>
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                    </svg>
                                </button>
                                <div class="faq-answer">
                                    <p>Experience console gaming at its best with up to 4K resolution at 60fps,
                                        depending on your plan and internet speed. Our infrastructure ensures minimal
                                        latency and smooth gameplay, providing a native console experience from the
                                        cloud.</p>
                                </div>
                            </div>
                        </div>
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

</body>
<script src="main.js"></script>

</html>