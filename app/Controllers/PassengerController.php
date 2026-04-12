<?php
/**
 * PassengerController — CRUD for passengers
 */
class PassengerController extends Controller
{
    /**
     * List passengers with search
     */
    public function index(): void
    {
        $this->requireAuth();

        $page = max(1, (int) ($this->input('page', 1)));
        $search = $this->input('search', '');

        $result = Passenger::search($page, $search);

        $this->view('passengers.index', [
            'pageTitle' => 'Passageiros',
            'passengers' => $result['data'],
            'pagination' => $result,
            'search' => $search,
        ]);
    }

    /**
     * Show create form
     */
    public function create(): void
    {
        $this->requireAuth();
        $this->view('passengers.form', [
            'pageTitle' => 'Novo Passageiro',
            'passenger' => null,
            'editing' => false,
        ]);
    }

    /**
     * Store new passenger
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
                  ->unique('email', 'passengers', 'email', 0, 'E-mail')
                  ->required('phone', 'Telefone')
                  ->phone('phone', 'Telefone')
                  ->required('cpf', 'CPF')
                  ->cpf('cpf')
                  ->unique('cpf', 'passengers', 'cpf', 0, 'CPF');

        if ($validator->fails()) {
            Session::setFlash('error', $validator->firstError());
            Session::set('old_input', $_POST);
            $this->redirect('passengers/create');
            return;
        }

        $cpf = preg_replace('/\D/', '', $_POST['cpf']);
        $phone = preg_replace('/\D/', '', $_POST['phone']);

        Passenger::create([
            'name' => $this->input('name'),
            'email' => $this->input('email'),
            'phone' => $phone,
            'cpf' => $cpf,
        ]);

        Session::setFlash('success', 'Passageiro cadastrado com sucesso!');
        $this->redirect('passengers');
    }

    /**
     * Show edit form
     */
    public function edit(int $id): void
    {
        $this->requireAuth();

        $passenger = Passenger::withRideCount($id);
        if (!$passenger) {
            Session::setFlash('error', 'Passageiro não encontrado.');
            $this->redirect('passengers');
            return;
        }

        $this->view('passengers.form', [
            'pageTitle' => 'Editar Passageiro',
            'passenger' => $passenger,
            'editing' => true,
        ]);
    }

    /**
     * Update passenger
     */
    public function update(int $id): void
    {
        $this->requireAuth();
        $this->validateCsrf();

        $passenger = Passenger::find($id);
        if (!$passenger) {
            Session::setFlash('error', 'Passageiro não encontrado.');
            $this->redirect('passengers');
            return;
        }

        $validator = new Validator($_POST);
        $validator->required('name', 'Nome')
                  ->minLength('name', 3, 'Nome')
                  ->required('email', 'E-mail')
                  ->email('email', 'E-mail')
                  ->unique('email', 'passengers', 'email', $id, 'E-mail')
                  ->required('phone', 'Telefone')
                  ->phone('phone', 'Telefone')
                  ->required('cpf', 'CPF')
                  ->cpf('cpf')
                  ->unique('cpf', 'passengers', 'cpf', $id, 'CPF');

        if ($validator->fails()) {
            Session::setFlash('error', $validator->firstError());
            $this->redirect("passengers/edit/{$id}");
            return;
        }

        $cpf = preg_replace('/\D/', '', $_POST['cpf']);
        $phone = preg_replace('/\D/', '', $_POST['phone']);

        Passenger::update($id, [
            'name' => $this->input('name'),
            'email' => $this->input('email'),
            'phone' => $phone,
            'cpf' => $cpf,
        ]);

        Session::setFlash('success', 'Passageiro atualizado com sucesso!');
        $this->redirect('passengers');
    }

    /**
     * Delete passenger
     */
    public function delete(int $id): void
    {
        $this->requireAuth();
        $this->validateCsrf();

        $passenger = Passenger::find($id);
        if (!$passenger) {
            Session::setFlash('error', 'Passageiro não encontrado.');
            $this->redirect('passengers');
            return;
        }

        // Check for active rides
        $activeRides = (int) Ride::rawValue(
            "SELECT COUNT(*) FROM rides WHERE passenger_id = :id AND status IN ('requested', 'accepted', 'in_progress')",
            ['id' => $id]
        );

        if ($activeRides > 0) {
            Session::setFlash('error', 'Não é possível excluir um passageiro com corridas ativas.');
            $this->redirect('passengers');
            return;
        }

        Passenger::delete($id);
        Session::setFlash('success', 'Passageiro excluído com sucesso!');
        $this->redirect('passengers');
    }
}
