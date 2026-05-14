<?php

require_once __DIR__ . '/../../core/Model.php';

class Incidencia extends Model
{
    public function getAll(bool $incluirCanceladas = true): array
    {
        $sql = "
            SELECT 
                i.id,
                i.localizador,
                i.cliente_id,
                i.tecnico_id,
                i.descripcion,
                i.especialidad_id,
                i.direccion,
                i.telefono_contacto,
                i.fecha_servicio,
                i.tipo_urgencia,
                i.estado,
                i.created_at,
                u.nombre AS cliente_nombre,
                u.email AS cliente_email,
                t.nombre_completo AS tecnico_nombre,
                e.nombre_especialidad
            FROM incidencias i
            JOIN usuarios u ON i.cliente_id = u.id
            LEFT JOIN tecnicos t ON i.tecnico_id = t.id
            LEFT JOIN especialidades e ON i.especialidad_id = e.id
        ";

        if (!$incluirCanceladas) {
            $sql .= " WHERE i.estado <> 'Cancelada' ";
        }

        $sql .= " ORDER BY i.fecha_servicio ASC ";

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function getById(int $id): array|false
    {
        $stmt = $this->db->prepare("
            SELECT i.*, u.nombre AS cliente_nombre
            FROM incidencias i
            JOIN usuarios u ON i.cliente_id = u.id
            WHERE i.id = :id
        ");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function getByIdAndClienteId(int $id, int $clienteId): array|false
    {
        $stmt = $this->db->prepare("
            SELECT i.*
            FROM incidencias i
            WHERE i.id = :id
              AND i.cliente_id = :cliente_id
            LIMIT 1
        ");

        $stmt->execute([
            'id' => $id,
            'cliente_id' => $clienteId
        ]);

        return $stmt->fetch();
    }

    public function getByTecnicoId(int $tecnicoId): array
    {
        $stmt = $this->db->prepare("
            SELECT
                i.id,
                i.localizador,
                i.descripcion,
                i.direccion,
                i.telefono_contacto,
                i.fecha_servicio,
                i.tipo_urgencia,
                i.estado,
                u.nombre AS cliente_nombre,
                u.email AS cliente_email,
                e.nombre_especialidad
            FROM incidencias i
            JOIN usuarios u ON i.cliente_id = u.id
            LEFT JOIN especialidades e ON i.especialidad_id = e.id
            WHERE i.tecnico_id = :tecnico_id
              AND i.estado <> 'Cancelada'
            ORDER BY i.fecha_servicio ASC
        ");

        $stmt->execute([
            'tecnico_id' => $tecnicoId
        ]);

        return $stmt->fetchAll();
    }

    public function getByClienteId(int $clienteId): array
    {
        $stmt = $this->db->prepare("
            SELECT
                i.id,
                i.cliente_id,
                i.localizador,
                i.descripcion,
                i.direccion,
                i.telefono_contacto,
                i.fecha_servicio,
                i.tipo_urgencia,
                i.estado,
                i.created_at,
                e.nombre_especialidad,
                t.nombre_completo AS tecnico_nombre
            FROM incidencias i
            LEFT JOIN especialidades e ON i.especialidad_id = e.id
            LEFT JOIN tecnicos t ON i.tecnico_id = t.id
            WHERE i.cliente_id = :cliente_id
            ORDER BY i.fecha_servicio DESC, i.id DESC
        ");

        $stmt->execute([
            'cliente_id' => $clienteId
        ]);

        return $stmt->fetchAll();
    }

    private function generarLocalizador(): string
    {
        return 'INC-' . strtoupper(substr(uniqid(), -6));
    }

    public function create(array $datos): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO incidencias 
                (
                    localizador,
                    cliente_id,
                    especialidad_id,
                    descripcion,
                    direccion,
                    telefono_contacto,
                    fecha_servicio,
                    tipo_urgencia,
                    estado
                )
            VALUES 
                (
                    :localizador,
                    :cliente_id,
                    :especialidad_id,
                    :descripcion,
                    :direccion,
                    :telefono_contacto,
                    :fecha_servicio,
                    :tipo_urgencia,
                    'Pendiente'
                )
        ");

        return $stmt->execute([
            'localizador'       => $this->generarLocalizador(),
            'cliente_id'        => $datos['cliente_id'],
            'especialidad_id'   => $datos['especialidad_id'],
            'descripcion'       => $datos['descripcion'],
            'direccion'         => $datos['direccion'],
            'telefono_contacto' => $datos['telefono_contacto'],
            'fecha_servicio'    => $datos['fecha_servicio'],
            'tipo_urgencia'     => $datos['tipo_urgencia'],
        ]);
    }

    public function update(int $id, array $datos): bool
    {
        $stmt = $this->db->prepare("
            UPDATE incidencias SET
                especialidad_id   = :especialidad_id,
                descripcion       = :descripcion,
                direccion         = :direccion,
                telefono_contacto = :telefono_contacto,
                fecha_servicio    = :fecha_servicio,
                tipo_urgencia     = :tipo_urgencia,
                estado            = :estado
            WHERE id = :id
        ");

        return $stmt->execute([
            'id'                => $id,
            'especialidad_id'   => $datos['especialidad_id'],
            'descripcion'       => $datos['descripcion'],
            'direccion'         => $datos['direccion'],
            'telefono_contacto' => $datos['telefono_contacto'],
            'fecha_servicio'    => $datos['fecha_servicio'],
            'tipo_urgencia'     => $datos['tipo_urgencia'],
            'estado'            => $datos['estado'],
        ]);
    }

    public function asignarTecnico(int $id, int $tecnicoId): bool
    {
        $stmt = $this->db->prepare("
            UPDATE incidencias
            SET tecnico_id = :tecnico_id, estado = 'Asignada'
            WHERE id = :id
              AND estado NOT IN ('Cancelada', 'Finalizada')
        ");

        return $stmt->execute([
            'id'         => $id,
            'tecnico_id' => $tecnicoId
        ]);
    }

    public function cancel(int $id): bool
    {
        $stmt = $this->db->prepare("
            UPDATE incidencias
            SET estado = 'Cancelada', tecnico_id = NULL
            WHERE id = :id
        ");

        return $stmt->execute(['id' => $id]);
    }

    public function deletePermanent(int $id): bool
    {
        $stmt = $this->db->prepare("
            DELETE FROM incidencias
            WHERE id = :id
        ");

        return $stmt->execute(['id' => $id]);
    }
}