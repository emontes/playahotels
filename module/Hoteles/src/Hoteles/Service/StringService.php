<?php
namespace Hoteles\Service;

class StringService
{
    
    public function estadoToUrlHtml($id, $nombre, $currentLang)
    {
        $nombreSano = strtolower($this->sanearString($nombre, '+'));
        if ($currentLang == 'english') {
            $retval = 'hotelsin-' . $nombreSano . '-' . $id . '.html';
        } else {
            $retval = 'hotelesen-' . $nombreSano . '-' . $id . '.html';
        }
        return $retval;
    }
    
    public function hotelToUrlHtml($id, $hotelName, $currentlang)
    {
        $nombreSano = strtolower($this->sanearString($hotelName, '+'));
        if ($currentlang == 'english') {
            $retval = $nombreSano . '-hotel-' . $id . '.html';
        } else {
            $retval = 'hotel-' . $nombreSano . '-' . $id . '.html';
        }
        return $retval;
    }
    
    public function hotelActionToUrlHtml($action, $id, $hotelName, $currentlang)
    {
        $nombreSano = strtolower($this->sanearString($hotelName, '+'));
        if ($currentlang == 'english') {
            $retval = $nombreSano . '-hotel-' . $id . '-' . $action . '.html';
        } else {
            $retval = 'hotel-' . $nombreSano . '-' . $id . '-' . $action . '.html';
        }
        return $retval;
    }
    
    public function vistaToUrl($vista, $currentLang)
    {
        if ($vista['alias'] <> '') {
            $string = $vista['alias'];
        } else {
            $string = $vista['hvi_desc_' . $currentLang];
        }
        $stringSano = $this->sanearString($string, '+');
        return strtolower($stringSano);
    }
    
    public function vistaToDir($vista, $estado, $config, $cache)
    {
        if ($config['id'] == 'usa') {
            $dirfotos = 'http://turista.me/hoteles/usa/';
        } else {
            $dirfotos = 'http://turista.me/hoteles/f/';
        }
        $estadoNormalizado = strtolower($this->sanearString($estado['nombre']));
        $dirfotos .= $estadoNormalizado . '/';
        if ($vista['parentid'] > 0) {
            $apiService = new \Turista\Service\ApiService($config, $cache);
            $parentVista = $apiService->getApiData('vista', $vista['parentid']);
            $nombreParent = $parentVista['hvi_desc_spanish'];
            $nombreParentNormal = strtolower($this->sanearString($nombreParent)) . '/';
            $dirfotos .= $nombreParentNormal;
        }
        $nombreVistaNormal = strtolower($this->sanearString($vista['hvi_desc_spanish'])) . '/';
        $dirfotos .= $nombreVistaNormal;
        return $dirfotos;
    }
    
    public function vistaToUrlHtml($vista, $currentLang, $externo = null)
    {
        $stringSano = strtolower($this->vistaToUrl($vista, $currentLang));
        if ($currentLang == 'english') {
            $hotelesLet = $stringSano . '-hotels-' . $vista['hviid'] . '.html';
        } else {
            $hotelesLet = 'hoteles-'. $stringSano . '-' . $vista['hviid'] . '.html';
        }
        
        if ($externo) {
            $retval = $externo['canonical'] . '/' . $hotelesLet;
        } else {
            $retval = $hotelesLet;
        }
        
        return $retval;
    }
    
    public function vistaActionToUrlHtml($vista, $currentLang, $action)
    {
        $stringSano = strtolower($this->vistaToUrl($vista, $currentLang));
        if ($currentLang == 'english') {
            $hotelesLet = $stringSano . '-hotels-' . $vista['hviid'];
        } else {
            $hotelesLet = 'hoteles-'. $stringSano . '-' . $vista['hviid'];
        }
    
        $retval = $hotelesLet . '-' . $action . '.html';
        return $retval;
    }
    
    public function vistaPageToUrl($vista, $currentLang, $page, $action, $stars=null)
    {
        $stringSano = strtolower($this->vistaToUrl($vista, $currentLang));
        if ($currentLang == 'english') {
            $hotelesLet = $stringSano . '-hotels-' . $vista['hviid'];
        } else {
            $hotelesLet = 'hoteles-'. $stringSano . '-' . $vista['hviid'];
        }
        
        $yaEsta = false;
        if ($action == 'index') {
            if ($page == 1) {
                $retval = $this->vistaToUrlHtml($vista, $currentLang);
            } else {
                $retval = $hotelesLet . '-p' . $page . '.html';
            }
            $yaEsta = true;
        } 
        
        if ($action == 'estrellas' || $action == 'stars') {
            if ($page == 1) {
                $retval = $hotelesLet . '-' . $action . '-' . $stars . '.html';
            } else {
                $retval = $hotelesLet . '-' . $action . '-' . $stars . '-p' . $page . '.html';
            }
            $yaEsta = true;
        }
        
        if (!$yaEsta) {
            if ($page == 1) {
                $retval = $this->vistaActionToUrlHtml($vista, $currentLang, $action);
            } else {
                $retval = $hotelesLet . '-' . $action . '-p' . $page . '.html';
            }
        }
        
        
        return $retval;
    }
    
