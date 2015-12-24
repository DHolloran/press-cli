<?php

namespace App\Install;

use PDO;
use Symfony\Component\Console\Output\OutputInterface;

class Database
{
    protected $db_name;

    protected $db_prefix = 'wp_';

    protected $db_table_prefix = 'wp_';

    protected $db_password = 'root';

    protected $db_user = 'root';

    protected $db_host = '127.0.0.1';

    protected $db_connection;

    protected $output;

    public function __construct($domain, OutputInterface $output)
    {
        $this->db_name = $this->makeDBNameFromDomain($domain);

        $this->getDBConnection();

        $this->output = $output;
    }

    protected function makeDBNameFromDomain($domain)
    {
        $db_name = str_replace( array( '-', '_', '.' ), ' ', $domain );
        $db_name = preg_replace("/[^A-Za-z0-9 ]/", "", $db_name);
        $db_name = preg_replace('!\s+!', ' ', $db_name);
        $db_name = str_replace( array( ' ' ), '_', $db_name );

        return "{$this->db_prefix}{$db_name}";
    }

    /**
     * Gets the value of db_name.
     *
     * @return mixed
     */
    public function getDBName()
    {
        return $this->db_name;
    }

    /**
     * Sets the value of db_name.
     *
     * @param mixed $db_name the db name
     *
     * @return self
     */
    protected function setDBName($db_name)
    {
        $this->db_name = $db_name;

        return $this;
    }

    /**
     * Gets the value of db_prefix.
     *
     * @return mixed
     */
    public function getDBPrefix()
    {
        return $this->db_prefix;
    }

    /**
     * Sets the value of db_prefix.
     *
     * @param mixed $db_prefix the db prefix
     *
     * @return self
     */
    protected function setDBPrefix($db_prefix)
    {
        $this->db_prefix = $db_prefix;

        return $this;
    }

    /**
     * Gets the value of db_table_prefix.
     *
     * @return mixed
     */
    public function getDBTablePrefix()
    {
        return $this->db_table_prefix;
    }

    /**
     * Sets the value of db_table_prefix.
     *
     * @param mixed $db_table_prefix the db table prefix
     *
     * @return self
     */
    protected function setDBTablePrefix($db_table_prefix)
    {
        $this->db_table_prefix = $db_table_prefix;

        return $this;
    }

    /**
     * Gets the value of db_password.
     *
     * @return mixed
     */
    public function getDBPassword()
    {
        return $this->db_password;
    }

    /**
     * Sets the value of db_password.
     *
     * @param mixed $db_password the db password
     *
     * @return self
     */
    protected function setDBPassword($db_password)
    {
        $this->db_password = $db_password;

        return $this;
    }

    /**
     * Gets the value of db_user.
     *
     * @return mixed
     */
    public function getDBUser()
    {
        return $this->db_user;
    }

    /**
     * Sets the value of db_user.
     *
     * @param mixed $db_user the db user
     *
     * @return self
     */
    protected function setDBUser($db_user)
    {
        $this->db_user = $db_user;

        return $this;
    }

    /**
     * Gets the value of db_host.
     *
     * @return mixed
     */
    public function getDBHost()
    {
        return $this->db_host;
    }

    /**
     * Sets the value of db_host.
     *
     * @param mixed $db_host the db host
     *
     * @return self
     */
    protected function setDBHost($db_host)
    {
        $this->db_host = $db_host;

        return $this;
    }

    /**
     * Gets the value of db_connection.
     *
     * @return mixed
     */
    public function getDBConnection()
    {
        if ( isset( $this->db_connection ) ) {
            return $this->db_connection;
        }

        $this->db_connection = new PDO(
            "mysql:host={$this->db_host}",
            $this->db_user,
            $this->db_password
        );

        return $this->db_connection;
    }
}
