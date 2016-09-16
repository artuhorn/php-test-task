<?php

namespace App;

use DateTime;

/**
 * Class Post
 * @package App
 * @property $id
 * @property $title
 * @property $text
 * @property $image
 * @property $created_at
 * @property $updated_at
 */
class Post
{
    const TABLE = 'post';

    /** @var string */
    protected $id;

    public function __construct()
    {
        if ($this->isNew()) {
            $this->created_at = time();
            $this->updated_at = $this->created_at;
        }
    }

    /**
     * @param string $id
     * @return bool|static
     */
    public static function findById(string $id)
    {
        $db = DB::instance();
        $result = $db->query('SELECT * FROM ' . static::TABLE . ' WHERE id = :id', [':id' => $id], static::class);

        if (count($result) > 0) {
            return $result[0];
        }

        return false;
    }

    /**
     * @param array $where
     * @param array $orderBy
     * @param int $limit
     * @param array $params
     * @return array|static[]
     */
    public static function find(array $where = [], array $orderBy = [], int $limit = 0, array $params = []): array
    {
        $whereClause = implode(',', $where);
        $orderByClause = implode(',', $orderBy);

        $query = 'SELECT * FROM ' . static::TABLE .
            (count($where) > 0 ? ' WHERE ' . $whereClause : '') .
            (count($orderBy) > 0 ? ' ORDER BY ' . $orderByClause : '') .
            ($limit > 0 ? ' LIMIT ' . $limit : '');

        return DB::instance()->query($query, $params, static::class);
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return bool
     */
    public function isNew()
    {
        return empty($this->id);
    }

    /**
     * @param $timestamp
     * @return string
     */
    public function formatDate($timestamp)
    {
        return $this->format($timestamp, 'd-m-Y');
    }

    /**
     * @param $timestamp
     * @return string
     */
    public function formatTime($timestamp)
    {
        return $this->format($timestamp, 'H:i');
    }

    /**
     * @param $timestamp
     * @param $format
     * @return string
     */
    protected function format($timestamp, $format)
    {
        $date = new DateTime();
        $date->setTimestamp($timestamp);
        return $date->format($format);
    }

    /**
     * @return bool
     */
    public function delete()
    {
        $query = 'DELETE FROM ' . static::TABLE . ' WHERE id = :id';

        return DB::instance()->execute($query, [':id' => $this->id]);
    }

    /**
     * @return bool
     */
    public function save(): bool
    {
        if ($this->isNew()) {
            return $this->insert();
        } else {
            return $this->update();
        }
    }

    /**
     * @return bool
     */
    protected function insert(): bool
    {
        $fields = [];
        $params = [];
        $this->setFieldsAndParams($fields, $params);

        $query = 'INSERT INTO ' . static::TABLE .
            ' (' . implode(', ', array_keys($fields)) . ')' .
            ' VALUES ' .
            ' (' . implode(', ', array_keys($params)) . ')';

        $db = DB::instance();
        $result = $db->execute($query, $params);
        if ($result === true) {
            $this->id = $db->getLastId();
        }

        return $result;
    }

    /**
     * @return bool
     */
    protected function update(): bool
    {
        $this->updated_at = time();

        $fields = [];
        $params = [];
        $this->setFieldsAndParams($fields, $params);

        $set = array_map(function ($key, $value) {
            return $key . ' = ' . $value;
        }, array_keys($fields), $fields);

        $query = 'UPDATE ' . static::TABLE .
            ' SET ' . implode(', ', $set) .
            ' WHERE id = :id';

        $db = DB::instance();
        return $db->execute($query, array_merge($params, [':id' => $this->id]));
    }

    /**
     * @param array $fields
     * @param array $params
     */
    protected function setFieldsAndParams(array &$fields, array &$params)
    {
        foreach ($this as $key => $value) {
            if ($key == 'id') {
                continue;
            }
            $fields[$key] = ':' . $key;
            $params[':' . $key] = $value;
        }
    }
}
