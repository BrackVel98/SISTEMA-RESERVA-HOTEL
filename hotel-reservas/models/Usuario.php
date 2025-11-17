<?php
/**
 * Modelo Usuario
 */

require_once __DIR__ . '/../core/Model.php';

class Usuario extends Model
{
    protected $table = 'usuarios';
    
    /**
     * Buscar usuario por email (ESTE ES EL MÉTODO QUE FALTABA)
     */
    public function buscarPorEmail($email)
    {
        return $this->findOne(['email' => $email]);
    }
    
    /**
     * Alias de buscarPorEmail para compatibilidad
     */
    public function findByEmail($email)
    {
        return $this->buscarPorEmail($email);
    }
    
    /**
     * Verificar contraseña
     */
    public function verificarPassword($password, $hash)
    {
        return password_verify($password, $hash);
    }
    
    /**
     * Verificar si email existe
     */
    public function emailExiste($email, $excluir_id = null)
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE email = ?";
        $params = [$email];
        
        if ($excluir_id) {
            $sql .= " AND id != ?";
            $params[] = $excluir_id;
        }
        
        $result = $this->queryOne($sql, $params);
        return $result ? (int)$result['total'] > 0 : false;
    }
    
    /**
     * Crear usuario con password hasheado
     */
    public function crearUsuario($data)
    {
        // Hashear password antes de guardar
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        
        return $this->create($data);
    }
}