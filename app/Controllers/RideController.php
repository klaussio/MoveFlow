<?php
/**
 * RideController — Full ride lifecycle management
 */
class RideController extends Controller
{
    /**
     * List rides with filters
     */
    public function index(): void
    {
        $this->requireAuth();

        $page = max(1, (int) ($this->input('page', 1)));
        $search = $this->input('search', '');
        $status = $this->input('status', '');
        $dateFrom = $this->input('date_from', '');
        $dateTo = $this->input('date_to', '');

        $result = Ride::search($page, $search, $status, $dateFrom, $dateTo);

        $this->view('rides.index', [
            'pageTitle' => 'Corridas',
            'rides' => $result['data'],
            'pagination' => $result,
            'search' => $search,
            'status' => $status,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
        ]);
    }

    /**
     * Show create ride form
     */
    public function create(): void
    {
        $this->requireAuth();

        $this->view('rides.form', [
            'pageTitle' => 'Nova Corrida',
            'passengers' => Passenger::allForSelect(),
            'drivers' => Driver::available(),
        ]);
    }

    /**
     * Store new ride
     */
    public function store(): void
    {
        $this->requireAuth();
        $this->validateCsrf();

        $validator = new Validator($_POST);
        $validator->required('passenger_id', 'Passageiro')
                  ->required('origin_address', 'Endereço de Origem')
                  ->required('destination_address', 'Endereço de Destino')
                  ->required('distance_km', 'Distância')
                  ->numeric('distance_km', 'Distância')
                  ->required('estimated_duration_min', 'Duração Estimada')
                  ->numeric('estimated_duration_min', 'Duração Estimada');

        if ($validator->fails()) {
            Session::setFlash('error', $validator->firstError());
            Session::set('old_input', $_POST);
            $this->redirect('rides/create');
            return;
        }

        $distanceKm = (float) $_POST['distance_km'];
        $durationMin = (int) $_POST['estimated_duration_min'];
        $fare = Ride::calculateFare($distanceKm, $durationMin);
        $driverId = !empty($_POST['driver_id']) ? (int) $_POST['driver_id'] : null;
        $status = $driverId ? RIDE_STATUS_ACCEPTED : RIDE_STATUS_REQUESTED;

        $rideId = Ride::create([
            'passenger_id' => (int) $_POST['passenger_id'],
            'driver_id' => $driverId,
            'origin_address' => $this->input('origin_address'),
            'origin_lat' => (float) ($_POST['origin_lat'] ?? 0),
            'origin_lng' => (float) ($_POST['origin_lng'] ?? 0),
            'destination_address' => $this->input('destination_address'),
            'destination_lat' => (float) ($_POST['destination_lat'] ?? 0),
            'destination_lng' => (float) ($_POST['destination_lng'] ?? 0),
            'status' => $status,
            'distance_km' => $distanceKm,
            'estimated_duration_min' => $durationMin,
            'fare' => $fare,
            'requested_at' => date('Y-m-d H:i:s'),
            'accepted_at' => $driverId ? date('Y-m-d H:i:s') : null,
        ]);

        // Mark driver as busy if assigned
        if ($driverId) {
            Driver::update($driverId, ['status' => DRIVER_STATUS_BUSY]);
        }

        Session::setFlash('success', 'Corrida criada com sucesso! Tarifa: R$ ' . number_format($fare, 2, ',', '.'));
        $this->redirect("rides/show/{$rideId}");
    }

    /**
     * Show ride details
     */
    public function show(int $id): void
    {
        $this->requireAuth();

        $ride = Ride::findWithDetails($id);
        if (!$ride) {
            Session::setFlash('error', 'Corrida não encontrada.');
            $this->redirect('rides');
            return;
        }

        $availableDrivers = Driver::available();

        $this->view('rides.detail', [
            'pageTitle' => 'Corrida #' . $id,
            'ride' => $ride,
            'availableDrivers' => $availableDrivers,
        ]);
    }

    /**
     * Accept ride (assign driver)
     */
    public function accept(int $id): void
    {
        $this->requireAuth();
        $this->validateCsrf();

        $ride = Ride::find($id);
        if (!$ride || $ride['status'] !== RIDE_STATUS_REQUESTED) {
            Session::setFlash('error', 'Corrida não pode ser aceita neste estado.');
            $this->redirect("rides/show/{$id}");
            return;
        }

        $driverId = (int) ($_POST['driver_id'] ?? 0);
        if (!$driverId) {
            // Auto-assign first available driver
            $available = Driver::available();
            if (empty($available)) {
                Session::setFlash('error', 'Nenhum motorista disponível no momento.');
                $this->redirect("rides/show/{$id}");
                return;
            }
            $driverId = $available[0]['id'];
        }

        Ride::update($id, [
            'driver_id' => $driverId,
            'status' => RIDE_STATUS_ACCEPTED,
            'accepted_at' => date('Y-m-d H:i:s'),
        ]);

        Driver::update($driverId, ['status' => DRIVER_STATUS_BUSY]);

        Session::setFlash('success', 'Corrida aceita com sucesso!');
        $this->redirect("rides/show/{$id}");
    }

