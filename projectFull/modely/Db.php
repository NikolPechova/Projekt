<?php
/**
 * Description of Db
 *
 * @author nikky
 */
class Db {
    private static PDO $spojeni;
    
    private static array $nastaveni = array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
    PDO::ATTR_EMULATE_PREPARES => false,
    );
    
    public static function pripoj(string $host, string $uzivatel, string $heslo, string $databaze) : void
    {
        if (!isset(self::$spojeni))
        {
            self::$spojeni = @new PDO(
                "mysql:host=$host;dbname=$databaze",
                $uzivatel,
                $heslo,
                self::$nastaveni
            );
        }
    }
    
    public static function dotazJeden(string $dotaz, array $parametry = array()) : array|bool
    {
        $navrat = self::$spojeni->prepare($dotaz);
        $navrat->execute($parametry);
        return $navrat->fetch();
    }
    
    public static function dotazVsechny(string $dotaz, array $parametry = array()) : array|bool
    {
        $navrat = self::$spojeni->prepare($dotaz);
        $navrat->execute($parametry);
        return $navrat->fetchAll();
    }
    
    public static function dotazSamotny(string $dotaz, array $parametry = array()) : string
    {
        $vysledek = self::dotazJeden($dotaz, $parametry);
        return $vysledek[0];
    }
    
    public static function dotaz(string $dotaz, array $parametry = array()) : int
    {
        $navrat = self::$spojeni->prepare($dotaz);
        $navrat->execute($parametry);
        return $navrat->rowCount();
    }
    
    public static function vloz(string $tabulka, array $parametry = array()) : bool
    {
        return self::dotaz("INSERT INTO `$tabulka` (`".
            implode('`, `', array_keys($parametry)).
            "`) VALUES (".str_repeat('?,', sizeOf($parametry)-1)."?)",
                array_values($parametry));
    }
    
    public static function zmen(string $tabulka, array $hodnoty = array(), string $podminka, array $parametry = array()) : bool
    {
        return self::dotaz("UPDATE `$tabulka` SET `".
            implode('` = ?, `', array_keys($hodnoty)).
            "` = ? " . $podminka,
            array_merge(array_values($hodnoty), $parametry));
    }
    
    public static function posledniId() : int
    {
        return self::$spojeni->lastInsertId();
    }

}
