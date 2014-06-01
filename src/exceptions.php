<?php

namespace Flunorette;

class Exception extends \Exception {

}

class InvalidArgumentException extends Exception {

}

class InvalidStateException extends Exception {

}

class UndeclaredColumnException extends InvalidStateException {

}
