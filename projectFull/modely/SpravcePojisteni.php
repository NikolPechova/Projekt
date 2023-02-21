<?php
/**
 * Description of SpravceUzivatelu
 *
 * @author nikky
 */
class SpravcePojisteni {
    
    public function pridejPojisteni(int $pojistenci_id, string $nazev, int $castka, string $predmet, string $platnost_od, string $platnost_do) : void
    {
        $pojisteni = array(
            'pojistenci_id' => $pojistenci_id,
            'nazev' => $nazev,
            'castka' => $castka,
            'predmet' => $predmet,
            'platnost_od' => $platnost_od,
            'platnost_do' => $platnost_do,
        );
        try
        {
            Db::vloz('pojisteni', $pojisteni);
        }
        catch (PDOException $chyba)
        {
            throw new ChybaPojistence('Pojištění se nepodařilo přidat.');
        }
    }
    
    public function upravPojisteni(int $pojisteni_id, int $castka, string $predmet, string $platnost_od, string $platnost_do) : void
    {
        $pojisteni = array(
            'castka' => $castka,
            'predmet' => $predmet,
            'platnost_od' => $platnost_od,
            'platnost_do' => $platnost_do,
        );
        try
        {
            Db::zmen('pojisteni', $pojisteni, 'WHERE pojisteni_id = ?', array($pojisteni_id));
        }
        catch (PDOException $chyba)
        {
            throw new ChybaPojistence('Změny se nepodařilo uložit.');
        }
    }
    
    public function vyberPojisteni(int $pojisteni_id) : array
    {
        return Db::dotazJeden('
            SELECT *
            FROM pojisteni
            WHERE pojisteni_id = ?
        ', array($pojisteni_id));
    }
    
    public function odstranPojisteni(int $pojisteni_id) : void
    {
        Db::dotaz('
            DELETE 
            FROM pojisteni
            WHERE pojisteni_id = ?
        ', array($pojisteni_id));
    }
    
    public function vyberVsechnaPojisteni(int $pojistenci_id) : array
    {
        return Db::dotazVsechny('
            SELECT *
            FROM `pojisteni`
            WHERE pojistenci_id = ?
            ORDER BY `pojisteni_id` DESC
        ', array($pojistenci_id));
    }
    
    public function vyberVsechnaAktivniPojisteni(int $pojistenci_id, string $datum) : array
    {
        return Db::dotazVsechny('
            SELECT *
            FROM `pojisteni`
            WHERE pojistenci_id = ? AND ? < platnost_do
            ORDER BY `pojisteni_id` DESC
        ', array($pojistenci_id, $datum));
    }
    
    public function vyberVsechnaUkoncenaPojisteni(int $pojistenci_id, string $datum) : array
    {
        return Db::dotazVsechny('
            SELECT *
            FROM `pojisteni`
            WHERE pojistenci_id = ? AND ? > platnost_do
            ORDER BY `pojisteni_id` DESC
        ', array($pojistenci_id, $datum));
    }
}
