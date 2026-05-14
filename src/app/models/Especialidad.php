<?php

require_once __DIR__ . '/../../core/Model.php';

class Especialidad extends Model
{
    public function getAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM especialidades ORDER BY id ASC");
        return $stmt->fetchAll();
    }

    public function getById(int $id): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM especialidades WHERE id = :id");
        $stmt->execute([
            'id' => $id
        ]);

        return $stmt->fetch();
    }

    public function create(string $nombreEspecialidad): bool
    {
        $stmt = $this->db->prepare("INSERT INTO especialidades (nombre_especialidad) VALUES (:nombre)");
        return $stmt->execute([
            'nombre' => $nombreEspecialidad
        ]);
    }

    public function update(int $id, string $nombreEspecialidad): bool
    {
        $stmt = $this->db->prepare("UPDATE especialidades SET nombre_especialidad = :nombre WHERE id = :id");
        return $stmt->execute([
            'id' => $id,
            'nombre' => $nombreEspecialidad
        ]);
    }
    public function delete(int $id): bool
    {
    $stmt = $this->db->prepare("DELETE FROM especialidades WHERE id = :id");
    return $stmt->execute([
        'id' => $id
    ]);
    }
    

}