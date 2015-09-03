<?php defined('SYSPATH') OR die('No direct script access.');

class Rating
{
    public static function allowVotes($mid, $ip)
    {
        // Проверка голосовал ли этот ip.
        $result = DB::select('*')
            ->from('manager_rating_votes')
            ->where('mid', '=', $mid)
            ->and_where('hostname', '=', $ip)
            ->limit(1)
            ->execute()
            ->as_array();

        if (count($result))
            return FALSE;

        // Blacklist без поддержки ipv6.
        // Проверка на ipv4.
        if(!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4))
            return TRUE;

        // Проверка по blacklist.
        $blacklist = new IPFilter(
            array(
                '127.0.0.1',
                '172.0.0.*',
                '173.0.*.*',
                '126.1.0.0/255.255.0.0',
                '125.0.0.1-125.0.0.9'
            )
        );
        if ($blacklist->check($ip))
            return FALSE;

        return TRUE;
    }

    public static function vote($mid, $type = '+')
    {
        if (!$mid)
            return FALSE;

        if (isset($_SERVER['REMOTE_ADDR']))
            DB::query(Database::INSERT, 'INSERT INTO manager_rating_votes (mid, hostname, date) VALUES (:mid, :hostname, NOW())')
            ->parameters(array(
                ':mid' => $mid,
                ':hostname' => $_SERVER['REMOTE_ADDR']
            ))
            ->execute();

        if ($type == '+')
            $this->like($mid);
        else
            $this->dislike($mid);

        return TRUE;
    }

    private static function like($mid)
    {
        DB::query(Database::UPDATE, 'UPDATE manager_rating SET total = total + 1 WHERE mid = :mid')
        ->param(':mid', $mid)
        ->execute();
    }

    private static function dislike($mid)
    {
        DB::query(Database::UPDATE, 'UPDATE manager_rating SET total = total - 1 WHERE mid = :mid')
        ->param(':mid', $mid)
        ->execute();
    }
}