<?php
// روش ترکیبی دریافت اطلاعات از gps و وارد کردن نام شهر توسط کاربر

$apiKey = "c46b932a5e1a4136ba684521251002"; // کلید API خود را اینجا قرار دهید
$cityName = "";
$country = "";
$temp = "";
$humidity = "";
$condition = "";
$icon = "";
$error = "";

// بررسی ورودی از فرم (نام شهر یا GPS)
if (isset($_GET['city']) && !empty($_GET['city'])) {
    $city = urlencode($_GET['city']);
    $url = "http://api.weatherapi.com/v1/current.json?key={$apiKey}&q={$city}&lang=fa";
} elseif (isset($_GET['lat']) && isset($_GET['lon'])) {
    $lat = $_GET['lat'];
    $lon = $_GET['lon'];
    $url = "http://api.weatherapi.com/v1/current.json?key={$apiKey}&q={$lat},{$lon}&lang=fa";
}

// دریافت داده‌ها از API
if (isset($url)) {
    $response = file_get_contents($url);
    $data = json_decode($response, true);

    if (isset($data['current'])) {
        $cityName = $data['location']['name'];
        $country = $data['location']['country'];
        $temp = $data['current']['temp_c'];
        $humidity = $data['current']['humidity'];
        $condition = $data['current']['condition']['text'];
        $icon = $data['current']['condition']['icon'];
    } else {
        $error = "داده‌ای یافت نشد، لطفاً دوباره امتحان کنید!";
    }
}
?>

<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>وضعیت آب و هوا</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script>
        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition, showError);
            } else {
                alert("مرورگر شما از GPS پشتیبانی نمی‌کند!");
            }
        }

        function showPosition(position) {
            let lat = position.coords.latitude;
            let lon = position.coords.longitude;
            window.location.href = `?lat=${lat}&lon=${lon}`;
        }

        function showError(error) {
            let message = "خطا در دریافت موقعیت مکانی!";
            if (error.code === 1) message = "دسترسی به موقعیت مکانی رد شد!";
            else if (error.code === 2) message = "موقعیت مکانی در دسترس نیست!";
            else if (error.code === 3) message = "زمان دریافت موقعیت مکانی به پایان رسید!";
            alert(message);
        }
    </script>
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2 class="text-center">برنامه نمایش وضعیت آب و هوا</h2>

    <!-- فرم دریافت نام شهر -->
    <form method="GET" class="d-flex justify-content-center mt-4">
        <input type="text" name="city" class="form-control w-50" placeholder="نام شهر را وارد کنید">
        <button type="submit" class="btn btn-success ms-2">جستجو</button>
    </form>

    <!-- دکمه دریافت موقعیت GPS -->
    <div class="text-center mt-3">
        <button class="btn btn-primary" onclick="getLocation()">دریافت وضعیت آب و هوا از GPS</button>
    </div>

    <!-- نمایش اطلاعات -->
    <div class="mt-4 text-center">
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= $error; ?></div>
        <?php elseif (!empty($cityName)): ?>
            <div class="card mx-auto" style="width: 18rem;">
                <div class="card-body">
                    <h5 class="card-title"><?= $cityName ?>, <?= $country ?></h5>
                    <img src="<?= $icon ?>" alt="وضعیت آب و هوا">
                    <p class="card-text">وضعیت: <?= $condition ?></p>
                    <p class="card-text">دما: <?= $temp ?>°C</p>
                    <p class="card-text">رطوبت: <?= $humidity ?>%</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
