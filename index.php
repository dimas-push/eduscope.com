<!DOCTYPE html>
<html lang="id">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eduscope</title>
    <!-- Link ke Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="index-style.css">

    <!-- Font-Awesome-Logo-Icon -->
    <script src="https://kit.fontawesome.com/da77020350.js" crossorigin="anonymous"></script>

    <!-- Font -->
    <link
        href="https://fonts.googleapis.com/css2?family=Alexandria:wght@100..900&family=Kulim+Park:ital,wght@0,200;0,300;0,400;0,600;0,700;1,200;1,300;1,400;1,600;1,700&display=swap"
        rel="stylesheet">

    <body>

        <!-- Navbar Header -->
        <nav class="navbar navbar-expand-lg navbar-custom fixed-top">
            <div class="container">
                <!-- Logo -->
                <a class="navbar-brand" href="#">
                    <img src="layout/img/logo0.png" alt="Logo">
                </a>

                <!-- Tombol burger untuk membuka menu pada mode kecil -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Navbar Links (Centered) -->
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav mx-auto">
                        <!-- Menu tengah -->
                        <li class="nav-item">
                            <a class="nav-link" href="#">Program</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Tutor</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Testimoni</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Kelas</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Tentang Kami</a>
                        </li>
                    </ul>

                    <!-- Tombol Login dan Register (terpisah) -->
                    <div class="d-flex">
                        <a href="login.php" class="btn btn-login">Login</a>
                        <a href="register.php" class="btn btn-register">Register</a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Jumbotron -->
        <section class="container jumbotron d-flex align-items-center">
            <div class="row w-100">
                <div class="col text-start d-flex flex-column justify-content-center">
                    <h1>Bantu anak berkembang <br> dengan kursus yang pas <br> buat mereka!</h1><br>
                    <p>Bantu anak tumbuh dengan kursus yang pas buat mereka, seru dan asik! <br> Yuk, pilih kelas yang
                        sesuai biar
                        makin semangat belajar</p><br>
                    <div class="d-flex">
                        <a href="register.php" class="btn btn-outline-light btn-warning register-btn">Gabung
                            Sekarang!</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 d-flex justify-content-center">
                <img src="layout/img/jumbotron.png" alt="Bootstrap" width="auto" height="500">
            </div>
        </section>
        <!-- End Jumbotron -->

        <!-- Program -->
        <div id="program">
            <div class="container">
                <div class="row text-center">
                    <div class="col p-3">
                        <h1 class="p-2 judul">Cari program yang cocok buat kamu!</h1>
                        <p class="deskripsi">Yuk, temukan program yang pas banget buat anak! Seru dan sesuai kebutuhan
                            mereka,<br>
                            bikin si kecil makin berkembang dengan cara yang asik</p>
                    </div>
                </div>
                <div id="multiImageCarousel" class="carousel slide justify-content-center" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <!-- Slide pertama dengan 4 gambar -->
                        <div class="carousel-item active">
                            <div class="row text-center">
                                <div class="col-lg-3 mb-2">
                                    <div class="card">
                                        <img src="layout/img/english.png" class="card-img-top" alt="ENGLISH">
                                        <div class="card-body">
                                            <h5 class="icon-title">ENGLISH</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 mb-2">
                                    <div class="card">
                                        <img src="layout/img/japanese.png" class="card-img-top" alt="JAPANESE">
                                        <div class="card-body">
                                            <h5 class="icon-title">JAPANESE</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 mb-2">
                                    <div class="card">
                                        <img src="layout/img/bimbel.png" class="card-img-top" alt="BIMBEL">
                                        <div class="card-body">
                                            <h5 class="icon-title">BIMBEL</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 mb-2">
                                    <div class="card">
                                        <img src="layout/img/aritmatika.png" class="card-img-top" alt="JARIMATIKA">
                                        <div class="card-body">
                                            <h5 class="icon-title">ARIMATIKA</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Slide kedua dengan 4 gambar (contoh tambahan) -->
                        <div class="carousel-item justify-content-center align-items-center">
                            <div class="row text-center">
                                <div class="col-6">
                                    <div class="card">
                                        <img src="layout/img/calistung.png" class="card-img-top" alt="Another Image 1">
                                        <div class="card-body">
                                            <h5 class="icon-title">CALISTUNG</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="card">
                                        <img src="layout/img/toefl.png" class="card-img-top" alt="Photo Toefl">
                                        <div class="card-body">
                                            <h4 class="icon-title">TOEFL</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tambahkan slide lainnya di sini jika diperlukan -->
                    </div>

                    <!-- Tombol Navigasi Kiri -->
                    <button class="carousel-control-prev" type="button" data-bs-target="#multiImageCarousel"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>

                    <!-- Tombol Navigasi Kanan -->
                    <button class="carousel-control-next" type="button" data-bs-target="#multiImageCarousel"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
        </div>
        <!-- End Program -->

        <!-- Tutor Section -->
        <section class="tutor-section">
            <div class="container text-center">
                <div class="yellow-container">
                    <h1 class="tutor-h1">Kenalan sama Tutor berpengalaman</h1>
                    <p class="p-tutor">Kenalan sama tutor berpengalaman di Eduscope Learning Centre yang siap membantu
                        kamu
                        menguasai berbagai<br>bidang seperti Bahasa Inggris, Matematika, hingga TOEFL dengan metode fun
                        learning yang efektif.
                    </p>
                </div>


                <div id="tutor">
                    <div class="container">
                        <div class="row text-center">
                            <div class="col p-3">
                            </div>
                        </div>
                        <div id="multiImageCarouselTutor" class="carousel slide justify-content-center"
                            data-bs-ride="carousel">
                            <div class="carousel-inner">
                                <!-- Slide pertama dengan 4 gambar -->
                                <div class="carousel-item active">
                                    <div class="row text-center">
                                        <div class="col-lg-3 mb-2">
                                            <div class="card-tutor">
                                                <img src="layout/img/tutor/tutor1.png" class="tutor-photo"
                                                    alt="Tutor English">
                                                <div class="card-body-tutor">
                                                    <h5 class="icon-title-tutor">Ms. Peni</h5>
                                                    <p class="card-text-tutor">Tutor Calistung</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-6 mb-2">
                                            <div class="card-tutor">
                                                <img src="layout/img/tutor/tutor2.png" class="tutor-photo"
                                                    alt="Tutor Jepang">
                                                <div class="card-body-tutor">
                                                    <h5 class="icon-title-tutor">Ms. Putri</h5>
                                                    <p class="card-text-tutor">Tutor Bhs.Inggris</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-6 mb-2">
                                            <div class="card-tutor">
                                                <img src="layout/img/tutor/tutor3.png" class="tutor-photo"
                                                    alt="Tutor Bimbel">
                                                <div class="card-body-tutor">
                                                    <h5 class="icon-title-tutor">Ms. Indah</h5>
                                                    <p class="card-text-tutor">Tutor</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-6 mb-2">
                                            <div class="card-tutor">
                                                <img src="layout/img/tutor/tutor4.png" class="tutor-photo"
                                                    alt="Tutor Jarimatika">
                                                <div class="card-body-tutor">
                                                    <h5 class="icon-title-tutor">Ms. Hanan</h5>
                                                    <p class="card-text-tutor">Tutor Matematika</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Slide kedua dengan 4 gambar (contoh tambahan) -->
                                <div class="carousel-item">
                                    <div class="row text-center">
                                        <div class="col-6">
                                            <div class="card-tutor">
                                                <img src="layout/img/tutor/tutor5.png" class="tutor-photo"
                                                    alt="Photo Tutor 1">
                                                <div class="card-body-tutor">
                                                    <h5 class="icon-title-tutor">Ms. Sirri</h5>
                                                    <p class="card-text-tutor">Tutor Bhs.Inggris</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="card-tutor">
                                                <img src="layout/img/tutor/tutor6.png" class="tutor-photo"
                                                    alt="Photo Tutor 2">
                                                <div class="card-body-tutor">
                                                    <h5 class="icon-title-tutor">Ms. Reva</h5>
                                                    <p class="card-text-tutor">Tutor Bhs.Inggris</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tambahkan slide lainnya di sini jika diperlukan -->
                            </div>

                            <!-- Tombol Navigasi Kiri -->
                            <button class="carousel-control-prev tutor-prev" type="button"
                                data-bs-target="#multiImageCarouselTutor" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon prev-tutor" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>

                            <!-- Tombol Navigasi Kanan -->
                            <button class="carousel-control-next tutor-next" type="button"
                                data-bs-target="#multiImageCarouselTutor" data-bs-slide="next">
                                <span class="carousel-control-next-icon next-tutor" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End Tutor Section -->

        <!-- Start Testimoni -->
        <section class="testimoni">
            <section class="container d-flex align-items-center testimonial-section">
                <div class="row w-100">
                    <div class="col text-start d-flex flex-column justify-content-center">
                        <h1 class="testimoni-h1">Apa kata mereka?</h1>
                        <i class="fa-solid fa-quote-left fa-2xl" style="color: #004aad;"></i>
                        <p class="testimoni-p">Belajar di Eduscope bikin belajar jadi asyik,<br>materinya gampang
                            dipahami, dan gurunya seru banget!</p>
                        <div class="card-tutor">
                            <img src="layout/img/tutor/tutor6.png" class="testimoni-photo" alt="Photo Tutor 2">
                        </div>
                    </div>
                    <div class="col-md-6 d-flex justify-content-center video-testimoni">
                        <!-- 21:9 aspect ratio -->
                        <div class="embed-responsive embed-responsive-1by1">
                            <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/2u_kncwzJ5Y"
                                width="600" height="305"></iframe>
                        </div>
                    </div>
            </section>
        </section>



        <!-- Start Footer -->
        <footer class="footer text-center">
            <div class="container">
                <p>Temukan Kursus Online yang Tepat untuk Anak Anda</p>
                <p>© 2024 Eduscope Learning Centre. All Rights Reserved</p>
                <p><a href="#">Program Eduscope</a> | <a href="#">Kontak Kami</a></p>
            </div>
        </footer>

        <!-- Link ke Bootstrap JS dan Popper.js -->
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    </body>

</html>