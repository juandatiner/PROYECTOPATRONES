<?php
class FavoritoController {
    private $conn;

    public function __construct($connection) {
        $this->conn = $connection;
    }

    public function agregarFavorito($user_id, $animal_id) {
        $sql = "INSERT INTO favoritos (animal_id, user_id) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $user_id, $animal_id);

        if ($stmt->execute()) {
            return ["success" => true, "message" => "Favorito agregado correctamente."];
        } else {
            return ["success" => false, "message" => "Error al agregar el favorito."];
        }
    }

    public function eliminarFavorito($user_id, $animal_id) {
        $sql = "DELETE FROM favoritos WHERE animal_id = ? AND user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $user_id, $animal_id);

        if ($stmt->execute()) {
            return ["success" => true, "message" => "Favorito eliminado correctamente."];
        } else {
            return ["success" => false, "message" => "Error al eliminar el favorito."];
        }
    }
}
?>
