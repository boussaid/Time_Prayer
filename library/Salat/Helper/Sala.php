<?php

class Salat_Helper_Sala {

    public static function julian_day($y, $m, $d) {
        $day =((367*$y)-((INT)((7/4)*($y+(INT)(($m+9)/12))))+(INT)(275*($m/9))+$d-730531.5);
        return $day;
    }
    
    
public static function sun_long($julian_day) {
        $l=  (280.461+0.9856474*$julian_day);
        if($l>360) {
            $l= (($l/360)-(INT)($l/360))*360;
        }

        return $l;
    }

    public static function sun_period($julian_day)
    {
        $m = (357.528+0.9856003*$julian_day);
        if($m>360)
        {
            $m= (($m/360)-(INT)($m/360))*360;
        }

        return $m;
    }
    
    public static function sun_wave_long($sun_long, $sun_period, $pi)
    {
        $Lambda = $sun_long +1.915*SIN($sun_period*$pi/180)+0.02*SIN(2* $sun_period*$pi/180);
        return $Lambda;
    }
    
    // obliquity
    
public static function obliquity($julian_day)
    {
        $Obliquity = (23.439-0.0000004*$julian_day);
        return $Obliquity;
    }
    
    // alpha
    
    public static function alpha($Obliquity, $Lambda, $pi)
    {

        $Alpha =ATAN (COS($Obliquity*$pi/180)*TAN($Lambda*$pi/180))*180/$pi;
        if($Alpha>90 AND $Alpha<180)
        {
            $Alpha= $Alpha + 180;
        }
        else
        {
            $Alpha= $Alpha + 360;
        }

        $Alpha=  $Alpha + 90 * (((INT)($Lambda/90))-((INT)($Alpha/90)));



        return $Alpha;
    }
    
    //  ST
    
    public static function starry_time($julian_day)
    {
        $ST = (100.46+0.985647352*$julian_day);
        if ($ST>360)
        {
            $ST=  (($ST/360)-((INT)($ST/360)))*360;
        }
        return $ST;
    }
    

    // Dec
    
    public static function sun_obliquity($Obliquity, $Lambda, $pi) {
        $Dec = ASIN(SIN($Obliquity*$pi/180)*SIN($Lambda*$pi/180))*180/$pi;
        return $Dec;
    }
    
    // noon
    
    public static function sun_miday($Alpha, $ST) {
        $noon = ($Alpha - $ST);
        if($noon<0)
        {
            $noon = $noon+360;
        }
        return $noon;
    }
    
    // UT NOON
    
    public static function international_midday($noon, $long) {
        $UT_noon = $noon - $long;
        return $UT_noon;
    }
    
    // local_noon
    
    public static function local_noon($UT_noon, $zone) {
        $local_noon = $UT_noon/15+$zone;
        return $local_noon;
    }

    // وقت صلاة الظهر
     
    public static function zuhr_time($local_noon) {
        $timeh = floor ($local_noon);
        $timeh2 = ($timeh < 10) ? '0'.$timeh : $timeh ;
        $timem = floor (($local_noon - (INT)($local_noon))*60);
        $timem2 = ($timem < 10) ? '0'.$timem : $timem ;
        $zuhr_prayer = "$timeh2:$timem2";
        
        return $zuhr_prayer;

    }
    
    // Asr_Alt

    public static function asr_alt($lat, $Dec, $pi) {
        $Asr_Alt= ATAN(1+TAN(($lat-$Dec)*$pi/180))*180/$pi;
        return $Asr_Alt;
    }
    
    // حساب وقت صلاة العصر - المذهب الحنفي
    
      public static function asr_alt2($lat, $Dec, $pi) {
        $Asr_Alt2= ATAN(2+TAN(($lat-$Dec)*$pi/180))*180/$pi;
        return $Asr_Alt2;
    }
    
