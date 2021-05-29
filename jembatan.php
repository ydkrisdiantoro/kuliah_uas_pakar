<?php
$nama = $_POST["nama"];
$jenis_kelamin = $_POST["jenis_kelamin"];
$usia = $_POST["usia"];
$surel = $_POST["surel"];
$gejala = array();
for ($i = 1; $i <= 8; $i++) {
    $gejala[] = $_POST["gejala" . $i];
}
$semua_gejala = implode("_", $gejala);
header("Location: index.php?nama=" . $nama . "&jenis_kelamin=" . $jenis_kelamin . "&usia=" . $usia . "&surel=" . $surel . "&legalitas=1&gejala=" . $semua_gejala);
