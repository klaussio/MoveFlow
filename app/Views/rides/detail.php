<?php
/** @var array $ride @var array $availableDrivers */
$s = $ride['status'];
$isRequested = $s === RIDE_STATUS_REQUESTED;
$isAccepted = $s === RIDE_STATUS_ACCEPTED;
$isInProgress = $s === RIDE_STATUS_IN_PROGRESS;
$isCompleted = $s === RIDE_STATUS_COMPLETED;
$isCancelled = $s === RIDE_STATUS_CANCELLED;
?>

<div class="page-header">
    <div class="page-header-info">
        <h1>Corrida #<?= $ride['id'] ?></h1>
        <p>Detalhes e gerenciamento da corrida</p>
    </div>
    <div class="page-header-actions">
        <a href="<?= APP_URL ?>/rides" class="btn btn-outline">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
            Voltar
        </a>
    </div>
</div>

<div class="ride-detail-grid">
    <!-- Left Column -->
    <div>
        <!-- Status & Actions -->
        <div class="card mb-4">
            <div class="card-body">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:var(--space-4)">
                    <h3>Status</h3>
                    <span class="badge badge-dot <?= RIDE_STATUS_CLASSES[$s] ?? '' ?>" style="font-size:var(--text-sm);padding:6px 14px">
                        <span><?= RIDE_STATUS_LABELS[$s] ?? $s ?></span>
                    </span>
                </div>

                <!-- Timeline -->
                <div class="ride-timeline">
                    <div class="timeline-item">
                        <div class="timeline-dot <?= $ride['requested_at'] ? 'completed' : '' ?>"></div>
                        <div class="timeline-label">Solicitada</div>
                        <div class="timeline-time"><?= $ride['requested_at'] ? date('d/m/Y H:i:s', strtotime($ride['requested_at'])) : '—' ?></div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-dot <?= $ride['accepted_at'] ? 'completed' : ($isRequested ? 'active' : '') ?>"></div>
                        <div class="timeline-label">Aceita</div>
                        <div class="timeline-time"><?= $ride['accepted_at'] ? date('d/m/Y H:i:s', strtotime($ride['accepted_at'])) : '—' ?></div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-dot <?= $ride['started_at'] ? 'completed' : ($isAccepted ? 'active' : '') ?>"></div>
                        <div class="timeline-label">Em Andamento</div>
                        <div class="timeline-time"><?= $ride['started_at'] ? date('d/m/Y H:i:s', strtotime($ride['started_at'])) : '—' ?></div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-dot <?= $isCompleted ? 'completed' : ($isCancelled ? 'cancelled' : ($isInProgress ? 'active' : '')) ?>"></div>
                        <div class="timeline-label"><?= $isCancelled ? 'Cancelada' : 'Finalizada' ?></div>
                        <div class="timeline-time">
                            <?php if ($isCompleted): ?><?= date('d/m/Y H:i:s', strtotime($ride['completed_at'])) ?>
                            <?php elseif ($isCancelled): ?><?= date('d/m/Y H:i:s', strtotime($ride['cancelled_at'])) ?>
                            <?php else: ?>—<?php endif; ?>
                        </div>
                    </div>
                </div>

                <?php if ($isCancelled && $ride['cancel_reason']): ?>
                    <div style="margin-top:var(--space-4);padding:var(--space-3);background:var(--danger-50);border-radius:var(--radius-md);border:1px solid var(--danger-100)">
                        <span style="font-size:var(--text-xs);font-weight:var(--font-semibold);color:var(--danger-600)">Motivo do cancelamento:</span>
                        <p style="margin:var(--space-1) 0 0;font-size:var(--text-sm);color:var(--danger-700)"><?= htmlspecialchars($ride['cancel_reason']) ?></p>
                    </div>
                <?php endif; ?>

                <!-- Action Buttons -->
                <div style="display:flex;gap:var(--space-3);margin-top:var(--space-5);flex-wrap:wrap">
                    <?php if ($isRequested): ?>
                        <form method="POST" action="<?= APP_URL ?>/rides/accept/<?= $ride['id'] ?>" style="display:inline">
                            <?= Session::csrfField() ?>
                            <button class="btn btn-success">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                                Aceitar (Auto)
                            </button>
                        </form>
                    <?php endif; ?>

                    <?php if ($isAccepted): ?>
                        <form method="POST" action="<?= APP_URL ?>/rides/start/<?= $ride['id'] ?>" style="display:inline">
                            <?= Session::csrfField() ?>
                            <button class="btn btn-primary">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                                Iniciar Corrida
                            </button>
                        </form>
                    <?php endif; ?>

                    <?php if ($isInProgress): ?>
                        <form method="POST" action="<?= APP_URL ?>/rides/complete/<?= $ride['id'] ?>" style="display:inline">
                            <?= Session::csrfField() ?>
                            <button class="btn btn-success">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                Finalizar Corrida
                            </button>
                        </form>
                    <?php endif; ?>

                    <?php if (!$isCompleted && !$isCancelled): ?>
                        <form method="POST" action="<?= APP_URL ?>/rides/cancel/<?= $ride['id'] ?>" style="display:inline" onsubmit="return confirm('Tem certeza que deseja cancelar esta corrida?')">
                            <?= Session::csrfField() ?>
                            <input type="hidden" name="cancel_reason" value="Cancelada pelo administrador">
                            <button class="btn btn-danger">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                                Cancelar Corrida
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Route -->
        <div class="card mb-4">
            <div class="card-header"><h3>Rota</h3></div>
            <div class="card-body">
                <div class="ride-route">
                    <div class="ride-route-line"></div>
                    <div class="ride-route-points">
                        <div class="ride-route-point">
                            <strong>Origem</strong>
                            <?= htmlspecialchars($ride['origin_address']) ?>
                        </div>
                        <div class="ride-route-point">
                            <strong>Destino</strong>
                            <?= htmlspecialchars($ride['destination_address']) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Assign Driver (only for requested rides) -->
        <?php if ($isRequested && !empty($availableDrivers)): ?>
        <div class="card mb-4">
            <div class="card-header"><h3>Atribuir Motorista</h3></div>
            <div class="card-body">
                <form method="POST" action="<?= APP_URL ?>/rides/assign-driver/<?= $ride['id'] ?>">
                    <?= Session::csrfField() ?>
                    <div class="form-group">
                        <select class="form-control" name="driver_id" required>
                            <option value="">Selecione um motorista</option>
                            <?php foreach ($availableDrivers as $d): ?>
                                <option value="<?= $d['id'] ?>"><?= htmlspecialchars($d['name']) ?> — <?= htmlspecialchars($d['vehicle_model']) ?> (<?= $d['vehicle_plate'] ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button class="btn btn-primary btn-sm">Atribuir Motorista</button>
                </form>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Right Column -->
    <div>
        <!-- Fare -->
        <div class="fare-display mb-4">
            <div class="fare-label">Tarifa</div>
            <div class="fare-value">R$ <?= number_format($ride['fare'] ?? 0, 2, ',', '.') ?></div>
            <div class="fare-breakdown">
                <span>Base: R$ <?= number_format(FARE_BASE, 2, ',', '.') ?></span>
                <span>Dist: R$ <?= number_format(($ride['distance_km'] ?? 0) * FARE_PER_KM, 2, ',', '.') ?></span>
                <span>Tempo: R$ <?= number_format(($ride['estimated_duration_min'] ?? 0) * FARE_PER_MIN, 2, ',', '.') ?></span>
            </div>
        </div>

        <!-- Ride Info -->
        <div class="card mb-4">
            <div class="card-header"><h3>Informações</h3></div>
            <div class="card-body">
                <div class="ride-info-grid">
                    <div class="ride-info-item">
                        <div class="ride-info-label">Distância</div>
                        <div class="ride-info-value"><?= number_format($ride['distance_km'] ?? 0, 1) ?> km</div>
                    </div>
                    <div class="ride-info-item">
                        <div class="ride-info-label">Duração Est.</div>
                        <div class="ride-info-value"><?= $ride['estimated_duration_min'] ?? 0 ?> min</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Passenger Info -->
        <div class="card mb-4">
            <div class="card-header"><h3>Passageiro</h3></div>
            <div class="card-body">
                <div style="font-weight:var(--font-medium);color:var(--gray-900);margin-bottom:var(--space-1)"><?= htmlspecialchars($ride['passenger_name'] ?? '—') ?></div>
                <div style="font-size:var(--text-sm);color:var(--gray-500)"><?= htmlspecialchars($ride['passenger_phone'] ?? '') ?></div>
                <div style="font-size:var(--text-sm);color:var(--gray-500)"><?= htmlspecialchars($ride['passenger_email'] ?? '') ?></div>
            </div>
        </div>

        <!-- Driver Info -->
        <?php if ($ride['driver_name']): ?>
        <div class="card">
            <div class="card-header"><h3>Motorista</h3></div>
            <div class="card-body">
                <div style="font-weight:var(--font-medium);color:var(--gray-900);margin-bottom:var(--space-1)"><?= htmlspecialchars($ride['driver_name']) ?></div>
                <div style="font-size:var(--text-sm);color:var(--gray-500)"><?= htmlspecialchars($ride['driver_phone'] ?? '') ?></div>
                <div style="font-size:var(--text-sm);color:var(--gray-500);margin-top:var(--space-2)"><?= htmlspecialchars($ride['vehicle_model'] ?? '') ?> · <?= htmlspecialchars($ride['vehicle_plate'] ?? '') ?> · <?= htmlspecialchars($ride['vehicle_color'] ?? '') ?></div>
                <?php if ($ride['driver_rating']): ?>
                    <div style="margin-top:var(--space-2);color:var(--warning-500)">★ <?= number_format($ride['driver_rating'], 1) ?></div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
