<?php

/*
 * This file is part of the Witty Growth C.A. - J406095737 package.
 * 
 * (c) www.mpandco.com
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tecnoready\Common\Service\TimeAgo;

/**
 * [
 *      //Pasado
    'less than {seconds} seconds ago' => 'hace menos de {seconds} segundos',
    'half a minute ago' => 'hace medio minuto',
    'less than a minute ago' => 'hace menos de un minuto',
    '1 minute ago' => 'hace 1 minuto',
    '{minutes} minutes ago' => 'hace {minutes} minutos',
    'about 1 hour ago' => 'hace aproximadamente 1 hora',
    'about {hours} hours ago' => 'hace unas {hours} horas',
    '1 day ago' => 'hace 1 día',
    '{days} days ago' => 'hace {days} días',
    '1 month ago' => 'hace 1 mes',
    '1 year ago' => 'hace 1 año',
    '{years} years ago' => 'hace {years} años',
    '{months} months ago' => 'hace {months} meses',
    
    //Futuro
    'in less than {seconds} seconds' => 'en menos de {seconds} segundos',
    'in half a minute' => 'en medio minuto',
    'in less than a minute' => 'en menos de un minuto',
    'in 1 minute' => 'en 1 minuto',
    'in {minutes} minutes' => 'en {minutes} minutos',
    'in about 1 hour' => 'en aproximadamente 1 hora',
    'in about {hours} hours' => 'en aproximadamente {hours} horas',
    'in 1 day' => 'en 1 día',
    'in {days} days' => 'en {days} días',
    'in {months} months' => 'en {months} meses',
    'in {years} years' => 'en {years} años',
 * ]
 */

