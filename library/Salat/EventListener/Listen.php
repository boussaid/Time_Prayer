<?php
class Salat_EventListener_Listen {

public static function template_create($templateName, array &$params, XenForo_Template_Abstract $template){
	if ($templateName == 'PAGE_CONTAINER'){
         
            $template->preloadTemplate('salat');
        }
    }
 
 public static function salatte($hookName, &$contents, array $hookParams, XenForo_Template_Abstract $template)
    {
        $salatTime = new Salat_Helper_Sala();
        $options = XenForo_Application::get('options');

        $y= date("Y");
        $m= date("n");
        $d= date("j");
        // pi
        $pi = 3.14159265358979323846;
        // خط الطول
        $long = $options->prayer_longitude;
        // خط العرض
        $lat = $options->prayer_latitude;
        // فارق التوقيت
        $zone = $options->prayer_offset;

        $julian_day = $salatTime->julian_day($y, $m, $d);
        $sun_long = $salatTime->sun_long($julian_day);
        $sun_period = $salatTime->sun_period($julian_day);
        $Lambda = $salatTime->sun_wave_long($sun_long, $sun_period, $pi);
        $Obliquity = $salatTime->obliquity($julian_day);
        $Alpha = $salatTime->alpha($Obliquity, $Lambda, $pi);
        $ST = $salatTime->starry_time($julian_day);
        $Dec = $salatTime->sun_obliquity($Obliquity, $Lambda, $pi);

        $noon = $salatTime->sun_miday($Alpha, $ST);
        $UT_noon = $salatTime->international_midday($noon, $long);
        $local_noon = $salatTime->local_noon($UT_noon, $zone);
        $zuhr_prayer = $salatTime->zuhr_time($local_noon);

        $AsrAlt = $salatTime->asr_alt($lat, $Dec, $pi);
        $AsrAlt2 = $salatTime->asr_alt2($lat, $Dec, $pi);
        $AsrArc = $salatTime->asr_arc($AsrAlt, $lat, $Dec, $pi);
        $AsrArc2 = $salatTime->asr_arc2($AsrAlt2, $lat, $Dec, $pi);
        $AsrTime = $salatTime->asr_time($local_noon, $AsrArc);
        $AsrTime2 = $salatTime->asr_time2($local_noon, $AsrArc2);
        $asr_prayer = $salatTime->asr_prayer_time($AsrTime);
        $asr_prayer2 = $salatTime->asr_prayer_time2($AsrTime2);

        $Durinal_Arc = $salatTime->Durinal_Arc ($lat, $Dec, $pi);
        $sun_rise =  $salatTime->sun_rise($local_noon, $Durinal_Arc);
        $Shrouk_prayer = $salatTime->shrouk_prayer($sun_rise);
        $sun_set = $salatTime->sun_set($local_noon, $Durinal_Arc);

        $goroub_prayer = $salatTime->goroub_prayer($sun_set);
        $maghrib_prayer = $salatTime->maghrib_prayer($sun_set);
        $eshaain = $salatTime->eshaain_prayer($sun_set);

        $Esha_Arc = $salatTime->esha_arc($lat, $Dec, $pi);
        $esha_time= $salatTime->esha_time($local_noon, $Esha_Arc);
        $esha_prayer= $salatTime->esha_prayer($esha_time);

        $Fajr_Arc= $salatTime->fajr_arc($lat, $Dec, $pi);
        $fajr_time= $salatTime->fajr_time($local_noon, $Fajr_Arc);
        $fajr_prayer= $salatTime->fajr_prayer($fajr_time);

        $imsak_Arc= $salatTime->imsak_arc($lat, $Dec, $pi);
        $imsak_time= $salatTime->imsak_time($local_noon, $imsak_Arc);
        $imsak_prayer= $salatTime->imsak_prayer($imsak_time);

        $params = $template->getParams();
        $hookParams = array(
            'fajr_prayer' => $fajr_prayer,
            'shor' => $Shrouk_prayer,
            'zuhr_prayer' => $zuhr_prayer,
            'asr_prayer' => $asr_prayer,
            'maghrib_prayer' => $maghrib_prayer,
            'esha_prayer' => $esha_prayer,
            'goroub_prayer' => $goroub_prayer,
            'asr_prayer2' => $asr_prayer2,
            'eshaain' => $eshaain,
            'imsak_prayer' => $imsak_prayer
        );
        $params += $hookParams;

         $posit = $options->posit;
         $prayer_enable_global = $options->prayer_enable_global;
         if ($prayer_enable_global){
         switch ($posit)
         {
        case 1: $salat_sw = 'ad_sidebar_top';    break;
        case 2: $salat_sw = 'ad_sidebar_below_visitor_panel';    break;
        case 3: $salat_sw = 'ad_sidebar_bottom'; break;
        case 4: $salat_sw2 = 'ad_above_content'; break;
        case 5: $salat_sw2 = 'ad_below_content'; break;
        case 6: $salat_sw2 = 'page_container_content_top'; break;
         }
         if ($hookName== $salat_sw){
            $contents .= $template->create('salat',$params);
             }
            
         else if ($hookName== $salat_sw2){
            $contents .= $template->create('salat2',$params);
             }

        }
    }
}  
