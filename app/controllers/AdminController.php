<?php
require_once CONTROLLER_PATH . '/Controller.php';

class AdminController extends Controller {
    
    /**
     * Get Admin Page Content via AJAX (SPA)
     * Returns JSON with HTML content
     */
    public function getPageContent($params) {
        // Check authentication first
        if (!isset($_SESSION['username'])) {
            header('Content-Type: application/json');
            http_response_code(401);
            echo json_encode([
                'success' => false,
                'message' => 'Unauthorized - Please login'
            ]);
            exit;
        }
        
        $route = $params['route'] ?? 'admin';
        
        // Security: Validate route to prevent directory traversal
        $allowed_routes = [
            'admin', 
            'admin/asisten', 
            'admin/manajemen', 
            'admin/laboratorium', 
            'admin/matakuliah', 
            'admin/alumni', 
            'admin/jadwal', 
            'admin/peraturan',
            'admin/peraturan-lab',
            'admin/sanksi',
            'admin/sanksi-lab'
        ];
        
        if (!in_array($route, $allowed_routes)) {
            header('Content-Type: application/json');
            http_response_code(403);
            echo json_encode([
                'success' => false,
                'message' => 'Route tidak diperbolehkan'
            ]);
            exit;
        }
        
        try {
            // Clean all output buffers to ensure JSON response
            while (ob_get_level()) {
                ob_end_clean();
            }
            
            // Start fresh buffer for content capture
            ob_start();
            
            // Determine module and file
            $viewsPath = VIEW_PATH . '/admin/';
            $module = str_replace('admin/', '', $route);
            $module = $module === 'admin' ? 'dashboard' : $module;
            
            // Build view path
            if ($module === 'dashboard') {
                $targetFile = $viewsPath . 'dashboard/index.php';
            } else {
                $targetFile = $viewsPath . $module . '/index.php';
            }
            
            // Check if file exists
            if (!file_exists($targetFile)) {
                ob_end_clean();
                header('Content-Type: application/json');
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'message' => 'Halaman tidak ditemukan: ' . $targetFile
                ]);
                exit;
            }
            
            // Include page content
            include $targetFile;
            
            // Get buffered content
            $content = ob_get_clean();
            
            // Return JSON response
            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode([
                'success' => true,
                'content' => $content,
                'route' => $route,
                'timestamp' => date('Y-m-d H:i:s')
            ]);
            exit;
            
        } catch (Exception $e) {
            // Clean all buffers
            while (ob_get_level()) {
                ob_end_clean();
            }
            
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
            exit;
        }
    }
}
?>
