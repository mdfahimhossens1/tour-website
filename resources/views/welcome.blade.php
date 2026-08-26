<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Panel — {{ config('app.name', 'VromonSeba') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=sora:400,600,700,800|inter:400,500,600,700" rel="stylesheet" />

    <style>
        :root{
            --navy-900:#071B33; --navy-800:#0A2647; --navy-700:#0F3A6E;
            --blue-600:#1B58A8; --gold-500:#F2A93B; --gold-600:#E0921C;
            --ink-900:#10233F; --ink-600:#5B6B85; --ink-400:#8C9BB5;
            --line:#E6EAF2; --surface:#FFFFFF; --bg:#F5F7FB;
        }
        *,*::before,*::after{ box-sizing:border-box; }
        html,body{ margin:0; padding:0; }
        body{
            font-family:'Inter', ui-sans-serif, system-ui, sans-serif;
            background:var(--bg); color:var(--ink-900);
            min-height:100vh; display:flex; align-items:center; justify-content:center; padding:28px;
        }
        h1,h2,.display{ font-family:'Sora', 'Inter', sans-serif; }

        .shell{
            width:100%; max-width:960px; background:var(--surface);
            border-radius:22px; overflow:hidden;
            box-shadow:0 30px 60px -25px rgba(7,27,51,0.35), 0 2px 8px rgba(7,27,51,0.06);
            border:1px solid var(--line);
            position:relative;
            opacity:0; transform:translateY(14px); animation:rise .6s ease forwards;
        }
        @keyframes rise{ to{ opacity:1; transform:translateY(0); } }
        @media (prefers-reduced-motion:reduce){ .shell{ animation:none; opacity:1; transform:none; } }

        .brand{
            position:relative;
            background:
                radial-gradient(120% 140% at 15% 0%, rgba(242,169,59,0.16), transparent 55%),
                linear-gradient(160deg, var(--navy-900) 0%, var(--navy-800) 55%, var(--navy-700) 100%);
            color:#fff; padding:56px 48px; overflow:hidden;
        }
        .routemap{ position:absolute; inset:0; z-index:1; opacity:.85; pointer-events:none; }
        .routemap svg{ width:100%; height:100%; }
        .routemap .path{ stroke:rgba(255,255,255,0.24); stroke-width:1.4; stroke-dasharray:5 7; fill:none; }
        .routemap .node{ fill:var(--gold-500); }
        .routemap .node-outline{ fill:none; stroke:rgba(255,255,255,0.45); stroke-width:1; }
        .routemap text{ fill:rgba(255,255,255,0.5); font-family:'Inter',sans-serif; font-size:9.5px; letter-spacing:.3px; }

        .brand-inner{ position:relative; z-index:2; text-align:center; }
        .brand-logo{ display:flex; align-items:center; justify-content:center; gap:12px; margin-bottom:34px; }
        .brand-logo img{ height:42px; width:auto; border-radius:9px; background:rgba(255,255,255,0.06); padding:5px; }
        .brand-logo .fallback-mark{
            height:42px; width:42px; border-radius:10px; display:flex; align-items:center; justify-content:center;
            background:linear-gradient(135deg, var(--gold-500), var(--gold-600));
            font-family:'Sora',sans-serif; font-weight:800; color:var(--navy-900); font-size:17px;
        }
        .brand-logo .name{ font-family:'Sora',sans-serif; font-size:21px; font-weight:700; }
        .brand-logo .name span{ color:var(--gold-500); }

        .badge{
            display:inline-flex; align-items:center; gap:8px; margin:0 auto 18px;
            background:rgba(242,169,59,0.14); border:1px solid rgba(242,169,59,0.4); color:var(--gold-500);
            padding:6px 14px; border-radius:999px; font-size:12px; font-weight:600; letter-spacing:.4px; text-transform:uppercase;
        }
        .brand h1{ font-size:30px; line-height:1.28; font-weight:700; margin:0 auto 14px; max-width:460px; }
        .brand p.lead{ color:rgba(255,255,255,0.72); font-size:14.5px; line-height:1.65; max-width:420px; margin:0 auto 34px; }

        .stats{ display:flex; justify-content:center; gap:36px; padding-top:26px; border-top:1px solid rgba(255,255,255,0.14); margin-bottom:34px; }
        .stats .stat b{ display:block; font-family:'Sora',sans-serif; font-size:22px; font-weight:700; color:#fff; }
        .stats .stat span{ display:block; font-size:11.5px; color:rgba(255,255,255,0.6); margin-top:2px; }

        .cta-row{ display:flex; justify-content:center; gap:14px; flex-wrap:wrap; }
        .btn{
            font-family:'Sora',sans-serif; font-size:14.5px; font-weight:700; letter-spacing:.2px;
            padding:13px 30px; border-radius:10px; text-decoration:none; display:inline-flex; align-items:center; gap:8px;
            transition:filter .15s ease, transform .15s ease;
        }
        .btn:active{ transform:translateY(1px); }
        .btn-primary{ background:linear-gradient(135deg, var(--gold-500), var(--gold-600)); color:var(--navy-900); }
        .btn-primary:hover{ filter:brightness(1.06); }
        .btn-outline{ background:rgba(255,255,255,0.08); color:#fff; border:1px solid rgba(255,255,255,0.28); }
        .btn-outline:hover{ background:rgba(255,255,255,0.14); }

        .trust-line{
            display:flex; align-items:center; justify-content:center; gap:8px;
            font-size:12.5px; color:rgba(255,255,255,0.5); margin-top:28px;
        }

        .back-site{ display:flex; justify-content:center; margin-top:22px; }
        .back-site a{ color:rgba(255,255,255,0.55); font-size:12.5px; text-decoration:none; }
        .back-site a:hover{ color:#fff; }
    </style>
</head>
<body>

    <div class="shell">
        <div class="brand">
            <div class="routemap" aria-hidden="true">
                <svg viewBox="0 0 960 320" preserveAspectRatio="xMidYMid slice">
                    <path class="path" d="M40,280 C160,220 220,240 300,180 C400,110 500,140 600,80 C700,30 800,50 920,10" />
                    <circle class="node" cx="40" cy="280" r="4.5" /><circle class="node-outline" cx="40" cy="280" r="8" />
                    <text x="54" y="285">Cox's Bazar</text>
                    <circle class="node" cx="300" cy="180" r="4.5" /><circle class="node-outline" cx="300" cy="180" r="8" />
                    <text x="314" y="185">Sajek Valley</text>
                    <circle class="node" cx="600" cy="80" r="4.5" /><circle class="node-outline" cx="600" cy="80" r="8" />
                    <text x="614" y="85">Sylhet</text>
                    <circle class="node" cx="920" cy="10" r="4" /><circle class="node-outline" cx="920" cy="10" r="7" />
                    <text x="850" y="4">Sundarbans</text>
                </svg>
            </div>

            <div class="brand-inner">
                <div class="brand-logo">
                    <img src="{{ asset('contents/admin/images/logo.png') }}" alt="{{ config('app.name', 'VromonSeba') }} logo"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <span class="fallback-mark" style="display:none;">VS</span>
                    <span class="name">Vromon<span>Seba</span></span>
                </div>

                <span class="badge">🔒 Admin Console</span>
                <h1>Manage every journey behind VromonSeba, in one place</h1>
                <p class="lead">Tour packages, resort offers, bookings and traveller reviews — all controlled from a single, secure dashboard built for the team.</p>

                <div class="stats">
                    <div class="stat"><b>45k+</b><span>Happy Explorers</span></div>
                    <div class="stat"><b>98.2%</b><span>Rating Record</span></div>
                    <div class="stat"><b>24/7</b><span>Hotline Support</span></div>
                </div>

                <div class="cta-row">
                    <a href="{{ route('login') }}" class="btn btn-primary">Sign In to Dashboard</a>
                    <a href="{{ route('register') }}" class="btn btn-outline">Create an Account</a>
                </div>

                <div class="trust-line">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2l8 4v6c0 5-3.5 8.5-8 10-4.5-1.5-8-5-8-10V6l8-4z"/></svg>
                    Access is restricted to authorised VromonSeba staff and partners.
                </div>

                <div class="back-site">
                    <a href="{{ url('/') }}">← Back to VromonSeba website</a>
                </div>
            </div>
        </div>
    </div>

</body>
</html>