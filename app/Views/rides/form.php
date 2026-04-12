<?php
/** @var array $passengers @var array $drivers */
$old = Session::get('old_input', []);
Session::remove('old_input');
$v = fn($field) => htmlspecialchars($old[$field] ?? '');
?>

<div class="page-header">
    <div class="page-header-info">
        <h1>Nova Corrida</h1>
        <p>Solicite uma nova corrida preenchendo os dados abaixo</p>
    </div>
    <div class="page-header-actions">
        <a href="<?= APP_URL ?>/rides" class="btn btn-outline">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
            Voltar
        </a>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 340px;gap:var(--space-5);align-items:start">
    <div class="card">
        <div class="card-body">
            <form id="ride-form" method="POST" action="<?= APP_URL ?>/rides/store">
                <?= Session::csrfField() ?>

                <div class="form-group">
                    <label class="form-label" for="passenger_id">Passageiro <span class="required">*</span></label>
                    <select class="form-control" id="passenger_id" name="passenger_id" required>
                        <option value="">Selecione um passageiro</option>
                        <?php foreach ($passengers as $p): ?>
                            <option value="<?= $p['id'] ?>" <?= ($old['passenger_id'] ?? '') == $p['id'] ? 'selected' : '' ?>><?= htmlspecialchars($p['name']) ?> — <?= htmlspecialchars($p['phone']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="driver_id">Motorista (opcional — será atribuído automaticamente se não selecionado)</label>
                    <select class="form-control" id="driver_id" name="driver_id">
                        <option value="">Atribuição automática</option>
                        <?php foreach ($drivers as $d): ?>
                            <option value="<?= $d['id'] ?>" <?= ($old['driver_id'] ?? '') == $d['id'] ? 'selected' : '' ?>><?= htmlspecialchars($d['name']) ?> — <?= htmlspecialchars($d['vehicle_model']) ?> (<?= htmlspecialchars($d['vehicle_plate']) ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <h4 style="margin:var(--space-4) 0;color:var(--gray-700)">Rota</h4>
                <div class="form-group">
                    <label class="form-label" for="origin_address">Endereço de Origem <span class="required">*</span></label>
                    <input type="text" class="form-control" id="origin_address" name="origin_address" value="<?= $v('origin_address') ?>" required placeholder="Ex: Av. Paulista, 1000 - São Paulo">
                </div>

                <div class="form-group">
                    <label class="form-label" for="destination_address">Endereço de Destino <span class="required">*</span></label>
                    <input type="text" class="form-control" id="destination_address" name="destination_address" value="<?= $v('destination_address') ?>" required placeholder="Ex: Rua Augusta, 500 - São Paulo">
                </div>

                <input type="hidden" name="origin_lat" value="0">
                <input type="hidden" name="origin_lng" value="0">
                <input type="hidden" name="destination_lat" value="0">
                <input type="hidden" name="destination_lng" value="0">

                <h4 style="margin:var(--space-4) 0;color:var(--gray-700)">Estimativa</h4>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="distance_km">Distância (km) <span class="required">*</span></label>
                        <input type="number" class="form-control" id="distance_km" name="distance_km" value="<?= $v('distance_km') ?>" required min="0.1" step="0.1" placeholder="Ex: 8.5">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="estimated_duration_min">Duração Estimada (min) <span class="required">*</span></label>
                        <input type="number" class="form-control" id="estimated_duration_min" name="estimated_duration_min" value="<?= $v('estimated_duration_min') ?>" required min="1" placeholder="Ex: 20">
                    </div>
                </div>

                <div style="display:flex;gap:var(--space-3);margin-top:var(--space-6)">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        Solicitar Corrida
                    </button>
                    <a href="<?= APP_URL ?>/rides" class="btn btn-outline btn-lg">Cancelar</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Fare Preview Sidebar -->
    <div>
        <div class="fare-display" id="fare-preview" style="display:none"></div>
        <div class="card mt-4">
            <div class="card-body" style="font-size:var(--text-sm);color:var(--gray-500)">
                <h4 style="font-size:var(--text-sm);margin-bottom:var(--space-3);color:var(--gray-700)">Como a tarifa é calculada:</h4>
                <p style="margin-bottom:var(--space-2)"><strong>Base:</strong> R$ 5,00</p>
                <p style="margin-bottom:var(--space-2)"><strong>Por km:</strong> R$ 2,50</p>
                <p style="margin-bottom:0"><strong>Por min:</strong> R$ 0,40</p>
            </div>
        </div>
    </div>
</div>
