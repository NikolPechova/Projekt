<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of RegistraceKontroler
 *
 * @author nikky
 */
class RegistraceKontroler extends Kontroler
{
    public function zpracuj(array $parametry) : void
    {
        $spravcePojistencu = new SpravcePojistencu();
        $pojistenec = $spravcePojistencu->vratPojistence();
        
        if(!$pojistenec || !$pojistenec['admin'])
            $this->hlavicka['titulek'] = 'Registrace';
        else if ($pojistenec && $pojistenec['admin'])
            $this->hlavicka['titulek'] = 'Přidání nového pojištence';
            
        if ($_POST && $pojistenec['admin'])
        {
            try
            {
                $spravcePojistencu = new SpravcePojistencu();
                $spravcePojistencu->pridej($_POST['jmeno'], $_POST['prijmeni'], $_POST['email'], $_POST['telefon'], $_POST['ulice'], $_POST['cisloPopisne'], $_POST['mesto'], $_POST['psc']);
                $this->pridejZpravu('Pojištěnec byl úspěšně přidán.');
                $this->presmeruj('pojistenci');
            }
            catch (ChybaPojistence $chyba)
            {
                $this->pridejVyjimku($chyba->getMessage());
            }
        }
        else if ($_POST)
        {
            try
            {
                $spravcePojistencu = new SpravcePojistencu();
                $spravcePojistencu->registruj($_POST['jmeno'], $_POST['prijmeni'], $_POST['email'], $_POST['telefon'], $_POST['ulice'], $_POST['cisloPopisne'], $_POST['mesto'], $_POST['psc'], $_POST['heslo'], $_POST['hesloZnovu']);
                $spravcePojistencu->prihlas($_POST['email'], $_POST['heslo']);
                $this->pridejZpravu('Byl jste úspěšně zaregistrován.');
                $this->presmeruj('profil');
            }
            catch (ChybaPojistence $chyba)
            {
                $this->pridejVyjimku($chyba->getMessage());
            }
        }
        
        if (!$pojistenec)
            $this->data['admin'] = 0;
        else
            $this->data['admin'] = $pojistenec['admin'];
        $this->pohled = 'registrace';
    }
}
