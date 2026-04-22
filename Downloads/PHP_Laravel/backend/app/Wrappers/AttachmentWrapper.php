<?php

namespace App\Wrappers;

class AttachmentWrapper
{
    protected $path;
    protected $name;

    public function __construct($path, $name = null)
    {
        $this->path = $path;
        $this->name = $name ?: basename($path);
    }

    public function attachTo($mailer)
    {
        $mailer->addAttachment($this->path, $this->name);
        return $this;
    }
}
