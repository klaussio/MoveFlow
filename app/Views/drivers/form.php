<?php
/** @var array|null $driver @var bool $editing */
$old = Session::get('old_input', []);
Session::remove('old_input');
$v = fn($field) => htmlspecialchars($driver[$field] ?? $old[$field] ?? '');
$action = $editing ? APP_URL . "/drivers/update/{$driver['id']}" : APP_URL . '/drivers/store';
?>

<div class="page-header">
    <div class="page-header-info">
        <h1><?= $editing ? 'Editar Motorista' : 'Novo Motorista' ?></h1>
        <p><?= $editing ? 'Atualize os dados do motorista' : 'Preencha os dados para cadastrar um novo motorista' ?></p>
    </div>
    <div class="page-header-actions">
        <a href="<?= APP_URL ?>/drivers" class="btn btn-outline">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
            Voltar
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form id="driver-form" method="POST" action="<?= $action ?>">
            <?= Session::csrfField() ?>

            <h4 class="section-heading">Dados Pessoais</h4>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="name">Nome Completo <span class="required">*</span></label>
                    <input type="text" class="form-control" id="name" name="name" value="<?= $v('name') ?>" required placeholder="Nome do motorista">
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

            <div class="form-group">
                <label class="form-label" for="cnh">CNH <span class="required">*</span></label>
                <input type="text" class="form-control" id="cnh" name="cnh" value="<?= $v('cnh') ?>" required placeholder="Número da CNH" style="max-width:400px">
            </div>

            <h4 class="section-heading mt">Dados do Veículo</h4>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="vehicle_model">Modelo <span class="required">*</span></label>
                    <input type="text" class="form-control" id="vehicle_model" name="vehicle_model" value="<?= $v('vehicle_model') ?>" required placeholder="Ex: Toyota Corolla">
                </div>
                <div class="form-group">
                    <label class="form-label" for="vehicle_plate">Placa <span class="required">*</span></label>
                    <input type="text" class="form-control" id="vehicle_plate" name="vehicle_plate" value="<?= $v('vehicle_plate') ?>" required placeholder="ABC-1D23" data-mask="plate">
                </div>
                <div class="form-group">
                    <label class="form-label" for="vehicle_color">Cor <span class="required">*</span></label>
                    <input type="text" class="form-control" id="vehicle_color" name="vehicle_color" value="<?= $v('vehicle_color') ?>" required placeholder="Ex: Preto">
                </div>
            </div>

            <div style="display:flex;gap:var(--space-3);margin-top:var(--space-6)">
                <button type="submit" class="btn btn-primary">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    <?= $editing ? 'Atualizar' : 'Cadastrar' ?>
                </button>
                <a href="<?= APP_URL ?>/drivers" class="btn btn-outline">Cancelar</a>
            </div>
        </form>
    </div>
</div>