    // AsrArc
    public static function asr_arc($AsrAlt, $lat, $Dec, $pi) {
        $AsrArc= ACOS((SIN((90-$AsrAlt)*$pi/180)-SIN($Dec*$pi/180)*SIN($lat*$pi/180))/(COS($Dec*$pi/180)*COS($lat*$pi/180)))*180/$pi/15; 
        return $AsrArc;
    }
    
    public static function asr_time($local_noon, $AsrArc) {
        $AsrTime = $local_noon + $AsrArc; 
        return $AsrTime;
    }
    
    // حساب وقت صلاة العصر - المذهب الحنفي
    public static function asr_arc2($AsrAlt2, $lat, $Dec, $pi) {
        $AsrArc2= ACOS((SIN((90-$AsrAlt2)*$pi/180)-SIN($Dec*$pi/180)*SIN($lat*$pi/180))/(COS($Dec*$pi/180)*COS($lat*$pi/180)))*180/$pi/15; 
        return $AsrArc2;
    }
    
    public static function asr_time2($local_noon, $AsrArc2) {
        $AsrTime2 = $local_noon + $AsrArc2; 
        return $AsrTime2;
    }   

    // وقت صلاة العصر

    public static function asr_prayer_time($AsrTime) {
        $timeh = floor ($AsrTime);
        $timeh= $timeh;
        $timeh2 = ($timeh < 10) ? '0'.$timeh : $timeh ;
        $timem = floor (($AsrTime - (INT)($AsrTime))*60);
        $timem2 = ($timem < 10) ? '0'.$timem : $timem ;
        $asr_prayer = "$timeh2:$timem2";

        return $asr_prayer;

    } 
    
    // وقت صلاة العصر - المذهب الحنفي

    public static function asr_prayer_time2($AsrTime2) {
        $timeh = floor ($AsrTime2);
        $timeh= $timeh;
        $timeh2 = ($timeh < 10) ? '0'.$timeh : $timeh ;
        $timem = floor (($AsrTime2 - (INT)($AsrTime2))*60);
        $timem2 = ($timem < 10) ? '0'.$timem : $timem ;
        $asr_prayer2 = "$timeh2:$timem2";

        return $asr_prayer2;

    }  

    // Durinal_Arc
    
    public static function Durinal_Arc ($lat, $Dec, $pi) {
        $Durinal_Arc= Acos((SIN(-0.8333*$pi/180)-SIN($Dec*$pi/180)*SIN($lat*$pi/180))/(COS($Dec*$pi/180)*COS($lat*$pi/180)))*180/$pi;
        return $Durinal_Arc;
    }
    

    // sun_rise
    
    public static function sun_rise($local_noon, $Durinal_Arc) {
        $sun_rise= $local_noon - ($Durinal_Arc/15);
        
        return $sun_rise;
    }
    
    // وقت شروق الشمس
    
    public static function shrouk_prayer($sun_rise) {
        $timeh= floor($sun_rise);
        $timeh2 = ($timeh < 10) ? '0'.$timeh : $timeh ;
        $timem = floor (($sun_rise - (INT)($sun_rise))*60);
        $timem2 = ($timem < 10) ? '0'.$timem : $timem ;
        $sun_rise_prayer = "$timeh2:$timem2";
        
        return $sun_rise_prayer;
    }
    
    // sun_set
    
    public static function sun_set($local_noon, $Durinal_Arc) {
        $sun_set= $local_noon + ($Durinal_Arc/15);

        return $sun_set;
    }
    
     // وقت غروب الشمس

public static function goroub_prayer($sun_set) {
        $timeh= floor($sun_set);
        $timeh= $timeh;
        $timeh2 = ($timeh < 10) ? '0'.$timeh : $timeh ;
        $timem = floor (($sun_set - (INT)($sun_set))*60);
        $timem2 = ($timem < 10) ? '0'.$timem : $timem ;
        $goroub_prayer = "$timeh2:$timem2";

        return $goroub_prayer;
    }

    // وقت صلاة المغرب
    
