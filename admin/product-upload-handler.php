<?php
/**
 * Product Image Upload Handler
 * Automatically resizes product images to standard sizes
 * No cropping - maintains aspect ratio
 */

require_once '../includes/image-handler.php';

function handleProductImageUpload($file, $uploadDir = '../uploads/products/') {
    try {
        // Validate file
        if (!isset($file['tmp_name']) || $file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('Upload error');
        }
        
        // Validate image type
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        if (!in_array($mimeType, $allowedTypes)) {
            throw new Exception('Invalid file type');
        }
        
        // Generate unique filename
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid('product_', true) . '.' . $ext;
        $destination = $uploadDir . $filename;
        
        // Create directory if not exists
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        // Move uploaded file to temp location
        $tempPath = $uploadDir . 'temp_' . $filename;
        if (!move_uploaded_file($file['tmp_name'], $tempPath)) {
            throw new Exception('Failed to move uploaded file');
        }
        
        // Resize image to fit 800x800 (product display size)
        // Maintains aspect ratio, no cropping
        $resized = ImageHandler::resizeContain($tempPath, $destination, 800, 800);
        
        if (!$resized) {
            // If resize fails, use original
            rename($tempPath, $destination);
        } else {
            // Delete temp file
            if (file_exists($tempPath)) {
                unlink($tempPath);
            }
        }
        
        return $filename;
        
    } catch (Exception $e) {
        return false;
    }
}

function handleCategoryImageUpload($file, $uploadDir = '../uploads/categories/') {
    try {
        if (!isset($file['tmp_name']) || $file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('Upload error');
        }
        
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        if (!in_array($mimeType, $allowedTypes)) {
            throw new Exception('Invalid file type');
        }
        
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid('category_', true) . '.' . $ext;
        $destination = $uploadDir . $filename;
        
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $tempPath = $uploadDir . 'temp_' . $filename;
        if (!move_uploaded_file($file['tmp_name'], $tempPath)) {
            throw new Exception('Failed to move uploaded file');
        }
        
        // Resize to 600x600 for categories
        $resized = ImageHandler::resizeContain($tempPath, $destination, 600, 600);
        
        if (!$resized) {
            rename($tempPath, $destination);
        } else {
            if (file_exists($tempPath)) {
                unlink($tempPath);
            }
        }
        
        return $filename;
        
    } catch (Exception $e) {
        return false;
    }
}
