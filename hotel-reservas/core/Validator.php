<?php
// filepath: d:\Software\Apache24\htdocs\hotel-reservas\core\Validator.php

/**
 * Clase Validator - Validación de datos
 */
class Validator
{
    private $data = [];
    private $rules = [];
    private $errors = [];
    private $messages = [];
    
    public function __construct($data)
    {
        $this->data = $data;
    }
    
    /**
     * Establecer reglas de validación
     */
    public function rules($rules)
    {
        $this->rules = $rules;
        return $this;
    }
    
    /**
     * Establecer mensajes personalizados
     */
    public function messages($messages)
    {
        $this->messages = $messages;
        return $this;
    }
    
    /**
     * Validar datos
     */
    public function validate()
    {
        foreach ($this->rules as $field => $rules) {
            $rulesArray = explode('|', $rules);
            
            foreach ($rulesArray as $rule) {
                $this->applyRule($field, $rule);
            }
        }
        
        return empty($this->errors);
    }
    
    /**
     * Aplicar regla de validación
     */
    private function applyRule($field, $rule)
    {
        $value = $this->data[$field] ?? null;
        
        // Separar regla y parámetros
        $ruleParts = explode(':', $rule);
        $ruleName = $ruleParts[0];
        $ruleParams = isset($ruleParts[1]) ? explode(',', $ruleParts[1]) : [];
        
        switch ($ruleName) {
            case 'required':
                if (empty($value) && $value !== '0') {
                    $this->addError($field, 'required');
                }
                break;
                
            case 'email':
                if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addError($field, 'email');
                }
                break;
                
            case 'min':
                $min = $ruleParams[0];
                if (!empty($value) && strlen($value) < $min) {
                    $this->addError($field, 'min', ['min' => $min]);
                }
                break;
                
            case 'max':
                $max = $ruleParams[0];
                if (!empty($value) && strlen($value) > $max) {
                    $this->addError($field, 'max', ['max' => $max]);
                }
                break;
                
            case 'numeric':
                if (!empty($value) && !is_numeric($value)) {
                    $this->addError($field, 'numeric');
                }
                break;
                
            case 'integer':
                if (!empty($value) && !filter_var($value, FILTER_VALIDATE_INT)) {
                    $this->addError($field, 'integer');
                }
                break;
                
            case 'positive':
                if (!empty($value) && (!is_numeric($value) || $value <= 0)) {
                    $this->addError($field, 'positive');
                }
                break;
                
            case 'date':
                if (!empty($value) && !$this->validateDate($value)) {
                    $this->addError($field, 'date');
                }
                break;
                
            case 'after':
                $compareDate = $ruleParams[0];
                if (!empty($value) && !$this->validateAfter($value, $this->data[$compareDate] ?? null)) {
                    $this->addError($field, 'after', ['date' => $compareDate]);
                }
                break;
                
            case 'before':
                $compareDate = $ruleParams[0];
                if (!empty($value) && !$this->validateBefore($value, $this->data[$compareDate] ?? null)) {
                    $this->addError($field, 'before', ['date' => $compareDate]);
                }
                break;
                
            case 'unique':
                // Formato: unique:tabla,columna,exceptId
                $table = $ruleParams[0];
                $column = $ruleParams[1] ?? $field;
                $exceptId = $ruleParams[2] ?? null;
                
                if (!empty($value) && !$this->validateUnique($table, $column, $value, $exceptId)) {
                    $this->addError($field, 'unique');
                }
                break;
                
            case 'confirmed':
                $confirmField = $field . '_confirmation';
                if ($value !== ($this->data[$confirmField] ?? null)) {
                    $this->addError($field, 'confirmed');
                }
                break;
                
            case 'in':
                // Formato: in:value1,value2,value3
                if (!empty($value) && !in_array($value, $ruleParams)) {
                    $this->addError($field, 'in', ['values' => implode(', ', $ruleParams)]);
                }
                break;
                
            case 'exists':
                // Formato: exists:tabla,columna
                $table = $ruleParams[0];
                $column = $ruleParams[1] ?? 'id';
                
                if (!empty($value) && !$this->validateExists($table, $column, $value)) {
                    $this->addError($field, 'exists');
                }
                break;
        }
    }
    
    /**
     * Agregar error
     */
    private function addError($field, $rule, $params = [])
    {
        $message = $this->getErrorMessage($field, $rule, $params);
        $this->errors[$field][] = $message;
    }
    
    /**
     * Obtener mensaje de error
     */
    private function getErrorMessage($field, $rule, $params = [])
    {
        $key = "{$field}.{$rule}";
        
        if (isset($this->messages[$key])) {
            return $this->messages[$key];
        }
        
        // Convertir field a nombre legible
        $fieldName = ucfirst(str_replace('_', ' ', $field));
        
        // Mensajes por defecto
        $defaultMessages = [
            'required' => "{$fieldName} es obligatorio.",
            'email' => "{$fieldName} debe ser un email válido.",
            'min' => "{$fieldName} debe tener al menos {$params['min']} caracteres.",
            'max' => "{$fieldName} no puede tener más de {$params['max']} caracteres.",
            'numeric' => "{$fieldName} debe ser numérico.",
            'integer' => "{$fieldName} debe ser un número entero.",
            'positive' => "{$fieldName} debe ser un número positivo.",
            'date' => "{$fieldName} debe ser una fecha válida (YYYY-MM-DD).",
            'after' => "{$fieldName} debe ser posterior a {$params['date']}.",
            'before' => "{$fieldName} debe ser anterior a {$params['date']}.",
            'unique' => "El valor de {$fieldName} ya está registrado.",
            'confirmed' => "La confirmación de {$fieldName} no coincide.",
            'in' => "{$fieldName} debe ser uno de: {$params['values']}.",
            'exists' => "El valor de {$fieldName} no existe."
        ];
        
        return $defaultMessages[$rule] ?? "{$fieldName} no es válido.";
    }
    
    /**
     * Validar fecha
     */
    private function validateDate($date)
    {
        $d = DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }
    
    /**
     * Validar fecha posterior
     */
    private function validateAfter($date, $compareDate)
    {
        if (empty($compareDate)) return false;
        return strtotime($date) > strtotime($compareDate);
    }
    
    /**
     * Validar fecha anterior
     */
    private function validateBefore($date, $compareDate)
    {
        if (empty($compareDate)) return false;
        return strtotime($date) < strtotime($compareDate);
    }
    
    /**
     * Validar unicidad en base de datos
     * CORREGIDO: Usar Database::queryOne con array de parámetros
     */
    private function validateUnique($table, $column, $value, $exceptId = null)
    {
        try {
            $sql = "SELECT COUNT(*) as total FROM {$table} WHERE {$column} = ?";
            $params = [$value];
            
            if ($exceptId) {
                $sql .= " AND id != ?";
                $params[] = $exceptId;
            }
            
            // ✅ CORRECTO: Usar Database::queryOne con array
            $result = Database::queryOne($sql, $params);
            
            return $result && $result['total'] == 0;
        } catch (Exception $e) {
            error_log("Error en validateUnique: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Validar que existe en base de datos
     * NUEVO: Validar que un registro existe
     */
    private function validateExists($table, $column, $value)
    {
        try {
            $sql = "SELECT COUNT(*) as total FROM {$table} WHERE {$column} = ?";
            
            // ✅ CORRECTO: Usar Database::queryOne con array
            $result = Database::queryOne($sql, [$value]);
            
            return $result && $result['total'] > 0;
        } catch (Exception $e) {
            error_log("Error en validateExists: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Obtener errores
     */
    public function errors()
    {
        return $this->errors;
    }
    
    /**
     * Verificar si hay errores
     */
    public function fails()
    {
        return !empty($this->errors);
    }
    
    /**
     * Verificar si pasó la validación
     */
    public function passes()
    {
        return empty($this->errors);
    }
    
    /**
     * Obtener primer error de un campo
     */
    public function first($field)
    {
        return $this->errors[$field][0] ?? null;
    }
    
    /**
     * Obtener todos los errores de un campo
     */
    public function get($field)
    {
        return $this->errors[$field] ?? [];
    }
    
    /**
     * Verificar si un campo tiene errores
     */
    public function has($field)
    {
        return isset($this->errors[$field]);
    }
    
    /**
     * Limpiar errores
     */
    public function reset()
    {
        $this->errors = [];
        return $this;
    }
    
    /**
     * Método estático para validar rápidamente
     */
    public static function make($data, $rules, $messages = [])
    {
        $validator = new self($data);
        $validator->rules($rules);
        
        if (!empty($messages)) {
            $validator->messages($messages);
        }
        
        $validator->validate();
        
        return $validator;
    }
}