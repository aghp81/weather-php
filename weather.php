<?php
// وارد کردن نام شهر توسط کاربر
// بررسی اینکه آیا ورودی شهر دریافت شده است یا نه
if (isset($_GET['city']) && !empty($_GET['city'])) {
    $apiKey = "c46b932a5e1a4136ba684521251002"; // کلید API خود را اینجا قرار دهید
    $city = urlencode($_GET['city']);
    $url = "http://api.weatherapi.com/v1/current.json?key={$apiKey}&q={$city}&lang=fa";

    // دریافت اطلاعات از API
    $response = file_get_contents($url);
    $data = json_decode($response, true);

    // بررسی موفقیت‌آمیز بودن دریافت داده‌ها
    if (isset($data['current'])) {
        $cityName = $data['location']['name'];
        $country = $data['location']['country'];
        $temp = $data['current']['temp_c'];
        $humidity = $data['current']['humidity'];
        $condition = $data['current']['condition']['text'];
        $icon = $data['current']['condition']['icon'];
    } else {
        $error = "شهر موردنظر یافت نشد!";
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
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2 class="text-center">برنامه نمایش وضعیت آب و هوا</h2>

    <!-- فرم دریافت نام شهر -->
    <form method="GET" class="d-flex justify-content-center mt-4">
        <input type="text" name="city" class="form-control w-50" placeholder="نام شهر را وارد کنید" required>
        <button type="submit" class="btn btn-primary ms-2">دریافت وضعیت</button>
    </form>

    <!-- نمایش نتیجه -->
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
