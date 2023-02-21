<?php

/**
 * Description of SmerovacKontroler
 *
 * @author nikky
 */
class SmerovacKontroler extends Kontroler
{
    
    protected Kontroler $kontroler;

    public function zpracuj(array $parametry): void 
    {
        $naparsovanaURL = $this->parsujURL($parametry[0]);
        
        if (empty($naparsovanaURL[0])){
            $this->presmeruj('uvod');
        }

        $tridaKontroleru = $this->pomlckyDoVelbloudiNotace(array_shift($naparsovanaURL)) . 'Kontroler';
        
        if (file_exists('kontrolery/' . $tridaKontroleru . '.php')){
            $this->kontroler = new $tridaKontroleru;
        }
        else{
            $this->presmeruj('chyba');
        }
        
        $this->kontroler->zpracuj($naparsovanaURL);
        
        $this->data['titulek'] = $this->kontroler->hlavicka['titulek'];
        $this->data['popis'] = $this->kontroler->hlavicka['popis'];
        $this->data['klicova_slova'] = $this->kontroler->hlavicka['klicova_slova'];
        $this->data['zpravy'] = $this->vratZpravy();
        $this->data['vyjimky'] = $this->vratVyjimky();
        
        $this->pohled = 'rozlozeni';
    }
    
    private function parsujURL(string $url) : array 
    { 
 	$naparsovanaURL = parse_url($url);
 	$naparsovanaURL["path"] = ltrim($naparsovanaURL["path"], "/"); 
 	$naparsovanaURL["path"] = trim($naparsovanaURL["path"]);
 				       
        $rozdelenaCesta = explode("/", $naparsovanaURL["path"]);
 	return $rozdelenaCesta;
    }
    
    private function pomlckyDoVelbloudiNotace(string $text) : string
    {
        $veta = str_replace('-', ' ', $text);
        $veta = ucwords($veta);
        $veta = str_replace(' ', '', $veta);
        return $veta;
        
    }

}