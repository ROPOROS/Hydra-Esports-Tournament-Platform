<?php 
namespace App\Utils;
class Utils{
    
    public static function isNotValidMontant($val)
    {
        return $val>999 || $val<1;
    }
    public static function isNegative($val)
    {
        return $val < 0;
    }
    public static function isTournoiEmpty($tournoi)
    {
        return ($tournoi->getNom() == "" ||
                $tournoi->getDateDebut() == "" ||
                $tournoi->getDateFin() == "" ||
                $tournoi->getHeure() == "" ||
                $tournoi->getPrix() == "" ||
                $tournoi->getDetails() == "" ||
                $tournoi->getIdJeu() == "" 
            );
    }
    public static function isAnterior($date1, $date2)
    {
        $date1_ts = strtotime($date1);
        $date2_ts = strtotime($date2);
        $diff = $date2_ts - $date1_ts;
        return self::isNegative($diff);
    }
    public static function isNotValidHour($val)
    {
        return $val > 24 || $val < 1;
    }

    
    public static function verifyCode($val)
    {
        $apiURL = 'https://www.authenticatorapi.com/Validate.aspx?Pin='.$val.'&SecretCode=1234';
                $json_data = file_get_contents($apiURL);
        return  $json_data=="True";
    }
}

?>