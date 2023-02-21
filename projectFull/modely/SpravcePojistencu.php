<?php
/**
 * Description of SpravceUzivatelu
 *
 * @author nikky
 */
class SpravcePojistencu {
    public function vratOtisk(string $heslo) : string
    {
        return password_hash($heslo, PASSWORD_DEFAULT);
    }

    public function registruj(string $jmeno, string $prijmeni, string $email, string $telefon, string $ulice, string $cisloPopisne, string $mesto, string $psc, string $heslo, string $hesloZnovu) : void
    {
        if ($heslo != $hesloZnovu)
            throw new ChybaPojistence('Hesla nesouhlasí.');
        $pojistenec = array(
            'jmeno' => $jmeno,
            'prijmeni' => $prijmeni,
            'email' => $email,
            'telefon' => $telefon,
            'ulice' => $ulice,
            'cislo_popisne' => $cisloPopisne,
            'mesto' => $mesto,
            'psc' => $psc,
            'heslo' => $this->vratOtisk($heslo),
        );
        try
        {
            Db::vloz('pojistenci', $pojistenec);
        }
        catch (PDOException $chyba)
        {
            throw new ChybaPojistence('Uživatel s tímto e-mailem je již zaregistrovaný.');
        }
    }
    
    public function pridej(string $jmeno, string $prijmeni, string $email, string $telefon, string $ulice, string $cisloPopisne, string $mesto, string $psc) : void
    {
        $pojistenec = array(
            'jmeno' => $jmeno,
            'prijmeni' => $prijmeni,
            'email' => $email,
            'telefon' => $telefon,
            'ulice' => $ulice,
            'cislo_popisne' => $cisloPopisne,
            'mesto' => $mesto,
            'psc' => $psc,
        );
        try
        {
            Db::vloz('pojistenci', $pojistenec);
        }
        catch (PDOException $chyba)
        {
            throw new ChybaPojistence('Uživatel s tímto e-mailem je již zaregistrovaný.');
        }
    }

    public function prihlas(string $email, string $heslo) : void
    {
        $pojistenec = Db::dotazJeden('
            SELECT *
            FROM pojistenci
            WHERE email = ?
        ', array($email));
        if (!$pojistenec || !password_verify($heslo, $pojistenec['heslo']))
            throw new ChybaPojistence('Neplatné jméno nebo heslo.');
        $_SESSION['pojistenec'] = $pojistenec;
    }
    
    public function upravPojistence(string $jmeno, string $prijmeni, string $email, string $telefon, string $ulice, string $cisloPopisne, string $mesto, string $psc, int $pojistenci_id) : void
    {
        $pojistenec = array(
            'jmeno' => $jmeno,
            'prijmeni' => $prijmeni,
            'email' => $email,
            'telefon' => $telefon,
            'ulice' => $ulice,
            'cislo_popisne' => $cisloPopisne,
            'mesto' => $mesto,
            'psc' => $psc,
        );
        try
        {
            Db::zmen('pojistenci', $pojistenec, 'WHERE pojistenci_id = ?', array($pojistenci_id));
        }
        catch (PDOException $chyba)
        {
            throw new ChybaPojistence('Změny se nepodařilo uložit.');
        }
    }
    
    public function zmenHeslo(string $soucasneHeslo, string $noveHeslo, string $noveHesloZnovu, int $pojistenci_id) : void
    {
        if ($noveHeslo != $noveHesloZnovu)
            throw new ChybaPojistence('Nová hesla se neshodují.');
        
        $pojistenec = Db::dotazJeden('
            SELECT heslo
            FROM pojistenci
            WHERE pojistenci_id = ?
        ', array($pojistenci_id));       
        
        if (!$pojistenec || !password_verify($soucasneHeslo, $pojistenec['heslo']))
            throw new ChybaPojistence('Bylo zadáno nesprávné současné heslo.');
        
        $pojistenec = array(
            'heslo' => $this->vratOtisk($noveHeslo),
        );
        
        try
        {
            Db::zmen('pojistenci', $pojistenec, 'WHERE pojistenci_id = ?', array($pojistenci_id));
        }
        catch (PDOException $chyba)
        {
            throw new ChybaPojistence('Změny se nepodařilo uložit.');
        }
    }    
    
    public function nastavHeslo(string $email, string $heslo, string $hesloZnovu) : void
    {
        if ($heslo != $hesloZnovu)
            throw new ChybaPojistence('Hesla se neshodují.');
        
        $pojistenec = Db::dotazJeden('
            SELECT heslo
            FROM pojistenci
            WHERE email = ?
        ', array($email));       
        
        if (!$pojistenec)
            throw new ChybaPojistence('Nelze nastavit heslo k neexistujícímu účtu.');
        if ($pojistenec['heslo'] != null)
            throw new ChybaPojistence('K Vašemu účtu je již heslo nastavené.');
        
        $pojistenec = array(
            'heslo' => $this->vratOtisk($heslo),
        );
        
        try
        {
            Db::zmen('pojistenci', $pojistenec, 'WHERE email = ?', array($email));
        }
        catch (PDOException $chyba)
        {
            throw new ChybaPojistence('Heslo se nepodařilo nastavit.');
        }
    }    
    
    public function vyberPojistence(int $id) : array
    {
        return Db::dotazJeden('
            SELECT *
            FROM pojistenci
            WHERE pojistenci_id = ?
        ', array($id));
    }
    
    public function odstranPojistence(int $id) : void
    {
        Db::dotaz('
            DELETE 
            FROM pojistenci
            WHERE pojistenci_id = ?
        ', array($id));
    }
    
    public function vyberVsechnyPojistence() : array
    {
        return Db::dotazVsechny('
            SELECT *
            FROM `pojistenci`
            ORDER BY `pojistenci_id` DESC
        ');
    }
    
    
    public function aktualizujSession(string $session, int $id) : void
    {
        $pojistenec = Db::dotazJeden('
            SELECT *
            FROM pojistenci
            WHERE pojistenci_id = ?
        ', array($id));
        $_SESSION[$session] = $pojistenec;
    }
    

    public function odhlas() : void
    {
        unset($_SESSION['pojistenec']);
    }

    public function vratPojistence() : ?array
    {
        if (isset($_SESSION['pojistenec']))
            return $_SESSION['pojistenec'];
        return null;
    }
}
