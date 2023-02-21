<?php

/**
 * Description of UvodKontroler
 *
 * @author nikky
 */
class AplikaceKontroler  extends Kontroler {
    public function zpracuj(array $parametry) : void
    {
        
        $this->hlavicka['titulek'] = 'O aplikaci';
        
        $this->pohled = 'aplikace';
        
    }
}
