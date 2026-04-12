<?php
/**
 * AuthController — Login/Logout
 */
class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function loginForm(): void
    {
        if (Session::isLoggedIn()) {
            $this->redirect('dashboard');
            return;
        }
        $this->view('auth.login', [], false);
    }

    /**
     * Process login
     */
    public function login(): void
    {
        $this->validateCsrf();

        $email = $this->input('email');
        $password = $this->rawInput('password');

        // Validate
        $validator = new Validator($_POST);
        $validator->required('email', 'E-mail')
                  ->email('email', 'E-mail')
                  ->required('password', 'Senha');

        if ($validator->fails()) {
            Session::setFlash('error', $validator->firstError());
            Session::set('old_email', $email);
            $this->redirect('login');
            return;
        }

        // Find user
        $user = User::findByEmail($email);

        if (!$user || !User::verifyPassword($password, $user['password_hash'])) {
            Session::setFlash('error', 'Credenciais inválidas. Verifique seu e-mail e senha.');
            Session::set('old_email', $email);
            $this->redirect('login');
            return;
        }

        // Login
        Session::login($user);
        Session::setFlash('success', 'Bem-vindo de volta, ' . $user['name'] . '!');
        $this->redirect('dashboard');
    }

    /**
     * Logout
     */
    public function logout(): void
    {
        Session::destroy();
        session_start();
        Session::setFlash('success', 'Você saiu do sistema com sucesso.');
        $this->redirect('login');
    }
}
