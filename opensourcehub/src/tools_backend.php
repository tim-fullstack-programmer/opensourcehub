<?php
require_once __DIR__ . '/config.php';

function create_repo_zip(int $repo_id) {
    $source = REPO_STORAGE . '/' . $repo_id;
    if (!is_dir($source)) return false;
    $zipname = sys_get_temp_dir() . '/repo_' . $repo_id . '_' . time() . '.zip';

    $zip = new ZipArchive();
    if ($zip->open($zipname, ZipArchive::CREATE) !== TRUE) return false;

    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::LEAVES_ONLY);
    foreach ($files as $name => $file) {
        if (!$file->isDir()) {
            $filePath = $file->getRealPath();
            $relativePath = substr($filePath, strlen($source) + 1);
            $zip->addFile($filePath, $relativePath);
        }
    }
    $zip->close();
    return $zipname;
}
