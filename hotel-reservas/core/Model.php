<?php
/**
 * Clase base Model
 * Proporciona métodos CRUD básicos para todos los modelos
 */
class Model
{
    protected $table;
    protected $db;
    
    public function __construct()
    {
        $this->db = Database::getInstance();
    }
    
    /**
     * Obtener todos los registros
     * 
     * @param string $orderBy Orden (ej: 'nombre ASC')
     * @return array
     */
    public function all($orderBy = 'id DESC')
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY {$orderBy}";
        return $this->query($sql, []);
    }
    
    /**
     * Buscar por ID
     * 
     * @param int $id
     * @return array|null
     */
    public function find($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        return $this->queryOne($sql, [$id]);
    }
    
    /**
     * Buscar por campo específico
     * 
     * @param string $field Nombre del campo
     * @param mixed $value Valor a buscar
     * @return array|null
     */
    public function findBy($field, $value)
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$field} = ? LIMIT 1";
        return $this->queryOne($sql, [$value]);
    }
    
    /**
     * Buscar un registro por condiciones
     * 
     * @param array $conditions ['campo' => 'valor']
     * @return array|null
     */
    public function findOne($conditions)
    {
        $where = [];
        $params = [];
        
        foreach ($conditions as $field => $value) {
            $where[] = "{$field} = ?";
            $params[] = $value;
        }
        
        $sql = "SELECT * FROM {$this->table} WHERE " . implode(' AND ', $where) . " LIMIT 1";
        return $this->queryOne($sql, $params);
    }
    
    /**
     * Buscar múltiples registros por condiciones
     * 
     * @param array $conditions ['campo' => 'valor']
     * @param string $orderBy Orden
     * @return array
     */
    public function findAll($conditions, $orderBy = 'id DESC')
    {
        if (empty($conditions)) {
            return $this->all($orderBy);
        }
        
        $where = [];
        $params = [];
        
        foreach ($conditions as $field => $value) {
            $where[] = "{$field} = ?";
            $params[] = $value;
        }
        
        $sql = "SELECT * FROM {$this->table} WHERE " . implode(' AND ', $where) . " ORDER BY {$orderBy}";
        return $this->query($sql, $params);
    }
    
    /**
     * Crear un nuevo registro
     * 
     * @param array $data
     * @return int|bool ID del registro creado o false
     */
    public function create($data)
    {
        // NO agregar timestamps automáticamente
        // Solo usar los datos que vienen en $data
        
        $fields = array_keys($data);
        $values = array_values($data);
        $placeholders = array_fill(0, count($fields), '?');
        
        $sql = sprintf(
            "INSERT INTO {$this->table} (%s) VALUES (%s)",
            implode(', ', $fields),
            implode(', ', $placeholders)
        );
        
        if ($this->execute($sql, $values)) {
            return $this->lastInsertId();
        }
        
        return false;
    }
    
    /**
     * Actualizar un registro
     * 
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update($id, $data)
    {
        // NO agregar updated_at automáticamente
        // Solo usar los datos que vienen en $data
        
        $fields = [];
        $values = [];
        
        foreach ($data as $field => $value) {
            $fields[] = "{$field} = ?";
            $values[] = $value;
        }
        
        $values[] = $id;
        
        $sql = sprintf(
            "UPDATE {$this->table} SET %s WHERE id = ?",
            implode(', ', $fields)
        );
        
        return $this->execute($sql, $values);
    }
    
    /**
     * Eliminar un registro
     * 
     * @param int $id
     * @return bool
     */
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        return $this->execute($sql, [$id]);
    }
    
    /**
     * Contar registros
     * 
     * @param array $conditions
     * @return int
     */
    public function count($conditions = [])
    {
        if (empty($conditions)) {
            $sql = "SELECT COUNT(*) as total FROM {$this->table}";
            $params = [];
        } else {
            $where = [];
            $params = [];
            
            foreach ($conditions as $field => $value) {
                $where[] = "{$field} = ?";
                $params[] = $value;
            }
            
            $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE " . implode(' AND ', $where);
        }
        
        $result = $this->queryOne($sql, $params);
        return $result ? (int)$result['total'] : 0;
    }
    
    /**
     * Ejecutar consulta SQL que retorna múltiples resultados
     * 
     * @param string $sql
     * @param array $params
     * @return array
     */
    protected function query($sql, $params = [])
    {
        return Database::query($sql, $params);
    }
    
    /**
     * Ejecutar consulta SQL que retorna un solo resultado
     * 
     * @param string $sql
     * @param array $params
     * @return array|null
     */
    protected function queryOne($sql, $params = [])
    {
        return Database::queryOne($sql, $params);
    }
    
    /**
     * Ejecutar consulta SQL sin retorno
     * 
     * @param string $sql
     * @param array $params
     * @return bool
     */
    protected function execute($sql, $params = [])
    {
        return Database::execute($sql, $params);
    }
    
    /**
     * Obtener último ID insertado
     * 
     * @return int
     */
    protected function lastInsertId()
    {
        return Database::lastInsertId();
    }
    
    /**
     * Verificar si un registro existe
     * 
     * @param int $id
     * @return bool
     */
    public function exists($id)
    {
        $result = $this->find($id);
        return $result !== null;
    }
}