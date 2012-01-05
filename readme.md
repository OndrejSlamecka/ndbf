NDBF - Layer for Nette\Database
===============================

NDBF is a thin layer above Nette\Database. Please introduce yourself to Nette\Database syntax first, because it is essential to understand NDBF.

Use
---
In config.neon you create the service like below. Service with Nette\Database\Connection is required, but how you create it is up to you.

    services:        
        database: Nette\Database\Connection('mysql:host=localhost;dbname=testdb','root','toor')
        repositoryManager: NDBF\RepositoryManager(...)


Put this method into your BasePresenter:

    final public function getRepositories()
    {
        return $this->getService('repositoryManager');
    }


And you can use it in your presenters:

    // In renderDefault() or similar:
    $products = $this->repositories->Product;
    
    // Returns all 'Red' products ordered by 'price', instance of Nette\Database\Table\Selection
    $products->find(array('color'=>'Red'),'price');
    
    // Fetches product with id 15 (this is method of Nette\Database - NDBF fluently extends its functionality) 
    $products->find(array('id'=>15),'price')->fetch(); 
    
    // Returns Nette\Database\Table\Selection - NDBF fills table name for you
    $products->table();

    // Saves new $product
    $product = array('name' => 'FooBar');
    $products->save($product, 'id'); // $product recieves newly assigned id after save

    // Updates product
    $product->save($product, 'id'); // $product has id assigned from the previous save

    // Removes product with id 15
    $products->remove(array('id' => 15));


If default functions (see NDBF\Repository) aren't enough for you, just extend them by writing your own repository.
Do you want to extend Product repository?

    namespace Application\Repository; // This namespace is necessary
    
    class Product extends \NDBF\Repository
    {
        function findOnlyFifteenCoolProducts()
        {
            return $this->find(array('is_cool'=>true),null,15);
        }
    } 

Disclaimer
----------
Released under the New BSD license.

This is not a part of Nette Framework!! Use at your own risk.


