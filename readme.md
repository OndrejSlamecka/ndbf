NDBF - Layer for Nette\Database
===============================

NDBF is a thin layer above Nette\Database which offers interface based on repository manager/repositories model.
Please introduce yourself to Nette\Database syntax first, because it is essential to understand NDBF.

Use
---
In config.neon create database connection/service using class Nette\Database\Connection. Then in bootstrap.php add following callback:

    $configurator->onCompile[] = function ($cf, $compiler) { $compiler->addExtension('ndbf', new Ndbf\CompilerExtension); };

Put this method into your BasePresenter:

    public function getRepositories()
    {
        return $this->getService('repositoryManager');
    }

And you can use it in your presenters:

    // In renderDefault() or similar:
    $products = $this->repositories->Product;

    // Use simplified methods select(), table(), fetchPairs(), count()
    $products->select()->where('color', 'red')->order('price');

    // Saves new $product
    $product = array('name' => 'FooBar');
    $products->save($product, 'id'); // $product recieves newly assigned id after save

    // Updates product
    $product->save($product, 'id'); // $product has id assigned from the previous save

    // Removes product with id 15
    $products->remove(array('id' => 15));


For guide on own repositories and further information see [the wiki](/OndrejSlamecka/ndbf/wiki).


Disclaimer
----------
This is not a part of Nette Framework!! Use at your own risk.

Released under the New BSD license.
