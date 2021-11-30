<?php

session_start();

//Подключаемся к базе данных
require $_SERVER['DOCUMENT_ROOT'].'/include/db.php';

//Подключаем скрипт проверки авторизации
require $_SERVER['DOCUMENT_ROOT'].'/include/check_auth.php';

//Подключаем скрипт проверки ошибок
include $_SERVER['DOCUMENT_ROOT'].'/include/error.php';
$error = new errors();

//Проверяем наличие авторизации
if ($auth !== true){
    header("Location: /");
    die();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Xmel 35</title>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">

    <link rel="stylesheet" href="/css/owl.carousel.css">
    <link rel="stylesheet" href="/css/owl.theme.default.css">
    <link rel="stylesheet" href="/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="/css/modal.css?v=2"/>
    <link rel="stylesheet" href="/styles.css"/>

    <link rel="stylesheet" type="text/css" href="/styles.css" media="print" />

    <script src="https://kit.fontawesome.com/653875a875.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
</head>

<body>

<header>
    <div class="container">
        <nav class="navbar navbar-light navbar-expand-lg">

            <a class="navbar-brand" href="/"><img class="image-main" src="/images/logo.png" alt=""></a>
            <div class="collapse navbar-collapse" id="navbarToggler">
                <ul class="navbar-nav ">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#"><img src="/images/home.png" alt=""></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Servıces</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Projects</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Team</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">News</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Clients</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Contact</a>
                    </li>
                </ul>
                <div class="header-r">
                    <div class="social-networks">
                        <a href="" onclick="javascript:window.print()"><img src="/images/print.png" alt=""></a>
                        <a href="/filter-page.html"><img src="/images/filter.png" alt=""></a>
                        <a href=""><img src="/images/social-network-1.png" alt=""></a>
                        <a href=""><img src="/images/social-network-2.png" alt=""></a>
                        <a href=""><img src="/images/social-network-3.png" alt=""></a>
                    </div>
                    <div>
                        <form class="d-flex">
                            <input class="form-control me-2" type="search" placeholder="" aria-label="Search">
                            <button class="btn btn-outline-success" type="submit"><img src="/images/search.png"></button>
                        </form>
                            <a href="/lk/"><b><?=$db->query("SELECT `fio` FROM `users` WHERE `id`='$user_id'")->fetch_assoc()['fio']?></b></a>
                            <br>
                            <a href="/lk/profile/"><i class="fas fa-user-cog"></i>Профиль</a>
                            <br>
                            <a href="/lk/auth/logout.php"><i class="fas fa-sign-out-alt"></i>Выйти</a>
                    </div>
                </div>

            </div>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggler"
                    aria-controls="navbarToggler" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                <span class="navbar-toggler-icon"></span>
                <span class="navbar-toggler-icon"></span>
            </button>
        </nav>
    </div>
</header>

<main>
    <section class="container" style="margin-top: 15em">
        <h4><?=$db->query("SELECT `fio` FROM `users` WHERE `id`='$user_id'")->fetch_assoc()['fio']?>, добро пожаловать в закрытый раздел портала! Вы успешно авторизованы!</h4>
        <p>На вкладке "Профиль" Вы можете изменить свои данные (кроме логина).</p>
        <img src="/images/closedroom.png">
    </section>
</main>

<footer>
    <div class="footer-top">
        <div class="container">
            <div class="footer-top-wrapper">
                <div class="footer-top-l">
                    <img src="/images/logo-footer.png" alt="">
                </div>
                <div class="footer-top-c">
                    <div class="social-networks">
                        <a href=""><img src="/images/social-network-1.png" alt=""></a>
                        <a href=""><img src="/images/social-network-2.png" alt=""></a>
                        <a href=""><img src="/images/social-network-3.png" alt=""></a>
                        <a href=""><img src="/images/social-network-4.png" alt=""></a>
                        <a href=""><img src="/images/social-network-5.png" alt=""></a>
                        <a href=""><img src="/images/social-network-6.png" alt=""></a>
                    </div>
                </div>
                <div class="footer-top-r">
                    <h3>NEWSLETTER</h3>
                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                    <form class="form-footer">
                        <input type="email">
                        <button type="submit"><img src="/images/mail.png" alt=""></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">
            <h2><span>QUARANTIE SOFTWARE DEVELOPMENT INC.</span></h2>
            <div class="row">
                <div class="col-md-6 col-lg-3">
                    <div class="contacts-block">
                        <h3>SYDNEY</h3>
                        <p>Level 6, 341 George St
                            Sydney, NSW, 2000, Australia</p>
                        <p>
                            <span>Phone</span>
                            +61 2 9262 1443
                        </p>
                        <p>
                            <span>Fax</span>
                            +61 2 8208 7383
                        </p>
                        <p>
                            <span>e-mail</span>
                            sydney@quarantie.com
                        </p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="contacts-block">
                        <h3>San Francısco</h3>
                        <p>1098 Harrison Street San Francisco, CA, 94103 USA</p>
                        <p>
                            <span>Phone</span>
                            +1 415 701 1110
                        </p>
                        <p>
                            <span>Fax</span>
                            +1 415 449 6222
                        </p>
                        <p>
                            <span>e-mail</span>
                            sanfrancisco@quarantie.com
                        </p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="contacts-block">
                        <h3>Amsterdam</h3>
                        <p>Keizersgracht 311 1016EE Amsterdam
                            Netherlands</p>
                        <p>
                            <span>Phone</span>
                            +31 20 796 0060
                        </p>
                        <p>
                            <span>Fax</span>
                            +31 20 524 8260
                        </p>
                        <p>
                            <span>e-mail</span>
                            sanfrancisco@quarantie.com
                        </p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="contacts-block">
                        <h3>Tokyo</h3>
                        <p>22F Shibuya Mark City West 1-12-1 Dogenzaka Shibuya-ku Tokyo 150-0043
                            Japan</p>
                        <p>
                            <span>Phone</span>
                            +81 (0)3-4360-5376
                        </p>
                        <p>
                            <span>Fax</span>
                            +81 (0)3-4360-5301
                        </p>
                        <p>
                            <span>e-mail</span>
                            sanfrancisco@quarantie.com
                        </p>
                    </div>
                </div>
            </div>
            <p class="copyright">Copyright © 2013 - Quarantie PSD Template - Design by Göksel Vançin - All Right
                Reserved</p>
        </div>
    </div>
</footer>

<script src="/js/jquery-3.2.1.min.js"></script>
<script src="/js/bootstrap.min.js"></script>
<script src="/js/owl.carousel.js"></script>

<script>
    $(document).ready(function () {
        $('.top-slider').owlCarousel({
            autoplay: false,
            autoplayTimeout: 6000,
            loop: true,
            autoplaySpeed: 500,
            items: 1,
            dotsEach: true,
            center: false,
            responsive: {
                0: {
                    nav: false,
                    dots: true
                },
                992: {
                    nav: true,
                    dots: true
                }
            }
        });

    });
    $(document).ready(function () {
        $('.projects-slider').owlCarousel({
            autoWidth: false,
            autoplay: false,
            autoplayTimeout: 6000,
            loop: true,
            autoplaySpeed: 500,
            items: 1,
            margin: 18,
            nav: true,
            dots: false,
            dotsEach: true,
            center: false,
            responsive: {
                0: {
                    items: 1
                },
                767: {
                    margin: 18,
                    items: 2
                },
                992: {
                    margin: 30,
                    items: 3
                },
                1200: {

                    margin: 30,
                    items: 4
                }
            }
        });

    });
    $(document).ready(function () {
        $('.team-slider').owlCarousel({
            autoWidth: false,
            autoplay: false,
            autoplayTimeout: 6000,
            loop: true,
            autoplaySpeed: 500,
            items: 1,
            margin: 18,
            nav: true,
            dots: false,
            dotsEach: false,
            center: false,
            responsive: {
                0: {
                    items: 1
                },
                767: {
                    margin: 18,
                    items: 2
                },
                992: {
                    margin: 30,
                    items: 3
                },
                1200: {

                    margin: 30,
                    items: 4
                }
            }
        });

    });
    $(document).ready(function () {
        $('.news-slider').owlCarousel({
            autoplay: false,
            autoplayTimeout: 6000,
            loop: true,
            autoplaySpeed: 500,
            items: 1,
            margin: 18,
            dotsEach: true,
            center: false,
            nav: true,
            dots: false,
            responsive: {
                1: {
                    margin: 30,
                    items: 1
                },
                1200: {
                    margin: 30,
                    items: 2
                }
            }
        });

    });

    $(document).ready(function () {
        $('.clients-slider').owlCarousel({
            autoplay: false,
            autoplayTimeout: 6000,
            loop: true,
            autoplaySpeed: 500,
            items: 1,
            margin: 18,
            nav: true,
            dots: false,
            dotsEach: true,
            center: false,
            responsive: {
                0: {
                    margin: 10,
                    items: 2
                },
                767: {
                    margin: 10,
                    items: 4
                },
                992: {
                    margin: 10,
                    items: 6
                },
                1200: {

                    margin: 30,
                    items: 8
                }
            }
        });
        var height = $('.clients-slider .owl-stage').height();
        $('.clients-slider .item').height(height)
    });
</script>

</body>

</html>
