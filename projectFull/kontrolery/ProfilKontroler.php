<?php
/**
 * Description of AdministraceKontroler
 *
 * @author nikky
 */
class ProfilKontroler extends Kontroler 
{
    public function zpracuj(array $parametry) : void
    {
        $this->overPojistence();

        $this->hlavicka['titulek'] = 'Můj Účet';

        $spravcePojistencu = new SpravcePojistencu();
        $spravcePojisteni = new SpravcePojisteni();
        
        if ($_POST && !$_POST['soucasneHeslo'])
        {
            try
            {
                $pojistenec = $spravcePojistencu->vratPojistence();
                $spravcePojistencu->upravPojistence($_POST['jmeno'], $_POST['prijmeni'], $_POST['email'], $_POST['telefon'], $_POST['ulice'], $_POST['cisloPopisne'], $_POST['mesto'], $_POST['psc'], $pojistenec['pojistenci_id']);
                $spravcePojistencu->aktualizujSession('pojistenec', $pojistenec['pojistenci_id']);
                $this->pridejZpravu('Vaše údaje byly změněny.');
                $this->presmeruj('profil');
            }
            catch (ChybaPojistence $chyba)
            {
                $this->pridejVyjimku($chyba->getMessage());
            }
        }
        
        if ($_POST && $_POST['soucasneHeslo'])
        {
            try
            {
                $pojistenec = $spravcePojistencu->vratPojistence();
                $spravcePojistencu->zmenHeslo($_POST['soucasneHeslo'], $_POST['noveHeslo'], $_POST['noveHesloZnovu'], $pojistenec['pojistenci_id']);
                $spravcePojistencu->aktualizujSession('pojistenec', $pojistenec['pojistenci_id']);
                $this->pridejZpravu('Vaše heslo bylo změněno.');
                $this->presmeruj('profil');
            }
            catch (ChybaPojistence $chyba)
            {
                $this->pridejVyjimku($chyba->getMessage());
            }
        }
        
        if (!empty($parametry[0]) && $parametry[0] == 'odhlasit')
        {
            $spravcePojistencu->odhlas();
            $this->pridejZpravu('Byl jste úspěšně odhlášen.');
            $this->presmeruj('prihlaseni');
        }
        
        $pojistenec = $spravcePojistencu->vratPojistence();
        $this->data['pojistenci_id'] = $pojistenec['pojistenci_id'];
        $this->data['jmeno'] = $pojistenec['jmeno'];
        $this->data['prijmeni'] = $pojistenec['prijmeni'];
        $this->data['email'] = $pojistenec['email'];
        $this->data['telefon'] = $pojistenec['telefon'];
        $this->data['ulice'] = $pojistenec['ulice'];        
        $this->data['cislo_popisne'] = $pojistenec['cislo_popisne'];
        $this->data['mesto'] = $pojistenec['mesto']; 
        $this->data['psc'] = $pojistenec['psc']; 
        $this->data['admin'] = $pojistenec['admin'];
        
        $vsechnaPojisteni = $spravcePojisteni->vyberVsechnaPojisteni($pojistenec['pojistenci_id']);
        $this->data['vsechnaPojisteni'] = $vsechnaPojisteni;
            
        $datum = new DateTime();
        $datum = $datum->format("Y-m-d H:i:s");
        
        $vsechnaAktivniPojisteni = $spravcePojisteni->vyberVsechnaAktivniPojisteni($pojistenec['pojistenci_id'], $datum);
        $this->data['vsechnaAktivniPojisteni'] = $vsechnaAktivniPojisteni;
        
        $vsechnaUkoncenaPojisteni = $spravcePojisteni->vyberVsechnaUkoncenaPojisteni($pojistenec['pojistenci_id'], $datum);
        $this->data['vsechnaUkoncenaPojisteni'] = $vsechnaUkoncenaPojisteni;

        if (!empty($parametry[0]) && $parametry[0] == 'editaceUdaju')
        {
            $this->hlavicka['titulek'] = 'Změna údajů';
            $this->pohled = 'editaceUzivatele';
        }
        else if (!empty($parametry[0]) && $parametry[0] == 'editaceHesla')
        {
            $this->hlavicka['titulek'] = 'Změna hesla';
            $this->pohled = 'editaceHesla';
        }
        else         
            $this->pohled = 'profil';
    }
}