    public static function maghrib_prayer($sun_set) {
        $timeh= floor($sun_set);
        $timeh= $timeh;
        $timeh2 = ($timeh < 10) ? '0'.$timeh : $timeh;
        $timem = floor (($sun_set - (INT)($sun_set))*60);
        $timem2 = ($timem < 10) ? '0'.$timem : $timem;
        $maghrib_prayer = "$timeh2:$timem2";
        
        return $maghrib_prayer;
    }
    
    // حساب وقت صلاة المغرب - المذهب الجعفري
    
    public static function eshaain_prayer($sun_set) {
        $timeh= floor($sun_set);
        $timeh= $timeh;
        $timeh2 = ($timeh < 10) ? '0'.$timeh : $timeh ;
        $timem = floor (($sun_set - (INT)($sun_set))*60 +15);
        $timem2 = ($timem < 10) ? '0'.$timem : $timem ;
        $eshaain_prayer = "$timeh2:$timem2";

        return $eshaain_prayer;
    }
    
    
    
    // Esha_Arc

    public static function esha_arc($lat, $Dec, $pi)
    {
        $Esha_Arc= Acos((SIN(-17*$pi/180)-SIN($Dec*$pi/180)*SIN($lat*$pi/180))/(COS($Dec*$pi/180)*COS($lat*$pi/180)))*180/$pi;
        return $Esha_Arc;
    }
    
    // esha_time
 
    public static function esha_time($local_noon, $Esha_Arc)
    {
        $esha_time= $local_noon + ($Esha_Arc/15);
        return $esha_time;
    }
    
    // وقت صلاة العشاء
    
    public static function esha_prayer($esha_time){
        $timeh= floor($esha_time);
        $timeh= $timeh;
        $timeh2 = ($timeh < 10) ? '0'.$timeh : $timeh ;
        $timem = floor (($esha_time - (INT)($esha_time))*60);
        $timem2 = ($timem < 10) ? '0'.$timem : $timem ;
        $esha_prayer = "$timeh2:$timem2";

        return $esha_prayer;
    }
    
    
    // Fajr_Arc
    
    public static function fajr_arc($lat, $Dec, $pi)
    {
        $Fajr_Arc= Acos((SIN(-18*$pi/180)-SIN($Dec*$pi/180)*SIN($lat*$pi/180))/(COS($Dec*$pi/180)*COS($lat*$pi/180)))*180/$pi;
        return $Fajr_Arc;
    }
    
    // fajr_time
    public static function fajr_time($local_noon, $Fajr_Arc)
    {
        $fajr_time= $local_noon - ($Fajr_Arc/15);
        return $fajr_time;
    }
    
    // وقت صلاة الفجر
    public static function fajr_prayer($fajr_time)
    {
        $timeh= floor($fajr_time);
        $timeh2 = ($timeh < 10) ? '0'.$timeh : $timeh ;
        $timem = floor (($fajr_time - (INT)($fajr_time))*60);
        $timem2 = ($timem < 10) ? '0'.$timem : $timem ;
        $fajr_time = "$timeh2:$timem2";

        return $fajr_time;
    }
    
    // imsak_Arc
    
    function imsak_arc($lat, $Dec, $pi)
    {
        $imsak_Arc= Acos((SIN(-20*$pi/180)-SIN($Dec*$pi/180)*SIN($lat*$pi/180))/(COS($Dec*$pi/180)*COS($lat*$pi/180)))*180/$pi;
        return $imsak_Arc;
    }
    // imsak_time
    function imsak_time($local_noon, $imsak_Arc)
    {
        $imsak_time= $local_noon - ($imsak_Arc/15);
        return $imsak_time;
    }
    
        // وقت الامساك
    function imsak_prayer($imsak_time)
    {
        $timeh= floor($imsak_time );
        $timeh2 = ($timeh < 10) ? '0'.$timeh : $timeh ;
        $timem = floor (($imsak_time -(INT)($imsak_time))*60);
        $timem2 = ($timem < 10) ? '0'.$timem : $timem ;
        $imsak_time = "$timeh2:$timem2";

        return $imsak_time;
    }

}