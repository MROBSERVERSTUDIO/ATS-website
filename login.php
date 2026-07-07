<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ATS - Login | AI-Powered Trading That Works 24/7</title>
  <link rel="icon" type="image/png" href="assets/images/favicon.png" sizes="16x16">
  <link rel="stylesheet" href="assets/css/vendor/bootstrap.min.css">
  <link rel="stylesheet" href="assets/css/all.min.css">
  <link rel="stylesheet" href="assets/css/line-awesome.min.css">
  <link rel="stylesheet" href="assets/css/vendor/animate.min.css">
  <link rel="stylesheet" href="assets/css/vendor/slick.css">
  <link rel="stylesheet" href="assets/css/vendor/dots.css">
  <link rel="stylesheet" href="assets/css/main.css">

  <style>
    /* ================================================
       ATS Login Page — Scroll stiffness FIXED
    ================================================ */

    html {
      overflow-x: clip;
      scroll-behavior: smooth;
    }

    body {
      overflow-x: clip;
    }

    /* ── FADE-UP ── */
    .ats-fade-up {
      opacity: 0;
      transform: translateY(28px);
      transition: opacity 0.65s ease, transform 0.65s ease;
    }

    .ats-fade-up.ats-visible { opacity: 1; transform: translateY(0); }

    /* ── TICKER ── */
    .ats-ticker-bar {
      background: rgba(10,132,255,0.06);
      border-bottom: 1px solid rgba(10,132,255,0.15);
      padding: 8px 0;
      font-size: 12px;
      color: rgba(232,234,246,0.7);
      letter-spacing: 0.5px;
      text-transform: uppercase;
      overflow: hidden;
      white-space: nowrap;
      width: 100%;
      display: block !important;
      visibility: visible !important;
      position: relative;
      z-index: 10;
      margin-top: 72px;
    }

    .ats-ticker-bar .ticker-inner {
      display: inline-flex;
      gap: 30px;
      white-space: nowrap;
      animation: tickerScroll 60s linear infinite;
      will-change: transform;
    }

    .ats-ticker-bar:hover .ticker-inner { animation-play-state: paused; }

    .ats-ticker-bar .ticker-item {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      flex-shrink: 0;
    }

    .ats-ticker-bar .ticker-item .symbol { color: rgba(232,234,246,0.5); font-weight: 600; }
    .ats-ticker-bar .ticker-item .price  { color: #e8eaf6; font-weight: 700; font-family: "Josefin Sans", sans-serif; }
    .ats-ticker-bar .ticker-item .change { font-weight: 600; font-size: 11px; }
    .ats-ticker-bar .ticker-item .up     { color: #00FFB3; }
    .ats-ticker-bar .ticker-item .down   { color: #ff4d6d; }
    .ats-ticker-bar .ticker-sep          { color: rgba(10,132,255,0.35); }

    @keyframes tickerScroll {
      0%   { transform: translateX(0); }
      100% { transform: translateX(-50%); }
    }

    @media (max-width: 1199px) { .ats-ticker-bar { margin-top: 65px; } }
    @media (max-width: 991px)  { .ats-ticker-bar { margin-top: 60px; } }
    @media (max-width: 767px)  { .ats-ticker-bar { margin-top: 58px; font-size: 11px; padding: 7px 0; } }
    @media (max-width: 575px)  { .ats-ticker-bar { margin-top: 54px; } .ats-ticker-bar .ticker-inner { gap: 18px; } }

    /* ── INNER HERO ── */
    .inner-hero .page-title {
      background: linear-gradient(135deg, #ffffff 0%, #00D4FF 60%, #0A84FF 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      font-size: 48px;
      line-height: 1.2;
    }

    @media (max-width: 991px) { .inner-hero .page-title { font-size: 38px; } }
    @media (max-width: 767px) { .inner-hero .page-title { font-size: 32px; } }
    @media (max-width: 575px) { .inner-hero .page-title { font-size: 26px; } }

    /* ── ACCOUNT SECTION ──
       KEY: NO overflow:hidden — prevents scroll stiffness */
    .account-section {
      position: relative;
      min-height: 100vh;
      display: flex;
      align-items: center;
    }

    .account-section::after {
      content: '';
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      width: 600px;
      height: 600px;
      background: radial-gradient(ellipse, rgba(10,132,255,0.08) 0%, transparent 70%);
      pointer-events: none;
      z-index: 0;
    }

    /* ── ACCOUNT CARD ── */
    .account-card { position: relative; z-index: 2; }

    .account-card__header .section-title { font-size: 28px !important; }

    .account-card__header p {
      color: rgba(232,234,246,0.75);
      font-size: 14px;
      margin-top: 10px;
      line-height: 1.7;
    }

    .account-card__body h3 {
      background: linear-gradient(135deg, #ffffff 0%, #00D4FF 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      font-size: 22px;
      letter-spacing: 1px;
    }

    .account-card__body .form-group { margin-bottom: 20px; }

    .account-card__body .form-control:focus {
      border-color: rgba(0,212,255,0.5) !important;
      box-shadow: 0 0 0 3px rgba(10,132,255,0.1);
    }

    .account-card__body .form-control.error {
      border-color: rgba(255,77,109,0.6) !important;
      box-shadow: 0 0 0 3px rgba(255,77,109,0.1);
    }

    .form-check-label {
      color: rgba(232,234,246,0.65);
      font-size: 13px;
      font-weight: 400;
      text-transform: none;
      letter-spacing: 0;
    }

    .account-card__body .f-size-14       { color: rgba(232,234,246,0.55); font-size: 13px; }
    .account-card__body .f-size-14 a     { color: #00D4FF; font-weight: 600; }
    .account-card__body .f-size-14 a:hover { color: #00FFB3; }

    .account-card__body .cmn-btn { width: 100%; justify-content: center; }

    .ats-login-error {
      display: none;
      color: #ff4d6d;
      font-size: 13px;
      margin-bottom: 16px;
      padding: 10px 14px;
      background: rgba(255,77,109,0.08);
      border: 1px solid rgba(255,77,109,0.25);
      border-radius: 8px;
    }

    .ats-forgot-link {
      display: block;
      text-align: center;
      margin-top: 16px;
      font-size: 13px;
      color: rgba(232,234,246,0.45);
    }

    .ats-forgot-link a       { color: rgba(10,132,255,0.8); font-size: 13px; }
    .ats-forgot-link a:hover { color: #00D4FF; }

    .ats-live-dot {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      font-size: 11px;
      color: #00FFB3;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 1.5px;
      margin-bottom: 14px;
    }

    .ats-live-dot::before {
      content: '';
      width: 7px; height: 7px;
      background: #00FFB3;
      border-radius: 50%;
      box-shadow: 0 0 8px rgba(0,255,179,0.8);
      animation: loginPulse 1.5s ease-in-out infinite;
    }

    @keyframes loginPulse {
      0%,100% { opacity:1; box-shadow:0 0 8px rgba(0,255,179,0.8); }
      50%      { opacity:0.4; box-shadow:0 0 3px rgba(0,255,179,0.3); }
    }

    .ats-security-strip {
      display: flex;
      justify-content: center;
      gap: 16px;
      flex-wrap: wrap;
      margin-top: 20px;
      padding-top: 16px;
      border-top: 1px solid rgba(10,132,255,0.12);
    }

    .ats-security-strip .badge-item {
      display: flex;
      align-items: center;
      gap: 5px;
      font-size: 11px;
      color: rgba(232,234,246,0.4);
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .ats-security-strip .badge-item i { color: #00FFB3; font-size: 13px; }

    /* ── RESPONSIVE ── */
    @media (max-width: 991px) {
      .account-section { align-items: flex-start; padding-top: 80px; padding-bottom: 60px; }
      .account-section .col-xl-5,
      .account-section .col-lg-7 { width: 100%; max-width: 540px; flex: 0 0 100%; padding-left: 15px; padding-right: 15px; }
    }

    @media (max-width: 575px) {
      .account-section { padding-top: 68px; padding-bottom: 48px; }
      .account-section .col-xl-5,
      .account-section .col-lg-7 { max-width: 100%; padding-left: 12px; padding-right: 12px; }
      .account-card__body { padding: 24px 20px !important; }
      .account-card__body h3 { font-size: 18px; }
      .account-card__body .form-group { margin-bottom: 16px; }
      .account-card__body .form-control { font-size: 14px; padding: 10px 14px; }
      .account-card__body .cmn-btn { padding: 14px 20px; font-size: 14px; }
      .account-card__header .section-title { font-size: 22px !important; }
      .account-card__header .section-title span { font-size: 26px !important; }
    }

    @media (max-width: 480px) {
      .account-card__body .form-row { flex-direction: column; }
      .account-card__body .form-row .col-sm-6 { width: 100%; max-width: 100%; flex: 0 0 100%; }
      .account-card__body .form-row .text-sm-right { text-align: left !important; margin-top: 4px; }
    }

    /* ── FOOTER ── */
    .footer__bottom p            { color: rgba(232,234,246,0.5); font-size:14px; }
    .footer__bottom .base--color { color: #00D4FF !important; }
    .scroll-to-top .scroll-icon i { transform: rotate(0deg) !important; font-size:20px; }

    @media (max-width: 767px) {
      .footer__bottom .col-md-6 { text-align: center !important; }
      .social-link-list { justify-content: center !important; margin-top: 8px; }
    }
  </style>
</head>

<body>

  <!-- Preloader -->
  <div class="preloader">
    <div class="preloader-container">
      <span class="animated-preloader"></span>
    </div>
  </div>

  <!-- Scroll to top -->
  <div class="scroll-to-top">
    <span class="scroll-icon">
      <i class="fa fa-rocket" aria-hidden="true"></i>
    </span>
  </div>

  <!-- Star Field Background -->
  <div class="full-wh">
    <div class="bg-animation">
      <div id='stars'></div>
      <div id='stars2'></div>
      <div id='stars3'></div>
      <div id='stars4'></div>
    </div>
  </div>

  <div class="page-wrapper">

    <!-- ============================================================
         HEADER — exact copy from index.html as provided
         Fixed: nav-right moved outside <ul> but inside collapse div
    ============================================================ -->
    <header class="header">
      <div class="header__bottom">
        <div class="container">
          <nav class="navbar navbar-expand-xl p-0 align-items-center">

            <a class="site-logo site-title" href="index.html">
              <img src="assets/images/logo.png" alt="ATS - Automated Trading System">
            </a>

            <ul class="account-menu mobile-acc-menu">
              <li class="icon"><a href="login.html"><i class="las la-user"></i></a></li>
            </ul>

            <button class="navbar-toggler" type="button" data-toggle="collapse"
              data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
              aria-expanded="false" aria-label="Toggle navigation">
              <span class="menu-toggle"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
              <ul class="navbar-nav main-menu m-auto">
                <li><a href="index.html">Home</a></li>
                <li><a href="about.html">About Us</a></li>
                <li><a href="contact.html">Contact Us</a></li>
                <li><a href="plan.html">AI Plans</a></li>
                <li class="menu_has_children"><a href="#0">Blog</a>
                  <ul class="sub-menu">
                    <li><a href="blog.html">Market Analysis</a></li>
                    <li><a href="blog-details.html">Trade Insights</a></li>
                  </ul>
                </li>
              </ul>

              <div class="nav-right">
                <ul class="account-menu ml-3">
                  <li class="icon"><a href="login.html"><i class="las la-user"></i></a></li>
                </ul>
              </div>
            </div>

          </nav>
        </div>
      </div>
    </header>


    <!-- ============================================================
         LIVE BINANCE TICKER
    ============================================================ -->
    <div class="ats-ticker-bar" id="atsTicker">
      <div class="container-fluid px-0">
        <div class="ticker-inner" id="atsTickerInner">
          <span class="ticker-item">
            <span class="symbol">Loading live prices</span>
            <span class="price">—</span>
          </span>
        </div>
      </div>
    </div>


    <!-- ============================================================
         INNER HERO
         BG IMAGE → bg-1.jpg
    ============================================================ -->
    <section class="inner-hero bg_img" data-background="assets/images/bg/bg-1.jpg">
      <div class="container">
        <div class="row">
          <div class="col-lg-7">
            <span class="section-top-title mb-3" style="display:block;">Member Access</span>
            <h2 class="page-title">Login</h2>
            <ul class="page-breadcrumb">
              <li><a href="index.html">Home</a></li>
              <li>Login</li>
            </ul>
            <div style="margin-top:20px;">
              <a href="index.html" class="border-btn">
                <i class="las la-home" style="margin-right:6px;"></i>Back To Home
              </a>
            </div>
          </div>
        </div>
      </div>
    </section>


    <!-- ============================================================
         ACCOUNT / LOGIN SECTION
         BG IMAGE → bg-5.jpg
         NO overflow:hidden — scroll stiffness fix applied
    ============================================================ -->
    <div class="account-section bg_img" data-background="assets/images/bg/bg-5.jpg">

      <div class="container">
        <div class="row justify-content-center">
          <div class="col-xl-5 col-lg-7">

            <div class="account-card ats-fade-up">

              <!-- Card header — BG IMAGE → bg-6.jpg / overlay--one -->
              <div class="account-card__header bg_img overlay--one"
                data-background="assets/images/bg/bg-6.jpg">

                <div class="ats-live-dot">AI Engine Active</div>

                <h2 class="section-title">
                  Welcome to
                  <span style="
                    color: #00FFB3;
                    -webkit-text-fill-color: #00FFB3;
                    display: block;
                    margin-top: 4px;
                    font-size: 32px;
                  ">ATS Platform</span>
                </h2>

                <p>
                  Access your AI trading dashboard. Your bots are working
                  around the clock to grow your portfolio — log in to monitor
                  real-time performance and manage your strategies.
                </p>

              </div>

              <!-- Card body — login form -->
              <div class="account-card__body">

                <h3 class="text-center">
                  <i class="las la-sign-in-alt" style="font-size:20px; margin-right:8px; -webkit-text-fill-color:#00D4FF;"></i>
                  Sign In
                </h3>

                <div class="ats-login-error" id="loginError">
                  ⚠ Invalid username or password. Please try again.
                </div>

                <form class="mt-4" id="atsLoginForm">

                  <div class="form-group">
                    <label>
                      <i class="las la-user" style="color:#0A84FF; margin-right:5px;"></i>
                      Username
                    </label>
                    <input type="text" name="username" id="login-username"
                      class="form-control" placeholder="Enter your username">
                  </div>

                  <div class="form-group">
                    <label>
                      <i class="las la-lock" style="color:#0A84FF; margin-right:5px;"></i>
                      Password
                    </label>
                    <input type="password" name="password" id="login-password"
                      class="form-control" placeholder="Enter your password">
                  </div>

                  <div class="form-row">
                    <div class="col-sm-6">
                      <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="rememberMe">
                        <label class="form-check-label" for="rememberMe">Remember me</label>
                      </div>
                    </div>
                    <div class="col-sm-6 text-sm-right">
                      <p class="f-size-14">
                        No account? <a href="registration.html">Sign Up</a>
                      </p>
                    </div>
                  </div>

                  <div class="mt-3">
                    <button class="cmn-btn" type="submit">
                      <i class="las la-rocket" style="margin-right:6px;"></i>Login Now
                    </button>
                  </div>

                </form>

                <div class="ats-forgot-link">
                  <a href="#">Forgot your password?</a>
                </div>

                <div class="ats-security-strip">
                  <span class="badge-item"><i class="las la-shield-alt"></i> 256-bit SSL</span>
                  <span class="badge-item"><i class="las la-lock"></i> 2FA Ready</span>
                  <span class="badge-item"><i class="las la-eye-slash"></i> Private</span>
                </div>

              </div>

            </div>

          </div>
        </div>
      </div>
    </div>


    <!-- ============================================================
         FOOTER
         BG IMAGE → bg-7.jpg
    ============================================================ -->
    <footer class="footer bg_img ats-fade-up" data-background="assets/images/bg/bg-7.jpg">
      <div class="footer__top">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-12 text-center">

              <a href="index.html" class="footer-logo">
                <img src="assets/images/logo.png" alt="ATS - Automated Trading System" loading="lazy">
              </a>

              <p style="color:rgba(232,234,246,0.4); font-size:12px; margin-top:8px; letter-spacing:2px; text-transform:uppercase;">
                AI-Powered Trading That Works 24/7
              </p>

              <ul class="footer-short-menu d-flex flex-wrap justify-content-center mt-4">
                <li><a href="index.html">Home</a></li>
                <li><a href="plan.html">AI Trading Plans</a></li>
                <li><a href="#0">Privacy Policy</a></li>
                <li><a href="#0">Terms &amp; Conditions</a></li>
                <li><a href="#0">Risk Disclosure</a></li>
              </ul>

              <div style="display:flex; justify-content:center; gap:36px; flex-wrap:wrap; margin-top:32px; padding-top:24px; border-top:1px solid rgba(10,132,255,0.12);">
                <div style="text-align:center;">
                  <div style="font-size:20px; font-weight:700; color:#00FFB3; font-family:'Josefin Sans',sans-serif;">$5.4B+</div>
                  <div style="font-size:11px; color:rgba(232,234,246,0.4); text-transform:uppercase; letter-spacing:1px; margin-top:3px;">Volume Traded</div>
                </div>
                <div style="text-align:center;">
                  <div style="font-size:20px; font-weight:700; color:#00D4FF; font-family:'Josefin Sans',sans-serif;">88K+</div>
                  <div style="font-size:11px; color:rgba(232,234,246,0.4); text-transform:uppercase; letter-spacing:1px; margin-top:3px;">Active Traders</div>
                </div>
                <div style="text-align:center;">
                  <div style="font-size:20px; font-weight:700; color:#0A84FF; font-family:'Josefin Sans',sans-serif;">99.99%</div>
                  <div style="font-size:11px; color:rgba(232,234,246,0.4); text-transform:uppercase; letter-spacing:1px; margin-top:3px;">System Uptime</div>
                </div>
                <div style="text-align:center;">
                  <div style="font-size:20px; font-weight:700; color:#00FFB3; font-family:'Josefin Sans',sans-serif;">99.99%</div>
                  <div style="font-size:11px; color:rgba(232,234,246,0.4); text-transform:uppercase; letter-spacing:1px; margin-top:3px;">AI Win Rate</div>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>

      <div class="footer__bottom">
        <div class="container">
          <div class="row">
            <div class="col-md-6 text-md-left text-center">
              <p>© 2024 <a href="index.html" class="base--color">ATS — Automated Trading System</a>. All rights reserved.</p>
            </div>
            <div class="col-md-6">
              <ul class="social-link-list d-flex flex-wrap justify-content-md-end justify-content-center">
                <li><a href="#0" data-toggle="tooltip" data-placement="top" title="Twitter / X"><i class="lab la-twitter"></i></a></li>
                <li><a href="#0" data-toggle="tooltip" data-placement="top" title="Telegram"><i class="lab la-telegram-plane"></i></a></li>
                <li><a href="#0" data-toggle="tooltip" data-placement="top" title="LinkedIn"><i class="lab la-linkedin-in"></i></a></li>
                <li><a href="#0" data-toggle="tooltip" data-placement="top" title="Discord"><i class="lab la-discord"></i></a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </footer>

  </div><!-- page-wrapper end -->

  <!-- Scripts — all preserved exactly -->
  <script src="assets/js/vendor/jquery-3.5.1.min.js"></script>
  <script src="assets/js/vendor/bootstrap.bundle.min.js"></script>
  <script src="assets/js/vendor/slick.min.js"></script>
  <script src="assets/js/vendor/wow.min.js"></script>
  <script src="assets/js/contact.js"></script>
  <script src="assets/js/app.js"></script>

  <script>
  /* ============================================================
     1. LIVE BINANCE WEBSOCKET TICKER
  ============================================================ */
  (function () {
    'use strict';
    var PAIRS = [
      ['btcusdt','BTC/USD'],['ethusdt','ETH/USD'],
      ['solusdt','SOL/USD'],['bnbusdt','BNB/USD'],
      ['xrpusdt','XRP/USD'],['dogeusdt','DOGE/USD'],
      ['adausdt','ADA/USD'],['maticusdt','MATIC/USD'],
    ];
    var live = {}, ws = null, retryMs = 3000, buildTimer = null;

    function fmtPrice(p) {
      p = parseFloat(p);
      if (isNaN(p)) return '—';
      if (p >= 1000) return '$' + p.toLocaleString('en-US', {minimumFractionDigits:2, maximumFractionDigits:2});
      if (p >= 1)    return '$' + p.toFixed(4);
      return '$' + p.toFixed(6);
    }

    function fmtChange(c) {
      c = parseFloat(c);
      if (isNaN(c)) return '';
      return (c >= 0 ? '+' : '') + c.toFixed(2) + '%';
    }

    function buildTicker() {
      var el = document.getElementById('atsTickerInner');
      if (!el) return;
      var half = '';
      PAIRS.forEach(function (p) {
        var sym = p[0], lbl = p[1], d = live[sym] || {};
        var pr  = d.price  ? fmtPrice(d.price)  : '—';
        var ch  = d.change !== undefined ? parseFloat(d.change) : null;
        var cls = ch === null ? '' : (ch >= 0 ? 'up' : 'down');
        var ct  = ch !== null ? fmtChange(ch) : '';
        half += '<span class="ticker-item"><span class="symbol">' + lbl + '</span><span class="price">' + pr + '</span>';
        if (ct) half += '<span class="change ' + cls + '">' + ct + '</span>';
        half += '</span><span class="ticker-sep">·</span>';
      });
      el.innerHTML = half + half;
    }

    function scheduleBuild() {
      if (buildTimer) return;
      buildTimer = setTimeout(function () { buildTimer = null; buildTicker(); }, 500);
    }

    function connect() {
      var streams = PAIRS.map(function (p) { return p[0] + '@ticker'; }).join('/');
      try { ws = new WebSocket('wss://stream.binance.com:9443/stream?streams=' + streams); }
      catch (e) { retry(); return; }
      ws.onopen  = function () { retryMs = 3000; };
      ws.onclose = function () { retry(); };
      ws.onerror = function () {};
      ws.onmessage = function (e) {
        try {
          var m = JSON.parse(e.data);
          if (!m || !m.data) return;
          var sym = (m.data.s || '').toLowerCase();
          live[sym] = { price: m.data.c, change: m.data.P };
          scheduleBuild();
        } catch (err) { /* ignore */ }
      };
    }

    function retry() {
      setTimeout(connect, retryMs);
      retryMs = Math.min(retryMs * 1.5, 30000);
    }

    connect();
    buildTicker();
  }());


  /* ============================================================
     2. INTERSECTION OBSERVER — Fade-up
  ============================================================ */
  (function () {
    'use strict';
    if (!('IntersectionObserver' in window)) {
      document.querySelectorAll('.ats-fade-up').forEach(function (el) {
        el.classList.add('ats-visible');
      });
      return;
    }
    var obs = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('ats-visible');
          obs.unobserve(entry.target);
        }
      });
    }, { threshold: 0.1 });
    document.querySelectorAll('.ats-fade-up').forEach(function (el) { obs.observe(el); });
  }());


  /* ============================================================
     3. PERFORMANCE — passive listeners, debounced resize
  ============================================================ */
  (function () {
    'use strict';
    var ticking = false;
    window.addEventListener('scroll', function () {
      if (!ticking) {
        requestAnimationFrame(function () { ticking = false; });
        ticking = true;
      }
    }, { passive: true });
    var resizeTimer;
    window.addEventListener('resize', function () {
      clearTimeout(resizeTimer);
      resizeTimer = setTimeout(function () {}, 150);
    }, { passive: true });
  }());

  </script>

</body>
</html>