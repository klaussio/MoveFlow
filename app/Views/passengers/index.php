<?php /** @var array $passengers @var array $pagination @var string $search */ ?>

<div class="page-header">
    <div class="page-header-info">
        <h1>Passageiros</h1>
        <p>Gerencie os passageiros cadastrados no sistema</p>
    </div>
    <div class="page-header-actions">
        <a href="<?= APP_URL ?>/passengers/create" class="btn btn-primary">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Novo Passageiro
        </a>
    </div>
</div>

<div class="table-container" id="passengers-table">
    <div class="table-header">
        <h3><?= $pagination['total'] ?> passageiro(s) encontrado(s)</h3>
        <div class="table-filters">
            <div class="form-search">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input type="text" class="form-control" id="passenger-search" placeholder="Buscar passageiro..." value="<?= htmlspecialchars($search) ?>">
            </div>
        </div>
    </div>

    <div class="table-wrapper">
        <table>
            <thead><tr><th>Passageiro</th><th>Telefone</th><th>CPF</th><th>Cadastrado em</th><th>Ações</th></tr></thead>
            <tbody>
            <?php if (empty($passengers)): ?>
                <tr><td colspan="5"><div class="empty-state"><div class="empty-state-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg></div><h4>Nenhum passageiro encontrado</h4><p>Cadastre um novo passageiro ou ajuste a busca.</p></div></td></tr>
            <?php else: foreach ($passengers as $p): ?>
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:var(--space-3)">
                            <div style="width:36px;height:36px;border-radius:var(--radius-full);background:linear-gradient(135deg,var(--accent-100),var(--success-100));display:flex;align-items:center;justify-content:center;font-weight:var(--font-semibold);color:var(--accent-600);font-size:var(--text-sm)"><?= strtoupper(substr($p['name'], 0, 1)) ?></div>
                            <div><div style="font-weight:var(--font-medium);color:var(--gray-900)"><?= htmlspecialchars($p['name']) ?></div><div style="font-size:var(--text-xs);color:var(--gray-500)"><?= htmlspecialchars($p['email']) ?></div></div>
                        </div>
                    </td>
                    <td><?= htmlspecialchars($p['phone']) ?></td>
                    <td style="font-family:var(--font-mono);font-size:var(--text-xs)"><?= htmlspecialchars($p['cpf']) ?></td>
                    <td style="color:var(--gray-500);font-size:var(--text-xs)"><?= date('d/m/Y', strtotime($p['created_at'])) ?></td>
                    <td>
                        <div class="table-actions">
                            <a href="<?= APP_URL ?>/passengers/edit/<?= $p['id'] ?>" class="btn btn-ghost btn-icon btn-sm" title="Editar"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></a>
                            <form method="POST" action="<?= APP_URL ?>/passengers/delete/<?= $p['id'] ?>" class="delete-form" data-name="<?= htmlspecialchars($p['name']) ?>" style="display:inline"><?= Session::csrfField() ?><button type="submit" class="btn btn-ghost btn-icon btn-sm" style="color:var(--danger-500)" title="Excluir"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button></form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>

    <?php if ($pagination['total_pages'] > 1): ?>
    <div class="table-footer">
        <div class="table-info">Mostrando <?= count($passengers) ?> de <?= $pagination['total'] ?></div>
        <div class="pagination">
            <?php if ($pagination['page'] > 1): ?><a href="<?= APP_URL ?>/passengers?page=<?= $pagination['page'] - 1 ?>&search=<?= urlencode($search) ?>">‹</a><?php else: ?><span class="disabled">‹</span><?php endif; ?>
            <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                <?php if ($i === $pagination['page']): ?><span class="active"><?= $i ?></span><?php else: ?><a href="<?= APP_URL ?>/passengers?page=<?= $i ?>&search=<?= urlencode($search) ?>"><?= $i ?></a><?php endif; ?>
            <?php endfor; ?>
            <?php if ($pagination['page'] < $pagination['total_pages']): ?><a href="<?= APP_URL ?>/passengers?page=<?= $pagination['page'] + 1 ?>&search=<?= urlencode($search) ?>">›</a><?php else: ?><span class="disabled">›</span><?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
</div>
