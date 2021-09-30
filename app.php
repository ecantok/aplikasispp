<?php
// Mendefinisikan halaman url utama
define("BASEURL", "http://localhost/aplikasispp");

// Mengambil url yang dikunjungi
$url = explode('/', $_SERVER['REQUEST_URI']);
$urlSplit = explode('?', $url[2]);
$selectedUrl = $urlSplit[0];

//Menyiapkan info koneksi
$dataSourceName = 'mysql:host=localhost;dbname=dbspp2';
$username = "root";
$password = "";
$option = [
    PDO::ATTR_PERSISTENT,
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
];

//Membuat koneksi database
try {
    $conn = new PDO($dataSourceName, $username, $password, $option);
} catch (PDOException $e) {
    die("Koneksi database error: ".$e->getMessage());
}

// Cek Session login
if (!session_id()) {
    session_start();
}

//Variabel session dimulai false
$session = false;

// Jika ada session username dari proses login...
@$idUser = $_SESSION['iduser'];
@$username = $_SESSION['username'];
@$levelUser = $_SESSION['level'];
@$bio_name = $_SESSION['bioname'];
if ($idUser && $username && $levelUser && $bio_name) {
    // ... ada session (session = true)
    $session = true;
}

//Buat objek App yang akan digunakan
$app = new App();

/**
 * Kelas yang memuat kebutuhan aplikasi website pembayaran spp
 * @author David Resanto
 */
class App
{
    /**
     * Mengecek apakah level user sama
     * @param string $levelUser String level user dari proses login.
     * @param string $level level yang ingin dicek.
     * @return boolean Mengembalikan nilai true atau false.
     */
    public function cekPemissionLevel(string $levelUser, string $level = 'Admin')
    {
        if ($levelUser == $level) {
            return true;
        }
        return false;
    }

    /**
     * Mengecek apakah password sama
     * @param string $password1 Password pertama yang ingin dicek.
     * @param string $password2 Password kedua yang ingin dicek.
     * @return boolean Mengembalikan nilai true atau false.
     */
    public function confirmPassword($password1, $password2)
    {
        if ($password1 == $password2) {
            return true;
        }
        return false;
    }

    /**
     * Membuat format angka dengan pemisah koma dan titik
     * @param int $number Angka yang ingin diformat.
     * @return string Angka yang telah diformat.
     */
    public function numberformat($number)
    {
        return number_format($number, 0, ',', '.');
    }

    /**
     * Membuat password random
     * @param int $length Jumlah karakter.
     * @return string pasword yang telah di-generate.
     */
    public function generateRandomString($length = 6)
    {
        $randomstring = "ABCDEFGHIJKLMNPQRSTUVWXYZ123456789";
        $password = substr(str_shuffle($randomstring), 0, $length);
        return $password;
    }

    /**
     * Membuat pesan yang ingin dikirimkan ke suatu halaman melalui sesi
     * @param string $pesan Isi pesan yang ingin dikirimkan.
     * @param string $aksi Jumlah karakter.
     * @param string $warna Warna karakter.
     * @return void sesi flash pesan
     */
    function setpesan($pesan, $aksi = "", $warna = "green")
    {
        $_SESSION['flash'] = [
            'pesan' => $pesan,
            'aksi'  => $aksi,
            'warna' => $warna
        ];
    }

    /**
     * Membuat pesan langsung yang ingin dikirimkan melalui sesi
     * @param string $pesan Isi pesan yang ingin dikirimkan.
     */
    public function setPesanDirect($pesan)
    {
        $_SESSION['pesandirect'] = $pesan;
    }
    
    /**
     * Memanggil pesan langsung
     */
    public function pesanDirect()
    {
        if (!empty($_SESSION['pesandirect'])) {
            echo '
  <script defer>alert("' . $_SESSION['pesandirect'] . '")</script>';
            unset($_SESSION['pesandirect']);
        }
    }

    /**
     * Memanggil pesan dialog
     */
    public function pesanDialog()
    {
        if (!empty($_SESSION['flash'])) {
            echo '
  <script defer>alert("' . $_SESSION['flash']['pesan'] . ' ' . $_SESSION['flash']['aksi'] . '")</script>';
            unset($_SESSION['flash']);
        }
    }

    /**
     * Memanggil pesan
     */
    public function pesan()
    {
        if (!empty($_SESSION['flash'])) {
            echo '
  <div style="color :' . $_SESSION['flash']['warna'] . ';">
  ' . $_SESSION['flash']['pesan'] . ' ' . $_SESSION['flash']['aksi'] . '</div><br>';
            unset($_SESSION['flash']);
        }
    }

    /**
     * Menyimpan form yang telah inputkan
     * @param mixed ...$input Form yang telah diinputkan
     */
    public function setReturnForm(...$input)
    {
        $_SESSION["returnForm"] = $input;
    }

    /**
     * Mengembalikan form yang telah diinputkan
     * @return array|null Form yang telah dikembalikan
     */
    public function returnForm()
    {
        if (!empty($_SESSION["returnForm"])) {
            return $_SESSION["returnForm"];
        }
    }

    private $bulan = [
        "Juli", "Agustus", "September", "Oktober", "November", "Desember", "Januari", "Februari", "Maret",
        "April", "Mei", "Juni"
    ];

    public function getMonth()
    {
        return $this->bulan;
    }

    function buatBulan($selected = "")
    {

        echo '<optgroup label="Semester 1">';
        for ($i = 0; $i < 12; $i++) {
            if ($this->bulan[$i] == $selected) {
                echo "<option value=" . $this->bulan[$i] . " selected>" . $this->bulan[$i] . "</option>";
            } else {
                echo "<option value=" . $this->bulan[$i] . ">" . $this->bulan[$i] . "</option>";
            }
            if ($i == 5) {
                echo '</optgroup>';
                echo '<optgroup label="Semester 2">';
            }
        }
        echo '</optgroup>';
    }

    public function buatTahunAjaran($selected = "")
    {
        $tahunajaran = ["2020/2021", "2021/2022", "2022/2023", "2023/2024", "2024/2025"];
        for ($i = 0; $i < count($tahunajaran); $i++) {
            if ($tahunajaran[$i] == $selected) {
                echo "<option value=" . $tahunajaran[$i] . " selected>" . $tahunajaran[$i] . "</option>";
            } else {
                echo "<option value=" . $tahunajaran[$i] . ">" . $tahunajaran[$i] . "</option>";
            }
        }
    }
}