    /**
     * Start ride
     */
    public function start(int $id): void
    {
        $this->requireAuth();
        $this->validateCsrf();

        $ride = Ride::find($id);
        if (!$ride || $ride['status'] !== RIDE_STATUS_ACCEPTED) {
            Session::setFlash('error', 'Corrida não pode ser iniciada neste estado.');
            $this->redirect("rides/show/{$id}");
            return;
        }

        Ride::update($id, [
            'status' => RIDE_STATUS_IN_PROGRESS,
            'started_at' => date('Y-m-d H:i:s'),
        ]);

        Session::setFlash('success', 'Corrida iniciada!');
        $this->redirect("rides/show/{$id}");
    }

    /**
     * Complete ride
     */
    public function complete(int $id): void
    {
        $this->requireAuth();
        $this->validateCsrf();

        $ride = Ride::find($id);
        if (!$ride || $ride['status'] !== RIDE_STATUS_IN_PROGRESS) {
            Session::setFlash('error', 'Corrida não pode ser finalizada neste estado.');
            $this->redirect("rides/show/{$id}");
            return;
        }

        Ride::update($id, [
            'status' => RIDE_STATUS_COMPLETED,
            'completed_at' => date('Y-m-d H:i:s'),
        ]);

        // Release driver
        if ($ride['driver_id']) {
            Driver::update($ride['driver_id'], ['status' => DRIVER_STATUS_AVAILABLE]);
        }

        Session::setFlash('success', 'Corrida finalizada com sucesso!');
        $this->redirect("rides/show/{$id}");
    }

    /**
     * Cancel ride
     */
    public function cancel(int $id): void
    {
        $this->requireAuth();
        $this->validateCsrf();

        $ride = Ride::find($id);
        if (!$ride || in_array($ride['status'], [RIDE_STATUS_COMPLETED, RIDE_STATUS_CANCELLED])) {
            Session::setFlash('error', 'Corrida não pode ser cancelada neste estado.');
            $this->redirect("rides/show/{$id}");
            return;
        }

        $cancelReason = $this->input('cancel_reason', 'Cancelada pelo administrador');

        Ride::update($id, [
            'status' => RIDE_STATUS_CANCELLED,
            'cancelled_at' => date('Y-m-d H:i:s'),
            'cancel_reason' => $cancelReason,
        ]);

        // Release driver
        if ($ride['driver_id']) {
            Driver::update($ride['driver_id'], ['status' => DRIVER_STATUS_AVAILABLE]);
        }

        Session::setFlash('success', 'Corrida cancelada.');
        $this->redirect("rides/show/{$id}");
    }

    /**
     * Assign driver to ride manually
     */
    public function assignDriver(int $id): void
    {
        $this->requireAuth();
        $this->validateCsrf();

        $ride = Ride::find($id);
        if (!$ride || $ride['status'] !== RIDE_STATUS_REQUESTED) {
            Session::setFlash('error', 'Motorista só pode ser atribuído a corridas solicitadas.');
            $this->redirect("rides/show/{$id}");
            return;
        }

        $driverId = (int) ($_POST['driver_id'] ?? 0);
        $driver = Driver::find($driverId);

        if (!$driver || $driver['status'] !== DRIVER_STATUS_AVAILABLE) {
            Session::setFlash('error', 'Motorista não disponível.');
            $this->redirect("rides/show/{$id}");
            return;
        }

        Ride::update($id, [
            'driver_id' => $driverId,
            'status' => RIDE_STATUS_ACCEPTED,
            'accepted_at' => date('Y-m-d H:i:s'),
        ]);

        Driver::update($driverId, ['status' => DRIVER_STATUS_BUSY]);

        Session::setFlash('success', 'Motorista atribuído com sucesso!');
        $this->redirect("rides/show/{$id}");
    }

    /**
     * API: Calculate fare
     */
    public function calculateFare(): void
    {
        $this->requireAuth();

        $distance = (float) ($this->input('distance', 0));
        $duration = (int) ($this->input('duration', 0));

        $fare = Ride::calculateFare($distance, $duration);

        Response::success('Tarifa calculada', [
            'fare' => $fare,
            'formatted' => 'R$ ' . number_format($fare, 2, ',', '.'),
            'breakdown' => [
                'base' => FARE_BASE,
                'distance' => round($distance * FARE_PER_KM, 2),
                'time' => round($duration * FARE_PER_MIN, 2),
            ]
        ]);
    }
}
