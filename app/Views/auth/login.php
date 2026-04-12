<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= Session::csrfToken() ?>">
    <title>Login — <?= APP_NAME ?></title>
    <meta name="description" content="Acesse o painel administrativo do MoveFlow">
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/variables.css">
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/base.css">
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/components.css">
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/pages.css">
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/responsive.css">
</head>
<body>
<div class="login-page">
    <div class="login-container">
        <div class="login-card">
            <div class="login-brand">
                <div class="login-brand-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/></svg>
                </div>
                <div class="login-brand-text">Move<span>Flow</span></div>
            </div>

            <div class="login-title">
                <h2>Bem-vindo de volta</h2>
                <p>Acesse o painel administrativo</p>
            </div>

            <?php if (Session::hasFlash('error')): ?>
                <div class="login-alert error">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                    <?= htmlspecialchars(Session::getFlash('error')) ?>
                </div>
            <?php endif; ?>

            <?php if (Session::hasFlash('success')): ?>
                <div class="login-alert success">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    <?= htmlspecialchars(Session::getFlash('success')) ?>
                </div>
            <?php endif; ?>

            <form class="login-form" id="login-form" method="POST" action="<?= APP_URL ?>/login">
                <?= Session::csrfField() ?>

                <div class="form-group">
                    <label class="form-label" for="email">E-mail</label>
                    <input type="email" class="form-control" id="email" name="email"
                           placeholder="admin@moveflow.com"
                           value="<?= htmlspecialchars(Session::get('old_email', '')) ?>"
                           required autocomplete="email">
                    <?php Session::remove('old_email'); ?>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Senha</label>
                    <input type="password" class="form-control" id="password" name="password"
                           placeholder="••••••••" required autocomplete="current-password">
                </div>

                <button type="submit" class="btn btn-primary btn-lg" style="width:100%;margin-top:var(--space-2)">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                    Entrar
                </button>
            </form>

            <div class="login-footer">
                <p><?= APP_NAME ?> v<?= APP_VERSION ?></p>
            </div>
        </div>
    </div>
</div>

<script src="<?= APP_URL ?>/assets/js/utils.js"></script>
<script src="<?= APP_URL ?>/assets/js/auth.js"></script>
</body>
</html>
