<?php

require_once __DIR__ . '/../../core/Model.php';

class Usuario extends Model
{
    protected string $table = 'usuarios';

    public ?int $id;
    public string $nombre;
    public string $email;
    public string $password;
    public string $rol;
    public ?string $telefono;
    public ?string $created_at;

    public function __construct(array $data = [])
    {
        parent::__construct();

        $this->id = $data['id'] ?? null;
        $this->nombre = $data['nombre'] ?? '';
        $this->email = $data['email'] ?? '';
        $this->password = $data['password'] ?? '';
        $this->rol = $data['rol'] ?? 'particular';
        $this->telefono = $data['telefono'] ?? null;
        $this->created_at = $data['created_at'] ?? null;
    }

    public function getAll(): array
    {
        $sql = "SELECT * FROM usuarios ORDER BY id ASC";
        $stmt = $this->db->query($sql);

        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array
    {
        $sql = "SELECT * FROM usuarios WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);

        $usuario = $stmt->fetch();

        return $usuario ?: null;
    }

    public function getByEmail(string $email): ?array
    {
        $sql = "SELECT * FROM usuarios WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['email' => $email]);

        $usuario = $stmt->fetch();

        return $usuario ?: null;
    }

    public function getByEmailExcludingId(string $email, int $id): ?array
    {
        $sql = "SELECT * FROM usuarios WHERE email = :email AND id != :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'email' => $email,
            'id' => $id
        ]);

        $usuario = $stmt->fetch();

        return $usuario ?: null;
    }

    public function create(array $data): bool
    {
        $sql = "INSERT INTO usuarios (nombre, email, password, rol, telefono)
                VALUES (:nombre, :email, :password, :rol, :telefono)";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'nombre' => $data['nombre'],
            'email' => $data['email'],
            'password' => $data['password'],
            'rol' => $data['rol'] ?? 'particular',
            'telefono' => !empty($data['telefono']) ? $data['telefono'] : null
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE usuarios
                SET nombre = :nombre,
                    email = :email,
                    password = :password,
                    rol = :rol,
                    telefono = :telefono
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'id' => $id,
            'nombre' => $data['nombre'],
            'email' => $data['email'],
            'password' => $data['password'],
            'rol' => $data['rol'] ?? 'particular',
            'telefono' => !empty($data['telefono']) ? $data['telefono'] : null
        ]);
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM usuarios WHERE id = :id";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'id' => $id
        ]);
    }

    public function getClientes(): array
    {
        $stmt = $this->db->query("
            SELECT id, nombre, email, telefono
            FROM usuarios
            WHERE rol = 'particular'
            ORDER BY nombre ASC
        ");

        return $stmt->fetchAll();
    }

    public function getUsuariosTecnicosDisponibles(?int $tecnicoActualId = null): array
    {
        if ($tecnicoActualId !== null) {
            $stmt = $this->db->prepare("
                SELECT 
                    u.id,
                    u.nombre,
                    u.email
                FROM usuarios u
                LEFT JOIN tecnicos t ON t.usuario_id = u.id
                WHERE u.rol = 'tecnico'
                  AND (t.id IS NULL OR t.id = :tecnico_actual_id)
                ORDER BY u.nombre ASC
            ");

            $stmt->execute([
                'tecnico_actual_id' => $tecnicoActualId
            ]);

            return $stmt->fetchAll();
        }

        $stmt = $this->db->query("
            SELECT 
                u.id,
                u.nombre,
                u.email
            FROM usuarios u
            LEFT JOIN tecnicos t ON t.usuario_id = u.id
            WHERE u.rol = 'tecnico'
              AND t.id IS NULL
            ORDER BY u.nombre ASC
        ");

        return $stmt->fetchAll();
    }
}