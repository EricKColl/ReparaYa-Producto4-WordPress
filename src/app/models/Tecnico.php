<?php

require_once __DIR__ . '/../../core/Model.php';

class Tecnico extends Model
{
    public function getAll(): array
    {
        $stmt = $this->db->query("
            SELECT 
                t.id,
                t.usuario_id,
                t.nombre_completo,
                t.especialidad_id,
                t.disponible,
                e.nombre_especialidad,
                u.nombre AS usuario_nombre,
                u.email AS usuario_email
            FROM tecnicos t
            LEFT JOIN especialidades e ON t.especialidad_id = e.id
            LEFT JOIN usuarios u ON t.usuario_id = u.id
            ORDER BY t.id ASC
        ");

        return $stmt->fetchAll();
    }

    public function getById(int $id): array|false
    {
        $stmt = $this->db->prepare("
            SELECT 
                id,
                usuario_id,
                nombre_completo,
                especialidad_id,
                disponible
            FROM tecnicos
            WHERE id = :id
        ");

        $stmt->execute([
            'id' => $id
        ]);

        return $stmt->fetch();
    }

    public function getByUsuarioId(int $usuarioId): array|false
    {
        $stmt = $this->db->prepare("
            SELECT 
                t.id,
                t.usuario_id,
                t.nombre_completo,
                t.especialidad_id,
                t.disponible,
                e.nombre_especialidad
            FROM tecnicos t
            LEFT JOIN especialidades e ON t.especialidad_id = e.id
            WHERE t.usuario_id = :usuario_id
            LIMIT 1
        ");

        $stmt->execute([
            'usuario_id' => $usuarioId
        ]);

        return $stmt->fetch();
    }

    public function create(int $usuarioId, string $nombreCompleto, int $especialidadId, int $disponible): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO tecnicos (usuario_id, nombre_completo, especialidad_id, disponible)
            VALUES (:usuario_id, :nombre_completo, :especialidad_id, :disponible)
        ");

        return $stmt->execute([
            'usuario_id' => $usuarioId,
            'nombre_completo' => $nombreCompleto,
            'especialidad_id' => $especialidadId,
            'disponible' => $disponible
        ]);
    }

    public function update(int $id, int $usuarioId, string $nombreCompleto, int $especialidadId, int $disponible): bool
    {
        $stmt = $this->db->prepare("
            UPDATE tecnicos
            SET usuario_id = :usuario_id,
                nombre_completo = :nombre_completo,
                especialidad_id = :especialidad_id,
                disponible = :disponible
            WHERE id = :id
        ");

        return $stmt->execute([
            'id' => $id,
            'usuario_id' => $usuarioId,
            'nombre_completo' => $nombreCompleto,
            'especialidad_id' => $especialidadId,
            'disponible' => $disponible
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("
            DELETE FROM tecnicos
            WHERE id = :id
        ");

        return $stmt->execute([
            'id' => $id
        ]);
    }

    public function getDisponibles(): array
    {
        $stmt = $this->db->query("
            SELECT 
                t.id,
                t.usuario_id,
                t.nombre_completo,
                t.especialidad_id,
                t.disponible,
                e.nombre_especialidad
            FROM tecnicos t
            LEFT JOIN especialidades e ON t.especialidad_id = e.id
            WHERE t.disponible = 1
            ORDER BY t.nombre_completo ASC
        ");

        return $stmt->fetchAll();
    }

    public function getDisponiblesByEspecialidad(int $especialidadId): array
    {
        $stmt = $this->db->prepare("
            SELECT 
                t.id,
                t.usuario_id,
                t.nombre_completo,
                t.especialidad_id,
                t.disponible,
                e.nombre_especialidad
            FROM tecnicos t
            LEFT JOIN especialidades e ON t.especialidad_id = e.id
            WHERE t.disponible = 1
              AND t.especialidad_id = :especialidad_id
            ORDER BY t.nombre_completo ASC
        ");

        $stmt->execute([
            'especialidad_id' => $especialidadId
        ]);

        return $stmt->fetchAll();
    }
}