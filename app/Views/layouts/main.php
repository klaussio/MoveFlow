<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="base-url" content="<?= APP_URL ?>">
    <meta name="csrf-token" content="<?= Session::csrfToken() ?>">
    <title><?= htmlspecialchars($pageTitle ?? 'Dashboard') ?> — <?= APP_NAME ?></title>
    <meta name="description" content="MoveFlow — Sistema de Gestão de Corridas Urbanas">
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/variables.css">
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/base.css">
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/layout.css">
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/components.css">
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/components2.css">
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/pages.css">
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/dark-theme.css">
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/responsive.css">
    <!-- Theme script loaded early to prevent flash -->
    <script src="<?= APP_URL ?>/assets/js/theme.js"></script>
</head>
<body>
<?php $currentUrl = isset($_GET['url']) ? trim($_GET['url'], '/') : ''; ?>
<?php $user = Session::user(); ?>

<div class="app">
    <!-- Sidebar Overlay (mobile) -->
    <div class="sidebar-overlay" id="sidebar-overlay"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div class="sidebar-brand-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/></svg>
            </div>
            <div class="sidebar-brand-text">Move<span>Flow</span></div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-label">Principal</div>
            <div class="nav-item">
                <a href="<?= APP_URL ?>/dashboard" class="nav-link <?= ($currentUrl === '' || $currentUrl === 'dashboard') ? 'active' : '' ?>">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                    <span class="nav-link-text">Dashboard</span>
                </a>
            </div>

            <div class="nav-label">Gerenciamento</div>
            <div class="nav-item">
                <a href="<?= APP_URL ?>/drivers" class="nav-link <?= str_starts_with($currentUrl, 'drivers') ? 'active' : '' ?>">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-2-2.2-3.3C13 5.6 12 5 10.8 5H5.6c-1.1 0-2.2.6-2.8 1.5L1 9.7C.4 10.6 0 11.5 0 12.5V16c0 .6.4 1 1 1h1"/><circle cx="7" cy="17" r="2"/><circle cx="17" cy="17" r="2"/></svg>
                    <span class="nav-link-text">Motoristas</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="<?= APP_URL ?>/passengers" class="nav-link <?= str_starts_with($currentUrl, 'passengers') ? 'active' : '' ?>">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    <span class="nav-link-text">Passageiros</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="<?= APP_URL ?>/rides" class="nav-link <?= str_starts_with($currentUrl, 'rides') ? 'active' : '' ?>">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    <span class="nav-link-text">Corridas</span>
                </a>
            </div>
        </nav>

        <div class="sidebar-footer">
            <!-- Theme toggle in sidebar -->
            <button class="sidebar-theme-toggle" id="sidebar-theme-btn" title="Alternar tema">
                <svg class="icon-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
                <svg class="icon-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
                <span class="theme-toggle-text">Modo Escuro</span>
            </button>

            <a href="<?= APP_URL ?>/logout" class="sidebar-user" title="Sair do sistema">
                <div class="sidebar-avatar"><?= strtoupper(substr($user['name'] ?? 'A', 0, 1)) ?></div>
                <div class="sidebar-user-info">
                    <div class="sidebar-user-name"><?= htmlspecialchars($user['name'] ?? 'Admin') ?></div>
                    <div class="sidebar-user-role">Administrador</div>
                </div>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:var(--gray-500);flex-shrink:0"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="main">
        <header class="topbar">
            <div class="topbar-left">
                <button class="topbar-toggle" id="sidebar-toggle" aria-label="Toggle sidebar">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
                </button>
                <div>
                    <div class="topbar-title"><?= htmlspecialchars($pageTitle ?? 'Dashboard') ?></div>
                </div>
            </div>
            <div class="topbar-right">
                <!-- Theme toggle in topbar -->
                <button class="theme-toggle" id="topbar-theme-btn" title="Alternar tema" aria-label="Alternar tema escuro/claro">
                    <svg class="icon-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
                    <svg class="icon-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
                </button>
                <span style="font-size:var(--text-sm);color:var(--gray-500)"><?= date('d/m/Y H:i') ?></span>
            </div>
        </header>

        <main class="content">
            <?= $content ?>
        </main>
    </div>
</div>

<!-- Toast container for flash messages -->
<div class="toast-container">
    <?php foreach (Session::getAllFlash() as $type => $message): ?>
    <div class="toast toast-<?= $type ?>">
        <span class="toast-icon">
            <?php if ($type === 'success'): ?>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            <?php elseif ($type === 'error'): ?>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
            <?php else: ?>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
            <?php endif; ?>
        </span>
        <div class="toast-content">
            <div class="toast-message"><?= htmlspecialchars($message) ?></div>
        </div>
        <button class="toast-close">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
    </div>
    <?php endforeach; ?>
</div>

<script src="<?= APP_URL ?>/assets/js/utils.js"></script>
<script src="<?= APP_URL ?>/assets/js/api.js"></script>
<script src="<?= APP_URL ?>/assets/js/app.js"></script>
<script src="<?= APP_URL ?>/assets/js/dashboard.js"></script>
<script src="<?= APP_URL ?>/assets/js/drivers.js"></script>
<script src="<?= APP_URL ?>/assets/js/passengers.js"></script>
<script src="<?= APP_URL ?>/assets/js/rides.js"></script>
</body>
</html>
