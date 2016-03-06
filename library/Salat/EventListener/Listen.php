<?php

class Salat_EventListener_Listen
{

    public static function template_create($templateName, array &$params, XenForo_Template_Abstract $template)
    {
        if ($templateName == 'PAGE_CONTAINER') {
            $template->preloadTemplate('salat');
        }
    }

    public static function salatte($hookName, &$contents, array $hookParams, XenForo_Template_Abstract $template)
    {
        #--------------> Get Options
        $options   = XenForo_Application::get('options');
        #--------------> Get Options

        #--------------> Get Options Salat Off/ON
        $prayer_enable_global = $options->prayer_enable_global;
        #--------------> Get Options Salat Off/ON

        $vistor = XenForo_Visitor::getInstance();
        $Permissionsalat = $vistor->hasPermission('salat', 'salat_edit_user');

        #--------------> Salat is ON
        if ($prayer_enable_global) {
            $salatFilde = $vistor->salat;
            $_salat = unserialize($salatFilde);
            $posit = !empty($_salat['posit']) ? $_salat['posit'] : $options->posit;
            switch ($posit) {
                case 1:
                    $salat_sw = 'ad_sidebar_top';
                    $templatesalat = 'salat';
                    break;
                case 2:
                    $salat_sw = 'ad_sidebar_below_visitor_panel';
                    $templatesalat = 'salat';
                    break;
                case 3:
                    $salat_sw = 'ad_sidebar_bottom';
                    $templatesalat = 'salat';
                    break;
                case 4:
                    $salat_sw = 'ad_above_content';
                    $templatesalat = 'salat2';
                    break;
                case 5:
                    $salat_sw = 'ad_below_content';
                    $templatesalat = 'salat2';
                    break;
                case 6:
                    $salat_sw = 'page_container_content_top';
                    $templatesalat = 'salat2';
                    break;
            }
            if($hookName == 'account_wrapper_sidebar_settings' && $Permissionsalat)
            {
                $params = $template->getParams();
                $hookParams['selectedKey'] = $params['selectedKey'];
                $contents .= $template->create('salat_account_wrapper', $hookParams);
            }
            if($hookName == 'navigation_visitor_tab_links1' && $Permissionsalat)
            {
                $contents .= $template->create('salat_navigation_visitor_tab_links');
            }
            if($hookName == $salat_sw)
            {
                #--------------> Option Salat
                $y = date("Y");
                $m = date("n");
                $d = date("j");
                // pi
                $pi = 3.14159265358979323846;
                $salatTime = new Salat_Helper_Sala();
                
                if (!empty($_salat['lat']) && !empty($_salat['lng'])) {        
                    $long                   = $_salat['lng'];
                    $lat                    = $_salat['lat'];
                    $tim_date               = $_salat['tim_date'];
                    $prayer_imsak           = $_salat['prayer_imsak'];
                    $higri_date             = $_salat['higri_date'];
                    $prayer_jam3            = $_salat['prayer_jam3'];
                    $prayer_mathhab         = $_salat['prayer_mathhab'];
                    $zone                   = Salat_Helper_Sala::timezone_offset_string($vistor->timezone);
                    $location['country']    = $_salat['country'];
                    $weather                = $_salat['weather'];
                    $weather_units          = $_salat['weather_units'];
                } else {
                    $long                   = $options->prayer_longitude;
                    $lat                    = $options->prayer_latitude;
                    $tim_date               = $options->tim_date;
                    $prayer_imsak           = $options->prayer_imsak;
                    $higri_date             = $options->higri_date;
                    $prayer_jam3            = $options->prayer_jam3;
                    $prayer_mathhab         = $options->prayer_mathhab;
                    $zone                   = Salat_Helper_Sala::timezone_offset_string($options->prayer_offset);
                    $location['country']    = $options->country_prayer;
                    $weather                = $options->prayer_weather['weaId'];
                    $weather_units          = $options->prayer_weather['units'];
                }
                $location['lat']   = $lat;
                $location['long']  = $long;

                $julian_day = $salatTime->julian_day($y, $m, $d);
                $sun_long   = $salatTime->sun_long($julian_day);
                $sun_period = $salatTime->sun_period($julian_day);
                $Lambda     = $salatTime->sun_wave_long($sun_long, $sun_period, $pi);
                $Obliquity  = $salatTime->obliquity($julian_day);
                $Alpha      = $salatTime->alpha($Obliquity, $Lambda, $pi);
                $ST         = $salatTime->starry_time($julian_day);
                $Dec        = $salatTime->sun_obliquity($Obliquity, $Lambda, $pi);

                $noon        = $salatTime->sun_miday($Alpha, $ST);
                $UT_noon     = $salatTime->international_midday($noon, $long);
                $local_noon  = $salatTime->local_noon($UT_noon, $zone);
                $zuhr_prayer = $salatTime->zuhr_time($local_noon);

                $AsrAlt      = $salatTime->asr_alt($lat, $Dec, $pi);
                $AsrAlt2     = $salatTime->asr_alt2($lat, $Dec, $pi);
                $AsrArc      = $salatTime->asr_arc($AsrAlt, $lat, $Dec, $pi);
                $AsrArc2     = $salatTime->asr_arc2($AsrAlt2, $lat, $Dec, $pi);
                $AsrTime     = $salatTime->asr_time($local_noon, $AsrArc);
                $AsrTime2    = $salatTime->asr_time2($local_noon, $AsrArc2);
                $asr_prayer  = $salatTime->asr_prayer_time($AsrTime);
                $asr_prayer2 = $salatTime->asr_prayer_time2($AsrTime2);

                $Durinal_Arc   = $salatTime->Durinal_Arc($lat, $Dec, $pi);
                $sun_rise      = $salatTime->sun_rise($local_noon, $Durinal_Arc);
                $Shrouk_prayer = $salatTime->shrouk_prayer($sun_rise);
                $sun_set       = $salatTime->sun_set($local_noon, $Durinal_Arc);

                $goroub_prayer  = $salatTime->goroub_prayer($sun_set);
                $maghrib_prayer = $salatTime->maghrib_prayer($sun_set);
                $eshaain        = $salatTime->eshaain_prayer($sun_set);

                $Esha_Arc    = $salatTime->esha_arc($lat, $Dec, $pi);
                $esha_time   = $salatTime->esha_time($local_noon, $Esha_Arc);
                $esha_prayer = $salatTime->esha_prayer($esha_time);

                $Fajr_Arc    = $salatTime->fajr_arc($lat, $Dec, $pi);
                $fajr_time   = $salatTime->fajr_time($local_noon, $Fajr_Arc);
                $fajr_prayer = $salatTime->fajr_prayer($fajr_time);

                $imsak_Arc    = $salatTime->imsak_arc($lat, $Dec, $pi);
                $imsak_time   = $salatTime->imsak_time($local_noon, $imsak_Arc);
                $imsak_prayer = $salatTime->imsak_prayer($imsak_time);

                $params     = $template->getParams();
                $hookParams = array(
                    'fajr_prayer'    => $fajr_prayer,
                    'shor'           => $Shrouk_prayer,
                    'zuhr_prayer'    => $zuhr_prayer,
                    'asr_prayer'     => $asr_prayer,
                    'maghrib_prayer' => $maghrib_prayer,
                    'esha_prayer'    => $esha_prayer,
                    'goroub_prayer'  => $goroub_prayer,
                    'asr_prayer2'    => $asr_prayer2,
                    'eshaain'        => $eshaain,
                    'imsak_prayer'   => $imsak_prayer,
                    'location'       => $location,
                    'can_edit'       => $Permissionsalat,
                    'tim_date'       => $tim_date,
                    'prayer_imsak'   => $prayer_imsak,
                    'higri_date'     => $higri_date,
                    'prayer_jam3'    => $prayer_jam3,
                    'weather'        => $weather,
                    'weather_units'  => $weather_units,
                    'prayer_mathhab' => $prayer_mathhab
                );
                $params += $hookParams;
                
                #--------------> Option Salat
                
                $contents .= $template->create($templatesalat, $params);
            }
            

        }
        #--------------> Salat is ON
    }
}  
