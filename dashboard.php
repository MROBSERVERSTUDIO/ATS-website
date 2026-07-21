<?php
session_start();

// Guard: bounce back to login if no active session
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}

$username = $_SESSION['username'] ?? 'there';
$email = $_SESSION['email'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
  <meta name="description" content="ATS - AI-Powered Trading Dashboard">
  <meta name="theme-color" content="#0B1020">
  <title>ATS - Dashboard | AI-Powered Trading That Works 24/7</title>
  <link rel="icon" type="image/png" href="assets/images/favicon.png" sizes="16x16">
  <link rel="stylesheet" href="assets/css/vendor/bootstrap.min.css">
  <link rel="stylesheet" href="assets/css/all.min.css">
  <link rel="stylesheet" href="assets/css/line-awesome.min.css">
  <link rel="stylesheet" href="assets/css/vendor/animate.min.css">
  <link rel="stylesheet" href="assets/css/vendor/slick.css">
  <link rel="stylesheet" href="assets/css/vendor/dots.css">
  <link rel="stylesheet" href="assets/css/main.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.45.1/apexcharts.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

  <style>
    :root {
      --primary: #0A84FF;
      --secondary: #00D4FF;
      --accent: #00FFB3;
      --danger: #ff4d6d;
      --warning: #ffd700;
      --purple: #9333ea;
      --bg-main: #0B1020;
      --bg-card: rgba(20, 27, 52, 0.85);
      --border: rgba(10, 132, 255, 0.15);
      --border-hover: rgba(0, 212, 255, 0.35);
      --text: #e8eaf6;
      --text-muted: rgba(232, 234, 246, 0.5);
      --radius: 14px;
      --radius-sm: 8px;
      --shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
      --shadow-blue: 0 8px 32px rgba(10, 132, 255, 0.2);
      --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      --sidebar-width: 260px;
      --ticker-height: 38px;
      --z-sidebar: 300;
      --z-s-overlay: 299;
      --z-ticker: 400;
      --z-fab: 600;
      --z-toast: 700;
      --z-notif: 9000;
    }

    *,
    *::before,
    *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    html {
      overflow-x: hidden;
      scroll-behavior: smooth;
      -webkit-text-size-adjust: 100%;
    }

    body {
      background: var(--bg-main);
      color: var(--text);
      font-family: 'Exo', 'Josefin Sans', sans-serif;
      font-size: 14px;
      line-height: 1.6;
      overflow-x: hidden;
      -webkit-font-smoothing: antialiased;
    }

    body.scroll-locked {
      overflow: hidden !important;
      padding-right: var(--sb-width, 0px);
    }

    ::-webkit-scrollbar {
      width: 5px;
      height: 5px;
    }

    ::-webkit-scrollbar-track {
      background: transparent;
    }

    ::-webkit-scrollbar-thumb {
      background: rgba(10, 132, 255, 0.3);
      border-radius: 3px;
    }

    ::-webkit-scrollbar-thumb:hover {
      background: rgba(10, 132, 255, 0.5);
    }

    .ats-ticker-bar {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      z-index: var(--z-ticker);
      background: rgba(11, 16, 32, 0.97);
      backdrop-filter: blur(10px);
      border-bottom: 1px solid var(--border);
      height: var(--ticker-height);
      display: flex;
      align-items: center;
      overflow: hidden;
      white-space: nowrap;
    }

    .ticker-inner {
      display: inline-flex;
      align-items: center;
      gap: 28px;
      animation: tickerScroll 60s linear infinite;
      will-change: transform;
      padding-left: 20px;
    }

    .ats-ticker-bar:hover .ticker-inner {
      animation-play-state: paused;
    }

    .ticker-item {
      display: inline-flex;
      align-items: center;
      gap: 7px;
      font-size: 11px;
      font-weight: 600;
      flex-shrink: 0;
    }

    .ticker-item .symbol {
      color: var(--text-muted);
    }

    .ticker-item .price {
      color: var(--text);
      font-family: 'Josefin Sans', monospace;
    }

    .ticker-item .up {
      color: var(--accent);
    }

    .ticker-item .down {
      color: var(--danger);
    }

    .ticker-sep {
      color: rgba(10, 132, 255, 0.3);
      font-size: 10px;
    }

    @keyframes tickerScroll {
      0% {
        transform: translateX(0)
      }

      100% {
        transform: translateX(-50%)
      }
    }

    .dash-shell {
      display: flex;
      min-height: 100vh;
      padding-top: var(--ticker-height);
    }

    .dash-sidebar {
      position: fixed;
      top: var(--ticker-height);
      left: 0;
      width: var(--sidebar-width);
      height: calc(100vh - var(--ticker-height));
      background: rgba(14, 20, 40, 0.98);
      backdrop-filter: blur(16px);
      border-right: 1px solid var(--border);
      overflow-y: auto;
      overflow-x: hidden;
      z-index: var(--z-sidebar);
      transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      will-change: transform;
      transform: translateX(0);
    }

    .dash-sidebar::-webkit-scrollbar {
      width: 3px;
    }

    .dash-sidebar::-webkit-scrollbar-thumb {
      background: rgba(10, 132, 255, 0.2);
    }

    @media (max-width:820px) {
      .dash-sidebar {
        transform: translateX(-100%);
      }

      .dash-sidebar.sidebar-is-open {
        transform: translateX(0);
      }
    }

    .sidebar-close-btn {
      display: none;
      position: absolute;
      top: 18px;
      right: 18px;
      width: 38px;
      height: 38px;
      min-width: 38px;
      background: rgba(255, 255, 255, 0.07);
      border: 1px solid rgba(255, 255, 255, 0.12);
      border-radius: 10px;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      color: #e8eaf6 !important;
      font-size: 20px !important;
      line-height: 1;
      visibility: visible !important;
      opacity: 1 !important;
      z-index: 10;
      transition: var(--transition);
    }

    .sidebar-close-btn:hover {
      color: var(--danger) !important;
      background: rgba(255, 77, 109, 0.12);
    }

    @media (max-width:820px) {
      .sidebar-close-btn {
        display: flex;
      }
    }

    .sidebar-ai-block {
      margin: 16px;
      padding: 14px;
      background: rgba(0, 255, 179, 0.06);
      border: 1px solid rgba(0, 255, 179, 0.18);
      border-radius: var(--radius-sm);
    }

    .ai-block-label {
      font-size: 10px;
      color: var(--accent);
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 1.5px;
      margin-bottom: 8px;
      display: flex;
      align-items: center;
      gap: 5px;
    }

    .ai-block-row {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .ai-block-status {
      font-size: 13px;
      font-weight: 700;
      color: #fff;
    }

    .ai-block-sub {
      font-size: 11px;
      color: var(--text-muted);
      margin-top: 2px;
    }

    .ai-pulse-dot {
      width: 8px;
      height: 8px;
      background: var(--accent);
      border-radius: 50%;
      flex-shrink: 0;
      box-shadow: 0 0 8px rgba(0, 255, 179, 0.8);
      animation: pulseDot 1.5s ease-in-out infinite;
    }

    @keyframes pulseDot {

      0%,
      100% {
        opacity: 1;
        box-shadow: 0 0 8px rgba(0, 255, 179, 0.8)
      }

      50% {
        opacity: 0.4;
        box-shadow: 0 0 3px rgba(0, 255, 179, 0.3)
      }
    }

    .sidebar-section-label {
      padding: 14px 20px 6px;
      font-size: 10px;
      color: var(--text-muted);
      text-transform: uppercase;
      letter-spacing: 2px;
      font-weight: 700;
      user-select: none;
    }

    .sidebar-nav {
      list-style: none;
      padding: 0;
      margin: 0;
    }

    .sidebar-nav li a {
      display: flex;
      align-items: center;
      gap: 11px;
      padding: 11px 20px;
      color: var(--text-muted);
      font-size: 13px;
      font-weight: 600;
      border-left: 3px solid transparent;
      text-decoration: none;
      transition: var(--transition);
      cursor: pointer;
      min-height: 44px;
    }

    .sidebar-nav li a:hover,
    .sidebar-nav li a.active {
      color: var(--secondary);
      background: rgba(10, 132, 255, 0.08);
      border-left-color: var(--primary);
    }

    .sidebar-nav li a:focus-visible {
      outline: 2px solid var(--primary);
      outline-offset: -2px;
    }

    .sidebar-nav li a i {
      font-size: 18px;
      width: 22px;
      text-align: center;
      flex-shrink: 0;
    }

    .sidebar-nav li a .nav-label {
      flex: 1 1 auto;
      min-width: 0;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
    }

    .sidebar-nav li a .nav-badge {
      flex-shrink: 0;
      background: var(--danger);
      color: #fff;
      font-size: 10px;
      font-weight: 700;
      padding: 2px 7px;
      border-radius: 10px;
      line-height: 1.4;
    }

    .sidebar-overlay {
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, 0.65);
      backdrop-filter: blur(3px);
      z-index: var(--z-s-overlay);
      opacity: 0;
      pointer-events: none;
      transition: opacity 0.3s ease;
    }

    .sidebar-overlay.overlay-visible {
      opacity: 1;
      pointer-events: all;
    }

    .dash-main {
      flex: 1;
      margin-left: var(--sidebar-width);
      min-width: 0;
      padding: 20px;
      transition: margin-left 0.3s ease;
    }

    @media (max-width:820px) {
      .dash-main {
        margin-left: 0;
        padding: 14px;
      }
    }

    @media (max-width:480px) {
      .dash-main {
        padding: 10px;
      }
    }

    @media (max-width:360px) {
      .dash-main {
        padding: 8px;
      }
    }

    .dash-topbar {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 12px;
      margin-bottom: 20px;
    }

    .topbar-left {
      display: flex;
      align-items: center;
      gap: 12px;
      min-width: 0;
    }

    .sidebar-toggle-btn {
      display: none;
      width: 44px;
      height: 44px;
      background: var(--bg-card);
      border: 1px solid var(--border);
      border-radius: var(--radius-sm);
      align-items: center;
      justify-content: center;
      cursor: pointer;
      color: var(--text-muted);
      font-size: 22px;
      transition: var(--transition);
      flex-shrink: 0;
      position: relative;
      z-index: 10;
    }

    .sidebar-toggle-btn:hover {
      border-color: var(--border-hover);
      color: var(--secondary);
    }

    @media (max-width:820px) {
      .sidebar-toggle-btn {
        display: flex;
      }
    }

    .dash-greeting h4 {
      font-size: 17px;
      font-weight: 700;
      color: #fff;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      font-family: 'Josefin Sans', sans-serif;
    }

    .dash-greeting p {
      font-size: 12px;
      color: var(--text-muted);
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    @media (max-width:414px) {
      .dash-greeting h4 {
        font-size: 14px;
      }

      .dash-greeting p {
        font-size: 11px;
      }
    }

    .topbar-actions {
      display: flex;
      align-items: center;
      gap: 8px;
      flex-shrink: 0;
    }

    .topbar-icon-btn {
      position: relative;
      width: 44px;
      height: 44px;
      background: var(--bg-card);
      border: 1px solid var(--border);
      border-radius: var(--radius-sm);
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      color: var(--text-muted);
      font-size: 18px;
      transition: var(--transition);
      flex-shrink: 0;
    }

    .topbar-icon-btn:hover {
      border-color: var(--border-hover);
      color: var(--secondary);
    }

    .topbar-icon-btn .notif-dot {
      position: absolute;
      top: 8px;
      right: 8px;
      width: 8px;
      height: 8px;
      background: var(--danger);
      border-radius: 50%;
      border: 2px solid var(--bg-main);
    }

    .topbar-avatar {
      width: 44px;
      height: 44px;
      background: linear-gradient(135deg, var(--primary), var(--accent));
      border-radius: var(--radius-sm);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 16px;
      font-weight: 700;
      color: #fff;
      cursor: pointer;
      font-family: 'Josefin Sans', sans-serif;
      flex-shrink: 0;
      transition: var(--transition);
    }

    .topbar-avatar:hover {
      transform: scale(1.05);
    }

    @media (max-width:414px) {

      .topbar-icon-btn,
      .topbar-avatar {
        width: 38px;
        height: 38px;
      }

      .topbar-actions {
        gap: 6px;
      }
    }

    .notif-overlay {
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, 0.6);
      backdrop-filter: blur(3px);
      z-index: calc(var(--z-notif) - 1);
      opacity: 0;
      pointer-events: none;
      transition: opacity 0.3s ease;
    }

    .notif-overlay.notif-overlay-visible {
      opacity: 1;
      pointer-events: all;
    }

    .notif-drawer {
      position: fixed;
      top: 0;
      right: -100%;
      width: min(380px, 100vw);
      height: 100vh;
      background: rgba(14, 20, 40, 0.99);
      backdrop-filter: blur(16px);
      border-left: 1px solid var(--border);
      z-index: var(--z-notif);
      display: flex;
      flex-direction: column;
      transition: right 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      will-change: right;
      overflow: visible;
    }

    @media (max-width:820px) {
      .notif-drawer {
        height: 100dvh;
      }

      @supports not (height:100dvh) {
        .notif-drawer {
          height: 100vh;
        }
      }
    }

    .notif-drawer.notif-is-open {
      right: 0;
    }

    .notif-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 18px 18px 14px;
      border-bottom: 1px solid var(--border);
      flex-shrink: 0;
      position: relative;
      z-index: 1;
      overflow: visible;
    }

    .notif-header h5 {
      font-size: 15px;
      font-weight: 700;
      color: #fff;
      font-family: 'Josefin Sans', sans-serif;
      display: flex;
      align-items: center;
      gap: 8px;
      margin: 0;
    }

    .notif-header-actions {
      display: flex;
      align-items: center;
      gap: 8px;
      flex-shrink: 0;
    }

    .notif-mark-all-btn {
      font-size: 11px;
      color: var(--primary);
      font-weight: 700;
      cursor: pointer;
      background: none;
      border: none;
      padding: 6px 8px;
      min-height: 36px;
      transition: color 0.2s;
      white-space: nowrap;
    }

    .notif-mark-all-btn:hover {
      color: var(--secondary);
    }

    .notif-close-btn {
      display: flex !important;
      align-items: center !important;
      justify-content: center !important;
      flex-shrink: 0 !important;
      width: 44px !important;
      height: 44px !important;
      min-width: 44px !important;
      min-height: 44px !important;
      background: rgba(255, 255, 255, 0.08) !important;
      border: 2px solid rgba(255, 255, 255, 0.2) !important;
      border-radius: 8px !important;
      color: #ffffff !important;
      font-size: 22px !important;
      line-height: 1 !important;
      font-family: Arial, sans-serif;
      position: relative !important;
      z-index: 9999 !important;
      visibility: visible !important;
      opacity: 1 !important;
      pointer-events: all !important;
      overflow: visible !important;
      cursor: pointer;
      transition: var(--transition);
      user-select: none;
    }

    .notif-close-btn:hover {
      background: rgba(255, 77, 109, 0.25) !important;
      border-color: rgba(255, 77, 109, 0.6) !important;
      color: #ff4d6d !important;
    }

    .notif-close-btn .close-icon {
      display: none;
      font-size: 20px !important;
      color: inherit !important;
      line-height: 1;
    }

    .notif-close-btn .close-unicode {
      display: block;
      font-size: 24px !important;
      font-weight: 400;
      color: inherit !important;
      line-height: 1;
      font-family: Arial, sans-serif;
    }

    .notif-list {
      flex: 1 1 auto;
      min-height: 0;
      overflow-y: auto;
      overflow-x: hidden;
      padding: 10px;
    }

    .notif-item {
      display: flex;
      gap: 11px;
      padding: 13px;
      border-radius: 10px;
      margin-bottom: 8px;
      background: rgba(10, 132, 255, 0.04);
      border: 1px solid transparent;
      cursor: pointer;
      transition: var(--transition);
    }

    .notif-item:hover {
      border-color: var(--border);
    }

    .notif-item.unread {
      border-color: rgba(10, 132, 255, 0.2);
      background: rgba(10, 132, 255, 0.07);
    }

    .notif-icon {
      width: 38px;
      height: 38px;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 18px;
      flex-shrink: 0;
    }

    .notif-body {
      flex: 1;
      min-width: 0;
    }

    .notif-title {
      font-size: 13px;
      font-weight: 700;
      color: #fff;
      margin-bottom: 3px;
    }

    .notif-msg {
      font-size: 12px;
      color: var(--text-muted);
      line-height: 1.5;
      word-break: break-word;
    }

    .notif-time {
      font-size: 11px;
      color: rgba(232, 234, 246, 0.3);
      margin-top: 5px;
    }

    .notif-unread-dot {
      width: 7px;
      height: 7px;
      background: var(--primary);
      border-radius: 50%;
      flex-shrink: 0;
      margin-top: 5px;
    }

    .notif-empty {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 48px 20px;
      text-align: center;
    }

    .notif-empty i {
      font-size: 40px;
      color: rgba(10, 132, 255, 0.2);
      margin-bottom: 12px;
    }

    .notif-empty p {
      font-size: 13px;
      color: var(--text-muted);
    }

    .notif-footer {
      padding: 12px 16px;
      border-top: 1px solid var(--border);
      flex-shrink: 0;
    }

    .ats-card {
      background: var(--bg-card);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      backdrop-filter: blur(12px);
      transition: border-color 0.3s, box-shadow 0.3s;
      overflow: hidden;
      position: relative;
    }

    .ats-card:hover {
      border-color: var(--border-hover);
      box-shadow: var(--shadow-blue);
    }

    .ats-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 1px;
      background: linear-gradient(90deg, transparent, rgba(10, 132, 255, 0.4), transparent);
      opacity: 0;
      transition: opacity 0.3s;
    }

    .ats-card:hover::before {
      opacity: 1;
    }

    .card-head {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 16px 18px 12px;
      border-bottom: 1px solid var(--border);
      gap: 10px;
      flex-wrap: wrap;
    }

    .card-head-title {
      font-size: 14px;
      font-weight: 700;
      color: #fff;
      font-family: 'Josefin Sans', sans-serif;
      display: flex;
      align-items: center;
      gap: 8px;
      margin: 0;
    }

    .card-head-title i {
      color: var(--secondary);
      font-size: 16px;
    }

    .card-body {
      padding: 18px;
    }

    .stat-cards-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 14px;
      margin-bottom: 20px;
    }

    @media (max-width:1023px) {
      .stat-cards-grid {
        grid-template-columns: repeat(2, 1fr);
      }
    }

    @media (max-width:599px) {
      .stat-cards-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
      }
    }

    @media (max-width:359px) {
      .stat-cards-grid {
        gap: 7px;
      }
    }

    .stat-card {
      background: var(--bg-card);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      padding: 18px 16px 14px;
      position: relative;
      overflow: hidden;
      transition: var(--transition);
      cursor: default;
    }

    .stat-card:hover {
      transform: translateY(-4px);
      border-color: var(--border-hover);
      box-shadow: var(--shadow-blue);
    }

    .stat-card::after {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 2px;
      opacity: 0;
      transition: opacity 0.3s;
    }

    .stat-card:hover::after {
      opacity: 1;
    }

    .sc-blue::after {
      background: linear-gradient(90deg, var(--primary), var(--secondary));
    }

    .sc-green::after {
      background: linear-gradient(90deg, #00b37a, var(--accent));
    }

    .sc-cyan::after {
      background: linear-gradient(90deg, var(--secondary), var(--primary));
    }

    .sc-gold::after {
      background: linear-gradient(90deg, var(--warning), #ff8c00);
    }

    .sc-purple::after {
      background: linear-gradient(90deg, #9333ea, var(--primary));
    }

    .sc-red::after {
      background: linear-gradient(90deg, var(--danger), #9333ea);
    }

    .sc-teal::after {
      background: linear-gradient(90deg, #00b4d8, var(--accent));
    }

    .stat-icon {
      width: 42px;
      height: 42px;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 19px;
      margin-bottom: 12px;
      transition: transform 0.3s;
    }

    .stat-card:hover .stat-icon {
      transform: scale(1.1) rotate(-5deg);
    }

    .sc-blue .stat-icon {
      background: rgba(10, 132, 255, 0.15);
      color: var(--primary);
    }

    .sc-green .stat-icon {
      background: rgba(0, 255, 179, 0.12);
      color: var(--accent);
    }

    .sc-cyan .stat-icon {
      background: rgba(0, 212, 255, 0.12);
      color: var(--secondary);
    }

    .sc-gold .stat-icon {
      background: rgba(255, 215, 0, 0.1);
      color: var(--warning);
    }

    .sc-purple .stat-icon {
      background: rgba(147, 51, 234, 0.12);
      color: #9333ea;
    }

    .sc-red .stat-icon {
      background: rgba(255, 77, 109, 0.12);
      color: var(--danger);
    }

    .sc-teal .stat-icon {
      background: rgba(0, 180, 216, 0.12);
      color: #00b4d8;
    }

    .stat-label {
      font-size: 10px;
      color: var(--text-muted);
      text-transform: uppercase;
      letter-spacing: 1px;
      font-weight: 700;
      margin-bottom: 5px;
    }

    .stat-value {
      font-size: 22px;
      font-weight: 700;
      color: #fff;
      font-family: 'Josefin Sans', sans-serif;
      line-height: 1;
      margin-bottom: 8px;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
    }

    @media (max-width:479px) {
      .stat-value {
        font-size: 17px;
      }
    }

    @media (max-width:375px) {
      .stat-value {
        font-size: 15px;
      }

      .stat-icon {
        width: 36px;
        height: 36px;
        font-size: 16px;
        margin-bottom: 8px;
      }
    }

    @media (max-width:320px) {
      .stat-value {
        font-size: 13px;
      }

      .stat-card {
        padding: 12px 10px 10px;
      }
    }

    .stat-trend {
      display: inline-flex;
      align-items: center;
      gap: 4px;
      font-size: 11px;
      font-weight: 600;
    }

    .stat-trend.up {
      color: var(--accent);
    }

    .stat-trend.down {
      color: var(--danger);
    }

    .stat-trend.flat {
      color: var(--text-muted);
    }

    .stat-mini-chart {
      height: 40px !important;
      min-height: 40px;
      overflow: hidden;
      margin-top: 8px;
      width: 100%;
      position: relative;
    }

    .stat-mini-chart .apexcharts-legend,
    .stat-mini-chart .apexcharts-toolbar {
      display: none !important;
    }

    .section-head {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 16px;
      gap: 10px;
      flex-wrap: wrap;
    }

    .section-head h5 {
      font-size: 15px;
      font-weight: 700;
      color: #fff;
      font-family: 'Josefin Sans', sans-serif;
      display: flex;
      align-items: center;
      gap: 8px;
      margin: 0;
    }

    .section-head h5 i {
      color: var(--secondary);
    }

    .section-head a,
    .section-head button {
      font-size: 12px;
      color: var(--primary);
      font-weight: 600;
      background: none;
      border: none;
      cursor: pointer;
      transition: color 0.2s;
      padding: 4px;
    }

    .section-head a:hover,
    .section-head button:hover {
      color: var(--secondary);
    }

    .quick-actions-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 10px;
      margin-bottom: 16px;
    }

    @media (max-width:599px) {
      .quick-actions-grid {
        grid-template-columns: repeat(2, 1fr);
      }
    }

    .qa-item {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 8px;
      padding: 16px 10px;
      background: var(--bg-card);
      border: 1px solid var(--border);
      border-radius: var(--radius-sm);
      cursor: pointer;
      transition: var(--transition);
      text-align: center;
      color: var(--text-muted);
      font-size: 12px;
      font-weight: 600;
      min-height: 44px;
    }

    .qa-item:hover {
      border-color: var(--border-hover);
      color: var(--secondary);
      transform: translateY(-3px);
      box-shadow: var(--shadow-blue);
    }

    .qa-icon {
      width: 40px;
      height: 40px;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 20px;
      transition: transform 0.3s;
    }

    .qa-item:hover .qa-icon {
      transform: scale(1.12);
    }

    .qa-item.deposit .qa-icon {
      background: rgba(0, 255, 179, 0.12);
      color: var(--accent);
    }

    .qa-item.withdraw .qa-icon {
      background: rgba(10, 132, 255, 0.12);
      color: var(--primary);
    }

    .qa-item.invest .qa-icon {
      background: rgba(255, 215, 0, 0.1);
      color: var(--warning);
    }

    .qa-item.refer .qa-icon {
      background: rgba(147, 51, 234, 0.12);
      color: #9333ea;
    }

    .gauge-wrap {
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 18px 16px;
    }

    .gauge-ring {
      position: relative;
      width: 130px;
      height: 130px;
      flex-shrink: 0;
    }

    .gauge-ring svg {
      transform: rotate(-90deg);
    }

    .gauge-track {
      fill: none;
      stroke: rgba(10, 132, 255, 0.1);
      stroke-width: 10;
    }

    .gauge-fill {
      fill: none;
      stroke-width: 10;
      stroke-linecap: round;
      stroke: url(#gaugeGrad);
      stroke-dasharray: 345;
      stroke-dashoffset: 345;
      transition: stroke-dashoffset 1.5s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .gauge-center {
      position: absolute;
      inset: 0;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
    }

    .gauge-val {
      font-size: 26px;
      font-weight: 700;
      color: #fff;
      font-family: 'Josefin Sans', sans-serif;
      line-height: 1;
    }

    .gauge-sub {
      font-size: 10px;
      color: var(--text-muted);
      text-transform: uppercase;
      letter-spacing: 1px;
      margin-top: 3px;
    }

    .gauge-desc {
      font-size: 12px;
      color: var(--text-muted);
      text-align: center;
      margin-top: 10px;
    }

    .gauge-legend {
      display: flex;
      gap: 12px;
      margin-top: 10px;
      flex-wrap: wrap;
      justify-content: center;
    }

    .gauge-legend span {
      font-size: 10px;
      color: var(--text-muted);
      display: flex;
      align-items: center;
      gap: 5px;
    }

    .gauge-legend span::before {
      content: '';
      width: 7px;
      height: 7px;
      border-radius: 50%;
    }

    .gl-good::before {
      background: var(--accent);
    }

    .gl-mid::before {
      background: var(--warning);
    }

    .gl-low::before {
      background: var(--danger);
    }

    .plan-card {
      background: var(--bg-card);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      padding: 18px;
      position: relative;
      overflow: hidden;
      transition: var(--transition);
    }

    .plan-card:hover {
      transform: translateY(-4px);
      border-color: var(--border-hover);
      box-shadow: var(--shadow-blue);
    }

    .plan-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 3px;
    }

    .plan-card.starter::before {
      background: linear-gradient(90deg, var(--primary), var(--secondary));
    }

    .plan-card.elite::before {
      background: linear-gradient(90deg, var(--warning), #ff8c00);
    }

    .plan-card.vip::before {
      background: linear-gradient(90deg, var(--danger), #9333ea);
    }

    .plan-card.unlimited::before {
      background: linear-gradient(90deg, var(--accent), var(--secondary));
    }

    .plan-badge {
      display: inline-flex;
      align-items: center;
      gap: 4px;
      padding: 3px 10px;
      border-radius: 20px;
      font-size: 10px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 1.5px;
      margin-bottom: 10px;
    }

    .plan-badge.starter {
      background: rgba(10, 132, 255, 0.15);
      color: var(--primary);
      border: 1px solid rgba(10, 132, 255, 0.3);
    }

    .plan-badge.elite {
      background: rgba(255, 215, 0, 0.1);
      color: var(--warning);
      border: 1px solid rgba(255, 215, 0, 0.3);
    }

    .plan-badge.vip {
      background: rgba(255, 77, 109, 0.12);
      color: var(--danger);
      border: 1px solid rgba(255, 77, 109, 0.3);
    }

    .plan-badge.unlimited {
      background: rgba(0, 255, 179, 0.1);
      color: var(--accent);
      border: 1px solid rgba(0, 255, 179, 0.3);
    }

    .plan-name {
      font-size: 15px;
      font-weight: 700;
      color: #fff;
      margin-bottom: 3px;
      font-family: 'Josefin Sans', sans-serif;
    }

    .plan-rate {
      font-size: 26px;
      font-weight: 700;
      color: var(--accent);
      font-family: 'Josefin Sans', sans-serif;
      line-height: 1;
    }

    .plan-rate-sub {
      font-size: 11px;
      color: var(--text-muted);
      margin-bottom: 12px;
    }

    .plan-detail {
      display: flex;
      justify-content: space-between;
      font-size: 12px;
      margin-bottom: 5px;
    }

    .plan-detail-label {
      color: var(--text-muted);
    }

    .plan-detail-val {
      color: #fff;
      font-weight: 600;
    }

    .plan-progress-wrap {
      margin: 12px 0;
    }

    .plan-progress-labels {
      display: flex;
      justify-content: space-between;
      font-size: 11px;
      color: var(--text-muted);
      margin-bottom: 5px;
    }

    .progress-bar-track {
      height: 6px;
      background: rgba(10, 132, 255, 0.1);
      border-radius: 3px;
      overflow: hidden;
    }

    .progress-bar-fill {
      height: 100%;
      border-radius: 3px;
      background: linear-gradient(90deg, var(--primary), var(--accent));
      transition: width 1.2s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .btn-ats {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 7px;
      padding: 11px 20px;
      border-radius: 10px;
      font-size: 13px;
      font-weight: 700;
      font-family: 'Josefin Sans', sans-serif;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      cursor: pointer;
      transition: var(--transition);
      border: none;
      text-decoration: none;
      white-space: nowrap;
      min-height: 44px;
      position: relative;
      overflow: hidden;
    }

    .btn-ats.primary {
      background: linear-gradient(135deg, var(--primary), var(--secondary));
      color: #fff;
      box-shadow: 0 4px 16px rgba(10, 132, 255, 0.4);
    }

    .btn-ats.primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 24px rgba(10, 132, 255, 0.5);
    }

    .btn-ats.outline {
      background: transparent;
      color: var(--secondary);
      border: 1px solid rgba(10, 132, 255, 0.3);
    }

    .btn-ats.outline:hover {
      background: rgba(10, 132, 255, 0.1);
      border-color: var(--border-hover);
    }

    .btn-ats.success {
      background: linear-gradient(135deg, #00b37a, var(--accent));
      color: #fff;
    }

    .btn-ats.sm {
      padding: 7px 14px;
      font-size: 12px;
      min-height: 36px;
    }

    .btn-ats.full {
      width: 100%;
    }

    .btn-ats:disabled {
      opacity: 0.5;
      cursor: not-allowed;
      transform: none !important;
    }

    .ats-tabs {
      display: flex;
      gap: 3px;
      background: rgba(10, 132, 255, 0.06);
      border-radius: 10px;
      padding: 3px;
      flex-wrap: wrap;
    }

    .ats-tab-btn {
      padding: 8px 14px;
      border-radius: 7px;
      font-size: 12px;
      font-weight: 600;
      color: var(--text-muted);
      cursor: pointer;
      transition: var(--transition);
      border: none;
      background: transparent;
      white-space: nowrap;
      min-height: 36px;
    }

    .ats-tab-btn.active {
      background: rgba(10, 132, 255, 0.2);
      color: var(--secondary);
      border: 1px solid rgba(10, 132, 255, 0.3);
    }

    .ats-tab-pane {
      display: none;
    }

    .ats-tab-pane.active {
      display: block;
    }

    .table-responsive-wrap {
      overflow-x: auto;
      -webkit-overflow-scrolling: touch;
      max-width: 100%;
    }

    .ats-table {
      width: 100%;
      border-collapse: collapse;
      min-width: 480px;
    }

    .ats-table th {
      font-size: 10px;
      font-weight: 700;
      color: var(--text-muted);
      text-transform: uppercase;
      letter-spacing: 1px;
      padding: 12px 14px;
      border-bottom: 1px solid var(--border);
      white-space: nowrap;
      background: rgba(10, 132, 255, 0.04);
    }

    .ats-table td {
      padding: 13px 14px;
      font-size: 13px;
      color: var(--text);
      border-bottom: 1px solid rgba(10, 132, 255, 0.06);
      white-space: nowrap;
      max-width: 200px;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .ats-table tr:hover td {
      background: rgba(10, 132, 255, 0.04);
    }

    .ats-table tr:last-child td {
      border-bottom: none;
    }

    .status-badge {
      display: inline-flex;
      align-items: center;
      gap: 4px;
      padding: 3px 10px;
      border-radius: 20px;
      font-size: 10px;
      font-weight: 700;
      white-space: nowrap;
    }

    .status-badge.active {
      background: rgba(0, 255, 179, 0.1);
      color: var(--accent);
      border: 1px solid rgba(0, 255, 179, 0.3);
    }

    .status-badge.pending {
      background: rgba(255, 215, 0, 0.1);
      color: var(--warning);
      border: 1px solid rgba(255, 215, 0, 0.3);
    }

    .status-badge.expired {
      background: rgba(255, 77, 109, 0.1);
      color: var(--danger);
      border: 1px solid rgba(255, 77, 109, 0.3);
    }

    .status-badge.complete {
      background: rgba(10, 132, 255, 0.12);
      color: var(--secondary);
      border: 1px solid rgba(10, 132, 255, 0.3);
    }

    .status-badge.processing {
      background: rgba(0, 180, 216, 0.12);
      color: #00b4d8;
      border: 1px solid rgba(0, 180, 216, 0.3);
    }

    .status-badge.cancelled {
      background: rgba(150, 150, 160, 0.12);
      color: #9a9aae;
      border: 1px solid rgba(150, 150, 160, 0.3);
    }

    .form-group {
      margin-bottom: 16px;
    }

    .form-label {
      display: block;
      font-size: 11px;
      font-weight: 700;
      color: var(--text-muted);
      text-transform: uppercase;
      letter-spacing: 1px;
      margin-bottom: 7px;
    }

    .ats-input,
    .ats-select,
    .ats-textarea {
      width: 100%;
      background: rgba(11, 16, 32, 0.8);
      border: 1px solid var(--border);
      color: var(--text);
      border-radius: 10px;
      padding: 12px 14px;
      font-size: 14px;
      transition: border-color 0.2s, box-shadow 0.2s;
      appearance: none;
      -webkit-appearance: none;
      font-family: inherit;
      min-height: 44px;
    }

    .ats-input:focus,
    .ats-select:focus,
    .ats-textarea:focus {
      outline: none;
      border-color: rgba(0, 212, 255, 0.5);
      box-shadow: 0 0 0 3px rgba(10, 132, 255, 0.1);
    }

    .ats-textarea {
      resize: vertical;
      min-height: 100px;
    }

    .input-wrap {
      position: relative;
    }

    .input-wrap .ats-input {
      padding-left: 42px;
    }

    .input-icon {
      position: absolute;
      left: 14px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--text-muted);
      font-size: 16px;
      pointer-events: none;
    }

    .input-error {
      display: none;
      font-size: 12px;
      color: var(--danger);
      margin-top: 6px;
      padding: 8px 12px;
      background: rgba(255, 77, 109, 0.07);
      border: 1px solid rgba(255, 77, 109, 0.2);
      border-radius: 7px;
    }

    .input-error.show {
      display: block;
    }

    .toggle-wrap {
      display: flex;
      align-items: center;
      gap: 10px;
      cursor: pointer;
      min-height: 44px;
    }

    .toggle-switch {
      position: relative;
      width: 44px;
      height: 24px;
      flex-shrink: 0;
    }

    .toggle-switch input {
      display: none;
    }

    .toggle-track {
      position: absolute;
      inset: 0;
      background: rgba(10, 132, 255, 0.15);
      border-radius: 24px;
      border: 1px solid rgba(10, 132, 255, 0.3);
      transition: var(--transition);
    }

    .toggle-thumb {
      position: absolute;
      top: 3px;
      left: 3px;
      width: 16px;
      height: 16px;
      background: var(--text-muted);
      border-radius: 50%;
      transition: var(--transition);
    }

    .toggle-switch input:checked~.toggle-track {
      background: rgba(0, 255, 179, 0.2);
      border-color: rgba(0, 255, 179, 0.4);
    }

    .toggle-switch input:checked~.toggle-thumb {
      transform: translateX(20px);
      background: var(--accent);
    }

    .dep-step {
      display: flex;
      gap: 14px;
      margin-bottom: 22px;
      align-items: flex-start;
    }

    .dep-num {
      width: 30px;
      height: 30px;
      background: rgba(10, 132, 255, 0.15);
      border: 1px solid rgba(10, 132, 255, 0.3);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 12px;
      font-weight: 700;
      color: var(--secondary);
      flex-shrink: 0;
      margin-top: 2px;
    }

    .dep-body {
      flex: 1;
      min-width: 0;
    }

    .dep-body h6 {
      font-size: 13px;
      font-weight: 700;
      color: #fff;
      margin-bottom: 8px;
    }

    .wallet-addr-box {
      display: flex;
      align-items: center;
      gap: 8px;
      background: rgba(10, 132, 255, 0.06);
      border: 1px solid var(--border);
      border-radius: 10px;
      padding: 11px 14px;
      flex-wrap: wrap;
    }

    .wallet-addr-text {
      flex: 1;
      font-size: 12px;
      color: var(--secondary);
      font-family: monospace;
      word-break: break-all;
      min-width: 0;
    }

    .copy-addr-btn {
      background: rgba(10, 132, 255, 0.15);
      border: 1px solid rgba(10, 132, 255, 0.3);
      color: var(--primary);
      border-radius: 7px;
      padding: 6px 12px;
      font-size: 12px;
      font-weight: 700;
      cursor: pointer;
      transition: var(--transition);
      white-space: nowrap;
      min-height: 36px;
    }

    .copy-addr-btn:hover {
      background: rgba(10, 132, 255, 0.25);
    }

    .qr-code-wrap {
      display: flex;
      justify-content: center;
      padding: 14px;
      background: #fff;
      border-radius: 10px;
      width: fit-content;
      max-width: 100%;
      margin: 10px auto;
    }

    .upload-zone {
      border: 2px dashed rgba(10, 132, 255, 0.3);
      border-radius: 10px;
      padding: 28px 16px;
      text-align: center;
      cursor: pointer;
      transition: var(--transition);
      min-height: 100px;
    }

    .upload-zone:hover {
      border-color: rgba(0, 212, 255, 0.5);
      background: rgba(10, 132, 255, 0.04);
    }

    .upload-zone i {
      font-size: 32px;
      color: var(--text-muted);
      margin-bottom: 8px;
      display: block;
    }

    .upload-zone p {
      font-size: 12px;
      color: var(--text-muted);
      margin: 0;
    }

    .upload-preview-wrap {
      display: none;
      margin-top: 10px;
    }

    .upload-preview-wrap img {
      width: 100%;
      border-radius: 8px;
      border: 1px solid var(--border);
      max-height: 180px;
      object-fit: cover;
    }

    /* Payment method selector cards (dynamic, driven by PAYMENT_METHODS) */
    .pm-select-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
      gap: 8px;
      margin-bottom: 4px;
    }

    .pm-select-card {
      background: rgba(10, 132, 255, 0.04);
      border: 1px solid var(--border);
      border-radius: 10px;
      padding: 10px 8px;
      text-align: center;
      cursor: pointer;
      transition: var(--transition);
    }

    .pm-select-card:hover {
      border-color: var(--border-hover);
    }

    .pm-select-card.selected {
      border-color: var(--primary);
      background: rgba(10, 132, 255, 0.12);
    }

    .pm-select-icon {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      margin: 0 auto 6px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 14px;
      color: #fff;
      font-weight: 700;
    }

    .pm-select-name {
      font-size: 11px;
      font-weight: 700;
      color: #fff;
    }

    .pm-select-net {
      font-size: 9px;
      color: var(--text-muted);
    }

    .pm-meta-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 8px;
      margin-top: 10px;
    }

    .pm-meta-box {
      background: rgba(0, 0, 0, 0.15);
      border-radius: 8px;
      padding: 8px 10px;
    }

    .pm-meta-box .lbl {
      font-size: 9px;
      color: var(--text-muted);
      text-transform: uppercase;
    }

    .pm-meta-box .val {
      font-size: 12px;
      font-weight: 700;
      color: #fff;
    }

    .wd-reason-box {
      background: rgba(255, 77, 109, 0.06);
      border: 1px solid rgba(255, 77, 109, 0.2);
      border-radius: 8px;
      padding: 10px 12px;
      margin-top: 6px;
    }

    .wd-reason-label {
      font-size: 9px;
      color: var(--danger);
      text-transform: uppercase;
      letter-spacing: 1px;
      font-weight: 700;
      margin-bottom: 4px;
    }

    .wd-reason-text {
      font-size: 11px;
      color: var(--text-muted);
      line-height: 1.6;
    }

    .ref-link-box {
      display: flex;
      align-items: center;
      gap: 8px;
      background: rgba(10, 132, 255, 0.06);
      border: 1px solid var(--border);
      border-radius: 10px;
      padding: 13px 16px;
      margin-bottom: 14px;
      flex-wrap: wrap;
    }

    .ref-link-text {
      flex: 1;
      font-size: 12px;
      color: var(--secondary);
      font-family: monospace;
      word-break: break-all;
      min-width: 0;
    }

    .ref-stats-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 12px;
    }

    .ref-stat-box {
      background: var(--bg-card);
      border: 1px solid var(--border);
      border-radius: 10px;
      padding: 16px;
      text-align: center;
      transition: var(--transition);
    }

    .ref-stat-box:hover {
      border-color: var(--border-hover);
      transform: translateY(-2px);
    }

    .ref-stat-val {
      font-size: 24px;
      font-weight: 700;
      color: var(--accent);
      font-family: 'Josefin Sans', sans-serif;
    }

    .ref-stat-lbl {
      font-size: 10px;
      color: var(--text-muted);
      text-transform: uppercase;
      letter-spacing: 1px;
      margin-top: 3px;
    }

    .badge-icon {
      width: 56px;
      height: 56px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 24px;
      border: 2px solid;
      transition: var(--transition);
      margin: 0 auto 8px;
    }

    .badge-icon:hover {
      transform: scale(1.1) rotate(5deg);
    }

    .badge-icon.gold {
      background: rgba(255, 215, 0, 0.1);
      border-color: rgba(255, 215, 0, 0.4);
      color: var(--warning);
    }

    .badge-icon.silver {
      background: rgba(192, 192, 192, 0.1);
      border-color: rgba(192, 192, 192, 0.4);
      color: #c0c0c0;
    }

    .badge-icon.bronze {
      background: rgba(205, 127, 50, 0.1);
      border-color: rgba(205, 127, 50, 0.4);
      color: #cd7f32;
    }

    .badge-icon.blue {
      background: rgba(10, 132, 255, 0.12);
      border-color: rgba(10, 132, 255, 0.4);
      color: var(--primary);
    }

    .badge-icon.green {
      background: rgba(0, 255, 179, 0.1);
      border-color: rgba(0, 255, 179, 0.35);
      color: var(--accent);
    }

    .badge-icon.locked {
      background: rgba(80, 80, 80, 0.1);
      border-color: rgba(80, 80, 80, 0.3);
      color: #555;
      filter: grayscale(1);
    }

    .badge-name {
      font-size: 11px;
      font-weight: 700;
      color: #fff;
      text-align: center;
    }

    .badge-desc {
      font-size: 10px;
      color: var(--text-muted);
      text-align: center;
      margin-top: 2px;
    }

    .calc-wrap {
      background: var(--bg-card);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      padding: 22px;
      position: relative;
      overflow: hidden;
    }

    .calc-wrap::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 2px;
      background: linear-gradient(90deg, var(--primary), var(--accent), var(--secondary));
    }

    .calc-results-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
      gap: 10px;
      margin-top: 18px;
    }

    .calc-box {
      background: rgba(10, 132, 255, 0.05);
      border: 1px solid rgba(10, 132, 255, 0.12);
      border-radius: 10px;
      padding: 14px 10px;
      text-align: center;
      transition: border-color 0.3s;
    }

    .calc-box:hover {
      border-color: rgba(0, 212, 255, 0.3);
    }

    .calc-box.highlight {
      border-color: rgba(0, 255, 179, 0.3);
      background: rgba(0, 255, 179, 0.04);
    }

    .calc-box-label {
      font-size: 10px;
      color: var(--text-muted);
      text-transform: uppercase;
      letter-spacing: 1px;
      margin-bottom: 7px;
      display: block;
    }

    .calc-box-val {
      font-size: 17px;
      font-weight: 700;
      color: var(--accent);
      font-family: 'Josefin Sans', sans-serif;
    }

    .calc-box.highlight .calc-box-val {
      font-size: 20px;
      text-shadow: 0 0 12px rgba(0, 255, 179, 0.4);
    }

    .setting-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 14px 0;
      border-bottom: 1px solid rgba(10, 132, 255, 0.07);
      gap: 12px;
    }

    .setting-row:last-child {
      border-bottom: none;
    }

    .setting-label {
      font-size: 13px;
      font-weight: 600;
      color: #fff;
    }

    .setting-desc {
      font-size: 11px;
      color: var(--text-muted);
      margin-top: 2px;
    }

    .avatar-ring {
      width: 80px;
      height: 80px;
      position: relative;
      cursor: pointer;
      margin: 0 auto 14px;
    }

    .avatar-img {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      background: linear-gradient(135deg, var(--primary), var(--accent));
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 28px;
      font-weight: 700;
      color: #fff;
      font-family: 'Josefin Sans', sans-serif;
      overflow: hidden;
    }

    .avatar-img img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .avatar-edit {
      position: absolute;
      bottom: 1px;
      right: 1px;
      width: 24px;
      height: 24px;
      background: var(--primary);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 11px;
      color: #fff;
      border: 2px solid var(--bg-main);
    }

    .ticket-card {
      background: rgba(10, 132, 255, 0.04);
      border: 1px solid var(--border);
      border-radius: 10px;
      padding: 14px;
      margin-bottom: 10px;
      transition: var(--transition);
      cursor: pointer;
    }

    .ticket-card:hover {
      border-color: var(--border-hover);
    }

    .ticket-top {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 7px;
    }

    .ticket-id {
      font-size: 11px;
      color: var(--text-muted);
      font-family: monospace;
    }

    .ticket-title {
      font-size: 13px;
      font-weight: 600;
      color: #fff;
    }

    .ticket-date {
      font-size: 11px;
      color: var(--text-muted);
      margin-top: 5px;
    }

    .faq-item {
      border-bottom: 1px solid var(--border);
    }

    .faq-item:last-child {
      border-bottom: none;
    }

    .faq-q {
      padding: 14px 4px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      cursor: pointer;
      font-size: 13px;
      font-weight: 600;
      color: #fff;
    }

    .faq-q i {
      transition: var(--transition);
      color: var(--secondary);
    }

    .faq-item.open .faq-q i {
      transform: rotate(180deg);
    }

    .faq-a {
      max-height: 0;
      overflow: hidden;
      transition: max-height 0.3s ease;
      font-size: 12px;
      color: var(--text-muted);
      line-height: 1.7;
    }

    .faq-item.open .faq-a {
      max-height: 200px;
      padding-bottom: 14px;
    }

    .verify-step {
      display: flex;
      align-items: center;
      gap: 14px;
      padding: 16px 0;
      border-bottom: 1px solid rgba(10, 132, 255, 0.07);
    }

    .verify-step:last-child {
      border-bottom: none;
    }

    .verify-icon {
      width: 44px;
      height: 44px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 19px;
      flex-shrink: 0;
    }

    .verify-icon.done {
      background: rgba(0, 255, 179, 0.12);
      color: var(--accent);
    }

    .verify-icon.pending {
      background: rgba(255, 215, 0, 0.1);
      color: var(--warning);
    }

    .verify-icon.todo {
      background: rgba(255, 77, 109, 0.1);
      color: var(--danger);
    }

    .verify-body {
      flex: 1;
      min-width: 0;
    }

    .verify-title {
      font-size: 13px;
      font-weight: 700;
      color: #fff;
    }

    .verify-sub {
      font-size: 11px;
      color: var(--text-muted);
      margin-top: 2px;
    }

    .announce-card {
      background: rgba(10, 132, 255, 0.05);
      border: 1px solid rgba(10, 132, 255, 0.2);
      border-radius: 10px;
      padding: 14px 16px;
      margin-bottom: 10px;
      display: flex;
      gap: 12px;
      align-items: flex-start;
    }

    .announce-card.warning {
      background: rgba(255, 215, 0, 0.05);
      border-color: rgba(255, 215, 0, 0.2);
    }

    .announce-card.danger {
      background: rgba(255, 77, 109, 0.05);
      border-color: rgba(255, 77, 109, 0.2);
    }

    .announce-icon {
      width: 36px;
      height: 36px;
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 16px;
      flex-shrink: 0;
      background: rgba(10, 132, 255, 0.12);
      color: var(--secondary);
    }

    .announce-card.warning .announce-icon {
      background: rgba(255, 215, 0, 0.12);
      color: var(--warning);
    }

    .announce-card.danger .announce-icon {
      background: rgba(255, 77, 109, 0.12);
      color: var(--danger);
    }

    .announce-title {
      font-size: 13px;
      font-weight: 700;
      color: #fff;
      margin-bottom: 3px;
    }

    .announce-msg {
      font-size: 12px;
      color: var(--text-muted);
      line-height: 1.6;
    }

    .announce-time {
      font-size: 10px;
      color: rgba(232, 234, 246, 0.3);
      margin-top: 6px;
    }

    .fab-btn {
      width: 54px;
      height: 54px;
      background: linear-gradient(135deg, var(--primary), var(--secondary));
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 22px;
      color: #fff;
      cursor: pointer;
      box-shadow: 0 8px 24px rgba(10, 132, 255, 0.5);
      transition: var(--transition);
      border: none;
      position: relative;
    }

    .fab-btn::before {
      content: '';
      position: absolute;
      inset: -4px;
      border-radius: 50%;
      border: 2px solid rgba(10, 132, 255, 0.3);
      animation: fabRing 2s ease-in-out infinite;
    }

    @keyframes fabRing {

      0%,
      100% {
        transform: scale(1);
        opacity: .8
      }

      50% {
        transform: scale(1.1);
        opacity: .3
      }
    }

    .fab-btn:hover {
      transform: scale(1.08);
      box-shadow: 0 12px 30px rgba(10, 132, 255, 0.65);
    }

    .ats-fab {
      position: fixed;
      bottom: 24px;
      right: 24px;
      z-index: var(--z-fab);
    }

    .fab-menu {
      position: absolute;
      bottom: 64px;
      right: 0;
      display: flex;
      flex-direction: column;
      gap: 8px;
      opacity: 0;
      pointer-events: none;
      transform: translateY(10px);
      transition: var(--transition);
    }

    .ats-fab.open .fab-menu {
      opacity: 1;
      pointer-events: all;
      transform: translateY(0);
    }

    .fab-menu-item {
      display: flex;
      align-items: center;
      gap: 9px;
      background: rgba(14, 20, 40, 0.97);
      border: 1px solid var(--border);
      border-radius: 10px;
      padding: 10px 14px;
      white-space: nowrap;
      cursor: pointer;
      color: var(--text);
      font-size: 13px;
      font-weight: 600;
      transition: var(--transition);
      box-shadow: var(--shadow);
      text-decoration: none;
      min-height: 44px;
    }

    .fab-menu-item:hover {
      border-color: var(--border-hover);
      color: var(--secondary);
    }

    .fab-menu-item i {
      color: var(--secondary);
      font-size: 16px;
    }

    .toast-container {
      position: fixed;
      top: 50px;
      right: 18px;
      z-index: var(--z-toast);
      display: flex;
      flex-direction: column;
      gap: 8px;
      max-width: min(340px, calc(100vw - 36px));
      pointer-events: none;
    }

    .toast-item {
      background: rgba(14, 20, 40, 0.97);
      border: 1px solid var(--border);
      border-radius: 12px;
      padding: 13px 16px;
      display: flex;
      align-items: flex-start;
      gap: 11px;
      box-shadow: var(--shadow-blue);
      animation: toastIn 0.35s ease forwards;
      pointer-events: all;
      backdrop-filter: blur(12px);
      max-width: 100%;
    }

    .toast-item.removing {
      animation: toastOut 0.3s ease forwards;
    }

    @keyframes toastIn {
      from {
        opacity: 0;
        transform: translateX(40px)
      }

      to {
        opacity: 1;
        transform: translateX(0)
      }
    }

    @keyframes toastOut {
      from {
        opacity: 1;
        transform: translateX(0)
      }

      to {
        opacity: 0;
        transform: translateX(40px)
      }
    }

    .toast-icon {
      font-size: 19px;
      flex-shrink: 0;
      margin-top: 1px;
    }

    .toast-body .toast-title {
      font-size: 13px;
      font-weight: 700;
      color: #fff;
      margin-bottom: 2px;
    }

    .toast-body .toast-msg {
      font-size: 12px;
      color: var(--text-muted);
      line-height: 1.5;
      word-break: break-word;
    }

    .toast-item.success {
      border-color: rgba(0, 255, 179, 0.3);
    }

    .toast-item.success .toast-icon {
      color: var(--accent);
    }

    .toast-item.error {
      border-color: rgba(255, 77, 109, 0.3);
    }

    .toast-item.error .toast-icon {
      color: var(--danger);
    }

    .toast-item.info {
      border-color: rgba(10, 132, 255, 0.3);
    }

    .toast-item.info .toast-icon {
      color: var(--primary);
    }

    [data-animate] {
      opacity: 0;
      transition: opacity 0.65s ease, transform 0.65s ease;
    }

    [data-animate="fade-up"] {
      transform: translateY(30px);
    }

    [data-animate="fade-in"] {
      transform: none;
    }

    [data-animate].animated {
      opacity: 1;
      transform: translate(0, 0);
    }

    .ats-divider {
      height: 1px;
      background: var(--border);
      margin: 16px 0;
    }

    .text-accent {
      color: var(--accent) !important;
    }

    .text-primary {
      color: var(--primary) !important;
    }

    .text-cyan {
      color: var(--secondary) !important;
    }

    .text-danger {
      color: var(--danger) !important;
    }

    .text-muted-a {
      color: var(--text-muted) !important;
    }

    .text-gold {
      color: var(--warning) !important;
    }

    .fw-7 {
      font-weight: 700;
    }

    .ff-j {
      font-family: 'Josefin Sans', sans-serif;
    }

    .mono {
      font-family: monospace;
    }

    .ats-pagination {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 5px;
      padding: 14px 0 4px;
      flex-wrap: wrap;
    }

    .page-btn {
      width: 34px;
      height: 34px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 7px;
      font-size: 12px;
      font-weight: 600;
      cursor: pointer;
      border: 1px solid var(--border);
      color: var(--text-muted);
      background: transparent;
      transition: var(--transition);
    }

    .page-btn:hover,
    .page-btn.active {
      background: rgba(10, 132, 255, 0.15);
      border-color: rgba(10, 132, 255, 0.3);
      color: var(--secondary);
    }

    .back-top {
      position: fixed;
      bottom: 90px;
      right: 24px;
      width: 40px;
      height: 40px;
      background: var(--bg-card);
      border: 1px solid var(--border);
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--text-muted);
      font-size: 16px;
      cursor: pointer;
      z-index: calc(var(--z-fab) - 1);
      opacity: 0;
      pointer-events: none;
      transition: var(--transition);
    }

    .back-top.show {
      opacity: 1;
      pointer-events: all;
    }

    .back-top:hover {
      border-color: var(--border-hover);
      color: var(--secondary);
    }

    .dash-footer {
      text-align: center;
      padding: 18px;
      font-size: 12px;
      color: var(--text-muted);
      border-top: 1px solid var(--border);
      margin-top: 32px;
    }

    .dash-footer a {
      color: var(--secondary);
    }

    .search-row {
      display: flex;
      gap: 8px;
      flex-wrap: wrap;
      align-items: center;
    }
  </style>
</head>

<body>

  <div class="toast-container" id="toastContainer"></div>
  <div class="notif-overlay" id="notifOverlay"></div>

  <div class="notif-drawer" id="notifDrawer" role="dialog" aria-label="Notifications" aria-modal="true">
    <div class="notif-header">
      <h5><i class="las la-bell" style="color:var(--secondary);"></i>Notifications <span id="notifBadge"
          style="background:var(--danger);color:#fff;font-size:10px;font-weight:700;padding:2px 7px;border-radius:10px;display:none;">3</span>
      </h5>
      <div class="notif-header-actions">
        <button class="notif-mark-all-btn" id="markAllReadBtn" type="button">Mark all read</button>
        <button class="notif-close-btn" id="notifCloseBtn" type="button" aria-label="Close notifications" title="Close">
          <span class="close-unicode" aria-hidden="true">&#x00D7;</span>
          <i class="close-icon las la-times" aria-hidden="true"></i>
        </button>
      </div>
    </div>
    <div class="notif-list" id="notifList" role="list"></div>
    <div class="notif-footer"><button class="btn-ats outline sm full" id="clearNotifsBtn" type="button">&#x1F5D1; Clear
        All</button></div>
  </div>

  <div class="preloader" id="pagePreloader">
    <div class="preloader-container"><span class="animated-preloader"></span></div>
  </div>
  <div class="full-wh" aria-hidden="true">
    <div class="bg-animation">
      <div id="stars"></div>
      <div id="stars2"></div>
      <div id="stars3"></div>
      <div id="stars4"></div>
    </div>
  </div>

  <button class="back-top" id="backTopBtn" type="button" aria-label="Back to top">&#x2191;</button>
  <div class="sidebar-overlay" id="sidebarOverlay" aria-hidden="true"></div>

  <div class="ats-ticker-bar" role="marquee" aria-label="Live market prices">
    <div class="ticker-inner" id="tickerInner"><span class="ticker-item"><span
          class="symbol">Connecting&#x2026;</span></span></div>
  </div>

  <div class="dash-shell">

    <aside class="dash-sidebar" id="dashSidebar" role="complementary" aria-label="Dashboard navigation">
      <button class="sidebar-close-btn" id="sidebarCloseBtn" type="button" aria-label="Close sidebar">&#x00D7;</button>

      <div class="sidebar-ai-block">
        <div class="ai-block-label"><i class="las la-robot" aria-hidden="true"></i> AI Engine</div>
        <div class="ai-block-row">
          <div class="ai-pulse-dot"></div>
          <div>
            <div class="ai-block-status">Running</div>
            <div class="ai-block-sub">94.7% confidence &#xB7; Active</div>
          </div>
        </div>
      </div>

      <div class="sidebar-section-label">Main</div>
      <ul class="sidebar-nav">
        <li><a href="#" class="active" data-section="overview"><i class="las la-th-large"></i><span
              class="nav-label">Overview</span></a></li>
        <li><a href="#" data-section="analytics"><i class="las la-chart-bar"></i><span
              class="nav-label">Analytics</span></a></li>
        <li><a href="#" data-section="investments"><i class="las la-cubes"></i><span class="nav-label">My
              Investments</span></a></li>
        <li><a href="#" data-section="calculator"><i class="las la-calculator"></i><span
              class="nav-label">Calculator</span></a></li>
      </ul>

      <div class="sidebar-section-label">Finance</div>
      <ul class="sidebar-nav">
        <li><a href="#" data-section="deposit"><i class="las la-arrow-down"></i><span
              class="nav-label">Deposit</span></a></li>
        <li><a href="#" data-section="withdraw"><i class="las la-arrow-up"></i><span
              class="nav-label">Withdraw</span></a></li>
        <li><a href="#" data-section="transactions"><i class="las la-history"></i><span
              class="nav-label">Transactions</span></a></li>
      </ul>

      <div class="sidebar-section-label">Growth</div>
      <ul class="sidebar-nav">
        <li><a href="#" data-section="referral"><i class="las la-users"></i><span class="nav-label">Referral</span></a>
        </li>
        <li><a href="#" data-section="rewards"><i class="las la-gift"></i><span class="nav-label">Bonuses &amp;
              Rewards</span></a></li>
        <li><a href="#" data-section="notifications"><i class="las la-bell"></i><span
              class="nav-label">Notifications</span><span class="nav-badge" id="navNotifBadge">3</span></a></li>
        <li><a href="#" data-section="announcements"><i class="las la-bullhorn"></i><span
              class="nav-label">Announcements</span></a></li>
      </ul>

      <div class="sidebar-section-label">Account</div>
      <ul class="sidebar-nav">
        <li><a href="#" data-section="verification"><i class="las la-shield-alt"></i><span
              class="nav-label">Verification</span></a></li>
        <li><a href="#" data-section="activity"><i class="las la-list-ul"></i><span class="nav-label">Activity
              History</span></a></li>
        <li><a href="#" data-section="support"><i class="las la-headset"></i><span class="nav-label">Support</span></a>
        </li>
        <li><a href="#" data-section="settings"><i class="las la-cog"></i><span class="nav-label">Settings</span></a>
        </li>
        <li><a href="index.html"><i class="las la-sign-out-alt"></i><span class="nav-label">Back to Site</span></a></li>
      </ul>
    </aside>

    <main class="dash-main" id="dashMain" role="main">

      <div class="dash-topbar">
        <div class="topbar-left">
          <button class="sidebar-toggle-btn" id="sidebarToggleBtn" type="button" aria-label="Open navigation"
            aria-expanded="false" aria-controls="dashSidebar"><i class="las la-bars" aria-hidden="true"></i></button>
          <div class="dash-greeting">
            <h4 id="greetingText">Good day, <?= htmlspecialchars($username) ?></h4>
            <p id="greetingDate">Loading&#x2026;</p>
          </div>
        </div>
        <div class="topbar-actions">
          <button class="topbar-icon-btn" id="notifToggleBtn" type="button" aria-label="Notifications"><i
              class="las la-bell" aria-hidden="true"></i><span class="notif-dot" id="notifDot"></span></button>
          <button class="topbar-icon-btn" id="settingsShortcutBtn" type="button" aria-label="Settings"><i
              class="las la-cog" aria-hidden="true"></i></button>
          <div class="topbar-avatar" tabindex="0" aria-label="Profile">T</div>
        </div>
      </div>

      <!-- ══ OVERVIEW — 12 stat cards (was 8) ══ -->
      <div id="section-overview" class="dash-section">

        <div id="overviewAnnounceSlot"></div>

        <div class="stat-cards-grid">
          <div class="stat-card sc-blue" data-animate="fade-up">
            <div class="stat-icon"><i class="las la-wallet"></i></div>
            <div class="stat-label">Total Portfolio</div>
            <div class="stat-value" id="sc-portfolio">$0.00</div>
            <div class="stat-trend up"><i class="las la-arrow-up"></i>+12.4% this week</div>
            <div class="stat-mini-chart" id="miniChart1"></div>
          </div>
          <div class="stat-card sc-green" data-animate="fade-up">
            <div class="stat-icon"><i class="las la-chart-line"></i></div>
            <div class="stat-label">Total Profit</div>
            <div class="stat-value" id="sc-profit">$0.00</div>
            <div class="stat-trend up"><i class="las la-arrow-up"></i>+5.4% today</div>
            <div class="stat-mini-chart" id="miniChart2"></div>
          </div>
          <div class="stat-card sc-cyan" data-animate="fade-up">
            <div class="stat-icon"><i class="las la-coins"></i></div>
            <div class="stat-label">Available Balance</div>
            <div class="stat-value" id="sc-balance">$0.00</div>
            <div class="stat-trend up"><i class="las la-rocket"></i>Ready to invest</div>
            <div class="stat-mini-chart" id="miniChart3"></div>
          </div>
          <div class="stat-card sc-gold" data-animate="fade-up">
            <div class="stat-icon"><i class="las la-calendar-day"></i></div>
            <div class="stat-label">Today's Profit</div>
            <div class="stat-value" id="sc-today">$0.00</div>
            <div class="stat-trend up"><i class="las la-bolt"></i>Live earnings</div>
            <div class="stat-mini-chart" id="miniChart4"></div>
          </div>
          <div class="stat-card sc-blue" data-animate="fade-up">
            <div class="stat-icon"><i class="las la-cubes"></i></div>
            <div class="stat-label">Total Invested</div>
            <div class="stat-value" id="sc-invested">$0.00</div>
            <div class="stat-trend up"><i class="las la-robot"></i>3 active bots</div>
          </div>
          <div class="stat-card sc-purple" data-animate="fade-up">
            <div class="stat-icon"><i class="las la-users"></i></div>
            <div class="stat-label">Referral Earnings</div>
            <div class="stat-value" id="sc-referral">$0.00</div>
            <div class="stat-trend up"><i class="las la-arrow-up"></i>+$25 new</div>
          </div>
          <div class="stat-card sc-red" data-animate="fade-up">
            <div class="stat-icon"><i class="las la-clock"></i></div>
            <div class="stat-label">Pending Withdrawals</div>
            <div class="stat-value" id="sc-pending">$0.00</div>
            <div class="stat-trend flat" style="color:var(--warning);"><i class="las la-hourglass-half"></i>Processing
            </div>
          </div>
          <div class="stat-card sc-green" data-animate="fade-up">
            <div class="stat-icon"><i class="las la-rocket"></i></div>
            <div class="stat-label">Active Investments</div>
            <div class="stat-value" id="sc-active">0</div>
            <div class="stat-trend up"><i class="las la-robot"></i>Bots running</div>
          </div>
          <div class="stat-card sc-gold" data-animate="fade-up">
            <div class="stat-icon"><i class="las la-arrow-down"></i></div>
            <div class="stat-label">Pending Deposits</div>
            <div class="stat-value" id="sc-pdeposit">$0.00</div>
            <div class="stat-trend flat" style="color:var(--warning);"><i class="las la-hourglass-half"></i>Awaiting
              review</div>
          </div>
          <div class="stat-card sc-purple" data-animate="fade-up">
            <div class="stat-icon"><i class="las la-gift"></i></div>
            <div class="stat-label">Total Bonuses</div>
            <div class="stat-value" id="sc-bonuses">$0.00</div>
            <div class="stat-trend up"><i class="las la-arrow-up"></i>All-time rewards</div>
          </div>
          <div class="stat-card sc-green" data-animate="fade-up">
            <div class="stat-icon"><i class="las la-robot"></i></div>
            <div class="stat-label">AI Trading Status</div>
            <div class="stat-value" style="font-size:16px;">RUNNING</div>
            <div class="stat-trend up"><i class="las la-check-circle"></i>All bots active</div>
          </div>
          <div class="stat-card sc-cyan" data-animate="fade-up">
            <div class="stat-icon"><i class="las la-brain"></i></div>
            <div class="stat-label">AI Confidence Score</div>
            <div class="stat-value" id="sc-confidence">0%</div>
            <div class="stat-trend up"><i class="las la-arrow-up"></i>Model v4.2</div>
          </div>
          <div class="stat-card sc-teal" data-animate="fade-up">
            <div class="stat-icon"><i class="las la-shield-alt"></i></div>
            <div class="stat-label">Verification Status</div>
            <div class="stat-value" id="sc-verify" style="font-size:16px;">Verified</div>
            <div class="stat-trend up"><i class="las la-check-circle"></i>Full access</div>
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-lg-4 mb-3" data-animate="fade-up">
            <div class="ats-card h-100">
              <div class="card-head">
                <h5 class="card-head-title"><i class="las la-robot"></i>AI Confidence</h5>
              </div>
              <div class="gauge-wrap">
                <div class="gauge-ring"><svg width="130" height="130" viewBox="0 0 130 130" aria-hidden="true">
                    <defs>
                      <linearGradient id="gaugeGrad" x1="0%" y1="0%" x2="100%" y2="0%">
                        <stop offset="0%" stop-color="#0A84FF" />
                        <stop offset="100%" stop-color="#00FFB3" />
                      </linearGradient>
                    </defs>
                    <circle class="gauge-track" cx="65" cy="65" r="55" />
                    <circle class="gauge-fill" id="gaugeFill" cx="65" cy="65" r="55" />
                  </svg>
                  <div class="gauge-center">
                    <div class="gauge-val" id="gaugeVal">0%</div>
                    <div class="gauge-sub">Confidence</div>
                  </div>
                </div>
                <div class="gauge-desc">Neural model v4.2 &#xB7; Updated 3hrs ago</div>
                <div class="gauge-legend"><span class="gl-good">Good (80%+)</span><span class="gl-mid">Mid
                    (50%+)</span><span class="gl-low">Low</span></div>
              </div>
            </div>
          </div>
          <div class="col-lg-8 mb-3" data-animate="fade-up" style="transition-delay:0.1s;">
            <div class="ats-card h-100">
              <div class="card-head">
                <h5 class="card-head-title"><i class="las la-bolt"></i>Quick Actions</h5>
              </div>
              <div class="card-body">
                <div class="quick-actions-grid">
                  <button class="qa-item deposit" type="button" data-nav="deposit">
                    <div class="qa-icon"><i class="las la-arrow-down"></i></div>Deposit
                  </button>
                  <button class="qa-item withdraw" type="button" data-nav="withdraw">
                    <div class="qa-icon"><i class="las la-arrow-up"></i></div>Withdraw
                  </button>
                  <button class="qa-item invest" type="button" data-nav="investments">
                    <div class="qa-icon"><i class="las la-chart-line"></i></div>Invest
                  </button>
                  <button class="qa-item refer" type="button" data-nav="referral">
                    <div class="qa-icon"><i class="las la-users"></i></div>Refer &amp; Earn
                  </button>
                </div>
                <div class="ats-divider"></div>
                <div class="section-head">
                  <h5><i class="las la-history"></i>Recent Activity</h5><button type="button" data-nav="activity">View
                    all</button>
                </div>
                <div class="table-responsive-wrap">
                  <table class="ats-table" id="recentActivityTable">
                    <thead>
                      <tr>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Date</th>
                        <th>Status</th>
                      </tr>
                    </thead>
                    <tbody></tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div data-animate="fade-up">
          <div class="section-head">
            <h5><i class="las la-cubes"></i>Active Plans</h5><button type="button" data-nav="investments">View
              all</button>
          </div>
          <div class="row">
            <div class="col-lg-4 mb-3">
              <div class="plan-card starter">
                <div class="plan-badge starter"><i class="las la-robot"></i>Starter</div>
                <div class="plan-name">Trading Mastery</div>
                <div class="plan-rate">26.04%</div>
                <div class="plan-rate-sub">per hour &#xB7; 2 Days</div>
                <div class="plan-detail"><span class="plan-detail-label">Invested</span><span
                    class="plan-detail-val">$500</span></div>
                <div class="plan-detail"><span class="plan-detail-label">Profit So Far</span><span
                    class="plan-detail-val text-accent">$156.24</span></div>
                <div class="plan-detail"><span class="plan-detail-label">Expected</span><span
                    class="plan-detail-val">$6,250</span></div>
                <div class="plan-progress-wrap">
                  <div class="plan-progress-labels"><span>Progress</span><span>12.5%</span></div>
                  <div class="progress-bar-track">
                    <div class="progress-bar-fill" style="width:0;" data-width="12.5"></div>
                  </div>
                </div><span class="status-badge active"><i class="las la-circle"
                    style="font-size:7px;"></i>Running</span>
              </div>
            </div>
            <div class="col-lg-4 mb-3">
              <div class="plan-card elite">
                <div class="plan-badge elite"><i class="las la-crown"></i>Elite</div>
                <div class="plan-name">Elite Trader</div>
                <div class="plan-rate" style="color:var(--warning);">17.36%</div>
                <div class="plan-rate-sub">per hour &#xB7; 3 Days</div>
                <div class="plan-detail"><span class="plan-detail-label">Invested</span><span
                    class="plan-detail-val">$600</span></div>
                <div class="plan-detail"><span class="plan-detail-label">Profit So Far</span><span
                    class="plan-detail-val text-accent">$312.48</span></div>
                <div class="plan-detail"><span class="plan-detail-label">Expected</span><span
                    class="plan-detail-val">$7,500</span></div>
                <div class="plan-progress-wrap">
                  <div class="plan-progress-labels"><span>Progress</span><span>27.4%</span></div>
                  <div class="progress-bar-track">
                    <div class="progress-bar-fill"
                      style="width:0;background:linear-gradient(90deg,var(--warning),#ff8c00);" data-width="27.4"></div>
                  </div>
                </div><span class="status-badge active"><i class="las la-circle"
                    style="font-size:7px;"></i>Running</span>
              </div>
            </div>
            <div class="col-lg-4 mb-3">
              <div class="plan-card vip">
                <div class="plan-badge vip"><i class="las la-gem"></i>VIP</div>
                <div class="plan-name">VIP Access</div>
                <div class="plan-rate" style="color:var(--danger);">17.36%</div>
                <div class="plan-rate-sub">per hour &#xB7; 3 Days</div>
                <div class="plan-detail"><span class="plan-detail-label">Invested</span><span
                    class="plan-detail-val">$900</span></div>
                <div class="plan-detail"><span class="plan-detail-label">Profit So Far</span><span
                    class="plan-detail-val text-accent">$468.72</span></div>
                <div class="plan-detail"><span class="plan-detail-label">Expected</span><span
                    class="plan-detail-val">$11,250</span></div>
                <div class="plan-progress-wrap">
                  <div class="plan-progress-labels"><span>Progress</span><span>38.2%</span></div>
                  <div class="progress-bar-track">
                    <div class="progress-bar-fill"
                      style="width:0;background:linear-gradient(90deg,var(--danger),#9333ea);" data-width="38.2"></div>
                  </div>
                </div><span class="status-badge active"><i class="las la-circle"
                    style="font-size:7px;"></i>Running</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- ══ ANALYTICS — +Monthly Earnings chart ══ -->
      <div id="section-analytics" class="dash-section" style="display:none;">
        <div class="section-head mb-3">
          <h5><i class="las la-chart-bar"></i>Portfolio Analytics</h5>
        </div>
        <div class="row mb-3">
          <div class="col-lg-8 mb-3" data-animate="fade-up">
            <div class="ats-card">
              <div class="card-head">
                <h5 class="card-head-title"><i class="las la-chart-area"></i>Portfolio Growth</h5>
                <div class="ats-tabs"><button class="ats-tab-btn active" type="button">7D</button><button
                    class="ats-tab-btn" type="button">30D</button><button class="ats-tab-btn" type="button">90D</button>
                </div>
              </div>
              <div class="card-body">
                <div id="portfolioChart" style="min-height:280px;"></div>
              </div>
            </div>
          </div>
          <div class="col-lg-4 mb-3" data-animate="fade-up" style="transition-delay:0.1s;">
            <div class="ats-card">
              <div class="card-head">
                <h5 class="card-head-title"><i class="las la-chart-pie"></i>Asset Allocation</h5>
              </div>
              <div class="card-body">
                <div id="allocationChart" style="min-height:280px;"></div>
              </div>
            </div>
          </div>
        </div>
        <div class="row mb-3">
          <div class="col-lg-6 mb-3" data-animate="fade-up">
            <div class="ats-card">
              <div class="card-head">
                <h5 class="card-head-title"><i class="las la-calendar-alt"></i>Daily ROI</h5>
              </div>
              <div class="card-body">
                <div id="roiChart" style="min-height:220px;"></div>
              </div>
            </div>
          </div>
          <div class="col-lg-6 mb-3" data-animate="fade-up" style="transition-delay:0.1s;">
            <div class="ats-card">
              <div class="card-head">
                <h5 class="card-head-title"><i class="las la-exchange-alt"></i>Win / Loss Ratio</h5>
              </div>
              <div class="card-body">
                <div id="winLossChart" style="min-height:220px;"></div>
              </div>
            </div>
          </div>
        </div>
        <div class="row mb-3">
          <div class="col-lg-6 mb-3" data-animate="fade-up">
            <div class="ats-card">
              <div class="card-head">
                <h5 class="card-head-title"><i class="las la-chart-line"></i>Monthly Earnings</h5>
              </div>
              <div class="card-body">
                <div id="monthlyChart" style="min-height:220px;"></div>
              </div>
            </div>
          </div>
          <div class="col-lg-6 mb-3" data-animate="fade-up" style="transition-delay:0.1s;">
            <div class="ats-card">
              <div class="card-head">
                <h5 class="card-head-title"><i class="las la-history"></i>Profit History</h5>
              </div>
              <div class="card-body">
                <div id="profitHistChart" style="min-height:220px;"></div>
              </div>
            </div>
          </div>
        </div>
        <div data-animate="fade-up">
          <div class="ats-card">
            <div class="card-head">
              <h5 class="card-head-title"><i class="las la-robot"></i>AI Trading Performance</h5>
            </div>
            <div class="card-body">
              <div id="aiPerfChart" style="min-height:220px;"></div>
            </div>
          </div>
        </div>
      </div>

      <!-- ══ MY INVESTMENTS — +search/filter/export ══ -->
      <div id="section-investments" class="dash-section" style="display:none;">
        <div class="section-head mb-3">
          <h5><i class="las la-cubes"></i>My Investments</h5>
          <div style="display:flex;gap:6px;"><button class="btn-ats outline sm" id="expInvCsv" type="button"><i
                class="las la-file-csv"></i></button><button class="btn-ats outline sm" id="expInvPdf" type="button"><i
                class="las la-file-pdf"></i></button></div>
        </div>
        <div class="ats-card" data-animate="fade-up">
          <div class="card-head" style="flex-wrap:wrap;gap:10px;">
            <div class="ats-tabs"><button class="ats-tab-btn active" type="button"
                data-invest-tab="active">Active</button><button class="ats-tab-btn" type="button"
                data-invest-tab="expired">Expired</button><button class="ats-tab-btn" type="button"
                data-invest-tab="plans">Available Plans</button></div>
            <div class="search-row">
              <div style="position:relative;max-width:180px;"><i class="las la-search"
                  style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--text-muted);font-size:14px;"></i><input
                  type="search" class="ats-input" id="invSearchInput"
                  style="padding-left:34px;font-size:12px;min-height:36px;" placeholder="Search ID or plan…"></div>
              <select class="ats-select" id="invSortSel" style="font-size:12px;min-height:36px;width:auto;">
                <option value="date">Sort: Newest</option>
                <option value="amount">Sort: Amount</option>
                <option value="roi">Sort: ROI</option>
              </select>
            </div>
          </div>
          <div id="invest-tab-active" class="ats-tab-pane active">
            <div class="table-responsive-wrap">
              <table class="ats-table" id="activeInvTable">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Plan</th>
                    <th>Amount</th>
                    <th>Profit</th>
                    <th>ROI</th>
                    <th>Progress</th>
                    <th>Started</th>
                    <th>Maturity</th>
                    <th>AI Conf.</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody></tbody>
              </table>
            </div>
            <div class="ats-pagination"><button class="page-btn active" type="button">1</button><button class="page-btn"
                type="button">2</button><button class="page-btn" type="button">&#x276F;</button></div>
          </div>
          <div id="invest-tab-expired" class="ats-tab-pane" style="display:none;">
            <div class="table-responsive-wrap">
              <table class="ats-table">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Plan</th>
                    <th>Amount</th>
                    <th>Total Profit</th>
                    <th>ROI</th>
                    <th>Completed</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td class="text-muted-a mono">#INV-000</td>
                    <td class="fw-7">Beginner Access</td>
                    <td class="fw-7">$200</td>
                    <td class="text-accent fw-7">$2,500</td>
                    <td class="text-accent">1250%</td>
                    <td class="text-muted-a">Dec 12</td>
                    <td><span class="status-badge expired">Expired</span></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div id="invest-tab-plans" class="ats-tab-pane" style="display:none;">
            <div class="card-body">
              <div class="row" id="availablePlansGrid"></div>
            </div>
          </div>
        </div>
      </div>

      <!-- ══ CALCULATOR (unchanged, already synced with admin plan config via PLAN_NAMES) ══ -->
      <div id="section-calculator" class="dash-section" style="display:none;">
        <div class="section-head mb-3">
          <h5><i class="las la-calculator"></i>AI Return Calculator</h5>
        </div>
        <div class="row">
          <div class="col-lg-5 mb-4" data-animate="fade-up">
            <div class="calc-wrap">
              <h5 class="ff-j fw-7 mb-4" style="color:#fff;font-size:15px;"><i class="las la-robot"
                  style="color:var(--secondary);margin-right:7px;"></i>Configure Strategy</h5>
              <div class="form-group"><label class="form-label" for="calcPlanSel">Choose AI Strategy</label><select
                  class="ats-select" id="calcPlanSel">
                  <optgroup label="2 Days (48hrs) &#xB7; 26.04%/hr">
                    <option value="200:26.04:48:2">Beginner Access &#x2014; Min $200</option>
                    <option value="300:26.04:48:2">Premium Access &#x2014; Min $300</option>
                    <option value="400:26.04:48:2">Massive Growth &#x2014; Min $400</option>
                    <option value="500:26.04:48:2">Trading Mastery &#x2014; Min $500</option>
                  </optgroup>
                  <optgroup label="3 Days (72hrs) &#xB7; 17.36%/hr">
                    <option value="600:17.36:72:3">Elite Trader &#x2014; Min $600</option>
                    <option value="700:17.36:72:3">Professional Level &#x2014; Min $700</option>
                    <option value="900:17.36:72:3">VIP Access &#x2014; Min $900</option>
                    <option value="10000:17.36:72:3">Hack Bot &#x2014; Min $10,000</option>
                  </optgroup>
                </select></div>
              <div class="form-group"><label class="form-label" for="calcAmtInput">Investment Amount (USD)</label>
                <div class="input-wrap"><i class="las la-dollar-sign input-icon"></i><input type="number"
                    class="ats-input" id="calcAmtInput" placeholder="e.g. 500" min="1" step="any"></div>
                <div class="input-error" id="calcErrMsg"></div>
              </div>
              <button class="btn-ats primary full" id="calcRunBtn" type="button"><i class="las la-calculator"></i>
                Calculate My Returns</button>
              <div
                style="margin-top:16px;padding:13px;background:rgba(0,255,179,0.05);border:1px solid rgba(0,255,179,0.15);border-radius:9px;">
                <div
                  style="font-size:10px;color:var(--accent);font-weight:700;text-transform:uppercase;letter-spacing:1px;margin-bottom:7px;">
                  Formula</div>
                <div style="font-size:12px;color:var(--text-muted);line-height:1.9;">Hourly Profit = Investment &#xD7;
                  Rate<br>Total Profit = Hourly &#xD7; Hours<br>Total Return = Investment + Profit</div>
              </div>
            </div>
          </div>
          <div class="col-lg-7 mb-4" data-animate="fade-up" style="transition-delay:0.1s;">
            <div class="calc-wrap" id="calcResultsWrap"
              style="opacity:0.4;pointer-events:none;transition:opacity 0.4s;">
              <div style="text-align:center;margin-bottom:18px;">
                <div id="calcPlanLabel"
                  style="display:inline-flex;align-items:center;gap:6px;padding:5px 14px;background:rgba(10,132,255,0.1);border:1px solid rgba(10,132,255,0.25);border-radius:20px;font-size:12px;color:var(--secondary);font-weight:600;">
                  <i class="las la-robot"></i> Select a plan to see projection
                </div>
              </div>
              <div class="calc-results-grid">
                <div class="calc-box"><span class="calc-box-label">Hourly Return</span><span class="calc-box-val"
                    id="cr-hourly">$0.00</span></div>
                <div class="calc-box"><span class="calc-box-label">Daily Return</span><span class="calc-box-val"
                    id="cr-daily">$0.00</span></div>
                <div class="calc-box"><span class="calc-box-label">Weekly Return</span><span class="calc-box-val"
                    id="cr-weekly">$0.00</span></div>
                <div class="calc-box"><span class="calc-box-label">Monthly Return</span><span class="calc-box-val"
                    id="cr-monthly">$0.00</span></div>
                <div class="calc-box"><span class="calc-box-label">Duration</span><span class="calc-box-val"
                    id="cr-days">&#x2014;</span></div>
                <div class="calc-box"><span class="calc-box-label">ROI</span><span class="calc-box-val"
                    id="cr-roi">0%</span></div>
                <div class="calc-box highlight"><span class="calc-box-label">Net Profit</span><span class="calc-box-val"
                    id="cr-profit">$0.00</span></div>
                <div class="calc-box highlight"><span class="calc-box-label">Capital + Profit</span><span
                    class="calc-box-val" id="cr-total">$0.00</span></div>
              </div>
              <div
                style="margin-top:14px;padding:12px;background:rgba(10,132,255,0.04);border-radius:9px;border:1px solid rgba(10,132,255,0.1);">
                <div style="display:flex;justify-content:space-between;font-size:13px;"><span class="text-muted-a">Est.
                    Completion</span><span style="color:#fff;font-weight:600;" id="cr-completion">&#x2014;</span></div>
              </div>
              <p style="text-align:center;font-size:11px;color:rgba(232,234,246,0.25);margin-top:12px;">Projections use
                the plan rates configured by the platform. Past performance &#x2260; future results.</p>
            </div>
          </div>
        </div>
      </div>

      <!-- ══ DEPOSIT — dynamic payment methods driven by PAYMENT_METHODS ══ -->
      <div id="section-deposit" class="dash-section" style="display:none;">
        <div class="section-head mb-3">
          <h5><i class="las la-arrow-down"></i>Deposit Funds</h5>
        </div>
        <div class="row">
          <div class="col-lg-6 mb-4" data-animate="fade-up">
            <div class="ats-card">
              <div class="card-head">
                <h5 class="card-head-title"><i class="las la-wallet"></i>Make a Deposit</h5>
              </div>
              <div class="card-body">
                <div class="dep-step">
                  <div class="dep-num">1</div>
                  <div class="dep-body">
                    <h6>Enter Amount</h6>
                    <div class="input-wrap"><i class="las la-dollar-sign input-icon"></i><input type="number"
                        class="ats-input" id="depAmtInput" placeholder="Min $10" min="10"></div>
                  </div>
                </div>
                <div class="dep-step">
                  <div class="dep-num">2</div>
                  <div class="dep-body">
                    <h6>Select Payment Method</h6>
                    <div class="pm-select-grid" id="pmSelectGrid"></div>
                  </div>
                </div>
                <div class="dep-step">
                  <div class="dep-num">3</div>
                  <div class="dep-body">
                    <h6>Send to Wallet Address</h6>
                    <div class="wallet-addr-box mb-2">
                      <div class="wallet-addr-text" id="depAddrText">Select a method above</div><button
                        class="copy-addr-btn" id="copyAddrBtn" type="button">Copy</button>
                    </div>
                    <div class="qr-code-wrap" id="depQRWrap"></div>
                    <div class="pm-meta-grid" id="pmMetaGrid"></div>
                    <p style="font-size:11px;color:var(--text-muted);text-align:center;margin-top:8px;"
                      id="depInstructionsText">Select a payment method to see deposit instructions.</p>
                  </div>
                </div>
                <div class="dep-step">
                  <div class="dep-num">4</div>
                  <div class="dep-body">
                    <h6>Upload Payment Proof</h6>
                    <div class="upload-zone" id="uploadZone"><i class="las la-cloud-upload-alt"></i>
                      <p>Click to upload screenshot</p>
                    </div><input type="file" id="depFileInput" accept="image/*" style="display:none;">
                    <div class="upload-preview-wrap" id="depPreview"><img id="depPreviewImg" src="" alt="Preview"></div>
                  </div>
                </div>
                <button class="btn-ats primary full" id="submitDepBtn" type="button"><i class="las la-paper-plane"></i>
                  Submit Deposit</button>
              </div>
            </div>
          </div>
          <div class="col-lg-6 mb-4" data-animate="fade-up" style="transition-delay:0.1s;">
            <div class="ats-card">
              <div class="card-head">
                <h5 class="card-head-title"><i class="las la-history"></i>Deposit History</h5>
              </div>
              <div class="table-responsive-wrap">
                <table class="ats-table" id="depHistoryTable">
                  <thead>
                    <tr>
                      <th>Date</th>
                      <th>Ref#</th>
                      <th>Amount</th>
                      <th>Method</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody></tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- ══ WITHDRAW — status set expanded + rejection reasons ══ -->
      <div id="section-withdraw" class="dash-section" style="display:none;">
        <div class="section-head mb-3">
          <h5><i class="las la-arrow-up"></i>Withdraw Funds</h5>
        </div>
        <div class="row">
          <div class="col-lg-5 mb-4" data-animate="fade-up">
            <div class="ats-card mb-3">
              <div class="card-body">
                <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px;">
                  <div>
                    <div
                      style="font-size:11px;color:var(--text-muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:4px;">
                      Available Balance</div>
                    <div
                      style="font-size:28px;font-weight:700;color:var(--accent);font-family:'Josefin Sans',sans-serif;">
                      $1,105.00</div>
                    <div style="font-size:11px;color:var(--text-muted);margin-top:3px;">Interest Wallet</div>
                  </div><i class="las la-wallet" style="font-size:36px;color:rgba(0,255,179,0.15);"></i>
                </div>
              </div>
            </div>
            <div class="ats-card">
              <div class="card-head">
                <h5 class="card-head-title"><i class="las la-arrow-up"></i>New Withdrawal</h5>
              </div>
              <div class="card-body">
                <div class="form-group"><label class="form-label">Cryptocurrency</label><select class="ats-select"
                    id="wdCryptoSel">
                    <option>Bitcoin (BTC)</option>
                    <option>Ethereum (ETH)</option>
                    <option>USDT &#x2014; TRC20</option>
                    <option>Solana (SOL)</option>
                  </select></div>
                <div class="form-group"><label class="form-label">Network</label><input type="text" class="ats-input"
                    id="wdNetworkText" readonly value="Bitcoin Mainnet"></div>
                <div class="form-group"><label class="form-label">Wallet Address</label><input type="text"
                    class="ats-input" placeholder="Enter wallet address"></div>
                <div class="form-group"><label class="form-label">Amount (USD)</label>
                  <div class="input-wrap"><i class="las la-dollar-sign input-icon"></i><input type="number"
                      class="ats-input" placeholder="Min $10" min="10"></div>
                </div>
                <div
                  style="background:rgba(255,215,0,0.06);border:1px solid rgba(255,215,0,0.2);border-radius:9px;padding:11px;margin-bottom:16px;font-size:12px;color:var(--warning);">
                  &#x26A0;&#xFE0F; Minimum $10. Processing in under 60 seconds.</div>
                <button class="btn-ats primary full" id="submitWdBtn" type="button"><i class="las la-paper-plane"></i>
                  Submit Withdrawal</button>
              </div>
            </div>
          </div>
          <div class="col-lg-7 mb-4" data-animate="fade-up" style="transition-delay:0.1s;">
            <div class="ats-card">
              <div class="card-head">
                <h5 class="card-head-title"><i class="las la-history"></i>Withdrawal History</h5>
              </div>
              <div id="wdHistoryList" class="card-body" style="padding-top:0;"></div>
            </div>
          </div>
        </div>
      </div>

      <!-- ══ TRANSACTIONS — full category set + search/filter/export ══ -->
      <div id="section-transactions" class="dash-section" style="display:none;">
        <div class="section-head mb-3">
          <h5><i class="las la-history"></i>Transaction History</h5>
          <div style="display:flex;gap:6px;"><button class="btn-ats outline sm" id="expTxCsv" type="button"><i
                class="las la-file-csv"></i></button><button class="btn-ats outline sm" id="expTxPdf" type="button"><i
                class="las la-file-pdf"></i></button></div>
        </div>
        <div class="ats-card" data-animate="fade-up">
          <div class="card-head" style="flex-wrap:wrap;gap:10px;">
            <div class="ats-tabs" id="txTabs">
              <button class="ats-tab-btn active" type="button" data-tx-filter="all">All</button>
              <button class="ats-tab-btn" type="button" data-tx-filter="deposit">Deposits</button>
              <button class="ats-tab-btn" type="button" data-tx-filter="withdrawal">Withdrawals</button>
              <button class="ats-tab-btn" type="button" data-tx-filter="profit">Profits</button>
              <button class="ats-tab-btn" type="button" data-tx-filter="bonus">Bonuses</button>
              <button class="ats-tab-btn" type="button" data-tx-filter="manual">Manual</button>
            </div>
            <div style="position:relative;max-width:200px;"><i class="las la-search"
                style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--text-muted);font-size:14px;"></i><input
                type="search" class="ats-input" id="txSearchInput"
                style="padding-left:34px;font-size:12px;min-height:36px;" placeholder="Search transactions…"></div>
          </div>
          <div class="table-responsive-wrap">
            <table class="ats-table" id="txTable">
              <thead>
                <tr>
                  <th>Date</th>
                  <th>ID</th>
                  <th>Type</th>
                  <th>Amount</th>
                  <th>Wallet</th>
                  <th>Details</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
          <div class="ats-pagination"><button class="page-btn active" type="button">1</button><button class="page-btn"
              type="button">2</button><button class="page-btn" type="button">&#x276F;</button></div>
        </div>
      </div>

      <!-- ══ REFERRAL — +QR, levels, leaderboard ══ -->
      <div id="section-referral" class="dash-section" style="display:none;">
        <div class="section-head mb-3">
          <h5><i class="las la-users"></i>Referral Program</h5>
        </div>
        <div class="row">
          <div class="col-lg-7 mb-4" data-animate="fade-up">
            <div class="ats-card">
              <div class="card-head">
                <h5 class="card-head-title"><i class="las la-link"></i>Your Referral Link</h5>
              </div>
              <div class="card-body">
                <p style="font-size:13px;color:var(--text-muted);margin-bottom:14px;">Earn 5% commission on every
                  investment made through your link.</p>
                <div class="ref-link-box">
                  <div class="ref-link-text" id="refLinkText">https://ats-trading.io/register?ref=TRADER123</div><button
                    class="copy-addr-btn" id="copyRefBtn" type="button">Copy</button>
                </div>
                <div class="qr-code-wrap" id="refQRWrap"></div>
                <div style="display:flex;gap:8px;flex-wrap:wrap;margin-top:12px;">
                  <button class="btn-ats outline sm" type="button" id="shareTelegramBtn"><i
                      class="lab la-telegram-plane"></i> Telegram</button>
                  <button class="btn-ats outline sm" type="button" id="shareTwitterBtn"><i class="lab la-twitter"></i>
                    Twitter</button>
                  <button class="btn-ats outline sm" type="button" id="shareWhatsappBtn"><i class="lab la-whatsapp"></i>
                    WhatsApp</button>
                </div>
              </div>
            </div>
            <div class="ats-card mt-3">
              <div class="card-head">
                <h5 class="card-head-title"><i class="las la-layer-group"></i>Referral Levels</h5>
              </div>
              <div class="table-responsive-wrap">
                <table class="ats-table">
                  <thead>
                    <tr>
                      <th>Level</th>
                      <th>Commission</th>
                      <th>Your Referrals</th>
                      <th>Earned</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td class="fw-7">Level 1</td>
                      <td class="text-accent">5%</td>
                      <td>9</td>
                      <td class="text-accent fw-7">$540</td>
                    </tr>
                    <tr>
                      <td class="fw-7">Level 2</td>
                      <td class="text-accent">2%</td>
                      <td>2</td>
                      <td class="text-accent fw-7">$140</td>
                    </tr>
                    <tr>
                      <td class="fw-7">Level 3</td>
                      <td class="text-accent">1%</td>
                      <td>1</td>
                      <td class="text-accent fw-7">$40</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="col-lg-5 mb-4" data-animate="fade-up" style="transition-delay:0.1s;">
            <div class="ref-stats-grid mb-3">
              <div class="ref-stat-box">
                <div class="ref-stat-val" id="ref-count">0</div>
                <div class="ref-stat-lbl">Total Referrals</div>
              </div>
              <div class="ref-stat-box">
                <div class="ref-stat-val" id="ref-active">0</div>
                <div class="ref-stat-lbl">Active Referrals</div>
              </div>
              <div class="ref-stat-box">
                <div class="ref-stat-val" id="ref-earn">$0</div>
                <div class="ref-stat-lbl">Total Earned</div>
              </div>
              <div class="ref-stat-box">
                <div class="ref-stat-val" id="ref-pending">$0</div>
                <div class="ref-stat-lbl">Pending</div>
              </div>
            </div>
            <div class="ats-card">
              <div class="card-head">
                <h5 class="card-head-title"><i class="las la-trophy"></i>Leaderboard</h5>
              </div>
              <div class="card-body">
                <div
                  style="display:flex;align-items:center;gap:10px;padding:8px 0;border-bottom:1px solid rgba(10,132,255,0.07);">
                  <span style="font-size:16px;">&#x1F947;</span>
                  <div style="flex:1;font-size:13px;font-weight:700;color:#fff;">James H.</div>
                  <div class="text-accent fw-7">$8,420</div>
                </div>
                <div
                  style="display:flex;align-items:center;gap:10px;padding:8px 0;border-bottom:1px solid rgba(10,132,255,0.07);">
                  <span style="font-size:16px;">&#x1F948;</span>
                  <div style="flex:1;font-size:13px;font-weight:700;color:#fff;">Sarah M.</div>
                  <div class="text-accent fw-7">$5,880</div>
                </div>
                <div
                  style="display:flex;align-items:center;gap:10px;padding:8px 0;border-bottom:1px solid rgba(10,132,255,0.07);background:rgba(10,132,255,0.06);border-radius:6px;padding-left:6px;">
                  <span style="font-size:16px;">&#x1F949;</span>
                  <div style="flex:1;font-size:13px;font-weight:700;color:var(--secondary);">You</div>
                  <div class="text-accent fw-7">$720</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- ══ REWARDS — includes Bonus & Reward History table ══ -->
      <div id="section-rewards" class="dash-section" style="display:none;">
        <div class="section-head mb-3">
          <h5><i class="las la-gift"></i>Bonuses &amp; Rewards</h5>
        </div>
        <div class="row mb-4">
          <div class="col-lg-8 mb-4" data-animate="fade-up">
            <div class="ats-card">
              <div class="card-head">
                <h5 class="card-head-title"><i class="las la-medal"></i>Achievement Badges</h5>
              </div>
              <div class="card-body">
                <div class="row text-center">
                  <div class="col-4 col-sm-2 mb-3">
                    <div class="badge-icon gold"><i class="las la-rocket"></i></div>
                    <div class="badge-name">First Trade</div>
                    <div class="badge-desc">Earned</div>
                  </div>
                  <div class="col-4 col-sm-2 mb-3">
                    <div class="badge-icon blue"><i class="las la-star"></i></div>
                    <div class="badge-name">VIP Trader</div>
                    <div class="badge-desc">Earned</div>
                  </div>
                  <div class="col-4 col-sm-2 mb-3">
                    <div class="badge-icon green"><i class="las la-users"></i></div>
                    <div class="badge-name">Referral Pro</div>
                    <div class="badge-desc">10+ refs</div>
                  </div>
                  <div class="col-4 col-sm-2 mb-3">
                    <div class="badge-icon silver"><i class="las la-trophy"></i></div>
                    <div class="badge-name">Top Earner</div>
                    <div class="badge-desc">$1K+ profit</div>
                  </div>
                  <div class="col-4 col-sm-2 mb-3">
                    <div class="badge-icon bronze"><i class="las la-fire"></i></div>
                    <div class="badge-name">Hot Streak</div>
                    <div class="badge-desc">7-day</div>
                  </div>
                  <div class="col-4 col-sm-2 mb-3">
                    <div class="badge-icon locked"><i class="las la-crown"></i></div>
                    <div class="badge-name">Elite Master</div>
                    <div class="badge-desc">Locked</div>
                  </div>
                </div>
              </div>
            </div>
            <div class="ats-card mt-3">
              <div class="card-head">
                <h5 class="card-head-title"><i class="las la-history"></i>Bonus &amp; Reward History</h5>
              </div>
              <div class="table-responsive-wrap">
                <table class="ats-table" id="bonusHistoryTable">
                  <thead>
                    <tr>
                      <th>Title</th>
                      <th>Category</th>
                      <th>Amount</th>
                      <th>Ref#</th>
                      <th>Date</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody></tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="col-lg-4 mb-4" data-animate="fade-up" style="transition-delay:0.1s;">
            <div class="ats-card mb-3">
              <div class="card-head">
                <h5 class="card-head-title"><i class="las la-fire"></i>Daily Reward</h5>
              </div>
              <div class="card-body text-center">
                <div style="font-size:42px;margin-bottom:10px;">&#x1F381;</div>
                <div
                  style="font-size:26px;font-weight:700;color:var(--accent);font-family:'Josefin Sans',sans-serif;margin-bottom:14px;">
                  +$5.00</div>
                <button class="btn-ats success full" id="claimRewardBtn" type="button"><i class="las la-gift"></i> Claim
                  Reward</button>
                <div style="font-size:11px;color:var(--text-muted);margin-top:9px;">Next: <span
                    style="color:var(--secondary);font-weight:700;" id="rewardTimer">23:59:59</span></div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- ══ NOTIFICATIONS (in-page) ══ -->
      <div id="section-notifications" class="dash-section" style="display:none;">
        <div class="section-head mb-3">
          <h5><i class="las la-bell"></i>Notifications</h5><button type="button" id="markAllInPageBtn">Mark all as
            read</button>
        </div>
        <div class="ats-card" data-animate="fade-up">
          <div class="card-body" id="inPageNotifList"></div>
        </div>
      </div>

      <!-- ══ ANNOUNCEMENTS (NEW) ══ -->
      <div id="section-announcements" class="dash-section" style="display:none;">
        <div class="section-head mb-3">
          <h5><i class="las la-bullhorn"></i>Live Announcements</h5>
        </div>
        <div class="ats-card" data-animate="fade-up">
          <div class="card-body" id="announceList"></div>
        </div>
      </div>

      <!-- ══ VERIFICATION (NEW) ══ -->
      <div id="section-verification" class="dash-section" style="display:none;">
        <div class="section-head mb-3">
          <h5><i class="las la-shield-alt"></i>Account Verification</h5>
        </div>
        <div class="row">
          <div class="col-lg-7 mb-4" data-animate="fade-up">
            <div class="ats-card">
              <div class="card-head">
                <h5 class="card-head-title"><i class="las la-tasks"></i>Verification Steps</h5>
              </div>
              <div class="card-body">
                <div class="verify-step">
                  <div class="verify-icon done"><i class="las la-envelope"></i></div>
                  <div class="verify-body">
                    <div class="verify-title">Email Verification</div>
                    <div class="verify-sub">trader@email.com &#xB7; Verified Dec 1, 2024</div>
                  </div><span class="status-badge active">Done</span>
                </div>
                <div class="verify-step">
                  <div class="verify-icon done"><i class="las la-mobile-alt"></i></div>
                  <div class="verify-body">
                    <div class="verify-title">Phone Verification</div>
                    <div class="verify-sub">+1 (555) 000-0000 &#xB7; Verified</div>
                  </div><span class="status-badge active">Done</span>
                </div>
                <div class="verify-step">
                  <div class="verify-icon pending"><i class="las la-id-card"></i></div>
                  <div class="verify-body">
                    <div class="verify-title">Identity Verification</div>
                    <div class="verify-sub">Documents under review &#xB7; Submitted Dec 14</div>
                  </div><span class="status-badge pending">Pending</span>
                </div>
                <div class="verify-step">
                  <div class="verify-icon todo"><i class="las la-file-upload"></i></div>
                  <div class="verify-body">
                    <div class="verify-title">Proof of Address</div>
                    <div class="verify-sub">Not yet submitted</div>
                  </div><span class="status-badge expired">Required</span>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-5 mb-4" data-animate="fade-up" style="transition-delay:0.1s;">
            <div class="ats-card mb-3">
              <div class="card-head">
                <h5 class="card-head-title"><i class="las la-chart-pie"></i>Verification Progress</h5>
              </div>
              <div class="card-body">
                <div class="plan-progress-labels"><span>Overall Progress</span><span>50%</span></div>
                <div class="progress-bar-track">
                  <div class="progress-bar-fill" style="width:50%;"></div>
                </div>
                <p style="font-size:12px;color:var(--text-muted);margin-top:12px;">Complete all steps to unlock
                  unlimited withdrawals and higher deposit limits.</p>
              </div>
            </div>
            <div class="ats-card">
              <div class="card-head">
                <h5 class="card-head-title"><i class="las la-file-upload"></i>Upload Document</h5>
              </div>
              <div class="card-body">
                <div class="upload-zone" id="kycUploadZone"><i class="las la-cloud-upload-alt"></i>
                  <p>Click to upload ID or proof of address</p>
                </div>
                <input type="file" id="kycFileInput" accept="image/*,.pdf" style="display:none;">
                <button class="btn-ats primary full mt-3" id="kycSubmitBtn" type="button"><i
                    class="las la-paper-plane"></i> Submit for Review</button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- ══ ACTIVITY HISTORY (NEW) ══ -->
      <div id="section-activity" class="dash-section" style="display:none;">
        <div class="section-head mb-3">
          <h5><i class="las la-list-ul"></i>Activity History</h5>
        </div>
        <div class="ats-card" data-animate="fade-up">
          <div class="card-head" style="flex-wrap:wrap;gap:10px;">
            <div class="ats-tabs" id="actTabs">
              <button class="ats-tab-btn active" type="button" data-act-filter="all">All</button>
              <button class="ats-tab-btn" type="button" data-act-filter="finance">Finance</button>
              <button class="ats-tab-btn" type="button" data-act-filter="account">Account</button>
              <button class="ats-tab-btn" type="button" data-act-filter="security">Security</button>
            </div>
          </div>
          <div class="card-body" id="activityList"></div>
        </div>
      </div>

      <!-- ══ SUPPORT — +FAQ & live chat ══ -->
      <div id="section-support" class="dash-section" style="display:none;">
        <div class="section-head mb-3">
          <h5><i class="las la-headset"></i>Support Center</h5>
        </div>
        <div class="row">
          <div class="col-lg-5 mb-4" data-animate="fade-up">
            <div class="ats-card">
              <div class="card-head">
                <h5 class="card-head-title"><i class="las la-plus-circle"></i>Create Ticket</h5>
              </div>
              <div class="card-body">
                <div class="form-group"><label class="form-label">Subject</label><input type="text" class="ats-input"
                    placeholder="Describe your issue" id="tickSubject"></div>
                <div class="form-group"><label class="form-label">Category</label><select class="ats-select">
                    <option>Deposit Issue</option>
                    <option>Withdrawal Issue</option>
                    <option>AI Bot Performance</option>
                    <option>Account Access</option>
                    <option>Other</option>
                  </select></div>
                <div class="form-group"><label class="form-label">Priority</label><select class="ats-select">
                    <option>Low</option>
                    <option selected>Medium</option>
                    <option>Urgent</option>
                  </select></div>
                <div class="form-group"><label class="form-label">Message</label><textarea class="ats-textarea"
                    placeholder="Describe in detail&#x2026;" id="tickMsg"></textarea></div>
                <div class="form-group"><label class="form-label">Attachment (optional)</label><input type="file"
                    class="ats-input" style="padding-top:9px;"></div>
                <button class="btn-ats primary full" id="submitTicketBtn" type="button"><i
                    class="las la-paper-plane"></i> Submit Ticket</button>
              </div>
            </div>
            <div class="ats-card mt-3">
              <div class="card-head">
                <h5 class="card-head-title"><i class="las la-comments"></i>Live Chat</h5>
              </div>
              <div class="card-body text-center">
                <p style="font-size:12px;color:var(--text-muted);margin-bottom:12px;">Need instant help? Chat with our
                  team on Telegram.</p><button class="btn-ats success full" id="liveChatBtn" type="button"><i
                    class="lab la-telegram-plane"></i> Start Live Chat</button>
              </div>
            </div>
          </div>
          <div class="col-lg-7 mb-4" data-animate="fade-up" style="transition-delay:0.1s;">
            <div class="ats-card mb-3">
              <div class="card-head">
                <h5 class="card-head-title"><i class="las la-list"></i>My Tickets</h5>
              </div>
              <div class="card-body">
                <div class="ticket-card">
                  <div class="ticket-top"><span class="ticket-id">#TKT-0042</span><span
                      class="status-badge pending">Open</span></div>
                  <div class="ticket-title">Withdrawal processing longer than expected</div>
                  <div class="ticket-date"><i class="las la-clock"></i> Dec 14 &#xB7; Last reply: 2 hrs ago</div>
                </div>
                <div class="ticket-card">
                  <div class="ticket-top"><span class="ticket-id">#TKT-0038</span><span
                      class="status-badge complete">Resolved</span></div>
                  <div class="ticket-title">Unable to upload deposit proof</div>
                  <div class="ticket-date"><i class="las la-clock"></i> Dec 10 &#xB7; Resolved Dec 11</div>
                </div>
              </div>
            </div>
            <div class="ats-card">
              <div class="card-head">
                <h5 class="card-head-title"><i class="las la-question-circle"></i>Knowledge Base / FAQ</h5>
              </div>
              <div class="card-body" id="faqList"></div>
            </div>
          </div>
        </div>
      </div>

      <!-- ══ SETTINGS — +KYC status, security, login history, sessions ══ -->
      <div id="section-settings" class="dash-section" style="display:none;">
        <div class="section-head mb-3">
          <h5><i class="las la-cog"></i>Account Settings</h5>
        </div>
        <div class="row">
          <div class="col-lg-4 mb-4" data-animate="fade-up">
            <div class="ats-card text-center">
              <div class="card-body">
                <div class="avatar-ring" id="avatarRing">
                  <div class="avatar-img" id="avatarDisplay">T</div>
                  <div class="avatar-edit"><i class="las la-camera"></i></div>
                </div>
                <input type="file" id="avatarFileInput" accept="image/*" style="display:none;">
                <div style="font-size:15px;font-weight:700;color:#fff;margin-bottom:4px;">Trader_123</div>
                <div style="font-size:13px;color:var(--text-muted);margin-bottom:4px;">trader@email.com</div>
                <div style="font-size:12px;color:var(--text-muted);margin-bottom:14px;">United States</div>
                <span class="status-badge active"><i class="las la-shield-alt"></i> KYC Verified</span>
              </div>
            </div>
          </div>
          <div class="col-lg-8 mb-4" data-animate="fade-up" style="transition-delay:0.1s;">
            <div class="ats-card mb-3">
              <div class="card-head">
                <h5 class="card-head-title"><i class="las la-user"></i>Personal Information</h5>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-sm-6 mb-3">
                    <div class="form-group" style="margin-bottom:0;"><label class="form-label">Full Name</label><input
                        type="text" class="ats-input" value="John Trader"></div>
                  </div>
                  <div class="col-sm-6 mb-3">
                    <div class="form-group" style="margin-bottom:0;"><label class="form-label">Username</label><input
                        type="text" class="ats-input" value="Trader_123"></div>
                  </div>
                  <div class="col-sm-6 mb-3">
                    <div class="form-group" style="margin-bottom:0;"><label class="form-label">Email</label><input
                        type="email" class="ats-input" value="trader@email.com"></div>
                  </div>
                  <div class="col-sm-6 mb-3">
                    <div class="form-group" style="margin-bottom:0;"><label class="form-label">Phone</label><input
                        type="tel" class="ats-input" placeholder="+1 (555) 000-0000"></div>
                  </div>
                  <div class="col-sm-6 mb-3">
                    <div class="form-group" style="margin-bottom:0;"><label class="form-label">Country</label><select
                        class="ats-select">
                        <option>United States</option>
                        <option>United Kingdom</option>
                        <option>Nigeria</option>
                        <option>Canada</option>
                      </select></div>
                  </div>
                  <div class="col-sm-6 mb-3">
                    <div class="form-group" style="margin-bottom:0;"><label class="form-label">Withdrawal Wallet
                        (BTC)</label><input type="text" class="ats-input" placeholder="bc1q…"
                        style="font-family:monospace;font-size:12px;"></div>
                  </div>
                  <div class="col-12"><button class="btn-ats primary" id="saveSettingsBtn" type="button"><i
                        class="las la-save"></i> Save Changes</button></div>
                </div>
              </div>
            </div>
            <div class="ats-card mb-3">
              <div class="card-head">
                <h5 class="card-head-title"><i class="las la-lock"></i>Password &amp; Security</h5>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-sm-6 mb-3">
                    <div class="form-group" style="margin-bottom:0;"><label class="form-label">Current
                        Password</label><input type="password" class="ats-input"
                        placeholder="&#x2022;&#x2022;&#x2022;&#x2022;&#x2022;&#x2022;&#x2022;&#x2022;"></div>
                  </div>
                  <div class="col-sm-6 mb-3">
                    <div class="form-group" style="margin-bottom:0;"><label class="form-label">New
                        Password</label><input type="password" class="ats-input"
                        placeholder="&#x2022;&#x2022;&#x2022;&#x2022;&#x2022;&#x2022;&#x2022;&#x2022;"></div>
                  </div>
                  <div class="col-12"><button class="btn-ats outline" id="changePassBtn" type="button"><i
                        class="las la-key"></i> Update Password</button></div>
                </div>
                <div class="ats-divider"></div>
                <div class="setting-row">
                  <div>
                    <div class="setting-label">Two-Factor Authentication</div>
                    <div class="setting-desc">Add an extra layer of security</div>
                  </div><label class="toggle-wrap">
                    <div class="toggle-switch"><input type="checkbox">
                      <div class="toggle-track"></div>
                      <div class="toggle-thumb"></div>
                    </div>
                  </label>
                </div>
              </div>
            </div>
            <div class="ats-card mb-3">
              <div class="card-head">
                <h5 class="card-head-title"><i class="las la-bell"></i>Notification Preferences</h5>
              </div>
              <div class="card-body">
                <div class="setting-row">
                  <div>
                    <div class="setting-label">Profit Alerts</div>
                    <div class="setting-desc">Notify when profit credited</div>
                  </div><label class="toggle-wrap">
                    <div class="toggle-switch"><input type="checkbox" checked>
                      <div class="toggle-track"></div>
                      <div class="toggle-thumb"></div>
                    </div>
                  </label>
                </div>
                <div class="setting-row">
                  <div>
                    <div class="setting-label">Deposit Notifications</div>
                    <div class="setting-desc">Alerts on deposit confirmation</div>
                  </div><label class="toggle-wrap">
                    <div class="toggle-switch"><input type="checkbox" checked>
                      <div class="toggle-track"></div>
                      <div class="toggle-thumb"></div>
                    </div>
                  </label>
                </div>
                <div class="setting-row">
                  <div>
                    <div class="setting-label">Withdrawal Updates</div>
                    <div class="setting-desc">Track withdrawal processing</div>
                  </div><label class="toggle-wrap">
                    <div class="toggle-switch"><input type="checkbox" checked>
                      <div class="toggle-track"></div>
                      <div class="toggle-thumb"></div>
                    </div>
                  </label>
                </div>
                <div class="setting-row">
                  <div>
                    <div class="setting-label">AI Engine Updates</div>
                    <div class="setting-desc">Model update notifications</div>
                  </div><label class="toggle-wrap">
                    <div class="toggle-switch"><input type="checkbox">
                      <div class="toggle-track"></div>
                      <div class="toggle-thumb"></div>
                    </div>
                  </label>
                </div>
              </div>
            </div>
            <div class="ats-card">
              <div class="card-head">
                <h5 class="card-head-title"><i class="las la-history"></i>Login History &amp; Sessions</h5>
              </div>
              <div class="table-responsive-wrap">
                <table class="ats-table">
                  <thead>
                    <tr>
                      <th>Device</th>
                      <th>Location</th>
                      <th>IP</th>
                      <th>Date</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td class="fw-7">Chrome &#xB7; Windows</td>
                      <td>New York, US</td>
                      <td class="mono text-muted-a">192.168.1.1</td>
                      <td class="text-muted-a">Today, 09:12</td>
                      <td><span class="status-badge active">This Device</span></td>
                    </tr>
                    <tr>
                      <td class="fw-7">Safari &#xB7; iPhone</td>
                      <td>New York, US</td>
                      <td class="mono text-muted-a">192.168.1.44</td>
                      <td class="text-muted-a">Yesterday</td>
                      <td><span class="status-badge complete">Ended</span></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <footer class="dash-footer">&#x00A9; 2024 <a href="index.html">ATS &#x2014; Automated Trading System</a> &#xB7;
        All rights reserved &#xB7; <a href="contact.html">Support</a></footer>

    </main>
  </div>

  <div class="ats-fab" id="atsFab">
    <div class="fab-menu" id="fabMenu">
      <a class="fab-menu-item" id="fabTelegramLink" href="#" target="_blank" rel="noopener noreferrer"><i
          class="lab la-telegram-plane"></i> Telegram Support</a>
      <button class="fab-menu-item" type="button" data-nav="support"><i class="las la-ticket-alt"></i> Create
        Ticket</button>
      <button class="fab-menu-item" type="button" data-nav="calculator"><i class="las la-calculator"></i> AI
        Calculator</button>
    </div>
    <button class="fab-btn" id="fabBtn" type="button" aria-expanded="false" aria-label="AI Assistant"><i
        class="las la-robot"></i></button>
  </div>

  <button class="back-top" id="backTopBtn" type="button" aria-label="Back to top">&#x2191;</button>

  <script src="assets/js/vendor/jquery-3.5.1.min.js"></script>
  <script src="assets/js/vendor/bootstrap.bundle.min.js"></script>
  <script src="assets/js/vendor/slick.min.js"></script>
  <script src="assets/js/vendor/wow.min.js"></script>
  <script src="assets/js/contact.js"></script>
  <script src="assets/js/app.js"></script>

  <script>
    'use strict';

    /* ══════════════════════════════════════════════════════════
       CONFIG — these objects stand in for future PHP/MySQL API
       responses (GET /api/user/*). Structured 1:1 with what the
       Admin Dashboard writes, so swapping in fetch() later is a
       drop-in replacement with zero markup changes.
    ══════════════════════════════════════════════════════════ */
    var ATS_CONFIG = {
      supportLink: 'https://t.me/serveradmins', traderName: 'Trader',
      stats: {
        portfolio: 22991.90, profit: 9937.44, balance: 1105.00, todayProfit: 125.00, invested: 2000.00,
        referral: 720.00, pending: 1000.00, active: 3, pdeposit: 300.00, bonuses: 175.00, confidence: 94.7
      }
    };

    /* Admin-controlled payment methods (Module 1 of Admin Dashboard) */
    var PAYMENT_METHODS = [
      { id: 'btc', name: 'Bitcoin', symbol: 'BTC', network: 'Bitcoin Mainnet', addr: 'bc1qxy2kgdygjrsqtzq2n0yrf2493p83kkfjhx0wlh', color: '#f7931a', minDep: 10, maxDep: 999999, confirmations: 3, processing: '10-30 min', instructions: 'Send BTC to the address above. Wait for 3 confirmations before uploading proof.' },
      { id: 'eth', name: 'Ethereum', symbol: 'ETH', network: 'Ethereum Mainnet', addr: '0x1a2b3c4d5e6f7a8b9c0d1e2f3a4b5c6d7e8f9a0b', color: '#627eea', minDep: 10, maxDep: 999999, confirmations: 12, processing: '2-5 min', instructions: 'Send ETH to the address above. 12 confirmations required.' },
      { id: 'usdt_trc', name: 'Tether', symbol: 'USDT', network: 'TRON TRC20', addr: 'TQn9Y2khEsLJW1ChVWFMSMeRDow5KcbLSE', color: '#26a17b', minDep: 10, maxDep: 999999, confirmations: 20, processing: '1-2 min', instructions: 'Send USDT via TRC20 network only. Sending via another network will result in loss of funds.' },
      { id: 'sol', name: 'Solana', symbol: 'SOL', network: 'Solana Mainnet', addr: '7EcDhSYGxXyscszYEp35KHN8vvw3svAuLKTzXwCFLtV', color: '#9945ff', minDep: 10, maxDep: 999999, confirmations: 1, processing: 'Instant', instructions: 'Send SOL to the address above.' },
    ];

    var PLAN_NAMES = { '200:26.04:48:2': 'Beginner Access', '300:26.04:48:2': 'Premium Access', '400:26.04:48:2': 'Massive Growth', '500:26.04:48:2': 'Trading Mastery', '600:17.36:72:3': 'Elite Trader', '700:17.36:72:3': 'Professional Level', '900:17.36:72:3': 'VIP Access', '10000:17.36:72:3': 'Hack Bot Automation' };

    var AVAILABLE_PLANS = [
      { badge: 'starter', label: 'Starter', name: 'Beginner Access', rate: '26.04%', sub: '/hr · 2 Days · Min $200', profit: '$2,500', cls: 'starter' },
      { badge: 'elite', label: 'Elite', name: 'Elite Trader', rate: '17.36%', sub: '/hr · 3 Days · Min $600', profit: '$7,500', cls: 'elite' },
      { badge: 'vip', label: 'VIP', name: 'VIP Access', rate: '17.36%', sub: '/hr · 3 Days · Min $900', profit: '$11,250', cls: 'vip' },
      { badge: 'unlimited', label: 'Unlimited', name: 'Hack Bot', rate: '17.36%', sub: '/hr · 3 Days · Min $10,000', profit: '$125,000+', cls: 'unlimited' },
    ];

    var ACTIVE_INVESTMENTS = [
      { id: '#INV-001', plan: 'Trading Mastery', amount: 500, profit: 156.24, roi: '12.5%', progress: 12.5, started: 'Dec 16', maturity: 'Dec 18', aiConf: '94.7%', color: '' },
      { id: '#INV-002', plan: 'Elite Trader', amount: 600, profit: 312.48, roi: '27.4%', progress: 27.4, started: 'Dec 15', maturity: 'Dec 19', aiConf: '93.1%', color: 'linear-gradient(90deg,var(--warning),#ff8c00)' },
      { id: '#INV-003', plan: 'VIP Access', amount: 900, profit: 468.72, roi: '38.2%', progress: 38.2, started: 'Dec 14', maturity: 'Dec 20', aiConf: '95.4%', color: 'linear-gradient(90deg,var(--danger),#9333ea)' },
    ];

    /* Transactions — full category set incl. manual adjustments from admin */
    var TRANSACTIONS = [
      { date: 'Dec 16', id: 'DZQXF5NAN2AT', type: 'profit', label: 'Interest', amount: 125, wallet: 'Interest Wallet', details: 'Trading Mastery', status: 'active' },
      { date: 'Dec 15', id: 'AK8MXPR9VT3Z', type: 'deposit', label: 'Deposit', amount: 500, wallet: 'Deposit Wallet', details: 'Bitcoin', status: 'complete' },
      { date: 'Dec 14', id: 'BQ7NLST4WX1M', type: 'withdrawal', label: 'Withdrawal', amount: -1000, wallet: 'External', details: 'Ethereum', status: 'pending' },
      { date: 'Dec 13', id: 'CR3PQWV8YH5J', type: 'bonus', label: 'Referral', amount: 25, wallet: 'Interest Wallet', details: 'Commission', status: 'active' },
      { date: 'Dec 12', id: 'FX9KLM2QP8RT', type: 'manual', label: 'Manual Credit', amount: 50, wallet: 'Main Balance', details: 'Telegram Task Reward — Admin', status: 'complete' },
      { date: 'Dec 11', id: 'HN4TWZ7YB3VC', type: 'bonus', label: 'Welcome Bonus', amount: 50, wallet: 'Main Balance', details: 'Signup reward', status: 'complete' },
      { date: 'Dec 9', id: 'JQ2RXF6MK9LP', type: 'manual', label: 'Cashback', amount: 20, wallet: 'Main Balance', details: 'Weekend promotion — Admin', status: 'complete' },
    ];

    var WITHDRAWALS = [
      { date: 'Dec 14', amount: 1000, method: 'Ethereum', wallet: '0x1a2b…9f3e', status: 'pending', reason: null },
      { date: 'Dec 8', amount: 703, method: 'Bitcoin', wallet: 'bc1q…4m7p', status: 'complete', reason: null },
      { date: 'Dec 5', amount: 200, method: 'USDT TRC20', wallet: 'TQn9…bLSE', status: 'rejected', reason: 'Upgrade your account before withdrawals can be processed. Please upgrade to a qualifying investment plan to enable withdrawal functionality.', rejectedDate: 'Dec 5, 2024 16:20', rejectedBy: 'Admin' },
    ];

    var DEPOSITS_HISTORY = [
      { date: 'Dec 16', ref: 'DEP-8841', amount: 500, method: 'Bitcoin', status: 'complete' },
      { date: 'Dec 10', ref: 'DEP-8790', amount: 1000, method: 'USDT TRC20', status: 'complete' },
      { date: 'Dec 5', ref: 'DEP-8722', amount: 300, method: 'Ethereum', status: 'pending' },
      { date: 'Dec 1', ref: 'DEP-8650', amount: 150, method: 'Solana', status: 'rejected', reason: 'Payment proof image was unreadable. Please resubmit a clearer screenshot.' },
    ];

    var BONUS_HISTORY = [
      { title: 'Welcome Bonus', category: 'welcome', amount: 50, ref: 'BON-1001', date: 'Nov 20', status: 'complete' },
      { title: 'Telegram Task Reward', category: 'task', amount: 50, ref: 'BON-1042', date: 'Dec 12', status: 'complete' },
      { title: 'Weekend Cashback', category: 'cashback', amount: 20, ref: 'BON-1058', date: 'Dec 9', status: 'complete' },
      { title: 'Referral Bonus — James H.', category: 'referral', amount: 25, ref: 'BON-1063', date: 'Dec 13', status: 'complete' },
      { title: 'Weekly Giveaway Winner', category: 'promotion', amount: 100, ref: 'BON-1071', date: 'Dec 15', status: 'complete' },
    ];

    var NOTIFICATIONS = [
      { icon: 'la-check-circle', color: 'accent', title: 'Profit Credited — $125.00', msg: 'Interest from Trading Mastery plan credited to your wallet.', time: '2 minutes ago', unread: true },
      { icon: 'la-arrow-down', color: 'primary', title: 'Deposit Approved — $500', msg: 'Bitcoin deposit confirmed after 3 confirmations.', time: '1 hour ago', unread: true },
      { icon: 'la-robot', color: 'purple', title: 'AI Engine Update', msg: 'Neural model v4.2 deployed. Confidence 94.7%.', time: '3 hours ago', unread: true },
      { icon: 'la-gift', color: 'warning', title: 'Referral Commission Earned', msg: 'You earned $25 referral commission.', time: 'Yesterday', unread: false },
      { icon: 'la-arrow-up', color: 'secondary', title: 'Withdrawal Completed', msg: '$1,000 ETH withdrawal processed successfully.', time: '2 days ago', unread: false },
      { icon: 'la-sliders-h', color: 'purple', title: 'Manual Credit — +$50', msg: 'Telegram Task Reward credited by admin.', time: '3 days ago', unread: false },
    ];

    var ANNOUNCEMENTS = [
      { type: 'info', icon: 'la-tools', title: 'Scheduled Trading Maintenance', msg: 'AI engines will briefly pause for upgrades on Dec 20, 2:00 AM UTC. No action needed.', time: '2 hours ago' },
      { type: 'warning', icon: 'la-layer-group', title: 'New Investment Plan Launched', msg: '"Hack Bot Automation" is now live with 17.36%/hr returns. Minimum $10,000.', time: '1 day ago' },
      { type: 'info', icon: 'la-gift', title: 'Weekend Deposit Boost', msg: 'Get an extra 5% bonus on all deposits made this Saturday and Sunday.', time: '2 days ago' },
    ];

    var ACTIVITY_LOG = [
      { cat: 'finance', icon: 'la-arrow-down', color: 'accent', action: 'Deposit Approved — $500', meta: 'Dec 16, 2024 14:32' },
      { cat: 'finance', icon: 'la-arrow-up', color: 'danger', action: 'Withdrawal Requested — $1,000', meta: 'Dec 14, 2024 09:32' },
      { cat: 'finance', icon: 'la-sliders-h', color: 'purple', action: 'Manual Credit Received — +$50 Telegram Task Reward', meta: 'Dec 12, 2024 11:00' },
      { cat: 'account', icon: 'la-user-edit', color: 'primary', action: 'Profile Updated', meta: 'Dec 10, 2024 08:15' },
      { cat: 'security', icon: 'la-key', color: 'warning', action: 'Password Changed', meta: 'Dec 8, 2024 19:40' },
      { cat: 'security', icon: 'la-sign-in-alt', color: 'secondary', action: 'Login from New Device — Chrome, Windows', meta: 'Dec 16, 2024 09:12' },
      { cat: 'finance', icon: 'la-users', color: 'accent', action: 'Referral Commission Earned — +$25', meta: 'Dec 13, 2024 17:05' },
    ];

    var FAQ_ITEMS = [
      { q: 'How long do deposits take to confirm?', a: 'Bitcoin deposits typically confirm within 10-30 minutes. USDT (TRC20) confirms in 1-2 minutes.' },
      { q: 'Why was my withdrawal rejected?', a: 'Withdrawals are rejected for reasons like incomplete verification, invalid wallet address, or account requirements. Check your Withdrawal History for the specific reason.' },
      { q: 'How is my AI Confidence Score calculated?', a: 'The score reflects the neural model\'s current prediction accuracy across your active investment plans, updated in real time.' },
      { q: 'Can I have multiple active investments?', a: 'Yes, you can run multiple AI trading plans simultaneously, each independently tracked with its own progress and ROI.' },
    ];

    var WALLETS = { btc: { addr: 'bc1qxy2kgdygjrsqtzq2n0yrf2493p83kkfjhx0wlh', name: 'BTC', network: 'Bitcoin Mainnet' }, eth: { addr: '0x1a2b3c4d5e6f7a8b9c0d1e2f3a4b5c6d7e8f9a0b', name: 'ETH', network: 'Ethereum Mainnet' }, usdt_trc: { addr: 'TQn9Y2khEsLJW1ChVWFMSMeRDow5KcbLSE', name: 'USDT TRC20', network: 'TRON TRC20' }, sol: { addr: '7EcDhSYGxXyscszYEp35KHN8vvw3svAuLKTzXwCFLtV', name: 'SOL', network: 'Solana Mainnet' } };

    var state = { sidebarOpen: false, notifOpen: false, fabOpen: false, chartsInited: false, selectedPM: null };
    var DOM = {};

    function qs(s, c) { return (c || document).querySelector(s); }
    function qsa(s, c) { return (c || document).querySelectorAll(s); }
    function on(el, ev, fn, o) { if (el) el.addEventListener(ev, fn, o || false); }
    function fmtUSD(v) { return '$' + parseFloat(v).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }); }

    function getScrollbarWidth() { var d = document.createElement('div'); d.style.cssText = 'width:100px;height:100px;overflow:scroll;position:absolute;top:-9999px;'; document.body.appendChild(d); var w = d.offsetWidth - d.clientWidth; document.body.removeChild(d); return w; }
    function lockScroll() { var w = getScrollbarWidth(); document.documentElement.style.setProperty('--sb-width', w + 'px'); document.body.classList.add('scroll-locked'); }
    function unlockScroll() { document.body.classList.remove('scroll-locked'); document.documentElement.style.removeProperty('--sb-width'); }

    function checkIconFont() {
      var test = document.createElement('i'); test.className = 'las la-times'; test.style.cssText = 'position:absolute;left:-9999px;visibility:hidden;font-size:16px;';
      document.body.appendChild(test);
      var content = window.getComputedStyle(test, ':before').content;
      document.body.removeChild(test);
      var loaded = content && content !== 'none' && content !== '""' && content !== "''";
      if (loaded) { qsa('.notif-close-btn .close-icon').forEach(function (el) { el.style.display = 'block'; }); qsa('.notif-close-btn .close-unicode').forEach(function (el) { el.style.display = 'none'; }); }
    }

    /* ── SIDEBAR ── */
    function openSidebar() { if (!DOM.sidebar) return; DOM.sidebar.classList.add('sidebar-is-open'); DOM.sidebarOverlay.classList.add('overlay-visible'); DOM.sidebarToggleBtn.setAttribute('aria-expanded', 'true'); if (window.innerWidth <= 820) lockScroll(); state.sidebarOpen = true; }
    function closeSidebar() { if (!state.sidebarOpen) return; DOM.sidebar.classList.remove('sidebar-is-open'); DOM.sidebarOverlay.classList.remove('overlay-visible'); DOM.sidebarToggleBtn.setAttribute('aria-expanded', 'false'); unlockScroll(); state.sidebarOpen = false; DOM.sidebarToggleBtn.focus(); }

    /* ── NOTIFICATIONS ── */
    function renderNotifList() {
      var list = qs('#notifList'); var inPage = qs('#inPageNotifList'); if (!list) return;
      var html = NOTIFICATIONS.map(function (n) {
        return '<div class="notif-item' + (n.unread ? ' unread' : '') + '" role="listitem"><div class="notif-icon" style="background:rgba(0,0,0,0.15);color:var(--' + n.color + ');"><i class="las ' + n.icon + '"></i></div><div class="notif-body"><div class="notif-title">' + n.title + '</div><div class="notif-msg">' + n.msg + '</div><div class="notif-time">' + n.time + '</div></div>' + (n.unread ? '<div class="notif-unread-dot"></div>' : '') + '</div>';
      }).join('');
      list.innerHTML = html;
      if (inPage) inPage.innerHTML = NOTIFICATIONS.map(function (n) {
        return '<div class="notif-item' + (n.unread ? ' unread' : '') + ' mb-2"><div class="notif-icon" style="background:rgba(0,0,0,0.15);color:var(--' + n.color + ');"><i class="las ' + n.icon + '"></i></div><div class="notif-body"><div class="notif-title">' + n.title + '</div><div class="notif-msg">' + n.msg + '</div><div class="notif-time">' + n.time + '</div></div>' + (n.unread ? '<div class="notif-unread-dot"></div>' : '') + '</div>';
      }).join('');
    }
    function openNotifDrawer() { if (!DOM.notifDrawer) return; DOM.notifDrawer.classList.add('notif-is-open'); DOM.notifOverlay.classList.add('notif-overlay-visible'); lockScroll(); state.notifOpen = true; setTimeout(function () { if (DOM.notifCloseBtn) DOM.notifCloseBtn.focus(); }, 320); }
    function closeNotifDrawer() { if (!state.notifOpen) return; DOM.notifDrawer.classList.remove('notif-is-open'); DOM.notifOverlay.classList.remove('notif-overlay-visible'); unlockScroll(); state.notifOpen = false; if (DOM.notifToggleBtn) DOM.notifToggleBtn.focus(); }
    function markAllRead() { NOTIFICATIONS.forEach(function (n) { n.unread = false; }); renderNotifList(); updateNotifBadge(); showToast('All notifications marked as read', 'success'); }
    function clearAllNotifs() { NOTIFICATIONS.length = 0; renderNotifList(); updateNotifBadge(); showToast('All notifications cleared', 'success'); }
    function updateNotifBadge() { var count = NOTIFICATIONS.filter(function (n) { return n.unread; }).length; var badge = qs('#notifBadge'), navBadge = qs('#navNotifBadge'), dot = qs('#notifDot'); if (badge) { badge.textContent = count; badge.style.display = count > 0 ? 'inline' : 'none'; } if (navBadge) { navBadge.textContent = count; navBadge.style.display = count > 0 ? 'inline' : 'none'; } if (dot) dot.style.display = count > 0 ? 'block' : 'none'; }

    /* ── FAB ── */
    function toggleFab() { state.fabOpen = !state.fabOpen; qs('#atsFab').classList.toggle('open', state.fabOpen); qs('#fabBtn').setAttribute('aria-expanded', String(state.fabOpen)); }

    /* ── TOAST ── */
    function showToast(msg, type, title) { type = type || 'info'; title = title || (type === 'success' ? 'Success' : type === 'error' ? 'Error' : 'Notice'); var icons = { success: '&#x2714;', error: '&#x2716;', info: '&#x2139;' }; var el = document.createElement('div'); el.className = 'toast-item ' + type; el.innerHTML = '<span class="toast-icon">' + (icons[type] || icons.info) + '</span><div class="toast-body"><div class="toast-title">' + title + '</div><div class="toast-msg">' + msg + '</div></div>'; var c = qs('#toastContainer'); if (c) c.appendChild(el); setTimeout(function () { el.classList.add('removing'); setTimeout(function () { if (el.parentNode) el.parentNode.removeChild(el); }, 350); }, 4500); }

    /* ── SECTION NAV ── */
    function showSection(name) {
      qsa('.dash-section').forEach(function (s) { s.style.display = 'none'; });
      var target = qs('#section-' + name); if (target) { target.style.display = 'block'; triggerIO(); }
      qsa('.sidebar-nav li a').forEach(function (a) { a.classList.remove('active'); a.removeAttribute('aria-current'); });
      var navItem = qs('[data-section="' + name + '"]'); if (navItem) { navItem.classList.add('active'); navItem.setAttribute('aria-current', 'page'); }
      if (name === 'analytics') initCharts();
      if (window.innerWidth <= 820 && state.sidebarOpen) closeSidebar();
      window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    /* ── ANIMATED COUNTERS ── */
    function countUp(id, target, pre, suf, dec) { pre = pre || ''; suf = suf || ''; dec = dec !== undefined ? dec : 2; var el = qs('#' + id); if (!el) return; var dur = 1200, t0 = null; function tick(ts) { if (!t0) t0 = ts; var p = Math.min((ts - t0) / dur, 1), e = 1 - Math.pow(1 - p, 3); el.textContent = pre + (target * e).toLocaleString('en-US', { minimumFractionDigits: dec, maximumFractionDigits: dec }) + suf; if (p < 1) requestAnimationFrame(tick); } requestAnimationFrame(tick); }
    function initStatCounters() {
      var s = ATS_CONFIG.stats;
      countUp('sc-portfolio', s.portfolio, '$', '', 2); countUp('sc-profit', s.profit, '$', '', 2); countUp('sc-balance', s.balance, '$', '', 2); countUp('sc-today', s.todayProfit, '$', '', 2);
      countUp('sc-invested', s.invested, '$', '', 2); countUp('sc-referral', s.referral, '$', '', 2); countUp('sc-pending', s.pending, '$', '', 2); countUp('sc-active', s.active, '', '', 0);
      countUp('sc-pdeposit', s.pdeposit, '$', '', 2); countUp('sc-bonuses', s.bonuses, '$', '', 2); countUp('sc-confidence', s.confidence, '', '%', 1);
      countUp('ref-count', 12, '', '', 0); countUp('ref-active', 9, '', '', 0); countUp('ref-earn', 720, '$', '', 0); countUp('ref-pending', 25, '$', '', 0);
    }
    function initGauge(score) { var fill = qs('#gaugeFill'), valEl = qs('#gaugeVal'); if (!fill || !valEl) return; var circ = 2 * Math.PI * 55, dur = 1500, t0 = null; function tick(ts) { if (!t0) t0 = ts; var p = Math.min((ts - t0) / dur, 1), e = 1 - Math.pow(1 - p, 3), c = score * e; fill.style.strokeDashoffset = circ - (circ * c / 100); valEl.textContent = Math.round(c) + '%'; if (p < 1) requestAnimationFrame(tick); } requestAnimationFrame(tick); }

    function initMiniCharts() {
      if (typeof ApexCharts === 'undefined') return;
      [{ id: 'miniChart1', data: [8, 9.2, 10.8, 11.5, 13.2, 15.1, 17.8, 19.2, 21, 22.9], color: '#0A84FF' },
      { id: 'miniChart2', data: [200, 580, 1200, 2100, 3400, 5200, 6800, 8100, 9100, 9937], color: '#00FFB3' },
      { id: 'miniChart3', data: [800, 750, 900, 820, 1000, 950, 1100, 1050, 1200, 1105], color: '#00D4FF' },
      { id: 'miniChart4', data: [80, 95, 110, 105, 120, 115, 125, 130, 120, 125], color: '#ffd700' }].forEach(function (cfg) {
        var el = qs('#' + cfg.id); if (!el) return; el.innerHTML = '';
        new ApexCharts(el, { chart: { type: 'area', height: 40, sparkline: { enabled: true }, animations: { enabled: true, speed: 800 }, parentHeightOffset: 0 }, series: [{ name: ' ', data: cfg.data }], stroke: { curve: 'smooth', width: 2 }, fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.35, opacityTo: 0.02 } }, colors: [cfg.color], legend: { show: false }, dataLabels: { enabled: false }, tooltip: { enabled: false }, xaxis: { labels: { show: false }, axisBorder: { show: false }, axisTicks: { show: false } }, yaxis: { labels: { show: false } }, grid: { show: false, padding: { top: 0, right: 0, bottom: 0, left: 0 } }, theme: { mode: 'dark' } }).render();
      });
    }

    function initCharts() {
      if (state.chartsInited || typeof ApexCharts === 'undefined') return; state.chartsInited = true;
      var base = { chart: { background: 'transparent', toolbar: { show: false }, animations: { enabled: true, speed: 700 } }, theme: { mode: 'dark' }, grid: { borderColor: 'rgba(10,132,255,0.08)', strokeDashArray: 3 }, tooltip: { theme: 'dark' }, legend: { show: false } };
      function render(id, opts) { var el = qs('#' + id); if (!el || el.dataset.inited) return; el.dataset.inited = '1'; el.innerHTML = ''; new ApexCharts(el, opts).render(); }
      render('portfolioChart', Object.assign({}, base, { chart: Object.assign({}, base.chart, { type: 'area', height: 280 }), series: [{ name: 'Portfolio', data: [8000, 9200, 10800, 11500, 13200, 15100, 17800, 19200, 21000, 22991] }], xaxis: { categories: ['Dec 7', 'Dec 8', 'Dec 9', 'Dec 10', 'Dec 11', 'Dec 12', 'Dec 13', 'Dec 14', 'Dec 15', 'Dec 16'], labels: { style: { colors: 'rgba(232,234,246,0.5)', fontSize: '11px' } } }, yaxis: { labels: { formatter: function (v) { return '$' + (v / 1000).toFixed(1) + 'K'; }, style: { colors: 'rgba(232,234,246,0.5)' } } }, fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.05 } }, stroke: { curve: 'smooth', width: 2 }, colors: ['#0A84FF'] }));
      render('allocationChart', Object.assign({}, base, { chart: Object.assign({}, base.chart, { type: 'donut', height: 280 }), series: [500, 600, 900, 1105, 720], labels: ['Trading Mastery', 'Elite Trader', 'VIP Access', 'Interest Wallet', 'Referrals'], colors: ['#0A84FF', '#ffd700', '#ff4d6d', '#00FFB3', '#9333ea'], plotOptions: { pie: { donut: { size: '65%' } } } }));
      render('roiChart', Object.assign({}, base, { chart: Object.assign({}, base.chart, { type: 'bar', height: 220 }), series: [{ name: 'Daily ROI', data: [125, 142, 138, 156, 162, 145, 170, 188, 175, 195] }], xaxis: { categories: ['Dec 7', 'Dec 8', 'Dec 9', 'Dec 10', 'Dec 11', 'Dec 12', 'Dec 13', 'Dec 14', 'Dec 15', 'Dec 16'], labels: { style: { colors: 'rgba(232,234,246,0.5)', fontSize: '11px' } } }, yaxis: { labels: { formatter: function (v) { return '$' + v; }, style: { colors: 'rgba(232,234,246,0.5)' } } }, colors: ['#00FFB3'], plotOptions: { bar: { borderRadius: 4 } } }));
      render('winLossChart', Object.assign({}, base, { chart: Object.assign({}, base.chart, { type: 'radialBar', height: 220 }), series: [94.7], labels: ['Win Rate'], colors: ['#00FFB3'], plotOptions: { radialBar: { hollow: { size: '60%' }, dataLabels: { value: { fontSize: '22px', color: '#fff', formatter: function (v) { return v + '%'; } } } } } }));
      render('monthlyChart', Object.assign({}, base, { chart: Object.assign({}, base.chart, { type: 'bar', height: 220 }), series: [{ name: 'Earnings', data: [1200, 1450, 1380, 1620, 1890, 2100, 2340] }], xaxis: { categories: ['Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'], labels: { style: { colors: 'rgba(232,234,246,0.5)', fontSize: '11px' } } }, yaxis: { labels: { formatter: function (v) { return '$' + v; }, style: { colors: 'rgba(232,234,246,0.5)' } } }, colors: ['#9333ea'], plotOptions: { bar: { borderRadius: 4 } } }));
      render('profitHistChart', Object.assign({}, base, { chart: Object.assign({}, base.chart, { type: 'area', height: 220 }), series: [{ name: 'Profit', data: [2100, 3400, 4200, 5100, 6300, 7800, 9937] }], xaxis: { categories: ['Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec 1', 'Dec 16'], labels: { style: { colors: 'rgba(232,234,246,0.5)', fontSize: '11px' } } }, yaxis: { labels: { formatter: function (v) { return '$' + (v / 1000).toFixed(1) + 'K'; }, style: { colors: 'rgba(232,234,246,0.5)' } } }, fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.05 } }, stroke: { curve: 'smooth', width: 2 }, colors: ['#00FFB3'] }));
      render('aiPerfChart', Object.assign({}, base, { chart: Object.assign({}, base.chart, { type: 'line', height: 220 }), series: [{ name: 'AI Confidence', data: [88, 90, 91, 89, 93, 91, 94, 92, 94, 94.7] }, { name: 'Win Rate', data: [90, 91, 93, 90, 94, 92, 95, 93, 95, 96] }], xaxis: { categories: ['Dec 7', 'Dec 8', 'Dec 9', 'Dec 10', 'Dec 11', 'Dec 12', 'Dec 13', 'Dec 14', 'Dec 15', 'Dec 16'], labels: { style: { colors: 'rgba(232,234,246,0.5)', fontSize: '11px' } } }, yaxis: { min: 80, max: 100, labels: { formatter: function (v) { return v + '%'; }, style: { colors: 'rgba(232,234,246,0.5)' } } }, stroke: { curve: 'smooth', width: [2, 2] }, colors: ['#0A84FF', '#00FFB3'] }));
    }

    /* ── TICKER (unchanged, Binance WS + fallback) ── */
    function initTicker() {
      var PAIRS = [['btcusdt', 'BTC/USD'], ['ethusdt', 'ETH/USD'], ['solusdt', 'SOL/USD'], ['bnbusdt', 'BNB/USD'], ['xrpusdt', 'XRP/USD'], ['dogeusdt', 'DOGE/USD'], ['adausdt', 'ADA/USD'], ['maticusdt', 'MATIC/USD']];
      var live = {}, ws = null, retryMs = 3000, buildTimer = null;
      function fmtPrice(p) { p = parseFloat(p); if (isNaN(p)) return '\u2014'; if (p >= 1000) return '$' + p.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }); if (p >= 1) return '$' + p.toFixed(4); return '$' + p.toFixed(6); }
      function fmtChg(c) { c = parseFloat(c); if (isNaN(c)) return ''; return (c >= 0 ? '+' : '') + c.toFixed(2) + '%'; }
      function buildTicker() { var el = qs('#tickerInner'); if (!el) return; var half = ''; PAIRS.forEach(function (p) { var sym = p[0], lbl = p[1], d = live[sym] || {}; var pr = d.price ? fmtPrice(d.price) : '\u2014'; var ch = d.change !== undefined ? parseFloat(d.change) : null; var cls = ch === null ? '' : (ch >= 0 ? 'up' : 'down'); var ct = ch !== null ? fmtChg(ch) : ''; half += '<span class="ticker-item"><span class="symbol">' + lbl + '</span><span class="price">' + pr + '</span>'; if (ct) half += '<span class="' + cls + '">' + ct + '</span>'; half += '</span><span class="ticker-sep">\u00B7</span>'; }); el.innerHTML = half + half; }
      function scheduleBuild() { if (buildTimer) return; buildTimer = setTimeout(function () { buildTimer = null; buildTicker(); }, 500); }
      function connect() { var streams = PAIRS.map(function (p) { return p[0] + '@ticker'; }).join('/'); try { ws = new WebSocket('wss://stream.binance.com:9443/stream?streams=' + streams); } catch (e) { retry(); return; } ws.onopen = function () { retryMs = 3000; }; ws.onclose = function () { retry(); }; ws.onerror = function () { }; ws.onmessage = function (e) { try { var m = JSON.parse(e.data); if (!m || !m.data) return; live[(m.data.s || '').toLowerCase()] = { price: m.data.c, change: m.data.P }; scheduleBuild(); } catch (ex) { } }; }
      function retry() { setTimeout(connect, retryMs); retryMs = Math.min(retryMs * 1.5, 30000); }
      connect(); buildTicker();
    }

    /* ── CALCULATOR — includes weekly/monthly returns ── */
    function runCalculator() {
      var planEl = qs('#calcPlanSel'), amtEl = qs('#calcAmtInput'), errEl = qs('#calcErrMsg'), resWrap = qs('#calcResultsWrap');
      if (!planEl || !amtEl) return;
      errEl.classList.remove('show'); amtEl.classList.remove('error');
      var parts = planEl.value.split(':'), minAmt = parseFloat(parts[0]), rate = parseFloat(parts[1]), hours = parseInt(parts[2]), days = parseInt(parts[3]);
      var planName = PLAN_NAMES[planEl.value] || 'Selected Plan', amount = parseFloat(amtEl.value);
      if (!amtEl.value.trim() || isNaN(amount) || amount <= 0) { errEl.textContent = '\u26A0 Please enter a valid amount.'; errEl.classList.add('show'); amtEl.classList.add('error'); amtEl.focus(); return; }
      if (amount < minAmt) { errEl.textContent = '\u26A0 Minimum for ' + planName + ' is $' + minAmt.toLocaleString('en-US') + '.'; errEl.classList.add('show'); amtEl.classList.add('error'); return; }
      var hourly = amount * (rate / 100), daily = hourly * 24, weekly = daily * 7, monthly = daily * 30, profit = hourly * hours, total = amount + profit, roi = (profit / amount) * 100;
      var comp = new Date(); comp.setDate(comp.getDate() + days);
      var compStr = comp.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
      resWrap.style.opacity = '1'; resWrap.style.pointerEvents = 'all';
      var lbl = qs('#calcPlanLabel'); if (lbl) lbl.innerHTML = '<i class="las la-robot"></i> ' + planName;
      function animNum(id, target, pre, suf, dec) { var el = qs('#' + id); if (!el) return; pre = pre || ''; suf = suf || ''; dec = dec !== undefined ? dec : 2; var dur = 800, t0 = null; function tick(ts) { if (!t0) t0 = ts; var p = Math.min((ts - t0) / dur, 1), e = 1 - Math.pow(1 - p, 3); el.textContent = pre + (target * e).toLocaleString('en-US', { minimumFractionDigits: dec, maximumFractionDigits: dec }) + suf; if (p < 1) requestAnimationFrame(tick); } requestAnimationFrame(tick); }
      function setText(id, val) { var el = qs('#' + id); if (el) el.textContent = val; }
      animNum('cr-hourly', hourly, '$', '', 2); animNum('cr-daily', daily, '$', '', 2); animNum('cr-weekly', weekly, '$', '', 2); animNum('cr-monthly', monthly, '$', '', 2);
      animNum('cr-roi', roi, '', '%', 2); animNum('cr-profit', profit, '$', '', 2); animNum('cr-total', total, '$', '', 2);
      setText('cr-days', days + ' Days'); setText('cr-completion', compStr);
    }

    /* ── DEPOSIT (Requirement 5 — dynamic from PAYMENT_METHODS) ── */
    function renderPMSelector() {
      var grid = qs('#pmSelectGrid'); if (!grid) return;
      grid.innerHTML = PAYMENT_METHODS.map(function (pm) {
        return '<div class="pm-select-card" data-pm="' + pm.id + '"><div class="pm-select-icon" style="background:' + pm.color + ';">' + pm.symbol.charAt(0) + '</div><div class="pm-select-name">' + pm.symbol + '</div><div class="pm-select-net">' + pm.network + '</div></div>';
      }).join('');
      qsa('.pm-select-card', grid).forEach(function (card) {
        on(card, 'click', function () { selectPaymentMethod(this.dataset.pm); qsa('.pm-select-card').forEach(function (c) { c.classList.remove('selected'); }); this.classList.add('selected'); });
      });
      if (PAYMENT_METHODS.length) { selectPaymentMethod(PAYMENT_METHODS[0].id); qs('.pm-select-card').classList.add('selected'); }
    }
    function selectPaymentMethod(id) {
      var pm = PAYMENT_METHODS.filter(function (p) { return p.id === id; })[0]; if (!pm) return;
      state.selectedPM = pm;
      var addrEl = qs('#depAddrText'), qrEl = qs('#depQRWrap'), metaEl = qs('#pmMetaGrid'), insEl = qs('#depInstructionsText');
      if (addrEl) addrEl.textContent = pm.addr;
      if (qrEl) { qrEl.innerHTML = ''; if (typeof QRCode !== 'undefined') { try { new QRCode(qrEl, { text: pm.addr, width: 130, height: 130, colorDark: '#000', colorLight: '#fff', correctLevel: QRCode.CorrectLevel.H }); } catch (e) { qrEl.textContent = 'QR unavailable'; } } }
      if (metaEl) metaEl.innerHTML = '<div class="pm-meta-box"><div class="lbl">Min Deposit</div><div class="val">' + fmtUSD(pm.minDep) + '</div></div><div class="pm-meta-box"><div class="lbl">Confirmations</div><div class="val">' + pm.confirmations + '</div></div><div class="pm-meta-box"><div class="lbl">Network</div><div class="val" style="font-size:11px;">' + pm.network + '</div></div><div class="pm-meta-box"><div class="lbl">Processing</div><div class="val" style="font-size:11px;">' + pm.processing + '</div></div>';
      if (insEl) insEl.innerHTML = '&#x26A0;&#xFE0F; ' + pm.instructions.replace('[COIN]', pm.symbol);
    }
    function renderDepositHistory() {
      var tbody = qs('#depHistoryTable tbody'); if (!tbody) return;
      tbody.innerHTML = DEPOSITS_HISTORY.map(function (d) {
        var badgeCls = d.status === 'complete' ? 'complete' : d.status === 'pending' ? 'pending' : 'expired';
        var row = '<tr><td class="text-muted-a">' + d.date + '</td><td class="mono text-cyan" style="font-size:11px;">' + d.ref + '</td><td class="text-accent fw-7">' + fmtUSD(d.amount) + '</td><td>' + d.method + '</td><td><span class="status-badge ' + badgeCls + '">' + d.status.charAt(0).toUpperCase() + d.status.slice(1) + '</span></td></tr>';
        if (d.status === 'rejected' && d.reason) row += '<tr><td colspan="5" style="padding-top:0;"><div class="wd-reason-box"><div class="wd-reason-label">Rejection Reason</div><div class="wd-reason-text">' + d.reason + '</div></div></td></tr>';
        return row;
      }).join('');
    }

    /* ── WITHDRAWAL (Requirement 6 — full status set + permanent rejection reasons) ── */
    function renderWithdrawalHistory() {
      var list = qs('#wdHistoryList'); if (!list) return;
      list.innerHTML = '<div class="table-responsive-wrap"><table class="ats-table"><thead><tr><th>Date</th><th>Amount</th><th>Method</th><th>Wallet</th><th>Status</th></tr></thead><tbody>' +
        WITHDRAWALS.map(function (w) {
          var badgeCls = w.status === 'complete' ? 'complete' : w.status === 'pending' ? 'pending' : w.status === 'processing' ? 'processing' : w.status === 'cancelled' ? 'cancelled' : 'expired';
          var row = '<tr><td class="text-muted-a">' + w.date + '</td><td class="text-danger fw-7">' + fmtUSD(w.amount) + '</td><td>' + w.method + '</td><td class="mono" style="font-size:11px;color:var(--text-muted);">' + w.wallet + '</td><td><span class="status-badge ' + badgeCls + '">' + w.status.charAt(0).toUpperCase() + w.status.slice(1) + '</span></td></tr>';
          return row;
        }).join('') + '</tbody></table></div>' +
        WITHDRAWALS.filter(function (w) { return w.status === 'rejected'; }).map(function (w) {
          return '<div class="wd-reason-box" style="margin-top:10px;"><div class="wd-reason-label">Rejected ' + w.rejectedDate + ' &#xB7; By: ' + w.rejectedBy + '</div><div class="wd-reason-text">' + w.reason + '</div></div>';
        }).join('');
    }

    /* ── TRANSACTIONS (Requirement 7 — full categories, search/filter) ── */
    var txFilter = 'all', txSearch = '';
    function renderTransactions() {
      var tbody = qs('#txTable tbody'); if (!tbody) return;
      var rows = TRANSACTIONS.filter(function (t) {
        var matchFilter = txFilter === 'all' || t.type === txFilter;
        var matchSearch = !txSearch || t.label.toLowerCase().indexOf(txSearch) !== -1 || t.details.toLowerCase().indexOf(txSearch) !== -1 || t.id.toLowerCase().indexOf(txSearch) !== -1;
        return matchFilter && matchSearch;
      });
      tbody.innerHTML = rows.map(function (t) {
        var isNeg = t.amount < 0; var badgeCls = t.status === 'active' ? 'active' : t.status === 'complete' ? 'complete' : t.status === 'pending' ? 'pending' : 'expired';
        return '<tr><td class="text-muted-a">' + t.date + '</td><td class="mono text-cyan" style="font-size:11px;">' + t.id + '</td><td>' + t.label + '</td><td class="' + (isNeg ? 'text-danger' : 'text-accent') + ' fw-7">' + (isNeg ? '-' : '+') + fmtUSD(Math.abs(t.amount)) + '</td><td>' + t.wallet + '</td><td class="text-muted-a">' + t.details + '</td><td><span class="status-badge ' + badgeCls + '">' + t.status.charAt(0).toUpperCase() + t.status.slice(1) + '</span></td></tr>';
      }).join('') || '<tr><td colspan="7" class="text-muted-a" style="text-align:center;padding:24px;">No transactions match your filters.</td></tr>';
    }

    /* ── RECENT ACTIVITY (overview widget) ── */
    function renderRecentActivity() {
      var tbody = qs('#recentActivityTable tbody'); if (!tbody) return;
      tbody.innerHTML = TRANSACTIONS.slice(0, 3).map(function (t) {
        var isNeg = t.amount < 0; var icon = t.type === 'deposit' ? 'la-arrow-down' : t.type === 'withdrawal' ? 'la-arrow-up' : t.type === 'bonus' ? 'la-gift' : t.type === 'manual' ? 'la-sliders-h' : 'la-percentage';
        var badgeCls = t.status === 'active' ? 'active' : t.status === 'complete' ? 'complete' : t.status === 'pending' ? 'pending' : 'expired';
        return '<tr><td><i class="las ' + icon + '" style="color:var(--accent);margin-right:5px;"></i>' + t.label + '</td><td class="' + (isNeg ? 'text-danger' : 'text-accent') + ' fw-7">' + (isNeg ? '-' : '+') + fmtUSD(Math.abs(t.amount)) + '</td><td class="text-muted-a">' + t.date + '</td><td><span class="status-badge ' + badgeCls + '">' + t.status.charAt(0).toUpperCase() + t.status.slice(1) + '</span></td></tr>';
      }).join('');
    }

    /* ── BONUS HISTORY (Requirement 8) ── */
    function renderBonusHistory() {
      var tbody = qs('#bonusHistoryTable tbody'); if (!tbody) return;
      tbody.innerHTML = BONUS_HISTORY.map(function (b) {
        return '<tr><td class="fw-7">' + b.title + '</td><td class="text-muted-a" style="text-transform:capitalize;">' + b.category + '</td><td class="text-accent fw-7">+' + fmtUSD(b.amount) + '</td><td class="mono text-cyan" style="font-size:11px;">' + b.ref + '</td><td class="text-muted-a">' + b.date + '</td><td><span class="status-badge complete">' + b.status.charAt(0).toUpperCase() + b.status.slice(1) + '</span></td></tr>';
      }).join('');
    }

    /* ── ANNOUNCEMENTS (Requirement 16) ── */
    function renderAnnouncements() {
      var list = qs('#announceList'); if (!list) return;
      list.innerHTML = ANNOUNCEMENTS.map(function (a) {
        return '<div class="announce-card ' + a.type + '"><div class="announce-icon"><i class="las ' + a.icon + '"></i></div><div><div class="announce-title">' + a.title + '</div><div class="announce-msg">' + a.msg + '</div><div class="announce-time">' + a.time + '</div></div></div>';
      }).join('');
      var slot = qs('#overviewAnnounceSlot');
      if (slot && ANNOUNCEMENTS.length) { var a = ANNOUNCEMENTS[0]; slot.innerHTML = '<div class="announce-card ' + a.type + '" style="margin-bottom:16px;"><div class="announce-icon"><i class="las ' + a.icon + '"></i></div><div><div class="announce-title">' + a.title + '</div><div class="announce-msg">' + a.msg + '</div></div></div>'; }
    }

    /* ── ACTIVITY HISTORY (Requirement 15) ── */
    var actFilter = 'all';
    function renderActivity() {
      var list = qs('#activityList'); if (!list) return;
      var rows = ACTIVITY_LOG.filter(function (a) { return actFilter === 'all' || a.cat === actFilter; });
      list.innerHTML = rows.map(function (a) {
        return '<div class="notif-item" style="cursor:default;"><div class="notif-icon" style="background:rgba(0,0,0,0.15);color:var(--' + a.color + ');"><i class="las ' + a.icon + '"></i></div><div class="notif-body"><div class="notif-title">' + a.action + '</div><div class="notif-time">' + a.meta + '</div></div></div>';
      }).join('') || '<p class="text-muted-a" style="text-align:center;padding:20px;">No activity in this category.</p>';
    }

    /* ── AVAILABLE PLANS GRID ── */
    function renderAvailablePlans() {
      var grid = qs('#availablePlansGrid'); if (!grid) return;
      grid.innerHTML = AVAILABLE_PLANS.map(function (p) {
        var btnCls = p.cls === 'unlimited' ? 'success' : 'primary';
        return '<div class="col-lg-3 col-sm-6 mb-3"><div class="plan-card ' + p.cls + '"><div class="plan-badge ' + p.cls + '">' + p.label + '</div><div class="plan-name">' + p.name + '</div><div class="plan-rate">' + p.rate + '</div><div class="plan-rate-sub">' + p.sub + '</div><div class="plan-detail mt-2"><span class="plan-detail-label">Total Profit</span><span class="plan-detail-val text-accent">' + p.profit + '</span></div><button class="btn-ats ' + btnCls + ' full mt-3" type="button" data-nav="deposit">Invest Now</button></div></div>';
      }).join('');
      qsa('#availablePlansGrid [data-nav]').forEach(function (btn) { on(btn, 'click', function () { showSection(this.dataset.nav); }); });
    }

    /* ── ACTIVE INVESTMENTS TABLE (search/sort) ── */
    var invSearch = '', invSort = 'date';
    function renderActiveInvestments() {
      var tbody = qs('#activeInvTable tbody'); if (!tbody) return;
      var rows = ACTIVE_INVESTMENTS.filter(function (i) { return !invSearch || i.id.toLowerCase().indexOf(invSearch) !== -1 || i.plan.toLowerCase().indexOf(invSearch) !== -1; });
      if (invSort === 'amount') rows = rows.slice().sort(function (a, b) { return b.amount - a.amount; });
      else if (invSort === 'roi') rows = rows.slice().sort(function (a, b) { return parseFloat(b.roi) - parseFloat(a.roi); });
      tbody.innerHTML = rows.map(function (i) {
        return '<tr><td class="text-muted-a mono">' + i.id + '</td><td class="fw-7">' + i.plan + '</td><td class="fw-7">' + fmtUSD(i.amount) + '</td><td class="text-accent fw-7">' + fmtUSD(i.profit) + '</td><td class="text-accent">' + i.roi + '</td><td style="min-width:110px;"><div class="progress-bar-track"><div class="progress-bar-fill" style="width:' + i.progress + '%;' + (i.color ? 'background:' + i.color + ';' : '') + '"></div></div></td><td class="text-muted-a">' + i.started + '</td><td class="text-muted-a">' + i.maturity + '</td><td class="text-cyan">' + i.aiConf + '</td><td><span class="status-badge active">Active</span></td></tr>';
      }).join('') || '<tr><td colspan="10" class="text-muted-a" style="text-align:center;padding:24px;">No investments match your search.</td></tr>';
    }

    /* ── FAQ ── */
    function renderFAQ() {
      var list = qs('#faqList'); if (!list) return;
      list.innerHTML = FAQ_ITEMS.map(function (f, i) {
        return '<div class="faq-item" data-faq="' + i + '"><div class="faq-q">' + f.q + '<i class="las la-chevron-down"></i></div><div class="faq-a">' + f.a + '</div></div>';
      }).join('');
      qsa('.faq-q', list).forEach(function (q) { on(q, 'click', function () { this.parentElement.classList.toggle('open'); }); });
    }

    /* ── DEPOSIT/COPY/UPLOAD ── */
    function copyToClipboard(text, msg) { if (navigator.clipboard && navigator.clipboard.writeText) { navigator.clipboard.writeText(text).then(function () { showToast(msg || 'Copied!', 'success'); }).catch(function () { fallbackCopy(text, msg); }); } else fallbackCopy(text, msg); }
    function fallbackCopy(text, msg) { var ta = document.createElement('textarea'); ta.value = text; ta.style.cssText = 'position:fixed;opacity:0;top:0;left:0;'; document.body.appendChild(ta); ta.select(); try { document.execCommand('copy'); showToast(msg || 'Copied!', 'success'); } catch (e) { showToast('Copy failed', 'error'); } document.body.removeChild(ta); }

    /* ── IO ── */
    function triggerIO() {
      if (!('IntersectionObserver' in window)) { qsa('[data-animate]').forEach(function (el) { el.classList.add('animated'); }); qsa('.progress-bar-fill[data-width]').forEach(function (el) { el.style.width = el.dataset.width + '%'; }); return; }
      var obs = new IntersectionObserver(function (entries) { entries.forEach(function (e) { if (e.isIntersecting) { e.target.classList.add('animated'); obs.unobserve(e.target); } }); }, { threshold: 0.07, rootMargin: '0px 0px -20px 0px' });
      qsa('[data-animate]:not(.animated)').forEach(function (el) { obs.observe(el); });
      var pObs = new IntersectionObserver(function (entries) { entries.forEach(function (e) { if (e.isIntersecting) { var f = e.target; if (f.dataset.width) f.style.width = f.dataset.width + '%'; pObs.unobserve(f); } }); }, { threshold: 0.2 });
      qsa('.progress-bar-fill[data-width]').forEach(function (f) { pObs.observe(f); });
    }

    function setGreeting() { var now = new Date(), h = now.getHours(), greet = h < 12 ? 'Good Morning' : h < 17 ? 'Good Afternoon' : 'Good Evening'; var gEl = qs('#greetingText'), dEl = qs('#greetingDate'); if (gEl) gEl.textContent = greet + ', ' + ATS_CONFIG.traderName; if (dEl) dEl.textContent = now.toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }); }

    function startRewardCountdown() { function upd() { var el = qs('#rewardTimer'); if (!el) return; var now = new Date(), mid = new Date(now.getFullYear(), now.getMonth(), now.getDate() + 1), diff = mid - now; var parts = [Math.floor(diff / 3600000), Math.floor((diff % 3600000) / 60000), Math.floor((diff % 60000) / 1000)]; el.textContent = parts.map(function (n) { return String(n).padStart(2, '0'); }).join(':'); } upd(); setInterval(upd, 1000); }

    function switchInvestTab(name) { ['active', 'expired', 'plans'].forEach(function (n) { var el = qs('#invest-tab-' + n); if (el) el.style.display = n === name ? 'block' : 'none'; }); qsa('[data-invest-tab]').forEach(function (b) { b.classList.toggle('active', b.dataset.investTab === name); }); }
    function switchTabGroup(btn) { var group = btn.closest('.ats-tabs'); if (!group) return; qsa('.ats-tab-btn', group).forEach(function (b) { b.classList.remove('active'); }); btn.classList.add('active'); }

    function initSwipe() { var sx = 0, sy = 0; document.addEventListener('touchstart', function (e) { sx = e.touches[0].clientX; sy = e.touches[0].clientY; }, { passive: true }); document.addEventListener('touchend', function (e) { var dx = e.changedTouches[0].clientX - sx, dy = Math.abs(e.changedTouches[0].clientY - sy); if (dy > 60) return; if (state.sidebarOpen && dx < -60) closeSidebar(); if (state.notifOpen && dx > 60) closeNotifDrawer(); }, { passive: true }); }

    function attachListeners() {
      on(DOM.sidebarToggleBtn, 'click', function (e) { e.stopPropagation(); if (state.sidebarOpen) closeSidebar(); else openSidebar(); });
      on(DOM.sidebarCloseBtn, 'click', closeSidebar); on(DOM.sidebarOverlay, 'click', closeSidebar);
      qsa('.sidebar-nav li a').forEach(function (link) { on(link, 'click', function (e) { var s = this.dataset.section; if (s) { e.preventDefault(); showSection(s); } if (window.innerWidth <= 820) closeSidebar(); }); });
      on(DOM.notifCloseBtn, 'click', closeNotifDrawer); on(DOM.notifOverlay, 'click', closeNotifDrawer);
      on(DOM.notifToggleBtn, 'click', function () { if (state.notifOpen) closeNotifDrawer(); else openNotifDrawer(); });
      on(qs('#markAllReadBtn'), 'click', markAllRead); on(qs('#markAllInPageBtn'), 'click', markAllRead); on(qs('#clearNotifsBtn'), 'click', clearAllNotifs);
      on(qs('#settingsShortcutBtn'), 'click', function () { showSection('settings'); });
      on(qs('#fabBtn'), 'click', function (e) { e.stopPropagation(); toggleFab(); });
      document.addEventListener('click', function (e) { if (state.fabOpen) { var fab = qs('#atsFab'); if (fab && !fab.contains(e.target)) { state.fabOpen = false; fab.classList.remove('open'); qs('#fabBtn').setAttribute('aria-expanded', 'false'); } } });
      qsa('.fab-menu-item[data-nav]').forEach(function (btn) { on(btn, 'click', function () { showSection(this.dataset.nav); state.fabOpen = false; qs('#atsFab').classList.remove('open'); }); });
      qsa('[data-nav]').forEach(function (btn) { on(btn, 'click', function () { if (this.dataset.nav) showSection(this.dataset.nav); }); });
      qsa('[data-invest-tab]').forEach(function (btn) { on(btn, 'click', function () { switchInvestTab(this.dataset.investTab); }); });
      qsa('.ats-tabs .ats-tab-btn').forEach(function (btn) { on(btn, 'click', function () { switchTabGroup(this); }); });
      on(qs('#calcRunBtn'), 'click', runCalculator);

      /* Deposit */
      on(qs('#copyAddrBtn'), 'click', function () { var a = qs('#depAddrText'); if (a && state.selectedPM) copyToClipboard(a.textContent, 'Wallet address copied!'); });
      on(qs('#uploadZone'), 'click', function () { var i = qs('#depFileInput'); if (i) i.click(); });
      on(qs('#depFileInput'), 'change', function (e) { var file = e.target.files[0]; if (!file) return; var r = new FileReader(); r.onload = function (ev) { var w = qs('#depPreview'), img = qs('#depPreviewImg'); if (w) w.style.display = 'block'; if (img) img.src = ev.target.result; }; r.readAsDataURL(file); });
      on(qs('#submitDepBtn'), 'click', function () { var a = parseFloat((qs('#depAmtInput') || {}).value); var minDep = state.selectedPM ? state.selectedPM.minDep : 10; if (!a || a < minDep) { showToast('Minimum deposit is ' + fmtUSD(minDep), 'error'); return; } showToast('Deposit submitted — pending approval!', 'success'); });

      /* Withdraw */
      on(qs('#wdCryptoSel'), 'change', function () { var map = { 'Bitcoin (BTC)': 'Bitcoin Mainnet', 'Ethereum (ETH)': 'Ethereum Mainnet', 'USDT — TRC20': 'TRON TRC20', 'Solana (SOL)': 'Solana Mainnet' }; var net = qs('#wdNetworkText'); if (net) net.value = map[this.value] || ''; });
      on(qs('#submitWdBtn'), 'click', function () { showToast('Withdrawal request submitted!', 'success'); });

      /* Transactions filter/search */
      qsa('#txTabs .ats-tab-btn').forEach(function (btn) { on(btn, 'click', function () { txFilter = this.dataset.txFilter; renderTransactions(); }); });
      on(qs('#txSearchInput'), 'input', function () { txSearch = this.value.toLowerCase(); renderTransactions(); });
      on(qs('#expTxCsv'), 'click', function () { showToast('Exporting transactions as CSV…', 'success'); });
      on(qs('#expTxPdf'), 'click', function () { showToast('Generating transactions PDF…', 'success'); });

      /* Investments search/sort/export */
      on(qs('#invSearchInput'), 'input', function () { invSearch = this.value.toLowerCase(); renderActiveInvestments(); });
      on(qs('#invSortSel'), 'change', function () { invSort = this.value; renderActiveInvestments(); });
      on(qs('#expInvCsv'), 'click', function () {
        showToast('
      Exporting investments as CSV…', 'success'); });
      on(qs('#expInvPdf'), 'click', function () { showToast('Generating investments PDF…', 'success'); });

        /* Activity filter */
        qsa('#actTabs .ats-tab-btn').forEach(function (btn) { on(btn, 'click', function () { actFilter = this.dataset.actFilter; renderActivity(); }); });

        /* Support */
        on(qs('#submitTicketBtn'), 'click', function () { var s = (qs('#tickSubject') || {}).value, m = (qs('#tickMsg') || {}).value; if (!s || !s.trim()) { showToast('Please enter a subject', 'error'); return; } if (!m || !m.trim()) { showToast('Please enter a message', 'error'); return; } showToast('Support ticket created!', 'success'); });
        on(qs('#liveChatBtn'), 'click', function () { window.open(ATS_CONFIG.supportLink, '_blank', 'noopener'); });

        /* Verification */
        on(qs('#kycUploadZone'), 'click', function () { var i = qs('#kycFileInput'); if (i) i.click(); });
        on(qs('#kycFileInput'), 'change', function (e) { if (e.target.files[0]) showToast('Document selected — click Submit for Review', 'info'); });
        on(qs('#kycSubmitBtn'), 'click', function () { showToast('Document submitted for review!', 'success'); });

        /* Settings */
        on(qs('#saveSettingsBtn'), 'click', function () { showToast('Profile saved!', 'success'); });
        on(qs('#changePassBtn'), 'click', function () { showToast('Password updated!', 'success'); });
        on(qs('#avatarRing'), 'click', function () { var i = qs('#avatarFileInput'); if (i) i.click(); });
        on(qs('#avatarFileInput'), 'change', function (e) { var file = e.target.files[0]; if (!file) return; var r = new FileReader(); r.onload = function (ev) { var d = qs('#avatarDisplay'); if (d) d.innerHTML = '<img src="' + ev.target.result + '" alt="Avatar" style="width:100%;height:100%;object-fit:cover;">'; showToast('Profile photo updated!', 'success'); }; r.readAsDataURL(file); });

        /* Rewards */
        on(qs('#claimRewardBtn'), 'click', function () { showToast('Daily reward of $5.00 claimed!', 'success', 'Reward Claimed'); });

        /* Referral */
        on(qs('#copyRefBtn'), 'click', function () { var l = (qs('#refLinkText') || {}).textContent; if (l) copyToClipboard(l, 'Referral link copied!'); });
        on(qs('#shareTelegramBtn'), 'click', function () { window.open('https://t.me/share/url?url=' + encodeURIComponent((qs('#refLinkText') || {}).textContent || ''), '_blank', 'noopener'); });
        on(qs('#shareTwitterBtn'), 'click', function () { window.open('https://twitter.com/intent/tweet?url=' + encodeURIComponent((qs('#refLinkText') || {}).textContent || ''), '_blank', 'noopener'); });
        on(qs('#shareWhatsappBtn'), 'click', function () { window.open('https://wa.me/?text=' + encodeURIComponent('Join ATS! ' + ((qs('#refLinkText') || {}).textContent || '')), '_blank', 'noopener'); });

        on(qs('#backTopBtn'), 'click', function () { window.scrollTo({ top: 0, behavior: 'smooth' }); });
        window.addEventListener('scroll', function () { var b = qs('#backTopBtn'); if (b) b.classList.toggle('show', window.scrollY > 400); }, { passive: true });
        var resizeTimer; window.addEventListener('resize', function () { clearTimeout(resizeTimer); resizeTimer = setTimeout(function () { if (window.innerWidth > 820 && state.sidebarOpen) closeSidebar(); }, 150); }, { passive: true });
        document.addEventListener('keydown', function (e) { if (e.key === 'Escape') { if (state.notifOpen) closeNotifDrawer(); else if (state.sidebarOpen) closeSidebar(); else if (state.fabOpen) { state.fabOpen = false; qs('#atsFab').classList.remove('open'); qs('#fabBtn').setAttribute('aria-expanded', 'false'); } } });
      }

    function init() {
          DOM.sidebar = qs('#dashSidebar'); DOM.sidebarOverlay = qs('#sidebarOverlay'); DOM.sidebarToggleBtn = qs('#sidebarToggleBtn'); DOM.sidebarCloseBtn = qs('#sidebarCloseBtn');
          DOM.notifDrawer = qs('#notifDrawer'); DOM.notifOverlay = qs('#notifOverlay'); DOM.notifCloseBtn = qs('#notifCloseBtn'); DOM.notifToggleBtn = qs('#notifToggleBtn'); DOM.notifList = qs('#notifList');

          var fabLink = qs('#fabTelegramLink'); if (fabLink) fabLink.href = ATS_CONFIG.supportLink;

          checkIconFont();
          setGreeting();
          attachListeners();
          initSwipe();
          initTicker();
          renderPMSelector();
          renderDepositHistory();
          renderWithdrawalHistory();
          renderTransactions();
          renderRecentActivity();
          renderBonusHistory();
          renderAnnouncements();
          renderActivity();
          renderAvailablePlans();
          renderActiveInvestments();
          renderFAQ();
          renderNotifList();
          startRewardCountdown();
          triggerIO();
          updateNotifBadge();

          /* Referral QR */
          var refQR = qs('#refQRWrap'); if (refQR && typeof QRCode !== 'undefined') { try { new QRCode(refQR, { text: 'https://ats-trading.io/register?ref=TRADER123', width: 130, height: 130, colorDark: '#000', colorLight: '#fff', correctLevel: QRCode.CorrectLevel.H }); } catch (e) { } }

          setTimeout(initStatCounters, 200);
          setTimeout(initMiniCharts, 500);
          setTimeout(function () { initGauge(94.7); }, 700);
          setTimeout(function () { showToast('AI Engine running at 94.7% confidence — all bots active.', 'success', 'Dashboard Ready'); }, 1800);
        }

    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init); else init();
  </script>

</body>

</html>