/**
 * Servicio de tiempo (tecno.service.time_ago)
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class TimeAgoService 
{
    const FORMAT_DATETIME = 'Y-m-d H:i:s';
    private $translator;
    
    public function __construct($translator) {
        $this->translator = $translator;
    }
    
    private function trans($id,array $parameters = []) {
        $trans = $id;
        if($this->translator instanceof \Closure){
            $t = $this->translator;
            $trans = $t($id,$parameters);
        }
        return $trans;
    }


    /**
     * Like distance_of_time_in_words, but where to_time is fixed to timestamp()
     *
     * @param $from_time String or DateTime
     * @param bool $include_seconds
     * @param bool $include_months
     *
     * @return mixed
     */
    function timeAgoInWords($from_time, $include_seconds = false, $include_months = false)
    {
        $now = new \DateTime('now');
        if($from_time instanceof \DateTime){
            $now->setTimezone($from_time->getTimezone());
        }
        return $this->distanceOfTimeInWordsFilter($from_time, $now, $include_seconds, $include_months);
    }
    
    /**
     * Reports the approximate distance in time between two times given in seconds
     * or in a valid ISO string like.
     * For example, if the distance is 47 minutes, it'll return
     * "about 1 hour". See the source for the complete wording list.
     *
     * Integers are interpreted as seconds. So, by example to check the distance of time between
     * a created user an it's last login:
     * {{ user.createdAt|distance_of_time_in_words(user.lastLoginAt) }} returns "less than a minute".
     *
     * Set include_seconds to true if you want more detailed approximations if distance < 1 minute
     * Set include_months to true if you want approximations in months if days > 30
     *
     * @param $from_time String or DateTime
     * @param $to_time String or DateTime
     * @param bool $include_seconds True to return distance in seconds when it's lower than a minute.
     * @param bool $include_months
     *
     * @return mixed
     */
    public function distanceOfTimeInWordsFilter($from_time, $to_time = null, $include_seconds = false, $include_months = false)
    {

        # Transforming to Timestamp
        if (!($from_time instanceof \DateTime) && !is_numeric($from_time)) {
            $from_time =  \DateTime::createFromFormat(self::FORMAT_DATETIME, $from_time);
            $from_time = $from_time->getTimestamp();
        } elseif($from_time instanceof \DateTime) {
            $from_time = $from_time->getTimestamp();
        }

        $to_time = empty($to_time) ? new \DateTime('now') : $to_time;

        # Transforming to Timestamp
        if (!($to_time instanceof \DateTime) && !is_numeric($to_time)) {
            $to_time = \DateTime::createFromFormat(self::FORMAT_DATETIME, $to_time);
            $to_time = $to_time->getTimestamp();
        } elseif($to_time instanceof \DateTime) {
            $to_time = $to_time->getTimestamp();
        }

        $future = ($to_time < $from_time) ? true : false;

        $distance_in_minutes = round((abs($to_time - $from_time))/60);
        $distance_in_seconds = round(abs($to_time - $from_time));

        if($future){
            return $this->future($distance_in_minutes,$include_seconds,$distance_in_seconds);
        }

        if ($distance_in_minutes <= 1){
            if ($include_seconds){
                if ($distance_in_seconds < 5){
                    return $this->trans('less than {seconds} seconds ago', array('{seconds}' => 5));
                }
                elseif($distance_in_seconds < 10){
                    return $this->trans('less than {seconds} seconds ago', array('{seconds}' => 10));
                }
                elseif($distance_in_seconds < 20){
                    return $this->trans('less than {seconds} seconds ago', array('{seconds}' => 20));
                }
                elseif($distance_in_seconds < 40){
                    return $this->trans('half a minute ago');
                }
                elseif($distance_in_seconds < 60){
                    return $this->trans('less than a minute ago');
                }
                else {
                    return $this->trans('1 minute ago');
                }
            }
            return ($distance_in_minutes==0) ? $this->trans('less than a minute ago', array()) : $this->trans('1 minute ago', array());
        }
        elseif ($distance_in_minutes <= 45){
            return $this->trans('{minutes} minutes ago', array('{minutes}' => $distance_in_minutes));
        }
        elseif ($distance_in_minutes <= 90){
            return $this->trans('about 1 hour ago');
        }
        elseif ($distance_in_minutes <= 1440){
            return $this->trans('about {hours} hours ago', array('{hours}' => round($distance_in_minutes/60)));
        }
        elseif ($distance_in_minutes <= 2880){
            return $this->trans('1 day ago');
        }
        else{
            $distance_in_days = round($distance_in_minutes/1440);
            if (!$include_months || $distance_in_days <= 30) {
                return $this->trans('{days} days ago', array('{days}' => round($distance_in_days)));
            } elseif ($distance_in_days < 345) {
                $months = round($distance_in_days/30);
                if($months == 1){
                    return $this->trans('1 month ago');
                }else{
                    return $this->trans('{months} months ago', array('{months}' => $months));
                }
            } else {
                $years = round($distance_in_days/365);
                if($years == 1){
                    return $this->trans("1 year ago");
                }else{
                    return $this->trans('{years} years ago',array('{years}' => $years));
                }
            }
        }
    }

    private function future($distance_in_minutes,$include_seconds,$distance_in_seconds){
        $distance_in_days = round($distance_in_minutes/1440);
        $distance_in_months = round($distance_in_minutes/43200);
        $distance_in_years = round($distance_in_minutes/518400);
        if ($distance_in_minutes <= 1){
            if ($include_seconds){
                if ($distance_in_seconds < 5){
                    return $this->trans('in less than {seconds} seconds', array('{seconds}' => 5));
                }
                elseif($distance_in_seconds < 10){
                    return $this->trans('in less than {seconds} seconds', array('{seconds}' => 10));
                }
                elseif($distance_in_seconds < 20){
                    return $this->trans('in less than {seconds} seconds', array('{seconds}' => 20));
                }
                elseif($distance_in_seconds < 40){
                    return $this->trans('in half a minute');
                }
                elseif($distance_in_seconds < 60){
                    return $this->trans('in less than a minute');
                }
                else {
                    return $this->trans('in 1 minute');
                }
            }
            return ($distance_in_minutes===0) ? $this->trans('in less than a minute', array()) : $this->trans('in 1 minute', array());
        }
        elseif ($distance_in_minutes <= 45){
            return $this->trans('in {minutes} minutes', array('{minutes}' => $distance_in_minutes));
        }
        elseif ($distance_in_minutes <= 90){
            return $this->trans('in about 1 hour');
        }
        elseif ($distance_in_minutes <= 1440){
            return $this->trans('in about {hours} hours', array('{hours}' => round($distance_in_minutes/60)));
        }
        elseif ($distance_in_minutes <= 2880){
            return $this->trans('in 1 day');
        }
        elseif ($distance_in_days <= 31){
            return $this->trans('in {days} days', array('{days}' => $distance_in_days));
        }
        elseif ($distance_in_months > 0 && $distance_in_months <= 12){
            return $this->trans('in {months} months', array('{months}' => $distance_in_months));
        }
        elseif ($distance_in_years > 0){
            return $this->trans('in {years} years', array('{years}' => $distance_in_years));
        }
        else{
            return $this->trans('in {days} days', array('{days}' => $distance_in_days));
        }
    }
}
