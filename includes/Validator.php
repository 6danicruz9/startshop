<?php
declare(strict_types=1);
class Validator {
    private array $errors = [];
    private array $cleaned = [];
    public static function schema(array $data, array $rules): self {
        $v = new self();
        foreach ($rules as $field => $ruleSet) {
            $val = isset($data[$field]) && is_string($data[$field]) ? trim($data[$field]) : ($data[$field] ?? null);
            foreach (explode('|', $ruleSet) as $rule) {
                [$rName, $param] = array_pad(explode(':', $rule, 2), 2, null);
                if ($rName === 'required' && ($val === null || $val === '')) { $v->errors[$field][] = 'Requerido'; continue 2; }
                if ($val === null || $val === '') continue;
                if ($rName === 'email' && !filter_var($val, FILTER_VALIDATE_EMAIL)) $v->errors[$field][] = 'Email inválido';
                if ($rName === 'min' && mb_strlen((string)$val) < (int)$param) $v->errors[$field][] = 'Demasiado corto';
                if ($rName === 'numeric' && !is_numeric($val)) $v->errors[$field][] = 'Numérico requerido';
            }
            $v->cleaned[$field] = $val;
        }
        return $v;
    }
    public function fails(): bool { return !empty($this->errors); }
    public function validated(): array { return $this->cleaned; }
}