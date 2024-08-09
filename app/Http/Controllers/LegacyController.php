<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\File;

class LegacyController extends Controller
{
    public function handleLegacyRequest(Request $request)
    {
        $redir = $_GET['redir'] ?? null;
        $request_app = config('app.request_app');

        $requestPath = $request->getPathInfo();

        $requestPath = preg_replace("#^/($request_app/)?#", '', $requestPath);
        $requestPath = "/$request_app/".($requestPath ?: 'index.php');

        $path = public_path("legacy/$requestPath");

        if (File::exists($path) && File::isFile($path)) {
            if (File::extension($path) === 'php') {
                return $this->executePhpFile($path, $request);
            }

            $mimeType = $this->getMimeType($path, File::extension($path));

            return response()->file($path,['Content-type' => $mimeType]);
        }

        // If file does not exist, return a 404 response
        abort(404);
    }

    protected function executePhpFile($path, $request)
    {
        ob_start();
        include $path;
        $content = ob_get_clean();

        return response($content);
    }

    protected function getMimeType($path, $extension)
    {
        $mimeTypes = [
            'html' => 'text/html',
            'htm' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'svg' => 'image/svg+xml',
            'ico' => 'image/x-icon',
            'woff' => 'font/woff',
            'woff2' => 'font/woff2',
        ];

        return $mimeTypes[$extension] ?? File::mimeType($path);
    }
}
