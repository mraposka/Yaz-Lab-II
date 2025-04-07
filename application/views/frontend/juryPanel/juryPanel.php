<?php
// Örnek ilanlar
$adverts = [
    ["id" => 1, "title" => "Yazılım Mühendisi İlanı"],
    ["id" => 2, "title" => "Veri Bilimci İlanı"]
];

// Örnek jüri listesi
$juries = [
    ["id" => 1, "name" => "Ahmet", "surname" => "Yılmaz"],
    ["id" => 2, "name" => "Ayşe", "surname" => "Demir"],
    ["id" => 3, "name" => "Mehmet", "surname" => "Kara"],
    ["id" => 4, "name" => "Zeynep", "surname" => "Çelik"],
    ["id" => 5, "name" => "Fatih", "surname" => "Koç"],
    ["id" => 6, "name" => "Emine", "surname" => "Güneş"]
];

// Kaydedilen ilan ve jüri seçimlerini saklamak için oturum değişkeni
if (!isset($_SESSION['selections'])) {
    $_SESSION['selections'] = [];
}

// Yeni seçimleri ekle
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["ilan"], $_POST["juriler"])) {
    $ilanId = $_POST["ilan"];
    $selectedJuries = $_POST["juriler"];

    // Aynı ilan için tekrar eklemeyi önlemek için kontrol
    $_SESSION['selections'][] = [
        "ilan_id" => $ilanId,
        "juriler" => $selectedJuries
    ];
}

// Silme işlemi
if (isset($_GET["delete"])) {
    $deleteIndex = $_GET["delete"];
    unset($_SESSION['selections'][$deleteIndex]);
    $_SESSION['selections'] = array_values($_SESSION['selections']); // Diziyi yeniden indexle
    header("Location: " . $_SERVER["PHP_SELF"]);
    exit;
}

// İlan adını getiren fonksiyon
function getAdvertTitle($id, $adverts) {
    foreach ($adverts as $advert) {
        if ($advert["id"] == $id) {
            return $advert["title"];
        }
    }
    return "Bilinmeyen İlan";
}

// Jüri adlarını getiren fonksiyon
function getJuryNames($juryIds, $juries) {
    $names = [];
    foreach ($juryIds as $juryId) {
        foreach ($juries as $jury) {
            if ($jury["id"] == $juryId) {
                $names[] = $jury["name"] . " " . $jury["surname"];
            }
        }
    }
    return implode(", ", $names);
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yönetici Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex min-h-screen">

    <!-- Sidebar -->
    <div id="sidebar" class="w-64 bg-gray-800 text-white p-6 flex flex-col h-screen fixed">
        <h2 class="text-2xl font-bold mb-6 text-center">Yönetici Panel</h2>
        <ul class="flex-1">
            <li class="mb-4">
                <a href="#" class="block py-2 px-4 rounded hover:bg-gray-700">Ana Sayfa</a>
            </li>
            <li class="mb-4">
                <a href="#" class="block py-2 px-4 rounded hover:bg-gray-700">Kriterler</a>
            </li>
            <li class="mb-4">
                <a href="#" class="block py-2 px-4 rounded hover:bg-gray-700">Jüri Ekleme</a>
            </li> 
        </ul>
    </div>

    <!-- Content -->
    <div class="flex-1 p-8 ml-64">
        <h2 class="text-3xl font-bold mb-6">Jüri Seçimi</h2>

        <!-- İlan ve Jüri Seçimi Formu -->
        <form method="POST">
            <!-- İlan Seçimi -->
            <div class="mb-4">
                <label for="ilan" class="block text-sm font-medium text-gray-700">İlan Seçiniz</label>
                <select id="ilan" name="ilan" class="mt-1 block w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    <?php foreach ($adverts as $advert): ?>
                        <option value="<?php echo $advert["id"]; ?>">
                            <?php echo $advert["title"]; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Jüri Seçimi -->
            <div class="mb-4">
                <label for="jury" class="block text-sm font-medium text-gray-700">Jüri Seçiniz (5 tane)</label>
                <select id="jury" name="juriler[]" multiple class="mt-1 block w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500 h-40">
                    <?php foreach ($juries as $jury): ?>
                        <option value="<?php echo $jury["id"]; ?>">
                            <?php echo $jury["name"] . " " . $jury["surname"]; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 transition duration-200">
                Kaydet
            </button>
        </form>

        <!-- Kayıtlı İlan ve Jürileri Gösteren Tablo -->
        <h2 class="text-2xl font-bold mt-8 mb-4">Seçili İlan ve Jüriler</h2>
        <table class="w-full bg-white shadow-md rounded-lg overflow-hidden">
            <thead>
                <tr class="bg-gray-200 text-gray-700">
                    <th class="py-2 px-4 text-left">İlan</th>
                    <th class="py-2 px-4 text-left">Jüriler</th>
                    <th class="py-2 px-4 text-center">Sil</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($_SESSION['selections'])): ?>
                    <?php foreach ($_SESSION['selections'] as $index => $selection): ?>
                        <tr class="border-b">
                            <td class="py-2 px-4"><?php echo getAdvertTitle($selection["ilan_id"], $adverts); ?></td>
                            <td class="py-2 px-4"><?php echo getJuryNames($selection["juriler"], $juries); ?></td>
                            <td class="py-2 px-4 text-center">
                                <a href="?delete=<?php echo $index; ?>" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">Sil</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="py-2 px-4 text-center text-gray-500">Henüz kayıtlı ilan ve jüri yok.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

    </div>


<script>
        const { createApp } = Vue;

        createApp({
            data() {

            }
        }).mount('#app');
    </script>

</body>
</html>

