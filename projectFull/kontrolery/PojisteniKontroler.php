<?php

/**
 * Description of UvodKontroler
 *
 * @author nikky
 */
class PojisteniKontroler  extends Kontroler {
    public function zpracuj(array $parametry) : void
    {
        
        $this->hlavicka['titulek'] = 'Pojištění';
        
        $this->pohled = 'pojisteni';
        
    }
}
