<?php

setlocale(LC_ALL, 'ru_RU', 'rus_RUS', 'Russian');

echo "<div class=\"span6 hidden-phone hidden-tablet\">";
echo "<div class=\"row\">";
echo "<div class=\"span1\">";
echo "<img src=\"img/31-l.png\" align=\"center\">";
echo "</div>";
echo "<div class=\"span5\" align=\"left\">";
echo "<h2 align=\"center\">Монитор температуры</h2>";
echo "<p class=\"lead\" style=\"text-align:center; font-style:italic\">Данные с температурных датчиков обновляются каждые 3  минуты</p>";
echo "</div>";
echo "</div>";

$lat    = 56.719002; // North
$long   = 36.772204; // East
$offset = 3; // difference between GMT and local time in hours

$zenith = 90 + 50 / 60;

echo "<img src=\"img/sonne093.gif\">";
echo "Восход: <span style=\"font-size:1.1em; font-weight:700\">" . date_sunrise(time(), SUNFUNCS_RET_STRING, $lat, $long, $zenith, $offset) . ".</span>";
echo " Закат: <span style=\"font-size:1.1em; font-weight:700\">" . date_sunset(time(), SUNFUNCS_RET_STRING, $lat, $long, $zenith, $offset) . "</span></br>";

function rusdate($d, $format = 'j %MONTH% Y', $offset = 0)
{
    $montharr = array(
        'января',
        'февраля',
        'марта',
        'апреля',
        'мая',
        'июня',
        'июля',
        'августа',
        'сентября',
        'октября',
        'ноября',
        'декабря'
    );
    $dayarr   = array(
        'понедельник',
        'вторник',
        'среда',
        'четверг',
        'пятница',
        'суббота',
        'воскресенье'
    );
    
    $d += 3600 * $offset;
    
    $sarr = array(
        '/%MONTH%/i',
        '/%DAYWEEK%/i'
    );
    $rarr = array(
        $montharr[date("m", $d) - 1],
        $dayarr[date("N", $d) - 1]
    );
    
    $format = preg_replace($sarr, $rarr, $format);
    return date($format, $d);
}
$filename = 'data/temperature.xml';
if (file_exists($filename)) {
    echo "Последнее обновление: <strong>" . rusdate(filemtime($filename), '%DAYWEEK%, j %MONTH% Y, G:i:s') . "</strong>";
}
$city_id   = 27415; // id города
$data_file = "data/yandex-weather.xml"; // адрес xml файла 

$xml = simplexml_load_file($data_file); // раскладываем xml на массив


// выбираем требуемые параметры (город, температура, пиктограмма и тип погоды текстом (облачно, ясно)

$city             = $xml->fact->station;
$temp             = $xml->fact->temperature;
$pic              = $xml->fact->image;
$weather_type     = $xml->fact->weather_type;
$observation_time = strftime("%d.%m.%Y г. в %H:%M:%S", strtotime($xml->fact->observation_time));
$color            = $xml->fact->temperature['color'];
$plate            = $xml->fact->temperature['plate'];
;
$weather_condition_code = $xml->fact->weather_condition['code'];
$wind_direction         = $xml->fact->wind_direction;
$wind_speed             = $xml->fact->wind_speed;
$humidity               = $xml->fact->humidity;
$pressure               = $xml->fact->pressure;
$image_v2               = $xml->fact->{'image-v2'};
$image_v3               = $xml->fact->{'image-v3'};

// Если значение температуры положительно, для наглядности добавляем "+"
if ($temp > 0) {
    $temp = '+' . $temp;
}

echo "<div id=\"yandex-weather\">";

echo ("<a href=\"http://pogoda.yandex.ru/?ncrnd=$city_id\"><h3>Погода по данным Яндекса</h3></a>");
// echo ("<img src=\"http://img.yandex.net/i/wiz$pic.png\" alt=\"$weather_type\" title=\"$weather_type\">$temp<sup>o</sup>C");
echo ("<div class='container'><div class='row'>");
echo ("<div class='span2' style='background-color:#$color; padding:7px'><span style='font-size:2em; font-weight:500'>$temp<sup>o</sup>C</span></br>");
echo ("<img class=\"b-ico\" src=\"http://yandex.st/weather/1.2.1/i/icons/48x48/$image_v3.png\" alt=\"$weather_type\" title=\"$weather_type\" onclick=\"return {name:'b-ico'}\"></br>$weather_type</div>");
echo ("<div class='span4' style='text-align:left'>Давление: <span style=\"font-weight:700\">$pressure мм рт. ст.</span></br>Влажность: <span style=\"font-weight:700\">$humidity%</span></br>Ветер: <span style=\"font-weight:700\">$wind_direction ($wind_speed м/с)</span></br>Данные зарегистрированы: <span style=\"font-weight:700\">$observation_time</span></div></div>");
echo ("</div>");

echo "</div>";
echo "</div>";
echo "<div class=\"span6\">";
echo "<table class=\"table table-bordered\">";
echo "<thead>";
echo "<tr>";
echo "<th>Местоположение</th>";
echo "<th>Текущая температура</th>";
echo "</tr>";
echo "</thead>";

//setLocale(LC_ALL, 'en_EN.UTF-8');
function exp_to_dec($float_str)
// formats a floating point number string in decimal notation, supports signed floats, also supports non-standard formatting e.g. 0.2e+2 for 20
    
// e.g. '1.6E+6' to '1600000', '-4.566e-12' to '-0.000000000004566', '+34e+10' to '340000000000'
    
