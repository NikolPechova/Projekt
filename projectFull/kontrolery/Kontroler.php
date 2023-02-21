<?php

/**
 * Description of Kontroler
 *
 * @author nikky
 */
abstract class Kontroler {
    
    protected array $data = array();
    protected string $pohled = "";
    protected array $hlavicka = array('titulek' => '', 'klicova_slova' => '', 'popis' => '');
    
    abstract function zpracuj(array $parametry) : void;
    
    public function vypisPohled() : void
    {
        if ($this->pohled)
        {
            extract($this->data);
            require("pohledy/" . $this->pohled . ".phtml");
        }
    }
    
    public function presmeruj(string $url) : never
    {
        header("Location: /$url");
        header("Connection: close");
        exit;
    }
    
    private function osetri($x = null)
    {
        if (!isset($x))
            return null;
        elseif (is_string($x))
            return htmlspecialchars($x, ENT_QUOTES);
        elseif (is_array($x))
        {
            foreach($x as $k => $v)
            {
                $x[$k] = $this->osetri($v);
            }
            return $x;
        }
        else
            return $x;
    }
    
    public function pridejZpravu(string $zprava) : void
    {
        if (isset($_SESSION['zpravy']))
            $_SESSION['zpravy'][] = $zprava;
        else
            $_SESSION['zpravy'] = array($zprava);
    }
    
    public function vratZpravy() : array
    {
        if (isset($_SESSION['zpravy']))
        {
            $zpravy = $_SESSION['zpravy'];
            unset($_SESSION['zpravy']);
            return $zpravy;
        }
        else
            return array();
    }
    
    public function pridejVyjimku(string $vyjimka) : void
    {
        if (isset($_SESSION['vyjimky']))
            $_SESSION['vyjimky'][] = $vyjimka;
        else
            $_SESSION['vyjimky'] = array($vyjimka);
    }
    
    public function vratVyjimky() : array
    {
        if (isset($_SESSION['vyjimky']))
        {
            $vyjimky = $_SESSION['vyjimky'];
            unset($_SESSION['vyjimky']);
            return $vyjimky;
        }
        else
            return array();
    }
    
    public function overPojistence(bool $admin = false)
    {
        $spravcePojistencu = new SpravcePojistencu();
        $pojistenec = $spravcePojistencu->vratPojistence();
        if (!$pojistenec || ($admin && !$pojistenec['admin']))
        {
            $this->pridejVyjimku('Nemáte dostatečná oprávnění pro přístup.');
            $this->presmeruj('prihlaseni');
        }
    }
    
    
    

}

