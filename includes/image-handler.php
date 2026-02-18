<?php
/**
 * Smart Image Handler
 * Automatically resizes images to fit size while maintaining aspect ratio
 * No cropping - just intelligent fitting with padding
 */

class ImageHandler {
    
    /**
     * Resize image to fit within dimensions without cropping
     * Adds padding to maintain aspect ratio
     * 
     * @param string $source Source image path
     * @param string $destination Destination image path
     * @param int $maxWidth Maximum width
     * @param int $maxHeight Maximum height
     * @param string $bgColor Background color for padding (default: white)
     * @return bool Success status
     */
    public static function resizeToFit($source, $destination, $maxWidth, $maxHeight, $bgColor = '#FFFFFF') {
        try {
            // Get image info
            $imageInfo = getimagesize($source);
            if (!$imageInfo) {
                return false;
            }
            
            list($originalWidth, $originalHeight, $imageType) = $imageInfo;
            
            // Create image resource from source
            switch ($imageType) {
                case IMAGETYPE_JPEG:
                    $sourceImage = imagecreatefromjpeg($source);
                    break;
                case IMAGETYPE_PNG:
                    $sourceImage = imagecreatefrompng($source);
                    break;
                case IMAGETYPE_GIF:
                    $sourceImage = imagecreatefromgif($source);
                    break;
                case IMAGETYPE_WEBP:
                    $sourceImage = imagecreatefromwebp($source);
                    break;
                default:
                    return false;
            }
            
            if (!$sourceImage) {
                return false;
            }
            
            // Calculate scaling ratio
            $ratioWidth = $maxWidth / $originalWidth;
            $ratioHeight = $maxHeight / $originalHeight;
            $ratio = min($ratioWidth, $ratioHeight);
            
            // Calculate new dimensions
            $newWidth = round($originalWidth * $ratio);
            $newHeight = round($originalHeight * $ratio);
            
            // Calculate position to center image
            $posX = round(($maxWidth - $newWidth) / 2);
            $posY = round(($maxHeight - $newHeight) / 2);
            
            // Create new image with padding
            $newImage = imagecreatetruecolor($maxWidth, $maxHeight);
            
            // Set background color
            $rgb = self::hexToRgb($bgColor);
            $bgColorResource = imagecolorallocate($newImage, $rgb['r'], $rgb['g'], $rgb['b']);
            imagefill($newImage, 0, 0, $bgColorResource);
            
            // Enable transparency for PNG
            if ($imageType == IMAGETYPE_PNG) {
                imagealphablending($newImage, false);
                imagesavealpha($newImage, true);
                $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
                imagefill($newImage, 0, 0, $transparent);
            }
            
            // Copy and resize
            imagecopyresampled(
                $newImage,
                $sourceImage,
                $posX, $posY,
                0, 0,
                $newWidth, $newHeight,
                $originalWidth, $originalHeight
            );
            
            // Save to destination
            switch ($imageType) {
                case IMAGETYPE_JPEG:
                    imagejpeg($newImage, $destination, 90);
                    break;
                case IMAGETYPE_PNG:
                    imagepng($newImage, $destination, 8);
                    break;
                case IMAGETYPE_GIF:
                    imagegif($newImage, $destination);
                    break;
                case IMAGETYPE_WEBP:
                    imagewebp($newImage, $destination, 90);
                    break;
            }
            
            // Free memory
            imagedestroy($sourceImage);
            imagedestroy($newImage);
            
            return true;
            
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Resize image maintaining aspect ratio (contain mode)
     * No padding, just resize to fit
     */
    public static function resizeContain($source, $destination, $maxWidth, $maxHeight) {
        try {
            $imageInfo = getimagesize($source);
            if (!$imageInfo) return false;
            
            list($originalWidth, $originalHeight, $imageType) = $imageInfo;
            
            // Create source image
            switch ($imageType) {
                case IMAGETYPE_JPEG:
                    $sourceImage = imagecreatefromjpeg($source);
                    break;
                case IMAGETYPE_PNG:
                    $sourceImage = imagecreatefrompng($source);
                    break;
                case IMAGETYPE_GIF:
                    $sourceImage = imagecreatefromgif($source);
                    break;
                case IMAGETYPE_WEBP:
                    $sourceImage = imagecreatefromwebp($source);
                    break;
                default:
                    return false;
            }
            
            if (!$sourceImage) return false;
            
            // Calculate new dimensions
            $ratioWidth = $maxWidth / $originalWidth;
            $ratioHeight = $maxHeight / $originalHeight;
            $ratio = min($ratioWidth, $ratioHeight);
            
            $newWidth = round($originalWidth * $ratio);
            $newHeight = round($originalHeight * $ratio);
            
            // Create new image
            $newImage = imagecreatetruecolor($newWidth, $newHeight);
            
            // Preserve transparency
            if ($imageType == IMAGETYPE_PNG) {
                imagealphablending($newImage, false);
                imagesavealpha($newImage, true);
            }
            
            // Resize
            imagecopyresampled(
                $newImage,
                $sourceImage,
                0, 0, 0, 0,
                $newWidth, $newHeight,
                $originalWidth, $originalHeight
            );
            
            // Save
            switch ($imageType) {
                case IMAGETYPE_JPEG:
                    imagejpeg($newImage, $destination, 90);
                    break;
                case IMAGETYPE_PNG:
                    imagepng($newImage, $destination, 8);
                    break;
                case IMAGETYPE_GIF:
                    imagegif($newImage, $destination);
                    break;
                case IMAGETYPE_WEBP:
                    imagewebp($newImage, $destination, 90);
                    break;
            }
            
            imagedestroy($sourceImage);
            imagedestroy($newImage);
            
            return true;
            
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Convert hex color to RGB
     */
    private static function hexToRgb($hex) {
        $hex = str_replace('#', '', $hex);
        
        if (strlen($hex) == 3) {
            $r = hexdec(str_repeat(substr($hex, 0, 1), 2));
            $g = hexdec(str_repeat(substr($hex, 1, 1), 2));
            $b = hexdec(str_repeat(substr($hex, 2, 1), 2));
        } else {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }
        
        return ['r' => $r, 'g' => $g, 'b' => $b];
    }
}
