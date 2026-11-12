<?php
echo "<h1>Test Configuration Upload</h1>";
echo "upload_max_filesize: " . ini_get('upload_max_filesize') . "<br>";
echo "post_max_size: " . ini_get('post_max_size') . "<br>";
echo "max_execution_time: " . ini_get('max_execution_time') . "<br>";
echo "memory_limit: " . ini_get('memory_limit') . "<br>";
echo "upload_tmp_dir: " . ini_get('upload_tmp_dir') . "<br>";

$storageWritable = is_writable(__DIR__ . '/../storage/app/temp');
echo "Storage writable: " . ($storageWritable ? "✅ OUI" : "❌ NON") . "<br>";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['test'])) {
    echo "<hr><h2>Test Upload</h2>";
    echo "Erreur: " . $_FILES['test']['error'] . "<br>";
    echo "Taille: " . $_FILES['test']['size'] . " bytes<br>";
    var_dump($_FILES['test']);
}
?>

<form method="POST" enctype="multipart/form-data">
    <input type="file" name="test">
    <button>Tester Upload</button>
</form>
