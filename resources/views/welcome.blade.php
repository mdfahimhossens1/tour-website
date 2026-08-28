<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>VromonSeba — Travel Management Platform</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=sora:400,500,600,700,800|inter:400,500,600,700"
        rel="stylesheet" />

    <style>
        :root {
            --navy-950: #041426;
            --navy-900: #071B33;
            --navy-800: #0A2647;
            --navy-700: #0F3A6E;

            --blue-600: #1B58A8;

            --gold-500: #F2A93B;
            --gold-600: #E0921C;

            --ink-900: #10233F;
            --ink-600: #5B6B85;
            --ink-400: #8C9BB5;

            --line: #E6EAF2;
            --surface: #FFFFFF;
            --bg: #F5F7FB;
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            min-height: 100%;
        }

        body {
            font-family: 'Inter', ui-sans-serif, system-ui, sans-serif;
            background: var(--bg);
            color: var(--ink-900);
        }

        a {
            text-decoration: none;
        }

        .page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px;
        }

        .shell {
            width: 100%;
            max-width: 1050px;

            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 24px;
            overflow: hidden;

            box-shadow:
                0 30px 70px -30px rgba(7, 27, 51, .35),
                0 4px 12px rgba(7, 27, 51, .06);

            position: relative;

            opacity: 0;
            transform: translateY(15px);

            animation: rise .6s ease forwards;
        }

        @keyframes rise {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .shell {
                animation: none;
                opacity: 1;
                transform: none;
            }
        }

        /* =========================
           HERO
        ========================= */

        .hero {
            position: relative;
            overflow: hidden;

            background:
                radial-gradient(
                    100% 130% at 10% 0%,
                    rgba(242, 169, 59, .17),
                    transparent 55%
                ),
                linear-gradient(
                    145deg,
                    var(--navy-950) 0%,
                    var(--navy-900) 45%,
                    var(--navy-700) 100%
                );

            color: white;
            padding: 55px 50px 48px;
        }

        .route-map {
            position: absolute;
            inset: 0;
            pointer-events: none;
            opacity: .75;
        }

        .route-map svg {
            width: 100%;
            height: 100%;
        }

        .route-path {
            fill: none;
            stroke: rgba(255, 255, 255, .20);
            stroke-width: 1.5;
            stroke-dasharray: 6 8;
        }

        .route-node {
            fill: var(--gold-500);
        }

        .route-ring {
            fill: none;
            stroke: rgba(255, 255, 255, .4);
            stroke-width: 1;
        }

        .route-text {
            fill: rgba(255, 255, 255, .45);
            font-size: 9px;
            font-family: 'Inter', sans-serif;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
        }

        /* =========================
           LOGO
        ========================= */

        .brand {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 12px;

            margin-bottom: 32px;
        }

        .brand img {
            height: 46px;
            width: auto;

            padding: 5px;

            border-radius: 10px;

            background: rgba(255, 255, 255, .07);
        }

        .fallback {
            width: 46px;
            height: 46px;

            display: none;
            align-items: center;
            justify-content: center;

            border-radius: 11px;

            background:
                linear-gradient(
                    135deg,
                    var(--gold-500),
                    var(--gold-600)
                );

            color: var(--navy-900);

            font-family: 'Sora', sans-serif;
            font-weight: 800;
            font-size: 18px;
        }

        .brand-name {
            font-family: 'Sora', sans-serif;
            font-size: 23px;
            font-weight: 700;
        }

        .brand-name span {
            color: var(--gold-500);
        }

        /* =========================
           BADGE
        ========================= */

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;

            padding: 7px 15px;
            margin-bottom: 18px;

            border-radius: 999px;

            background: rgba(242, 169, 59, .12);
            border: 1px solid rgba(242, 169, 59, .35);

            color: var(--gold-500);

            font-size: 12px;
            font-weight: 600;

            text-transform: uppercase;
            letter-spacing: .5px;
        }

        /* =========================
           TITLE
        ========================= */

        .hero h1 {
            max-width: 650px;

            margin: 0 auto 15px;

            font-family: 'Sora', sans-serif;

            font-size: 34px;
            line-height: 1.25;

            font-weight: 700;
        }

        .hero-description {
            max-width: 570px;

            margin: 0 auto 30px;

            color: rgba(255, 255, 255, .72);

            font-size: 15px;
            line-height: 1.7;
        }

        /* =========================
           STATS
        ========================= */

        .stats {
            max-width: 650px;

            margin: 0 auto 32px;

            padding-top: 24px;

            border-top: 1px solid rgba(255, 255, 255, .13);

            display: flex;
            justify-content: center;
            gap: 55px;
        }

        .stat strong {
            display: block;

            font-family: 'Sora', sans-serif;

            font-size: 22px;
            font-weight: 700;
        }

        .stat span {
            display: block;

            margin-top: 3px;

            color: rgba(255, 255, 255, .58);

            font-size: 11.5px;
        }

        /* =========================
           BUTTONS
        ========================= */

        .buttons {
            display: flex;
            justify-content: center;
            align-items: center;

            gap: 13px;

            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;

            min-width: 190px;

            padding: 13px 25px;

            border-radius: 10px;

            font-family: 'Sora', sans-serif;

            font-size: 14px;
            font-weight: 700;

            transition:
                transform .15s ease,
                filter .15s ease,
                background .15s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .btn-primary {
            color: var(--navy-900);

            background:
                linear-gradient(
                    135deg,
                    var(--gold-500),
                    var(--gold-600)
                );
        }

        .btn-primary:hover {
            filter: brightness(1.07);
        }

        .btn-secondary {
            color: white;

            background: rgba(255, 255, 255, .08);

            border: 1px solid rgba(255, 255, 255, .28);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, .14);
        }

        /* =========================
           ACCESS INFO
        ========================= */

        .access {
            display: flex;
            justify-content: center;
            align-items: center;

            gap: 8px;

            margin-top: 25px;

            color: rgba(255, 255, 255, .5);

            font-size: 12px;
        }

        /* =========================
           FOOTER
        ========================= */

        .footer {
            padding: 18px 25px;

            display: flex;
            justify-content: center;
            align-items: center;

            gap: 18px;
            flex-wrap: wrap;

            background: #fff;

            color: var(--ink-400);

            font-size: 12px;
        }

        .footer a {
            color: var(--ink-600);
        }

        .footer a:hover {
            color: var(--blue-600);
        }

        /* =========================
           MOBILE
        ========================= */

        @media (max-width: 700px) {

            .page {
                padding: 15px;
            }

            .hero {
                padding: 40px 22px 35px;
            }

            .hero h1 {
                font-size: 27px;
            }

            .hero-description {
                font-size: 14px;
            }

            .stats {
                gap: 25px;
            }

            .stat strong {
                font-size: 19px;
            }

            .stat span {
                font-size: 10px;
            }

            .btn {
                width: 100%;
            }

            .brand-name {
                font-size: 20px;
            }
        }
    </style>
