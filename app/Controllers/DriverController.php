<?php
/**
 * DriverController — CRUD for drivers
 */
class DriverController extends Controller
{
    /**
     * List drivers with search and filters
     */
    public function index(): void
    {
        $this->requireAuth();

        $page = max(1, (int) ($this->input('page', 1)));
        $search = $this->input('search', '');
        $status = $this->input('status', '');

        $result = Driver::search($page, $search, $status);

        $this->view('drivers.index', [
            'pageTitle' => 'Motoristas',
            'drivers' => $result['data'],
            'pagination' => $result,
            'search' => $search,
            'status' => $status,
        ]);
    }

    /**
     * Show create form
     */
    public function create(): void
    {
        $this->requireAuth();
        $this->view('drivers.form', [
            'pageTitle' => 'Novo Motorista',
            'driver' => null,
            'editing' => false,
        ]);
    }

    /**
     * Store new driver
     */
    public function store(): void
    {
        $this->requireAuth();
        $this->validateCsrf();

        $validator = new Validator($_POST);
        $validator->required('name', 'Nome')
                  ->minLength('name', 3, 'Nome')
                  ->required('email', 'E-mail')
                  ->email('email', 'E-mail')
                  ->unique('email', 'drivers', 'email', 0, 'E-mail')
                  ->required('phone', 'Telefone')
                  ->phone('phone', 'Telefone')
                  ->required('cpf', 'CPF')
                  ->cpf('cpf')
                  ->unique('cpf', 'drivers', 'cpf', 0, 'CPF')
                  ->required('cnh', 'CNH')
                  ->required('vehicle_plate', 'Placa do Veículo')
                  ->required('vehicle_model', 'Modelo do Veículo')
                  ->required('vehicle_color', 'Cor do Veículo');

        if ($validator->fails()) {
            if ($this->isAjax()) {
                Response::error('Erro de validação', $validator->errors());
                return;
            }
            Session::setFlash('error', $validator->firstError());
            Session::set('old_input', $_POST);
            $this->redirect('drivers/create');
            return;
        }

        $cpf = preg_replace('/\D/', '', $_POST['cpf']);
        $phone = preg_replace('/\D/', '', $_POST['phone']);

        Driver::create([
            'name' => $this->input('name'),
            'email' => $this->input('email'),
            'phone' => $phone,
            'cpf' => $cpf,
            'cnh' => $this->input('cnh'),
            'vehicle_plate' => strtoupper($this->input('vehicle_plate')),
            'vehicle_model' => $this->input('vehicle_model'),
            'vehicle_color' => $this->input('vehicle_color'),
            'status' => DRIVER_STATUS_AVAILABLE,
            'rating' => 5.0,
        ]);

        Session::setFlash('success', 'Motorista cadastrado com sucesso!');
        $this->redirect('drivers');
    }

    /**
     * Show edit form
     */
    public function edit(int $id): void
    {
        $this->requireAuth();

        $driver = Driver::withRideCount($id);
        if (!$driver) {
            Session::setFlash('error', 'Motorista não encontrado.');
            $this->redirect('drivers');
            return;
        }

        $this->view('drivers.form', [
            'pageTitle' => 'Editar Motorista',
            'driver' => $driver,
            'editing' => true,
        ]);
    }

    /**
     * Update driver
     */
    public function update(int $id): void
    {
        $this->requireAuth();
        $this->validateCsrf();

        $driver = Driver::find($id);
        if (!$driver) {
            Session::setFlash('error', 'Motorista não encontrado.');
            $this->redirect('drivers');
            return;
        }

        $validator = new Validator($_POST);
        $validator->required('name', 'Nome')
                  ->minLength('name', 3, 'Nome')
                  ->required('email', 'E-mail')
                  ->email('email', 'E-mail')
                  ->unique('email', 'drivers', 'email', $id, 'E-mail')
                  ->required('phone', 'Telefone')
                  ->phone('phone', 'Telefone')
                  ->required('cpf', 'CPF')
                  ->cpf('cpf')
                  ->unique('cpf', 'drivers', 'cpf', $id, 'CPF')
                  ->required('cnh', 'CNH')
                  ->required('vehicle_plate', 'Placa do Veículo')
                  ->required('vehicle_model', 'Modelo do Veículo')
                  ->required('vehicle_color', 'Cor do Veículo');

        if ($validator->fails()) {
            Session::setFlash('error', $validator->firstError());
            $this->redirect("drivers/edit/{$id}");
            return;
        }

        $cpf = preg_replace('/\D/', '', $_POST['cpf']);
        $phone = preg_replace('/\D/', '', $_POST['phone']);

        Driver::update($id, [
            'name' => $this->input('name'),
            'email' => $this->input('email'),
            'phone' => $phone,
            'cpf' => $cpf,
            'cnh' => $this->input('cnh'),
            'vehicle_plate' => strtoupper($this->input('vehicle_plate')),
            'vehicle_model' => $this->input('vehicle_model'),
            'vehicle_color' => $this->input('vehicle_color'),
        ]);

        Session::setFlash('success', 'Motorista atualizado com sucesso!');
        $this->redirect('drivers');
    }

    /**
     * Delete driver
     */
    public function delete(int $id): void
    {
        $this->requireAuth();
        $this->validateCsrf();

        $driver = Driver::find($id);
        if (!$driver) {
            Session::setFlash('error', 'Motorista não encontrado.');
            $this->redirect('drivers');
            return;
        }

        // Check for active rides
        $activeRides = Ride::count(['driver_id' => $id, 'status' => RIDE_STATUS_IN_PROGRESS]);
        if ($activeRides > 0) {
            Session::setFlash('error', 'Não é possível excluir um motorista com corridas em andamento.');
            $this->redirect('drivers');
            return;
        }

        Driver::delete($id);
        Session::setFlash('success', 'Motorista excluído com sucesso!');
        $this->redirect('drivers');
    }

    /**
     * Toggle driver status
     */
    public function toggleStatus(int $id): void
    {
        $this->requireAuth();

        $newStatus = Driver::toggleStatus($id);
        if (!$newStatus) {
            if ($this->isAjax()) {
                Response::error('Motorista não encontrado');
                return;
            }
            Session::setFlash('error', 'Motorista não encontrado.');
            $this->redirect('drivers');
            return;
        }

        if ($this->isAjax()) {
            Response::success('Status atualizado', [
                'status' => $newStatus,
                'label' => DRIVER_STATUS_LABELS[$newStatus],
                'class' => DRIVER_STATUS_CLASSES[$newStatus],
            ]);
            return;
        }

        Session::setFlash('success', 'Status do motorista atualizado!');
        $this->redirect('drivers');
    }

    /**
     * API: Get available drivers
     */
    public function available(): void
    {
        $this->requireAuth();
        $drivers = Driver::available();
        Response::success('Motoristas disponíveis', $drivers);
    }
}
