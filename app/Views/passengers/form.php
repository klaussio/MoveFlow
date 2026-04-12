<?php
/** @var array|null $passenger @var bool $editing */
$old = Session::get('old_input', []);
Session::remove('old_input');
$v = fn($field) => htmlspecialchars($passenger[$field] ?? $old[$field] ?? '');
$action = $editing ? APP_URL . "/passengers/update/{$passenger['id']}" : APP_URL . '/passengers/store';
?>

<div class="page-header">
    <div class="page-header-info">
        <h1><?= $editing ? 'Editar Passageiro' : 'Novo Passageiro' ?></h1>
        <p><?= $editing ? 'Atualize os dados do passageiro' : 'Preencha os dados para cadastrar um novo passageiro' ?></p>
    </div>
    <div class="page-header-actions">
        <a href="<?= APP_URL ?>/passengers" class="btn btn-outline">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
            Voltar
        </a>
    </div>
</div>

<div class="card" style="max-width:700px">
    <div class="card-body">
        <form id="passenger-form" method="POST" action="<?= $action ?>">
            <?= Session::csrfField() ?>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="name">Nome Completo <span class="required">*</span></label>
                    <input type="text" class="form-control" id="name" name="name" value="<?= $v('name') ?>" required placeholder="Nome do passageiro">
                </div>
                <div class="form-group">
                    <label class="form-label" for="email">E-mail <span class="required">*</span></label>
                    <input type="email" class="form-control" id="email" name="email" value="<?= $v('email') ?>" required placeholder="email@exemplo.com">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="phone">Telefone <span class="required">*</span></label>
                    <input type="text" class="form-control" id="phone" name="phone" value="<?= $v('phone') ?>" required placeholder="(00) 00000-0000" data-mask="phone">
                </div>
                <div class="form-group">
                    <label class="form-label" for="cpf">CPF <span class="required">*</span></label>
                    <input type="text" class="form-control" id="cpf" name="cpf" value="<?= $v('cpf') ?>" required placeholder="000.000.000-00" data-mask="cpf">
                </div>
            </div>

            <div style="display:flex;gap:var(--space-3);margin-top:var(--space-6)">
                <button type="submit" class="btn btn-primary">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    <?= $editing ? 'Atualizar' : 'Cadastrar' ?>
                </button>
                <a href="<?= APP_URL ?>/passengers" class="btn btn-outline">Cancelar</a>
            </div>
        </form>
    </div>
</div>