</head>

<body>

<div class="page">

    <div class="shell">

        <section class="hero">

            {{-- Route Map --}}
            <div class="route-map" aria-hidden="true">

                <svg viewBox="0 0 1050 360" preserveAspectRatio="xMidYMid slice">

                    <path
                        class="route-path"
                        d="M40,315
                           C150,270 220,285 310,220
                           C410,145 500,175 610,115
                           C730,50 850,75 1010,20"
                    />

                    <circle class="route-node" cx="40" cy="315" r="4.5"/>
                    <circle class="route-ring" cx="40" cy="315" r="9"/>

                    <text class="route-text" x="55" y="320">
                        Cox's Bazar
                    </text>


                    <circle class="route-node" cx="310" cy="220" r="4.5"/>
                    <circle class="route-ring" cx="310" cy="220" r="9"/>

                    <text class="route-text" x="325" y="225">
                        Sajek Valley
                    </text>


                    <circle class="route-node" cx="610" cy="115" r="4.5"/>
                    <circle class="route-ring" cx="610" cy="115" r="9"/>

                    <text class="route-text" x="625" y="120">
                        Sylhet
                    </text>


                    <circle class="route-node" cx="1010" cy="20" r="4"/>
                    <circle class="route-ring" cx="1010" cy="20" r="8"/>

                    <text class="route-text" x="930" y="17">
                        Sundarbans
                    </text>

                </svg>

            </div>


            <div class="hero-content">

                {{-- Brand --}}
                <div class="brand">

                    <img
                        src="{{ asset('contents/admin/images/logo.png') }}"
                        alt="VromonSeba"
                        onerror="
                            this.style.display='none';
                            this.nextElementSibling.style.display='flex';
                        "
                    >

                    <span class="fallback">
                        VS
                    </span>

                    <div class="brand-name">
                        Vromon<span>Seba</span>
                    </div>

                </div>


                {{-- Badge --}}
                <div class="badge">
                    ✈️ Bangladesh Travel Platform
                </div>


                {{-- Heading --}}
                <h1>
                    Explore Bangladesh.
                    Manage Every Journey.
                </h1>


                <p class="hero-description">
                    VromonSeba brings tours, resorts, bookings,
                    travellers and travel partners together in one
                    powerful platform.
                </p>


                {{-- Stats --}}
                <div class="stats">

                    <div class="stat">
                        <strong>45k+</strong>
                        <span>Happy Explorers</span>
                    </div>

                    <div class="stat">
                        <strong>98.2%</strong>
                        <span>Customer Rating</span>
                    </div>

                    <div class="stat">
                        <strong>24/7</strong>
                        <span>Support</span>
                    </div>

                </div>


                {{-- Buttons --}}
                <div class="buttons">

                    <a
                        href="{{ route('admin.login') }}"
                        class="btn btn-primary"
                    >
                        🔐 Admin Login
                    </a>


                    <a
                        href="{{ route('vendor.login') }}"
                        class="btn btn-secondary"
                    >
                        🏨 Vendor Login
                    </a>

                </div>


                {{-- Security --}}
                <div class="access">

                    <svg
                        width="14"
                        height="14"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path d="M12 2l8 4v6c0 5-3.5 8.5-8 10-4.5-1.5-8-5-8-10V6l8-4z"/>
                    </svg>

                    Secure access for authorised users and partners.

                </div>

            </div>

        </section>


        {{-- Footer --}}
        <footer class="footer">

            <span>
                © {{ date('Y') }} VromonSeba
            </span>

            <span>•</span>

            <a href="{{ url('/') }}">
                Home
            </a>

            <span>•</span>

            <span>
                Travel • Resort • Booking
            </span>

        </footer>

    </div>

</div>

</body>
</html>