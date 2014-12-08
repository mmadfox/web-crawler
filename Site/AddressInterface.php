<?php
namespace Madfox\WebCrawler\Site;

use Madfox\WebCrawler\Exception\InvalidAddressException;

interface AddressInterface {

    /**
     * @throws InvalidAddressException
     */
   public function validate();

    /**
     * @return string
     */
   public function getScheme();

    /**
     * @return string
     */
   public function getHostname();

    /**
     * @return int
     */
   public function getPort();

    /**
     * @return string
     */
   public function getUser();

    /**
     * @return string
     */
   public function getPassword();

    /**
     * @return string
     */
   public function toString();
} 