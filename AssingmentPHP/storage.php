<?php 
interface IFileIO {
  function save($data);
  function load();
}
abstract class FileIO implements IFileIO {
  protected $filepath;

  public function __construct($filename) {
    if (!is_readable($filename) || !is_writable($filename)) {
      throw new Exception("Data source $filename is invalid.");
    }
    $this->filepath = realpath($filename);
  }
}
class JsonIO extends FileIO {
  public function load($assoc = true) {
    $file_content = file_get_contents($this->filepath);
    return json_decode($file_content, $assoc) ?: [];
  }

  public function save($data) {
    $json_content = json_encode($data, JSON_PRETTY_PRINT);
    file_put_contents($this->filepath, $json_content);
  }
}
class SerializeIO extends FileIO {
  public function load() {
    $file_content = file_get_contents($this->filepath);
    return unserialize($file_content) ?: [];
  }

  public function save($data) {
    $serialized_content = serialize($data);
    file_put_contents($this->filepath, $serialized_content);
  }
}

interface IStorage {
  function add($record): string;
  function findById(string $id);
  function findAll(array $params = []);
  function findOne(array $params = []);
  function update(string $id, $record);
  function delete(string $id);

  function findMany(callable $condition);
  function updateMany(callable $condition, callable $updater);
  function deleteMany(callable $condition);
}

class Storage implements IStorage {
  protected $contents; 
  protected $io;

  public function __construct(IFileIO $io, $assoc = true) {
      $this->io = $io;
      $this->contents = (array) $this->io->load($assoc);
  }

  public function __destruct() {
      $this->io->save($this->contents);
  }

  private $idCounterFile = './data/id_counter.json';

  public function add($record): string {
    $newId = uniqid(); 
    if (is_array($record)) {
        $record['id'] = $newId;
    } elseif (is_object($record)) {
        $record->id = $newId;
    }
    $this->contents[] = $record;
    return $newId;
}
  public function findById(string $id) {
      foreach ($this->contents as $item) {
          if ($item['id'] == $id) {
              return $item;
          }
      }
      return null;
  }

  public function findAll(array $params = []) {
      return array_filter($this->contents, function ($item) use ($params) {
          foreach ($params as $key => $value) {
              if ($item[$key] !== $value) {
                  return false;
              }
          }
          return true;
      });
  }

  public function findOne(array $params = []) {
      foreach ($this->contents as $item) {
          $match = true;
          foreach ($params as $key => $value) {
              if ($item[$key] !== $value) {
                  $match = false;
                  break;
              }
          }
          if ($match) {
              return $item;
          }
      }
      return null;
  }

  public function update(string $id, $record) {
      foreach ($this->contents as &$item) {
          if ($item['id'] == $id) {
              $record['id'] = $id; 
              $item = $record;
              return;
          }
      }
  }

  public function delete(string $id) {
    $this->contents = array_filter($this->contents, function ($item) use ($id) {
        return (string) $item['id'] !== (string) $id; 
    });
}

  public function findMany(callable $condition) {
      return array_filter($this->contents, $condition);
  }

  public function updateMany(callable $condition, callable $updater) {
      foreach ($this->contents as &$item) {
          if ($condition($item)) {
              $updater($item);
          }
      }
  }

  public function deleteMany(callable $condition) {
      $this->contents = array_filter($this->contents, function ($item) use ($condition) {
          return !$condition($item);
      });
  }
}
