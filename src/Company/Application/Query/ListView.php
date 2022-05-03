<?php
declare(strict_types=1);
namespace App\Company\Application\Query;

final class ListView extends AbstractView
{
    public $data;

    /**
     * @param mixed[] $data
     * @return self
     */
    public static function fromData(array $data): self
    {
        $self = new self();
        $self->data = $data;

        return $self;
    }
}

