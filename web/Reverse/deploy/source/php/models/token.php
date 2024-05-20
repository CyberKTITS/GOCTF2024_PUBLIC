<?php

class Token {
    public $timespamp, $nonce;

    public function __construct(int $nonce, int $timespamp) {
        $this->timespamp = $timespamp;
        $this->nonce = $nonce;
    }

    public function create_token() {
        return md5(''.$this->nonce);
    }
}