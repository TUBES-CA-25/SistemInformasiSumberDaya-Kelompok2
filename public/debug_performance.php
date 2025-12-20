<?php
/**
 * Performance Debug Tool
 * Untuk menganalisis penyebab loading lambat
 */

// Start timing
$start_time = microtime(true);

?>
<!DOCTYPE html>
<html>
<head>
    <title>⚡ Performance Debug</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .timing { background: #f0f8ff; padding: 10px; margin: 10px 0; border-left: 4px solid #007cba; }
        .slow { background: #ffe6e6; border-left-color: #dc3545; }
        .fast { background: #e6ffe6; border-left-color: #28a745; }
        code { background: #f0f0f0; padding: 2px 4px; border-radius: 2px; }
    </style>
</head>
<body>
    <h1>⚡ Performance Analysis</h1>

    <?php
    function checkTiming($label, $start) {
        $elapsed = (microtime(true) - $start) * 1000;
        $class = $elapsed > 100 ? 'slow' : ($elapsed > 50 ? 'timing' : 'fast');
        echo "<div class='$class'>";
        echo "<strong>$label:</strong> " . number_format($elapsed, 2) . " ms";
        if ($elapsed > 100) echo " ⚠️ SLOW!";
        elseif ($elapsed < 50) echo " ✅ Fast";
        echo "</div>";
        return microtime(true);
    }

    $timing = microtime(true);
    ?>

    <h2>🔍 Component Loading Times</h2>

    <?php
    // Test autoload
    $test_start = microtime(true);
    require_once '../vendor/autoload.php';
    $timing = checkTiming('Composer Autoload', $test_start);

    // Test config loading
    $test_start = microtime(true);
    require_once '../app/config/config.php';
    $timing = checkTiming('Config Loading', $test_start);

    // Test database connection
    $test_start = microtime(true);
    require_once '../app/config/Database.php';
    $database = new Database();
    $db = $database->connect();
    $timing = checkTiming('Database Connection', $test_start);

    // Test router loading
    $test_start = microtime(true);
    require_once '../app/config/Router.php';
    $timing = checkTiming('Router Class Loading', $test_start);

    // Test route definition
    $test_start = microtime(true);
    $router = new Router();
    $timing = checkTiming('Router Initialization', $test_start);

    // Test route dispatching simulation
    $_GET['route'] = 'login';
    $test_start = microtime(true);
    // Simulate routing without actual execution
    $reflection = new ReflectionClass($router);
    $method = $reflection->getMethod('defineRoutes');
    $method->setAccessible(true);
    $method->invoke($router);
    $timing = checkTiming('Route Definition', $test_start);

    $total_time = (microtime(true) - $start_time) * 1000;
    ?>

    <div class="<?= $total_time > 500 ? 'slow' : 'fast' ?>">
        <h3>📊 Total Loading Time: <?= number_format($total_time, 2) ?> ms</h3>
    </div>

    <h2>🛠️ Optimization Recommendations</h2>

    <?php if ($total_time > 500): ?>
        <div class="slow">
            <h4>⚠️ Performance Issues Detected!</h4>
            <ul>
                <li><strong>Composer Autoload:</strong> Consider using optimized autoloader</li>
                <li><strong>Database:</strong> Use connection pooling or lazy loading</li>
                <li><strong>Router:</strong> Implement route caching</li>
            </ul>
        </div>
    <?php elseif ($total_time > 200): ?>
        <div class="timing">
            <h4>⚡ Moderate Performance</h4>
            <p>Loading time is acceptable but could be improved.</p>
        </div>
    <?php else: ?>
        <div class="fast">
            <h4>✅ Good Performance</h4>
            <p>Loading time is optimal!</p>
        </div>
    <?php endif; ?>

    <h2>🔧 Quick Fixes Applied</h2>
    <ul>
        <li>✅ <strong>Lazy Loading:</strong> Router only loads when MVC routes are accessed</li>
        <li>✅ <strong>Conditional Dependencies:</strong> Database only connects when needed</li>
        <li>✅ <strong>Early Exit:</strong> Legacy pages bypass MVC system entirely</li>
        <li>✅ <strong>Route Caching:</strong> Routes defined only when dispatching</li>
    </ul>

    <h2>📈 Performance Tips</h2>
    <div style="background: #f8f9fa; padding: 15px; border-radius: 5px;">
        <ol>
            <li><strong>Use Legacy Routes</strong> for simple pages: <code>?page=home</code> instead of <code>/home</code></li>
            <li><strong>Enable OPcache</strong> in PHP for faster script execution</li>
            <li><strong>Use CDN</strong> for static assets (CSS, JS, images)</li>
            <li><strong>Compress output</strong> with gzip</li>
        </ol>
    </div>

    <div style="text-align: center; margin-top: 30px;">
        <a href="/SistemInformasiSumberDaya-Kelompok2/public/" style="background: #007cba; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">🏠 Home (Legacy - Fast)</a>
        <a href="/SistemInformasiSumberDaya-Kelompok2/public/login" style="background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">🔑 Login (MVC)</a>
        <a href="/SistemInformasiSumberDaya-Kelompok2/public/?page=home" style="background: #ffc107; color: black; padding: 10px 20px; text-decoration: none; border-radius: 5px;">⚡ Legacy Page</a>
    </div>
</body>
</html>