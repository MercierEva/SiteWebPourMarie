<?php
declare(strict_types=1);
namespace App\Controllers;

abstract class AbstractRenderController {
    
    protected function render(string $file, string $layout, ?array $data = null){
        // Start output buffer
        ob_start();
        
        if (isset($data)) {
            // Get Data and extract them
            extract($data);
        }
        //include special view file
        require_once(ROOT . '/src/Views/' . $file . '.phtml');
        
        // Put data stock inside buffer in $content
        $content = ob_get_clean();
        
        // Build the result on template layout file
        require_once(ROOT . '/src/Views/' . $layout . '.phtml');
    }
}