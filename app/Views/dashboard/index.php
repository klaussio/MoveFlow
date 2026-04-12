<?php
/** @var int $totalRides @var int $todayRides @var array $ridesByStatus @var float $totalRevenue @var float $monthlyRevenue */
/** @var int $activeDrivers @var int $availableDrivers @var int $totalDrivers @var int $totalPassengers */
/** @var array $recentRides @var array $revenuePerDay @var array $statusDistribution */

$inProgress = $ridesByStatus[RIDE_STATUS_IN_PROGRESS] ?? 0;
$requested = $ridesByStatus[RIDE_STATUS_REQUESTED] ?? 0;
?>

<div class="page-header">
    <div class="page-header-info">
        <h1>Dashboard</h1>
        <p>Visão geral do sistema de corridas</p>
    </div>
    <div class="page-header-actions">
        <a href="<?= APP_URL ?>/rides/create" class="btn btn-primary">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Nova Corrida
        </a>
    </div>
</div>

<!-- Stat Cards -->
<div class="stat-grid">
    <div class="stat-card" style="--stat-accent: var(--primary-500)">
        <div class="stat-card-icon purple">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        </div>
        <div class="stat-card-content">
            <div class="stat-card-label">Total de Corridas</div>
            <div class="stat-card-value stat-animate" data-target="<?= $totalRides ?>">0</div>
            <div class="stat-card-trend"><?= $todayRides ?> hoje</div>
        </div>
    </div>

    <div class="stat-card" style="--stat-accent: var(--info-500)">
        <div class="stat-card-icon blue">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
        </div>
        <div class="stat-card-content">
            <div class="stat-card-label">Em Andamento</div>
            <div class="stat-card-value stat-animate" data-target="<?= $inProgress ?>">0</div>
            <div class="stat-card-trend"><?= $requested ?> solicitadas</div>
        </div>
    </div>

    <div class="stat-card" style="--stat-accent: var(--success-500)">
        <div class="stat-card-icon green">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
        </div>
        <div class="stat-card-content">
            <div class="stat-card-label">Faturamento Total</div>
            <div class="stat-card-value">R$ <?= number_format($totalRevenue, 2, ',', '.') ?></div>
            <div class="stat-card-trend up">R$ <?= number_format($monthlyRevenue, 2, ',', '.') ?> este mês</div>
        </div>
    </div>

    <div class="stat-card" style="--stat-accent: var(--warning-500)">
        <div class="stat-card-icon orange">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        </div>
        <div class="stat-card-content">
            <div class="stat-card-label">Motoristas Ativos</div>
            <div class="stat-card-value stat-animate" data-target="<?= $activeDrivers ?>">0</div>
            <div class="stat-card-trend"><?= $availableDrivers ?> disponíveis</div>
        </div>
    </div>
</div>

<!-- Charts -->
<div class="dashboard-grid">
    <div class="card">
        <div class="card-header">
            <h3>Faturamento — Últimos 7 dias</h3>
        </div>
        <div class="card-body">
            <div class="chart-container">
                <canvas id="revenue-chart" class="chart-canvas"></canvas>
            </div>
            <script type="application/json" id="revenue-data"><?= json_encode($revenuePerDay) ?></script>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3>Distribuição por Status</h3>
        </div>
        <div class="card-body">
            <div class="chart-container">
                <canvas id="status-chart" class="chart-canvas"></canvas>
            </div>
            <script type="application/json" id="status-data"><?= json_encode($statusDistribution) ?></script>
        </div>
    </div>
</div>

<!-- Recent Rides Table -->
<div class="table-container mt-6">
    <div class="table-header">
        <h3>Corridas Recentes</h3>
        <a href="<?= APP_URL ?>/rides" class="btn btn-outline btn-sm">Ver Todas</a>
    </div>
    <div class="table-wrapper">
        <table>
            <thead>
            <tr>
                <th>ID</th>
                <th>Passageiro</th>
                <th>Motorista</th>
                <th>Origem</th>
                <th>Destino</th>
                <th>Status</th>
                <th>Tarifa</th>
                <th>Data</th>
            </tr>
            </thead>
            <tbody>
            <?php if (empty($recentRides)): ?>
                <tr><td colspan="8" class="text-center text-muted" style="padding:var(--space-8)">Nenhuma corrida registrada</td></tr>
            <?php else: foreach ($recentRides as $ride): ?>
                <tr>
                    <td><a href="<?= APP_URL ?>/rides/show/<?= $ride['id'] ?>" style="font-weight:var(--font-semibold)">#<?= $ride['id'] ?></a></td>
                    <td><?= htmlspecialchars($ride['passenger_name'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($ride['driver_name'] ?? '<span class="text-muted">Não atribuído</span>') ?></td>
                    <td style="max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?= htmlspecialchars($ride['origin_address'] ?? '') ?></td>
                    <td style="max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?= htmlspecialchars($ride['destination_address'] ?? '') ?></td>
                    <td><span class="badge badge-dot <?= RIDE_STATUS_CLASSES[$ride['status']] ?? '' ?>"><span><?= RIDE_STATUS_LABELS[$ride['status']] ?? $ride['status'] ?></span></span></td>
                    <td style="font-weight:var(--font-semibold)">R$ <?= number_format($ride['fare'] ?? 0, 2, ',', '.') ?></td>
                    <td style="white-space:nowrap;color:var(--gray-500);font-size:var(--text-xs)"><?= date('d/m/Y H:i', strtotime($ride['created_at'])) ?></td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>
