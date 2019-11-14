<?php
namespace Codervex;

class HmacSha1 extends SignatureMethod
{
    public function getName()
    {
        return "HMAC-SHA1";
    }

    public function buildSignature(Request $request, Consumer $consumer, Token $token = null)
    {
        $signatureBase = $request->getSignatureBaseString();
        $request->baseString = $signatureBase;

        $parts = array($consumer->secret, null !== $token ? $token->secret : "");

        $parts = Util::urlencodeRfc3986($parts);
        $key = implode('&', $parts);

        return base64_encode(hash_hmac('sha1', $signatureBase, $key, true));
    }
}
