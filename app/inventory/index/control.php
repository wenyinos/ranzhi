<?php
class index extends control
{
    public function index()
    {
        header('location: ' . $this->createLink('purchase', 'browse'));
        exit;
    }

    public function browse()
    {
        $this->locate($this->createLink('purchase', 'browse'));
    }
}
