<?php

/**
 * Последовательность цветов для диаграмм
 * Class Ext_Chart_Color
 */
class Ext_Chart_Color
{
    protected static $_baseColors = array(
        0x8a56e2,
        0xcf56e2,
        0xe256ae,
        0xe25668,
        0xe28956,
        0xe2cf56,
        0xaee256,
        0x68e256,
        0x56e289,
        0x56e2cf,
        0x56aee2,
        0x5668e2
    );
    /**
     * @param $base
     * @param $length
     * @return array
     */
    public static function getColorsSeries($length, $base = null)
    {
        if (!$base) {
            $base = self::$_baseColors[$length % sizeof(self::$_baseColors)];
        }
        $baseColor = new Ext_Chart_Color_Rgb($base);
        $baseHsv = Ext_Chart_Color_Hsv::rgb2hsv($baseColor);

        $result = array(
            $baseColor
        );

        if ($length <= 1) {
            return $result;
        }

        $currentHue = $baseHsv->h;
        $step = ceil(360 / $length);

        for ($i = 1; $i < $length; $i++) {
            if(($currentHue += $step) > 360) {
                $currentHue -= 360;
            }

            $result[] = new Ext_Chart_Color_Rgb(
                new Ext_Chart_Color_Hsv(array(
                    $currentHue,
                    $baseHsv->s,
                    $baseHsv->v
                ))
            );
        }

        return $result;
    }
}
