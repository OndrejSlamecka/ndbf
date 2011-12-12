NDBF - Layer for Nette\Database
===============================

NDBF is a layer above Nette\Database. Please introduce yourself to Nette\Database syntax first, because it is essential to understand NDBF.

Use
---
In bootstrap.php you create the service:

    // Create database connection with settings
    list($dsn, $user, $password) = $container->params['database'];
    $container->addService('database', \NDBF\Factory::createService($this->container, $dsn, $user, $password));
    
    // Register repositoryManager
    $this->container->addService('repositoryManager', new \NDBF\RepositoryManager($container));


Put this method into your BasePresenter:

    final public function getRepositories()
    {
        return $this->context->repositoryManager;
    }


And you can use it in your presenters:

    // ... renderDefault()
    $products = $this->repositories->Product;
    
    // Returns all 'Red' products ordered by 'price', instance of Nette\Database\Table\Selection
    $products->find(array('color'=>'Red'),'price');
    
    // Fetches product with id 15 (this is already implemented in Nette\Database - NDBF fluently extends its functionality) 
    $products->find(array('id'=>15),'price')->fetch(); 
    
    // Returns Nette\Database\Table\Selection - NDBF fills table name for you
    $products->table();


If default functions (see NDBF\Repository) aren't enough for you, just extend them by writing your own repository.
Do you want to extend Product repository?

    namespace Application\Repository; // This is neccessary
    
    class Product extends \NDBF\Repository
    {
        function findOnlyFifteenCoolProducts()
        {
            return $this->find(array('is_cool'=>true),null,15);
        }
    } 

Disclaimer
----------
This is not a part of Nette Framework!! Use at your own risk.


