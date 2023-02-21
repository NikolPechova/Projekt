<?php
/**
 * Description of PojistenciKontroler
 *
 * @author nikky
 */
class PojistenciKontroler extends Kontroler 
{
    public function zpracuj(array $parametry) : void
    {
        $this->overPojistence(true);

        $this->hlavicka['titulek'] = 'Seznam pojištěnců';

        $spravcePojistencu = new SpravcePojistencu();
        $spravcePojisteni = new SpravcePojisteni();
        
        $pojisteni = array(
            'pojisteni_id' => '',
            'pojistenci_id' => '',
            'nazev' => '',
            'castka' => '',
            'predmet' => '',
            'platnost_od' => '',
            'platnost_do' => '',
        );
        $this->data['pojisteni'] = $pojisteni;

        
        if ($_POST && isset($_POST['jmeno']))
        {
            try
            {
                $pojistenec = $spravcePojistencu->vyberPojistence($parametry[0]);
                $spravcePojistencu->upravPojistence($_POST['jmeno'], $_POST['prijmeni'], $_POST['email'], $_POST['telefon'], $_POST['ulice'], $_POST['cisloPopisne'], $_POST['mesto'], $_POST['psc'], $pojistenec['pojistenci_id']);
                $this->pridejZpravu('Změna údajů proběhla úspěšně.');
                $this->presmeruj('pojistenci');
            }
            catch (ChybaPojistence $chyba)
            {
                $this->pridejVyjimku($chyba->getMessage());
            }
        }
        
        if ($_POST && isset($_POST['castka']))
        {
            if (isset($_POST['pojisteniNazev']))
            {
                try
                {
                    $spravcePojisteni->pridejPojisteni ($parametry[0], $_POST['pojisteniNazev'], $_POST['castka'], $_POST['predmet'], $_POST['platnost_od'], $_POST['platnost_do']);
                    $this->pridejZpravu('Pojištění bylo úspěšně přidáno.');
                    $this->presmeruj('pojistenci/' . $parametry[0]);
                }
                catch (ChybaPojistence $chyba)
                {
                    $this->pridejVyjimku($chyba->getMessage());
                }
            }
            else
            {
                try
                {
                    $spravcePojisteni->upravPojisteni ($parametry[1], $_POST['castka'], $_POST['predmet'], $_POST['platnost_od'], $_POST['platnost_do']);
                    $this->pridejZpravu('Pojištění bylo změněno.');
                    $this->presmeruj("pojistenci/" . $parametry[0]);
                }
                catch (ChybaPojistence $chyba)
                {
                    $this->pridejVyjimku($chyba->getMessage());
                }
            }
        }
        
        $pojistenci = $spravcePojistencu->vyberVsechnyPojistence();
        $this->data['pojistenci'] = $pojistenci;
        
        if (!empty($parametry[0]) && $parametry[0] == 'odhlasit')
        {
            $spravcePojistencu->odhlas();
            $this->pridejZpravu('Byl jste úspěšně odhlášen.');
            $this->presmeruj('prihlaseni');
        }
        else if (!empty($parametry[0]) && $parametry[0] == 'pridat')
        {
            $this->presmeruj('registrace');
        }
        else if (!empty($parametry[0]) && ((int)($parametry[0]) || ($parametry[0] === '1' )))
        {
            $pojistenec = $spravcePojistencu->vyberPojistence($parametry[0]);
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
            
            $vsechnaPojisteni = $spravcePojisteni->vyberVsechnaPojisteni($parametry[0]);
            $this->data['vsechnaPojisteni'] = $vsechnaPojisteni;
            
            $datum = new DateTime();
            $datum = $datum->format("Y-m-d H:i:s");
        
            $vsechnaAktivniPojisteni = $spravcePojisteni->vyberVsechnaAktivniPojisteni($pojistenec['pojistenci_id'], $datum);
            $this->data['vsechnaAktivniPojisteni'] = $vsechnaAktivniPojisteni;
        
            $vsechnaUkoncenaPojisteni = $spravcePojisteni->vyberVsechnaUkoncenaPojisteni($pojistenec['pojistenci_id'], $datum);
            $this->data['vsechnaUkoncenaPojisteni'] = $vsechnaUkoncenaPojisteni;
            
            if(empty($parametry[1]))
            {
                $this->hlavicka['titulek'] = 'Detail pojištěnce';
                $this->pohled = 'profil';
            }
            else if ((int)($parametry[1]) || ($parametry[1] === '1' ) )
            {
                $pojisteni = $spravcePojisteni->vyberPojisteni($parametry[1]);
                $this->data['pojisteni'] = $pojisteni;
                
                if(empty($parametry[2]))
                {
                    $this->hlavicka['titulek'] = 'Detail pojištění';
                    $this->pohled = 'detailPojisteni';
                }
                
                else if ($parametry[2] == 'odstranitPojisteni')
                {
                    $spravcePojisteni->odstranPojisteni($parametry[1]);
                    $this->pridejZpravu('Pojištění bylo úspěšně odstraněno.');
                    $this->presmeruj('pojistenci/' . $parametry[0]);
                }
                else if ($parametry[2] == 'editovatPojisteni')
                {
                    $this->hlavicka['titulek'] = 'Editace pojištění';
                    $this->pohled = 'pridatEditovatPojisteni';
                }
                else
                    $this->presmeruj('pojistenci');
            }
            else if ($parametry[1] == 'editovat')
            {
                $this->hlavicka['titulek'] = 'Editace pojištěnce';
                $this->pohled = 'editaceUzivatele';
            }
            else if ($parametry[1] == 'pridatPojisteni')
            {
                $this->hlavicka['titulek'] = 'Přidání pojištění';
                $this->pohled = 'pridatEditovatPojisteni';
            }
            else if ($parametry[1] == 'odstranit')
            {
                $spravcePojistencu->odstranPojistence($pojistenec['pojistenci_id']);
                $this->pridejZpravu('Pojištěnec byl úspěšně odstraněn.');
                $this->presmeruj('pojistenci');
            }
            else
                $this->presmeruj('pojistenci');
        }

        else
        {
            $this->pohled = 'pojistenci';
        }
        
    }
}

