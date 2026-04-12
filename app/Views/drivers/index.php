<?php /** @var array $drivers @var array $pagination @var string $search @var string $status */ ?>

<div class="page-header">
    <div class="page-header-info">
        <h1>Motoristas</h1>
        <p>Gerencie os motoristas cadastrados no sistema</p>
    </div>
    <div class="page-header-actions">
        <a href="<?= APP_URL ?>/drivers/create" class="btn btn-primary">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Novo Motorista
        </a>
    </div>
</div>

<div class="table-container" id="drivers-table">
    <div class="table-header">
        <h3><?= $pagination['total'] ?> motorista(s) encontrado(s)</h3>
        <div class="table-filters">
            <div class="form-search">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input type="text" class="form-control" id="driver-search" placeholder="Buscar motorista..." value="<?= htmlspecialchars($search) ?>">
            </div>
            <select class="form-control" id="driver-status-filter" style="width:auto">
                <option value="">Todos os Status</option>
                <?php foreach (DRIVER_STATUS_LABELS as $key => $label): ?>
                    <option value="<?= $key ?>" <?= $status === $key ? 'selected' : '' ?>><?= $label ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="table-wrapper">
        <table>
            <thead><tr>
                <th>Motorista</th><th>Contato</th><th>CPF</th><th>Veículo</th><th>Status</th><th>Avaliação</th><th>Ações</th>
            </tr></thead>
            <tbody>
            <?php if (empty($drivers)): ?>
                <tr><td colspan="7"><div class="empty-state"><div class="empty-state-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg></div><h4>Nenhum motorista encontrado</h4><p>Cadastre um novo motorista ou ajuste os filtros de busca.</p></div></td></tr>
            <?php else: foreach ($drivers as $d): ?>
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:var(--space-3)">
                            <div style="width:36px;height:36px;border-radius:var(--radius-full);background:linear-gradient(135deg,var(--primary-100),var(--accent-100));display:flex;align-items:center;justify-content:center;font-weight:var(--font-semibold);color:var(--primary-700);font-size:var(--text-sm)"><?= strtoupper(substr($d['name'], 0, 1)) ?></div>
                            <div><div style="font-weight:var(--font-medium);color:var(--gray-900)"><?= htmlspecialchars($d['name']) ?></div><div style="font-size:var(--text-xs);color:var(--gray-500)"><?= htmlspecialchars($d['email']) ?></div></div>
                        </div>
                    </td>
                    <td><?= htmlspecialchars($d['phone']) ?></td>
                    <td style="font-family:var(--font-mono);font-size:var(--text-xs)"><?= htmlspecialchars($d['cpf']) ?></td>
                    <td><div style="font-weight:var(--font-medium)"><?= htmlspecialchars($d['vehicle_model']) ?></div><div style="font-size:var(--text-xs);color:var(--gray-500)"><?= htmlspecialchars($d['vehicle_plate']) ?> · <?= htmlspecialchars($d['vehicle_color']) ?></div></td>
                    <td><span class="badge badge-dot driver-status-badge <?= DRIVER_STATUS_CLASSES[$d['status']] ?? '' ?>"><span><?= DRIVER_STATUS_LABELS[$d['status']] ?? $d['status'] ?></span></span></td>
                    <td><span style="color:var(--warning-500)">★</span> <?= number_format($d['rating'] ?? 5, 1) ?></td>
                    <td>
                        <div class="table-actions">
                            <button class="btn btn-ghost btn-icon btn-sm toggle-status-btn" data-id="<?= $d['id'] ?>" title="Alternar Status"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg></button>
                            <a href="<?= APP_URL ?>/drivers/edit/<?= $d['id'] ?>" class="btn btn-ghost btn-icon btn-sm" title="Editar"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></a>
                            <form method="POST" action="<?= APP_URL ?>/drivers/delete/<?= $d['id'] ?>" class="delete-form" data-name="<?= htmlspecialchars($d['name']) ?>" style="display:inline"><?= Session::csrfField() ?><button type="submit" class="btn btn-ghost btn-icon btn-sm" style="color:var(--danger-500)" title="Excluir"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button></form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>

    <?php if ($pagination['total_pages'] > 1): ?>
    <div class="table-footer">
        <div class="table-info">Mostrando <?= count($drivers) ?> de <?= $pagination['total'] ?> resultados</div>
        <div class="pagination">
            <?php if ($pagination['page'] > 1): ?><a href="<?= APP_URL ?>/drivers?page=<?= $pagination['page'] - 1 ?>&search=<?= urlencode($search) ?>&status=<?= urlencode($status) ?>">‹</a><?php else: ?><span class="disabled">‹</span><?php endif; ?>
            <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                <?php if ($i === $pagination['page']): ?><span class="active"><?= $i ?></span>
                <?php else: ?><a href="<?= APP_URL ?>/drivers?page=<?= $i ?>&search=<?= urlencode($search) ?>&status=<?= urlencode($status) ?>"><?= $i ?></a><?php endif; ?>
            <?php endfor; ?>
            <?php if ($pagination['page'] < $pagination['total_pages']): ?><a href="<?= APP_URL ?>/drivers?page=<?= $pagination['page'] + 1 ?>&search=<?= urlencode($search) ?>&status=<?= urlencode($status) ?>">›</a><?php else: ?><span class="disabled">›</span><?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
</div>
