<?php
/**
 * Validator — Server-side validation
 */
class Validator
{
    private array $errors = [];
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Validate required field
     */
    public function required(string $field, string $label = ''): self
    {
        $label = $label ?: $field;
        if (!isset($this->data[$field]) || trim($this->data[$field]) === '') {
            $this->errors[$field] = "O campo {$label} é obrigatório.";
        }
        return $this;
    }

    /**
     * Validate email format
     */
    public function email(string $field, string $label = ''): self
    {
        $label = $label ?: $field;
        if (isset($this->data[$field]) && !filter_var($this->data[$field], FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = "O campo {$label} deve ser um email válido.";
        }
        return $this;
    }

    /**
     * Validate minimum length
     */
    public function minLength(string $field, int $min, string $label = ''): self
    {
        $label = $label ?: $field;
        if (isset($this->data[$field]) && strlen($this->data[$field]) < $min) {
            $this->errors[$field] = "O campo {$label} deve ter no mínimo {$min} caracteres.";
        }
        return $this;
    }

    /**
     * Validate maximum length
     */
    public function maxLength(string $field, int $max, string $label = ''): self
    {
        $label = $label ?: $field;
        if (isset($this->data[$field]) && strlen($this->data[$field]) > $max) {
            $this->errors[$field] = "O campo {$label} deve ter no máximo {$max} caracteres.";
        }
        return $this;
    }

    /**
     * Validate numeric value
     */
    public function numeric(string $field, string $label = ''): self
    {
        $label = $label ?: $field;
        if (isset($this->data[$field]) && !is_numeric($this->data[$field])) {
            $this->errors[$field] = "O campo {$label} deve ser numérico.";
        }
        return $this;
    }

    /**
     * Validate CPF format (11 digits)
     */
    public function cpf(string $field, string $label = 'CPF'): self
    {
        if (!isset($this->data[$field])) return $this;
        
        $cpf = preg_replace('/\D/', '', $this->data[$field]);
        
        if (strlen($cpf) !== 11) {
            $this->errors[$field] = "O {$label} deve ter 11 dígitos.";
            return $this;
        }

        // Check for known invalid CPFs
        if (preg_match('/^(\d)\1{10}$/', $cpf)) {
            $this->errors[$field] = "O {$label} informado é inválido.";
            return $this;
        }

        // Validate check digits
        for ($t = 9; $t < 11; $t++) {
            $sum = 0;
            for ($i = 0; $i < $t; $i++) {
                $sum += $cpf[$i] * (($t + 1) - $i);
            }
            $digit = ((10 * $sum) % 11) % 10;
            if ($cpf[$t] != $digit) {
                $this->errors[$field] = "O {$label} informado é inválido.";
                return $this;
            }
        }

        return $this;
    }

    /**
     * Validate unique value in database
     */
    public function unique(string $field, string $table, string $column = '', int $exceptId = 0, string $label = ''): self
    {
        $label = $label ?: $field;
        $column = $column ?: $field;

        if (!isset($this->data[$field]) || trim($this->data[$field]) === '') return $this;

        $sql = "SELECT COUNT(*) FROM {$table} WHERE {$column} = :value";
        $params = ['value' => $this->data[$field]];

        if ($exceptId > 0) {
            $sql .= " AND id != :except_id";
            $params['except_id'] = $exceptId;
        }

        $count = (int) Model::rawValue($sql, $params);

        if ($count > 0) {
            $this->errors[$field] = "O valor do campo {$label} já está em uso.";
        }

        return $this;
    }

    /**
     * Validate phone format
     */
    public function phone(string $field, string $label = 'Telefone'): self
    {
        if (!isset($this->data[$field]) || trim($this->data[$field]) === '') return $this;

        $phone = preg_replace('/\D/', '', $this->data[$field]);
        if (strlen($phone) < 10 || strlen($phone) > 11) {
            $this->errors[$field] = "O {$label} deve ter 10 ou 11 dígitos.";
        }
        return $this;
    }

    /**
     * Check if validation passed
     */
    public function passes(): bool
    {
        return empty($this->errors);
    }

    /**
     * Check if validation failed
     */
    public function fails(): bool
    {
        return !empty($this->errors);
    }

    /**
     * Get validation errors
     */
    public function errors(): array
    {
        return $this->errors;
    }

    /**
     * Get first error message
     */
    public function firstError(): ?string
    {
        return !empty($this->errors) ? reset($this->errors) : null;
    }
}
