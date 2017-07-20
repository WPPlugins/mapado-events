<?php

namespace Mapado\Sdk\Client;

/**
 * Suggest code Client
 */
class SuggestCodeClient extends AbstractClient
{
    const SUGGEST_CODE_URL = '/suggest-codes';


    /**
     * Find one suggest code
     * @param string $id
     * @param string $cssSelector
     *
     * @return SuggestCode
     */
    public function findOne($id, $cssSelector)
    {
        $suggestCode = $this->apiGet('/' . $id, ['cssselector' => $cssSelector])->json();

        return $this->transformer->transformItem($suggestCode);
    }

    /**
     * @{inheritedDoc}
     */
    protected function getRootUrl()
    {
        return self::SUGGEST_CODE_URL;
    }
}
