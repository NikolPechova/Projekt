<?php

/**
 * Description of UvodKontroler
 *
 * @author nikky
 */
class UvodKontroler  extends Kontroler {
    public function zpracuj(array $parametry) : void
    {
        
        $this->hlavicka['titulek'] = 'Úvod';
        
        $this->pohled = 'uvod';
        
    }
}
