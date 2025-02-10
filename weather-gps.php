<?php
// دریافت اطلاعات از gps
if (isset($_GET['lat']) && isset($_GET['lon'])) {
    $apiKey = "c46b932a5e1a4136ba684521251002"; // کلید API را اینجا وارد کنید
    $lat = $_GET['lat'];
    $lon = $_GET['lon'];
    $url = "http://api.weatherapi.com/v1/current.json?key={$apiKey}&q={$lat},{$lon}&lang=fa";

    // دریافت داده از API
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
        $error = "دریافت اطلاعات امکان‌پذیر نیست!";
    }
}
?>

<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>وضعیت آب و هوا بر اساس GPS</title>
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
    <h2 class="text-center">وضعیت آب و هوا بر اساس موقعیت شما</h2>

    <!-- دکمه دریافت موقعیت -->
    <div class="text-center mt-4">
        <button class="btn btn-primary" onclick="getLocation()">دریافت وضعیت آب و هوا</button>
    </div>

    <!-- نمایش اطلاعات -->
    <div class="mt-4 text-center">
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error; ?></div>
        <?php elseif (isset($cityName)): ?>
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
