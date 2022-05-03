<?php
declare(strict_types=1);
namespace App\Company\Application\Query;

abstract class AbstractView implements \JsonSerializable
{
    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
