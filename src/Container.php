<?php 

declare(strict_types= 1);

namespace src;

use Psr\Container\ContainerInterface;
use ReflectionParameter;
use src\Exceptions\NotFoundException;
use src\Exceptions\ContainerException;


class Container implements ContainerInterface
{ 

    private array $enties = [];

    public function __construct()
    {}
     
    public function get(string $id) {

        /*if(!isset($this->enties[$id])){
            throw new NotFoundException('id not found');
        }*/

        if( $this->has($id) ){
            
            $entry = $this->enties[$id];
            return $entry($this);

        }

        return $this->resolve($id);

    }

    public function has(string $id):bool{
        return isset($this->enties[$id]);
    }

    public function set(string $id, callable $concreate):void {
        $this->enties[$id] = $concreate;
    }


    public function resolve(string $id){

        // 1 inspect the class, what trying to get from container
        $reflectionClass =  new \ReflectionClass($id);
        
        if( !$reflectionClass->isInstantiable() ){
            throw new ContainerException('hello mada fakaz');
        }
// die("dsad");
        // 2 inspect the constructor
        $constructor = $reflectionClass->getConstructor();
/*var_dump($constructor);
exit();*/
        if( !$constructor ){
            return new $id;
        }

        // 3 inspect constructor parameters
        $parameters = $constructor->getParameters();

        if( !$parameters ){
            return new $id;
        }

        // 4 if the constructor parameter is a class, try to resolve is using container
        /*$dependencies = array_map(
            function(ReflectionParameter $param){
                $name = $param->getName();
                $type = $param->getType();
                echo '<pre style="background-color: #f5f5f5; padding: 10px; border: 1px solid #ccc;">';
                var_dump($type->getName());
                echo '</pre>';
                exit();
                if( !$type ){
                    throw new ContainerException('Nincs typehint');
                }

                if( $type instanceof \ReflectionUnionType){ // ezta  részt ujra nézni !!!
                    throw new ContainerException('Union type miatt nem példánysítható');
                }

                if($type instanceof \ReflectionNamedType && !$type->isBuiltin()){
                    return $this->get($type->getName());
                }

                throw new ContainerException('nem példánysítható (invalid partemeter)');

            }, 
        $parameters);*/







        // Limp Bizkit - MyWay
        $dependencies = [];
        foreach($parameters as $param){
            $name = $param->getName();
            $type = $param->getType();

            if( !$type ){
                throw new ContainerException('Nincs typehint');
            }

            if( $type instanceof \ReflectionUnionType){ // ezta  részt ujra nézni !!!
                throw new ContainerException('Union type miatt nem példánysítható');
            }

            if($type instanceof \ReflectionNamedType && !$type->isBuiltin()){
                $dependencies[] = $this->get($type->getName());
            } else {
                throw new ContainerException('nem példánysítható (invalid partemeter)');
            }
        }

        return $reflectionClass->newInstanceArgs($dependencies);
    }


}
