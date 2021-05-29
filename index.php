<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../../framework/bootstrap-4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <style>
        body {
            background: #efefef;
        }

        label {
            font-style: italic;
        }

        strong {
            font-weight: 500;
        }
    </style>
</head>

<body>
    <div class="container my-5">
        <?php
        $gejala = array(
            'Tubuh lemas',
            'Sakit Kepala',
            'Pucat',
            'Demam',
            'Mual atau muntah-muntah',
            'Nyeri pada ulu hati',
            'Nyeri panggul hingga paha',
            'Keluar darah dari hidung/mimisan'
        );

        // Nilai CF Pakar
        $cfpakar = array(
            0.3, 0.3, 0.6, 0.4, 0.4, 0.8, 0.5, 0.8
        );
        $nilai_user = array(
            array('Tidak', 0),
            array('Tidak Tahu', 0.2),
            array('Sedikit Yakin', 0.4),
            array('Cukup Yakin', 0.6),
            array('Yakin', 0.8),
            array('Sangat Yakin', 1)
        );
        echo "<h2 class='display-4 text-center'>Diagnosa Penyakit Anemia</h2>";
        echo "<p class='lead font-weight-300 text-center mb-5 mx-md-5 px-md-5'><small>Developed by Yayan Dwi Krisdiantoro - Metode Certainty Factor (CF)</small></p>";
        ?>

        <?php
        if (isset($_GET['legalitas'])) {
            if ($_GET['legalitas'] == 1) {
                $nama = $_GET['nama'];
                $surel = $_GET['surel'];
                $jenis_kelamin = $_GET['jenis_kelamin'];
                $usia = $_GET['usia'];
                $input = array();
                // Ambil data inputan dari Pengguna
                if (isset($_GET["gejala"])) {
                    $semua_gejala = $_GET["gejala"];
                    $input = explode("_", $semua_gejala);
                }
        ?>

                <div class="row">
                    <div class="col-12 col-md-6">
                        <p class="display-4 text-primary text-center" style="font-size: 24pt;">Hasil Analisis Gejala dengan Metode CF</p>
                        <div class="card mb-4">
                            <div class="card-body">
                                <p class="lead"><strong>Identitas Pengguna:</strong></p>
                                <table class="table table-striped mb-0">
                                    <tr>
                                        <td>Nama Lengkap</td>
                                        <td><?= $nama ?></td>
                                    </tr>
                                    <tr>
                                        <td>Alamat Email</td>
                                        <td><?= $surel ?></td>
                                    </tr>
                                    <tr>
                                        <td>Jenis Kelamin</td>
                                        <td><?= $jenis_kelamin ?></td>
                                    </tr>
                                    <tr>
                                        <td>Usia</td>
                                        <td><?= $usia . ' tahun' ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="card mb-4">
                            <div class="card-body">
                                <p class="lead"><strong>Keluhan Gejala:</strong></p>
                                <table class="table table-striped mb-0">
                                    <?php
                                    for ($i = 0; $i < count($gejala); $i++) {
                                        echo '<tr>';
                                        echo '<td>';
                                        echo $gejala[$i];
                                        echo '</td>';
                                        echo '<td>';
                                        foreach ($nilai_user as $nilai) {
                                            if ($nilai[1] == $input[$i]) {
                                                echo $nilai[0];
                                            }
                                        }
                                        echo '</td>';
                                        echo '</tr>';
                                    }
                                    ?>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <p class="lead mb-0"><strong>Probabilitas Akhir:</strong></p>
                                <?php
                                $cfuser = array();
                                for ($i = 0; $i < count($input); $i++) {
                                    // echo "<td>" . $input[$i] . "</td>";
                                    $cfuser[] = $input[$i];
                                }

                                $CFHEi = array();
                                for ($i = 0; $i < count($input); $i++) {
                                    $CFHE = $cfpakar[$i] * $cfuser[$i];
                                    $CFHEi[] = $CFHE;
                                }
                                // CF COmbine
                                $CFcombine_old = 0;
                                $CFcombine_now = 0;
                                for ($i = 0; $i < count($CFHEi); $i++) {
                                    $t = $i + 1;
                                    if ($t == count($CFHEi)) {
                                    } else {
                                    }
                                    if ($i + 2 <= count($CFHEi)) {
                                        if ($i == 0) {
                                            $CFcombine_initial = $CFHEi[$i] + ($CFHEi[$i + 1] * (1 - $CFHEi[$i]));
                                            $CFcombine_now = round($CFcombine_initial, 2);
                                            $CFcombine_old = $CFcombine_now;
                                        } else {
                                            $CFcombine_now = round($CFcombine_old + ($CFHEi[$i + 1] * (1 - $CFcombine_old)), 2);
                                            $CFcombine_old = $CFcombine_now;
                                        }
                                    }
                                }
                                $prosentase = $CFcombine_now * 100;
                                echo '<h1 class="display-3 ';
                                if ($prosentase <= 50) {
                                    echo 'text-success';
                                } elseif ($prosentase > 50 && $prosentase <= 79) {
                                    echo 'text-primary';
                                } elseif ($prosentase >= 80 && $prosentase <= 99) {
                                    echo 'text-warning';
                                } elseif ($prosentase == 100) {
                                    echo 'text-danger';
                                }
                                echo '" style="font-weight: 500;">' . $prosentase . '%</h1>';
                                echo 'Hasil diagnosa: <b> ';
                                $hasil = '';
                                $angka = 0;
                                if ($prosentase <= 50) {
                                    echo "Sedikit kemungkinan atau kemungkinan kecil";
                                    $hasil = 'alert-success';
                                    $angka = 1;
                                } elseif ($prosentase >= 51 && $prosentase <= 79) {
                                    echo "Kemungkinan besar";
                                    $hasil = 'alert-primary';
                                    $angka = 2;
                                } elseif ($prosentase >= 80 && $prosentase <= 99) {
                                    echo "Yakin";
                                    $hasil = 'alert-warning';
                                    $angka = 3;
                                } elseif ($prosentase == 100) {
                                    echo "Sangat Yakin";
                                    $hasil = 'alert-danger';
                                    $angka = 4;
                                }
                                echo '</b> ' . $nama . ' mengidap penyakit Anemia';
                                echo '<div class="alert ' . $hasil . '">';
                                if ($angka == 1) {
                                    echo 'Tetap jaga kesehatan, istirahat yang cukup, dan perhatikan pola makan.';
                                }
                                if ($angka == 2) {
                                    echo 'Tetap jaga kesehatan, konsultasikan ke dokter untuk diagnosa lebih baik.';
                                }
                                if ($angka == 3) {
                                    echo 'Silahkan konsultasikan dengan dokter agar segera mendapat penanganan dini.';
                                }
                                if ($angka == 4) {
                                    echo 'Segera konsultasikan dengan dokter untuk penanganan yang tepat.';
                                }
                                echo '</div>';
                                ?>
                                <p class="mt-4"><b>Nilai Kepercayaan dalam Prosentase</b></p>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Rentang</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="table-success">
                                            <td>&gt;50%</td>
                                            <td>Sedikit kemungkinan <br>atau kemungkinan kecil</td>
                                        </tr>
                                        <tr class="table-primary">
                                            <td>50% - 79%</td>
                                            <td>Kemungkinan Besar</td>
                                        </tr>
                                        <tr class="table-warning">
                                            <td>80% - 99%</td>
                                            <td>Yakin</td>
                                        </tr>
                                        <tr class="table-danger">
                                            <td>100%</td>
                                            <td>Sangat Yakin</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="text-center">
                                    <p class="text-warning" style="font-size:2rem;">(!)</p>
                                    <p class="font-weight-300 mb-0" style="font-size:1.2rem;">Hasil di atas hanya diagnosa otomatis dari sistem. Silahkan hubungi dokter untuk diagnosa yang lebih akurat.</p>
                                    <hr>
                                    <small>Selalu jaga kesehatan dengan berolahraga dan makan makanan bergizi serta bahagia.</small>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            <?php
            }
        } else {
            ?>

            <form action="jembatan.php" method="post">
                <div class="row">

                    <!-- User Input -->
                    <div class="col-12 col-md-5">
                        <p class="display-4 text-primary text-center" style="font-size: 24pt;">Ayo Cek Prosentase Terkena Penyakit Anemia</p>
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-3 text-center">
                                        <div class="alert alert-danger">1</div>
                                        <div class="alert alert-warning">2</div>
                                        <div class="alert alert-primary">3</div>
                                    </div>
                                    <div class="col-9">
                                        <div class="alert alert-danger">Lengkapi form identitas</div>
                                        <div class="alert alert-warning">Isi form gejala</div>
                                        <div class="alert alert-primary">Klik "Cek Sekarang"</div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="card mb-4">
                            <div class="card-body">
                                <p class="lead"><strong>Identitas Diri:</strong></p>
                                <label for="nama">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" id="nama" name="nama" placeholder="Nama Lengkap" class="form-control mb-2" required>
                                <label for="jenis_kelamin">Jenis Kelamin <span class="text-danger">*</span></label>
                                <select class='form-control mb-2' name='jenis_kelamin' id="jenis_kelamin">
                                    <option value="Laki-laki">Laki-Laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                                <label for="usia">Usia Sekarang <span class="text-danger">*</span></label>
                                <input type="number" min=1 max=90 id="usia" name="usia" placeholder="Usia Sekarang" class="form-control mb-2" required>
                                <label for="surel">Alamat Email <span class="text-danger">*</span></label>
                                <input type="text" id="surel" name="surel" placeholder="Alamat Email" class="form-control mb-2" required>
                                <p class="alert alert-info">Selanjutnya, lengkapi form gejala yang anda rasakan.</p>
                            </div>
                        </div>
                    </div>
                    <!-- End of User Input -->
                    <!-- User Input -->
                    <div class="col-12 col-md-7">
                        <div class="card mb-4">
                            <div class="card-body">
                                <p class="lead"><strong>Keterangan Pilihan:</strong></p>
                                <table class="table table-striped mb-0">
                                    <?php
                                    $i = 1;
                                    foreach ($nilai_user as $nilai) {
                                        echo '<tr>';
                                        echo '<td class="py-1">';
                                        echo $i++;
                                        echo '</td>';
                                        echo '<td class="py-1">';
                                        echo $nilai[0];
                                        echo '</td>';
                                        echo '</td>';
                                    }
                                    ?>
                                </table>
                            </div>
                        </div>

                        <div class="card mb-4">
                            <div class="card-body">
                                <p class="lead"><strong>Form Gejala:</strong></p>
                                <p class="alert alert-warning">Silahkan lengkapi form identitas diri terlebih dahulu.</p>
                                <table class="table table-striped">
                                    <?php
                                    echo '<tr>';
                                    echo '<th>Nama Gejala</th>';
                                    for ($i = 1; $i <= count($nilai_user); $i++) {
                                        echo '<th class="px-1">';
                                        echo $i;
                                        echo '</th>';
                                    }
                                    echo '</tr>';

                                    for ($i = 0; $i < count($gejala); $i++) {
                                        $g = $i + 1;
                                        echo "<tr>
                                <td class='px-1'>" . $gejala[$i] . "</td>";
                                        for ($j = 0; $j < count($nilai_user); $j++) {
                                    ?>
                                            <td class="px-1">
                                                <input name="gejala<?= $g ?>" type="radio" value="<?= $nilai_user[$j][1] ?>" required>
                                            </td>
                                    <?php
                                        }
                                        echo "</tr>";
                                    }
                                    ?>
                                </table>
                                <button type="submit" class="btn btn-primary form-control">Cek Sekarang</button>
                            </div>
                        </div>
                    </div>
                    <!-- End of User Input -->

                </div>
            </form>

        <?php
        }
        ?>

        <div id="footer" class="mt-4 text-center">
            <small>Copyright &copy;2020 - <a href="mailto:y.d.krisdiantoro@gmail.com">y.d.krisdiantoro@gmail.com</a></small>
        </div>

    </div>


    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="../../framework/bootstrap-4.3.1/js/jquery.min.js"></script>
    <script src="../../framework/bootstrap-4.3.1/js/popper.min.js"></script>
    <script src="../../framework/bootstrap-4.3.1/js/bootstrap.min.js"></script>
    <script>
        $('#collapseOne').collapse({
            toggle: false
        })
    </script>
</body>

</html>