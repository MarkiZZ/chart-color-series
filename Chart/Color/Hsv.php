<?php

/**
 * Модель нелинейного преобразования модели RGB
 * Class Ext_Chart_Color_Hsv
 */
class Ext_Chart_Color_Hsv
{
    /**
     * Hue — цветовой тон
     * 0.0 .. 359.0
     * @var float
     */
    public $h = 0.0;

    /**
     * Saturation — насыщенность
     * 0.0 .. 100.0
     * @var float
     */
    public $s = 0.0;

    /**
     * Value (значение цвета) или Brightness — яркость
     * 0.0 .. 100.0
     * @var float
     */
    public $v = 0.0;

    public function __construct($color)
    {
        if (is_array($color)) {
            $this->_makeByHSVArray($color);
        } else if ($color instanceof Ext_Chart_Color_Rgb) {
            $this->_makeByRGBColor($color);
        }
    }

    public static function rgb2hsv(Ext_Chart_Color_Rgb $rgb)
    {
        return new static($rgb);
    }

    protected function _makeByRGBColor(Ext_Chart_Color_Rgb $rgb)
    {
        $rgb = clone $rgb;
        $rgb->r /= 255;
        $rgb->g /= 255;
        $rgb->b /= 255;

        $min = min($rgb->r, $rgb->g, $rgb->b);
        $max = max($rgb->r, $rgb->g, $rgb->b);
        if ($min == $max) {
            return $this->_makeByHSVArray(array(0, 0, $max * 100));
        }

        $delta = $rgb->r == $min
            ? $rgb->g - $rgb->b
            : ($rgb->g == $min ? $rgb->b - $rgb->r : $rgb->r - $rgb->g);
        $_i = $rgb->r == $min ? 3 : ($rgb->g == $min ? 5 : 1);

        return $this->_makeByHSVArray(array(
            floor($_i - $delta / ($max - $min)) % 360, // h
            floor((($max - $min) / $max) * 100), // s
            floor($max * 100) // v
        ));
    }

    protected function _makeByHSVArray(array $hsv)
    {
        $hsv = array_map('intval', $hsv);
        if (sizeof($hsv) < 3) $hsv += array_fill(sizeof($hsv), 3 - sizeof($hsv), 0);
        list($this->h, $this->s, $this->v) = $hsv;

        return $this;
    }
} 