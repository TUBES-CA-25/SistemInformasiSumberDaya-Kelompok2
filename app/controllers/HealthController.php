<?php
namespace App\Controllers;

class HealthController extends Controller {
    public function check() {
        $this->success([
            'status' => 'API is running',
            'timestamp' => date('Y-m-d H:i:s'),
            'version' => '1.0'
        ], 'Health check passed');
    }
}
?>
