<?php
/**
 * Created by PhpStorm.
 * User: fris
 * Date: 07/05/18
 * Time: 08:41 AM
 */

class Database
{
    /** @var string */
    const FILE_HANDLE = 'db';

    /** @var string */
    private $filename;
    /** @var array */
    private $data = [];

    public function __construct(string $filename)
    {
        $this->setFilename($filename);
        if(!file_exists($filename)) {
            echo "Creating the database's file:" . fopen($filename, "w") . PHP_EOL;
        } else {
            foreach (json_decode(file_get_contents($filename)) as $key => $value){
                $this->addData($key, $value);
            }
        }
    }

    /**
     * @return Database
     */
    public function saveData(): self
    {
        file_put_contents($this->getFilename() , json_encode($this->getData(), JSON_PRETTY_PRINT | JSON_BIGINT_AS_STRING));
        return $this;
    }

    /**
     * @return string
     */
    public function getFilename(): string
    {
        return $this->filename;
    }

    /**
     * @param string $filename
     * @return Database
     */
    public function setFilename(string $filename): self
    {
        $this->filename = $filename;
        return $this;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array $data
     * @return Database
     */
    public function setData(array $data): self
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @param $key
     * @param $value
     * @return Database
     */
    public function addData($key, $value): self
    {
        $this->data[strtolower($key)] = $value;
        return $this;
    }

    /**
     * @param $key
     * @return Database
     */
    public function removeData($key): self
    {
        if (key_exists($key, $this->data)){
            unset($this->data[$key]);
        }
        return $this;
    }

    /**
     * @return bool
     */
    public function close(): bool
    {
        $this->saveData();
        unset($this->data);
        unset($this->filename);
        return true;
    }
}
