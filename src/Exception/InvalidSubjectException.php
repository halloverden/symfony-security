<?php


namespace HalloVerden\Security\Exception;

class InvalidSubjectException extends \RuntimeException {

  /**
   * InvalidSubjectException constructor.
   *
   * @param null            $subject
   * @param \Exception|null $previous
   * @param int             $code
   */
  public function __construct($subject = null, \Exception $previous = null, $code = 0) {
    parent::__construct('Unsupported subject'. ($subject ? ' (' . get_class($subject) . ')' : ''), $code, $previous);
  }

}