    public function vistaStarsToUrl($vista, $currentLang, $stars)
    {
        $stringSano = strtolower($this->vistaToUrl($vista, $currentLang));
        if ($currentLang == 'english') {
            $hotelesLet = $stringSano . '-hotels-' . $vista['hviid'];
            $starsLet   = '-stars-';
        } else {
            $hotelesLet = 'hoteles-'. $stringSano . '-' . $vista['hviid'];
            $starsLet   = '-estrellas-';
        }
        $retval = $hotelesLet . $starsLet . $stars . '.html';
        return $retval;
    }
    
    public function construyeRating($rating, $translator)
    {
        $dirImg = 'https://cdn.thoteles.com/img/';
        $trans = $translator;
        $retval = '';
        if ($rating == 6) {
            $retval = '<img src="' . $dirImg . $trans->translate('gran_turismo') . '.png"
                            alt="' . $trans->translate('Hotel Gran Turismo') . '"' . ' '
                        . 'title="' . $trans->translate('Hotel Gran Turismo') . '">';
        }
        if ($rating < 6) {
            $retval = '<img src="' . $dirImg . $rating . 'stars.png"' 
                . ' alt="' . $trans->translate('hotel') . ' ' . $rating . ' ' . $trans->translate('Estrellas') . '"'
                . ' title="' . $trans->translate('hotel') . ' '  . $rating . ' ' . $trans->translate('Estrellas') . '"'
                . '>';
        }
        return $retval;
    } 
    
    public function construyeRatingAmp($rating, $translator)
    {
        $dirImg = 'https://cdn.thoteles.com/img/';
        $trans = $translator;
        $retval = '';
        if ($rating == 6) {
            $retval = '<amp-img src="' . $dirImg . $trans->translate('gran_turismo') . '.png"' .
                                ' width="65" height="14" ' .
                                'alt="' . $trans->translate('Hotel Gran Turismo') . '"></amp-img>';
        }
        if ($rating < 6) {
            $retval = '<amp-img src="' . $dirImg . $rating . 'stars.png" width="65" height="14"'
                . ' alt="' . $trans->translate('hotel') . ' ' . $rating . ' ' . $trans->translate('Estrellas') . '"'
                        . '></amp-img>';
        }
        return $retval;
    }
    
    public function recorta_string($string, $cuantosCaracteres)
    {
        if (strlen($string) > $cuantosCaracteres) {
            $string = substr($string, 0, $cuantosCaracteres);
            $string = explode(' ', $string);
            array_pop($string); //para que quite la última palabra por si quedó recortada
            $string = implode(' ', $string);
        }
        
        return $string;
    }
    
    public function sanearString($string, $blankReplacement = '-')
    {
        $string = trim($string);
        
        $string = str_replace(
            array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
            array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
            $string
        );
        
        $string = str_replace(
            array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
            array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
            $string
        );
        
        $string = str_replace(
            array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
            array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
            $string
        );
        
        $string = str_replace(
            array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
            array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
            $string
        );
        
        $string = str_replace(
            array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
            array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
            $string
        );
        
        $string = str_replace(
            array('ñ', 'Ñ', 'ç', 'Ç'),
            array('n', 'N', 'c', 'C',),
            $string
        );
        
        //Convierte a Texto Especiales
        $string = str_replace(
            array('@','&'),
            array('at','and'),
            $string
        );
        
        
        
        
        //Esta parte se encarga de eliminar cualquier caracter extraño
        $string = str_replace(
            array("\\", "¨", "º", "-", "~",
                "#", "@", "|", "!", "\"",
                "·", "$", "%", "&",
                "/", //lo tachamos por que causa problemas con las fotos (portadas)
                "(", ")", "?", "'", "¡",
                "¿", "[", "^", "`", "]",
                "+", "}", "{", "¨", "´",
                ">", "< ", ";", ",", ":",
                ".", " "),
            $blankReplacement,
            $string
        );
        
        
        return $string;
    }
}