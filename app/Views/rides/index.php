<?php /** @var array $rides @var array $pagination @var string $search @var string $status @var string $dateFrom @var string $dateTo */ ?>

<div class="page-header">
    <div class="page-header-info">
        <h1>Corridas</h1>
        <p>Gerencie todas as corridas do sistema</p>
    </div>
    <div class="page-header-actions">
        <a href="<?= APP_URL ?>/rides/create" class="btn btn-primary">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Nova Corrida
        </a>
    </div>
</div>

<div class="table-container" id="rides-table">
    <div class="table-header">
        <h3><?= $pagination['total'] ?> corrida(s)</h3>
        <div class="table-filters">
            <div class="form-search">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input type="text" class="form-control" id="ride-search" placeholder="Buscar..." value="<?= htmlspecialchars($search) ?>">
            </div>
            <select class="form-control" id="ride-status-filter" style="width:auto">
                <option value="">Todos os Status</option>
                <?php foreach (RIDE_STATUS_LABELS as $key => $label): ?>
                    <option value="<?= $key ?>" <?= $status === $key ? 'selected' : '' ?>><?= $label ?></option>
                <?php endforeach; ?>
            </select>
            <input type="date" class="form-control" id="ride-date-from" value="<?= htmlspecialchars($dateFrom) ?>" style="width:auto" title="Data inicial">
            <input type="date" class="form-control" id="ride-date-to" value="<?= htmlspecialchars($dateTo) ?>" style="width:auto" title="Data final">
        </div>
    </div>

    <div class="table-wrapper">
        <table>
            <thead><tr><th>ID</th><th>Passageiro</th><th>Motorista</th><th>Rota</th><th>Status</th><th>Distância</th><th>Tarifa</th><th>Data</th><th>Ações</th></tr></thead>
            <tbody>
            <?php if (empty($rides)): ?>
                <tr><td colspan="9"><div class="empty-state"><div class="empty-state-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div><h4>Nenhuma corrida encontrada</h4><p>Crie uma nova corrida ou ajuste os filtros.</p></div></td></tr>
            <?php else: foreach ($rides as $r): ?>
                <tr>
                    <td><a href="<?= APP_URL ?>/rides/show/<?= $r['id'] ?>" style="font-weight:var(--font-semibold)">#<?= $r['id'] ?></a></td>
                    <td><?= htmlspecialchars($r['passenger_name'] ?? '—') ?></td>
                    <td><?= $r['driver_name'] ? htmlspecialchars($r['driver_name']) : '<span class="text-muted">—</span>' ?></td>
                    <td style="max-width:200px"><div style="font-size:var(--text-xs);overflow:hidden;text-overflow:ellipsis;white-space:nowrap" title="<?= htmlspecialchars($r['origin_address']) ?>"><?= htmlspecialchars($r['origin_address']) ?></div><div style="font-size:var(--text-xs);color:var(--gray-400);overflow:hidden;text-overflow:ellipsis;white-space:nowrap" title="<?= htmlspecialchars($r['destination_address']) ?>">→ <?= htmlspecialchars($r['destination_address']) ?></div></td>
                    <td><span class="badge badge-dot <?= RIDE_STATUS_CLASSES[$r['status']] ?? '' ?>"><span><?= RIDE_STATUS_LABELS[$r['status']] ?? $r['status'] ?></span></span></td>
                    <td><?= number_format($r['distance_km'] ?? 0, 1) ?> km</td>
                    <td style="font-weight:var(--font-semibold)">R$ <?= number_format($r['fare'] ?? 0, 2, ',', '.') ?></td>
                    <td style="white-space:nowrap;color:var(--gray-500);font-size:var(--text-xs)"><?= date('d/m/Y H:i', strtotime($r['created_at'])) ?></td>
                    <td><a href="<?= APP_URL ?>/rides/show/<?= $r['id'] ?>" class="btn btn-ghost btn-icon btn-sm" title="Ver Detalhes"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></a></td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>

    <?php if ($pagination['total_pages'] > 1): ?>
    <div class="table-footer">
        <div class="table-info">Mostrando <?= count($rides) ?> de <?= $pagination['total'] ?></div>
        <div class="pagination">
            <?php $params = http_build_query(array_filter(['search' => $search, 'status' => $status, 'date_from' => $dateFrom, 'date_to' => $dateTo])); ?>
            <?php if ($pagination['page'] > 1): ?><a href="<?= APP_URL ?>/rides?page=<?= $pagination['page'] - 1 ?>&<?= $params ?>">‹</a><?php else: ?><span class="disabled">‹</span><?php endif; ?>
            <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                <?php if ($i === $pagination['page']): ?><span class="active"><?= $i ?></span><?php else: ?><a href="<?= APP_URL ?>/rides?page=<?= $i ?>&<?= $params ?>"><?= $i ?></a><?php endif; ?>
            <?php endfor; ?>
            <?php if ($pagination['page'] < $pagination['total_pages']): ?><a href="<?= APP_URL ?>/rides?page=<?= $pagination['page'] + 1 ?>&<?= $params ?>">›</a><?php else: ?><span class="disabled">›</span><?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
</div>
