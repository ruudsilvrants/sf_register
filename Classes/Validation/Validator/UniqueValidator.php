<?php
namespace Evoweb\SfRegister\Validation\Validator;

/***************************************************************
 * Copyright notice
 *
 * (c) 2011-17 Sebastian Fischer <typo3@evoweb.de>
 * All rights reserved
 *
 * This script is part of the TYPO3 project. The TYPO3 project is
 * free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use TYPO3\CMS\Extbase\Validation\Validator\ValidatorInterface;

/**
 * A password validator
 */
class UniqueValidator extends \TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator implements ValidatorInterface
{
    /**
     * @var bool
     */
    protected $acceptsEmptyValues = false;

    /**
     * @var array
     */
    protected $supportedOptions = [
        'global' => [
            true,
            'Whether to check uniqueness globally',
            'boolean'
        ],
    ];

    /**
     * @var \Evoweb\SfRegister\Domain\Repository\FrontendUserRepository
     */
    protected $userRepository = null;

    /**
     * @var string
     */
    protected $propertyName = '';

    public function injectUserRepository(\Evoweb\SfRegister\Domain\Repository\FrontendUserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function setPropertyName(string $propertyName)
    {
        $this->propertyName = $propertyName;
    }

    /**
     * If the given passwords are valid
     *
     * @param string $value The value
     *
     * @return bool
     */
    public function isValid($value): bool
    {
        $result = true;

        if ($this->userRepository->countByField($this->propertyName, $value)) {
            $this->addError(
                \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                    'error_notunique_local',
                    'SfRegister'
                ),
                1301599608
            );
            $result = false;
        } elseif ($this->options['global'] && $this->userRepository->countByFieldGlobal($this->propertyName, $value)) {
            $this->addError(
                \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                    'error_notunique_global',
                    'SfRegister'
                ),
                1301599619
            );
            $result = false;
        }

        return $result;
    }
}