// Author: Bob
{
    // make sure its a standard php float string (i.e. change 0.2e+2 to 20)
    // php will automatically format floats decimally if they are within a certain range
    $float_str = (string) ((float) ($float_str));
    
    // if there is an E in the float string
    if (($pos = strpos(strtolower($float_str), 'e')) !== false) {
        // get either side of the E, e.g. 1.6E+6 => exp E+6, num 1.6
        $exp = substr($float_str, $pos + 1);
        $num = substr($float_str, 0, $pos);
        
        // strip off num sign, if there is one, and leave it off if its + (not required)
        if ((($num_sign = $num[0]) === '+') || ($num_sign === '-'))
            $num = substr($num, 1);
        else
            $num_sign = '';
        if ($num_sign === '+')
            $num_sign = '';
        
        // strip off exponential sign ('+' or '-' as in 'E+6') if there is one, otherwise throw error, e.g. E+6 => '+'
        if ((($exp_sign = $exp[0]) === '+') || ($exp_sign === '-'))
            $exp = substr($exp, 1);
        else
            trigger_error("Could not convert exponential notation to decimal notation: invalid float string '$float_str'", E_USER_ERROR);
        
        // get the number of decimal places to the right of the decimal point (or 0 if there is no dec point), e.g., 1.6 => 1
        $right_dec_places = (($dec_pos = strpos($num, '.')) === false) ? 0 : strlen(substr($num, $dec_pos + 1));
        // get the number of decimal places to the left of the decimal point (or the length of the entire num if there is no dec point), e.g. 1.6 => 1
        $left_dec_places  = ($dec_pos === false) ? strlen($num) : strlen(substr($num, 0, $dec_pos));
        
        // work out number of zeros from exp, exp sign and dec places, e.g. exp 6, exp sign +, dec places 1 => num zeros 5
        if ($exp_sign === '+')
            $num_zeros = $exp - $right_dec_places;
        else
            $num_zeros = $exp - $left_dec_places;
        
        // build a string with $num_zeros zeros, e.g. '0' 5 times => '00000'
        $zeros = str_pad('', $num_zeros, '0');
        
        // strip decimal from num, e.g. 1.6 => 16
        if ($dec_pos !== false)
            $num = str_replace('.', '', $num);
        
        // if positive exponent, return like 1600000
        if ($exp_sign === '+')
            return $num_sign . $num . $zeros;
        // if negative exponent, return like 0.0000016
        else
            return $num_sign . '0.' . $zeros . $num;
    }
    // otherwise, assume already in decimal notation and return
    else
        return $float_str;
}
$temp_xml        = simplexml_load_file('data/temperature.xml');
$temp_xml_data   = $temp_xml->data;
$temp_xml_row    = $temp_xml_data->row[0];
$temp_xml_meta   = $temp_xml->meta;
$temp_xml_legend = $temp_xml_meta->legend[0];
foreach ($temp_xml_row->v as $v) {
    $mytemp            = $v->asXML();
    $mytemp            = exp_to_dec(strip_tags($mytemp));
    $myfloattemp[]     = $mytemp;
    $mytemp            = $v->asXML();
    $mytemp            = exp_to_dec(strip_tags($mytemp));
    $myroundtemp_value = round($mytemp, 0, PHP_ROUND_HALF_EVEN);
    if ($myroundtemp_value > 60)
        $myroundtemp_value = 60;
    if (abs($myroundtemp_value) & 1)
        $myroundtemp[] = $myroundtemp_value + 1;
    else
        $myroundtemp[] = $myroundtemp_value;
}
foreach ($temp_xml_legend->entry as $entry) {
    $mytemplegend[] = strip_tags($entry->asXML());
}
$count = count($myfloattemp);
for ($i = 0; $i < $count; $i++) {
    echo "<tr><td>" . $mytemplegend[$i] . "</td><td class='b-t_c_$myroundtemp[$i]'><span style='font-size:1.6em; font-weight:500'>" . sprintf("%0.1f° C", $myfloattemp[$i]) . "</span></td></tr>";
}

$press_xml        = simplexml_load_file('data/press.xml');
$press_xml_data   = $press_xml->data;
$press_xml_row    = $press_xml_data->row[0];
$press_xml_meta   = $press_xml->meta;
$press_xml_legend = $press_xml_meta->legend[0];
foreach ($press_xml_row->v as $v) {
    $mypress        = $v->asXML();
    $myfloatpress[] = exp_to_dec(strip_tags($mypress));
}
foreach ($press_xml_legend->entry as $entry) {
    $mypresslegend[] = strip_tags($entry->asXML());
}
$count = count($myfloatpress);
for ($i = 0; $i < $count; $i++) {
    echo "<tr><td>" . $mypresslegend[$i] . "</td><td><span style='font-size:1.6em; font-weight:500'>" . sprintf("%0.0f мм рт. ст.", $myfloatpress[$i]) . "</span></td></tr>";
}

$hum_xml        = simplexml_load_file('data/humid.xml');
$hum_xml_data   = $hum_xml->data;
$hum_xml_row    = $hum_xml_data->row[0];
$hum_xml_meta   = $hum_xml->meta;
$hum_xml_legend = $hum_xml_meta->legend[0];
foreach ($hum_xml_row->v as $v) {
    $myhum        = $v->asXML();
    $myfloathum[] = exp_to_dec(strip_tags($myhum));
}
foreach ($hum_xml_legend->entry as $entry) {
    $myhumlegend[] = strip_tags($entry->asXML());
}
$count = count($myfloathum);
for ($i = 0; $i < $count; $i++) {
    echo "<tr><td>" . $myhumlegend[$i] . "</td><td><span style='font-size:1.6em; font-weight:500'>" . sprintf("%0.0f", $myfloathum[$i]) . "%</span></font></td></tr>";
}
echo "</table></div>";
?>