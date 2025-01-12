<?php 
namespace Pamutlabor\Module\Layout;

abstract class LayoutView {

    public function __construct() {
    }

    protected function getHeader(array $data): string {
        return '
            <!DOCTYPE html>
            <html lang="hu">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>' . $data['title'] . '</title>
                <!-- Bootstrap CSS -->
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
            </head>
            <body>
            <nav class="navbar navbar-dark bg-dark">
                <div class="container-fluid">
                    <span class="navbar-brand mb-0 h1">WeLove Test</span>
                    <div>
                        <a href="/" class="text-white me-3">Projektlista</a>
                        <a href="/project" class="text-white">Szerkesztés/Létrehozás</a>
                    </div>
                </div>
            </nav>
        ';
    }

    abstract protected function content(array $data): string;

    protected function getFooter(array $data): string {
        return '
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
            </body>
            </html>
        ';
    }

    public function render(array $data){
        ob_start();
        header('Content-Type: text/html; charset=utf-8');
        echo $this->getHeader($data) . $this->content($data) . $this->getFooter($data);
        return ob_get_clean();
    }
}