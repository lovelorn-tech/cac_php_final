<?php
require_once("interfaces/ISerializable.php");

class SerializableArray implements ISerializable
{
    private array $array = [];

    public function __construct(?array $array = null)
    {
        if ($array != null) {
            $this->pushMany($array);
        }
    }

    public function serialize(): array
    {
        $serialized = [];
        foreach ($this->array as $key => $element) {
            $serialized[] = $element->serialize();
        }
        return $serialized;
    }

    public function getArray(): array {
        $newArray = $this->array;
        return $newArray;
    }

    public function push(ISerializable $element, ?bool $nullable = false): void
    {
        try {
            if ($element instanceof ISerializable) {
                $this->array[] = $element;
            } else if ($nullable) {
                $this->array[] = null;
            } else {
                throw new Exception("Element must be serializable.");
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function pushMany(array $elements, ?bool $nullable = false): void
    {
        $aux = $this->array;
        try {
            foreach ($elements as $key => $element) {
                $this->push($element, $nullable);
            }
        } catch (\Throwable $th) {
            $this->array = $aux;
            throw $th;
        }
    }

    public function pushFirst(ISerializable $element, ?bool $nullable = false): void
    {
        $aux = $this->array;
        try {
            $this->array = [];
            $this->push($element, $nullable);
            $this->array = array_merge($this->array, $aux);
        } catch (\Throwable $th) {
            $this->array = $aux;
            throw $th;
        }
    }

    public function pushManyFirst(array $elements, ?bool $nullable = false): void
    {
        $aux = $this->array;
        try {
            $this->array = [];
            $this->pushMany($elements, $nullable);
            $this->array = array_merge($this->array, $aux);
        } catch (\Throwable $th) {
            $this->array = $aux;
            throw $th;
        }
    }

    public function first(): ?ISerializable
    {
        return reset($this->array) ?: null;
    }

    public function last(): ?ISerializable
    {
        return end($this->array) ?: null;
    }

    public function pop(): ?ISerializable
    {
        return array_pop($this->array);
    }

    public function shift(): ?ISerializable
    {
        return array_shift($this->array);
    }

    public function clear(): void
    {
        $this->array = [];
    }
}
