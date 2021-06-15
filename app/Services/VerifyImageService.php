<?php
namespace App\Services;
use claviska\SimpleImage;
use svay\FaceDetector;

/**
 * CARACTERISTICAS A EVALUAR
 * Imagen a color con fondo blanco. Tomada de frente, sin gorra y sin lentes.
 * Sin sellos ni enmendaduras. La imagen debe enfocarse en el rostro del estudiante a partir de los hombros.
 * No mostrar medio cuerpo, ni acercarse mucho cubriendo el fondo, precausión con el cabello voluminoso.
 * De preferencia evitar las prendas blancas.
 * 
 * ANCHO DE 240 PX 
 * ALTO DE 288 PX
 * RESOLUCION EN DPI 300
 * FOTO SIN BORDES BLANCOS
 */
class VerifyImageService {
    /**
     * Check aspects of photography.
     *
     * @param SimpleImage $image Class variable to access the library.
     * @function fromFile() To access the photo.
     * @function getHeight() To get the height of the photo.
     * @function getWidth() To get the width of the photo.
     * @function getResolution() To get the resolution of the photo in dpi.
     * @function countWhitePixels() To get the total number of white bits in the photo.
     * @function toFile() To save the photo with de correct orientation.
     */
    public function getErrors($picture/*, $filename*/)
    {
        $image = new SimpleImage();
        $image->fromFile($picture);
        $errors = array();

        $height = $image->getHeight();
        $width = $image->getWidth();
        $correct_dimensions = true;
        if($height != 288){
            array_push($errors, "Alto de Imagen Fuera del Parámetro " . "(" . $height . ")");
            $correct_dimensions = false;
        }

        if($width != 240){
            array_push($errors, "Ancho de Imagen Fuera del Parámetro " . "(" . $width . ")");
            $correct_dimensions = false;
        }

        $dpi = $image->getResolution();
        if(($dpi[0] != 300 || $dpi[1] != 300)){
            array_push($errors, "La Resolución de DPI Fuera del Parámetro " . "(" . $dpi[0] . "*" . $dpi[1] . ")");
        }

        $correct_image = true;
        if($correct_dimensions){
            $c = $this->countWhitePixels($image);
            if ($c < 14500) {
                array_push($errors, "Se encuentra muy cerca o no se detectó el fondo blanco");
                $correct_image = false;
            } elseif ($c > 31000) {
                $corner1 = $image->getColorAt(1, 287);
                $corner2 = $image->getColorAt(239, 287);
                if ($this->isWhite($corner1) && $this->isWhite($corner2)) {
                    array_push($errors, "La foto no debe tener bordes blancos");
                    $correct_image = false;
                }
            }
        }

        if (!$this->hasFace($picture)){
            array_push($errors, "No se encontro un rostro en la foto");
            $correct_image = false;
        }

        return ['ok' => $correct_dimensions && $correct_image, 'errors' => $errors];
    }

    /**
     * Check the total number of white bits in the photo.
     *
     * @param array $valueX Photo width size.
     * @param integer $valueX Photo height size.
     * @return integer $count Returns the amount of white bits.
     */
    private function countWhitePixels($image)
    {
        $valueX = 240;
        $valueY = 288;
        $count = 0;
        for ($i = 0; $i < $valueX; $i++) {
            for ($j = 0; $j < $valueY; $j++) {
                $color = $image->getColorAt($i, $j);
                if ($this->isWhite($color)) {
                    $count++;
                }
            }
        }
        return $count;
    }

    /**
     * Check if the pixel is white.
     *
     * @param array $image The array color RGBA.
     */
    private function isWhite($color){
        if (($color['red'] >= 245 && $color['red'] <= 255) &&
            ($color['green'] >= 245 && $color['green'] <= 255) &&
            ($color['blue'] >= 245 && $color['blue'] <= 255)){
                return true;
        }
        return false;
    }

    /**
     * Check if the photo has a face.
     *
     * @param FaceDetector $faceDetector Class variable to access the library.
     * @function faceDetect() To open a photo.
     * @function getFace() To detect a face in the photo.
     */
    private function hasFace($picture)
    {
        $faceDetector = new FaceDetector();
        try {
            $faceDetector->faceDetect($picture);
            if (empty($faceDetector->getFace())) {
                return false;
            }
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
?>