<?php

    class DB{
        
        
        public $pdo;
        

        public function __construct($options = [])
        {
            $db = parse_ini_file("../config/.ini");

            $user = $db['user'];
            $pass = $db['pass'];
            $name = $db['name'];
            $host = $db['host'];
            $type = $db['type'];
            $port = $db['port'];

            $default_options = [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ];
            $options = array_replace($default_options, $options);
            $dsn = "$type:host=$host;dbname=$name;port=$port";

            try {
                $this->pdo = new \PDO($dsn, $user, $pass, $options);
            } catch (PDOException $e) {
                throw new PDOException($e->getMessage(), (int)$e->getCode());
            }
        }

        public function run($sql, $args = NULL)
        {
            if (!$args)
            {
                return $this->pdo->query($sql);
            }
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($args);
            return $stmt;
        }
    }
?>