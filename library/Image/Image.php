<?php
    /**
     * Image Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: Image.php, v1.00 5/7/2023 2:54 PM Gewa Exp $
     *
     */
    
    namespace Wojo\Image;
    
    use Exception;
    use GdImage;
    use Wojo\Debug\Debug;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class Image
    {
        const CROPTOP = 1;
        const CROPCENTRE = 2;
        const CROPCENTER = 2;
        const CROPBOTTOM = 3;
        const CROPLEFT = 4;
        const CROPRIGHT = 5;
        const CROPTOPCENTER = 6;
        const IMG_FLIP_HORIZONTAL = 0;
        const IMG_FLIP_VERTICAL = 1;
        const IMG_FLIP_BOTH = 2;
        
        public int $quality_jpg = 85;
        public int $quality_webp = 85;
        public int $quality_png = 9;
        public bool $quality_truecolor = true;
        public bool $gamma_correct = false;
        public int $interlace = 1;
        public mixed $source_type = null;
        protected $source_image;
        protected mixed $original_w = '';
        protected mixed $original_h = '';
        protected int $dest_x = 0;
        protected int $dest_y = 0;
        protected int $source_x;
        protected int $source_y;
        protected int $dest_w;
        protected int $dest_h;
        protected int $source_w;
        protected int $source_h;
        protected array $source_info = [];
        protected array $filters = [];
        
        /**
         * @param string $filename
         */
        public function __construct(string $filename)
        {
            if (!defined('IMAGETYPE_WEBP')) {
                define('IMAGETYPE_WEBP', 18);
            }
            
            if (!defined('IMAGETYPE_BMP')) {
                define('IMAGETYPE_BMP', 6);
            }
            
            if (strlen($filename) === 0 || (!str_starts_with($filename, 'data:') && !is_file($filename))) {
                Debug::addMessage('errors', '<i>Exception</i>', 'Image {' . $filename . '} does not exist', 'session');
                return false;
                exit;
            }
            
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $checkWebp = false;
            if (!str_contains(finfo_file($finfo, $filename), 'image')) {
                if (version_compare(PHP_VERSION, '7.0.0', '<=') && str_contains(file_get_contents($filename), 'WEBPVP8')) {
                    $checkWebp = true;
                    $this->source_type = IMAGETYPE_WEBP;
                } else {
                    Debug::addMessage('errors', '<i>Exception</i>', 'Unsupported file type ' . $this->source_type, 'session');
                    return false;
                    exit;
                }
            } elseif (str_contains(finfo_file($finfo, $filename), 'image/webp')) {
                $checkWebp = true;
                $this->source_type = IMAGETYPE_WEBP;
            }
            
            if (!$image_info = getimagesize($filename, $this->source_info)) {
                $image_info = getimagesize($filename);
            }
            
            if (!$checkWebp) {
                if (!$image_info) {
                    if (str_contains(finfo_file($finfo, $filename), 'image')) {
                        Debug::addMessage('errors', '<i>Exception</i>', 'Unsupported image type', 'session');
                        return false;
                        exit;
                    }
                    Debug::addMessage('errors', '<i>Exception</i>', 'Could not read file ' . $filename, 'session');
                    return false;
                    exit;
                }
                
                $this->original_w = $image_info[0];
                $this->original_h = $image_info[1];
                $this->source_type = $image_info[2];
            }
            
            switch ($this->source_type) {
                case IMAGETYPE_GIF:
                    $this->source_image = imagecreatefromgif($filename);
                    break;
                
                case IMAGETYPE_JPEG:
                    $this->source_image = $this->imageCreateJpegFromExif($filename);
                    $this->original_w = imagesx($this->source_image);
                    $this->original_h = imagesy($this->source_image);
                    break;
                
                case IMAGETYPE_PNG:
                    $this->source_image = imagecreatefrompng($filename);
                    break;
                
                case IMAGETYPE_WEBP:
                    $this->source_image = imagecreatefromwebp($filename);
                    $this->original_w = imagesx($this->source_image);
                    $this->original_h = imagesy($this->source_image);
                    break;
                
                case IMAGETYPE_BMP:
                    $this->source_image = imagecreatefrombmp($filename);
                    break;
                
                default:
                    Debug::addMessage('errors', '<i>Exception</i>', 'Unsupported image type', 'session');
                    return false;
                    exit;
            }
            
            if (!$this->source_image) {
                Debug::addMessage('errors', '<i>Exception</i>', 'Could not load image ' . $this->source_image, 'session');
                return false;
                exit;
            }
            
            finfo_close($finfo);
            
            return $this->resize($this->getSourceWidth(), $this->getSourceHeight());
        }
        
        /**
         * imageCreateJpegFromExif
         *
         * @param string $filename
         * @return false|GdImage|resource
         */
        public function imageCreateJpegFromExif(string $filename)
        {
            $img = imagecreatefromjpeg($filename);
            
            if (!function_exists('exif_read_data') || !isset($this->source_info['APP1']) || !str_starts_with($this->source_info['APP1'], 'Exif')) {
                return $img;
            }
            
            try {
                $exif = exif_read_data($filename);
            } catch (Exception) {
                $exif = null;
            }
            
            if (!$exif || !isset($exif['Orientation'])) {
                return $img;
            }
            
            $orientation = $exif['Orientation'];
            
            if ($orientation === 6 || $orientation === 5) {
                $img = imagerotate($img, 270, 0);
            } elseif ($orientation === 3 || $orientation === 4) {
                $img = imagerotate($img, 180, 0);
            } elseif ($orientation === 8 || $orientation === 7) {
                $img = imagerotate($img, 90, 0);
            }
            
            if ($orientation === 5 || $orientation === 4 || $orientation === 7) {
                imageflip($img, IMG_FLIP_HORIZONTAL);
            }
            
            return $img;
        }
        
        /**
         * resize
         *
         * @param int $width
         * @param int $height
         * @param bool $allow_enlarge
         * @return $this
         */
        public function resize(int $width, int $height, bool $allow_enlarge = false): static
        {
            if (!$allow_enlarge) {
                if ($width > $this->getSourceWidth() || $height > $this->getSourceHeight()) {
                    $width = $this->getSourceWidth();
                    $height = $this->getSourceHeight();
                }
            }
            
            $this->source_x = 0;
            $this->source_y = 0;
            
            $this->dest_w = $width;
            $this->dest_h = $height;
            
            $this->source_w = $this->getSourceWidth();
            $this->source_h = $this->getSourceHeight();
            
            return $this;
        }
        
        /**
         * getSourceWidth
         *
         * @return false|int|mixed
         */
        public function getSourceWidth(): mixed
        {
            return $this->original_w;
        }
        
        
        /**
         * getSourceHeight
         *
         * @return false|int|mixed
         */
        public function getSourceHeight(): mixed
        {
            return $this->original_h;
        }
        
        
        /**
         * createFromString
         *
         * @param $image_data
         * @return self
         */
        public static function createFromString($image_data): Image
        {
            if (empty($image_data)) {
                Debug::addMessage('errors', '<i>Exception</i>', 'image_data must not be empty', 'session');
            }
            return new self('data://application/octet-stream;base64,' . base64_encode($image_data));
        }
        
        /**
         * addFilter
         *
         * @param callable $filter
         * @return $this
         */
        public function addFilter(callable $filter): static
        {
            $this->filters[] = $filter;
            return $this;
        }
        
        
        /**
         * __toString
         *
         * @return false|string
         */
        public function __toString()
        {
            return $this->getImageAsString();
        }
        
        
        /**
         * getImageAsString
         *
         * @param $image_type
         * @param $quality
         * @return false|string
         */
        public function getImageAsString($image_type = null, $quality = null): false|string
        {
            $string_temp = tempnam(sys_get_temp_dir(), '');
            $this->save($string_temp, $image_type, $quality);
            $string = file_get_contents($string_temp);
            unlink($string_temp);
            
            return $string;
        }
        
        /**
         * save
         *
         * @param $filename
         * @param $image_type
         * @param $quality
         * @param $permissions
         * @param $exact_size
         * @return $this
         */
        public function save($filename, $image_type = null, $quality = null, $permissions = null, $exact_size = false): static
        {
            $image_type = $image_type ? : $this->source_type;
            $quality = is_numeric($quality) ? (int) abs($quality) : null;
            $dest_image = null;
            
            switch ($image_type) {
                case IMAGETYPE_GIF:
                    if (!empty($exact_size) && is_array($exact_size)) {
                        $dest_image = imagecreatetruecolor($exact_size[0], $exact_size[1]);
                    } else {
                        $dest_image = imagecreatetruecolor($this->getDestWidth(), $this->getDestHeight());
                    }
                    
                    $background = imagecolorallocatealpha($dest_image, 255, 255, 255, 1);
                    imagecolortransparent($dest_image, $background);
                    imagefill($dest_image, 0, 0, $background);
                    imagesavealpha($dest_image, true);
                    break;
                
                case IMAGETYPE_JPEG:
                    if (!empty($exact_size) && is_array($exact_size)) {
                        $dest_image = imagecreatetruecolor($exact_size[0], $exact_size[1]);
                        $background = imagecolorallocate($dest_image, 255, 255, 255);
                        imagefilledrectangle($dest_image, 0, 0, $exact_size[0], $exact_size[1], $background);
                    } else {
                        $dest_image = imagecreatetruecolor($this->getDestWidth(), $this->getDestHeight());
                        $background = imagecolorallocate($dest_image, 255, 255, 255);
                        imagefilledrectangle($dest_image, 0, 0, $this->getDestWidth(), $this->getDestHeight(), $background);
                    }
                    break;
                
                case IMAGETYPE_WEBP:
                    if (version_compare(PHP_VERSION, '5.5.0', '<')) {
                        Debug::addMessage('errors', '<i>Exception</i>', 'For WebP support PHP >= 5.5.0 is required', 'session');
                    }
                    if (!empty($exact_size) && is_array($exact_size)) {
                        $dest_image = imagecreatetruecolor($exact_size[0], $exact_size[1]);
                        $background = imagecolorallocate($dest_image, 255, 255, 255);
                        imagefilledrectangle($dest_image, 0, 0, $exact_size[0], $exact_size[1], $background);
                    } else {
                        $dest_image = imagecreatetruecolor($this->getDestWidth(), $this->getDestHeight());
                        $background = imagecolorallocate($dest_image, 255, 255, 255);
                        imagefilledrectangle($dest_image, 0, 0, $this->getDestWidth(), $this->getDestHeight(), $background);
                    }
                    
                    imagealphablending($dest_image, false);
                    imagesavealpha($dest_image, true);
                    
                    break;
                
                case IMAGETYPE_PNG:
                    if (!$this->quality_truecolor || !imageistruecolor($this->source_image)) {
                        if (!empty($exact_size) && is_array($exact_size)) {
                            $dest_image = imagecreate($exact_size[0], $exact_size[1]);
                        } else {
                            $dest_image = imagecreate($this->getDestWidth(), $this->getDestHeight());
                        }
                    } else {
                        if (!empty($exact_size) && is_array($exact_size)) {
                            $dest_image = imagecreatetruecolor($exact_size[0], $exact_size[1]);
                        } else {
                            $dest_image = imagecreatetruecolor($this->getDestWidth(), $this->getDestHeight());
                        }
                    }
                    
                    imagealphablending($dest_image, false);
                    imagesavealpha($dest_image, true);
                    
                    $background = imagecolorallocatealpha($dest_image, 255, 255, 255, 127);
                    imagecolortransparent($dest_image, $background);
                    imagefill($dest_image, 0, 0, $background);
                    break;
                
                case IMAGETYPE_BMP:
                    if (version_compare(PHP_VERSION, '7.2.0', '<')) {
                        Debug::addMessage('errors', '<i>Exception</i>', 'For WebP support PHP >= 7.2.0 is required', 'session');
                    }
                    
                    if (!empty($exact_size) && is_array($exact_size)) {
                        $dest_image = imagecreatetruecolor($exact_size[0], $exact_size[1]);
                        $background = imagecolorallocate($dest_image, 255, 255, 255);
                        imagefilledrectangle($dest_image, 0, 0, $exact_size[0], $exact_size[1], $background);
                    } else {
                        $dest_image = imagecreatetruecolor($this->getDestWidth(), $this->getDestHeight());
                        $background = imagecolorallocate($dest_image, 255, 255, 255);
                        imagefilledrectangle($dest_image, 0, 0, $this->getDestWidth(), $this->getDestHeight(), $background);
                    }
                    break;
            }
            
            imageinterlace($dest_image, $this->interlace);
            
            if ($this->gamma_correct) {
                imagegammacorrect($this->source_image, 2.2, 1.0);
            }
            
            if (!empty($exact_size) && is_array($exact_size)) {
                if ($this->getSourceHeight() < $this->getSourceWidth()) {
                    $this->dest_x = 0;
                    $this->dest_y = ($exact_size[1] - $this->getDestHeight()) / 2;
                }
                if ($this->getSourceHeight() > $this->getSourceWidth()) {
                    $this->dest_x = ($exact_size[0] - $this->getDestWidth()) / 2;
                    $this->dest_y = 0;
                }
            }
            
            imagecopyresampled($dest_image, $this->source_image, $this->dest_x, $this->dest_y, $this->source_x, $this->source_y, $this->getDestWidth(), $this->getDestHeight(), $this->source_w, $this->source_h);
            
            if ($this->gamma_correct) {
                imagegammacorrect($dest_image, 1.0, 2.2);
            }
            
            $this->applyFilter($dest_image);
            
            switch ($image_type) {
                case IMAGETYPE_GIF:
                    imagegif($dest_image, $filename);
                    break;
                
                case IMAGETYPE_JPEG:
                    if ($quality === null || $quality > 100) {
                        $quality = $this->quality_jpg;
                    }
                    
                    imagejpeg($dest_image, $filename, $quality);
                    break;
                
                case IMAGETYPE_WEBP:
                    if (version_compare(PHP_VERSION, '5.5.0', '<')) {
                        Debug::addMessage('errors', '<i>Exception</i>', 'For WebP support PHP >= 5.5.0 is required', 'session');
                    }
                    if ($quality === null) {
                        $quality = $this->quality_webp;
                    }
                    
                    imagewebp($dest_image, $filename, $quality);
                    break;
                
                case IMAGETYPE_PNG:
                    if ($quality === null || $quality > 9) {
                        $quality = $this->quality_png;
                    }
                    
                    imagepng($dest_image, $filename, $quality);
                    break;
                
                case IMAGETYPE_BMP:
                    imagebmp($dest_image, $filename, $quality);
                    break;
            }
            
            if ($permissions) {
                chmod($filename, $permissions);
            }
            
            imagedestroy($dest_image);
            
            return $this;
        }
        
        /**
         * getDestWidth
         *
         * @return int
         */
        public function getDestWidth(): int
        {
            return $this->dest_w;
        }
        
        
        /**
         * getDestHeight
         *
         * @return int
         */
        public function getDestHeight(): int
        {
            return $this->dest_h;
        }
        
        
        /**
         * applyFilter
         *
         * @param $image
         * @param $filterType
         * @return void
         */
        protected function applyFilter($image, $filterType = IMG_FILTER_NEGATE): void
        {
            foreach ($this->filters as $function) {
                $function($image, $filterType);
            }
        }
        
        
        /**
         * output
         *
         * @param $image_type
         * @param $quality
         * @return void
         */
        public function output($image_type = null, $quality = null): void
        {
            $image_type = $image_type ? : $this->source_type;
            header('Content-Type: ' . image_type_to_mime_type($image_type));
            $this->save(null, $image_type, $quality);
        }
        
        /**
         * resizeToShortSide
         *
         * @param $max_short
         * @param $allow_enlarge
         * @return $this
         */
        public function resizeToShortSide($max_short, $allow_enlarge = false): static
        {
            if ($this->getSourceHeight() < $this->getSourceWidth()) {
                $ratio = $max_short / $this->getSourceHeight();
                $long = (int) ($this->getSourceWidth() * $ratio);
                $this->resize($long, $max_short, $allow_enlarge);
            } else {
                $ratio = $max_short / $this->getSourceWidth();
                $long = (int) ($this->getSourceHeight() * $ratio);
                $this->resize($max_short, $long, $allow_enlarge);
            }
            
            return $this;
        }
        
        /**
         * resizeToLongSide
         *
         * @param $max_long
         * @param $allow_enlarge
         * @return $this
         */
        public function resizeToLongSide($max_long, $allow_enlarge = false): static
        {
            if ($this->getSourceHeight() > $this->getSourceWidth()) {
                $ratio = $max_long / $this->getSourceHeight();
                $short = (int) ($this->getSourceWidth() * $ratio);
                $this->resize($short, $max_long, $allow_enlarge);
            } else {
                $ratio = $max_long / $this->getSourceWidth();
                $short = (int) ($this->getSourceHeight() * $ratio);
                $this->resize($max_long, $short, $allow_enlarge);
            }
            
            return $this;
        }
        
        /**
         * bestFit
         *
         * @param $max_width
         * @param $max_height
         * @param $allow_enlarge
         * @return $this
         */
        public function bestFit($max_width, $max_height, $allow_enlarge = false): static
        {
            if ($this->getSourceWidth() <= $max_width && $this->getSourceHeight() <= $max_height && $allow_enlarge === false) {
                return $this;
            }
            
            $ratio = $this->getSourceHeight() / $this->getSourceWidth();
            $width = $max_width;
            $height = (int) ($width * $ratio);
            
            if ($height > $max_height) {
                $height = $max_height;
                $width = (int) ($height / $ratio);
            }
            
            return $this->resize($width, $height, $allow_enlarge);
        }
        
        /**
         * thumbnail
         *
         * @param $width
         * @param $height
         * @param $allow_enlarge
         * @return $this
         */
        public function thumbnail($width, $height, $allow_enlarge = false): static
        {
            $height = $height ? : $width;
            
            // Determine aspect ratios
            $current_aspect_ratio = $this->getSourceHeight() / $this->getSourceWidth();
            $new_aspect_ratio = $height / $width;
            
            // Fit to height/width
            if ($new_aspect_ratio > $current_aspect_ratio) {
                $this->resizeToHeight($height);
            } else {
                $this->resizeToWidth($width);
            }
            //floor(($this->getSourceHeight() / 2) - ($width / 2)); //left
            //floor(($this->getSourceWidth() / 2) - ($height / 2)); //top
            
            return $this->resize($width, $height, $allow_enlarge);
        }
        
        /**
         * resizeToHeight
         *
         * @param $height
         * @param $allow_enlarge
         * @return $this
         */
        public function resizeToHeight($height, $allow_enlarge = false): static
        {
            $ratio = $height / $this->getSourceHeight();
            $width = (int) ($this->getSourceWidth() * $ratio);
            $this->resize($width, $height, $allow_enlarge);
            
            return $this;
        }
        
        
        /**
         * resizeToWidth
         *
         * @param $width
         * @param $allow_enlarge
         * @return $this
         */
        public function resizeToWidth($width, $allow_enlarge = false): static
        {
            $ratio = $width / $this->getSourceWidth();
            $height = (int) ($this->getSourceHeight() * $ratio);
            $this->resize($width, $height, $allow_enlarge);
            
            return $this;
        }
        
        /**
         * scale
         *
         * @param $scale
         * @return $this
         */
        public function scale($scale): static
        {
            $width = (int) ($this->getSourceWidth() * $scale / 100);
            $height = (int) ($this->getSourceHeight() * $scale / 100);
            $this->resize($width, $height, true);
            
            return $this;
        }
        
        /**
         * freeCrop
         *
         * @param $width
         * @param $height
         * @param $x
         * @param $y
         * @return $this
         */
        public function freeCrop($width, $height, $x = false, $y = false): static
        {
            if ($x === false || $y === false) {
                return $this->crop($width, $height);
            }
            $this->source_x = $x;
            $this->source_y = $y;
            if ($width > $this->getSourceWidth() - $x) {
                $this->source_w = $this->getSourceWidth() - $x;
            } else {
                $this->source_w = $width;
            }
            
            if ($height > $this->getSourceHeight() - $y) {
                $this->source_h = $this->getSourceHeight() - $y;
            } else {
                $this->source_h = $height;
            }
            
            $this->dest_w = $width;
            $this->dest_h = $height;
            
            return $this;
        }
        
        /**
         * crop
         *
         * @param $width
         * @param $height
         * @param $allow_enlarge
         * @param $position
         * @return $this
         */
        public function crop($width, $height, $allow_enlarge = false, $position = self::CROPCENTER): static
        {
            if (!$allow_enlarge) {
                if ($width > $this->getSourceWidth()) {
                    $width = $this->getSourceWidth();
                }
                
                if ($height > $this->getSourceHeight()) {
                    $height = $this->getSourceHeight();
                }
            }
            
            $ratio_source = $this->getSourceWidth() / $this->getSourceHeight();
            $ratio_dest = $width / $height;
            
            if ($ratio_dest < $ratio_source) {
                $this->resizeToHeight($height, $allow_enlarge);
                
                $excess_width = (int) (($this->getDestWidth() - $width) * $this->getSourceWidth() / $this->getDestWidth());
                
                $this->source_w = $this->getSourceWidth() - $excess_width;
                $this->source_x = $this->getCropPosition($excess_width, $position);
                
                $this->dest_w = $width;
            } else {
                $this->resizeToWidth($width, $allow_enlarge);
                
                $excess_height = (int) (($this->getDestHeight() - $height) * $this->getSourceHeight() / $this->getDestHeight());
                
                $this->source_h = $this->getSourceHeight() - $excess_height;
                $this->source_y = $this->getCropPosition($excess_height, $position);
                
                $this->dest_h = $height;
            }
            
            return $this;
        }
        
        /**
         * getCropPosition
         *
         * @param $expectedSize
         * @param $position
         * @return int
         */
        protected function getCropPosition($expectedSize, $position = self::CROPCENTER): int
        {
            $size = 0;
            switch ($position) {
                case self::CROPBOTTOM:
                case self::CROPRIGHT:
                    $size = $expectedSize;
                    break;
                case self::CROPCENTER:
                case self::CROPCENTRE:
                    $size = $expectedSize / 2;
                    break;
                case self::CROPTOPCENTER:
                    $size = $expectedSize / 4;
                    break;
            }
            return (int) $size;
        }
        
        /**
         * overlay
         *
         * @param $overlay
         * @param $position
         * @param $opacity
         * @param $x_offset
         * @param $y_offset
         * @return $this
         */
        public function overlay($overlay, $position = 'center', $opacity = 1, $x_offset = 0, $y_offset = 0): static
        {
            // Load overlay image
            if (!($overlay instanceof Image)) {
                $overlay = new Image($overlay);
            }
            
            // Convert opacity
            $opacity = $opacity * 100;
            
            // Determine position
            switch (strtolower($position)) {
                case 'top left':
                    $x = 0 + $x_offset;
                    $y = 0 + $y_offset;
                    break;
                case 'top right':
                    $x = $this->original_w - $overlay->original_w + $x_offset;
                    $y = 0 + $y_offset;
                    break;
                case 'top':
                    $x = ($this->original_w / 2) - ($overlay->original_w / 2) + $x_offset;
                    $y = 0 + $y_offset;
                    break;
                case 'bottom left':
                    $x = 0 + $x_offset;
                    $y = $this->original_h - $overlay->original_h + $y_offset;
                    break;
                case 'bottom right':
                    $x = $this->original_w - $overlay->original_w + $x_offset;
                    $y = $this->original_h - $overlay->original_h + $y_offset;
                    break;
                case 'bottom':
                    $x = ($this->original_w / 2) - ($overlay->original_w / 2) + $x_offset;
                    $y = $this->original_h - $overlay->original_h + $y_offset;
                    break;
                case 'left':
                    $x = 0 + $x_offset;
                    $y = ($this->original_h / 2) - ($overlay->original_h / 2) + $y_offset;
                    break;
                case 'right':
                    $x = $this->original_w - $overlay->original_w + $x_offset;
                    $y = ($this->original_h / 2) - ($overlay->original_h / 2) + $y_offset;
                    break;
                case 'center':
                default:
                    $x = ($this->original_w / 2) - ($overlay->original_w / 2) + $x_offset;
                    $y = ($this->original_h / 2) - ($overlay->original_h / 2) + $y_offset;
                    break;
            }
            
            // Perform the overlay
            $this->imageCopyMergeAlpha($this->source_image, $overlay->source_image, $x, $y, 0, 0, $overlay->original_w, $overlay->original_h, $opacity);
            
            return $this;
            
        }
        
        /**
         * imageCopyMergeAlpha
         *
         * @param $dst_im
         * @param $src_im
         * @param $dst_x
         * @param $dst_y
         * @param $src_x
         * @param $src_y
         * @param $src_w
         * @param $src_h
         * @param $pct
         * @return void
         */
        protected function imageCopyMergeAlpha($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $pct): void
        {
            
            // Get image width and height and percentage
            $pct /= 100;
            $w = imagesx($src_im);
            $h = imagesy($src_im);
            
            // Turn alpha blending off
            imagealphablending($src_im, false);
            
            // Find the most opaque pixel in the image (the one with the smallest alpha value)
            $minalpha = 127;
            for ($x = 0; $x < $w; $x++) {
                for ($y = 0; $y < $h; $y++) {
                    $alpha = (imagecolorat($src_im, $x, $y) >> 24) & 0xFF;
                    if ($alpha < $minalpha) {
                        $minalpha = $alpha;
                    }
                }
            }
            
            // Loop through image pixels and modify alpha for each
            for ($x = 0; $x < $w; $x++) {
                for ($y = 0; $y < $h; $y++) {
                    // Get current alpha value (represents the TRANSPARENCY!)
                    $colorxy = imagecolorat($src_im, $x, $y);
                    $alpha = ($colorxy >> 24) & 0xFF;
                    // Calculate new alpha
                    if ($minalpha !== 127) {
                        $alpha = 127 + 127 * $pct * ($alpha - 127) / (127 - $minalpha);
                    } else {
                        $alpha += 127 * $pct;
                    }
                    // Get the color index with new alpha
                    $alphacolorxy = imagecolorallocatealpha($src_im, ($colorxy >> 16) & 0xFF, ($colorxy >> 8) & 0xFF, $colorxy & 0xFF, (int) $alpha);
                    // Set pixel with the new color + opacity
                    if (!imagesetpixel($src_im, $x, $y, $alphacolorxy)) {
                        return;
                    }
                }
            }
            
            // Copy it
            imagesavealpha($dst_im, true);
            imagealphablending($dst_im, true);
            imagesavealpha($src_im, true);
            imagealphablending($src_im, true);
            imagecopy($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h);
        }
        
        /**
         * gamma
         *
         * @param $enable
         * @return $this
         */
        public function gamma($enable = false): static
        {
            $this->gamma_correct = $enable;
            
            return $this;
        }
    }