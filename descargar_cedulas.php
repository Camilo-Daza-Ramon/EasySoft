<?php
require __DIR__ . '/vendor/autoload.php'; // Carga las dependencias
$app = require_once __DIR__ . '/bootstrap/app.php'; // Inicializa Laravel
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

// 📌 Conectar a SQL Server
$serverName = "CAMILO-DAZA\\SQLEXPRESS";
$database = "Sisteco";
$username = "sa";
$password = "ajdm0918";

try {
    $conn = new PDO("sqlsrv:Server=$serverName;Database=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Conexión exitosa.\n";
} catch (PDOException $e) {
    die("❌ Error de conexión: " . $e->getMessage());
}

// 📌 Consultar nombres de archivos en la tabla `Clientes`
$sql = "SELECT ArchivoCedulaCara2 FROM Clientes WHERE ArchivoCedulaCara2 IS NOT NULL";
$stmt = $conn->prepare($sql);
$stmt->execute();
$imagenes = $stmt->fetchAll(PDO::FETCH_COLUMN);

// 📌 Definir carpeta destino en el escritorio
$destino = getenv("USERPROFILE") . '\\Desktop\\cedulas-clientes';
if (!file_exists($destino)) {
    mkdir($destino, 0777, true);
}

// 📌 Recuperar las imágenes desde almacenamiento y guardarlas en el escritorio
foreach ($imagenes as $archivo) {
    $rutaImagen = Storage::disk('public')->path($archivo); // Ajusta 'public' si el disco es diferente
    $nombreArchivo = basename($archivo);
    $destinoArchivo = $destino . "\\" . $nombreArchivo;

    if (file_exists($rutaImagen)) {
        copy($rutaImagen, $destinoArchivo);
        echo "✅ Imagen guardada: $nombreArchivo\n";
    } else {
        echo "❌ No encontrada: $rutaImagen\n";
    }
}

echo "🚀 Descarga completada.\n";

?>