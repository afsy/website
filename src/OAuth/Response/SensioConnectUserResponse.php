<?php

namespace App\OAuth\Response;

use HWI\Bundle\OAuthBundle\OAuth\Response\SensioConnectUserResponse as BaseResponse;

class SensioConnectUserResponse extends BaseResponse
{
    /**
     * Retrieves the city from the response.
     */
    public function getCity()
    {
        return $this->getNodeValue('./vcard:locality', $this->data);
    }
}
