<?php // COPY PASTA FROM JBA BRIDGE
namespace JBAShop\Traits;

trait RallyModel
{   
    public function getDb()
    {
        $mongo = \Dsc\System::instance()->get('mongo_secondary');
        if (is_a($mongo, '\MongoDB')) {
            return $mongo;
        } else if (is_a($mongo, '\Dsc\Mongo\Db')) {
            return $mongo->db();
        }
        
        return $mongo;
    }

    public static function collection()
    {
        $item = new static();
        $database = \Base::instance()->get('db.mongo.secondary.database');
        return $item->getDb()->selectCollection($database, $item->collectionName() );
    }
    
    public function save($document = [], $options = [])
    {
        return $this;
    }
    
    public function store($options = [])
    {
        return $this;
    }
}
