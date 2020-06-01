<?php
namespace Hoteles\Service;

class VistaService
{
    protected $apiService;
    protected $config;
    
    public function makePaginatorBootStrap($collection, $vistaNombre)
    {
       
        $page = $collection['page'];
        $last = $collection['page_count'];
        if ($page == 1) {
            $paginas = '<li class="disabled"><a href="#">&laquo; Previous</a></li>';
        } else {
            $prevPage = $page -1;
            $paginas = '<li><a href="/'
                . $vistaNombre . '/pag/' . $prevPage
                .'" title="' . $vistaNombre . ' '
                    . 'Previous Page">'
                        . '&laquo; Previous</a></li>';
        }
        if ($last > 2) {
            if ($page > 9) {
                $inicial = $page-5;
                $final = $page+5;
            } else {
                $inicial = 1;
                $final = 10;
            }
            if ($final > $last) {
                $final = $last;
            }
            for ($i=$inicial;$i<=$final;$i++) {
                if ($page == $i) {
                    $paginas .= '<li class="active"><a href="#">' . $i . '</a></li>';
                } else {
                    $paginas .= '<li><a href="/'
                        . $vistaNombre . '/pag/' . $i
                        .'" title="' . $vistaNombre . ' '
                            .'Page ' . $i
                            . '">' . $i . '</a></li>';
                }
            }
        }
    
        if ($page == $last) {
            $paginas .= '<li class="disabled"><a href="#">Next &raquo;</a></li>';
        } else {
            $nextPage = $page + 1;
            $paginas .= '<li><a href="/'
                .  $vistaNombre . '/pag/' . $nextPage
                . '" title="' . $vistaNombre . ' '
                    .  'Next Page">'
                        . 'Next »</a></li>';
        }
    
        return $paginas;
    }
}