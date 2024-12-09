## Usage: 
### 1. Add To Model for reindex integration version for specific item
```php
     public function save(array $options = [])
    {
        $result = parent::save($options);

        Context::getInstance()->getIntegrationVersionManager()->executeOne(Seeder::SOURCE, $this->id);

        return $result;
    }
```

### 2. Reindex By Source
#####  with queue: 
```php
use IntegrationHelper\IntegrationVersionLaravelServer\Jobs\RunReindexQueue;

        $sourceCode = 'product_inventory';
        dispatch(new RunReindexQueue($sourceCode));

```
##### without queue
```php
use IntegrationHelper\IntegrationVersion\Context;
        $sourceCode = 'product_inventory';

        Context::getIntegrationVersionManager()->executeFull($sourceCode);

```

### 3. API
```
Use POST http method: 

    1) api/v1/admin/integration-version/get-identities
    Return identites for cur source
    Params: 
    - source - source code
    - old_hash - Integration version hash
    - hash_date_time - Date time the last storad hash in client side (For old hash) 
    - page > 0
    - limit > 499
    
    2) api/v1/admin/integration-version/get-latest-hash
    Return latest hash and Hash Date Time
    Params: 
    - source - source code
    
    3) api/v1/admin/integration-version/get-data-by-identities
    Return data by identities for source
     Params: 
        - source - source code
        - identities - lsit of identities
        
     get-deleted-identities
    Return an array of deleted ids based on a check of incoming identites
     Params: 
        - source - source code
        - identities_for_check - lsit of identities
    
```
