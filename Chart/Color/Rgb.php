<?php

class Ext_Chart_Color_Rgb
{
    public $r = 0;
    public $g = 0;
    public $b = 0;

    public function __construct($color)
    {
        if (is_numeric($color)) {
            $this->_makeByHex($color);
        } else if (is_array($color)) {
            $this->_makeByRGBArray($color);
        } else if ($color instanceof Ext_Chart_Color_Hsv) {
            $this->_makeByHSVColor($color);
        }
    }

    /**
     * @param $hex
     * @return Ext_Chart_Color_Rgb
     */
    protected function _makeByHex($hex)
    {
        $hex = (int)$hex;

        return $this->_makeByRGBArray(array(
            'red'   => ($hex >> 16) & 0xFF,
            'green' => ($hex >> 8) & 0xFF,
            'blue'  => $hex & 0xFF
        ));
    }

    /**
     * @param array $rgb
     * @return $this
     */
    protected function _makeByRGBArray(array $rgb)
    {
        $rgb = array_map('intval', $rgb);
        if (sizeof($rgb) < 3) $rgb += array_fill(sizeof($rgb), 3 - sizeof($rgb), 0);
        list($this->r, $this->g, $this->b) = array_values($rgb);

        return $this;
    }

    /**
     * @param Ext_Chart_Color_Hsv $hsv
     * @return $this
     * @throws Ext_Exception
     */
    protected function _makeByHSVColor(Ext_Chart_Color_Hsv $hsv)
    {
        $hsv = clone $hsv;

        $hsv->h %= 360;
        $hsv->s /= 100;
        $hsv->v /= 100;

        $hsv->h /= 60;

        $_f = $hsv->h - floor($hsv->h);
        $_p = $hsv->v * (1 - $hsv->s);
        $_q = $hsv->v * (1 - ($hsv->s * $_f));
        $_t = $hsv->v * (1 - ($hsv->s * (1 - $_f)));

        switch (floor($hsv->h)) {
            case 0:
                $this->r = $hsv->v;
                $this->g = $_t;
                $this->b = $_p;

                break;
            case 1:
                $this->r = $_q;
                $this->g = $hsv->v;
                $this->b = $_p;

                break;
            case 2:
                $this->r = $_p;
                $this->g = $hsv->v;
                $this->b = $_t;

                break;
            case 3:
                $this->r = $_p;
                $this->g = $_q;
                $this->b = $hsv->v;

                break;
            case 4:
                $this->r = $_t;
                $this->g = $_p;
                $this->b = $hsv->v;

                break;
            case 5:
                $this->r = $hsv->v;
                $this->g = $_p;
                $this->b = $_q;

                break;
            default:
                throw new Ext_Exception('Ошибка преобразования цвета.');
        }

        $this->r = floor($this->r * 255);
        $this->g = floor($this->g * 255);
        $this->b = floor($this->b * 255);

        return $this;
    }

    public function __toString()
    {
        return dechex(($this->r << 16) + ($this->g << 8) + $this->b);
    }
} 