<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="description"
        content="Sistem Informasi Monitoring Perkembangan Belajar Siswa UPTD SDN Kandangan Baru"
    >

    <title>
        Login | Monitoring Perkembangan Belajar Siswa
    </title>


    {{-- FONT --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap"
        rel="stylesheet"
    >


    <style>

        /* =========================================================
           RESET
        ========================================================= */

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }


        html,
        body {
            width: 100%;
            min-height: 100%;
            font-family: "Poppins", sans-serif;
        }


        body {
            min-height: 100vh;
            background: #0b4d88;
            color: #ffffff;
        }


        /* =========================================================
           PAGE
        ========================================================= */

        .login-page {
            position: relative;
            width: 100%;
            min-height: 100vh;
            overflow: hidden;

            display: flex;
            align-items: center;

            padding: 45px 55px;
        }


        /* =========================================================
           BACKGROUND
        ========================================================= */

        .background-image {
            position: absolute;
            inset: 0;
            z-index: 0;

            background-image:
                url("{{ asset('images/sekolah.jpg') }}");

            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;

            transform: scale(1.02);
        }


        /* =========================================================
           OVERLAY
        ========================================================= */

        .background-overlay {
            position: absolute;
            inset: 0;
            z-index: 1;

            background:
                linear-gradient(
                    90deg,
                    rgba(4, 46, 91, 0.92) 0%,
                    rgba(5, 60, 112, 0.82) 38%,
                    rgba(8, 76, 135, 0.60) 68%,
                    rgba(4, 43, 83, 0.72) 100%
                );
        }


        /* =========================================================
           MAIN CONTENT
        ========================================================= */

        .content {
            position: relative;
            z-index: 2;

            width: 100%;
            max-width: 1320px;

            margin: 0 auto;

            display: grid;

            grid-template-columns:
                minmax(0, 1fr)
                375px;

            align-items: center;

            gap: 110px;
        }


        /* =========================================================
           LEFT CONTENT
        ========================================================= */

        .intro {
            width: 100%;
            max-width: 650px;

            justify-self: start;
        }


        /* =========================================================
           SCHOOL IDENTITY
        ========================================================= */

        .school {
            display: flex;
            align-items: center;

            gap: 13px;

            margin-bottom: 55px;
        }


        /*
         * FOTO SEKOLAH
         */

        .school-photo {
            width: 48px;
            height: 48px;

            flex: 0 0 48px;

            border-radius: 11px;

            overflow: hidden;

            background: #ffffff;

            border: 2px solid rgba(255,255,255,0.85);

            box-shadow:
                0 8px 24px rgba(0,0,0,0.18);
        }


        .school-photo img {
            width: 100%;
            height: 100%;

            display: block;

            object-fit: cover;

            object-position: center;
        }


        .school-text {
            display: flex;
            flex-direction: column;

            gap: 2px;
        }


        .school-text strong {
            color: #ffffff;

            font-size: 15px;
            line-height: 1.35;

            font-weight: 600;
        }


        .school-text span {
            color: rgba(255,255,255,0.68);

            font-size: 10px;
            line-height: 1.4;

            font-weight: 400;
        }


        /* =========================================================
           EYEBROW
        ========================================================= */

        .eyebrow {
            margin-bottom: 13px;

            color: rgba(255,255,255,0.72);

            font-size: 10px;
            line-height: 1.5;

            font-weight: 600;

            letter-spacing: 1.5px;

            text-transform: uppercase;
        }


        /* =========================================================
           TITLE
        ========================================================= */

        .title {
            max-width: 620px;

            color: #ffffff;

            font-size: clamp(35px, 4vw, 50px);

            line-height: 1.15;

            font-weight: 700;

            letter-spacing: -1.5px;
        }


        .title-highlight {
            color: #8fd4ff;
        }


        /* =========================================================
           DESCRIPTION
        ========================================================= */

        .description {
            max-width: 570px;

            margin-top: 21px;

            color: rgba(255,255,255,0.76);

            font-size: 12px;

            line-height: 1.8;

            font-weight: 400;
        }


        /* =========================================================
           DECORATIVE LINE
        ========================================================= */

        .blue-line {
            width: 55px;
            height: 3px;

            margin-top: 25px;

            border-radius: 10px;

            background: #8bd3ff;
        }


        /* =========================================================
           LOGIN AREA
        ========================================================= */

        .login-wrapper {
            width: 100%;

            justify-self: end;
        }


        /* =========================================================
           LOGIN CARD
        ========================================================= */

        .login-card {
            width: 100%;

            padding: 34px 35px 30px;

            border-radius: 15px;

            background: rgba(255,255,255,0.97);

            border: 1px solid rgba(255,255,255,0.75);

            box-shadow:
                0 22px 55px rgba(0,25,55,0.25);

            color: #173d63;
        }


        /* =========================================================
           LOGIN HEADING
        ========================================================= */

        .login-heading {
            margin-bottom: 25px;
        }


        .login-heading h2 {
            color: #124b7d;

            font-size: 23px;

            line-height: 1.3;

            font-weight: 600;
        }


        .login-heading p {
            max-width: 280px;

            margin-top: 7px;

            color: #7a8fa3;

            font-size: 10px;

            line-height: 1.7;
        }


        .heading-line {
            width: 32px;
            height: 3px;

            margin-top: 12px;

            border-radius: 10px;

            background: #147bd1;
        }


        /* =========================================================
           ERROR
        ========================================================= */

        .error {
            padding: 10px 12px;

            margin-bottom: 18px;

            border-radius: 7px;

            background: #fff4f4;

            border: 1px solid #f4d1d1;

            color: #b4232d;

            font-size: 10px;

            line-height: 1.5;
        }


        /* =========================================================
           FORM
        ========================================================= */

        .form-group {
            margin-bottom: 17px;
        }


        .form-label {
            display: block;

            margin-bottom: 6px;

            color: #355b7b;

            font-size: 10px;

            font-weight: 500;
        }


        /* =========================================================
           INPUT
        ========================================================= */

        .form-control {
            width: 100%;
            height: 46px;

            padding: 0 13px;

            border: 1px solid #d7e3ee;

            border-radius: 7px;

            outline: none;

            background: #ffffff;

            color: #193f60;

            font-family: "Poppins", sans-serif;

            font-size: 11px;

            transition:
                border-color .2s ease,
                box-shadow .2s ease;
        }


        .form-control::placeholder {
            color: #a4b3c0;
        }


        .form-control:focus {
            border-color: #2386d7;

            box-shadow:
                0 0 0 3px rgba(35,134,215,0.10);
        }


        /* =========================================================
           BUTTON
        ========================================================= */

        .login-button {
            width: 100%;
            height: 46px;

            margin-top: 3px;

            border: none;
            border-radius: 7px;

            background: #147bd1;

            color: #ffffff;

            font-family: "Poppins", sans-serif;

            font-size: 11px;

            font-weight: 600;

            cursor: pointer;

            transition:
                background .2s ease,
                box-shadow .2s ease;
        }


        .login-button:hover {
            background: #096bbd;

            box-shadow:
                0 7px 18px rgba(20,123,209,0.22);
        }


        /* =========================================================
           LOGIN INFO
        ========================================================= */

        .login-info {
            margin-top: 18px;

            padding-top: 15px;

            border-top: 1px solid #edf1f5;

            text-align: center;

            color: #91a1b1;

            font-size: 8px;

            line-height: 1.7;
        }


        /* =========================================================
           FOOTER
        ========================================================= */

        .footer {
            position: absolute;

            left: 55px;
            bottom: 18px;

            z-index: 3;

            color: rgba(255,255,255,0.48);

            font-size: 8px;
        }


        /* =========================================================
           LARGE SCREEN
        ========================================================= */

        @media (min-width: 1400px) {

            .login-page {
                padding-left: 70px;
                padding-right: 70px;
            }

            .content {
                max-width: 1380px;

                grid-template-columns:
                    minmax(0, 1fr)
                    390px;

                gap: 125px;
            }

            .title {
                font-size: 53px;
            }

        }


        /* =========================================================
           TABLET
        ========================================================= */

        @media (max-width: 1050px) {

            .login-page {
                padding: 35px;
            }


            .content {
                max-width: 900px;

                grid-template-columns:
                    minmax(0, 1fr)
                    340px;

                gap: 50px;
            }


            .title {
                font-size: 39px;
            }


            .school {
                margin-bottom: 45px;
            }


            .footer {
                left: 35px;
            }

        }


        /* =========================================================
           MOBILE
        ========================================================= */

        @media (max-width: 800px) {

            .login-page {
                min-height: 100vh;

                padding: 28px 20px;

                align-items: flex-start;
            }


            .background-overlay {
                background:
                    linear-gradient(
                        180deg,
                        rgba(4,48,96,0.88) 0%,
                        rgba(5,58,115,0.88) 50%,
                        rgba(4,43,88,0.94) 100%
                    );
            }


            .content {
                min-height: auto;

                grid-template-columns: 1fr;

                gap: 32px;

                padding: 10px 0 35px;
            }


            .intro {
                max-width: 620px;
            }


            .school {
                margin-bottom: 42px;
            }


            .title {
                font-size: clamp(30px, 8vw, 39px);

                letter-spacing: -0.8px;
            }


            .description {
                max-width: 580px;

                font-size: 11px;

                line-height: 1.75;
            }


            .login-wrapper {
                max-width: 420px;

                justify-self: start;
            }


            .login-card {
                padding: 29px 25px;
            }


            .footer {
                display: none;
            }

        }


        /* =========================================================
           SMALL PHONE
        ========================================================= */

        @media (max-width: 480px) {

            .login-page {
                padding: 22px 16px;
            }


            .content {
                gap: 27px;

                padding-top: 5px;
            }


            .school {
                margin-bottom: 34px;
            }


            .school-photo {
                width: 43px;
                height: 43px;

                flex-basis: 43px;
            }


            .school-text strong {
                font-size: 13px;
            }


            .school-text span {
                font-size: 8px;
            }


            .eyebrow {
                font-size: 8px;

                letter-spacing: 1.1px;
            }


            .title {
                font-size: 29px;

                line-height: 1.16;
            }


            .description {
                font-size: 10px;

                line-height: 1.7;
            }


            .login-card {
                padding: 26px 21px;
            }


            .login-heading {
                margin-bottom: 22px;
            }


            .login-heading h2 {
                font-size: 21px;
            }


            .form-control,
            .login-button {
                height: 45px;
            }

        }

    </style>

</head>


<body>

<div class="login-page">


    {{-- =========================================================
         BACKGROUND FOTO SEKOLAH
    ========================================================== --}}

    <div class="background-image"></div>

    <div class="background-overlay"></div>


    {{-- =========================================================
         CONTENT
    ========================================================== --}}

    <div class="content">


        {{-- =====================================================
             LEFT / INFORMASI SEKOLAH
        ====================================================== --}}

        <section class="intro">


            {{-- IDENTITAS SEKOLAH --}}

            <div class="school">

                <div class="school-photo">

                    <img
                        src="{{ asset('images/sekolah.jpg') }}"
                        alt="UPTD SDN Kandangan Baru"
                    >

                </div>


                <div class="school-text">

                    <strong>
                        UPTD SDN Kandangan Baru
                    </strong>

                    <span>
                        Kabupaten Tanah Laut
                    </span>

                </div>

            </div>


            {{-- LABEL --}}

            <div class="eyebrow">
                Sistem Informasi Sekolah
            </div>


            {{-- TITLE --}}

            <h1 class="title">

                Monitoring
                <br>

                Perkembangan
                <br>

                <span class="title-highlight">
                    Belajar Siswa
                </span>

            </h1>


            {{-- DESCRIPTION --}}

            <p class="description">

                Sistem informasi berbasis web untuk membantu guru
                mencatat perkembangan belajar siswa secara berkala
                serta memudahkan orang tua memperoleh informasi
                perkembangan belajar anak.

            </p>


            <div class="blue-line"></div>


        </section>



        {{-- =====================================================
             RIGHT / LOGIN
        ====================================================== --}}

        <section class="login-wrapper">

            <div class="login-card">


                {{-- HEADING --}}

                <div class="login-heading">

                    <h2>
                        Selamat Datang
                    </h2>

                    <p>
                        Silakan masuk untuk mengakses
                        sistem monitoring perkembangan belajar siswa.
                    </p>

                    <div class="heading-line"></div>

                </div>


                {{-- ERROR --}}

                @if($errors->any())

                    <div class="error">
                        {{ $errors->first() }}
                    </div>

                @endif


                {{-- FORM --}}

                <form
                    action="{{ route('login.process') }}"
                    method="POST"
                >

                    @csrf


                    {{-- EMAIL --}}

                    <div class="form-group">

                        <label
                            for="email"
                            class="form-label"
                        >
                            Email
                        </label>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="form-control"
                            value="{{ old('email') }}"
                            placeholder="Masukkan email"
                            autocomplete="email"
                            required
                        >

                    </div>


                    {{-- PASSWORD --}}

                    <div class="form-group">

                        <label
                            for="password"
                            class="form-label"
                        >
                            Password
                        </label>

                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-control"
                            placeholder="Masukkan password"
                            autocomplete="current-password"
                            required
                        >

                    </div>


                    {{-- BUTTON --}}

                    <button
                        type="submit"
                        class="login-button"
                    >
                        Masuk ke Sistem
                    </button>


                </form>


                {{-- INFO --}}

                <div class="login-info">

                    Akses sistem untuk Admin, Guru,
                    dan Orang Tua sesuai dengan
                    hak akses masing-masing.

                </div>


            </div>

        </section>


    </div>


    {{-- =========================================================
         FOOTER
    ========================================================== --}}

    <div class="footer">

        © {{ date('Y') }} UPTD SDN Kandangan Baru

    </div>


</div>

</body>

</html>