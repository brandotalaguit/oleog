<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Nativesession
{
    public function __construct()
    {
        session_start();
    }

    public function set( $key, $value = NULL )
    {
        if ( ! is_array($key))
        {
            $key = array($key => $value);
        }

        foreach ($key as $k => $v)
        {
            $_SESSION[$k] = $v;
        }
    }

    public function get( $key )
    {
        return isset( $_SESSION[$key] ) ? $_SESSION[$key] : null;
    }

    public function regenerateId( $delOld = false )
    {
        session_regenerate_id( $delOld );
    }

    public function delete( $key )
    {
        unset( $_SESSION[$key] );
    }
}