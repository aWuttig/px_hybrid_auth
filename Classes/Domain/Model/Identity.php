<?php
namespace Portrino\PxHybridAuth\Domain\Model;

    /***************************************************************
     *
     *  Copyright notice
     *
     *  (c) 2016 André Wuttig <wuttig@portrino.de>, portrino GmbH
     *
     *  All rights reserved
     *
     *  This script is part of the TYPO3 project. The TYPO3 project is
     *  free software; you can redistribute it and/or modify
     *  it under the terms of the GNU General Public License as published by
     *  the Free Software Foundation; either version 3 of the License, or
     *  (at your option) any later version.
     *
     *  The GNU General Public License can be found at
     *  http://www.gnu.org/copyleft/gpl.html.
     *
     *  This script is distributed in the hope that it will be useful,
     *  but WITHOUT ANY WARRANTY; without even the implied warranty of
     *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
     *  GNU General Public License for more details.
     *
     *  This copyright notice MUST APPEAR in all copies of the script!
     ***************************************************************/
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * Class Identity
 *
 * @package Portrino\PxHybridAuth\Domain\Model
 */
class Identity extends AbstractEntity
{

    /**
     * identifier
     *
     * @var string
     */
    protected $identifier = '';

    /**
     * @var string
     */
    protected $extbaseType = '';

    /**
     * Returns the identifier
     *
     * @return string $identifier
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * Sets the identifier
     *
     * @param string $identifier
     *
     * @return void
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
    }

    /**
     * @param string $extbaseType
     */
    public function setExtbaseType($extbaseType)
    {
        $this->extbaseType = $extbaseType;
    }

    /**
     * @return string
     */
    public function getExtbaseType()
    {
        return $this->extbaseType;
    }

    /**
     * returns the class
     *
     * @return string
     */
    public function getType()
    {
        return get_class($this);
    }

    /**
     * returns the provider name
     *
     * @return string
     */
    public function getProvider()
    {
        $reflection = new \ReflectionClass($this);
        return str_replace('Identity', '', $reflection->getShortName());
    }

}