<?php
namespace Portrino\PxHybridAuth\Hybrid\Providers;

    /***************************************************************
     *  Copyright notice
     *
     *  (c) 2016 Andre Wuttig <wuttig@portrino.de>, portrino GmbH
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
use Portrino\PxHybridAuth\Hybrid\User;

/**
 * Class Xing
 *
 * @package Portrino\PxHybridAuth\Hybrid\Providers
 */
class Xing extends \Hybrid_Providers_XING
{

    /**
     * Common providers adapter constructor
     *
     * @param Numeric /String $providerId
     * @param array $config
     * @param array $params
     */
    function __construct($providerId, $config, $params = null)
    {
        parent::__construct($providerId, $config, $params); // TODO: Change the autogenerated stub
        $this->user = new User();
    }

    /**
     * Gets the profile of the user who has granted access.
     *
     * @see https://dev.xing.com/docs/get/users/me
     */
    function getUserProfile()
    {

        parent::getUserProfile();

        $oResponse = $this->api->get('users/me');

        // The HTTP status code needs to be 200 here. If it's not, something is wrong.
        if ($this->api->http_code !== 200) {
            throw new \Exception('Profile request failed! ' . (string)$this->providerId . ' API returned an error: ' . $this->errorMessageByStatus($this->api->http_code) . '.');
        }

        // We should have an object by now.
        if (!is_object($oResponse)) {
            throw new \Exception('Profile request failed! ' . (string)$this->providerId . ' API returned an error: invalid response.');
        }

        // Redefine the object.
        $oResponse = $oResponse->users[0];

        $this->user->profile->email = (property_exists($oResponse, 'active_email')) ? $oResponse->active_email : '';

        if ((property_exists($oResponse, 'professional_experience'))) {
            $professionalExperience = $oResponse->professional_experience;
            if ((property_exists($professionalExperience, 'primary_company'))) {
                $primaryCompany = $professionalExperience->primary_company;
                if (property_exists($primaryCompany, 'name')) {
                    $this->user->profile->company = $primaryCompany->name;
                }
                if (property_exists($primaryCompany, 'title')) {
                    $this->user->profile->position = $primaryCompany->title;
                } else {
                    if (property_exists($primaryCompany, 'description')) {
                        $this->user->profile->position = $primaryCompany->description;
                    }
                }
                if (property_exists($primaryCompany, 'industry')) {
                    $this->user->profile->industry = $primaryCompany->industry;
                }
            }
        }

        // We use the largest picture available.
        $requestedPictureSize = \XingUserPicureSize::SIZE_ORIGINAL;
        if (property_exists($oResponse, 'photo_urls') && property_exists($oResponse->photo_urls,
                $requestedPictureSize)
        ) {
            $this->user->profile->photoURL = (property_exists($oResponse->photo_urls,
                $requestedPictureSize)) ? $oResponse->photo_urls->size_original : '';
        }

        return $this->user->profile;
    }
}
