<?php
declare(strict_types=1);

namespace FreeRouter\Tools;


use FreeRouter\Http\Redirect;
use FreeRouter\Interface\IController;
use FreeRouter\Interface\IRouter;
use FreeRouter\Interface\IRouterController;
use JetBrains\PhpStorm\Pure;

class ClassRunner
{

    private IRouterController|IRouter $class;
    private array $attributes;
    private array $attributesNullable = [];
    private string $pathTemplate;

    public function setClass(IRouter|IRouterController $class): ClassRunner {
        $this->class = $class;

        return $this;
    }

    public function setAttributes(array $attributes): ClassRunner {
        $this->attributes = $attributes;
        return $this;
    }

    public function setPathTemplate(string $pathTemplate): ClassRunner {
        $this->pathTemplate = $pathTemplate;
        return $this;
    }

    public function runFunction(string $func): void {
        $mapped = $this->mapArrayAttributes($this->getEmptyArrayParams($this->pathTemplate), $this->attributes);
        $this->class->before();
        $data = $this->class->{$func}(...$mapped);
        $this->observe($data);
        $this->class->after();
    }

    private function getEmptyArrayParams(string $path): array {
        $exploded = explode('/', $path);
        $returnArray = [];

        foreach ($exploded as $explode) {
            if(str_contains($explode, '{') && str_contains($explode, '}')) {
                $clearedName = str_replace(array('}', '{'), '', $explode);

                //Nullable parameter
                if($clearedName[0] === '?') {
                    $clearedName = str_replace('?', '', $clearedName);
                    $this->attributesNullable[] = $clearedName;
                }

                $returnArray[$clearedName] = "";
            }
        }

        return $returnArray;
    }

    #[Pure]
    private function mapArrayAttributes(array $unmapped, array $values): array {
        $keyValue = 0;

        if((count($unmapped) - count($this->attributesNullable)) > count($values)) {
            throw new \Exception("You can't declare full path with undefined variables, set them to nullable or add another parameters to the URL");
        }

        foreach ($unmapped as $key => $un) {
            if(!isset($values[$keyValue]) && in_array($key, $this->attributesNullable, true)) {
                unset($unmapped[$key]);
                continue;
            }

            $unmapped[$key] = $values[$keyValue];
            $keyValue++;
        }

        return $unmapped;
    }

    private function observe($data): void {
        if($data instanceof Redirect) {
            $data->redirect();
        }

        $classReflection = new \ReflectionClass($this->class);

        if(count($classReflection->getAttributes()) > 0) {
            $classCall = $classReflection->getAttributes()[0]->getName();

            /** @var IController $class */
            $class = new $classCall();

            $class->render($data);
        }
    }
}