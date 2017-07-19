<?php

namespace Test;

/**
 * simple ActiveRecord for banner views
 *
 * @property $ip_address
 * @property $page_url
 * @property $view_date
 * @property $ip_version
 * @property $user_agent
 * @property $views_count
 */
final class BannerView {

    private static $tb = 'views';

    /**
     * @var Database
     */
    private static $db;

    public function __construct()
    {
        $this->ip_address = long2ip($this->ip_address);
    }

    public static function setDb($db) {
        self::$db = $db;
    }

    public static function findByComplex($ip, $url, $userAgent) {
        $stmt = self::$db->getPDO()->prepare("SELECT * FROM ".self::$tb." WHERE ip_address = :ip AND page_url = :page_url AND user_agent = :agent AND ip_version = :ip_version LIMIT 1");
        $stmt->bindValue(':ip', ip2long($ip));
        $stmt->bindValue(':ip_version', \Test\Helpers::getIPVersion($ip)); // TODO: add IPv6 support
        $stmt->bindValue(':page_url', $url);
        $stmt->bindValue(':agent', $userAgent);
        $stmt->execute();

        return $stmt->fetchObject(static::class);
    }

    /**
     * @param $ip
     * @param $url
     * @param $userAgent
     * @return false | static
     */
    public static function create($ip, $url, $userAgent) {
        self::$db->getPDO()
            ->prepare("INSERT INTO ".self::$tb." (ip_address, page_url, user_agent, view_date, ip_version) VALUES (:ip, :page_url, :agent, :view_date, :ip_version)")
            ->execute([
                ':ip' => ip2long($ip), // TODO: add IPv6 support
                ':page_url' => $url,
                ':agent' => $userAgent,
                ':view_date' => (new \DateTime())->format('Y-m-d H:i:s'),
                ':ip_version' => \Test\Helpers::getIPVersion($ip),
            ]);
        return self::findByComplex($ip, $url, $userAgent);
    }


    public function incrementView($count) {
        self::$db->getPDO()
            ->prepare("UPDATE ".self::$tb." SET  view_date = :view_date, views_count = views_count + :views_count WHERE ip_address = :ip AND page_url = :page_url AND user_agent = :agent AND ip_version = :ip_version")
            ->execute([
                ':view_date' => (new \DateTime())->format('Y-m-d H:i:s'),
                ':views_count' => (int)$count,
                ':ip' => ip2long($this->ip_address), // TODO: add IPv6 support
                ':page_url' => $this->page_url,
                ':agent' => $this->user_agent,
                ':ip_version' => \Test\Helpers::getIPVersion($this->ip_address),
            ]);
    }


}