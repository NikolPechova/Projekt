<?php
/**
 * Description of PrihlaseniKontroler
 *
 * @author nikky
 */
class PrihlaseniKontroler extends Kontroler 
{
    public function zpracuj(array $parametry) : void
    {
        $spravcePojistencu = new SpravcePojistencu();
        if ($spravcePojistencu->vratPojistence())
            $this->presmeruj('profil');
        
        if (!empty($parametry[0]) && $parametry[0] == 'nastavHeslo' && $_POST)
        {
            try
            {
            $spravcePojistencu->nastavHeslo($_POST['email'], $_POST['heslo'], $_POST['hesloZnovu']);
            $this->pridejZpravu('Heslo bylo nastaveno, nyní se můžete přihlásit.');
            $this->presmeruj('prihlaseni');
            }
            catch (ChybaPojistence $chyba)
            {
                $this->pridejVyjimku($chyba->getMessage());
            }
        }
        
        
        
        $this->hlavicka['titulek'] = 'Přihlášení';
        if ($_POST && empty($parametry[0]))
        {
            try
            {
                $spravcePojistencu->prihlas($_POST['email'], $_POST['heslo']);
                $this->pridejZpravu('Byl jste úspěšně přihlášen.');
                $this->presmeruj('profil');
            }
            catch (ChybaPojistence $chyba)
            {
                $this->pridejVyjimku($chyba->getMessage());
            }
        }
        
        if (!empty($parametry[0]) && $parametry[0] == 'nastavHeslo')
            $this->pohled = 'nastavHeslo';
        else
            $this->pohled = 'prihlaseni';
    }
}

