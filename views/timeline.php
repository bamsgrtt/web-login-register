<?php
require_once '../classes/Session.php';

require_once '../config/Database.php';

require_once '../classes/Controller.php';



$controller = new Controller();
$lat = -8.11655; // Contoh latitude (Jakarta)
$long = 113.74552; // Contoh longitude (Jakarta) 
$weatherData = $controller->getWeather($lat, $long);
?>


    <nav class="navbar navbar-light bg-white shadow-sm sticky-top mb-4">
        <div class="container">
            <span class="navbar-brand fw-bold text-primary fs-4">WSI</span>
            <div class="d-flex align-items-center">
                <span class="me-3 fw-semibold">
                    Halo, <?php echo $session->get('user')['username']; ?>
                </span>
                <a href="views/logout.php" class="btn btn-outline-danger btn-sm">
                    <i class="bi bi-box-arrow-right"></i>
                </a>
            </div>
        </div>
    </nav>
    <?php if ($session->get('success')): ?>
        <div id="success-alert" class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            <?= $session->get('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php $session->remove('success'); ?>
    <?php endif; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            
            <?php if (isset($weatherData) && $weatherData['status'] == 'success'): 
                $cw = $weatherData['data']; 
                usort($cw['forecast'], function($a, $b) {
                    return strtotime($a['local_datetime']) - strtotime($b['local_datetime']);
                });
            ?>
                <!-- Header Lokasi: Muncul Sekali -->
                <div class="card border-0 shadow-sm rounded-4 mb-4 bg-primary text-white">
                    <div class="card-body p-4 d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="fw-bold mb-1"><i class="bi bi-geo-alt-fill me-2"></i> <?= $cw['location'] ?></h4>
                            <p class="mb-0 opacity-75">Prakiraan Cuaca Per Jam</p>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-white text-primary rounded-pill px-3">Real-time</span>
                        </div>
                    </div>
                </div>

                <!-- List ke Samping (Horizontal Scroll) -->
                <div class="d-flex overflow-auto pb-3 gap-3" style="scrollbar-width: thin;">
                    <?php foreach ($cw['forecast'] as $data): ?>
                        <div class="card border-0 shadow-sm rounded-4 text-center flex-shrink-0" style="min-width: 160px;">
                            <div class="card-body p-3">
                                <!-- Waktu -->
                                <div class="small text-muted mb-2 fw-bold">
                                    <?= date('H:i', strtotime($data['local_datetime'])) ?>
                                </div>
                                
                                <!-- Ikon & Suhu -->
                                <div class="my-3">
                                    <h2 class="fw-bold mb-0"><?= round($data['temperature']) ?>°</h2>
                                    <small class="text-primary fw-medium"><?= $data['weather'] ?></small>
                                </div>

                                <!-- Detail Kecil -->
                                <div class="pt-2 border-top">
                                    <div class="d-flex justify-content-between mb-1">
                                        <small class="text-muted">Lembap</small>
                                        <small class="fw-bold"><?= $data['humidity'] ?>%</small>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <small class="text-muted">Angin</small>
                                        <small class="fw-bold"><?= round($data['wind_speed']) ?> <span style="font-size: 8px;">km/h</span></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

            <?php else: ?>
                <div class="card border-0 shadow-sm rounded-4 py-5 text-center">
                    <i class="bi bi-cloud-slash display-4 text-muted"></i>
                    <p class="mt-3 text-secondary">Data cuaca tidak tersedia.</p>
                </div>
            <?php endif; ?>
            
        </div>
    </div>
</div>

<style>
    /* Menghilangkan scrollbar agar terlihat lebih bersih di beberapa browser */
    .overflow-auto::-webkit-scrollbar {
        height: 6px;
    }
    .overflow-auto::-webkit-scrollbar-thumb {
        background-color: #dee2e6;
        border-radius: 10px;
    }
    .overflow-auto {
        -ms-overflow-style: none;
        scroll-snap-type: x mandatory;
    }
    .card {
        scroll-snap-align: start;
    }
</style>


<!-- </div> -->

<footer class="py-4 mt-5 border-top bg-white">
    <div class="container text-center">
        <span class="fw-bold text-primary fs-5">PESBUK</span>
        <p class="text-muted small mt-2 mb-0">
            &copy; <?php echo date("Y"); ?> Pesbuk Indonesia. <br>
            Dibuat dengan <i class="bi bi-heart-fill text-danger"></i> untuk koneksi yang lebih baik.
        </p>
    </div>
</footer>
